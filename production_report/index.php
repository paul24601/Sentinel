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
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></nav>
    <!-- Add custom CSS -->
    <style>
        .form-check-label {
            cursor: pointer;
            padding: 20px;
            border: 2px solid #e3e6f0;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-check-input:checked + .form-check-label {
            border-color: #4e73df;
            background-color: rgba(78, 115, 223, 0.15);
            box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
        }
        
        .form-check-label:hover {
            border-color: #4e73df;
            background-color: rgba(78, 115, 223, 0.08);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .injection-section, .finishing-section {
            transition: all 0.5s ease;
        }
        
        .form-section {
            margin-bottom: 2.5rem;
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .form-section .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px;
            font-weight: 600;
        }
        
        .form-section .card-body {
            padding: 25px;
            background-color: #fafbfc;
        }
        
        .table-section {
            margin-bottom: 2.5rem;
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .table-section .card-header {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
            color: white;
            border: none;
            padding: 20px;
            font-weight: 600;
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e3e6f0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-action {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .autocomplete-container {
            position: relative;
        }
        
        .autocomplete-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 9999;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .autocomplete-search {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .autocomplete-items {
            max-height: 200px;
            overflow-y: auto;
        }
        
        .autocomplete-item {
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s ease;
        }
        
        .autocomplete-item:hover,
        .autocomplete-item.selected {
            background-color: #f8f9fa;
        }
        
        .autocomplete-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-toggle-custom {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        
        .shift-hours-display {
            background-color: #e9ecef;
            border: 2px solid #e3e6f0;
            border-radius: 8px;
            padding: 12px 15px;
            font-weight: 600;
            color: #495057;
        }
        
        .report-type-card {
            background: white;
            color: #333;
            margin-bottom: 2rem;
            border: 1px solid #e3e6f0;
        }
        
        .report-type-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }

        /* jQuery UI Autocomplete Styling */
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            background: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            z-index: 10000 !important;
            font-family: inherit;
        }

        .ui-menu-item {
            padding: 0;
        }

        .ui-menu-item-wrapper {
            padding: 8px 12px;
            border: none;
            font-size: 0.875rem;
            cursor: pointer;
            color: #212529;
        }

        .ui-menu-item-wrapper:hover,
        .ui-state-active {
            background-color: #e9ecef;
            border: none;
            color: #212529;
        }

        .finishing-section, .injection-section, .injection-machine-section {
            transition: all 0.3s ease;
        }
        
        /* Enhanced Button Styles */
        .btn-action {
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-action:disabled {
            transform: none;
            cursor: not-allowed;
        }
        
        /* Loading spinner animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        
        /* Toast Notifications */
        .toast {
            min-width: 300px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .toast-header {
            border-bottom: none;
            font-weight: 600;
        }
        
        .toast-body {
            font-size: 14px;
            padding: 15px;
        }
        
        /* Form enhancement */
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .was-validated .form-control:valid {
            border-color: #28a745;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.5-.5.5.5L4.5 6l.5.5.5-.5L6.5 6l.5.5.5-.5L8 5.5l-.5-.5-.5.5-.5-.5-.5.5-.5-.5-.5.5L4 5l-.5.5-.5-.5L2 5l-.5.5-.5-.5L0 4.5l.5-.5.5.5.5-.5.5.5.5-.5.5.5L3 4l.5-.5.5.5.5-.5.5.5z'/%3e%3c/svg%3e");
        }
        
        /* Success animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        .toast.show {
            animation: fadeInUp 0.3s ease;
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
                                data-bs-target="#collapseProduction" aria-expanded="true"
                                aria-controls="collapseProduction">
                                <div class="sb-nav-link-icon"><i class="fas fa-industry"></i></div>
                                Production Reports
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse show" id="collapseProduction" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link active" href="index.php">Create Report</a>
                                    <a class="nav-link" href="records.php">View Records</a>
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

                    <!-- Report Type Selection -->
                    <div class="card mb-4 report-type-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Select Report Type</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportTypeSelection" id="finishingReport" value="finishing" checked>
                                        <label class="form-check-label" for="finishingReport">
                                            <i class="fas fa-tools fa-2x mb-2 d-block"></i>
                                            <strong>Finishing Report</strong>
                                            <br><small>For finishing operations and assembly processes</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reportTypeSelection" id="injectionReport" value="injection">
                                        <label class="form-check-label" for="injectionReport">
                                            <i class="fas fa-cogs fa-2x mb-2 d-block"></i>
                                            <strong>Injection Report</strong>
                                            <br><small>For injection molding operations and processes</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form id="qualityControlForm" method="POST" action="submit.php" class="needs-validation" novalidate>
                        <input type="hidden" name="reportType" id="selectedReportType" value="finishing">
                        <!-- Header Information -->
                        <div class="form-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" id="date" name="date" required>
                                            <label for="date">Date</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select" id="shift" name="shift" required>
                                                <option value="">Select Shift</option>
                                                <option value="1st-8hr">1st Shift (8 Hours)</option>
                                                <option value="2nd-8hr">2nd Shift (8 Hours)</option>
                                                <option value="3rd-8hr">3rd Shift (8 Hours)</option>
                                                <option value="1st-12hr">1st Shift (12 Hours)</option>
                                                <option value="2nd-12hr">2nd Shift (12 Hours)</option>
                                                <option value="custom">Custom Hours</option>
                                            </select>
                                            <label for="shift">Shift</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <div class="shift-hours-display" id="totalHoursDisplay">
                                                Select shift to see hours
                                            </div>
                                            <input type="hidden" id="shiftHours" name="shiftHours">
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="time" class="form-control" id="shiftStart" name="shiftStart" required>
                                            <label for="shiftStart">Shift Start Time</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="time" class="form-control" id="shiftEnd" name="shiftEnd" required>
                                            <label for="shiftEnd">Shift End Time</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <div class="shift-hours-display" id="calculatedHours">
                                                Enter times to calculate
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="form-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0"><i class="fas fa-box me-2"></i>Product Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="productName" name="productName" required autocomplete="off">
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

                        <!-- Injection-Specific Parameters (only shown for Injection reports) -->
                        <div class="form-section injection-section" style="display: none;">
                            <div class="card-header py-3">
                                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Injection Molding Parameters</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="injectionPressure" name="injectionPressure" step="0.1">
                                            <label for="injectionPressure">Injection Pressure (MPa)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="moldingTemp" name="moldingTemp">
                                            <label for="moldingTemp">Molding Temperature (°C)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="cycleTime" name="cycleTime" step="0.1">
                                            <label for="cycleTime">Cycle Time (seconds)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="cavityCount" name="cavityCount">
                                            <label for="cavityCount">Cavity Count</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="coolingTime" name="coolingTime" step="0.1">
                                            <label for="coolingTime">Cooling Time (seconds)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="holdingPressure" name="holdingPressure" step="0.1">
                                            <label for="holdingPressure">Holding Pressure (MPa)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="materialType" name="materialType">
                                            <label for="materialType">Material Type</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="shotSize" name="shotSize" step="0.1">
                                            <label for="shotSize">Shot Size (g)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Finishing-Specific Parameters (only shown for Finishing reports) -->
                        <div class="form-section finishing-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Finishing Process Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select" id="finishingProcess" name="finishingProcess">
                                                <option value="">Select Process</option>
                                                <option value="Assembly">Assembly</option>
                                                <option value="Painting">Painting</option>
                                                <option value="Polishing">Polishing</option>
                                                <option value="Packaging">Packaging</option>
                                                <option value="Quality Check">Quality Check</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <label for="finishingProcess">Finishing Process</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="stationNumber" name="stationNumber">
                                            <label for="stationNumber">Station Number</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="workOrder" name="workOrder">
                                            <label for="workOrder">Work Order #</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="finishingTools" name="finishingTools">
                                            <label for="finishingTools">Tools/Equipment Used</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="qualityStandard" name="qualityStandard">
                                            <label for="qualityStandard">Quality Standard</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ID Numbers Section -->
                        <div class="form-section">
                            <div class="card-header py-3">
                                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>ID Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
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

                        <!-- Assembly Line Section - Only for Finishing Reports -->
                        <div class="form-section finishing-section" style="display: none;">
                            <div class="card-header py-3">
                                <h5 class="mb-0"><i class="fas fa-industry me-2"></i>Assembly Line Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select" id="assemblyLine" name="assemblyLine">
                                                <option value="">Select Table</option>
                                                <option value="Table 1">Table 1</option>
                                                <option value="Table 2">Table 2</option>
                                                <option value="Table 3">Table 3</option>
                                                <option value="Table 4">Table 4</option>
                                                <option value="Table 5">Table 5</option>
                                                <option value="Table 6">Table 6</option>
                                                <option value="Table 7">Table 7</option>
                                                <option value="Table 8">Table 8</option>
                                            </select>
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

                        <!-- Machine Information Section - Only for Injection Reports -->
                        <div class="form-section injection-machine-section" style="display: none;">
                            <div class="card-header py-3">
                                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Machine Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="machine" name="machine">
                                            <label for="machine">Machine</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select" id="robotArm" name="robotArm">
                                                <option value="">Select Option</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            <label for="robotArm">With Robot Arm</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="regInjection" name="reg">
                                            <label for="regInjection">REG</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="otInjection" name="ot">
                                            <label for="otInjection">OT</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quality Control Table -->
                        <div class="table-section">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Quality Control Records</h5>
                                <button type="button" class="btn btn-light btn-sm" id="addRow">
                                    <i class="fas fa-plus me-2"></i>Add Row
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="qualityTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th rowspan="2" style="min-width: 200px; vertical-align: middle;">Part Name</th>
                                            <th rowspan="2" style="min-width: 200px; vertical-align: middle;">Defect</th>
                                            <th colspan="8" class="text-center" id="timeHeaderSpan">Time (Hours)</th>
                                            <th rowspan="2" style="min-width: 100px; vertical-align: middle;">Total</th>
                                        </tr>
                                        <tr id="timeSubHeaders">
                                            <!-- Time column headers will be generated dynamically -->
                                        </tr>
                                    </thead>
                                    <tbody id="qualityTableBody">
                                        <tr>
                                            <td><input type="text" class="form-control" name="partName[]"></td>
                                            <td><input type="text" class="form-control" name="defect[]"></td>
                                            <!-- Time input columns will be generated dynamically -->
                                            <td class="time-columns-container"></td>
                                            <td><input type="number" class="form-control total" name="total[]" readonly></td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="2" class="fw-bold">Total Reject</td>
                                            <td colspan="8" id="totalRejectColumns" class="time-totals-container">
                                                <!-- Time total inputs will be generated dynamically -->
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="totalRejectSum" name="totalRejectSum" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="fw-bold">Total Good</td>
                                            <td colspan="8" id="totalGoodColumns" class="time-totals-container">
                                                <!-- Time total inputs will be generated dynamically -->
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="totalGoodSum" name="totalGoodSum" readonly>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Downtime Section -->
                        <div class="table-section">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Downtime Records</h5>
                                <button type="button" class="btn btn-light btn-sm" id="addDowntimeRow">
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
                                <h5 class="mb-0"><i class="fas fa-comment-alt me-2"></i>Additional Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="remarks" name="remarks" style="height: 120px"></textarea>
                                    <label for="remarks">Remarks</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2 mb-4">
                            <button type="button" class="btn btn-secondary btn-action" onclick="window.history.back()">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary btn-action" id="submitBtn">
                                <span class="btn-text">
                                    <i class="fas fa-save me-2"></i>Submit Form
                                </span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Submitting...
                                </span>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Success/Error Notifications -->
                    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
                        <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                            <div class="toast-header bg-success text-white">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong class="me-auto">Success</strong>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body" id="successMessage">
                                Production report submitted successfully!
                            </div>
                        </div>
                        
                        <div id="errorToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="7000">
                            <div class="toast-header bg-danger text-white">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong class="me-auto">Error</strong>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body" id="errorMessage">
                                There was an error submitting the form.
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
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Shift time mappings
            const shiftTimes = {
                '1st-8hr': { start: '06:00', end: '14:00', hours: 8 },
                '2nd-8hr': { start: '14:00', end: '22:00', hours: 8 },
                '3rd-8hr': { start: '22:00', end: '06:00', hours: 8 },
                '1st-12hr': { start: '06:00', end: '18:00', hours: 12 },
                '2nd-12hr': { start: '18:00', end: '06:00', hours: 12 },
                'custom': { start: '', end: '', hours: 0 }
            };

            // Initialize jQuery UI autocomplete for product names
            $('#productName').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: 'get_product_names.php',
                        data: { search: request.term },
                        dataType: 'json',
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    $(this).val(ui.item);
                    return false;
                }
            });

            // Initialize jQuery UI autocomplete for machines (injection only)
            $('#machine').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: 'get_machines.php',
                        data: { term: request.term },
                        dataType: 'json',
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 0,
                select: function(event, ui) {
                    $(this).val(ui.item.value);
                    return false;
                }
            }).focus(function() {
                // Show all machines when focused with no value
                if ($(this).val() === '') {
                    $(this).autocomplete('search', '');
                }
            });

            // ID number formatting
            $('#idNumber1, #idNumber2, #idNumber3').on('blur', function() {
                let value = $(this).val().replace(/\D/g, ''); // Remove non-digits
                if (value.length > 0) {
                    value = value.padStart(6, '0'); // Pad with zeros to make 6 digits
                    $(this).val(value);
                }
            });

            // Handle shift selection
            $('#shift').change(function() {
                const selectedShift = $(this).val();
                if (selectedShift && shiftTimes[selectedShift]) {
                    const times = shiftTimes[selectedShift];
                    if (selectedShift !== 'custom') {
                        $('#shiftStart').val(times.start);
                        $('#shiftEnd').val(times.end);
                        updateShiftHours();
                        $('#totalHoursDisplay').text(`${selectedShift}: ${times.start} - ${times.end} (${times.hours} hours)`);
                        generateTimeColumns(times.start, times.end);
                    } else {
                        $('#totalHoursDisplay').text('Custom shift - enter start and end times');
                        $('#shiftStart').val('');
                        $('#shiftEnd').val('');
                    }
                } else {
                    $('#totalHoursDisplay').text('Select shift to see hours');
                    $('#shiftStart').val('');
                    $('#shiftEnd').val('');
                    $('#calculatedHours').text('Enter times to calculate');
                }
            });

            // Generate time columns based on shift hours
            function generateTimeColumns(startTime, endTime) {
                if (!startTime || !endTime) return;

                const start = new Date(`2000-01-01 ${startTime}`);
                let end = new Date(`2000-01-01 ${endTime}`);
                
                // Handle overnight shifts
                if (end <= start) {
                    end.setDate(end.getDate() + 1);
                }
                
                const diffMs = end - start;
                const totalHours = diffMs / (1000 * 60 * 60);
                
                // Generate time column headers
                let timeHeaders = '';
                let currentTime = new Date(start);
                
                for (let i = 0; i < totalHours; i++) {
                    const fromTime = formatTime(currentTime);
                    currentTime.setHours(currentTime.getHours() + 1);
                    const toTime = formatTime(currentTime);
                    
                    timeHeaders += `<th style="min-width: 80px; text-align: center;">${fromTime}-${toTime}</th>`;
                }
                
                // Update table headers
                $('#timeHeaderSpan').attr('colspan', totalHours);
                $('#timeSubHeaders').html(timeHeaders);
                
                // Generate time input columns for each row
                generateTimeInputs(totalHours);
                
                // Generate total reject and total good time columns
                generateTimeTotals(totalHours);
                
                // Update footer colspan
                $('#totalRejectColumns, #totalGoodColumns').attr('colspan', totalHours);
            }

            function generateTimeInputs(totalHours) {
                $('#qualityTableBody tr').each(function() {
                    let timeInputs = '';
                    for (let i = 0; i < totalHours; i++) {
                        timeInputs += `<td><input type="number" class="form-control time-input" name="time${i+1}[]" style="width: 70px;" min="0"></td>`;
                    }
                    
                    // Replace the time-columns-container
                    const $row = $(this);
                    const partNameCell = $row.find('td:eq(0)');
                    const defectCell = $row.find('td:eq(1)');
                    const totalCell = $row.find('td:last()');
                    
                    $row.html('');
                    $row.append(partNameCell);
                    $row.append(defectCell);
                    $row.append(timeInputs);
                    $row.append(totalCell);
                });
            }

            function generateTimeTotals(totalHours) {
                let rejectTotals = '';
                let goodTotals = '';
                
                for (let i = 0; i < totalHours; i++) {
                    rejectTotals += `<td><input type="number" class="form-control time-total-reject" id="timeReject${i+1}" readonly style="width: 70px;"></td>`;
                    goodTotals += `<td><input type="number" class="form-control time-total-good" name="timeGood${i+1}" style="width: 70px;" min="0"></td>`;
                }
                
                $('#totalRejectColumns').html(rejectTotals);
                $('#totalGoodColumns').html(goodTotals);
            }

            function formatTime(date) {
                return date.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    hour12: false 
                }).substring(0, 5);
            }

            // Calculate shift hours
            function updateShiftHours() {
                const startTime = $('#shiftStart').val();
                const endTime = $('#shiftEnd').val();
                
                if (startTime && endTime) {
                    const start = new Date(`2000-01-01 ${startTime}`);
                    let end = new Date(`2000-01-01 ${endTime}`);
                    
                    // Handle overnight shifts
                    if (end <= start) {
                        end.setDate(end.getDate() + 1);
                    }
                    
                    const diffMs = end - start;
                    const diffHours = diffMs / (1000 * 60 * 60);
                    
                    $('#shiftHours').val(diffHours);
                    $('#calculatedHours').text(`${diffHours} hours`);
                    
                    // Generate time columns for custom shifts
                    if ($('#shift').val() === 'custom') {
                        generateTimeColumns(startTime, endTime);
                    }
                } else {
                    $('#calculatedHours').text('Enter times to calculate');
                    $('#shiftHours').val('');
                }
            }

            // Handle manual time changes
            $('#shiftStart, #shiftEnd').change(function() {
                updateShiftHours();
            });

            // Add row to quality table
            $('#addRow').click(function() {
                const firstRow = $('#qualityTableBody tr:first');
                const newRow = firstRow.clone();
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
                calculateColumnTotals();
            }

            function calculateTotals() {
                let totalReject = 0;
                $('#qualityTableBody tr').each(function() {
                    totalReject += parseInt($(this).find('.total').val()) || 0;
                });
                $('#totalRejectSum').val(totalReject);
                
                // Calculate total good sum
                let totalGood = 0;
                $('.time-total-good').each(function() {
                    totalGood += parseInt($(this).val()) || 0;
                });
                $('#totalGoodSum').val(totalGood);
            }

            function calculateColumnTotals() {
                // Calculate totals for each time column (reject totals)
                $('.time-total-reject').each(function(index) {
                    let columnTotal = 0;
                    $('#qualityTableBody tr').each(function() {
                        const timeInput = $(this).find('.time-input').eq(index);
                        columnTotal += parseInt(timeInput.val()) || 0;
                    });
                    $(this).val(columnTotal);
                });
                
                // Recalculate main totals
                calculateTotals();
            }

            // Calculate totals when time-total-good inputs change
            $(document).on('input', '.time-total-good', function() {
                calculateTotals();
            });

            function calculateDowntimeTotal() {
                let total = 0;
                $('.downtime-minutes').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                $('#totalDowntime').val(total);
            }

            // Form validation and AJAX submission
            const form = document.getElementById('qualityControlForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Always prevent default to handle with AJAX
                
                // Validate ID numbers
                const idFields = ['#idNumber1', '#idNumber2', '#idNumber3'];
                let isValid = true;
                
                idFields.forEach(field => {
                    const value = $(field).val();
                    if (value && value.length !== 6) {
                        isValid = false;
                        $(field).addClass('is-invalid');
                    } else {
                        $(field).removeClass('is-invalid');
                    }
                });

                if (!form.checkValidity() || !isValid) {
                    form.classList.add('was-validated');
                    return;
                }
                
                // Show confirmation dialog
                showSubmissionConfirmation();
            });
            
            // Confirmation dialog
            function showSubmissionConfirmation() {
                const reportType = $('input[name="reportTypeSelection"]:checked').val();
                const productName = $('#productName').val();
                const date = $('#date').val();
                const shift = $('#shift').val();
                
                const confirmMessage = `Are you sure you want to submit this ${reportType} production report?
                
Product: ${productName}
Date: ${date}
Shift: ${shift}
                
This action cannot be undone.`;
                
                if (confirm(confirmMessage)) {
                    submitFormAjax();
                }
            }
            
            // AJAX form submission function
            function submitFormAjax() {
                const submitBtn = $('#submitBtn');
                const btnText = submitBtn.find('.btn-text');
                const btnLoading = submitBtn.find('.btn-loading');
                
                // Show loading state
                submitBtn.prop('disabled', true);
                btnText.addClass('d-none');
                btnLoading.removeClass('d-none');
                
                // Prepare form data
                const formData = new FormData(form);
                
                // Submit via AJAX
                $.ajax({
                    url: 'submit.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Show success notification with detailed info
                            let successMsg = response.message;
                            if (response.report_id) {
                                successMsg += ` (Report ID: ${response.report_id})`;
                            }
                            
                            $('#successMessage').html(`
                                <strong>Success!</strong><br>
                                ${successMsg}<br>
                                <small class="text-muted">Submitted at ${response.timestamp || new Date().toLocaleString()}</small>
                            `);
                            new bootstrap.Toast($('#successToast')[0]).show();
                            
                            // Reset form after short delay
                            setTimeout(function() {
                                form.reset();
                                form.classList.remove('was-validated');
                                $('.injection-section, .finishing-section').hide();
                                // Reset report type to default
                                $('input[name="reportTypeSelection"]:first').prop('checked', true).trigger('change');
                            }, 2000);
                        } else {
                            // Show error notification
                            $('#errorMessage').html(`
                                <strong>Error!</strong><br>
                                ${response.message || 'An error occurred while submitting the form.'}
                            `);
                            new bootstrap.Toast($('#errorToast')[0]).show();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show error notification
                        let errorMessage = 'Network error occurred. Please check your connection and try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 500) {
                            errorMessage = 'Server error occurred. Please contact the administrator if this persists.';
                        } else if (xhr.status === 0) {
                            errorMessage = 'Network connection lost. Please check your internet connection.';
                        }
                        
                        $('#errorMessage').html(`
                            <strong>Network Error!</strong><br>
                            ${errorMessage}
                        `);
                        new bootstrap.Toast($('#errorToast')[0]).show();
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false);
                        btnText.removeClass('d-none');
                        btnLoading.addClass('d-none');
                    }
                });
            }

            // Auto-fill test data
            $('#autoFillTest').click(function() {
                const reportType = $('input[name="reportTypeSelection"]:checked').val();
                
                // Fill common fields
                $('#date').val('2024-03-20');
                $('#shift').val('1st-8hr').trigger('change');
                
                // Wait for shift change to process, then fill remaining fields
                setTimeout(function() {
                    // Fill product information
                    $('#productName').val('ASSY FOG LAMP LH TUNDRA');
                    $('#color').val('Black');
                    $('#partNo').val('FL-001-LH');
                    
                    // Fill ID Information
                    $('#idNumber1').val('123456');
                    $('#idNumber2').val('789012');
                    $('#idNumber3').val('345678');
                    $('#ejo').val('EJO-2024-001');
                    
                    // Fill time columns with realistic sample data
                    const baseGood = 35;
                    const baseReject = 2;
                    for (let i = 1; i <= 12; i++) {
                        const variance = Math.floor(Math.random() * 10) - 5; // -5 to +5 variance
                        $(`#good${i}`).val(Math.max(0, baseGood + variance));
                        $(`#reject${i}`).val(Math.max(0, baseReject + Math.floor(Math.random() * 3)));
                    }
                    
                    // Fill remarks
                    $('#remarks').val('Test data - sample production report for testing purposes. All parameters within normal operating ranges.');
                    
                    if (reportType === 'injection') {
                        // Fill injection-specific fields
                        $('#machine').val('IM-001');
                        $('#robotArm').val('Yes');
                        $('#regInjection').val('8');
                        $('#otInjection').val('0');
                        
                        // Fill all injection parameters
                        $('#injectionPressure').val('85.5');
                        $('#moldingTemp').val('220');
                        $('#cycleTime').val('45.2');
                        $('#cavityCount').val('4');
                        $('#coolingTime').val('15.8');
                        $('#holdingPressure').val('65.3');
                        $('#materialType').val('ABS');
                        $('#shotSize').val('28.5');
                        
                        // Clear finishing fields to avoid confusion
                        $('#assemblyLine').val('');
                        $('#manpower').val('');
                        $('#reg').val('');
                        $('#ot').val('');
                        $('#finishingProcess').val('');
                        $('#stationNumber').val('');
                        $('#workOrder').val('');
                        $('#finishingTools').val('');
                        $('#qualityStandard').val('');
                    } else {
                        // Fill finishing-specific fields
                        $('#assemblyLine').val('Table 1');
                        $('#manpower').val('8');
                        $('#reg').val('8');
                        $('#ot').val('0');
                        
                        // Fill all finishing parameters
                        $('#finishingProcess').val('Assembly');
                        $('#stationNumber').val('ST-001');
                        $('#workOrder').val('WO-2024-001');
                        $('#finishingTools').val('Screwdriver, Wrench Set, Torque Gun, Air Compressor');
                        $('#qualityStandard').val('ISO 9001:2015');
                        
                        // Clear injection fields to avoid confusion
                        $('#machine').val('');
                        $('#robotArm').val('');
                        $('#regInjection').val('');
                        $('#otInjection').val('');
                        $('#injectionPressure').val('');
                        $('#moldingTemp').val('');
                        $('#cycleTime').val('');
                        $('#cavityCount').val('');
                        $('#coolingTime').val('');
                        $('#holdingPressure').val('');
                        $('#materialType').val('');
                        $('#shotSize').val('');
                    }
                    
                    // Trigger calculation update
                    calculateTotals();
                    
                    // Show success message
                    console.log('Auto-fill completed for ' + reportType + ' report');
                }, 500); // Wait 500ms for shift change to complete
            });

            // Handle report type selection
            $('input[name="reportTypeSelection"]').change(function() {
                const selectedType = $(this).val();
                $('#selectedReportType').val(selectedType);
                
                if (selectedType === 'injection') {
                    $('.injection-section').show();
                    $('.injection-machine-section').show();
                    $('.finishing-section').hide();
                    // Set required fields for injection
                    $('#machine').prop('required', true);
                    $('#robotArm').prop('required', true);
                    $('#assemblyLine').prop('required', false);
                    $('#manpower').prop('required', false); // Manpower not required for injection
                } else {
                    $('.injection-section').hide();
                    $('.injection-machine-section').hide();
                    $('.finishing-section').show();
                    // Set required fields for finishing
                    $('#assemblyLine').prop('required', true);
                    $('#machine').prop('required', false);
                    $('#robotArm').prop('required', false);
                    $('#manpower').prop('required', true); // Manpower required for finishing
                }
            });

            // Initialize report type on page load
            const initialType = $('input[name="reportTypeSelection"]:checked').val();
            $('#selectedReportType').val(initialType);
            if (initialType === 'injection') {
                $('.injection-section').show();
                $('.injection-machine-section').show();
                $('.finishing-section').hide();
                $('#machine').prop('required', true);
                $('#robotArm').prop('required', true);
                $('#assemblyLine').prop('required', false);
                $('#manpower').prop('required', false); // Manpower not required for injection
            } else {
                $('.injection-section').hide();
                $('.injection-machine-section').hide();
                $('.finishing-section').show();
                $('#assemblyLine').prop('required', true);
                $('#machine').prop('required', false);
                $('#robotArm').prop('required', false);
                $('#manpower').prop('required', true); // Manpower required for finishing
            }
        });
    </script>
</body>

</html>