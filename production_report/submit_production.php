<?php
// PRODUCTION-SAFE SUBMIT HANDLER
// Handles both local and production environments

// Start output buffering to prevent any accidental output
ob_start();

// Basic error handling - don't display errors in production
$is_production = (strpos($_SERVER['HTTP_HOST'] ?? '', 'mpinternal.xyz') !== false);

if ($is_production) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear any buffered output
ob_clean();

// Set JSON header
header('Content-Type: application/json');

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Check authentication
    if (!isset($_SESSION['id_number']) || empty($_SESSION['id_number'])) {
        throw new Exception('Authentication required');
    }

    // Include database connection with error handling
    $db_path = __DIR__ . '/../includes/database.php';
    if (!file_exists($db_path)) {
        throw new Exception('Database configuration not found');
    }
    
    require_once $db_path;

    // Validate required fields
    $required = ['date', 'shift', 'shiftHours', 'productName', 'color', 'partNo'];
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            throw new Exception("Field '{$field}' is required");
        }
    }

    // Get database connection
    $conn = DatabaseManager::getConnection('sentinel_production');
    
    // Start transaction
    $conn->autocommit(false);

    // Prepare insert statement
    $sql = "INSERT INTO production_reports (
        plant, report_date, shift, shift_hours, 
        product_name, color, part_no,
        id_number1, id_number2, id_number3, ejo_number,
        assembly_line, manpower, reg, ot,
        total_reject, total_good, remarks, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }

    // Bind parameters
    $plant = $_POST['plant'] ?? 'Plant A';
    $date = $_POST['date'];
    $shift = $_POST['shift'];
    $shiftHours = $_POST['shiftHours'];
    $productName = $_POST['productName'];
    $color = $_POST['color'];
    $partNo = $_POST['partNo'];
    $idNumber1 = $_POST['idNumber1'] ?? '';
    $idNumber2 = $_POST['idNumber2'] ?? '';
    $idNumber3 = $_POST['idNumber3'] ?? '';
    $ejo = $_POST['ejo'] ?? '';
    $assemblyLine = $_POST['assemblyLine'] ?? '';
    $manpower = (int)($_POST['manpower'] ?? 0);
    $reg = $_POST['reg'] ?? '';
    $ot = $_POST['ot'] ?? '';
    $totalReject = (int)($_POST['totalRejectSum'] ?? 0);
    $totalGood = (int)($_POST['totalGoodSum'] ?? 0);
    $remarks = $_POST['remarks'] ?? '';
    $createdBy = $_SESSION['id_number'];

    $stmt->bind_param('ssssssssssssisssss',
        $plant, $date, $shift, $shiftHours,
        $productName, $color, $partNo,
        $idNumber1, $idNumber2, $idNumber3, $ejo,
        $assemblyLine, $manpower, $reg, $ot,
        $totalReject, $totalGood, $remarks, $createdBy
    );

    // Execute
    if (!$stmt->execute()) {
        throw new Exception('Database execute error: ' . $stmt->error);
    }

    $reportId = $conn->insert_id;
    
    // Commit transaction
    $conn->commit();

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Production report submitted successfully!',
        'report_id' => $reportId,
        'product_name' => $productName,
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    // Rollback on error
    if (isset($conn)) {
        $conn->rollback();
    }

    // Log error
    error_log('Production Report Error: ' . $e->getMessage());

    // Return error response
    $errorResponse = [
        'success' => false,
        'message' => 'Unable to submit production report. Please try again.',
        'error_code' => 'SUBMIT_ERROR'
    ];

    // Add debug info for non-production
    if (!$is_production) {
        $errorResponse['debug'] = $e->getMessage();
        $errorResponse['trace'] = $e->getTraceAsString();
    }

    echo json_encode($errorResponse);

} finally {
    // Clean up
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}

// End output buffering
ob_end_flush();
?>
