<?php
/**
 * Centralized Breadcrumb Component
 * Dynamic breadcrumb navigation with role-based access
 * 
 * @author Sentinel Development Team
 * @date August 20, 2025
 */

// Include navigation configuration if not already included
if (!function_exists('getCurrentPageInfo')) {
    $config_path = dirname(__FILE__) . '/navigation_config.php';
    if (file_exists($config_path)) {
        require_once $config_path;
    } else {
        die('Navigation configuration file not found');
    }
}

// Get current page info
$current_path = $_SERVER['REQUEST_URI'];
$page_info = getCurrentPageInfo($current_path);

// Calculate base path for URLs
function getBreadcrumbUrlBasePath() {
    $script_path = $_SERVER['SCRIPT_NAME'];
    $path_parts = explode('/', trim($script_path, '/'));
    
    // Remove the script filename to get directory path
    array_pop($path_parts);
    
    // Calculate how many levels deep we are from document root
    $depth = count($path_parts) - 1; // Subtract 1 for the root level
    
    return str_repeat('../', $depth);
}

$base_path = getBreadcrumbUrlBasePath();

// Get user role for access checks
$user_role = $_SESSION['user_type'] ?? 'guest';
?>

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="bg-light border-bottom">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <ol class="breadcrumb mb-0 py-3">
                    <!-- Home/Dashboard -->
                    <li class="breadcrumb-item">
                        <a href="<?= $base_path ?>index.php" class="text-decoration-none">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    
                    <?php if (count($page_info['breadcrumb']) > 1): ?>
                        <?php for ($i = 1; $i < count($page_info['breadcrumb']); $i++): ?>
                            <?php if ($i === count($page_info['breadcrumb']) - 1): ?>
                                <!-- Current page - no link -->
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?= htmlspecialchars($page_info['breadcrumb'][$i]) ?>
                                </li>
                            <?php else: ?>
                                <!-- Intermediate pages -->
                                <li class="breadcrumb-item">
                                    <?php
                                    // Generate appropriate link based on module
                                    $link_url = '';
                                    switch (strtolower($page_info['breadcrumb'][$i])) {
                                        case 'dms':
                                            $link_url = $base_path . 'dms/submission.php';
                                            break;
                                        case 'parameters':
                                            $link_url = $base_path . 'parameters/submission.php';
                                            break;
                                        case 'production reports':
                                            $link_url = $base_path . 'production_report/submit.php';
                                            break;
                                        case 'administration':
                                            $link_url = $base_path . 'admin/users.php';
                                            break;
                                        case 'sensory data':
                                            $link_url = $base_path . 'sensory_data/dashboard.php';
                                            break;
                                        default:
                                            $link_url = '#';
                                    }
                                    ?>
                                    <a href="<?= $link_url ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($page_info['breadcrumb'][$i]) ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
    </div>
</nav>

<!-- Page Header Section -->
<div class="bg-white border-bottom">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <!-- Page Title -->
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            <?php
                            // Add appropriate icon based on module
                            $icon_class = 'fas fa-file-alt';
                            switch ($page_info['module']) {
                                case 'dms':
                                    $icon_class = 'fas fa-people-roof';
                                    break;
                                case 'parameters':
                                    $icon_class = 'fas fa-columns';
                                    break;
                                case 'production_report':
                                    $icon_class = 'fas fa-sheet-plastic';
                                    break;
                                case 'admin':
                                    $icon_class = 'fas fa-cog';
                                    break;
                                case 'sensory_data':
                                    $icon_class = 'fas fa-microchip';
                                    break;
                                default:
                                    $icon_class = 'fas fa-tachometer-alt';
                            }
                            ?>
                            <i class="<?= $icon_class ?> me-2 text-primary"></i>
                            <?= htmlspecialchars($page_info['title']) ?>
                        </h1>
                        <?php if (!empty($page_info['module'])): ?>
                            <p class="text-muted mb-0">
                                <?php
                                // Add descriptive text based on page
                                switch ($page_info['module'] . '_' . $page_info['page']) {
                                    case 'dms_index':
                                        echo 'Submit new DMS data entries and manage quality records';
                                        break;
                                    case 'dms_submission':
                                        echo 'View and manage all DMS submission records';
                                        break;
                                    case 'dms_analytics':
                                        echo 'Analyze DMS data trends and generate reports';
                                        break;
                                    case 'dms_approval':
                                        echo 'Review and approve pending DMS submissions';
                                        break;
                                    case 'dms_declined_submissions':
                                        echo 'Manage declined and rejected submissions';
                                        break;
                                    case 'parameters_index':
                                        echo 'Enter and manage system parameters data';
                                        break;
                                    case 'parameters_submission':
                                        echo 'Visualize and review parameter data records';
                                        break;
                                    case 'parameters_analytics':
                                        echo 'Advanced analytics for parameter performance';
                                        break;
                                    case 'production_report_index':
                                        echo 'Create and submit production reports';
                                        break;
                                    case 'admin_users':
                                        echo 'Manage system users and permissions';
                                        break;
                                    case 'admin_notifications':
                                        echo 'System-wide notification management';
                                        break;
                                    default:
                                        echo 'Manage and monitor system operations';
                                }
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <?php
                        // Show relevant action buttons based on current page
                        switch ($page_info['module'] . '_' . $page_info['page']) {
                            case 'dms_submission':
                                if (in_array($user_role, ['admin', 'supervisor', 'personnel'])) {
                                    echo '<a href="' . $base_path . 'dms/index.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>New Entry
                                          </a>';
                                }
                                if (in_array($user_role, ['admin', 'supervisor'])) {
                                    echo '<a href="' . $base_path . 'dms/analytics.php" class="btn btn-info btn-sm">
                                            <i class="fas fa-chart-line me-1"></i>Analytics
                                          </a>';
                                }
                                break;
                                
                            case 'parameters_submission':
                                if (in_array($user_role, ['admin', 'supervisor', 'personnel'])) {
                                    echo '<a href="' . $base_path . 'parameters/index.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>New Entry
                                          </a>';
                                }
                                if (in_array($user_role, ['admin', 'supervisor'])) {
                                    echo '<a href="' . $base_path . 'parameters/analytics.php" class="btn btn-info btn-sm">
                                            <i class="fas fa-chart-bar me-1"></i>Analytics
                                          </a>';
                                }
                                break;
                                
                            case 'dms_analytics':
                            case 'parameters_analytics':
                                echo '<button class="btn btn-success btn-sm" onclick="exportData()">
                                        <i class="fas fa-download me-1"></i>Export
                                      </button>';
                                echo '<button class="btn btn-secondary btn-sm" onclick="refreshData()">
                                        <i class="fas fa-sync me-1"></i>Refresh
                                      </button>';
                                break;
                                
                            case 'admin_users':
                                if ($user_role === 'admin') {
                                    echo '<a href="' . $base_path . 'admin/add_user.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-user-plus me-1"></i>Add User
                                          </a>';
                                }
                                break;
                                
                            case 'admin_notifications':
                                if ($user_role === 'admin') {
                                    echo '<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createNotificationModal">
                                            <i class="fas fa-bell me-1"></i>Create Notification
                                          </button>';
                                }
                                break;
                        }
                        ?>
                        
                        <!-- Help button (always available) -->
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="fas fa-question-circle me-1"></i>Help
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="fas fa-question-circle me-2"></i>Page Help
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                // Dynamic help content based on current page
                switch ($page_info['module'] . '_' . $page_info['page']) {
                    case 'dms_index':
                        echo '<h6>DMS Data Entry</h6>
                              <p>Use this page to submit new DMS (Document Management System) entries. Fill in all required fields and click submit.</p>
                              <ul>
                                <li>Ensure all data is accurate before submission</li>
                                <li>Required fields are marked with an asterisk (*)</li>
                                <li>Data will be pending approval after submission</li>
                              </ul>';
                        break;
                    case 'dms_submission':
                        echo '<h6>DMS Records</h6>
                              <p>View and manage all DMS submission records. Use filters to find specific entries.</p>
                              <ul>
                                <li>Click on any record to view details</li>
                                <li>Use the search box to filter records</li>
                                <li>Export data using the export button</li>
                              </ul>';
                        break;
                    case 'admin_notifications':
                        echo '<h6>Notification Management</h6>
                              <p>Create and manage system-wide notifications for users.</p>
                              <ul>
                                <li>Create targeted notifications for specific user roles</li>
                                <li>Set priority levels for important announcements</li>
                                <li>Track notification delivery and read status</li>
                              </ul>';
                        break;
                    default:
                        echo '<h6>General Navigation</h6>
                              <p>Use the sidebar navigation to access different modules and features.</p>
                              <ul>
                                <li>Dashboard provides an overview of system status</li>
                                <li>Each module has specific permissions based on your role</li>
                                <li>Contact your administrator for additional access</li>
                              </ul>';
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?= $base_path ?>help/user_guide.php" class="btn btn-primary">
                    <i class="fas fa-book me-1"></i>Full User Guide
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.breadcrumb {
    background-color: transparent;
    margin-bottom: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
}

.breadcrumb-item a {
    color: #495057;
    transition: color 0.2s;
}

.breadcrumb-item a:hover {
    color: #007bff;
}

.breadcrumb-item.active {
    color: #6c757d;
    font-weight: 500;
}

.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}
</style>
