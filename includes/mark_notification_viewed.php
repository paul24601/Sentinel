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

// Handle both JSON and form data
$notificationId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['notification_id'])) {
        // Form data
        $notificationId = intval($_POST['notification_id']);
    } else {
        // JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['notification_id'])) {
            $notificationId = intval($input['notification_id']);
        }
    }
}

if (!$notificationId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing notification ID']);
    exit();
}

$userName = $_SESSION['full_name'];

// Mark notification as viewed
if (markNotificationAsViewed($notificationId, $userName)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to mark notification as viewed']);
}
?>
