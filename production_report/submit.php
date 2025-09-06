<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Include database connection
require_once __DIR__ . '/../includes/database.php';

// Debug: Log the POST data
error_log("POST Data: " . print_r($_POST, true));

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Validate required fields based on report type
    $reportType = $_POST['reportType'] ?? '';
    $required_fields = ['reportType', 'date', 'shift', 'shiftHours', 'productName', 'color', 'partNo'];
    
    if ($reportType === 'injection') {
        $required_fields[] = 'machine';
        $required_fields[] = 'robotArm';
    } else {
        $required_fields[] = 'assemblyLine';
        $required_fields[] = 'manpower'; // Only required for finishing reports
    }
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Required field missing: $field");
        }
    }

    // Insert main report data
    $sql = "INSERT INTO production_reports (
        report_type, report_date, shift, shift_hours, 
        product_name, color, part_no,
        id_number1, id_number2, id_number3, ejo_number,
        assembly_line, machine, robot_arm, manpower, reg, ot,
        total_reject, total_good,
        injection_pressure, molding_temp, cycle_time, cavity_count,
        cooling_time, holding_pressure, material_type, shot_size,
        finishing_process, station_number, work_order, finishing_tools, quality_standard,
        remarks, created_by, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Assign variables for binding (required for PHP)
    $reportType = $_POST['reportType'];
    $date = $_POST['date'];
    $shift = $_POST['shift'];
    $shiftHours = $_POST['shiftHours'];
    $productName = $_POST['productName'];
    $color = $_POST['color'];
    $partNo = $_POST['partNo'];
    $idNumber1 = $_POST['idNumber1'] ?? null;
    $idNumber2 = $_POST['idNumber2'] ?? null;
    $idNumber3 = $_POST['idNumber3'] ?? null;
    $ejo = $_POST['ejo'] ?? null;
    $assemblyLine = $_POST['assemblyLine'] ?? null;
    $machine = $_POST['machine'] ?? null;
    $robotArm = $_POST['robotArm'] ?? null;
    $manpower = $_POST['manpower'] ?? null;
    $reg = $_POST['reg'] ?? null;
    $ot = $_POST['ot'] ?? null;
    $totalRejectSum = $_POST['totalRejectSum'] ?? 0;
    $totalGoodSum = $_POST['totalGoodSum'] ?? 0;
    $injectionPressure = $_POST['injectionPressure'] ?? null;
    $moldingTemp = $_POST['moldingTemp'] ?? null;
    $cycleTime = $_POST['cycleTime'] ?? null;
    $cavityCount = $_POST['cavityCount'] ?? null;
    $coolingTime = $_POST['coolingTime'] ?? null;
    $holdingPressure = $_POST['holdingPressure'] ?? null;
    $materialType = $_POST['materialType'] ?? null;
    $shotSize = $_POST['shotSize'] ?? null;
    $finishingProcess = $_POST['finishingProcess'] ?? null;
    $stationNumber = $_POST['stationNumber'] ?? null;
    $workOrder = $_POST['workOrder'] ?? null;
    $finishingTools = $_POST['finishingTools'] ?? null;
    $qualityStandard = $_POST['qualityStandard'] ?? null;
    $remarks = $_POST['remarks'] ?? null;
    $userId = $_SESSION['id_number'];

    $stmt->bind_param(
        "ssssssssssssssissiiddddddsdsssssss",
        $reportType, $date, $shift, $shiftHours,
        $productName, $color, $partNo,
        $idNumber1, $idNumber2, $idNumber3, $ejo,
        $assemblyLine, $machine, $robotArm, $manpower, $reg, $ot,
        $totalRejectSum, $totalGoodSum,
        $injectionPressure, $moldingTemp, $cycleTime, $cavityCount,
        $coolingTime, $holdingPressure, $materialType, $shotSize,
        $finishingProcess, $stationNumber, $workOrder, $finishingTools, $qualityStandard,
        $remarks, $userId
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $report_id = $conn->insert_id;

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Production report saved successfully',
        'report_id' => $report_id
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($conn) && $conn->ping()) {
        $conn->rollback();
    }
    
    // Log the error
    error_log("Production report error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    echo json_encode([
        'success' => false,
        'message' => 'Error saving production report: ' . $e->getMessage(),
        'debug' => [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}

// Close connection
if (isset($conn)) {
    $conn->close();
} 