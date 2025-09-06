<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    header("Location: ../login.html");
    exit();
}

// Include database connection
require_once __DIR__ . '/../includes/database.php';

// Get record ID
$id = $_GET['id'] ?? 0;

if (!$id) {
    header("Location: records.php");
    exit();
}

try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    
    // Fetch the specific record
    $sql = "SELECT * FROM production_reports WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: records.php?error=Record not found");
        exit();
    }
    
    $record = $result->fetch_assoc();
    
    // Set headers for Excel download
    $filename = "Production_Report_" . $record['id'] . "_" . date('Y-m-d_H-i-s') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    // Start Excel content
    echo "<table border='1'>";
    echo "<tr><th colspan='2' style='background-color: #4CAF50; color: white; font-size: 16px; text-align: center;'>Production Report #" . $record['id'] . "</th></tr>";
    
    // Basic Information
    echo "<tr><th colspan='2' style='background-color: #2196F3; color: white;'>Basic Information</th></tr>";
    echo "<tr><td><strong>Report Type</strong></td><td>" . ucfirst($record['report_type']) . "</td></tr>";
    echo "<tr><td><strong>Date</strong></td><td>" . date('F d, Y', strtotime($record['report_date'])) . "</td></tr>";
    echo "<tr><td><strong>Shift</strong></td><td>" . $record['shift'] . "</td></tr>";
    echo "<tr><td><strong>Shift Hours</strong></td><td>" . $record['shift_hours'] . "</td></tr>";
    echo "<tr><td><strong>Created By</strong></td><td>" . $record['created_by'] . "</td></tr>";
    echo "<tr><td><strong>Created At</strong></td><td>" . date('M d, Y g:i A', strtotime($record['created_at'])) . "</td></tr>";
    
    // Product Information
    echo "<tr><th colspan='2' style='background-color: #2196F3; color: white;'>Product Information</th></tr>";
    echo "<tr><td><strong>Product Name</strong></td><td>" . $record['product_name'] . "</td></tr>";
    echo "<tr><td><strong>Color</strong></td><td>" . $record['color'] . "</td></tr>";
    echo "<tr><td><strong>Part Number</strong></td><td>" . $record['part_no'] . "</td></tr>";
    
    // ID Information
    if (!empty($record['id_number1']) || !empty($record['id_number2']) || !empty($record['id_number3']) || !empty($record['ejo_number'])) {
        echo "<tr><th colspan='2' style='background-color: #2196F3; color: white;'>ID Information</th></tr>";
        if (!empty($record['id_number1'])) echo "<tr><td><strong>ID Number 1</strong></td><td>" . $record['id_number1'] . "</td></tr>";
        if (!empty($record['id_number2'])) echo "<tr><td><strong>ID Number 2</strong></td><td>" . $record['id_number2'] . "</td></tr>";
        if (!empty($record['id_number3'])) echo "<tr><td><strong>ID Number 3</strong></td><td>" . $record['id_number3'] . "</td></tr>";
        if (!empty($record['ejo_number'])) echo "<tr><td><strong>EJO Number</strong></td><td>" . $record['ejo_number'] . "</td></tr>";
    }
    
    // Type-specific information
    if ($record['report_type'] == 'finishing') {
        echo "<tr><th colspan='2' style='background-color: #2196F3; color: white;'>Finishing Information</th></tr>";
        if (!empty($record['assembly_line'])) echo "<tr><td><strong>Assembly Line</strong></td><td>" . $record['assembly_line'] . "</td></tr>";
        if (!empty($record['manpower'])) echo "<tr><td><strong>Manpower</strong></td><td>" . $record['manpower'] . "</td></tr>";
        if (!empty($record['finishing_process'])) echo "<tr><td><strong>Finishing Process</strong></td><td>" . $record['finishing_process'] . "</td></tr>";
        if (!empty($record['station_number'])) echo "<tr><td><strong>Station Number</strong></td><td>" . $record['station_number'] . "</td></tr>";
        if (!empty($record['work_order'])) echo "<tr><td><strong>Work Order</strong></td><td>" . $record['work_order'] . "</td></tr>";
        if (!empty($record['finishing_tools'])) echo "<tr><td><strong>Tools/Equipment</strong></td><td>" . $record['finishing_tools'] . "</td></tr>";
        if (!empty($record['quality_standard'])) echo "<tr><td><strong>Quality Standard</strong></td><td>" . $record['quality_standard'] . "</td></tr>";
    } else {
        echo "<tr><th colspan='2' style='background-color: #2196F3; color: white;'>Injection Molding Information</th></tr>";
        if (!empty($record['machine'])) echo "<tr><td><strong>Machine</strong></td><td>" . $record['machine'] . "</td></tr>";
        if (!empty($record['robot_arm'])) echo "<tr><td><strong>Robot Arm</strong></td><td>" . $record['robot_arm'] . "</td></tr>";
        if (!empty($record['injection_pressure'])) echo "<tr><td><strong>Injection Pressure</strong></td><td>" . $record['injection_pressure'] . " MPa</td></tr>";
        if (!empty($record['molding_temp'])) echo "<tr><td><strong>Molding Temperature</strong></td><td>" . $record['molding_temp'] . " °C</td></tr>";
        if (!empty($record['cycle_time'])) echo "<tr><td><strong>Cycle Time</strong></td><td>" . $record['cycle_time'] . " seconds</td></tr>";
        if (!empty($record['cavity_count'])) echo "<tr><td><strong>Cavity Count</strong></td><td>" . $record['cavity_count'] . "</td></tr>";
        if (!empty($record['cooling_time'])) echo "<tr><td><strong>Cooling Time</strong></td><td>" . $record['cooling_time'] . " seconds</td></tr>";
        if (!empty($record['holding_pressure'])) echo "<tr><td><strong>Holding Pressure</strong></td><td>" . $record['holding_pressure'] . " MPa</td></tr>";
        if (!empty($record['material_type'])) echo "<tr><td><strong>Material Type</strong></td><td>" . $record['material_type'] . "</td></tr>";
        if (!empty($record['shot_size'])) echo "<tr><td><strong>Shot Size</strong></td><td>" . $record['shot_size'] . " g</td></tr>";
    }
    
    // Production Summary
    echo "<tr><th colspan='2' style='background-color: #2196F3; color: white;'>Production Summary</th></tr>";
    if (!empty($record['reg'])) echo "<tr><td><strong>Regular Hours</strong></td><td>" . $record['reg'] . "</td></tr>";
    if (!empty($record['ot'])) echo "<tr><td><strong>Overtime Hours</strong></td><td>" . $record['ot'] . "</td></tr>";
    echo "<tr><td><strong>Total Good</strong></td><td>" . $record['total_good'] . "</td></tr>";
    echo "<tr><td><strong>Total Reject</strong></td><td>" . $record['total_reject'] . "</td></tr>";
    $total_produced = $record['total_good'] + $record['total_reject'];
    echo "<tr><td><strong>Total Produced</strong></td><td>" . $total_produced . "</td></tr>";
    if ($total_produced > 0) {
        $efficiency = round(($record['total_good'] / $total_produced) * 100, 2);
        echo "<tr><td><strong>Efficiency</strong></td><td>" . $efficiency . "%</td></tr>";
    }
    
    // Remarks
    if (!empty($record['remarks'])) {
        echo "<tr><th colspan='2' style='background-color: #2196F3; color: white;'>Remarks</th></tr>";
        echo "<tr><td colspan='2'>" . nl2br(htmlspecialchars($record['remarks'])) . "</td></tr>";
    }
    
    echo "</table>";
    
} catch (Exception $e) {
    header("Location: records.php?error=" . urlencode("Export failed: " . $e->getMessage()));
    exit();
}
?>
