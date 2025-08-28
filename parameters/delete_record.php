<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Check if record_id is provided
if (!isset($_GET['record_id']) || empty($_GET['record_id'])) {
    $_SESSION['error_message'] = "No record ID provided for deletion.";
    header("Location: submission.php");
    exit();
}

$record_id = $_GET['record_id'];

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_main');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start transaction
$conn->begin_transaction();

try {
    // Option 1: Soft delete - update status in parameter_records table
    $sql = "UPDATE parameter_records SET status = 'deleted' WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $record_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to mark record as deleted: " . $stmt->error);
    }
    
    /* Option 2: Hard delete - Remove data from all tables
    // Define all tables that have record_id
    $tables = [
        'parameter_records',
        'productmachineinfo',
        'productdetails',
        'materialcomposition',
        'colorantdetails',
        'moldoperationspecs',
        'timerparameters',
        'barrelheatertemperatures',
        'moldheatertemperatures',
        'plasticizingparameters',
        'injectionparameters',
        'ejectionparameters',
        'corepullsettings',
        'additionalinformation',
        'personnel'
    ];
    
    // Delete files associated with this record
    $sql = "SELECT FilePath FROM attachments WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        if (file_exists($row['FilePath'])) {
            unlink($row['FilePath']);
        }
    }
    
    // Add attachments to the tables array
    $tables[] = 'attachments';
    
    // Delete from all tables
    foreach ($tables as $table) {
        $sql = "DELETE FROM $table WHERE record_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $record_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error deleting from $table: " . $stmt->error);
        }
    }
    */
    
    // Commit transaction
    $conn->commit();
    $_SESSION['success_message'] = "Record successfully marked as deleted.";
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
}

$conn->close();
header("Location: submission.php");
exit(); 