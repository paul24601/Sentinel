<?php
/**
 * UNIFIED CENTRALIZED NAVIGATION SYSTEM
 * Clean implementation addressing all layout issues
 * Version: 2.0 - September 2024
 */

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure admin notifications are loaded
if (!isset($admin_notifications)) {
    require_once __DIR__ . '/admin_notifications.php';
    $admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
    $notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);
}

// Simplified path detection
if (!function_exists('getBasePath')) {
    function getBasePath() {
        $currentDir = dirname($_SERVER['SCRIPT_NAME']);
        
        if (strpos($currentDir, '/dms') !== false || 
            strpos($currentDir, '/parameters') !== false || 
            strpos($currentDir, '/admin') !== false || 
            strpos($currentDir, '/production_report') !== false ||
            strpos($currentDir, '/sensory_data') !== false) {
            return '../';
        } else {
            return '';
        }
    }
}

$basePath = getBasePath();

// Permission check function
function hasPermission($item, $userRole) {
    if (!isset($item['permissions']) || empty($item['permissions'])) {
        return true;
    }
    return in_array($userRole, $item['permissions']);
}

$currentPath = $_SERVER['REQUEST_URI'];
$userRole = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sentinel Digitization System" />
    <meta name="author" content="Sentinel Development Team" />
    <title>Sentinel Digitization</title>
    
    <!-- Core CSS - Load in correct order -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>css/styles.css" rel="stylesheet" />
    <link href="<?php echo $basePath; ?>css/responsive-fixes.css" rel="stylesheet">
    <link href="<?php echo $basePath; ?>css/layout-debug-fix.css" rel="stylesheet">
    
    <!-- Optional: Custom layout (minimal) -->
    <link href="<?php echo $basePath; ?>css/custom-layout.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="sb-nav-fixed">
    <!-- Top Navigation -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="<?php echo $basePath; ?>index.php">
            <i class="fas fa-shield-alt me-2"></i>Sentinel Digitization
        </a>
        
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Right-side navbar items -->
        <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <?php if ($notification_count > 0): ?>
                        <span class="badge bg-danger"><?php echo $notification_count; ?></span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <?php if (!empty($admin_notifications) && count($admin_notifications) > 0): ?>
                        <?php foreach (array_slice($admin_notifications, 0, 5) as $notification): ?>
                            <li>
                                <a class="dropdown-item <?php echo $notification['viewed_by_admin'] == 0 ? 'fw-bold' : ''; ?>" 
                                   href="#" onclick="markAsViewed(<?php echo $notification['id']; ?>)">
                                    <div class="d-flex justify-content-between">
                                        <span><?php echo htmlspecialchars(substr($notification['message'], 0, 50)); ?>...</span>
                                        <small class="text-muted"><?php echo date('M j', strtotime($notification['created_at'])); ?></small>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <?php if (count($admin_notifications) > 5): ?>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item text-center" href="<?php echo $basePath; ?>admin/notifications.php">View All Notifications</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><span class="dropdown-item-text">No notifications available.</span></li>
                    <?php endif; ?>
                </ul>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                    <span class="d-none d-md-inline ms-1"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">Account</h6></li>
                    <li><a class="dropdown-item" href="<?php echo $basePath; ?>change_password.php"><i class="fas fa-key me-2"></i>Change Password</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="<?php echo $basePath; ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <!-- Main Layout Container -->
    <div id="layoutSidenav">
        <!-- Sidebar Navigation -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <!-- Core Section -->
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link <?php echo (strpos($currentPath, 'index.php') !== false && strpos($currentPath, '/dms/') === false && strpos($currentPath, '/admin/') === false) ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        
                        <!-- Systems Section -->
                        <div class="sb-sidenav-menu-heading">Systems</div>
                        
                        <!-- DMS -->
                        <?php if (in_array($userRole, ['admin', 'supervisor', 'personnel', 'manager', 'Quality Control Inspection'])): ?>
                        <a class="nav-link collapsed <?php echo (strpos($currentPath, '/dms/') !== false) ? 'active' : ''; ?>" 
                           href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS" 
                           aria-expanded="<?php echo (strpos($currentPath, '/dms/') !== false) ? 'true' : 'false'; ?>" 
                           aria-controls="collapseDMS">
                            <div class="sb-nav-link-icon"><i class="fas fa-database"></i></div>
                            DMS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse <?php echo (strpos($currentPath, '/dms/') !== false) ? 'show' : ''; ?>" 
                             id="collapseDMS" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php if ($userRole !== 'Quality Control Inspection'): ?>
                                    <a class="nav-link <?php echo (strpos($currentPath, 'dms/index.php') !== false) ? 'active' : ''; ?>" 
                                       href="<?php echo $basePath; ?>dms/index.php">
                                        <i class="fas fa-plus-circle me-2"></i>Data Entry
                                    </a>
                                <?php endif; ?>
                                <a class="nav-link <?php echo (strpos($currentPath, 'dms/submission.php') !== false) ? 'active' : ''; ?>" 
                                   href="<?php echo $basePath; ?>dms/submission.php">
                                    <i class="fas fa-list me-2"></i>Records
                                </a>
                                <?php if (in_array($userRole, ['admin', 'supervisor', 'manager'])): ?>
                                    <a class="nav-link <?php echo (strpos($currentPath, 'dms/analytics.php') !== false) ? 'active' : ''; ?>" 
                                       href="<?php echo $basePath; ?>dms/analytics.php">
                                        <i class="fas fa-chart-bar me-2"></i>Analytics
                                    </a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Parameters -->
                        <?php if (in_array($userRole, ['admin', 'supervisor', 'personnel', 'manager'])): ?>
                        <a class="nav-link collapsed <?php echo (strpos($currentPath, '/parameters/') !== false) ? 'active' : ''; ?>" 
                           href="#" data-bs-toggle="collapse" data-bs-target="#collapseParameters" 
                           aria-expanded="<?php echo (strpos($currentPath, '/parameters/') !== false) ? 'true' : 'false'; ?>" 
                           aria-controls="collapseParameters">
                            <div class="sb-nav-link-icon"><i class="fas fa-sliders-h"></i></div>
                            Parameters
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse <?php echo (strpos($currentPath, '/parameters/') !== false) ? 'show' : ''; ?>" 
                             id="collapseParameters" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?php echo (strpos($currentPath, 'parameters/submission.php') !== false) ? 'active' : ''; ?>" 
                                   href="<?php echo $basePath; ?>parameters/submission.php">
                                    <i class="fas fa-plus-circle me-2"></i>Add Parameters
                                </a>
                                <a class="nav-link <?php echo (strpos($currentPath, 'parameters/index.php') !== false) ? 'active' : ''; ?>" 
                                   href="<?php echo $basePath; ?>parameters/index.php">
                                    <i class="fas fa-list me-2"></i>View Parameters
                                </a>
                            </nav>
                        </div>
                        <?php endif; ?>

                        <!-- Production Report -->
                        <?php if (in_array($userRole, ['admin', 'supervisor', 'personnel', 'manager'])): ?>
                        <a class="nav-link <?php echo (strpos($currentPath, 'production_report') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>production_report/index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Production Report
                        </a>
                        <?php endif; ?>

                        <!-- Quality Control -->
                        <a class="nav-link <?php echo (strpos($currentPath, 'quality_control.php') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>quality_control.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-check-circle"></i></div>
                            Quality Control
                        </a>

                        <!-- Admin Section -->
                        <?php if ($userRole === 'admin'): ?>
                        <div class="sb-sidenav-menu-heading">Administration</div>
                        <a class="nav-link <?php echo (strpos($currentPath, 'admin/users.php') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>admin/users.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Users
                        </a>
                        <a class="nav-link <?php echo (strpos($currentPath, 'admin/product_parameters.php') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>admin/product_parameters.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                            Product Parameters
                        </a>
                        <a class="nav-link <?php echo (strpos($currentPath, 'admin/notifications.php') !== false) ? 'active' : ''; ?>" 
                           href="<?php echo $basePath; ?>admin/notifications.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-bell"></i></div>
                            Notifications
                            <?php if ($notification_count > 0): ?>
                                <span class="badge bg-danger ms-2"><?php echo $notification_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>
                    <div class="small text-muted"><?php echo htmlspecialchars($userRole); ?></div>
                </div>
            </nav>
        </div>
        
        <!-- Main Content Area -->
        <div id="layoutSidenav_content">
            <main>

<!-- Load Core JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $basePath; ?>js/scripts.js"></script>

<!-- Enhanced Sidebar Toggle -->
<script>
// Enhanced sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const body = document.body;
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            body.classList.toggle('sb-sidenav-toggled');
            
            // Store sidebar state
            localStorage.setItem('sb|sidebar-toggle', body.classList.contains('sb-sidenav-toggled'));
        });
    }
    
    // Restore sidebar state
    if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        body.classList.add('sb-sidenav-toggled');
    }
    
    // Handle responsive behavior
    function handleResponsiveLayout() {
        const windowWidth = window.innerWidth;
        
        if (windowWidth <= 991.98) {
            // On mobile/tablet: ensure sidebar is hidden by default
            body.classList.remove('sb-sidenav-toggled');
        }
    }
    
    // Check on load and resize
    handleResponsiveLayout();
    window.addEventListener('resize', handleResponsiveLayout);
});

// Notification management
function markAsViewed(notificationId) {
    fetch('<?php echo $basePath; ?>includes/mark_notification_viewed.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'notification_id=' + notificationId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh page or update UI
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
