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

// Get database connection and notifications
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    $admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
    $notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$shift = $_GET['shift'] ?? '';
$machine = $_GET['machine'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build WHERE clause
$where_conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(product_name LIKE ? OR prn LIKE ? OR mold_code LIKE ? OR name LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
    $types .= 'ssss';
}

if (!empty($shift)) {
    $where_conditions[] = "shift = ?";
    $params[] = $shift;
    $types .= 's';
}

if (!empty($machine)) {
    $where_conditions[] = "machine LIKE ?";
    $params[] = "%$machine%";
    $types .= 's';
}

if (!empty($date_from)) {
    $where_conditions[] = "date >= ?";
    $params[] = $date_from;
    $types .= 's';
}

if (!empty($date_to)) {
    $where_conditions[] = "date <= ?";
    $params[] = $date_to;
    $types .= 's';
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM submissions $where_clause";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_records = $count_stmt->get_result()->fetch_assoc()['total'];

// Pagination
$page = $_GET['page'] ?? 1;
$records_per_page = 25;
$total_pages = ceil($total_records / $records_per_page);
$offset = ($page - 1) * $records_per_page;

$recordCreated = false;

// Handle record details view for modal
if (isset($_GET['view_record'])) {
    $record_id = intval($_GET['view_record']);
    $detail_sql = "SELECT * FROM submissions WHERE id = ?";
    $detail_stmt = $conn->prepare($detail_sql);
    $detail_stmt->bind_param('i', $record_id);
    $detail_stmt->execute();
    $detail_result = $detail_stmt->get_result();
    
    if ($record = $detail_result->fetch_assoc()) {
        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        echo "<h6>Basic Information</h6>";
        echo "<table class='table table-sm'>";
        echo "<tr><td><strong>ID:</strong></td><td>#{$record['id']}</td></tr>";
        echo "<tr><td><strong>Date:</strong></td><td>" . htmlspecialchars($record['date']) . "</td></tr>";
        echo "<tr><td><strong>Product Name:</strong></td><td>" . htmlspecialchars($record['product_name']) . "</td></tr>";
        echo "<tr><td><strong>Machine:</strong></td><td>" . htmlspecialchars($record['machine']) . "</td></tr>";
        echo "<tr><td><strong>IRN:</strong></td><td>" . htmlspecialchars($record['prn']) . "</td></tr>";
        echo "<tr><td><strong>Mold Code:</strong></td><td>" . htmlspecialchars($record['mold_code']) . "</td></tr>";
        echo "<tr><td><strong>Name:</strong></td><td>" . htmlspecialchars($record['name']) . "</td></tr>";
        echo "<tr><td><strong>Shift:</strong></td><td>" . htmlspecialchars($record['shift']) . "</td></tr>";
        echo "</table>";
        echo "</div>";
        echo "<div class='col-md-6'>";
        echo "<h6>Performance Data</h6>";
        echo "<table class='table table-sm'>";
        echo "<tr><td><strong>Cycle Time Target:</strong></td><td>" . htmlspecialchars($record['cycle_time_target']) . "s</td></tr>";
        echo "<tr><td><strong>Cycle Time Actual:</strong></td><td>" . htmlspecialchars($record['cycle_time_actual']) . "s</td></tr>";
        echo "<tr><td><strong>Cycle Time Difference:</strong></td><td>" . htmlspecialchars($record['cycle_time_target'] - $record['cycle_time_actual']) . "s</td></tr>";
        echo "<tr><td><strong>Weight Standard:</strong></td><td>" . htmlspecialchars($record['weight_standard']) . "g</td></tr>";
        echo "<tr><td><strong>Weight Gross:</strong></td><td>" . htmlspecialchars($record['weight_gross']) . "g</td></tr>";
        echo "<tr><td><strong>Weight Net:</strong></td><td>" . htmlspecialchars($record['weight_net']) . "g</td></tr>";
        echo "<tr><td><strong>Cavity Designed:</strong></td><td>" . htmlspecialchars($record['cavity_designed']) . "</td></tr>";
        echo "<tr><td><strong>Cavity Active:</strong></td><td>" . htmlspecialchars($record['cavity_active']) . "</td></tr>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        if (!empty($record['remarks'])) {
            echo "<div class='mt-3'>";
            echo "<h6>Remarks</h6>";
            echo "<p class='border rounded p-2 bg-light'>" . htmlspecialchars($record['remarks']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p class='text-center text-muted'>Record not found.</p>";
    }
    exit;
}

// Export CSV (Allow only if user is admin or supervisor)
if (isset($_GET['export_csv'])) {
    if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'supervisor')) {
        $filename = "DMS_data_" . date('Ymd') . ".csv";
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output = fopen("php://output", "w");
        $headers = [
            "ID", "Date", "Product Name", "Machine", "IRN", "Mold Code",
            "Cycle Time (Target)", "Cycle Time (Actual)", "Cycle Time Difference",
            "Weight (Standard)", "Weight (Gross)", "Weight (Net)",
            "Cavity (Designed)", "Cavity (Active)", "Remarks", "Name", "Shift"
        ];
        fputcsv($output, $headers);

        // Use the same filtering for export
        $export_sql = "SELECT id, date, product_name, machine, prn, mold_code, cycle_time_target, 
                cycle_time_actual, (cycle_time_target - cycle_time_actual) AS cycle_time_difference, 
                weight_standard, weight_gross, weight_net, cavity_designed, cavity_active, 
                remarks, name, shift FROM submissions $where_clause ORDER BY date DESC";
        
        $export_stmt = $conn->prepare($export_sql);
        if (!empty($params)) {
            $export_stmt->bind_param($types, ...$params);
        }
        $export_stmt->execute();
        $export_result = $export_stmt->get_result();

        if ($export_result->num_rows > 0) {
            while ($row = $export_result->fetch_assoc()) {
                fputcsv($output, $row);
            }
        }

        fclose($output);
        exit();
    } else {
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Access Denied</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body class='bg-light d-flex align-items-center justify-content-center vh-100'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-6 offset-md-3'>
                        <div class='alert alert-danger text-center'>
                            <h4 class='alert-heading'>Access Denied</h4>
                            <p>You are not authorized to export data. Only supervisors or admins can perform this action.</p>
                            <hr>
                            <button onclick='goBack()' class='btn btn-outline-danger'>Return to Previous Page</button>
                        </div>
                    </div>
                </div>
            </div>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
            <script>
                function goBack() {
                    window.history.back();
                }
            </script>
        </body>
        </html>";
        exit();
    }
}

// Handle submission approval/decline
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submission_id']) && isset($_POST['action'])) {
        $submission_id = intval($_POST['submission_id']);
        $action = $_POST['action'];

        // Logic for handling approval/decline
        if ($action === 'approve' || $action === 'decline') {
            $recordCreated = true;
            // Additional processing logic can go here
        }
    }
}

// Get records with filtering and pagination
$sql = "SELECT id, date, product_name, machine, prn, mold_code, cycle_time_target, 
        cycle_time_actual, 
        (cycle_time_target - cycle_time_actual) AS cycle_time_difference, 
        weight_standard, weight_gross, weight_net, cavity_designed, cavity_active, 
        remarks, name, shift 
        FROM submissions $where_clause ORDER BY date DESC LIMIT $records_per_page OFFSET $offset";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get unique values for filter dropdowns
$shifts_query = "SELECT DISTINCT shift FROM submissions WHERE shift IS NOT NULL AND shift != '' ORDER BY shift";
$shifts_result = $conn->query($shifts_query);

$machines_query = "SELECT DISTINCT machine FROM submissions WHERE machine IS NOT NULL AND machine != '' ORDER BY machine";
$machines_result = $conn->query($machines_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMS Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/dms-records.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
        
        .badge-morning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .badge-afternoon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .badge-night {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .search-form {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
        }
        
        .badge-cycle-positive {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .badge-cycle-negative {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo htmlspecialchars($_SESSION['full_name']); ?><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="../index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Systems</div>
                        <!-- DMS -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                            aria-expanded="true" aria-controls="collapseDMS">
                            <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                            DMS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseDMS" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="index.php">Data Entry</a>
                                <a class="nav-link active" href="submission.php">Records</a>
                            </nav>
                        </div>

                        <!-- Parameters -->
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
                                <a class="nav-link" href="../parameters/submission.php">Records</a>
                            </nav>
                        </div>

                        <!-- Production Reports -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseProduction" aria-expanded="false"
                            aria-controls="collapseProduction">
                            <div class="sb-nav-link-icon"><i class="fas fa-industry"></i></div>
                            Production Reports
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseProduction" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="../production_report/index.php">Create Report</a>
                                <a class="nav-link" href="../production_report/records.php">View Records</a>
                            </nav>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>

                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                        <h1>DMS Records</h1>
                        <a href="index.php" class="btn btn-gradient">
                            <i class="fas fa-plus me-2"></i>New Entry
                        </a>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total Records</h6>
                                        <h3 class="mb-0"><?php echo number_format($total_records); ?></h3>
                                    </div>
                                    <i class="fas fa-database fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">This Page</h6>
                                        <h3 class="mb-0"><?php echo $result->num_rows; ?></h3>
                                    </div>
                                    <i class="fas fa-file-alt fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Page</h6>
                                        <h3 class="mb-0"><?php echo number_format($page); ?> of <?php echo number_format($total_pages); ?></h3>
                                    </div>
                                    <i class="fas fa-bookmark fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Filters Active</h6>
                                        <h3 class="mb-0"><?php echo count(array_filter([$search, $shift, $machine, $date_from, $date_to])); ?></h3>
                                    </div>
                                    <i class="fas fa-filter fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter Form -->
                    <div class="search-form">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Product, IRN, Mold Code, Name...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Shift</label>
                                <select class="form-select" name="shift">
                                    <option value="">All Shifts</option>
                                    <?php while ($shift_row = $shifts_result->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($shift_row['shift']); ?>" <?php echo $shift === $shift_row['shift'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($shift_row['shift']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Machine</label>
                                <select class="form-select" name="machine">
                                    <option value="">All Machines</option>
                                    <?php while ($machine_row = $machines_result->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($machine_row['machine']); ?>" <?php echo $machine === $machine_row['machine'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($machine_row['machine']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date From</label>
                                <input type="date" class="form-control" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date To</label>
                                <input type="date" class="form-control" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-gradient w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        <div class="mt-2">
                            <a href="submission.php" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i>Clear Filters
                            </a>
                        </div>
                    </div>

                    <!-- Export Options -->
                    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'supervisor')): ?>
                        <div class="mb-3">
                            <a href="submission.php?export_csv=1<?php echo !empty($_GET) ? '&' . http_build_query(array_filter($_GET, function($k) { return $k !== 'export_csv'; }, ARRAY_FILTER_USE_KEY)) : ''; ?>" class="btn btn-success">
                                <i class="fas fa-file-export me-1"></i>Export Filtered Results to CSV
                            </a>
                            <small class="text-muted ms-2">Export current filtered results (<?php echo number_format($total_records); ?> records)</small>
                        </div>
                    <?php endif; ?>

                    <div class="container-fluid my-5">
                        <?php if ($recordCreated): ?>
                            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="successModalLabel">Submission Approved</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            The submission has been successfully processed.
                                        </div>
                                        <div class="modal-footer">
                                            <a href="../index.php" class="btn btn-primary">Go to Dashboard</a>
                                            <a href="submission.php" class="btn btn-secondary">Stay on Records Page</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Export Button -->
                        

                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                DMS Records (Showing <?php echo number_format($result->num_rows); ?> of <?php echo number_format($total_records); ?> records)
                            </div>
                            <div class="card-body table-container bg-white">
                                <?php if ($result->num_rows > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Product Name</th>
                                                    <th>Machine</th>
                                                    <th>IRN</th>
                                                    <th>Mold Code</th>
                                                    <th>Cycle Time</th>
                                                    <th>Weight (Net)</th>
                                                    <th>Cavity</th>
                                                    <th>Shift</th>
                                                    <th>Name</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><strong>#<?php echo $row["id"]; ?></strong></td>
                                                        <td><?php echo htmlspecialchars($row["date"]); ?></td>
                                                        <td><?php echo htmlspecialchars($row["product_name"]); ?></td>
                                                        <td><span class="badge bg-info"><?php echo htmlspecialchars($row["machine"]); ?></span></td>
                                                        <td><?php echo htmlspecialchars($row["prn"]); ?></td>
                                                        <td><?php echo htmlspecialchars($row["mold_code"]); ?></td>
                                                        <td>
                                                            <div class="small">
                                                                <strong>Target:</strong> <?php echo htmlspecialchars($row["cycle_time_target"]); ?>s<br>
                                                                <strong>Actual:</strong> <?php echo htmlspecialchars($row["cycle_time_actual"]); ?>s<br>
                                                                <span class="badge <?php echo $row['cycle_time_difference'] >= 0 ? 'badge-cycle-positive' : 'badge-cycle-negative'; ?>">
                                                                    <?php echo $row['cycle_time_difference'] >= 0 ? '+' : ''; ?><?php echo $row["cycle_time_difference"]; ?>s
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="small">
                                                                <strong>Standard:</strong> <?php echo htmlspecialchars($row["weight_standard"]); ?>g<br>
                                                                <strong>Net:</strong> <?php echo htmlspecialchars($row["weight_net"]); ?>g
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="small">
                                                                <strong>Designed:</strong> <?php echo htmlspecialchars($row["cavity_designed"]); ?><br>
                                                                <strong>Active:</strong> <?php echo htmlspecialchars($row["cavity_active"]); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge 
                                                                <?php 
                                                                    $shift_lower = strtolower($row["shift"]);
                                                                    if (strpos($shift_lower, 'morning') !== false) echo 'badge-morning';
                                                                    elseif (strpos($shift_lower, 'afternoon') !== false) echo 'badge-afternoon';
                                                                    else echo 'badge-night';
                                                                ?>">
                                                                <?php echo htmlspecialchars($row["shift"]); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                                        <td>
                                                            <button class="btn btn-outline-primary btn-sm" onclick="viewDetails(<?php echo $row['id']; ?>)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Pagination -->
                                    <?php if ($total_pages > 1): ?>
                                        <nav aria-label="Records pagination" class="mt-4">
                                            <ul class="pagination justify-content-center">
                                                <!-- Previous button -->
                                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($_GET) ? '&' . http_build_query(array_filter($_GET, function($k) { return $k !== 'page'; }, ARRAY_FILTER_USE_KEY)) : ''; ?>">
                                                        <i class="fas fa-chevron-left"></i> Previous
                                                    </a>
                                                </li>
                                                
                                                <!-- Page numbers -->
                                                <?php
                                                $start_page = max(1, $page - 2);
                                                $end_page = min($total_pages, $page + 2);
                                                
                                                for ($i = $start_page; $i <= $end_page; $i++):
                                                ?>
                                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($_GET) ? '&' . http_build_query(array_filter($_GET, function($k) { return $k !== 'page'; }, ARRAY_FILTER_USE_KEY)) : ''; ?>">
                                                            <?php echo $i; ?>
                                                        </a>
                                                    </li>
                                                <?php endfor; ?>
                                                
                                                <!-- Next button -->
                                                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($_GET) ? '&' . http_build_query(array_filter($_GET, function($k) { return $k !== 'page'; }, ARRAY_FILTER_USE_KEY)) : ''; ?>">
                                                        Next <i class="fas fa-chevron-right"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No records found</h5>
                                        <p class="text-muted">Try adjusting your filters or search criteria</p>
                                        <a href="submission.php" class="btn btn-outline-primary">Clear Filters</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Record Details Modal -->
                <div class="modal fade" id="recordModal" tabindex="-1" aria-labelledby="recordModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="recordModalLabel">Record Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="recordModalBody">
                                <!-- Details will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Modal Script -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                <script src="../js/scripts.js"></script>
                <script>
                    $(document).ready(function() {
                        // Show success modal if record was created
                        <?php if ($recordCreated): ?>
                            $('#successModal').modal('show');
                        <?php endif; ?>
                    });

                    function viewDetails(recordId) {
                        // Fetch and display record details
                        fetch(`submission.php?view_record=${recordId}`)
                            .then(response => response.text())
                            .then(data => {
                                document.getElementById('recordModalBody').innerHTML = data;
                                new bootstrap.Modal(document.getElementById('recordModal')).show();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error loading record details');
                            });
                    }
                </script>
            </main>

<?php include '../includes/navbar_close.php'; ?>
