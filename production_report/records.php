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
    $conn = DatabaseManager::getConnection('sentinel_production');
    $admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
    $notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$report_type = $_GET['report_type'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build WHERE clause
$where_conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(product_name LIKE ? OR part_no LIKE ? OR assembly_line LIKE ? OR plant LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
    $types .= 'ssss';
}

// Remove report_type filter since it doesn't exist in the database, use plant instead
if (!empty($report_type)) {
    $where_conditions[] = "plant = ?";
    $params[] = $report_type;
    $types .= 's';
}

if (!empty($date_from)) {
    $where_conditions[] = "report_date >= ?";
    $params[] = $date_from;
    $types .= 's';
}

if (!empty($date_to)) {
    $where_conditions[] = "report_date <= ?";
    $params[] = $date_to;
    $types .= 's';
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM production_reports $where_clause";
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

// Get records
$sql = "SELECT * FROM production_reports $where_clause ORDER BY created_at DESC LIMIT $records_per_page OFFSET $offset";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Report Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
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
        
        .badge-finishing {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .badge-injection {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                            data-bs-target="#collapseProduction" aria-expanded="true"
                            aria-controls="collapseProduction">
                            <div class="sb-nav-link-icon"><i class="fas fa-industry"></i></div>
                            Production Reports
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseProduction" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="index.php">Create Report</a>
                                <a class="nav-link active" href="records.php">View Records</a>
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
                        <h1>Production Report Records</h1>
                        <a href="index.php" class="btn btn-gradient">
                            <i class="fas fa-plus me-2"></i>Create New Report
                        </a>
                    </div>

                    <!-- Search and Filter Form -->
                    <div class="search-form">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Product, Part No, Assembly Line...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Plant</label>
                                <select class="form-select" name="report_type">
                                    <option value="">All Plants</option>
                                    <option value="Plant A" <?php echo $report_type === 'Plant A' ? 'selected' : ''; ?>>Plant A</option>
                                    <option value="Plant B" <?php echo $report_type === 'Plant B' ? 'selected' : ''; ?>>Plant B</option>
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
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-gradient me-2">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                                <a href="records.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Export Options -->
                    <div class="mb-3">
                        <a href="export_all.php<?php echo !empty($_GET) ? '?' . http_build_query($_GET) : ''; ?>" class="btn btn-success">
                            <i class="fas fa-file-export me-1"></i>Export All to Excel
                        </a>
                        <small class="text-muted ms-2">Export all filtered records to Excel format</small>
                    </div>

                    <!-- Results Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Production Reports (<?php echo number_format($total_records); ?> records)
                        </div>
                        <div class="card-body">
                            <?php if ($result->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Date</th>
                                                <th>Plant</th>
                                                <th>Product Name</th>
                                                <th>Part No</th>
                                                <th>Shift</th>
                                                <th>Assembly Line</th>
                                                <th>Total Reject</th>
                                                <th>Total Good</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($row['report_date'])); ?></td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <?php echo htmlspecialchars($row['plant']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="fw-bold"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['part_no']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['shift']); ?></td>
                                                    <td>
                                                        <?php echo htmlspecialchars($row['assembly_line'] ?: 'N/A'); ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-danger"><?php echo number_format($row['total_reject']); ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success"><?php echo number_format($row['total_good']); ?></span>
                                                    </td>
                                                    <td class="text-muted small">
                                                        <?php echo date('M d, Y g:i A', strtotime($row['created_at'])); ?>
                                                    </td>
                                                    <td>
                                                        <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="export.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success" title="Export to Excel">
                                                            <i class="fas fa-file-export"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <?php if ($total_pages > 1): ?>
                                    <nav aria-label="Page navigation" class="mt-4">
                                        <ul class="pagination justify-content-center">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">Previous</a>
                                                </li>
                                            <?php endif; ?>

                                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                                <li class="page-item <?php echo $i === (int)$page ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>

                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No production reports found</h5>
                                    <p class="text-muted">Try adjusting your search criteria or create a new report.</p>
                                    <a href="index.php" class="btn btn-gradient">
                                        <i class="fas fa-plus me-2"></i>Create First Report
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>
