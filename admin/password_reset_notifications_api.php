<?php
session_start();
header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "admin_sentinel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Include notification functions
require_once 'password_reset_notifications.php';

try {
    $pendingCount = getPendingPasswordResetCount($conn);
    $pendingRequests = getPendingPasswordResetRequests($conn, 10);
    
    echo json_encode([
        'success' => true,
        'count' => $pendingCount,
        'requests' => $pendingRequests
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch notifications']);
}

$conn->close();
?>
