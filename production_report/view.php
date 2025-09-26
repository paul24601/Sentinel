<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Get report ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: records.php");
    exit();
}

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_production');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get report details
$sql = "SELECT * FROM production_reports WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No report found with ID: $id");
}

$report = $result->fetch_assoc();

// Get quality control data if exists (with error handling)
$quality_data = [];
try {
    $quality_sql = "SELECT * FROM quality_control_entries WHERE report_id = ? ORDER BY id";
    $quality_stmt = $conn->prepare($quality_sql);
    if ($quality_stmt) {
        $quality_stmt->bind_param("i", $id);
        $quality_stmt->execute();
        $quality_result = $quality_stmt->get_result();
        $quality_data = $quality_result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    // Ignore quality control errors since table might not exist
    $quality_data = [];
}

// Debug information (remove this after testing)
// error_log("Report data: " . print_r($report, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Report Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin-bottom: 25px;
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
        
        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #212529;
            margin-bottom: 15px;
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
        <a class="navbar-brand ps-3" href="../index.php">Sentinel</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
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
                                <a class="nav-link" href="records.php">View Records</a>
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
                        <div>
                            <h1>Production Report Details</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="records.php">Records</a></li>
                                    <li class="breadcrumb-item active">Report #<?php echo $report['id']; ?></li>
                                </ol>
                            </nav>
                        </div>
                        <div>
                            <a href="records.php" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i>Back to Records
                            </a>
                            <a href="edit.php?id=<?php echo $report['id']; ?>" class="btn btn-gradient">
                                <i class="fas fa-edit me-1"></i>Edit Report
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-label">Plant</div>
                                    <div class="info-value">
                                        <span class="badge bg-primary fs-6">
                                            <?php echo htmlspecialchars($report['plant']); ?>
                                        </span>
                                    </div>

                                    <div class="info-label">Report Date</div>
                                    <div class="info-value"><?php echo date('F d, Y', strtotime($report['report_date'])); ?></div>

                                    <div class="info-label">Shift</div>
                                    <div class="info-value"><?php echo htmlspecialchars($report['shift']); ?> (<?php echo htmlspecialchars($report['shift_hours']); ?>)</div>

                                    <div class="info-label">Created</div>
                                    <div class="info-value"><?php echo date('M d, Y g:i A', strtotime($report['created_at'])); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Product Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-label">Product Name</div>
                                    <div class="info-value fw-bold"><?php echo htmlspecialchars($report['product_name']); ?></div>

                                    <div class="info-label">Color</div>
                                    <div class="info-value"><?php echo htmlspecialchars($report['color']); ?></div>

                                    <div class="info-label">Part Number</div>
                                    <div class="info-value"><?php echo htmlspecialchars($report['part_no']); ?></div>

                                    <div class="row">
                                        <?php if ($report['id_number1']): ?>
                                            <div class="col-md-4">
                                                <div class="info-label">ID Number 1</div>
                                                <div class="info-value"><?php echo htmlspecialchars($report['id_number1']); ?></div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($report['id_number2']): ?>
                                            <div class="col-md-4">
                                                <div class="info-label">ID Number 2</div>
                                                <div class="info-value"><?php echo htmlspecialchars($report['id_number2']); ?></div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($report['id_number3']): ?>
                                            <div class="col-md-4">
                                                <div class="info-label">ID Number 3</div>
                                                <div class="info-value"><?php echo htmlspecialchars($report['id_number3']); ?></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Assembly Line Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-industry me-2"></i>
                                        Assembly Line Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-label">Assembly Line</div>
                                    <div class="info-value"><?php echo htmlspecialchars($report['assembly_line'] ?: 'N/A'); ?></div>

                                    <div class="info-label">Manpower Allocation</div>
                                    <div class="info-value"><?php echo number_format($report['manpower']); ?></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">REG</div>
                                            <div class="info-value"><?php echo htmlspecialchars($report['reg'] ?: 'N/A'); ?></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">OT</div>
                                            <div class="info-value"><?php echo htmlspecialchars($report['ot'] ?: 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Production Summary -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Production Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-6">
                                            <div class="info-label">Total Reject</div>
                                            <div class="info-value">
                                                <span class="badge bg-danger fs-5"><?php echo number_format($report['total_reject']); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Total Good</div>
                                            <div class="info-value">
                                                <span class="badge bg-success fs-5"><?php echo number_format($report['total_good']); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php 
                                    $total_produced = $report['total_reject'] + $report['total_good'];
                                    if ($total_produced > 0):
                                        $good_percentage = ($report['total_good'] / $total_produced) * 100;
                                    ?>
                                    <div class="mt-3">
                                        <div class="info-label">Quality Rate</div>
                                        <div class="progress mb-2">
                                            <div class="progress-bar bg-success" style="width: <?php echo $good_percentage; ?>%"></div>
                                        </div>
                                        <small class="text-muted"><?php echo number_format($good_percentage, 1); ?>% good quality</small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks Section -->
                    <?php if ($report['remarks']): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-comment me-2"></i>Remarks</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-0">
                                    <?php echo nl2br(htmlspecialchars($report['remarks'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Quality Control Data -->
                    <?php if (!empty($quality_data)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Quality Control Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Part Name</th>
                                                <th>Defect Type</th>
                                                <th>Time Period</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($quality_data as $quality): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($quality['part_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($quality['defect_type']); ?></td>
                                                    <td><?php echo htmlspecialchars($quality['time_period']); ?></td>
                                                    <td><span class="badge bg-warning"><?php echo number_format($quality['quantity']); ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Remarks -->
                    <?php if ($report['remarks']): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-comment me-2"></i>Remarks</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($report['remarks'])); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>
