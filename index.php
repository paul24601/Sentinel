<?php
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
$sqlVariance = "SELECT (AVG(cycle_time_target) - AVG(cycle_time_actual)) * 100 AS cycle_time_monitoring_variance FROM submissions WHERE approval_status = 'approved'";
$resultVariance = $conn->query($sqlVariance);
if ($resultVariance && $resultVariance->num_rows > 0) {
    $rowVariance = $resultVariance->fetch_assoc();
    $cycleVariance = round($rowVariance['cycle_time_monitoring_variance'], 2);
} else {
    $cycleVariance = 0;
}

// Calculate Gross Weight Variance for approved submissions
$sqlGrossWeight = "SELECT (AVG(weight_standard) - AVG(weight_gross)) * 100 AS gross_weight_variance FROM submissions WHERE approval_status = 'approved'";
$resultGrossWeight = $conn->query($sqlGrossWeight);
if ($resultGrossWeight && $resultGrossWeight->num_rows > 0) {
    $rowGrossWeight = $resultGrossWeight->fetch_assoc();
    $grossWeightVariance = round($rowGrossWeight['gross_weight_variance'], 2);
} else {
    $grossWeightVariance = 0;
}

// Cycle Time Variance Breakdown
$sqlCycleBreakdown = "SELECT AVG(cycle_time_target) AS avg_cycle_target, AVG(cycle_time_actual) AS avg_cycle_actual FROM submissions WHERE approval_status = 'approved'";
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
$sqlGrossBreakdown = "SELECT AVG(weight_standard) AS avg_weight_standard, AVG(weight_gross) AS avg_weight_gross FROM submissions WHERE approval_status = 'approved'";
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

    // Base query for submissions pending overall approval
    $sql_pending = "SELECT id, product_name, `date` FROM submissions WHERE approval_status = 'pending'";

    // Check the role and append additional conditions for individual approvals
    if (in_array($role, ['supervisor', 'admin'])) {
        $sql_pending .= " AND (supervisor_status IS NULL OR supervisor_status = 'pending')";
    } elseif (in_array($role, ['Quality Assurance Engineer', 'Quality Assurance Supervisor', 'Quality Control Inspection'])) {
        $sql_pending .= " AND (qa_status IS NULL OR qa_status = 'pending')";
    }

    $sql_pending .= " ORDER BY `date` DESC";

    $result_pending = $conn->query($sql_pending);
    if ($result_pending && $result_pending->num_rows > 0) {
        while ($row = $result_pending->fetch_assoc()) {
            $pending[] = $row;
        }
    }
    return $pending;
}

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
                                    <a class="nav-link" href="dms/approval.php">Approvals</a>
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
                                    <a class="nav-link" href="dms/approval.php">Approvals</a>
                                    <a class="nav-link" href="dms/declined_submissions.php">Declined</a>
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

                            <div class="sb-sidenav-menu-heading">Admin</div>
                            <a class="nav-link" href="admin/users.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                                Users
                            </a>
                            <a class="nav-link" href="charts.html">
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
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
                    </ol>

                    <!-- Updated Cards Section for Metrics -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5">
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

                        <!-- Card 2: Internal Rejection -->
                        <div class="col mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body text-center">
                                    <h2 class="mb-0">0</h2>
                                    <small>Internal Rejection</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Gross Weight Variance -->
                        <div class="col mb-4">
                            <div class="card bg-success text-white h-100" data-bs-toggle="modal"
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

                        <!-- Card 4: Material Consumption -->
                        <div class="col mb-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body text-center">
                                    <h2 class="mb-0">0</h2>
                                    <small>Material Consumption</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 5: Productivity -->
                        <div class="col mb-4">
                            <div class="card bg-secondary text-white h-100">
                                <div class="card-body text-center">
                                    <h2 class="mb-0">0</h2>
                                    <small>Productivity</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
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
                    <!-- Visits DataTable -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Visits Log
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>IP Address</th>
                                        <th>Visit Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['user']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                                                <td><?php echo htmlspecialchars($row['visit_time']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">No visits recorded.</td>
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
                            <div class="modal-header bg-success text-white">
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
                    order: [[0, "desc"]]
                });
            }
        });
    </script>
    <!-- Charts Script -->
    <script>
        // Pass PHP analytics data to JavaScript
        var analyticsLabels = <?php echo json_encode($analyticsLabels); ?>;
        var analyticsData = <?php echo json_encode($analyticsData); ?>;

        // Updated LINE HISTOGRAM (stepline chart)
        var optionsLineHistogram = {
            chart: {
                type: 'line', // Use a line chart
                height: 350
            },
            stroke: {
                curve: 'stepline' // Makes the line appear like stepped sections (histogram-like)
            },
            series: [{
                name: 'Visits',
                data: analyticsData
            }],
            xaxis: {
                categories: analyticsLabels,
                labels: {
                    show: false  // Hide x-axis labels
                }
            },
            yaxis: {
                labels: {
                    show: false  // Hide y-axis labels
                }
            }
        };

        var chartLineHistogram = new ApexCharts(document.querySelector("#myAreaChart"), optionsLineHistogram);
        chartLineHistogram.render();

        // The BAR CHART below remains unchanged
        var optionsBar = {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Visits',
                data: analyticsData
            }],
            xaxis: {
                categories: analyticsLabels,
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            }
        };

        var chartBar = new ApexCharts(document.querySelector("#myBarChart"), optionsBar);
        chartBar.render();

    </script>

</body>

</html>