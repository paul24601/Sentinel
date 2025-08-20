# Sentinel Admin Notifications System - Setup Guide

## Database Setup Instructions

### Step 1: Create Database Tables

Execute the following SQL commands in your MySQL database (you can use phpMyAdmin or any MySQL client):

```sql
-- Use your existing Sentinel database
USE sentinel_monitoring;

-- Create admin_notifications table
CREATE TABLE IF NOT EXISTS admin_notifications (
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
```

### Step 2: File Structure

The following files have been created/modified:

#### New Files Created:
- `c:\xampp\htdocs\Sentinel\includes\admin_notifications.php` - Core notification functions
- `c:\xampp\htdocs\Sentinel\includes\mark_notification_viewed.php` - AJAX handler for marking notifications as viewed
- `c:\xampp\htdocs\Sentinel\admin\notifications.php` - Admin notification management page

#### Modified Files:
- `c:\xampp\htdocs\Sentinel\index.php` - Updated notification system in main dashboard
- `c:\xampp\htdocs\Sentinel\dms\approval.php` - Updated notification system in approval page

### Step 3: Features Implemented

#### For Administrators:
1. **Admin Notification Management Page** (`/admin/notifications.php`)
   - Create new notifications with different types (info, warning, success, danger)
   - Target specific user roles or all users
   - Set urgent notifications with visual indicators
   - Set expiration dates for time-sensitive notifications
   - View notification statistics (view counts)
   - Activate/deactivate notifications
   - Delete notifications

#### For All Users:
1. **Enhanced Notification Dropdown**
   - Shows admin notifications based on user role
   - Visual indicators for notification types (different icons and colors)
   - "New" badges for unviewed notifications
   - Notification count in red badge
   - Mark notifications as viewed when clicked
   - Time stamps showing when notifications were created

#### Notification Types:
- **Info** (blue): General information and announcements
- **Success** (green): Achievements, completions, good news
- **Warning** (yellow): Important notices, upcoming changes
- **Danger** (red): Critical alerts, urgent issues

#### Role-Based Targeting:
- Admin
- Supervisor
- Quality Control Inspection
- Quality Assurance Engineer
- Quality Assurance Supervisor
- Operator
- All Users

### Step 4: Usage Guide

#### For Administrators:
1. Navigate to `/admin/notifications.php`
2. Fill out the notification creation form:
   - **Title**: Short, descriptive title
   - **Type**: Choose appropriate notification type
   - **Message**: Detailed message content
   - **Target Roles**: Select which user roles should see this notification
   - **Expires At**: (Optional) Set expiration date
   - **Mark as Urgent**: Check for urgent notifications (they'll have priority display)
3. Click "Create Notification"
4. Manage existing notifications using the activate/deactivate and delete buttons

#### For All Users:
1. Look for the bell icon in the top navigation
2. Red badge indicates unread notifications
3. Click the bell to view notifications
4. Click on a notification to mark it as read
5. "New" badges indicate unviewed notifications

### Step 5: Technical Details

#### Database Schema:
- **admin_notifications**: Stores notification content and metadata
- **notification_views**: Tracks which users have viewed which notifications

#### Security Features:
- Role-based access control
- SQL injection prevention with prepared statements
- XSS protection with htmlspecialchars
- Session validation

#### Performance Features:
- JSON storage for target roles (efficient querying)
- Indexed foreign keys
- AJAX loading for marking notifications as viewed
- Efficient query with joins to check view status

### Step 6: Next Steps

You can extend this system by:
1. Adding email notifications for urgent alerts
2. Adding push notifications
3. Creating notification templates
4. Adding file attachments to notifications
5. Adding notification scheduling
6. Creating notification categories
7. Adding notification search and filtering

## Installation Complete!

Your admin notification system is now ready to use. Administrators can start creating notifications immediately, and all users will see them based on their roles.
