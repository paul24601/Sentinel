<?php
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

require_once 'session_config.php';

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html");
    exit();
}

// Load the centralized configuration
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Get database connection using the centralized system
try {
    $conn = DatabaseManager::getConnection('sentinel_main');
    // Set MySQL timezone to Philippine Time (UTC+8)
    $conn->query("SET time_zone = '+08:00'");
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchData($conn, $tableName)
{
    $sql = "SELECT * FROM $tableName";
    return $conn->query($sql);
}

// Fetch master records
$sql = "SELECT pr.* 
        FROM parameter_records pr
        ORDER BY pr.submission_date DESC";
$parameterRecords = $conn->query($sql);
