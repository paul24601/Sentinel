<?php
/**
 * Simplified Centralized Navigation System for Sentinel
 * Clean and minimal approach following SB Admin standards
 */

// Ensure session is started and admin notifications are loaded
if (!isset($admin_notifications)) {
    require_once __DIR__ . '/admin_notifications.php';
    $admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
    $notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);
}

// Simple function to determine relative paths
if (!function_exists('getBasePath')) {
    function getBasePath() {
        $currentDir = dirname($_SERVER['SCRIPT_NAME']);
        
        if (strpos($currentDir, '/dms') !== false || strpos($currentDir, '/parameters') !== false || 
            strpos($currentDir, '/admin') !== false || strpos($currentDir, '/production_report') !== false) {
            return '../';
        } else {
            return '';
        }
    }
}

$basePath = getBasePath();

// Helper function to check permissions
function hasPermission($item, $userRole) {
    if (isset($item['roles']) && !in_array($userRole, $item['roles'])) {
        return false;
    }
    
    if (isset($item['exclude_roles']) && in_array($userRole, $item['exclude_roles'])) {
        return false;
    }
    
    return true;
}

// Check if menu should be active/expanded
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sentinel Digitization</title>
    
    <!-- Load Bootstrap first -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SB Admin CSS -->
    <link href="<?php echo $basePath; ?>css/styles.css" rel="stylesheet" />
    
    <!-- Custom layout fixes - minimal and clean -->
    <link href="<?php echo $basePath; ?>css/custom-layout.css" rel="stylesheet">
    
    <!-- Responsive fixes for mobile/tablet -->
    <link href="<?php echo $basePath; ?>css/responsive-fixes.css" rel="stylesheet">
    
    <!-- Layout debug fixes -->
    <link href="<?php echo $basePath; ?>css/layout-debug-fix.css" rel="stylesheet">
    
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
        <a class="navbar-brand ps-3" href="<?php echo $basePath; ?>index.php">Sentinel Digitization</a>
        
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="notificationDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if ($notification_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $notification_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                    <?php if (!empty($admin_notifications)): ?>
                        <li><h6 class="dropdown-header">Recent Notifications</h6></li>
                        <?php foreach ($admin_notifications as $notification): ?>
                            <li>
                                <a class="dropdown-item" href="#" onclick="markAsViewed(<?php echo $notification['id']; ?>)">
                                    <small><?php echo htmlspecialchars(substr($notification['title'], 0, 50)); ?>...</small>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li><hr class="dropdown-divider" /></li>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a class="dropdown-item" href="<?php echo $basePath; ?>admin/notifications.php">Manage Notifications</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="#">No new notifications</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            
            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="<?php echo $basePath; ?>change_password.php">Change Password</a></li>
                    <li><hr class="dropdown-divider" /></li>
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
                        <!-- Core Section -->
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="<?php echo $basePath; ?>index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        
                        <!-- Systems Section -->
                        <div class="sb-sidenav-menu-heading">Systems</div>
                        
                        <!-- DMS -->
                        <?php if (in_array($userRole, ['admin', 'supervisor', 'personnel', 'manager', 'Quality Control Inspection'])): ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS" aria-expanded="false" aria-controls="collapseDMS">
                            <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                            DMS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseDMS" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php if ($userRole !== 'Quality Control Inspection'): ?>
                                    <a class="nav-link" href="<?php echo $basePath; ?>dms/index.php">Data Entry</a>
                                <?php endif; ?>
                                <a class="nav-link" href="<?php echo $basePath; ?>dms/submission.php">Records</a>
                                <?php if (in_array($userRole, ['admin', 'supervisor', 'manager'])): ?>
                                    <a class="nav-link" href="<?php echo $basePath; ?>dms/analytics.php">Analytics</a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Parameters -->
                        <?php if (in_array($userRole, ['admin', 'supervisor', 'personnel', 'manager', 'Quality Control Inspection'])): ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseParameters" aria-expanded="false" aria-controls="collapseParameters">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Parameters
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseParameters" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php if ($userRole !== 'Quality Control Inspection'): ?>
                                    <a class="nav-link" href="<?php echo $basePath; ?>parameters/index.php">Data Entry</a>
                                <?php endif; ?>
                                <a class="nav-link" href="<?php echo $basePath; ?>parameters/submission.php">Records</a>
                                <?php if (in_array($userRole, ['admin', 'supervisor', 'manager'])): ?>
                                    <a class="nav-link" href="<?php echo $basePath; ?>parameters/analytics.php">Analytics</a>
                                <?php endif; ?>
                            </nav>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Production Report -->
                        <?php if ($userRole !== 'Quality Control Inspection'): ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProduction" aria-expanded="false" aria-controls="collapseProduction">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Production Report
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseProduction" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?php echo $basePath; ?>production_report/index.php">Data Entry</a>
                                <a class="nav-link" href="<?php echo $basePath; ?>production_report/submit.php">Submit Report</a>
                            </nav>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Admin Section -->
                        <?php if (in_array($userRole, ['admin', 'supervisor', 'manager'])): ?>
                        <div class="sb-sidenav-menu-heading">Admin</div>
                        <a class="nav-link" href="<?php echo $basePath; ?>admin/users.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Users
                        </a>
                        <?php if ($userRole === 'admin'): ?>
                        <a class="nav-link" href="<?php echo $basePath; ?>admin/password_reset_management.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                            Password Reset
                        </a>
                        <?php endif; ?>
                        <a class="nav-link" href="<?php echo $basePath; ?>admin/product_parameters.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                            Product Parameters
                        </a>
                        <a class="nav-link" href="<?php echo $basePath; ?>admin/notifications.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-bell"></i></div>
                            Notifications
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $_SESSION['full_name']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
