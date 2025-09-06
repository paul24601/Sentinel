<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    header("Location: ../login.html");
    exit();
}

// Include database connection
require_once __DIR__ . '/../includes/database.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    
    // Handle search and filtering (same logic as records.php)
    $search = $_GET['search'] ?? '';
    $report_type = $_GET['report_type'] ?? '';
    $date_from = $_GET['date_from'] ?? '';
    $date_to = $_GET['date_to'] ?? '';
    
    // Build WHERE clause
    $where_conditions = [];
    $params = [];
    $types = '';
    
    if (!empty($search)) {
        $where_conditions[] = "(product_name LIKE ? OR part_no LIKE ? OR assembly_line LIKE ? OR machine LIKE ?)";
        $search_param = "%$search%";
        $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
        $types .= 'ssss';
    }
    
    if (!empty($report_type)) {
        $where_conditions[] = "report_type = ?";
        $params[] = $report_type;
        $types .= 's';
    }
    
    if (!empty($date_from)) {
        $where_conditions[] = "report_date >= ?";
        $params[] = $date_from;
        $types .= 's';
    }
    
    if (!empty($date_to)) {
        $where_conditions[] = "report_date <= ?";
        $params[] = $date_to;
        $types .= 's';
    }
    
    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
    
    // Fetch all matching records
    $sql = "SELECT * FROM production_reports $where_clause ORDER BY report_date DESC, created_at DESC";
    
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }
    
    // Set headers for Excel download
    $filename = "Production_Reports_" . date('Y-m-d_H-i-s') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    // Start Excel content
    echo "<table border='1'>";
    echo "<tr style='background-color: #4CAF50; color: white; font-weight: bold;'>";
    echo "<th>Report ID</th>";
    echo "<th>Type</th>";
    echo "<th>Date</th>";
    echo "<th>Shift</th>";
    echo "<th>Product Name</th>";
    echo "<th>Color</th>";
    echo "<th>Part No</th>";
    echo "<th>Assembly Line</th>";
    echo "<th>Machine</th>";
    echo "<th>Robot Arm</th>";
    echo "<th>Manpower</th>";
    echo "<th>REG Hours</th>";
    echo "<th>OT Hours</th>";
    echo "<th>Total Good</th>";
    echo "<th>Total Reject</th>";
    echo "<th>Total Produced</th>";
    echo "<th>Efficiency %</th>";
    echo "<th>Injection Pressure</th>";
    echo "<th>Molding Temp</th>";
    echo "<th>Cycle Time</th>";
    echo "<th>Cavity Count</th>";
    echo "<th>Cooling Time</th>";
    echo "<th>Holding Pressure</th>";
    echo "<th>Material Type</th>";
    echo "<th>Shot Size</th>";
    echo "<th>Finishing Process</th>";
    echo "<th>Station Number</th>";
    echo "<th>Work Order</th>";
    echo "<th>Tools/Equipment</th>";
    echo "<th>Quality Standard</th>";
    echo "<th>ID Number 1</th>";
    echo "<th>ID Number 2</th>";
    echo "<th>ID Number 3</th>";
    echo "<th>EJO Number</th>";
    echo "<th>Remarks</th>";
    echo "<th>Created By</th>";
    echo "<th>Created At</th>";
    echo "</tr>";
    
    while ($row = $result->fetch_assoc()) {
        $total_produced = $row['total_good'] + $row['total_reject'];
        $efficiency = $total_produced > 0 ? round(($row['total_good'] / $total_produced) * 100, 2) : 0;
        
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . ucfirst($row['report_type']) . "</td>";
        echo "<td>" . date('Y-m-d', strtotime($row['report_date'])) . "</td>";
        echo "<td>" . $row['shift'] . "</td>";
        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['color']) . "</td>";
        echo "<td>" . htmlspecialchars($row['part_no']) . "</td>";
        echo "<td>" . ($row['assembly_line'] ?? '') . "</td>";
        echo "<td>" . ($row['machine'] ?? '') . "</td>";
        echo "<td>" . ($row['robot_arm'] ?? '') . "</td>";
        echo "<td>" . ($row['manpower'] ?? '') . "</td>";
        echo "<td>" . ($row['reg'] ?? '') . "</td>";
        echo "<td>" . ($row['ot'] ?? '') . "</td>";
        echo "<td>" . $row['total_good'] . "</td>";
        echo "<td>" . $row['total_reject'] . "</td>";
        echo "<td>" . $total_produced . "</td>";
        echo "<td>" . $efficiency . "%</td>";
        echo "<td>" . ($row['injection_pressure'] ?? '') . "</td>";
        echo "<td>" . ($row['molding_temp'] ?? '') . "</td>";
        echo "<td>" . ($row['cycle_time'] ?? '') . "</td>";
        echo "<td>" . ($row['cavity_count'] ?? '') . "</td>";
        echo "<td>" . ($row['cooling_time'] ?? '') . "</td>";
        echo "<td>" . ($row['holding_pressure'] ?? '') . "</td>";
        echo "<td>" . ($row['material_type'] ?? '') . "</td>";
        echo "<td>" . ($row['shot_size'] ?? '') . "</td>";
        echo "<td>" . ($row['finishing_process'] ?? '') . "</td>";
        echo "<td>" . ($row['station_number'] ?? '') . "</td>";
        echo "<td>" . ($row['work_order'] ?? '') . "</td>";
        echo "<td>" . ($row['finishing_tools'] ?? '') . "</td>";
        echo "<td>" . ($row['quality_standard'] ?? '') . "</td>";
        echo "<td>" . ($row['id_number1'] ?? '') . "</td>";
        echo "<td>" . ($row['id_number2'] ?? '') . "</td>";
        echo "<td>" . ($row['id_number3'] ?? '') . "</td>";
        echo "<td>" . ($row['ejo_number'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['remarks'] ?? '') . "</td>";
        echo "<td>" . $row['created_by'] . "</td>";
        echo "<td>" . date('Y-m-d H:i:s', strtotime($row['created_at'])) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (Exception $e) {
    header("Location: records.php?error=" . urlencode("Export failed: " . $e->getMessage()));
    exit();
}
?>
