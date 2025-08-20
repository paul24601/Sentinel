<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['full_name']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Load required files
require_once __DIR__ . '/../includes/admin_notifications.php';

$message = "";
$messageType = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $title = trim($_POST['title']);
                $messageText = trim($_POST['message']);
                $type = $_POST['notification_type'];
                $targetRoles = isset($_POST['target_roles']) ? $_POST['target_roles'] : [];
                $isUrgent = isset($_POST['is_urgent']) ? 1 : 0;
                $expiresAt = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;
                $createdBy = $_SESSION['full_name'];
                
                if (createAdminNotification($title, $messageText, $type, $targetRoles, $isUrgent, $expiresAt, $createdBy)) {
                    $message = "Notification created successfully!";
                    $messageType = "success";
                } else {
                    $message = "Error creating notification.";
                    $messageType = "danger";
                }
                break;
                
            case 'toggle_status':
                $id = intval($_POST['notification_id']);
                $isActive = intval($_POST['is_active']);
                
                if (updateNotificationStatus($id, $isActive)) {
                    $message = "Notification status updated successfully!";
                    $messageType = "success";
                } else {
                    $message = "Error updating notification status.";
                    $messageType = "danger";
                }
                break;
                
            case 'delete':
                $id = intval($_POST['notification_id']);
                
                if (deleteNotification($id)) {
                    $message = "Notification deleted successfully!";
                    $messageType = "success";
                } else {
                    $message = "Error deleting notification.";
                    $messageType = "danger";
                }
                break;
        }
    }
}

// Get all notifications for display
$notifications = getAllNotificationsForAdmin();

// Available roles for targeting
$availableRoles = [
    'admin' => 'Administrator',
    'supervisor' => 'Supervisor',
    'Quality Control Inspection' => 'Quality Control Inspection',
    'Quality Assurance Engineer' => 'Quality Assurance Engineer',
    'Quality Assurance Supervisor' => 'Quality Assurance Supervisor',
    'operator' => 'Operator',
    'all' => 'All Users'
];

// Include centralized navbar
include '../includes/navbar.php';
?>
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Notification Management</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Notification Management</li>
                    </ol>

                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Create New Notification Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-plus me-1"></i>
                            Create New Notification
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="action" value="create">
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title *</label>
                                            <input type="text" class="form-control" id="title" name="title" required maxlength="255">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="notification_type" class="form-label">Type *</label>
                                            <select class="form-control" id="notification_type" name="notification_type" required>
                                                <option value="info">Info</option>
                                                <option value="success">Success</option>
                                                <option value="warning">Warning</option>
                                                <option value="danger">Critical</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Target Roles *</label>
                                            <div class="row">
                                                <?php foreach ($availableRoles as $roleKey => $roleName): ?>
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="target_roles[]" value="<?php echo $roleKey; ?>" id="role_<?php echo $roleKey; ?>">
                                                            <label class="form-check-label" for="role_<?php echo $roleKey; ?>">
                                                                <?php echo $roleName; ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expires_at" class="form-label">Expires At (Optional)</label>
                                            <input type="datetime-local" class="form-control" id="expires_at" name="expires_at">
                                            <small class="form-text text-muted">Leave empty for no expiration</small>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_urgent" name="is_urgent">
                                            <label class="form-check-label" for="is_urgent">
                                                Mark as Urgent
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create Notification
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Existing Notifications -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bell me-1"></i>
                            Existing Notifications (<?php echo count($notifications); ?>)
                        </div>
                        <div class="card-body">
                            <?php if (empty($notifications)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No notifications found. Create your first notification above!</p>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card notification-card h-100 <?php echo $notification['is_active'] ? '' : 'border-secondary'; ?>">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-0">
                                                            <i class="<?php echo getNotificationIcon($notification['notification_type']); ?> me-1"></i>
                                                            <?php echo htmlspecialchars($notification['title']); ?>
                                                        </h6>
                                                        <div>
                                                            <?php if ($notification['is_urgent']): ?>
                                                                <span class="badge bg-danger badge-urgent me-1">URGENT</span>
                                                            <?php endif; ?>
                                                            <span class="badge <?php echo getNotificationBadgeClass($notification['notification_type']); ?>">
                                                                <?php echo ucfirst($notification['notification_type']); ?>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <p class="card-text text-muted small">
                                                        <?php echo htmlspecialchars(substr($notification['message'], 0, 100)); ?>
                                                        <?php if (strlen($notification['message']) > 100): ?>...<?php endif; ?>
                                                    </p>

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <strong>Target:</strong> 
                                                            <?php 
                                                            if ($notification['target_roles']) {
                                                                $roleNames = array_map(function($role) use ($availableRoles) {
                                                                    return $availableRoles[$role] ?? $role;
                                                                }, $notification['target_roles']);
                                                                echo implode(', ', $roleNames);
                                                            } else {
                                                                echo 'All Users';
                                                            }
                                                            ?>
                                                        </small>
                                                    </div>

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <strong>Views:</strong> <?php echo $notification['view_count']; ?> | 
                                                            <strong>Created:</strong> <?php echo timeAgo($notification['created_at']); ?>
                                                            <?php if ($notification['expires_at']): ?>
                                                                | <strong>Expires:</strong> <?php echo date('M j, Y g:i A', strtotime($notification['expires_at'])); ?>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            Status: 
                                                            <span class="badge <?php echo $notification['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                                <?php echo $notification['is_active'] ? 'Active' : 'Inactive'; ?>
                                                            </span>
                                                        </small>
                                                        
                                                        <div class="btn-group btn-group-sm">
                                                            <!-- Toggle Status -->
                                                            <form method="POST" class="d-inline">
                                                                <input type="hidden" name="action" value="toggle_status">
                                                                <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                                                                <input type="hidden" name="is_active" value="<?php echo $notification['is_active'] ? 0 : 1; ?>">
                                                                <button type="submit" class="btn btn-outline-primary btn-sm" title="<?php echo $notification['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                                                    <i class="fas <?php echo $notification['is_active'] ? 'fa-pause' : 'fa-play'; ?>"></i>
                                                                </button>
                                                            </form>
                                                            
                                                            <!-- Delete -->
                                                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                                                <input type="hidden" name="action" value="delete">
                                                                <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

<?php include '../includes/navbar_close.php'; ?>

</body>
</html>
