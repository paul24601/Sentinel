<?php
// Database setup script for admin notifications
require_once __DIR__ . '/includes/database.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_admin');
    echo "<h1>Setting up Admin Notifications Database</h1>";
    
    // Create admin_notifications table
    $sql_notifications = "CREATE TABLE IF NOT EXISTS admin_notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        notification_type ENUM('info', 'warning', 'success', 'danger') DEFAULT 'info',
        target_roles JSON DEFAULT NULL,
        is_active BOOLEAN DEFAULT TRUE,
        is_urgent BOOLEAN DEFAULT FALSE,
        created_by VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP NULL DEFAULT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql_notifications)) {
        echo "<p style='color: green;'>✅ admin_notifications table created/verified successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating admin_notifications table: " . $conn->error . "</p>";
    }
    
    // Create notification_views table
    $sql_views = "CREATE TABLE IF NOT EXISTS notification_views (
        id INT AUTO_INCREMENT PRIMARY KEY,
        notification_id INT NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (notification_id) REFERENCES admin_notifications(id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_notification (notification_id, user_name)
    )";
    
    if ($conn->query($sql_views)) {
        echo "<p style='color: green;'>✅ notification_views table created/verified successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating notification_views table: " . $conn->error . "</p>";
    }
    
    // Check if sample data exists
    $result = $conn->query("SELECT COUNT(*) as count FROM admin_notifications");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<p style='color: blue;'>📝 No sample data found. Inserting sample notifications...</p>";
        
        // Insert sample notifications
        $sample_notifications = [
            [
                'System Maintenance Notice', 
                'Scheduled maintenance will occur on Friday, August 22, 2025 from 2:00 PM to 4:00 PM. Please save your work before this time.', 
                'warning', 
                '["admin", "supervisor", "Quality Control Inspection", "Quality Assurance Engineer", "operator"]', 
                TRUE, 
                TRUE, 
                'System Administrator'
            ],
            [
                'New Quality Control Procedures', 
                'Updated quality control procedures are now in effect. Please review the new guidelines in the QC manual.', 
                'info', 
                '["Quality Control Inspection", "Quality Assurance Engineer", "Quality Assurance Supervisor"]', 
                TRUE, 
                FALSE, 
                'System Administrator'
            ],
            [
                'Production Target Achievement', 
                'Congratulations! We have exceeded our monthly production target by 15%. Great work team!', 
                'success', 
                '["admin", "supervisor", "operator"]', 
                TRUE, 
                FALSE, 
                'System Administrator'
            ],
            [
                'Welcome to Sentinel DMS', 
                'Welcome to the updated Sentinel Digital Monitoring System. This notification system will keep you informed of important updates and announcements.', 
                'info', 
                '["admin", "supervisor", "Quality Control Inspection", "Quality Assurance Engineer", "operator"]', 
                TRUE, 
                FALSE, 
                'System Administrator'
            ]
        ];
        
        $stmt = $conn->prepare("INSERT INTO admin_notifications (title, message, notification_type, target_roles, is_active, is_urgent, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($sample_notifications as $notification) {
            $stmt->bind_param("sssssis", $notification[0], $notification[1], $notification[2], $notification[3], $notification[4], $notification[5], $notification[6]);
            if ($stmt->execute()) {
                echo "<p style='color: green;'>✅ Added: " . htmlspecialchars($notification[0]) . "</p>";
            } else {
                echo "<p style='color: red;'>❌ Failed to add: " . htmlspecialchars($notification[0]) . "</p>";
            }
        }
    } else {
        echo "<p style='color: blue;'>📊 Found " . $row['count'] . " existing notifications in database.</p>";
    }
    
    echo "<hr>";
    echo "<h2>Setup Complete!</h2>";
    echo "<p style='color: green;'>✅ Admin notifications system is ready to use!</p>";
    echo "<p><a href='test_notifications.php'>→ Test the system</a></p>";
    echo "<p><a href='admin/notifications.php'>→ Go to Admin Notifications Management</a></p>";
    echo "<p><a href='index.php'>→ Back to Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database setup failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database configuration and try again.</p>";
}
?>
