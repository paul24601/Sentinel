<?php
// MINIMAL SUBMIT HANDLER FOR DEBUGGING
// This will help identify exactly what's causing the 500 error

ob_start();

// Basic error handling
$is_production = (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'mpinternal.xyz') !== false);

if ($is_production) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

ob_clean();
header('Content-Type: application/json');

try {
    // Step 1: Check session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['id_number'])) {
        throw new Exception('Not logged in');
    }

    // Step 2: Check POST method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid method');
    }

    // Step 3: Check required fields
    $required = ['productName', 'color', 'partNo', 'date', 'shift', 'shiftHours'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing: $field");
        }
    }

    // Step 4: Include database file
    $db_file = __DIR__ . '/../includes/database.php';
    if (!file_exists($db_file)) {
        throw new Exception('Database file missing');
    }
    require_once $db_file;

    // Step 5: Get connection
    $conn = DatabaseManager::getConnection('sentinel_production');
    if (!$conn) {
        throw new Exception('DB connection failed');
    }

    // Step 6: Simple insert
    $sql = "INSERT INTO production_reports (
        product_name, color, part_no, report_date, shift, shift_hours, 
        plant, assembly_line, manpower, total_reject, total_good, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    $plant = $_POST['plant'] ?? 'Plant A';
    $assemblyLine = $_POST['assemblyLine'] ?? '';
    $manpower = (int)($_POST['manpower'] ?? 0);
    $totalReject = (int)($_POST['totalRejectSum'] ?? 0);
    $totalGood = (int)($_POST['totalGoodSum'] ?? 0);

    $stmt->bind_param('ssssssssiiis',
        $_POST['productName'],
        $_POST['color'], 
        $_POST['partNo'],
        $_POST['date'],
        $_POST['shift'],
        $_POST['shiftHours'],
        $plant,
        $assemblyLine,
        $manpower,
        $totalReject,
        $totalGood,
        $_SESSION['id_number']
    );

    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Report submitted successfully!',
        'report_id' => $conn->insert_id
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'session_exists' => isset($_SESSION['id_number']),
            'post_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
            'is_production' => $is_production
        ]
    ]);
}

ob_end_flush();
?>