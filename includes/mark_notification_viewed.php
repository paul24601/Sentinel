<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

// Load required files
require_once __DIR__ . '/admin_notifications.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['notification_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing notification ID']);
    exit();
}

$notificationId = intval($input['notification_id']);
$userName = $_SESSION['full_name'];

// Mark notification as viewed
if (markNotificationAsViewed($notificationId, $userName)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to mark notification as viewed']);
}
?>
