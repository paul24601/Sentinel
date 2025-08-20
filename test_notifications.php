<?php
// Test file to verify admin notifications database connection
session_start();

// Load required files
require_once __DIR__ . '/includes/admin_notifications.php';

echo "<h1>Admin Notifications System Test</h1>";

try {
    // Test database connection
    $conn = getAdminDbConnection();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if tables exist
    $result = $conn->query("SHOW TABLES LIKE 'admin_notifications'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ admin_notifications table exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ admin_notifications table does not exist!</p>";
    }
    
    $result = $conn->query("SHOW TABLES LIKE 'notification_views'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ notification_views table exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ notification_views table does not exist!</p>";
    }
    
    // Test getting notifications (without requiring session)
    $result = $conn->query("SELECT COUNT(*) as count FROM admin_notifications");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p style='color: blue;'>📊 Total notifications in database: " . $row['count'] . "</p>";
    }
    
    // Test specific functionality if session exists
    if (isset($_SESSION['role']) && isset($_SESSION['full_name'])) {
        echo "<p style='color: blue;'>👤 Current user: " . $_SESSION['full_name'] . " (Role: " . $_SESSION['role'] . ")</p>";
        
        $notifications = getAdminNotifications($_SESSION['role'], $_SESSION['full_name'], 5);
        echo "<p style='color: blue;'>📬 Notifications for current user: " . count($notifications) . "</p>";
        
        $unviewed_count = getUnviewedNotificationCount($_SESSION['role'], $_SESSION['full_name']);
        echo "<p style='color: blue;'>🔔 Unviewed notifications: " . $unviewed_count . "</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ No active session - user-specific tests skipped</p>";
        echo "<p>Please login first, then run this test again.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Back to Dashboard</a></p>";
echo "<p><a href='admin/notifications.php'>→ Go to Admin Notifications</a></p>";
?>
