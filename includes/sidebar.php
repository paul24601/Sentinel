<?php
/**
 * Centralized Sidebar Component
 * Responsive sidebar navigation with role-based access control
 * Compatible with existing Bootstrap 5 layouts
 * 
 * @author Sentinel Development Team
 * @date August 20, 2025
 */

// Include navigation configuration
$config_path = dirname(__FILE__) . '/navigation_config.php';
if (file_exists($config_path)) {
    require_once $config_path;
} else {
    die('Navigation configuration file not found');
}

// Get user session data
$user_role = $_SESSION['user_type'] ?? 'guest';
$user_name = $_SESSION['full_name'] ?? 'Guest User';
$user_id = $_SESSION['id_number'] ?? '';

// Get navigation for current user role
$user_navigation = getNavigationForRole($user_role);
$quick_actions = getQuickActionsForRole($user_role);

// Get current page info for active states
$current_path = $_SERVER['REQUEST_URI'];
$page_info = getCurrentPageInfo($current_path);

// Calculate base path for URLs (not file includes)
function getSidebarUrlBasePath() {
    $script_path = $_SERVER['SCRIPT_NAME'];
    $path_parts = explode('/', trim($script_path, '/'));
    
    // Remove the script filename to get directory path
    array_pop($path_parts);
    
    // Calculate how many levels deep we are from document root
    $depth = count($path_parts) - 1; // Subtract 1 for the root level
    
    return str_repeat('../', $depth);
}

$base_path = getSidebarUrlBasePath();
?>

<!-- Sidebar Navigation -->
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <!-- User Info Section -->
        <div class="sidebar-user-info p-3 mb-3 bg-primary text-white rounded">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                     style="width: 40px; height: 40px;">
                    <i class="fas fa-user text-primary"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0"><?= htmlspecialchars($user_name) ?></h6>
                    <small class="opacity-75"><?= htmlspecialchars($user_role) ?></small>
                </div>
            </div>
        </div>

        <!-- Quick Actions (if available) -->
        <?php if (!empty($quick_actions)): ?>
            <div class="quick-actions mb-4">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Quick Actions</span>
                </h6>
                <div class="px-3">
                    <?php foreach ($quick_actions as $action): ?>
                        <a href="<?= $base_path . ltrim($action['url'], '/') ?>" 
                           class="btn <?= $action['class'] ?> btn-sm w-100 mb-2">
                            <i class="<?= $action['icon'] ?> me-1"></i><?= $action['title'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <hr>
            </div>
        <?php endif; ?>

        <!-- Main Navigation -->
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?= ($page_info['module'] === '' && strpos($current_path, 'index.php') !== false) ? 'active' : '' ?>" 
                   href="<?= $base_path ?>index.php">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>

            <?php foreach ($user_navigation['modules'] as $module_key => $module): ?>
                <?php if ($module_key === 'dashboard') continue; // Skip dashboard as it's handled above ?>

                <?php if (isset($module['submenu']) && !empty($module['submenu'])): ?>
                    <!-- Module with submenu -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($page_info['module'] === $module_key) ? 'active' : '' ?> d-flex justify-content-between align-items-center" 
                           href="#<?= $module_key ?>Submenu" data-bs-toggle="collapse" 
                           aria-expanded="<?= ($page_info['module'] === $module_key) ? 'true' : 'false' ?>">
                            <span>
                                <i class="<?= $module['icon'] ?> me-2"></i><?= $module['title'] ?>
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="collapse <?= ($page_info['module'] === $module_key) ? 'show' : '' ?>" id="<?= $module_key ?>Submenu">
                            <ul class="nav flex-column ms-3">
                                <?php foreach ($module['submenu'] as $sub_key => $sub_item): ?>
                                    <li class="nav-item">
                                        <a class="nav-link py-1 <?= ($page_info['module'] === $module_key && $page_info['page'] === $sub_key) ? 'active' : '' ?>" 
                                           href="<?= $base_path . ltrim($sub_item['url'], '/') ?>">
                                            <i class="fas fa-circle me-2" style="font-size: 0.5rem;"></i>
                                            <?= $sub_item['title'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php else: ?>
                    <!-- Single module item -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($page_info['module'] === $module_key) ? 'active' : '' ?>" 
                           href="<?= $base_path . ltrim($module['url'], '/') ?>">
                            <i class="<?= $module['icon'] ?> me-2"></i><?= $module['title'] ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <!-- Admin Notifications Section (Admin only) -->
        <?php if ($user_role === 'admin'): ?>
            <hr>
            <div class="admin-notifications px-3">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center mt-4 mb-1 text-muted">
                    <span>Notifications</span>
                    <span class="badge bg-primary" id="sidebarNotificationCount" style="display: none;">0</span>
                </h6>
                <div id="sidebarNotificationsList" class="notification-preview">
                    <div class="text-center p-2 text-muted small">
                        <i class="fas fa-bell-slash"></i><br>
                        No new notifications
                    </div>
                </div>
                <div class="d-grid mt-2">
                    <a href="<?= $base_path ?>admin/notifications.php" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-bell me-1"></i>Manage Notifications
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="sidebar-footer mt-auto p-3">
            <hr>
            <div class="d-grid gap-2">
                <a href="<?= $base_path ?>change_password.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-key me-1"></i>Change Password
                </a>
                <a href="<?= $base_path ?>logout.php" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
            <div class="text-center mt-2">
                <small class="text-muted">© 2025 Sentinel OJT</small>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar JavaScript (Admin notifications) -->
<?php if ($user_role === 'admin'): ?>
<script>
// Sidebar notification functions
function getSidebarNotifications() {
    fetch('<?= $base_path ?>admin/get_admin_notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateSidebarNotifications(data.notifications);
            }
        })
        .catch(error => {
            console.error('Error fetching sidebar notifications:', error);
        });
}

function updateSidebarNotifications(notifications) {
    const notificationsList = document.getElementById('sidebarNotificationsList');
    const notificationCount = document.getElementById('sidebarNotificationCount');
    
    if (notifications.length === 0) {
        notificationsList.innerHTML = `
            <div class="text-center p-2 text-muted small">
                <i class="fas fa-bell-slash"></i><br>
                No new notifications
            </div>
        `;
        notificationCount.style.display = 'none';
    } else {
        // Show only latest 3 notifications in sidebar
        const latestNotifications = notifications.slice(0, 3);
        let html = '';
        
        latestNotifications.forEach(notification => {
            const isUnviewed = notification.viewed_by_admin == 0;
            html += `
                <div class="notification-preview-item ${isUnviewed ? 'unviewed' : ''} p-2 mb-2 border rounded">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 small fw-bold">${notification.title}</h6>
                            <p class="mb-1 text-muted" style="font-size: 0.75rem;">
                                ${notification.message.length > 50 ? notification.message.substring(0, 50) + '...' : notification.message}
                            </p>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                ${formatNotificationTime(notification.created_at)}
                            </small>
                        </div>
                        ${isUnviewed ? '<div class="ms-1"><span class="badge bg-primary" style="font-size: 0.6rem;">New</span></div>' : ''}
                    </div>
                </div>
            `;
        });
        
        if (notifications.length > 3) {
            html += `
                <div class="text-center">
                    <small class="text-muted">+${notifications.length - 3} more notifications</small>
                </div>
            `;
        }
        
        notificationsList.innerHTML = html;
        
        // Update badge
        const unviewedCount = notifications.filter(n => n.viewed_by_admin == 0).length;
        if (unviewedCount > 0) {
            notificationCount.textContent = unviewedCount;
            notificationCount.style.display = 'block';
        } else {
            notificationCount.style.display = 'none';
        }
    }
}

// Initialize sidebar notifications
document.addEventListener('DOMContentLoaded', function() {
    getSidebarNotifications();
    
    // Refresh every 60 seconds (less frequent than navbar)
    setInterval(getSidebarNotifications, 60000);
});
</script>
<?php endif; ?>

<!-- Sidebar Styles -->
<style>
.sidebar {
    position: fixed;
    top: 56px; /* Account for navbar height */
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    overflow-y: auto;
}

.sidebar .nav-link {
    color: #333;
    padding: 0.75rem 1rem;
    transition: all 0.2s;
}

.sidebar .nav-link:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

.sidebar .nav-link.active {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
    border-left: 3px solid #007bff;
}

.sidebar .nav-link i {
    width: 16px;
    text-align: center;
}

.sidebar-heading {
    font-size: .75rem;
    text-transform: uppercase;
}

.sidebar-user-info {
    margin: 0 1rem;
}

.notification-preview-item {
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}

.notification-preview-item:hover {
    background-color: #e9ecef;
}

.notification-preview-item.unviewed {
    background-color: #e3f2fd;
    border-left: 3px solid #2196f3 !important;
}

.quick-actions .btn {
    font-size: 0.8rem;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .sidebar {
        position: relative;
        height: auto;
        top: 0;
    }
}

/* Collapse transitions */
.collapse {
    transition: height 0.2s ease;
}

/* Custom scrollbar for sidebar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
