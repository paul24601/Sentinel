<?php
// Password reset notification functions for admin pages

/**
 * Get count of pending password reset requests
 */
function getPendingPasswordResetCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM password_reset_requests WHERE status = 'pending'";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['count'];
    }
    return 0;
}

/**
 * Get pending password reset requests for notification dropdown
 */
function getPendingPasswordResetRequests($conn, $limit = 5) {
    $sql = "SELECT id, id_number, full_name, request_reason, request_date 
            FROM password_reset_requests 
            WHERE status = 'pending' 
            ORDER BY request_date DESC 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    return $requests;
}

/**
 * Generate notification HTML for password reset requests
 */
function generatePasswordResetNotificationHTML($conn) {
    $pendingCount = getPendingPasswordResetCount($conn);
    $pendingRequests = getPendingPasswordResetRequests($conn);
    
    $html = '';
    
    // Notification badge
    if ($pendingCount > 0) {
        $html .= '<span class="badge badge-warning badge-counter">' . $pendingCount . '</span>';
    }
    
    // Notification dropdown items
    if (!empty($pendingRequests)) {
        foreach ($pendingRequests as $request) {
            $timeAgo = time_ago($request['request_date']);
            $shortReason = substr($request['request_reason'], 0, 50) . (strlen($request['request_reason']) > 50 ? '...' : '');
            
            $html .= '<a class="dropdown-item notification-item d-flex align-items-center" href="password_reset_management.php">';
            $html .= '<div class="mr-3">';
            $html .= '<div class="icon-circle bg-warning">';
            $html .= '<i class="fas fa-key text-white"></i>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div>';
            $html .= '<div class="small text-gray-500">' . $timeAgo . '</div>';
            $html .= '<span class="font-weight-bold">Password Reset: ' . htmlspecialchars($request['full_name']) . '</span>';
            $html .= '<div class="small text-gray-500">' . htmlspecialchars($shortReason) . '</div>';
            $html .= '</div>';
            $html .= '</a>';
        }
        
        if ($pendingCount > count($pendingRequests)) {
            $html .= '<a class="dropdown-item text-center small text-gray-500" href="password_reset_management.php">';
            $html .= 'Show All Requests (' . $pendingCount . ')';
            $html .= '</a>';
        }
    } else {
        $html .= '<a class="dropdown-item text-center small text-gray-500">No pending password reset requests</a>';
    }
    
    return $html;
}

/**
 * Helper function to convert datetime to "time ago" format
 */
function time_ago($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    if ($time < 2592000) return floor($time/86400) . ' days ago';
    if ($time < 31104000) return floor($time/2592000) . ' months ago';
    return floor($time/31104000) . ' years ago';
}
?>
