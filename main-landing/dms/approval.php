<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html");
    exit();
}

// Only allow supervisors and admins to access this page
if (
    !isset($_SESSION['role']) ||
    ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'supervisor')
) {
    header("Location: ../login.html");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "dailymonitoringsheet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// ----- Get Pending Submissions for Notifications ----- //
function getPendingSubmissions($conn)
{
    $pending = [];
    $sql_pending = "SELECT id, product_name, date FROM submissions WHERE approval_status = 'pending' ORDER BY date DESC";
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

// ----- Process Quick Approval/Decline Actions ----- //
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submission_id']) && isset($_POST['approval_action'])) {
    $submission_id = intval($_POST['submission_id']);
    $approval_action = $_POST['approval_action']; // "approve" or "decline"
    $new_status = ($approval_action === 'approve') ? 'approved' : 'declined';

    $stmt = $conn->prepare("UPDATE submissions SET approval_status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $submission_id);
    if ($stmt->execute()) {
        $message = "Submission #{$submission_id} updated to " . ucfirst($new_status) . ".";
    } else {
        $message = "Error updating submission #" . $submission_id;
    }
    $stmt->close();
}

// ----- Process Edit Form Submission ----- //
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_submission_id']) && isset($_POST['new_approval_status'])) {
    $submission_id = intval($_POST['edit_submission_id']);
    $new_status = $_POST['new_approval_status'];
    // Validate the new status
    if (in_array($new_status, ['pending', 'approved', 'declined'])) {
        // Grab the comment from the form, if provided
        $approval_comment = isset($_POST['approval_comment']) ? $_POST['approval_comment'] : '';

        // Update both the approval_status and approval_comment fields
        $stmt = $conn->prepare("UPDATE submissions SET approval_status = ?, approval_comment = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_status, $approval_comment, $submission_id);
        if ($stmt->execute()) {
            $message = "Submission #{$submission_id} updated to " . ucfirst($new_status) . ".";
        } else {
            $message = "Error updating submission #" . $submission_id;
        }
        $stmt->close();
    } else {
        $message = "Invalid status provided.";
    }
}


// ----- Retrieve Pending Submissions ----- //
$sql_pending = "SELECT * FROM submissions WHERE approval_status = 'pending' ORDER BY date DESC";
$result_pending = $conn->query($sql_pending);

// ----- Retrieve Approved/Declined Submissions ----- //
$sql_other = "SELECT * FROM submissions WHERE approval_status != 'pending' ORDER BY date DESC";
$result_other = $conn->query($sql_other);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>DMS - Approvals</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">

    <!-- DataTables Buttons CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    <!-- DataTables Responsive CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <style>
        .alert-bottom-right {
            position: fixed;
            bottom: 20px;
            /* Adjust distance from bottom */
            right: 20px;
            /* Adjust distance from right */
            z-index: 1050;
            /* Ensure it's on top of other elements */
            max-width: 300px;
            /* Optional: Limit width */
        }

        .highlight {
            background-color: #ffff99 !important;
            /* Light yellow */
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel Digitization</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
        <!-- Navbar-->
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
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown">
                    <?php if ($pending_count > 0): ?>
                        <?php foreach ($pending_submissions as $pending): ?>
                            <li>
                                <a class="dropdown-item notification-link"
                                    href="approval.php?refresh=1#submission-<?php echo $pending['id']; ?>">
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
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
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
                                <a class="nav-link active" href="approval.php">Approvals</a>
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
                    <h1 class="">Submissions Approval</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
                    </ol>

                    <?php if ($message):
                        // Determine alert class based on message content
                        if (strpos($message, 'Approved') !== false) {
                            $alertClass = 'alert-success';
                        } elseif (strpos($message, 'Declined') !== false) {
                            $alertClass = 'alert-danger';
                        } else {
                            $alertClass = 'alert-secondary';
                        }
                        ?>
                        <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show alert-bottom-right"
                            role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>


                    <!-- Pending Submissions Table -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h2>Pending Submissions</h2>
                            <div class="table-responsive">
                                <table id="pendingTable" class="table table-bordered table-striped">
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
                                            <th>Name</th>
                                            <th>Shift</th>
                                            <th>Approval Status</th>
                                            <th>Action</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result_pending && $result_pending->num_rows > 0) {
                                            while ($row = $result_pending->fetch_assoc()) {
                                                echo "<tr id='submission-" . $row['id'] . "'>";
                                                echo "<td>" . $row['id'] . "</td>";
                                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['machine']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['prn']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['mold_code']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['cycle_time_target']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['cycle_time_actual']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['weight_standard']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['weight_gross']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['weight_net']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['cavity_designed']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['cavity_active']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['remarks'], ENT_QUOTES) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['shift']) . "</td>";
                                                echo "<td>" . ucfirst(htmlspecialchars($row['approval_status'])) . "</td>";
                                                echo "<td>";
                                                if ($row['approval_status'] === 'pending') {
                                                    echo '<div class="btn-group" role="group" aria-label="Approval actions">
                                                            <form method="post" class="d-inline">
                                                                <input type="hidden" name="submission_id" value="' . $row['id'] . '">
                                                                <button type="submit" name="approval_action" value="approve" class="btn btn-success btn-sm">
                                                                    Approve
                                                                </button>
                                                            </form>
                                                            <button type="button" class="btn btn-danger btn-sm decline-quick-button" data-id="' . $row['id'] . '">
                                                                Decline
                                                            </button>
                                                          </div>';
                                                }
                                                echo '<button type="button" class="btn btn-secondary btn-sm ms-1 edit-button" 
                                                        data-id="' . $row['id'] . '" 
                                                        data-current="' . $row['approval_status'] . '">
                                                        Edit
                                                    </button>';
                                                echo "</td>";
                                                echo "<td>" . htmlspecialchars($row['approval_comment']) . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='19' class='text-center'>No pending submissions found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Approved/Declined Submissions Table -->
                    <div class="card shadow">
                        <div class="card-body">
                            <h2>Approved/Declined Submissions</h2>
                            <div class="table-responsive">
                                <table id="otherTable" class="table table-bordered table-striped">
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
                                            <th>Name</th>
                                            <th>Shift</th>
                                            <th>Approval Status</th>
                                            <th>Action</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result_other && $result_other->num_rows > 0) {
                                            while ($row = $result_other->fetch_assoc()) {
                                                echo "<tr id='submission-" . $row['id'] . "'>";
                                                echo "<td>" . $row['id'] . "</td>";
                                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['machine']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['prn']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['mold_code']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['cycle_time_target']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['cycle_time_actual']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['weight_standard']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['weight_gross']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['weight_net']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['cavity_designed']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['cavity_active']) . "</td>";
                                                $maxLength = 20;
                                                $remarks = htmlspecialchars($row['remarks'], ENT_QUOTES);
                                                $truncated = (strlen($remarks) > $maxLength) ? substr($remarks, 0, $maxLength) . '...' : $remarks;
                                                echo "<td><span class='remarks-cell' data-full='{$remarks}' data-truncated='{$truncated}'>" . $truncated . "</span></td>";
                                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['shift']) . "</td>";
                                                echo "<td>" . ucfirst(htmlspecialchars($row['approval_status'])) . "</td>";
                                                echo "<td>";
                                                // Only show Edit button as quick actions aren't needed here.
                                                echo '<button type="button" class="btn btn-secondary btn-sm edit-button" 
                                                        data-id="' . $row['id'] . '" 
                                                        data-current="' . $row['approval_status'] . '">
                                                        Edit
                                                    </button>';
                                                echo "</td>";
                                                echo "<td>" . htmlspecialchars($row['approval_comment']) . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='19' class='text-center'>No approved or declined submissions found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Edit Approval Status Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" id="editForm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Approval Status</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="edit_submission_id" id="edit_submission_id">
                                    <div class="mb-3">
                                        <label for="new_approval_status" class="form-label">Select Approval
                                            Status:</label>
                                        <select class="form-select" name="new_approval_status" id="new_approval_status"
                                            required>
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="declined">Declined</option>
                                        </select>
                                    </div>
                                    <!-- New textarea for comment -->
                                    <div class="mb-3" id="commentDiv" style="display: none;">
                                        <label for="approval_comment" class="form-label">Comment</label>
                                        <textarea class="form-control" name="approval_comment" id="approval_comment"
                                            rows="3" placeholder="Enter comment..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </div>
                        </form>
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
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.6.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {
            // Global flag to track whether responsive mode is enabled
            var responsiveEnabled = true;

            // For Pending Submissions table
            var pendingResponsive = true;
            var pendingTable = initPendingTable(pendingResponsive);

            function initPendingTable(isResponsive) {
                return $('#pendingTable').DataTable({
                    fixedHeader: true,
                    responsive: isResponsive,
                    select: { style: 'single' },
                    dom: 'Bfrtip',
                    buttons: [{
                        text: 'Toggle Responsive',
                        action: function (e, dt, node, config) {
                            pendingResponsive = !isResponsive; // toggle the state
                            dt.destroy();
                            pendingTable = initPendingTable(pendingResponsive);
                        }
                    }]
                });
            }

            $(window).on('load', function () {
                if (window.location.hash) {
                    setTimeout(function () {
                        var target = $(window.location.hash);
                        if (target.length && typeof pendingTable !== 'undefined') {
                            // Use the Select extension API to select the row
                            pendingTable.row(target).select();
                            // Scroll the page so the selected row is centered
                            $('html, body').animate({
                                scrollTop: target.offset().top - ($(window).height() / 2) + (target.outerHeight() / 2)
                            }, 500);
                        }
                    }, 300);
                }
            });


            // For Approved/Declined table
            var otherResponsive = true;
            var otherTable = initOtherTable(otherResponsive);

            function initOtherTable(isResponsive) {
                return $('#otherTable').DataTable({
                    fixedHeader: true,
                    responsive: isResponsive,
                    select: { style: 'single' },
                    dom: 'Bfrtip',
                    buttons: [{
                        text: 'Toggle Responsive',
                        action: function (e, dt, node, config) {
                            otherResponsive = !isResponsive; // toggle the state
                            dt.destroy();
                            otherTable = initOtherTable(otherResponsive);
                        }
                    }]
                });
            }

            // Toggle remarks text based on responsive setting
            function toggleRemarks() {
                $('.remarks-cell').each(function () {
                    var $cell = $(this);
                    if (responsiveEnabled) {
                        $cell.text($cell.data('full'));
                        $cell.css('cursor', 'default');
                        $cell.off('click');
                    } else {
                        $cell.text($cell.data('truncated'));
                        $cell.css('cursor', 'pointer');
                        $cell.off('click').on('click', function () {
                            var fullText = $cell.data('full');
                            var truncatedText = $cell.data('truncated');
                            $cell.text($cell.text() === truncatedText ? fullText : truncatedText);
                        });
                    }
                });
            }
            toggleRemarks();


            // If there's a hash in the URL, scroll to and highlight that row
            if (window.location.hash) {
                var rowId = window.location.hash;
                var rowElement = $(rowId);
                if (rowElement.length) {
                    var table = $.fn.dataTable.Api ? $.fn.dataTable.Api().row(rowElement) : null;
                    $('html, body').animate({ scrollTop: rowElement.offset().top - ($(window).height() / 2) + (rowElement.outerHeight() / 2) }, 500);
                }
            }

            // Edit button functionality
            $('table').on('click', '.edit-button', function () {
                var submissionId = $(this).data('id');
                var currentStatus = $(this).data('current');
                $('#edit_submission_id').val(submissionId);
                $('#new_approval_status').val(currentStatus);
                var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            });
        });

        $('#new_approval_status').change(function () {
            var status = $(this).val();
            if (status === 'declined' || status === 'pending') {
                $('#commentDiv').show();
            } else {
                $('#commentDiv').hide();
                $('#approval_comment').val('');
            }
        });

        // Handle quick decline button click
        $(document).on('click', '.decline-quick-button', function () {
            var submissionId = $(this).data('id');
            $('#edit_submission_id').val(submissionId);
            $('#new_approval_status').val('declined');
            $('#commentDiv').show();
            $('#approval_comment').val('');
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });        
    </script>
    <script>
        // Force full page refresh on every notification click.
        $(document).on('click', '.notification-link', function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            // Split the URL by the hash (if any)
            var parts = href.split('#');
            var baseUrl = parts[0];
            var hash = parts.length > 1 ? '#' + parts[1] : '';
            // Append a timestamp parameter to ensure a fresh reload
            if (baseUrl.indexOf('?') > -1) {
                baseUrl += '&t=' + new Date().getTime();
            } else {
                baseUrl += '?t=' + new Date().getTime();
            }
            window.location.href = baseUrl + hash;
        });
    </script>

    <script src="https://cdn.datatables.net/keytable/2.6.2/js/dataTables.keyTable.min.js"></script>
</body>

</html>