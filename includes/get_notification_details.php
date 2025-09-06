<?php
session_start();
require_once 'admin_notifications.php';

header('Content-Type: application/json');

if (!isset($_SESSION['full_name']) || !isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    $notificationId = (int)$_GET['id'];
    $userName = $_SESSION['full_name'];
    
    // Get notification details using existing function
    $conn = getAdminDbConnection();
    
    $sql = "SELECT an.*, u.full_name as created_by_name
            FROM admin_notifications an
            LEFT JOIN users u ON an.created_by = u.full_name
            WHERE an.id = ? 
            AND an.is_active = 1
            AND (an.target_roles IS NULL OR JSON_CONTAINS(an.target_roles, ?) OR JSON_CONTAINS(an.target_roles, '\"all\"'))";
    
    $stmt = $conn->prepare($sql);
    $userRole = json_encode($_SESSION['role'] ?? 'user');
    $stmt->bind_param("is", $notificationId, $userRole);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($notification = $result->fetch_assoc()) {
        // Format the response
        $response = [
            'success' => true,
            'notification' => [
                'id' => $notification['id'],
                'title' => htmlspecialchars($notification['title']),
                'message' => htmlspecialchars($notification['message']),
                'notification_type' => $notification['notification_type'],
                'is_urgent' => (bool)$notification['is_urgent'],
                'created_at' => $notification['created_at'],
                'created_by' => $notification['created_by_name'] ?? $notification['created_by'] ?? 'System'
            ]
        ];
        
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Notification not found']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching notification details']);
}
?>
