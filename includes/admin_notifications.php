<?php
// Admin Notification System Functions
// Created: August 20, 2025

/**
 * Get admin database connection
 */
function getAdminDbConnection() {
    require_once __DIR__ . '/database.php';
    return DatabaseManager::getConnection('sentinel_admin');
}

/**
 * Get active notifications for a specific user role
 */
function getAdminNotifications($userRole, $userName, $limit = 10) {
    $conn = getAdminDbConnection();
    $notifications = [];
    
    // Get notifications that are active, not expired, and target the user's role
    $sql = "SELECT id, title, message, notification_type, is_urgent, created_by, created_at, expires_at
            FROM admin_notifications 
            WHERE is_active = TRUE 
            AND (expires_at IS NULL OR expires_at > NOW())
            AND (target_roles IS NULL OR JSON_CONTAINS(target_roles, ?) OR JSON_CONTAINS(target_roles, '\"all\"'))
            ORDER BY is_urgent DESC, created_at DESC 
            LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $roleJson = json_encode($userRole);
    $stmt->bind_param("si", $roleJson, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Check if user has viewed this notification
        $viewSql = "SELECT id FROM notification_views WHERE notification_id = ? AND user_name = ?";
        $viewStmt = $conn->prepare($viewSql);
        $viewStmt->bind_param("is", $row['id'], $userName);
        $viewStmt->execute();
        $viewResult = $viewStmt->get_result();
        
        $row['is_viewed'] = $viewResult->num_rows > 0;
        $notifications[] = $row;
    }
    
    return $notifications;
}

/**
 * Mark notification as viewed by user
 */
function markNotificationAsViewed($notificationId, $userName) {
    $conn = getAdminDbConnection();
    $sql = "INSERT IGNORE INTO notification_views (notification_id, user_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $notificationId, $userName);
    return $stmt->execute();
}

/**
 * Get count of unviewed notifications for a user
 */
function getUnviewedNotificationCount($userRole, $userName) {
    $conn = getAdminDbConnection();
    $sql = "SELECT COUNT(*) as count
            FROM admin_notifications an
            LEFT JOIN notification_views nv ON an.id = nv.notification_id AND nv.user_name = ?
            WHERE an.is_active = TRUE 
            AND (an.expires_at IS NULL OR an.expires_at > NOW())
            AND (an.target_roles IS NULL OR JSON_CONTAINS(an.target_roles, ?) OR JSON_CONTAINS(an.target_roles, '\"all\"'))
            AND nv.id IS NULL";
    
    $stmt = $conn->prepare($sql);
    $roleJson = json_encode($userRole);
    $stmt->bind_param("ss", $userName, $roleJson);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['count'];
    }
    return 0;
}

/**
 * Create new admin notification
 */
function createAdminNotification($title, $message, $type, $targetRoles, $isUrgent, $expiresAt, $createdBy) {
    $conn = getAdminDbConnection();
    $sql = "INSERT INTO admin_notifications (title, message, notification_type, target_roles, is_urgent, expires_at, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $targetRolesJson = $targetRoles ? json_encode($targetRoles) : null;
    $stmt->bind_param("ssssiss", $title, $message, $type, $targetRolesJson, $isUrgent, $expiresAt, $createdBy);
    
    return $stmt->execute();
}

/**
 * Update notification status
 */
function updateNotificationStatus($id, $isActive) {
    $conn = getAdminDbConnection();
    $sql = "UPDATE admin_notifications SET is_active = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $isActive, $id);
    return $stmt->execute();
}

/**
 * Delete notification
 */
function deleteNotification($id) {
    $conn = getAdminDbConnection();
    $sql = "DELETE FROM admin_notifications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

/**
 * Update notification details
 */
function updateNotification($id, $title, $message, $type, $targetRoles, $isUrgent, $expiresAt) {
    $conn = getAdminDbConnection();
    $sql = "UPDATE admin_notifications 
            SET title = ?, message = ?, notification_type = ?, target_roles = ?, is_urgent = ?, expires_at = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $targetRolesJson = $targetRoles ? json_encode($targetRoles) : null;
    $stmt->bind_param("ssssisi", $title, $message, $type, $targetRolesJson, $isUrgent, $expiresAt, $id);
    
    return $stmt->execute();
}

/**
 * Get notification by ID
 */
function getNotificationById($id) {
    $conn = getAdminDbConnection();
    $sql = "SELECT * FROM admin_notifications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notification = $result->fetch_assoc();
    if ($notification && $notification['target_roles']) {
        $notification['target_roles'] = json_decode($notification['target_roles'], true);
    }
    
    return $notification;
}

/**
 * Get all notifications for admin management
 */
function getAllNotificationsForAdmin() {
    $conn = getAdminDbConnection();
    $sql = "SELECT id, title, message, notification_type, target_roles, is_active, is_urgent, 
                   created_by, created_at, expires_at,
                   (SELECT COUNT(*) FROM notification_views WHERE notification_id = admin_notifications.id) as view_count
            FROM admin_notifications 
            ORDER BY created_at DESC";
    
    $result = $conn->query($sql);
    $notifications = [];
    
    while ($row = $result->fetch_assoc()) {
        if ($row['target_roles']) {
            $row['target_roles'] = json_decode($row['target_roles'], true);
        }
        $notifications[] = $row;
    }
    
    return $notifications;
}

/**
 * Get notification icon class based on type
 */
function getNotificationIcon($type) {
    switch ($type) {
        case 'warning':
            return 'fas fa-exclamation-triangle text-warning';
        case 'danger':
            return 'fas fa-exclamation-circle text-danger';
        case 'success':
            return 'fas fa-check-circle text-success';
        case 'info':
        default:
            return 'fas fa-info-circle text-info';
    }
}

/**
 * Get notification badge class based on type
 */
function getNotificationBadgeClass($type) {
    switch ($type) {
        case 'warning':
            return 'bg-warning';
        case 'danger':
            return 'bg-danger';
        case 'success':
            return 'bg-success';
        case 'info':
        default:
            return 'bg-info';
    }
}

/**
 * Time ago helper function
 */
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    if ($time < 2592000) return floor($time/86400) . ' days ago';
    if ($time < 31536000) return floor($time/2592000) . ' months ago';
    return floor($time/31536000) . ' years ago';
}
?>
