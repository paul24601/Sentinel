<?php
session_start();

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Initialize variables to prevent undefined variable warnings
$pending_count = 0;
$pending_submissions = [];

// Get database connection and notifications
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    
    // Get admin notifications for current user
    $admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
    $notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);
    
    // Get pending submissions for notifications
    $pending_sql = "SELECT id, product_name, date FROM injectionmoldingparameters WHERE status = 'pending' ORDER BY date DESC LIMIT 10";
    $pending_result = $conn->query($pending_sql);
    if ($pending_result && $pending_result->num_rows > 0) {
        $pending_submissions = $pending_result->fetch_all(MYSQLI_ASSOC);
        $pending_count = count($pending_submissions);
    }
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    // Initialize empty values on error
    $pending_count = 0;
    $pending_submissions = [];
    $admin_notifications = [];
    $notification_count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Report System</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></nav>
    <!-- Add custom CSS -->
    <style>
        .form-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow-x: auto;
        }
        .form-floating > label {
            padding-left: 20px;
        }
        .btn-action {
            min-width: 120px;
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #0056b3) !important;
            background-color: #007bff !important;
            color: white !important;
            border: none !important;
            font-weight: 600 !important;
        }
        .time-input {
            width: 80px !important;
            min-width: 80px;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem;
        }
        .floating-add-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
    </style>
</head>

<body class="sb-nav fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel OJT</a>
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
                            <a class="nav-link" href="../index.php">
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
                                    <a class="nav-link" href="../dms/submission.php">Records</a>
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
                                    <a class="nav-link" href="../parameters/submission.php">Records</a>
                                </nav>
                            </div>
                        <?php else: ?>
                            <!-- Full sidebar for all other roles -->
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="../index.php">
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
                                    <a class="nav-link" href="../dms/index.php">Data Entry</a>
                                    <a class="nav-link" href="../dms/submission.php">Records</a>
                                    <a class="nav-link" href="../dms/analytics.php">Analytics</a>
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
                                    <a class="nav-link" href="../parameters/index.php">Data Entry</a>
                                    <a class="nav-link" href="../parameters/submission.php">Data Visualization</a>
                                    <a class="nav-link" href="../parameters/analytics.php">Data Analytics</a>
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
                                    <a class="nav-link" href="#">Data Entry</a>
                                    <a class="nav-link" href="#">Data Visualization</a>
                                    <a class="nav-link" href="#">Data Analytics</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Admin</div>
                            <a class="nav-link" href="../admin/users.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                                Users
                            </a>
                            <a class="nav-link" href="../admin/product_parameters.php">
                                <div class="sb-nav-link-icon active"><i class="fas fa-chart-area"></i></div>
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
            <main class="bg-light">
                <div class="container-fluid px-4 py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Production Quality Control Sheet</h2>
                        <button type="button" class="btn btn-info" id="autoFillTest">
                            <i class="fas fa-magic me-2"></i>Auto-fill Test Data
                        </button>
                    </div>

                    <form id="qualityControlForm" method="POST" action="submit.php" class="needs-validation" novalidate>
                        <!-- Header Information -->
                        <div class="form-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0">Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="plant" name="plant" required>
                                            <label for="plant">Plant</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="date" name="date" required>
                                            <label for="date">Date</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <select class="form-select" id="shift" name="shift" required>
                                                <option value="">Select Shift</option>
                                                <option value="Morning">Morning</option>
                                                <option value="Afternoon">Afternoon</option>
                                                <option value="Night">Night</option>
                                            </select>
                                            <label for="shift">Shift</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="shiftHours" name="shiftHours" required>
                                            <label for="shiftHours">Shift Hours</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="form-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0">Product Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="productName" name="productName" required>
                                            <label for="productName">Product Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="color" name="color" required>
                                            <label for="color">Color</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="partNo" name="partNo" required>
                                            <label for="partNo">Part No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ID Numbers Section -->
                        <div class="form-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0">ID Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="idNumber1" name="idNumber1">
                                            <label for="idNumber1">ID Number 1</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="idNumber2" name="idNumber2">
                                            <label for="idNumber2">ID Number 2</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="idNumber3" name="idNumber3">
                                            <label for="idNumber3">ID Number 3</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="ejo" name="ejo">
                                            <label for="ejo">EJO #</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assembly Line Section -->
                        <div class="form-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0">Assembly Line Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="assemblyLine" name="assemblyLine" required>
                                            <label for="assemblyLine">Assembly Line # / Table #</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="manpower" name="manpower" required>
                                            <label for="manpower">Manpower Allocation</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="reg" name="reg">
                                            <label for="reg">REG</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="ot" name="ot">
                                            <label for="ot">OT</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quality Control Table -->
                        <div class="table-section">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Quality Control Records</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="addRow">
                                    <i class="fas fa-plus me-2"></i>Add Row
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="qualityTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="min-width: 200px;">Part Name</th>
                                            <th style="min-width: 200px;">Defect</th>
                                            <th colspan="8" class="text-center">Time</th>
                                            <th style="min-width: 100px;">Total</th>
                                            <th style="width: 50px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="qualityTableBody">
                                        <tr>
                                            <td><input type="text" class="form-control" name="partName[]"></td>
                                            <td><input type="text" class="form-control" name="defect[]"></td>
                                            <td><input type="number" class="form-control time-input" name="time1[]"></td>
                                            <td><input type="number" class="form-control time-input" name="time2[]"></td>
                                            <td><input type="number" class="form-control time-input" name="time3[]"></td>
                                            <td><input type="number" class="form-control time-input" name="time4[]"></td>
                                            <td><input type="number" class="form-control time-input" name="time5[]"></td>
                                            <td><input type="number" class="form-control time-input" name="time6[]"></td>
                                            <td><input type="number" class="form-control time-input" name="time7[]"></td>
                                            <td><input type="number" class="form-control time-input" name="time8[]"></td>
                                            <td><input type="number" class="form-control total" name="total[]" readonly></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm delete-row">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="2" class="fw-bold">Total Reject</td>
                                            <td colspan="8">
                                                <input type="number" class="form-control" id="totalReject" name="totalReject" readonly>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="totalRejectSum" name="totalRejectSum" readonly>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="fw-bold">Total Good</td>
                                            <td colspan="8">
                                                <input type="number" class="form-control" id="totalGood" name="totalGood">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="totalGoodSum" name="totalGoodSum" readonly>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Downtime Section -->
                        <div class="table-section">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Downtime Records</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="addDowntimeRow">
                                    <i class="fas fa-plus me-2"></i>Add Downtime
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="downtimeTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 70%;">Description</th>
                                            <th>Time (minutes)</th>
                                            <th style="width: 50px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="downtimeTableBody">
                                        <tr>
                                            <td><input type="text" class="form-control" name="downtimeDesc[]"></td>
                                            <td><input type="number" class="form-control downtime-minutes" name="downtimeMinutes[]"></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm delete-downtime">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td class="fw-bold">Total Downtime</td>
                                            <td><input type="number" class="form-control" id="totalDowntime" readonly></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Remarks Section -->
                        <div class="form-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0">Additional Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="remarks" name="remarks" style="height: 100px"></textarea>
                                    <label for="remarks">Remarks</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2 mb-4">
                            <button type="button" class="btn btn-secondary btn-action" onclick="window.history.back()">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-action">
                                <i class="fas fa-save me-2"></i>Submit Form
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2025 Sentinel OJT</div>
                        <div>
                            <a href="#" class="text-decoration-none">Privacy Policy</a>
                            &middot;
                            <a href="#" class="text-decoration-none">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Add this JavaScript code at the end of the file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Add row to quality table
            $('#addRow').click(function() {
                const newRow = $('#qualityTableBody tr:first').clone();
                newRow.find('input').val('');
                $('#qualityTableBody').append(newRow);
            });

            // Add row to downtime table
            $('#addDowntimeRow').click(function() {
                const newRow = $('#downtimeTableBody tr:first').clone();
                newRow.find('input').val('');
                $('#downtimeTableBody').append(newRow);
            });

            // Delete row from quality table
            $(document).on('click', '.delete-row', function() {
                if ($('#qualityTableBody tr').length > 1) {
                    $(this).closest('tr').remove();
                    calculateTotals();
                }
            });

            // Delete row from downtime table
            $(document).on('click', '.delete-downtime', function() {
                if ($('#downtimeTableBody tr').length > 1) {
                    $(this).closest('tr').remove();
                    calculateDowntimeTotal();
                }
            });

            // Calculate row totals
            $(document).on('input', '.time-input', function() {
                calculateRowTotal($(this).closest('tr'));
                calculateTotals();
            });

            // Calculate downtime total
            $(document).on('input', '.downtime-minutes', function() {
                calculateDowntimeTotal();
            });

            function calculateRowTotal(row) {
                let total = 0;
                row.find('.time-input').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                row.find('.total').val(total);
            }

            function calculateTotals() {
                let totalReject = 0;
                $('#qualityTableBody tr').each(function() {
                    totalReject += parseInt($(this).find('.total').val()) || 0;
                });
                $('#totalReject').val(totalReject);
                $('#totalRejectSum').val(totalReject);
            }

            function calculateDowntimeTotal() {
                let total = 0;
                $('.downtime-minutes').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                $('#totalDowntime').val(total);
            }

            // Form validation
            const form = document.getElementById('qualityControlForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            // Auto-fill test data
            $('#autoFillTest').click(function() {
                // Add your auto-fill logic here
                $('#plant').val('Plant A');
                $('#date').val('2024-03-20');
                $('#shift').val('Morning');
                $('#shiftHours').val('8');
                // ... add more auto-fill fields as needed
            });
        });
    </script>
</body>

</html>