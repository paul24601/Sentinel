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

    // Validate required fields
    $required_fields = ['plant', 'date', 'shift', 'shiftHours', 'productName', 'color', 'partNo', 'assemblyLine', 'manpower'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Required field missing: $field");
        }
    }

    // Insert main report data
    $sql = "INSERT INTO production_reports (
        plant, report_date, shift, shift_hours, 
        product_name, color, part_no,
        id_number1, id_number2, id_number3, ejo_number,
        assembly_line, manpower, reg, ot,
        total_reject, total_good,
        remarks, created_by, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssssssissiiisi",
        $_POST['plant'],
        $_POST['date'],
        $_POST['shift'],
        $_POST['shiftHours'],
        $_POST['productName'],
        $_POST['color'],
        $_POST['partNo'],
        $_POST['idNumber1'],
        $_POST['idNumber2'],
        $_POST['idNumber3'],
        $_POST['ejo'],
        $_POST['assemblyLine'],
        $_POST['manpower'],
        $_POST['reg'],
        $_POST['ot'],
        $_POST['totalRejectSum'],
        $_POST['totalGoodSum'],
        $_POST['remarks'],
        $_SESSION['user_id']
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

            $stmt->bind_param(
                "issiiiiiiiii",
                $report_id,
                $_POST['partName'][$i],
                $_POST['defect'][$i],
                $_POST['time1'][$i] ?? 0,
                $_POST['time2'][$i] ?? 0,
                $_POST['time3'][$i] ?? 0,
                $_POST['time4'][$i] ?? 0,
                $_POST['time5'][$i] ?? 0,
                $_POST['time6'][$i] ?? 0,
                $_POST['time7'][$i] ?? 0,
                $_POST['time8'][$i] ?? 0,
                $_POST['total'][$i] ?? 0
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

            $stmt->bind_param(
                "isi",
                $report_id,
                $_POST['downtimeDesc'][$i],
                $_POST['downtimeMinutes'][$i] ?? 0
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