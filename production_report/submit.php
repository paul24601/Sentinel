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
require_once __DIR__ . '/../includes/db_connect.php';

// Debug: Log the POST data
error_log("POST Data: " . print_r($_POST, true));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Validate required fields based on report type
    $reportType = $_POST['reportType'] ?? '';
    $required_fields = ['reportType', 'date', 'shift', 'shiftHours', 'productName', 'color', 'partNo', 'manpower'];
    
    if ($reportType === 'injection') {
        $required_fields[] = 'machine';
        $required_fields[] = 'robotArm';
    } else {
        $required_fields[] = 'assemblyLine';
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
    $manpower = $_POST['manpower'];
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
    $userId = $_SESSION['user_id'];

    $stmt->bind_param(
        "sssssssssssssssissiiddddsdssssssi",
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

    // Insert quality control entries
    if (isset($_POST['partName']) && is_array($_POST['partName'])) {
        $sql = "INSERT INTO quality_control_entries (
            report_id, part_name, defect,
            time1, time2, time3, time4,
            time5, time6, time7, time8,
            total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed for quality entries: " . $conn->error);
        }

        for ($i = 0; $i < count($_POST['partName']); $i++) {
            if (empty($_POST['partName'][$i]) && empty($_POST['defect'][$i])) {
                continue; // Skip empty rows
            }

            // Assign variables for binding
            $partName = $_POST['partName'][$i];
            $defect = $_POST['defect'][$i];
            $time1 = $_POST['time1'][$i] ?? 0;
            $time2 = $_POST['time2'][$i] ?? 0;
            $time3 = $_POST['time3'][$i] ?? 0;
            $time4 = $_POST['time4'][$i] ?? 0;
            $time5 = $_POST['time5'][$i] ?? 0;
            $time6 = $_POST['time6'][$i] ?? 0;
            $time7 = $_POST['time7'][$i] ?? 0;
            $time8 = $_POST['time8'][$i] ?? 0;
            $total = $_POST['total'][$i] ?? 0;

            $stmt->bind_param(
                "issiiiiiiiii",
                $report_id,
                $partName, $defect,
                $time1, $time2, $time3, $time4,
                $time5, $time6, $time7, $time8,
                $total
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed for quality entry: " . $stmt->error);
            }
        }
    }

    // Insert downtime entries
    if (isset($_POST['downtimeDesc']) && is_array($_POST['downtimeDesc'])) {
        $sql = "INSERT INTO downtime_entries (
            report_id, description, minutes
        ) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed for downtime entries: " . $conn->error);
        }

        for ($i = 0; $i < count($_POST['downtimeDesc']); $i++) {
            if (empty($_POST['downtimeDesc'][$i])) {
                continue; // Skip empty rows
            }

            // Assign variables for binding
            $downtimeDesc = $_POST['downtimeDesc'][$i];
            $downtimeMinutes = $_POST['downtimeMinutes'][$i] ?? 0;

            $stmt->bind_param(
                "isi",
                $report_id,
                $downtimeDesc,
                $downtimeMinutes
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed for downtime entry: " . $stmt->error);
            }
        }
    }

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