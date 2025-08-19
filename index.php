<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: login.html");
    exit();
}

// Example: set the role if not already set (this line is optional; ensure you set the role during login)
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = "Quality Control Inspection"; // Or assign another role as needed
}

/// --- Database Connection & Notification Functionality --- //

// Load centralized database configuration
require_once __DIR__ . '/includes/database.php';

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Calculate Cycle Time Monitoring Variance for approved submissions
$sqlVariance = "SELECT (AVG(cycle_time_target) - AVG(cycle_time_actual)) * 100 AS cycle_time_monitoring_variance FROM submissions";
$resultVariance = $conn->query($sqlVariance);
if ($resultVariance && $resultVariance->num_rows > 0) {
    $rowVariance = $resultVariance->fetch_assoc();
    $cycleVariance = round($rowVariance['cycle_time_monitoring_variance'], 2);
} else {
    $cycleVariance = 0;
}

// Calculate Gross Weight Variance for approved submissions
$sqlGrossWeight = "SELECT (AVG(weight_standard) - AVG(weight_gross)) * 100 AS gross_weight_variance FROM submissions";
$resultGrossWeight = $conn->query($sqlGrossWeight);
if ($resultGrossWeight && $resultGrossWeight->num_rows > 0) {
    $rowGrossWeight = $resultGrossWeight->fetch_assoc();
    $grossWeightVariance = round($rowGrossWeight['gross_weight_variance'], 2);
} else {
    $grossWeightVariance = 0;
}

// Cycle Time Variance Breakdown
$sqlCycleBreakdown = "SELECT AVG(cycle_time_target) AS avg_cycle_target, AVG(cycle_time_actual) AS avg_cycle_actual FROM submissions";
$resultCycleBreakdown = $conn->query($sqlCycleBreakdown);
if ($resultCycleBreakdown && $resultCycleBreakdown->num_rows > 0) {
    $rowCycleBreakdown = $resultCycleBreakdown->fetch_assoc();
    $avgCycleTarget = round($rowCycleBreakdown['avg_cycle_target'], 2);
    $avgCycleActual = round($rowCycleBreakdown['avg_cycle_actual'], 2);
    $cycleDifference = round($avgCycleTarget - $avgCycleActual, 2);
    $cycleVariance = round($cycleDifference * 100, 2);
} else {
    $avgCycleTarget = $avgCycleActual = $cycleDifference = $cycleVariance = 0;
}

// Gross Weight Variance Breakdown
$sqlGrossBreakdown = "SELECT AVG(weight_standard) AS avg_weight_standard, AVG(weight_gross) AS avg_weight_gross FROM submissions";
$resultGrossBreakdown = $conn->query($sqlGrossBreakdown);
if ($resultGrossBreakdown && $resultGrossBreakdown->num_rows > 0) {
    $rowGrossBreakdown = $resultGrossBreakdown->fetch_assoc();
    $avgWeightStandard = round($rowGrossBreakdown['avg_weight_standard'], 2);
    $avgWeightGross = round($rowGrossBreakdown['avg_weight_gross'], 2);
    $grossDifference = round($avgWeightStandard - $avgWeightGross, 2);
    $grossWeightVariance = round($grossDifference * 100, 2);
} else {
    $avgWeightStandard = $avgWeightGross = $grossDifference = $grossWeightVariance = 0;
}

// Function to get pending submissions for notifications
function getPendingSubmissions($conn)
{
    $pending = [];
    // Get the user's role from the session
    $role = $_SESSION['role'];

    // Base query for submissions
    $sql_pending = "SELECT id, product_name, `date` FROM submissions";

    $sql_pending .= " ORDER BY `date` DESC LIMIT 10";

    $result_pending = $conn->query($sql_pending);
    if ($result_pending && $result_pending->num_rows > 0) {
        while ($row = $result_pending->fetch_assoc()) {
            $pending[] = $row;
        }
    }
    return $pending;
}

// Additional Dashboard Metrics
// Total Submissions
$sqlTotalSubmissions = "SELECT COUNT(*) as total FROM submissions";
$resultTotalSubmissions = $conn->query($sqlTotalSubmissions);
$totalSubmissions = $resultTotalSubmissions->fetch_assoc()['total'];

// Total Parameter Records
try {
    $sqlTotalParameters = "SELECT COUNT(*) as total FROM injectionmoldingparameters.parameter_records";
    $resultTotalParameters = $conn->query($sqlTotalParameters);
    $totalParameters = $resultTotalParameters ? $resultTotalParameters->fetch_assoc()['total'] : 0;
} catch (Exception $e) {
    $totalParameters = 0;
}

// Total Production Reports (connect to production database)
try {
    $prodConn = new mysqli($servername, $username, "", "productionreport"); // Empty password for production DB
    if (!$prodConn->connect_error) {
        $sqlTotalProduction = "SELECT COUNT(*) as total FROM production_reports";
        $resultTotalProduction = $prodConn->query($sqlTotalProduction);
        $totalProduction = $resultTotalProduction ? $resultTotalProduction->fetch_assoc()['total'] : 0;
        $prodConn->close();
    } else {
        $totalProduction = 0;
    }
} catch (Exception $e) {
    $totalProduction = 0;
}

// Recent Activity (last 7 days)
$sqlRecentActivity = "SELECT COUNT(*) as count FROM submissions WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$resultRecentActivity = $conn->query($sqlRecentActivity);
$recentActivity = $resultRecentActivity->fetch_assoc()['count'];

// Most Active Users (top 5)
$sqlActiveUsers = "SELECT name, COUNT(*) as submissions FROM submissions GROUP BY name ORDER BY submissions DESC LIMIT 5";
$resultActiveUsers = $conn->query($sqlActiveUsers);
$activeUsers = [];
while ($row = $resultActiveUsers->fetch_assoc()) {
    $activeUsers[] = $row;
}

// Machine Utilization
$sqlMachineUtil = "SELECT machine, COUNT(*) as usage_count FROM submissions GROUP BY machine ORDER BY usage_count DESC";
$resultMachineUtil = $conn->query($sqlMachineUtil);
$machineUtilization = [];
while ($row = $resultMachineUtil->fetch_assoc()) {
    $machineUtilization[] = $row;
}

// Enhanced Quality Metrics with detailed calculation
$sqlQualityDetails = "SELECT 
    COUNT(*) as total_submissions,
    COUNT(CASE WHEN remarks IS NOT NULL AND remarks != '' THEN 1 END) as defective_count,
    COUNT(CASE WHEN remarks IS NULL OR remarks = '' THEN 1 END) as good_count,
    AVG(CASE WHEN weight_gross > 0 THEN weight_gross END) as avg_weight,
    AVG(CASE WHEN cycle_time_actual > 0 THEN cycle_time_actual END) as avg_cycle_time
FROM submissions";
$resultQualityDetails = $conn->query($sqlQualityDetails);
$qualityDetails = $resultQualityDetails->fetch_assoc();

$qualityRate = $qualityDetails['total_submissions'] > 0 ? 
    round(($qualityDetails['good_count'] / $qualityDetails['total_submissions']) * 100, 2) : 0;

// Enhanced Recent Activity with breakdown - ensure data shows
$sqlRecentActivityDetails = "SELECT 
    COUNT(*) as total_week,
    COUNT(CASE WHEN DATE(date) = CURDATE() THEN 1 END) as today,
    COUNT(CASE WHEN DATE(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1 END) as yesterday,
    COUNT(DISTINCT COALESCE(name, 'Unknown')) as unique_users_week,
    COUNT(DISTINCT COALESCE(machine, 'Unknown')) as active_machines
FROM submissions 
WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$resultRecentDetails = $conn->query($sqlRecentActivityDetails);
$recentDetails = $resultRecentDetails->fetch_assoc();

// Debug: Check if we have any submissions at all
$totalSubmissions = $conn->query("SELECT COUNT(*) as count FROM submissions")->fetch_assoc()['count'];

// If no recent data but we have submissions, get all-time data
if (!$recentDetails || $recentDetails['total_week'] == 0) {
    if ($totalSubmissions > 0) {
        $sqlAllTimeDetails = "SELECT 
            COUNT(*) as total_week,
            COUNT(CASE WHEN DATE(date) = CURDATE() THEN 1 END) as today,
            COUNT(CASE WHEN DATE(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1 END) as yesterday,
            COUNT(DISTINCT COALESCE(name, 'Unknown')) as unique_users_week,
            COUNT(DISTINCT COALESCE(machine, 'Unknown')) as active_machines
        FROM submissions";
        $resultAllTime = $conn->query($sqlAllTimeDetails);
        $recentDetails = $resultAllTime->fetch_assoc();
    } else {
        $recentDetails = [
            'total_week' => 0,
            'today' => 0,
            'yesterday' => 0,
            'unique_users_week' => 0,
            'active_machines' => 0
        ];
    }
}

// Get active users from all sources for the Recent Activity modal
$sqlActiveUsers = "
    SELECT 
        user_name as name,
        user_type,
        activity_count as submissions
    FROM (
        SELECT 
            user as user_name,
            'System Access' as user_type,
            COUNT(*) as activity_count
        FROM visits 
        WHERE visit_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY user
        
        UNION ALL
        
        SELECT 
            name as user_name,
            'Production' as user_type,
            COUNT(*) as activity_count
        FROM submissions 
        WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND name IS NOT NULL
        GROUP BY name
    ) as combined_activity
    GROUP BY user_name
    ORDER BY SUM(activity_count) DESC
    LIMIT 10";
$resultActiveUsers = $conn->query($sqlActiveUsers);
$activeUsers = [];
$totalActivityCount = 1; // Prevent division by zero

if ($resultActiveUsers && $resultActiveUsers->num_rows > 0) {
    while ($row = $resultActiveUsers->fetch_assoc()) {
        $activeUsers[] = $row;
        $totalActivityCount += $row['submissions'];
    }
}

// Production Efficiency Metrics
$sqlProductionMetrics = "SELECT 
    COUNT(*) as total_production,
    AVG(cycle_time_actual) as avg_cycle_actual,
    AVG(cycle_time_target) as avg_cycle_target,
    COUNT(DISTINCT machine) as total_machines,
    COUNT(DISTINCT product_name) as total_products
FROM submissions WHERE date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
$resultProdMetrics = $conn->query($sqlProductionMetrics);
$prodMetrics = $resultProdMetrics->fetch_assoc();

$efficiency = ($prodMetrics['avg_cycle_target'] > 0) ? 
    round(($prodMetrics['avg_cycle_target'] / $prodMetrics['avg_cycle_actual']) * 100, 2) : 0;

// Simplified System Performance Data for Charts
$sqlSystemPerformance = "SELECT 
    DATE(date) as production_date,
    COUNT(*) as daily_production,
    COUNT(CASE WHEN remarks IS NULL OR remarks = '' THEN 1 END) as good_parts
FROM submissions 
WHERE date >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)
GROUP BY DATE(date) 
ORDER BY production_date";
$resultSystemPerf = $conn->query($sqlSystemPerformance);
$systemPerfData = [];
$systemPerfLabels = [];
$productionData = [];
$qualityData = [];

if ($resultSystemPerf && $resultSystemPerf->num_rows > 0) {
    while ($row = $resultSystemPerf->fetch_assoc()) {
        $systemPerfLabels[] = date('M d', strtotime($row['production_date']));
        $productionData[] = intval($row['daily_production']);
        $qualityData[] = intval($row['good_parts']);
    }
} else {
    // Default data if no submissions exist
    for ($i = 14; $i >= 0; $i--) {
        $systemPerfLabels[] = date('M d', strtotime("-$i days"));
        $productionData[] = 0;
        $qualityData[] = 0;
    }
}

// Simplified Machine Performance Analysis
$sqlMachinePerformance = "SELECT 
    machine,
    COUNT(*) as total_jobs,
    COUNT(CASE WHEN remarks IS NULL OR remarks = '' THEN 1 END) as good_parts
FROM submissions 
WHERE date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY machine 
ORDER BY total_jobs DESC 
LIMIT 8";
$resultMachinePerf = $conn->query($sqlMachinePerformance);
$machinePerformance = [];
$machineNames = [];
$machineJobs = [];
$machineQuality = [];

if ($resultMachinePerf && $resultMachinePerf->num_rows > 0) {
    while ($row = $resultMachinePerf->fetch_assoc()) {
        $machinePerformance[] = $row;
        $machineNames[] = $row['machine'];
        $machineJobs[] = intval($row['total_jobs']);
        $machineQuality[] = $row['total_jobs'] > 0 ? round(($row['good_parts'] / $row['total_jobs']) * 100, 1) : 0;
    }
} else {
    // Default data if no submissions exist
    $machineNames = ['Machine A', 'Machine B', 'Machine C'];
    $machineJobs = [0, 0, 0];
    $machineQuality = [0, 0, 0];
}

$pending_submissions = getPendingSubmissions($conn);
$pending_count = count($pending_submissions);

$pending_submissions = getPendingSubmissions($conn);
$pending_count = count($pending_submissions);

// (Optional) Fetch submissions data if needed
$sql = "SELECT * FROM submissions";
$result = $conn->query($sql);

// ==========================
// Enhanced Activity Tracking - Include all user types
// ==========================

// Get all user types from users table for comprehensive activity tracking
$sqlAllUsers = "SELECT full_name, role FROM users WHERE full_name IS NOT NULL AND full_name != ''";
$resultAllUsers = $conn->query($sqlAllUsers);
$allSystemUsers = [];
if ($resultAllUsers && $resultAllUsers->num_rows > 0) {
    while ($row = $resultAllUsers->fetch_assoc()) {
        $allSystemUsers[$row['full_name']] = $row['role'];
    }
}

// Log the current visit (includes all user types)
$user = $_SESSION['full_name'];
$userRole = isset($allSystemUsers[$user]) ? $allSystemUsers[$user] : 'User';
$ipAddress = $_SERVER['REMOTE_ADDR'];
$visitTime = date('Y-m-d H:i:s');

$sqlVisit = "INSERT INTO visits (user, ip_address, visit_time) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sqlVisit);
$stmt->bind_param("sss", $user, $ipAddress, $visitTime);
$stmt->execute();
$stmt->close();

// ==========================
// Fetch Enhanced Activity Data - All Users
// ==========================

// Get combined activity from visits and submissions
$sqlCombinedActivity = "
    SELECT 
        'login' as activity_type,
        user as user_name,
        visit_time as activity_time,
        ip_address,
        'System Login' as activity_description
    FROM visits 
    WHERE visit_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    
    UNION ALL
    
    SELECT 
        'submission' as activity_type,
        name as user_name,
        date as activity_time,
        'Production' as ip_address,
        CONCAT('Production: ', product_name, ' on ', machine) as activity_description
    FROM submissions 
    WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    
    ORDER BY activity_time DESC 
    LIMIT 100";

$resultCombinedActivity = $conn->query($sqlCombinedActivity);

// Also get the original visits data for the table display
$sql = "SELECT v.*, 
        CASE 
            WHEN u.role IS NOT NULL THEN u.role 
            ELSE 'User' 
        END as user_role
        FROM visits v 
        LEFT JOIN users u ON v.user = u.full_name 
        ORDER BY visit_time DESC LIMIT 50";
$result = $conn->query($sql);

// ==========================
// Analytics Data for Charts
// Count the number of visits per day.
// ==========================
$sqlAnalytics = "SELECT DATE(visit_time) AS day, COUNT(*) AS total FROM visits GROUP BY day ORDER BY day";
$resultAnalytics = $conn->query($sqlAnalytics);
$analyticsLabels = [];
$analyticsData = [];

if ($resultAnalytics && $resultAnalytics->num_rows > 0) {
    while ($row = $resultAnalytics->fetch_assoc()) {
        $analyticsLabels[] = $row['day'];
        $analyticsData[] = $row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Sentinel OJT</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .dropdown-menu.scrollable {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .progress {
            height: 8px;
        }
        
        .table th {
            border-top: none;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(45deg, #6f42c1, #e83e8c) !important;
        }
        
        .card-body h2 {
            font-weight: 700;
            font-size: 2.5rem;
        }
        
        .card-body small {
            font-size: 0.875rem;
            opacity: 0.9;
        }
        
        .badge {
            font-size: 0.75rem;
        }
        
        #datatablesSimple th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }
        
        .quick-stats .bg-light {
            border: 1px solid #e9ecef;
            transition: all 0.2s;
        }
        
        .quick-stats .bg-light:hover {
            background-color: #e9ecef !important;
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Sentinel OJT</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search (optional)-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
        <!-- Navbar Notifications and User Dropdown-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle position-relative" id="notifDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if ($pending_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $pending_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end scrollable" aria-labelledby="notifDropdown">
                    <?php if ($pending_count > 0): ?>
                        <?php foreach ($pending_submissions as $pending): ?>
                            <li>
                                <a class="dropdown-item" href="dms/approval.php#submission-<?php echo $pending['id']; ?>">
                                    Submission #<?php echo $pending['id']; ?> -
                                    <?php echo htmlspecialchars($pending['product_name']); ?>
                                    <br>
                                    <small><?php echo date("M d, Y", strtotime($pending['date'])); ?></small>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>
                            <span class="dropdown-item-text">No pending submissions.</span>
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
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <?php if ($_SESSION['role'] === 'Quality Control Inspection'): ?>
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link active" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>

                            <div class="sb-sidenav-menu-heading">Systems</div>
                            <!-- DMS with only Records and Approvals -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                                aria-expanded="false" aria-controls="collapseDMS">
                                <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                                DMS
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseDMS" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="dms/submission.php">Records</a>
                                </nav>
                            </div>

                            <!-- Parameters with only Records -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#collapseParameters" aria-expanded="false"
                                aria-controls="collapseParameters">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Parameters
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseParameters" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="parameters/submission.php">Records</a>
                                </nav>
                            </div>
                        <?php else: ?>
                            <!-- Full sidebar for all other roles -->
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link active" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>

                            <div class="sb-sidenav-menu-heading">Systems</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                                aria-expanded="false" aria-controls="collapseDMS">
                                <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                                DMS
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseDMS" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="dms/index.php">Data Entry</a>
                                    <a class="nav-link" href="dms/submission.php">Records</a>
                                    <a class="nav-link" href="dms/analytics.php">Analytics</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#collapseParameters" aria-expanded="false"
                                aria-controls="collapseParameters">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Parameters
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseParameters" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="parameters/index.php">Data Entry</a>
                                    <a class="nav-link" href="parameters/submission.php">Data Visualization</a>
                                    <a class="nav-link" href="parameters/analytics.php">Data Analytics</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#collapseProduction" aria-expanded="false"
                                aria-controls="collapseProduction">
                                <div class="sb-nav-link-icon"><i class="fas fa-sheet-plastic"></i></div>
                                Production
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseProduction" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="production_report/index.php">Data Entry</a>
                                    <a class="nav-link" href="#">Data Visualization</a>
                                    <a class="nav-link" href="#">Data Analytics</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Admin</div>
                            <a class="nav-link" href="admin/users.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                                Users
                            </a>
                            <a class="nav-link" href="admin/product_parameters.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Values
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Analysis
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
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                        <div>
                            <h1 class="mt-4">Dashboard</h1>
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item active">Injection Department</li>
                            </ol>
                        </div>
                        <div>
                            <small class="text-muted me-3">
                                <i class="fas fa-clock me-1"></i>
                                Last updated: <span id="lastUpdateTime"><?php echo date('H:i:s'); ?></span>
                            </small>
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Quick Actions Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-bolt me-1"></i>
                                    Quick Actions
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-lg-3 col-md-6">
                                            <a href="dms/submission.php" class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-plus me-1"></i>New DMS Entry
                                            </a>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <a href="parameters/submission.php" class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-cogs me-1"></i>Add Parameters
                                            </a>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <a href="production_report/submit.php" class="btn btn-info btn-sm w-100">
                                                <i class="fas fa-chart-line me-1"></i>Production Report
                                            </a>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <a href="quality_control.php" class="btn btn-warning btn-sm w-100">
                                                <i class="fas fa-check-circle me-1"></i>Quality Control
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Updated Cards Section for Metrics -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
                        <!-- Card 1: Cycle Time Monitoring Variance Target vs Actual -->
                        <div class="col mb-4">
                            <div class="card bg-primary text-white h-100" data-bs-toggle="modal"
                                data-bs-target="#varianceBreakdownModal" style="cursor:pointer;">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo $cycleVariance; ?></h2>
                                    <small>Cycle Time Monitoring Variance</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                                        data-bs-target="#varianceBreakdownModal">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Total Submissions -->
                        <div class="col mb-4">
                            <div class="card bg-success text-white h-100" style="cursor:pointer;" onclick="window.location.href='dms/submission.php'">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo $totalSubmissions; ?></h2>
                                    <small>Total DMS Submissions</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="dms/submission.php">View Records</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Gross Weight Variance -->
                        <div class="col mb-4">
                            <div class="card bg-info text-white h-100" data-bs-toggle="modal"
                                data-bs-target="#grossVarianceModal" style="cursor:pointer;">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo $grossWeightVariance; ?></h2>
                                    <small>Gross Weight Variance</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                                        data-bs-target="#grossVarianceModal">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Quality Rate - Enhanced with click modal -->
                        <div class="col mb-4">
                            <div class="card bg-warning text-white h-100" data-bs-toggle="modal"
                                data-bs-target="#qualityMetricsModal" style="cursor:pointer;">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo $qualityRate; ?>%</h2>
                                    <small>Quality Rate</small>
                                    <div class="mt-2">
                                        <i class="fas fa-chart-line"></i>
                                        <small class="d-block mt-1">
                                            <?php echo $qualityDetails['good_count']; ?>/<?php echo $qualityDetails['total_submissions']; ?> parts
                                        </small>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                                        data-bs-target="#qualityMetricsModal">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row of Cards -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
                        <!-- Card 5: Parameter Records -->
                        <div class="col mb-4">
                            <div class="card bg-secondary text-white h-100" style="cursor:pointer;" onclick="window.location.href='parameters/submission.php'">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo $totalParameters; ?></h2>
                                    <small>Parameter Records</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="parameters/submission.php">View Records</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 6: Production Reports -->
                        <div class="col mb-4">
                            <div class="card bg-dark text-white h-100" style="cursor:pointer;" onclick="window.location.href='production_report/index.php'">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo $totalProduction; ?></h2>
                                    <small>Production Reports</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="production_report/index.php">View Reports</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 7: Recent Activity - Enhanced -->
                        <div class="col mb-4">
                            <div class="card bg-danger text-white h-100" data-bs-toggle="modal"
                                data-bs-target="#activityModal" style="cursor:pointer;">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo $recentDetails['total_week']; ?></h2>
                                    <small>Recent Activity (7 days)</small>
                                    <div class="mt-2">
                                        <i class="fas fa-users"></i>
                                        <small class="d-block mt-1">
                                            <?php echo $recentDetails['unique_users_week']; ?> users, <?php echo $recentDetails['active_machines']; ?> machines
                                        </small>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                                        data-bs-target="#activityModal">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 8: Machine Utilization -->
                        <div class="col mb-4">
                            <div class="card bg-gradient-primary text-white h-100" data-bs-toggle="modal"
                                data-bs-target="#machineUtilModal" style="cursor:pointer; background: linear-gradient(45deg, #6f42c1, #e83e8c);">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo count($machineUtilization); ?></h2>
                                    <small>Active Machines</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                                        data-bs-target="#machineUtilModal">View Utilization</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Charts Row -->
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Production Trend Analysis (Last 30 Days)
                                    <small class="float-end">
                                        <i class="fas fa-info-circle" data-bs-toggle="tooltip" 
                                           title="Daily production output and quality tracking"></i>
                                    </small>
                                </div>
                                <div class="card-body">
                                    <div id="myAreaChart"></div>
                                    <div class="row mt-3">
                                        <div class="col-md-4 text-center">
                                            <h6 class="text-primary"><?php echo array_sum($productionData); ?></h6>
                                            <small class="text-muted">Total Production</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h6 class="text-success"><?php echo count(array_filter($productionData)); ?></h6>
                                            <small class="text-muted">Active Days</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h6 class="text-info"><?php echo !empty($productionData) ? round(array_sum($productionData)/count($productionData), 1) : 0; ?></h6>
                                            <small class="text-muted">Avg Daily Output</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Machine Performance Overview
                                    <small class="float-end">
                                        <i class="fas fa-info-circle" data-bs-toggle="tooltip" 
                                           title="Top performing machines by production volume"></i>
                                    </small>
                                </div>
                                <div class="card-body">
                                    <div id="myBarChart"></div>
                                    <div class="row mt-3">
                                        <div class="col-md-4 text-center">
                                            <h6 class="text-primary"><?php echo count($machinePerformance); ?></h6>
                                            <small class="text-muted">Active Machines</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h6 class="text-warning"><?php echo $efficiency; ?>%</h6>
                                            <small class="text-muted">Efficiency</small>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <h6 class="text-info"><?php echo !empty($machinePerformance) ? round(array_sum(array_column($machinePerformance, 'machine_quality_rate'))/count($machinePerformance), 1) : 0; ?>%</h6>
                                            <small class="text-muted">Avg Quality</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Enhanced System Activity Log -->
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <i class="fas fa-server me-1"></i>
                            System Activity Monitor
                            <div class="float-end">
                                <button class="btn btn-sm btn-outline-light" onclick="refreshDashboard()">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                                <small class="text-light ms-2">
                                    Last updated: <span id="lastUpdateTime"><?php echo date('H:i:s'); ?></span>
                                </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Enhanced Quick Stats Row -->
                            <div class="row mb-4 quick-stats">
                                <div class="col-md-2">
                                    <div class="text-center p-3 bg-light rounded border">
                                        <h5 class="mb-1 text-primary"><?php echo $result->num_rows; ?></h5>
                                        <small class="text-muted">Total Sessions</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center p-3 bg-light rounded border">
                                        <h5 class="mb-1 text-success"><?php 
                                            // Count all unique users from both visits and users table
                                            $unique_users_query = "SELECT COUNT(DISTINCT user_name) as count FROM (
                                                SELECT user as user_name FROM visits 
                                                UNION 
                                                SELECT name as user_name FROM submissions WHERE name IS NOT NULL
                                                UNION
                                                SELECT full_name as user_name FROM users WHERE full_name IS NOT NULL
                                            ) as all_users";
                                            $unique_users = $conn->query($unique_users_query)->fetch_assoc()['count'];
                                            echo $unique_users; 
                                        ?></h5>
                                        <small class="text-muted">All Users</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center p-3 bg-light rounded border">
                                        <h5 class="mb-1 text-warning"><?php 
                                            $today_visits = $conn->query("SELECT COUNT(*) as count FROM visits WHERE DATE(visit_time) = CURDATE()")->fetch_assoc()['count'];
                                            echo $today_visits; 
                                        ?></h5>
                                        <small class="text-muted">Today</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center p-3 bg-light rounded border">
                                        <h5 class="mb-1 text-info"><?php 
                                            $week_visits = $conn->query("SELECT COUNT(*) as count FROM visits WHERE visit_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['count'];
                                            echo $week_visits; 
                                        ?></h5>
                                        <small class="text-muted">This Week</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center p-3 bg-light rounded border">
                                        <h5 class="mb-1 text-secondary"><?php 
                                            $peak_hour = $conn->query("SELECT HOUR(visit_time) as hour, COUNT(*) as count FROM visits WHERE DATE(visit_time) = CURDATE() GROUP BY HOUR(visit_time) ORDER BY count DESC LIMIT 1")->fetch_assoc()['hour'] ?? date('H');
                                            echo sprintf('%02d:00', $peak_hour); 
                                        ?></h5>
                                        <small class="text-muted">Peak Hour</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center p-3 bg-light rounded border">
                                        <h5 class="mb-1 text-secondary"><?php 
                                            // Count admin users
                                            $admin_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role IN ('Admin', 'admin', 'Administrator')")->fetch_assoc()['count'];
                                            echo $admin_count; 
                                        ?></h5>
                                        <small class="text-muted">Admin Users</small>
                                    </div>
                                </div>
                            </div>

                            <table id="datatablesSimple" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Session ID</th>
                                        <th>User</th>
                                        <th>Login Time</th>
                                        <th>Duration</th>
                                        <th>Activity Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <?php
                                            // Calculate session duration (simulated)
                                            $sessionDuration = rand(5, 45); // minutes
                                            $isActive = (strtotime($row['visit_time']) > strtotime('-30 minutes'));
                                            ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px; font-size: 12px; color: white;">
                                                            <?php echo strtoupper(substr($row['user'], 0, 2)); ?>
                                                        </div>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($row['user']); ?></strong>
                                                            <?php if (isset($row['user_role']) && $row['user_role'] && $row['user_role'] != 'User'): ?>
                                                                <span class="badge bg-info ms-1"><?php echo htmlspecialchars($row['user_role']); ?></span>
                                                            <?php elseif (isset($allSystemUsers[$row['user']])): ?>
                                                                <span class="badge bg-info ms-1"><?php echo htmlspecialchars($allSystemUsers[$row['user']]); ?></span>
                                                            <?php endif; ?>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($row['ip_address']); ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?php echo date('M d, Y', strtotime($row['visit_time'])); ?></strong>
                                                        <br><small class="text-muted"><?php echo date('H:i:s', strtotime($row['visit_time'])); ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo $sessionDuration; ?>m</span>
                                                </td>
                                                <td>
                                                    <?php if ($isActive): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-circle" style="font-size: 8px;"></i> Active
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-circle" style="font-size: 8px;"></i> Ended
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                <br>No system activity recorded
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Variance Breakdown Modal for Cycle Time -->
                <div class="modal fade" id="varianceBreakdownModal" tabindex="-1"
                    aria-labelledby="varianceBreakdownModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="varianceBreakdownModalLabel">Cycle Time Variance Breakdown
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle me-2"></i>What is Cycle Time Monitoring Variance?</h6>
                                                <p class="mb-1"><strong>Cycle Time Variance</strong> measures how much actual production times differ from target times.</p>
                                                <p class="mb-0"><small>Formula: Variance = |Target Time - Actual Time| ÷ Target Time × 100</small></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <h4 class="mb-3">Cycle Time Variance Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">Target vs Actual</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Average Target Cycle Time:</strong>
                                                        <?php echo number_format($avgCycleTarget, 2); ?> seconds</p>
                                                    <p><strong>Average Actual Cycle Time:</strong>
                                                        <?php echo number_format($avgCycleActual, 2); ?> seconds</p>
                                                    <p><strong>Time Difference:</strong>
                                                        <?php echo number_format($cycleDifference, 2); ?> seconds</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header bg-success text-white">
                                                    <h6 class="mb-0">Performance Analysis</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Variance Percentage:</strong>
                                                        <?php echo number_format($cycleVariance, 2); ?>%</p>
                                                    <?php if ($cycleVariance <= 5): ?>
                                                        <div class="alert alert-success p-2">
                                                            <small><i class="fas fa-check-circle"></i> Excellent timing control</small>
                                                        </div>
                                                    <?php elseif ($cycleVariance <= 15): ?>
                                                        <div class="alert alert-warning p-2">
                                                            <small><i class="fas fa-exclamation-triangle"></i> Acceptable variance</small>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-danger p-2">
                                                            <small><i class="fas fa-exclamation-circle"></i> High variance - review needed</small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gross Weight Variance Breakdown Modal -->
                <div class="modal fade" id="grossVarianceModal" tabindex="-1" aria-labelledby="grossVarianceModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title" id="grossVarianceModalLabel">Gross Weight Variance Breakdown
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle me-2"></i>What is Gross Weight Monitoring Variance?</h6>
                                                <p class="mb-1"><strong>Weight Variance</strong> shows how much actual part weights differ from standard specifications.</p>
                                                <p class="mb-0"><small>Formula: Variance = |Standard Weight - Actual Weight| ÷ Standard Weight × 100</small></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <h4 class="mb-3">Gross Weight Variance Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header bg-info text-white">
                                                    <h6 class="mb-0">Weight Specifications</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Average Standard Weight:</strong>
                                                        <?php echo number_format($avgWeightStandard, 2); ?> grams</p>
                                                    <p><strong>Average Gross Weight:</strong> 
                                                        <?php echo number_format($avgWeightGross, 2); ?> grams</p>
                                                    <p><strong>Weight Difference:</strong>
                                                        <?php echo number_format($grossDifference, 2); ?> grams</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header bg-warning text-white">
                                                    <h6 class="mb-0">Quality Assessment</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Weight Variance:</strong>
                                                        <?php echo number_format($grossWeightVariance, 2); ?>%</p>
                                                    <?php if ($grossWeightVariance <= 2): ?>
                                                        <div class="alert alert-success p-2">
                                                            <small><i class="fas fa-check-circle"></i> Excellent weight consistency</small>
                                                        </div>
                                                    <?php elseif ($grossWeightVariance <= 5): ?>
                                                        <div class="alert alert-warning p-2">
                                                            <small><i class="fas fa-exclamation-triangle"></i> Acceptable weight variance</small>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-danger p-2">
                                                            <small><i class="fas fa-exclamation-circle"></i> High variance - check process</small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Quality Metrics Modal -->
                <div class="modal fade" id="qualityMetricsModal" tabindex="-1" aria-labelledby="qualityMetricsModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title" id="qualityMetricsModalLabel">
                                    <i class="fas fa-chart-pie me-2"></i>Quality Rate Analysis
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle me-2"></i>How Quality Rate is Calculated</h6>
                                                <p class="mb-0">Quality Rate = (Good Parts ÷ Total Parts) × 100</p>
                                                <small>Good parts are submissions without remarks or defects noted.</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border-success">
                                                <div class="card-header bg-success text-white">
                                                    <h6 class="mb-0">Production Summary</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row text-center">
                                                        <div class="col-6">
                                                            <h4 class="text-success"><?php echo $qualityDetails['good_count']; ?></h4>
                                                            <small>Good Parts</small>
                                                        </div>
                                                        <div class="col-6">
                                                            <h4 class="text-danger"><?php echo $qualityDetails['defective_count']; ?></h4>
                                                            <small>Defective Parts</small>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="text-center">
                                                        <h3 class="text-primary"><?php echo $qualityDetails['total_submissions']; ?></h3>
                                                        <small class="text-muted">Total Production</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="card border-warning">
                                                <div class="card-header bg-warning text-white">
                                                    <h6 class="mb-0">Quality Metrics</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Quality Rate:</span>
                                                            <strong class="text-warning"><?php echo $qualityRate; ?>%</strong>
                                                        </div>
                                                        <div class="progress mt-1">
                                                            <div class="progress-bar bg-warning" style="width: <?php echo $qualityRate; ?>%"></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Defect Rate:</span>
                                                            <strong class="text-danger"><?php echo round(100 - $qualityRate, 2); ?>%</strong>
                                                        </div>
                                                        <div class="progress mt-1">
                                                            <div class="progress-bar bg-danger" style="width: <?php echo 100 - $qualityRate; ?>%"></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if ($qualityDetails['avg_weight'] > 0): ?>
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Avg Weight:</span>
                                                            <strong><?php echo round($qualityDetails['avg_weight'], 2); ?>g</strong>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($qualityDetails['avg_cycle_time'] > 0): ?>
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Avg Cycle Time:</span>
                                                            <strong><?php echo round($qualityDetails['avg_cycle_time'], 2); ?>s</strong>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">Quality Interpretation</h6>
                                                </div>
                                                <div class="card-body">
                                                    <?php if ($qualityRate >= 95): ?>
                                                        <div class="alert alert-success">
                                                            <i class="fas fa-check-circle me-2"></i>
                                                            <strong>Excellent Quality Performance!</strong> Your quality rate of <?php echo $qualityRate; ?>% exceeds industry standards.
                                                        </div>
                                                    <?php elseif ($qualityRate >= 85): ?>
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            <strong>Good Quality Performance.</strong> Consider process improvements to reach 95%+ quality rate.
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-exclamation-circle me-2"></i>
                                                            <strong>Quality Improvement Needed.</strong> Investigate processes and implement corrective actions.
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="quality_control.php" class="btn btn-warning">View Quality Control</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Recent Activity Modal -->
                <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="activityModalLabel">
                                    <i class="fas fa-chart-line me-2"></i>Recent Activity Analysis (Last 7 Days)
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle me-2"></i>Activity Calculation Method</h6>
                                                <p class="mb-0">Recent Activity includes all production submissions, user logins, and system interactions in the past 7 days.</p>
                                                <small>This metric helps track production intensity and system usage patterns.</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="card">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">Weekly Activity Breakdown</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row text-center mb-3">
                                                        <div class="col-md-3">
                                                            <h4 class="text-primary"><?php echo $recentDetails['total_week']; ?></h4>
                                                            <small>Total Activities</small>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h4 class="text-success"><?php echo $recentDetails['today']; ?></h4>
                                                            <small>Today</small>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h4 class="text-warning"><?php echo $recentDetails['yesterday']; ?></h4>
                                                            <small>Yesterday</small>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h4 class="text-info"><?php echo round($recentDetails['total_week']/7, 1); ?></h4>
                                                            <small>Daily Average</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header bg-secondary text-white">
                                                    <h6 class="mb-0">Resource Utilization</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Active Users:</span>
                                                            <strong class="text-primary"><?php echo $recentDetails['unique_users_week']; ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Active Machines:</span>
                                                            <strong class="text-success"><?php echo $recentDetails['active_machines']; ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Avg per User:</span>
                                                            <strong class="text-info"><?php echo $recentDetails['unique_users_week'] > 0 ? round($recentDetails['total_week']/$recentDetails['unique_users_week'], 1) : 0; ?></strong>
                                                        </div>
                                                    </div>
                                                    <?php if ($totalSubmissions > 0): ?>
                                                    <div class="mt-3 pt-3 border-top">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle"></i> 
                                                            Total database records: <?php echo $totalSubmissions; ?>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">Top Active Users This Week</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>User</th>
                                                                    <th>Submissions</th>
                                                                    <th>Activity Level</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($activeUsers)): ?>
                                                                    <?php foreach ($activeUsers as $user): ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                                                     style="width: 25px; height: 25px; font-size: 10px; color: white;">
                                                                                    <?php echo strtoupper(substr($user['name'], 0, 2)); ?>
                                                                                </div>
                                                                                <div>
                                                                                    <?php echo htmlspecialchars($user['name']); ?>
                                                                                    <?php if (isset($allSystemUsers[$user['name']])): ?>
                                                                                        <br><small class="text-muted"><?php echo htmlspecialchars($allSystemUsers[$user['name']]); ?></small>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <span class="badge bg-primary"><?php echo $user['submissions']; ?></span>
                                                                        </td>
                                                                        <td>
                                                                            <div class="progress" style="height: 8px;">
                                                                                <div class="progress-bar bg-success" role="progressbar" 
                                                                                     style="width: <?php echo ($user['submissions'] / $totalActivityCount) * 100; ?>%">
                                                                                </div>
                                                                            </div>
                                                                            <small class="text-muted"><?php echo round(($user['submissions'] / $totalActivityCount) * 100, 1); ?>%</small>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <tr>
                                                                        <td colspan="3" class="text-center text-muted">
                                                                            <i class="fas fa-users fa-2x mb-2"></i>
                                                                            <br>No recent user activity found
                                                                        </td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">Activity Level Assessment</h6>
                                                </div>
                                                <div class="card-body">
                                                    <?php 
                                                    $dailyAvg = $recentDetails['total_week']/7;
                                                    if ($dailyAvg >= 20): ?>
                                                        <div class="alert alert-success">
                                                            <i class="fas fa-arrow-up me-2"></i>
                                                            <strong>High Activity Level!</strong> System showing strong utilization with <?php echo round($dailyAvg, 1); ?> activities per day.
                                                        </div>
                                                    <?php elseif ($dailyAvg >= 10): ?>
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-minus me-2"></i>
                                                            <strong>Moderate Activity.</strong> Consider encouraging more system usage for better data tracking.
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-arrow-down me-2"></i>
                                                            <strong>Low Activity Period.</strong> This might indicate reduced production or system adoption needs.
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="dms/submission.php" class="btn btn-danger">View All Activities</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Machine Utilization Modal -->
                <div class="modal fade" id="machineUtilModal" tabindex="-1" aria-labelledby="machineUtilModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header" style="background: linear-gradient(45deg, #6f42c1, #e83e8c); color: white;">
                                <h5 class="modal-title" id="machineUtilModalLabel">Machine Utilization Report</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <h4 class="mb-3">Machine Usage Statistics</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Machine</th>
                                                    <th>Usage Count</th>
                                                    <th>Utilization</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $maxUsage = $machineUtilization[0]['usage_count'] ?? 1;
                                                foreach ($machineUtilization as $machine): 
                                                    $percentage = ($machine['usage_count'] / $maxUsage) * 100;
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($machine['machine']); ?></td>
                                                    <td><?php echo $machine['usage_count']; ?></td>
                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar" role="progressbar" 
                                                                 style="width: <?php echo $percentage; ?>%; background: linear-gradient(45deg, #6f42c1, #e83e8c);"
                                                                 aria-valuenow="<?php echo $percentage; ?>" 
                                                                 aria-valuemin="0" aria-valuemax="100">
                                                                <?php echo round($percentage, 1); ?>%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2025 Sentinel OJT</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="js/datatables-simple-demo.js"></script>
    <script>
        // Initialize DataTable for the visits log
        document.addEventListener("DOMContentLoaded", event => {
            const datatablesSimple = document.getElementById("datatablesSimple");
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple, {
                    order: [[0, "desc"]],
                    perPage: 15,
                    perPageSelect: [10, 15, 25, 50],
                    searchable: true,
                    sortable: true,
                    fixedHeight: true,
                    labels: {
                        placeholder: "Search activity logs...",
                        perPage: "entries per page",
                        noRows: "No activity found",
                        info: "Showing {start} to {end} of {rows} entries"
                    },
                    columns: [
                        {
                            select: 0,
                            type: "number"
                        },
                        {
                            select: 3,
                            type: "date",
                            format: "YYYY-MM-DD HH:mm:ss"
                        }
                    ]
                });
            }
        });

        // Add some interactive features
        document.addEventListener("DOMContentLoaded", function() {
            // Animate cards on page load
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });
        });
    </script>
    <!-- Enhanced Charts Script -->
    <script>
        // Simplified production data for charts
        var productionLabels = <?php echo json_encode($systemPerfLabels); ?>;
        var productionData = <?php echo json_encode($productionData); ?>;
        var qualityData = <?php echo json_encode($qualityData); ?>;
        
        // Simplified machine performance data
        var machineNames = <?php echo json_encode($machineNames); ?>;
        var machineJobs = <?php echo json_encode($machineJobs); ?>;
        var machineQuality = <?php echo json_encode($machineQuality); ?>;

        // Simplified Production Trend Area Chart
        var optionsAreaChart = {
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            series: [
                {
                    name: 'Total Production',
                    data: productionData,
                    color: '#28a745'
                },
                {
                    name: 'Good Parts',
                    data: qualityData,
                    color: '#007bff'
                }
            ],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            xaxis: {
                categories: productionLabels,
                title: {
                    text: 'Production Days'
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Parts'
                }
            },
            tooltip: {
                shared: true,
                intersect: false
            },
            legend: {
                position: 'top'
            }
        };

        var chartArea = new ApexCharts(document.querySelector("#myAreaChart"), optionsAreaChart);
        chartArea.render();

        // Simplified Machine Performance Bar Chart
        var optionsBar = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    distributed: true,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            series: [{
                name: 'Production Jobs',
                data: machineJobs.map((jobs, index) => ({
                    x: machineNames[index],
                    y: jobs,
                    fillColor: `hsl(${(index * 60) % 360}, 70%, 50%)`
                }))
            }],
            xaxis: {
                type: 'category',
                title: {
                    text: 'Machines'
                },
                labels: {
                    rotate: -45
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Jobs'
                },
                labels: {
                    formatter: function (val) {
                        return Math.round(val);
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ['#304758']
                }
            },
            tooltip: {
                y: {
                    formatter: function (val, { dataPointIndex }) {
                        const qualityRate = machineQuality[dataPointIndex] || 0;
                        return val + ' jobs<br>Quality Rate: ' + Math.round(qualityRate) + '%';
                    }
                }
            },
            colors: ['#2E93fA', '#66DA26', '#546E7A', '#E91E63', '#FF9800'],
            grid: {
                borderColor: '#f1f1f1'
            }
        };

        var chartBar = new ApexCharts(document.querySelector("#myBarChart"), optionsBar);
        chartBar.render();
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Dashboard refresh functionality
        function refreshDashboard() {
            const refreshBtn = document.querySelector('[onclick="refreshDashboard()"]');
            const icon = refreshBtn.querySelector('i');
            
            // Add loading animation
            icon.classList.add('fa-spin');
            refreshBtn.disabled = true;
            
            // Update time
            document.getElementById('lastUpdateTime').textContent = new Date().toLocaleTimeString();
            
            // Refresh page after a short delay to show the animation
            setTimeout(() => {
                location.reload();
            }, 500);
        }
        
        // Auto-refresh every 5 minutes
        setInterval(() => {
            const lastUpdate = document.getElementById('lastUpdateTime');
            if (lastUpdate) {
                lastUpdate.textContent = new Date().toLocaleTimeString();
            }
        }, 300000); // 5 minutes
        
        // Add smooth transitions to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

</body>

</html>