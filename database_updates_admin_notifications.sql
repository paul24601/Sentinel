-- Admin Notifications System Database Schema
-- Date: August 20, 2025

-- Use the admin_sentinel database
USE admin_sentinel;

-- Create admin_notifications table
CREATE TABLE IF NOT EXISTS admin_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    notification_type ENUM('info', 'warning', 'success', 'danger') DEFAULT 'info',
    target_roles JSON DEFAULT NULL, -- Store array of roles that should see this notification
    is_active BOOLEAN DEFAULT TRUE,
    is_urgent BOOLEAN DEFAULT FALSE,
    created_by VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create notification_views table to track which users have seen which notifications
CREATE TABLE IF NOT EXISTS notification_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notification_id INT NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notification_id) REFERENCES admin_notifications(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_notification (notification_id, user_name)
);

-- Insert sample admin notifications
INSERT INTO admin_notifications (title, message, notification_type, target_roles, is_active, is_urgent, created_by) VALUES
('System Maintenance Notice', 'Scheduled maintenance will occur on Friday, August 22, 2025 from 2:00 PM to 4:00 PM. Please save your work before this time.', 'warning', '["admin", "supervisor", "Quality Control Inspection", "Quality Assurance Engineer", "operator"]', TRUE, TRUE, 'System Administrator'),
('New Quality Control Procedures', 'Updated quality control procedures are now in effect. Please review the new guidelines in the QC manual.', 'info', '["Quality Control Inspection", "Quality Assurance Engineer", "Quality Assurance Supervisor"]', TRUE, FALSE, 'System Administrator'),
('Production Target Achievement', 'Congratulations! We have exceeded our monthly production target by 15%. Great work team!', 'success', '["admin", "supervisor", "operator"]', TRUE, FALSE, 'System Administrator'),
('Welcome to Sentinel DMS', 'Welcome to the updated Sentinel Digital Monitoring System. This notification system will keep you informed of important updates and announcements.', 'info', '["admin", "supervisor", "Quality Control Inspection", "Quality Assurance Engineer", "operator"]', TRUE, FALSE, 'System Administrator');
