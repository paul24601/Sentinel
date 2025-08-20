<?php
/**
 * Centralized Navigation System for Sentinel
 * This file contains both the top navbar and sidebar navigation
 * with role-based permissions and dynamic path resolution
 */

// Ensure session is started and admin notifications are loaded
if (!isset($admin_notifications)) {
    require_once __DIR__ . '/admin_notifications.php';
    $admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
    $notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);
}

// Function to determine relative paths based on current page location
if (!function_exists('getBasePath')) {
    function getBasePath() {
        $currentDir = dirname($_SERVER['SCRIPT_NAME']);
        
        // Check if we're in a subdirectory
        if (strpos($currentDir, '/dms') !== false || strpos($currentDir, '/parameters') !== false || 
            strpos($currentDir, '/admin') !== false || strpos($currentDir, '/production_report') !== false) {
            return '../';
        } else {
            return '';
        }
    }
}

$basePath = getBasePath();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sentinel Digitization</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="<?php echo $basePath; ?>css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery and jQuery UI for Autocomplete -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS to restore original sidebar design -->
    <style>
        /* Remove border-radius from sidebar nav-links to restore sharp corners */
        .sb-sidenav .sb-sidenav-menu .nav .nav-link {
            border-radius: 0 !important;
        }
        
        /* Ensure dropdown nav-links also have sharp corners */
        .sb-sidenav .sb-sidenav-menu .nav .sb-sidenav-menu-nested .nav-link {
            border-radius: 0 !important;
        }
        
        /* Remove any border-radius from active/hover states */
        .sb-sidenav .sb-sidenav-menu .nav .nav-link:hover,
        .sb-sidenav .sb-sidenav-menu .nav .nav-link.active {
            border-radius: 0 !important;
        }
    </style>
</head>

<body class="sb-nav-fixed">
<?php

// Define navigation menu structure with role-based permissions
$navigationConfig = [
    'core' => [
        'title' => 'Core',
        'items' => [
            [
                'title' => 'Dashboard',
                'url' => $basePath . 'index.php',
                'icon' => 'fas fa-tachometer-alt',
                'roles' => ['admin', 'supervisor', 'personnel', 'manager', 'Quality Control Inspection']
            ]
        ]
    ],
    'systems' => [
        'title' => 'Systems',
        'items' => [
            [
                'title' => 'DMS',
                'icon' => 'fas fa-people-roof',
                'type' => 'dropdown',
                'id' => 'collapseDMS',
                'roles' => ['admin', 'supervisor', 'personnel', 'manager', 'Quality Control Inspection'],
                'children' => [
                    [
                        'title' => 'Data Entry',
                        'url' => $basePath . 'dms/index.php',
                        'roles' => ['admin', 'supervisor', 'personnel', 'manager'],
                        'exclude_roles' => ['Quality Control Inspection']
                    ],
                    [
                        'title' => 'Records',
                        'url' => $basePath . 'dms/submission.php',
                        'roles' => ['admin', 'supervisor', 'personnel', 'manager', 'Quality Control Inspection']
                    ],
                    [
                        'title' => 'Analytics',
                        'url' => $basePath . 'dms/analytics.php',
                        'roles' => ['admin', 'supervisor', 'manager'],
                        'exclude_roles' => ['Quality Control Inspection']
                    ]
                ]
            ],
            [
                'title' => 'Parameters',
                'icon' => 'fas fa-columns',
                'type' => 'dropdown',
                'id' => 'collapseParameters',
                'roles' => ['admin', 'supervisor', 'personnel', 'manager', 'Quality Control Inspection'],
                'children' => [
                    [
                        'title' => 'Data Entry',
                        'url' => $basePath . 'parameters/index.php',
                        'roles' => ['admin', 'supervisor', 'personnel', 'manager'],
                        'exclude_roles' => ['Quality Control Inspection']
                    ],
                    [
                        'title' => 'Records',
                        'url' => $basePath . 'parameters/submission.php',
                        'roles' => ['admin', 'supervisor', 'personnel', 'manager', 'Quality Control Inspection']
                    ],
                    [
                        'title' => 'Analytics',
                        'url' => $basePath . 'parameters/analytics.php',
                        'roles' => ['admin', 'supervisor', 'manager'],
                        'exclude_roles' => ['Quality Control Inspection']
                    ]
                ]
            ],
            [
                'title' => 'Production Report',
                'icon' => 'fas fa-chart-area',
                'type' => 'dropdown',
                'id' => 'collapseProductionReport',
                'roles' => ['admin', 'supervisor', 'personnel', 'manager'],
                'exclude_roles' => ['Quality Control Inspection'],
                'children' => [
                    [
                        'title' => 'Data Entry',
                        'url' => $basePath . 'production_report/index.php',
                        'roles' => ['admin', 'supervisor', 'personnel', 'manager']
                    ],
                    [
                        'title' => 'Submit Report',
                        'url' => $basePath . 'production_report/submit.php',
                        'roles' => ['admin', 'supervisor', 'personnel', 'manager']
                    ]
                ]
            ]
        ]
    ],
    'admin' => [
        'title' => 'Admin',
        'roles' => ['admin', 'supervisor', 'manager'],
        'exclude_roles' => ['Quality Control Inspection'],
        'items' => [
            [
                'title' => 'Users',
                'url' => $basePath . 'admin/users.php',
                'icon' => 'fas fa-users',
                'roles' => ['admin', 'supervisor', 'manager'],
                'exclude_roles' => ['Quality Control Inspection']
            ],
            [
                'title' => 'Password Reset Management',
                'url' => $basePath . 'admin/password_reset_management.php',
                'icon' => 'fas fa-key',
                'roles' => ['admin'],
                'exclude_roles' => ['Quality Control Inspection', 'supervisor', 'manager', 'personnel']
            ],
            [
                'title' => 'Product Parameters',
                'url' => $basePath . 'admin/product_parameters.php',
                'icon' => 'fas fa-cogs',
                'roles' => ['admin', 'supervisor', 'manager'],
                'exclude_roles' => ['Quality Control Inspection']
            ],
            [
                'title' => 'Notifications',
                'url' => $basePath . 'admin/notifications.php',
                'icon' => 'fas fa-bell',
                'roles' => ['admin', 'supervisor', 'manager'],
                'exclude_roles' => ['Quality Control Inspection']
            ]
        ]
    ]
];

// Helper function to check if user has permission for a menu item
function hasPermission($item, $userRole) {
    // If roles array is specified, check if user role is in it
    if (isset($item['roles']) && !in_array($userRole, $item['roles'])) {
        return false;
    }
    
    // If exclude_roles is specified, check if user role is excluded
    if (isset($item['exclude_roles']) && in_array($userRole, $item['exclude_roles'])) {
        return false;
    }
    
    return true;
}

// Function to determine if a menu item should be expanded (active)
function isMenuActive($item, $currentPath) {
    if (isset($item['children'])) {
        foreach ($item['children'] as $child) {
            if (isset($child['url']) && strpos($currentPath, $child['url']) !== false) {
                return true;
            }
        }
    }
    return false;
}

$currentPath = $_SERVER['REQUEST_URI'];
$userRole = $_SESSION['role'];
?>

    <!-- Top Navigation -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="<?php echo $basePath; ?>index.php">Sentinel Digitization</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <!-- (Optional search form can go here) -->
        </form>
        <!-- Navbar Notifications and User Dropdown-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle position-relative" id="notifDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if ($notification_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $notification_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="max-width: 350px; max-height:300px; overflow-y:auto;">
                    <?php if (!empty($admin_notifications)): ?>
                        <li class="dropdown-header">
                            <i class="fas fa-bell me-1"></i> Recent Notifications
                        </li>
                        <?php foreach ($admin_notifications as $notification): ?>
                            <li>
                                <a class="dropdown-item notification-item <?php echo !$notification['is_viewed'] ? 'bg-light' : ''; ?>" 
                                   href="#" 
                                   onclick="markAsViewed(<?php echo $notification['id']; ?>)"
                                   data-notification-id="<?php echo $notification['id']; ?>">
                                    <div class="d-flex align-items-start">
                                        <div class="me-2">
                                            <i class="<?php echo getNotificationIcon($notification['notification_type']); ?>"></i>
                                            <?php if ($notification['is_urgent']): ?>
                                                <span class="badge bg-danger badge-sm">!</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 small"><?php echo htmlspecialchars($notification['title']); ?></h6>
                                            <p class="mb-1 small text-muted">
                                                <?php echo htmlspecialchars(substr($notification['message'], 0, 80)); ?>
                                                <?php if (strlen($notification['message']) > 80): ?>...<?php endif; ?>
                                            </p>
                                            <small class="text-muted"><?php echo timeAgo($notification['created_at']); ?></small>
                                            <?php if (!$notification['is_viewed']): ?>
                                                <span class="badge bg-primary badge-sm ms-1">New</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endforeach; ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li>
                                <a class="dropdown-item text-center" href="<?php echo $basePath; ?>admin/notifications.php">
                                    <i class="fas fa-cog me-1"></i> Manage Notifications
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li>
                            <span class="dropdown-item-text">No notifications available.</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="<?php echo $basePath; ?>logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <!-- Sidebar Navigation -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <?php foreach ($navigationConfig as $sectionKey => $section): ?>
                            <?php if (isset($section['title'])): ?>
                                <div class="sb-sidenav-menu-heading"><?php echo $section['title']; ?></div>
                            <?php endif; ?>
                            
                            <?php foreach ($section['items'] as $item): ?>
                                <?php if (hasPermission($item, $userRole)): ?>
                                    <?php if (isset($item['type']) && $item['type'] === 'dropdown'): ?>
                                        <!-- Dropdown Menu Item -->
                                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" 
                                           data-bs-target="#<?php echo $item['id']; ?>" 
                                           aria-expanded="<?php echo isMenuActive($item, $currentPath) ? 'true' : 'false'; ?>" 
                                           aria-controls="<?php echo $item['id']; ?>">
                                            <div class="sb-nav-link-icon"><i class="<?php echo $item['icon']; ?>"></i></div>
                                            <?php echo $item['title']; ?>
                                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                        </a>
                                        <div class="collapse <?php echo isMenuActive($item, $currentPath) ? 'show' : ''; ?>" 
                                             id="<?php echo $item['id']; ?>" 
                                             aria-labelledby="headingOne" 
                                             data-bs-parent="#sidenavAccordion">
                                            <nav class="sb-sidenav-menu-nested nav">
                                                <?php foreach ($item['children'] as $child): ?>
                                                    <?php if (hasPermission($child, $userRole)): ?>
                                                        <a class="nav-link <?php echo (strpos($currentPath, $child['url']) !== false) ? 'active' : ''; ?>" 
                                                           href="<?php echo $child['url']; ?>">
                                                            <?php echo $child['title']; ?>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </nav>
                                        </div>
                                    <?php else: ?>
                                        <!-- Simple Menu Item -->
                                        <a class="nav-link <?php echo (strpos($currentPath, $item['url']) !== false) ? 'active' : ''; ?>" 
                                           href="<?php echo $item['url']; ?>">
                                            <div class="sb-nav-link-icon"><i class="<?php echo $item['icon']; ?>"></i></div>
                                            <?php echo $item['title']; ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $_SESSION['full_name']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notificationModalBody">
                <!-- Notification content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to mark notification as viewed
function markAsViewed(notificationId) {
    fetch('<?php echo $basePath; ?>includes/mark_notification_viewed.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            notification_id: notificationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the "New" badge and background highlight
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('bg-light');
                const newBadge = notificationItem.querySelector('.badge.bg-primary');
                if (newBadge) {
                    newBadge.remove();
                }
            }
            
            // Update the notification count in the bell icon
            updateNotificationCount();
        }
    })
    .catch(error => {
        console.error('Error marking notification as viewed:', error);
    });
}

// Function to update notification count
function updateNotificationCount() {
    fetch('<?php echo $basePath; ?>includes/get_notification_count.php')
    .then(response => response.json())
    .then(data => {
        const bell = document.querySelector('.fa-bell');
        const badge = document.querySelector('.position-absolute.top-0.start-100.translate-middle');
        
        if (data.count > 0) {
            if (!badge) {
                const newBadge = document.createElement('span');
                newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                newBadge.textContent = data.count;
                bell.parentNode.appendChild(newBadge);
            } else {
                badge.textContent = data.count;
            }
        } else {
            if (badge) {
                badge.remove();
            }
        }
    })
    .catch(error => {
        console.error('Error updating notification count:', error);
    });
}

// Auto-update notification count every 30 seconds
setInterval(updateNotificationCount, 30000);
</script>
