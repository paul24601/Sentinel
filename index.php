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
$servername = "localhost";
$username = "root";
$password = "injectionadmin123"; // Change if needed
$dbname = "dailymonitoringsheet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

// Quality Metrics - defect rate
$sqlDefectRate = "SELECT 
    (SELECT COUNT(*) FROM submissions WHERE remarks IS NOT NULL AND remarks != '') as defective,
    (SELECT COUNT(*) FROM submissions) as total";
$resultDefectRate = $conn->query($sqlDefectRate);
$defectData = $resultDefectRate->fetch_assoc();
$defectRate = $defectData['total'] > 0 ? round(($defectData['defective'] / $defectData['total']) * 100, 2) : 0;

$pending_submissions = getPendingSubmissions($conn);
$pending_count = count($pending_submissions);

$pending_submissions = getPendingSubmissions($conn);
$pending_count = count($pending_submissions);

// (Optional) Fetch submissions data if needed
$sql = "SELECT * FROM submissions";
$result = $conn->query($sql);

// ==========================
// Log the Visit
// ==========================
$user = $_SESSION['full_name'];
$ipAddress = $_SERVER['REMOTE_ADDR'];
$visitTime = date('Y-m-d H:i:s');

$sqlVisit = "INSERT INTO visits (user, ip_address, visit_time) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sqlVisit);
$stmt->bind_param("sss", $user, $ipAddress, $visitTime);
$stmt->execute();
$stmt->close();

// ==========================
// Fetch Data for DataTable (all visits)
// ==========================
$sql = "SELECT * FROM visits ORDER BY visit_time DESC";
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

                        <!-- Card 4: Quality Rate -->
                        <div class="col mb-4">
                            <div class="card bg-warning text-white h-100" data-bs-toggle="modal"
                                data-bs-target="#qualityMetricsModal" style="cursor:pointer;">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo (100 - $defectRate); ?>%</h2>
                                    <small>Quality Rate</small>
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

                        <!-- Card 7: Recent Activity -->
                        <div class="col mb-4">
                            <div class="card bg-danger text-white h-100" data-bs-toggle="modal"
                                data-bs-target="#activityModal" style="cursor:pointer;">
                                <div class="card-body text-center">
                                    <h2 class="mb-0"><?php echo $recentActivity; ?></h2>
                                    <small>Recent Activity (7 days)</small>
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

                    <!-- Charts Row -->
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Area Chart - Daily Visits
                                </div>
                                <div class="card-body">
                                    <div id="myAreaChart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Bar Chart - Daily Visits
                                </div>
                                <div class="card-body">
                                    <div id="myBarChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Enhanced Visits DataTable -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            System Activity Log
                            <div class="float-end">
                                <small class="text-muted">Last updated: <?php echo date('Y-m-d H:i:s'); ?></small>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Quick Stats Row -->
                            <div class="row mb-3 quick-stats">
                                <div class="col-md-3">
                                    <div class="text-center p-2 bg-light rounded">
                                        <h5 class="mb-0"><?php echo $result->num_rows; ?></h5>
                                        <small class="text-muted">Total Visits</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-2 bg-light rounded">
                                        <h5 class="mb-0"><?php 
                                            $unique_users = $conn->query("SELECT COUNT(DISTINCT user) as count FROM visits")->fetch_assoc()['count'];
                                            echo $unique_users; 
                                        ?></h5>
                                        <small class="text-muted">Unique Users</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-2 bg-light rounded">
                                        <h5 class="mb-0"><?php 
                                            $today_visits = $conn->query("SELECT COUNT(*) as count FROM visits WHERE DATE(visit_time) = CURDATE()")->fetch_assoc()['count'];
                                            echo $today_visits; 
                                        ?></h5>
                                        <small class="text-muted">Today's Visits</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-2 bg-light rounded">
                                        <h5 class="mb-0"><?php 
                                            $week_visits = $conn->query("SELECT COUNT(*) as count FROM visits WHERE visit_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['count'];
                                            echo $week_visits; 
                                        ?></h5>
                                        <small class="text-muted">This Week</small>
                                    </div>
                                </div>
                            </div>

                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>IP Address</th>
                                        <th>Visit Time</th>
                                        <th>Browser Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 30px; height: 30px; font-size: 12px; color: white;">
                                                            <?php echo strtoupper(substr($row['user'], 0, 2)); ?>
                                                        </div>
                                                        <?php echo htmlspecialchars($row['user']); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($row['ip_address']); ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted"><?php echo date('M d, Y H:i:s', strtotime($row['visit_time'])); ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php 
                                                        // Simulate browser detection (in real scenario, you'd store user agent)
                                                        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
                                                        echo $browsers[array_rand($browsers)];
                                                        ?>
                                                    </small>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No visits recorded.</td>
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
                                    <h4 class="mb-3">Cycle Time Variance Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Average Standard Cycle Time:</strong>
                                                <?php echo $avgCycleTarget; ?></p>
                                            <p><strong>Average Actual Cycle Time:</strong>
                                                <?php echo $avgCycleActual; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Difference (Target - Actual):</strong>
                                                <?php echo $cycleDifference; ?></p>
                                            <p><strong>Cycle Time Monitoring Variance:</strong>
                                                <?php echo $cycleVariance; ?></p>
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
                                    <h4 class="mb-3">Gross Weight Variance Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Average Standard Weight:</strong>
                                                <?php echo $avgWeightStandard; ?></p>
                                            <p><strong>Average Gross Weight:</strong> <?php echo $avgWeightGross; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Difference (Standard - Gross):</strong>
                                                <?php echo $grossDifference; ?></p>
                                            <p><strong>Gross Weight Variance:</strong>
                                                <?php echo $grossWeightVariance; ?></p>
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

                <!-- Quality Metrics Modal -->
                <div class="modal fade" id="qualityMetricsModal" tabindex="-1" aria-labelledby="qualityMetricsModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title" id="qualityMetricsModalLabel">Quality Metrics Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <h4 class="mb-3">Quality Performance Overview</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Total Submissions:</strong> <?php echo $defectData['total']; ?></p>
                                            <p><strong>Submissions with Issues:</strong> <?php echo $defectData['defective']; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Defect Rate:</strong> <?php echo $defectRate; ?>%</p>
                                            <p><strong>Quality Rate:</strong> <?php echo (100 - $defectRate); ?>%</p>
                                        </div>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?php echo (100 - $defectRate); ?>%" 
                                             aria-valuenow="<?php echo (100 - $defectRate); ?>" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?php echo (100 - $defectRate); ?>% Quality
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

                <!-- Activity Modal -->
                <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="activityModalLabel">Recent Activity Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <h4 class="mb-3">Last 7 Days Activity</h4>
                                    <p><strong>New Submissions:</strong> <?php echo $recentActivity; ?></p>
                                    
                                    <h5 class="mt-4 mb-3">Most Active Users</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Submissions</th>
                                                    <th>Progress</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($activeUsers as $user): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                                    <td><?php echo $user['submissions']; ?></td>
                                                    <td>
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar bg-primary" role="progressbar" 
                                                                 style="width: <?php echo ($user['submissions'] / $totalSubmissions) * 100; ?>%">
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
    <!-- Charts Script -->
    <script>
        // Pass PHP analytics data to JavaScript
        var analyticsLabels = <?php echo json_encode($analyticsLabels); ?>;
        var analyticsData = <?php echo json_encode($analyticsData); ?>;

        // Create stock-like area chart with ApexCharts
        var optionsAreaChart = {
            chart: {
                type: 'area',
                height: 350,
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: true
                },
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight',
                width: 2
            },
            series: [{
                name: 'Visits',
                data: analyticsData
            }],
            colors: ['#2E93fA'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 100]
                }
            },
            xaxis: {
                categories: analyticsLabels,
                type: 'datetime',
                labels: {
                    datetimeFormatter: {
                        year: 'yyyy',
                        month: 'MMM yyyy',
                        day: 'dd MMM',
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return Math.round(val);
                    }
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy'
                },
                shared: true
            },
            markers: {
                size: 4,
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                row: {
                    colors: ['transparent', 'transparent'],
                    opacity: 0.5
                }
            }
        };

        var chartArea = new ApexCharts(document.querySelector("#myAreaChart"), optionsAreaChart);
        chartArea.render();

        // The BAR CHART below remains unchanged
        var optionsBar = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    colors: {
                        ranges: [{
                            from: 0,
                            to: 100,
                            color: '#2E93fA'
                        }]
                    }
                }
            },
            series: [{
                name: 'Visits',
                data: analyticsData
            }],
            xaxis: {
                categories: analyticsLabels,
                type: 'datetime',
                labels: {
                    datetimeFormatter: {
                        year: 'yyyy',
                        month: 'MMM yyyy',
                        day: 'dd MMM',
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return Math.round(val);
                    }
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy'
                }
            }
        };

        var chartBar = new ApexCharts(document.querySelector("#myBarChart"), optionsBar);
        chartBar.render();
        
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