<?php
session_start();

header('Content-Type: application/json');

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Include database connection
require_once __DIR__ . '/../includes/database.php';

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_production');
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Validate required fields
    $required_fields = ['date', 'shift', 'shiftHours', 'productName', 'color', 'partNo'];
    
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
        total_reject, total_good, remarks, 
        created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Assign variables for binding
    $plant = $_POST['plant'] ?? 'Plant A';
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
    $manpower = $_POST['manpower'] ?? 0;
    $reg = $_POST['reg'] ?? null;
    $ot = $_POST['ot'] ?? null;
    $totalRejectSum = $_POST['totalRejectSum'] ?? 0;
    $totalGoodSum = $_POST['totalGoodSum'] ?? 0;
    $remarks = $_POST['remarks'] ?? null;
    $userId = $_SESSION['id_number'];

    $stmt->bind_param(
        "ssssssssssssisssss",
        $plant, $date, $shift, $shiftHours,
        $productName, $color, $partNo,
        $idNumber1, $idNumber2, $idNumber3, $ejo,
        $assemblyLine, $manpower, $reg, $ot,
        $totalRejectSum, $totalGoodSum, $remarks, $userId
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $report_id = $conn->insert_id;

    // Commit transaction
    $conn->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Production report submitted successfully!',
        'report_id' => $report_id,
        'product_name' => $productName,
        'plant' => $plant,
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($conn) && method_exists($conn, 'rollback')) {
        $conn->rollback();
    }
    
    // Log the error
    error_log("Production report error: " . $e->getMessage());
    
    // Provide user-friendly error messages
    $userMessage = 'An error occurred while saving the production report.';
    
    // Check for common error types
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        $userMessage = 'A report with this information already exists. Please check your data and try again.';
    } elseif (strpos($e->getMessage(), 'Data too long') !== false) {
        $userMessage = 'One or more fields contain too much data. Please reduce the length and try again.';
    } elseif (strpos($e->getMessage(), 'Connection') !== false) {
        $userMessage = 'Database connection error. Please try again in a moment.';
    } elseif (strpos($e->getMessage(), 'Required field missing') !== false) {
        $userMessage = $e->getMessage();
    }
    
    echo json_encode([
        'success' => false,
        'message' => $userMessage,
        'error_code' => 'SUBMISSION_ERROR',
        'debug' => $e->getMessage(), // Add debug info
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
