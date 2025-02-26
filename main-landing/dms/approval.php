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
$password = "Admin123@plvil";
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
        $stmt = $conn->prepare("UPDATE submissions SET approval_status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $submission_id);
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

// ----- Retrieve all submissions ----- //
$sql = "SELECT * FROM submissions ORDER BY date DESC";
$result = $conn->query($sql);
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
    <link rel="stylesheet" href="https://cdn.datatables.net/keytable/2.6.2/css/keyTable.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">
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
                            <a class="dropdown-item" href="approval.php?refresh=1#submission-<?php echo $pending['id']; ?>">
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

                    <?php if ($message): ?>
                        <div class="alert alert-info alert-dismissible fade show alert-bottom-right" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="submissionsTable" class="table table-bordered table-striped">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
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
                                                if (strlen($remarks) > $maxLength) {
                                                    $truncated = substr($remarks, 0, $maxLength) . '...';
                                                } else {
                                                    $truncated = $remarks;
                                                }
                                                echo "<td><span class='remarks-cell' data-full='{$remarks}' data-truncated='{$truncated}'>" . $truncated . "</span></td>";

                                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['shift']) . "</td>";
                                                echo "<td>" . ucfirst(htmlspecialchars($row['approval_status'])) . "</td>";
                                                echo "<td>";
                                                // For pending submissions, show quick Approve/Decline buttons
                                                if ($row['approval_status'] === 'pending') {
                                                    echo '<div class="btn-group" role="group" aria-label="Approval actions">
                                                            <form method="post" class="d-inline">
                                                                <input type="hidden" name="submission_id" value="' . $row['id'] . '">
                                                                <button type="submit" name="approval_action" value="approve" class="btn btn-success btn-sm">
                                                                    Approve
                                                                </button>
                                                            </form>
                                                            <form method="post" class="d-inline">
                                                                <input type="hidden" name="submission_id" value="' . $row['id'] . '">
                                                                <button type="submit" name="approval_action" value="decline" class="btn btn-danger btn-sm">
                                                                    Decline
                                                                </button>
                                                            </form>
                                                        </div>';
                                                }
                                                // Always show an Edit button to update status (even if not pending)
                                                echo '<button type="button" class="btn btn-secondary btn-sm ms-1 edit-button" 
                                                        data-id="' . $row['id'] . '" 
                                                        data-current="' . $row['approval_status'] . '">
                                                        Edit
                                                    </button>';
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='18' class='text-center'>No submissions found.</td></tr>";
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
    <style>
        .highlight {
            background-color: #ffff99 !important;
            /* Light yellow */
        }
    </style>
    <script>
        $(document).ready(function () {
            // Initialize DataTable with fixedHeader, keys, and select enabled
            var table = $('#submissionsTable').DataTable({
                fixedHeader: true,
                select: {
                    style: 'single'
                }
            });

            // Check if there's a hash in the URL (e.g., #submission-123)
            if (window.location.hash) {
                var rowId = window.location.hash;
                var rowElement = $(rowId);
                if (rowElement.length) {
                    // Use the Select extension to highlight the entire row
                    table.row(rowElement).select();

                    // Calculate the scroll offset to center the row in the viewport
                    var rowOffset = rowElement.offset().top;
                    var rowHeight = rowElement.outerHeight();
                    var windowHeight = $(window).height();
                    var scrollTo = rowOffset - (windowHeight / 2) + (rowHeight / 2);

                    // Animate the scroll
                    $('html, body').animate({ scrollTop: scrollTo }, 500);
                }
            }

            // Existing edit button functionality
            $('#submissionsTable tbody').on('click', '.edit-button', function () {
                var submissionId = $(this).data('id');
                var currentStatus = $(this).data('current');
                $('#edit_submission_id').val(submissionId);
                $('#new_approval_status').val(currentStatus);
                var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            });

            $('.remarks-cell').css('cursor', 'pointer'); // Give a visual cue that it's clickable

            $('.remarks-cell').on('click', function(){
                var $cell = $(this);
                var fullText = $cell.data('full');
                var truncatedText = $cell.data('truncated');
                // If the current text is truncated, show the full text; otherwise, revert back.
                if ($cell.text() === truncatedText) {
                    $cell.text(fullText);
                } else {
                    $cell.text(truncatedText);
                }
            });
        });
    </script>





    <script src="https://cdn.datatables.net/keytable/2.6.2/js/dataTables.keyTable.min.js"></script>
</body>

</html>