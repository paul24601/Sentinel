<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html");
    exit();
}
// Load the centralized configuration
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Get database connection using the centralized system
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// (Redundant check can be kept for safety)
if (!isset($_SESSION['full_name'])) {
    header("Location: login.html");
    exit();
}

$full_name = $_SESSION['full_name'];
$message = "";

// Handle edit submission: update record and set approval_status back to pending
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submission_id'])) {
    $submission_id = intval($_POST['submission_id']);
    $date = $_POST['date'];
    $product_name = $_POST['product_name'];
    $machine = $_POST['machine'];
    $prn = $_POST['prn'];
    $mold_code = intval($_POST['mold_code']);
    $cycle_time_target = intval($_POST['cycle_time_target']);
    $cycle_time_actual = intval($_POST['cycle_time_actual']);
    $weight_standard = floatval($_POST['weight_standard']);
    $weight_gross = floatval($_POST['weight_gross']);
    $weight_net = floatval($_POST['weight_net']);
    $cavity_designed = intval($_POST['cavity_designed']);
    $cavity_active = intval($_POST['cavity_active']);
    $remarks = $_POST['remarks'];
    $shift = $_POST['shift'];

    // Verify that this submission belongs to the logged-in user
    $check_sql = "SELECT * FROM submissions WHERE id = ? AND name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $submission_id, $full_name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update submission
        $update_sql = "UPDATE submissions 
SET `date` = ?, product_name = ?, machine = ?, prn = ?, mold_code = ?, 
    cycle_time_target = ?, cycle_time_actual = ?, weight_standard = ?, 
    weight_gross = ?, weight_net = ?, cavity_designed = ?, cavity_active = ?, 
    remarks = ?, shift = ?
WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "ssssiiiddiisssi",
            $date,
            $product_name,
            $machine,
            $prn,
            $mold_code,
            $cycle_time_target,
            $cycle_time_actual,
            $weight_standard,
            $weight_gross,
            $weight_net,
            $cavity_designed,
            $cavity_active,
            $remarks,
            $shift,
            $submission_id
        );


        if ($update_stmt->execute()) {
            $message = "Submission successfully updated.";
        } else {
            $message = "Error updating submission: " . $conn->error;
        }
    } else {
        $message = "Unauthorized action.";
    }
}

// Fetch submissions belonging only to the logged-in user
$sql = "SELECT * FROM submissions WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $full_name);
$stmt->execute();
$result = $stmt->get_result();

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>DMS - Declined Submissions</title>
    <!-- DataTables CSS from reliable CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/css/jquery.dataTables.min.css">
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/datatables-responsive/2.5.0/css/responsive.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/css/buttons.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- jQuery UI for Autocomplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel Digitization</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <!-- (Optional search form) -->
        </form>
        <!-- Navbar Notifications and User Dropdown-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle position-relative" id="notifDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if ($notification_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $notification_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="max-width: 350px; max-height:300px; overflow-y:auto;">
                    <?php if (!empty($admin_notifications)): ?>
                        <li class="dropdown-header">
                            <i class="fas fa-bell me-1"></i> Recent Notifications
                        </li>
                        <?php foreach ($admin_notifications as $notification): ?>
                            <li>
                                <a class="dropdown-item notification-item <?php echo !$notification['is_viewed'] ? 'bg-light' : ''; ?>" 
                                   href="#" 
                                   onclick="markAsViewed(<?php echo $notification['id']; ?>)"
                                   data-notification-id="<?php echo $notification['id']; ?>">
                                    <div class="d-flex align-items-start">
                                        <div class="me-2">
                                            <i class="<?php echo getNotificationIcon($notification['notification_type']); ?>"></i>
                                            <?php if ($notification['is_urgent']): ?>
                                                <span class="badge bg-danger badge-sm">!</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 small"><?php echo htmlspecialchars($notification['title']); ?></h6>
                                            <p class="mb-1 small text-muted">
                                                <?php echo htmlspecialchars(substr($notification['message'], 0, 80)); ?>
                                                <?php if (strlen($notification['message']) > 80): ?>...<?php endif; ?>
                                            </p>
                                            <small class="text-muted"><?php echo timeAgo($notification['created_at']); ?></small>
                                            <?php if (!$notification['is_viewed']): ?>
                                                <span class="badge bg-primary badge-sm ms-1">New</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endforeach; ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li>
                                <a class="dropdown-item text-center" href="../admin/notifications.php">
                                    <i class="fas fa-cog me-1"></i> Manage Notifications
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li>
                            <span class="dropdown-item-text">No notifications available.</span>
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
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                            aria-expanded="false" aria-controls="collapseDMS">
                            <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                            DMS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseDMS" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="index.php">Data Entry</a>
                                <a class="nav-link" href="submission.php">Records</a>
                                <a class="nav-link" href="analytics.php">Analytics</a>
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
                        <div class="sb-sidenav-menu-heading">Admin</div>
                        <a class="nav-link" href="../admin/users.php">
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
                <div class="container-fluid p-4">
                    <h2 class="mb-4">Your Declined Submissions</h2>

                    <?php if ($message): ?>
                        <div class="alert alert-info"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <!-- Wrap table in a responsive container -->
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Product Name</th>
                                    <th>Machine</th>
                                    <th>PRN</th>
                                    <th>Mold Code</th>
                                    <th>Cycle Time Target</th>
                                    <th>Cycle Time Actual</th>
                                    <th>Weight Standard</th>
                                    <th>Weight Gross</th>
                                    <th>Weight Net</th>
                                    <th>Cavity Designed</th>
                                    <th>Cavity Active</th>
                                    <th>Remarks</th>
                                    <th>Shift</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['machine']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prn']); ?></td>
                                        <td><?php echo htmlspecialchars($row['mold_code']); ?></td>
                                        <td><?php echo htmlspecialchars($row['cycle_time_target']); ?></td>
                                        <td><?php echo htmlspecialchars($row['cycle_time_actual']); ?></td>
                                        <td><?php echo htmlspecialchars($row['weight_standard']); ?></td>
                                        <td><?php echo htmlspecialchars($row['weight_gross']); ?></td>
                                        <td><?php echo htmlspecialchars($row['weight_net']); ?></td>
                                        <td><?php echo htmlspecialchars($row['cavity_designed']); ?></td>
                                        <td><?php echo htmlspecialchars($row['cavity_active']); ?></td>
                                        <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                                        <td><?php echo htmlspecialchars($row['shift']); ?></td>
                                        <td>
                                            <!-- Edit button with data attributes -->
                                            <button class="btn btn-primary btn-sm edit-btn"
                                                data-id="<?php echo $row['id']; ?>" data-date="<?php echo $row['date']; ?>"
                                                data-product_name="<?php echo htmlspecialchars($row['product_name']); ?>"
                                                data-machine="<?php echo htmlspecialchars($row['machine']); ?>"
                                                data-prn="<?php echo htmlspecialchars($row['prn']); ?>"
                                                data-mold_code="<?php echo $row['mold_code']; ?>"
                                                data-cycle_time_target="<?php echo $row['cycle_time_target']; ?>"
                                                data-cycle_time_actual="<?php echo $row['cycle_time_actual']; ?>"
                                                data-weight_standard="<?php echo $row['weight_standard']; ?>"
                                                data-weight_gross="<?php echo $row['weight_gross']; ?>"
                                                data-weight_net="<?php echo $row['weight_net']; ?>"
                                                data-cavity_designed="<?php echo $row['cavity_designed']; ?>"
                                                data-cavity_active="<?php echo $row['cavity_active']; ?>"
                                                data-remarks="<?php echo htmlspecialchars($row['remarks']); ?>"
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                data-shift="<?php echo htmlspecialchars($row['shift']); ?>">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="declined_submissions.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Submission</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Hidden field for submission ID -->
                                        <input type="hidden" name="submission_id" value="">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Date</label>
                                                <input type="date" class="form-control" name="date"
                                                    max="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                            <div class="mb-3 col-md-8">
                                                <label class="form-label">Product Name</label>
                                                <input type="text" class="form-control" name="product_name" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Machine</label>
                                                <select class="form-control" name="machine" required>
                                                    <option value="" disabled>Select a machine</option>
                                                    <option value="ARB 50">ARB 50</option>
                                                    <option value="SUM 260C">SUM 260C</option>
                                                    <option value="SUM 350">SUM 350</option>
                                                    <option value="MIT 650D">MIT 650D</option>
                                                    <option value="TOS 650A">TOS 650A</option>
                                                    <option value="CLF 750A">CLF 750A</option>
                                                    <option value="CLF 750B">CLF 750B</option>
                                                    <option value="CLF 750C">CLF 750C</option>
                                                    <option value="TOS 850A">TOS 850A</option>
                                                    <option value="TOS 850B">TOS 850B</option>
                                                    <option value="TOS 850C">TOS 850C</option>
                                                    <option value="CLF 950A">CLF 950A</option>
                                                    <option value="CLF 950B">CLF 950B</option>
                                                    <option value="MIT 1050B">MIT 1050B</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">PRN</label>
                                                <input type="text" class="form-control" name="prn" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Mold Code</label>
                                                <input type="number" class="form-control" name="mold_code" max="9999"
                                                    required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Cycle Time Target</label>
                                                <input type="number" class="form-control" name="cycle_time_target"
                                                    min="0" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Cycle Time Actual</label>
                                                <input type="number" class="form-control" name="cycle_time_actual"
                                                    min="0" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Weight Standard</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="weight_standard" min="0" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Weight Gross</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="weight_gross" min="0" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Weight Net</label>
                                                <input type="number" step="0.01" class="form-control" name="weight_net"
                                                    min="0" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Cavity Designed</label>
                                                <input type="number" class="form-control" name="cavity_designed" min="0"
                                                    required>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Cavity Active</label>
                                                <input type="number" class="form-control" name="cavity_active" min="0"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Remarks</label>
                                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name" readonly required>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Shift</label>
                                                <select class="form-control" name="shift" required>
                                                    <option value="" disabled>Select your shift</option>
                                                    <option value="1st shift">1st Shift</option>
                                                    <option value="2nd shift">2nd Shift</option>
                                                    <option value="3rd shift">3rd Shift</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
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
    <!-- jQuery (required for DataTables and Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS from reliable CDN -->
    <script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Responsive JS -->
    <script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/datatables-responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable with a Toggle Responsive button.
            var responsiveEnabled = true;
            var myTable = initTable(responsiveEnabled);

            function initTable(isResponsive) {
                return $('#dataTable').DataTable({
                    fixedHeader: true,
                    responsive: isResponsive,
                    dom: 'Bfrtip',
                    buttons: [{
                        text: 'Toggle Responsive',
                        action: function (e, dt, node, config) {
                            // Toggle the responsive flag and reinitialize table
                            responsiveEnabled = !isResponsive;
                            dt.destroy();
                            myTable = initTable(responsiveEnabled);
                        }
                    }]
                });
            }

            // Edit button click handler to populate and show the modal
            $(document).on('click', '.edit-btn', function () {
                var button = $(this);
                $('#editModal input[name="submission_id"]').val(button.attr('data-id'));
                $('#editModal input[name="date"]').val(button.attr('data-date'));
                $('#editModal input[name="product_name"]').val(button.attr('data-product_name'));
                $('#editModal select[name="machine"]').val(button.attr('data-machine'));
                $('#editModal input[name="prn"]').val(button.attr('data-prn'));
                $('#editModal input[name="mold_code"]').val(button.attr('data-mold_code'));
                $('#editModal input[name="cycle_time_target"]').val(button.attr('data-cycle_time_target'));
                $('#editModal input[name="cycle_time_actual"]').val(button.attr('data-cycle_time_actual'));
                $('#editModal input[name="weight_standard"]').val(button.attr('data-weight_standard'));
                $('#editModal input[name="weight_gross"]').val(button.attr('data-weight_gross'));
                $('#editModal input[name="weight_net"]').val(button.attr('data-weight_net'));
                $('#editModal input[name="cavity_designed"]').val(button.attr('data-cavity_designed'));
                $('#editModal input[name="cavity_active"]').val(button.attr('data-cavity_active'));
                $('#editModal textarea[name="remarks"]').val(button.attr('data-remarks'));
                $('#editModal input[name="name"]').val(button.attr('data-name'));
                $('#editModal select[name="shift"]').val(button.attr('data-shift'));

                var editModalEl = document.getElementById('editModal');
                var editModal = new bootstrap.Modal(editModalEl);
                editModal.show();
            });

            // Notification functions
            window.markAsViewed = function(notificationId) {
                fetch('../includes/mark_notification_viewed.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'notification_id=' + notificationId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the notification item appearance
                        const notificationItem = document.querySelector('[data-notification-id="' + notificationId + '"]');
                        if (notificationItem) {
                            notificationItem.classList.remove('bg-light');
                            const newBadge = notificationItem.querySelector('.badge.bg-primary');
                            if (newBadge) {
                                newBadge.remove();
                            }
                        }
                        
                        // Update the notification count
                        updateNotificationCount();
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as viewed:', error);
                });
            };

            window.updateNotificationCount = function() {
                fetch('../includes/admin_notifications.php?action=count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('#notifDropdown .badge');
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                        } else {
                            // Create badge if it doesn't exist
                            const bell = document.querySelector('#notifDropdown i');
                            const newBadge = document.createElement('span');
                            newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                            newBadge.textContent = data.count;
                            bell.parentNode.appendChild(newBadge);
                        }
                    } else {
                        if (badge) {
                            badge.remove();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating notification count:', error);
                });
            };
        });
    </script>
</body>

</html>