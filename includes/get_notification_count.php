<?php
session_start();
require_once 'admin_notifications.php';

header('Content-Type: application/json');

if (!isset($_SESSION['full_name'])) {
    echo json_encode(['success' => false, 'count' => 0]);
    exit;
}

try {
    $userName = $_SESSION['full_name'];
    $userRole = $_SESSION['role'] ?? 'user';
    
    // Use existing function to get unread count
    $count = getUnviewedNotificationCount($userRole, $userName);
    
    echo json_encode([
        'success' => true,
        'count' => $count
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'count' => 0]);
}
?>
