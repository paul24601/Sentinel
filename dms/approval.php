<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Allow supervisors, admins, QA roles, and Quality Control Inspection to access this page
$allowed_roles = ['admin', 'supervisor', 'Quality Assurance Engineer', 'Quality Assurance Supervisor', 'Quality Control Inspection'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../login.html");
    exit();
}

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

/**
 * Helper function to send detailed email notifications.
 *
 * @param int    $submission_id       The submission ID.
 * @param string $final_status        The overall final status ('pending', 'approved', or 'declined').
 * @param string $approval_stage      The individual approval column updated ('supervisor_status' or 'qa_status').
 * @param string $approval_comment    The comment provided.
 * @param string $qa_approver         (Optional) The name of the QA approver.
 * @param string $supervisor_approver (Optional) The name of the supervisor/admin approver.
 */
function sendNotificationEmail($submission_id, $final_status, $approval_stage, $approval_comment, $qa_approver = '', $supervisor_approver = '')
{
    // Use the current session full name and current date/time for details.
    $action_by = $_SESSION['full_name'];
    $action_datetime = date('Y-m-d H:i:s');

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sentinel.dms.notifications@gmail.com';
        $mail->Password = 'zmys tnix xjjp jbsz'; // Use secure storage for credentials
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('sentinel.dms.notifications@gmail.com', 'DMS Notifications');
        // Adjusters will always receive these notifications.
        $mail->addAddress('dmsadjuster@gmail.com');

        $mail->isHTML(true);

        // Build subject and message body based on the final status.
        if ($final_status === 'declined') {
            $subject = "Submission #{$submission_id} Declined Notification";
            $body = "Submission #{$submission_id} was <strong>declined</strong> by <strong>{$action_by}</strong> ({$approval_stage}) on {$action_datetime}.<br><br>";
            $body .= "Comment: " . nl2br(htmlspecialchars($approval_comment));
        } elseif ($final_status === 'approved') {
            $subject = "Submission #{$submission_id} Approved Notification";
            $body = "Submission #{$submission_id} has been <strong>fully approved</strong> by all required parties. Latest update by <strong>{$action_by}</strong> ({$approval_stage}) on {$action_datetime}.<br><br>";
            $body .= "Comment: " . nl2br(htmlspecialchars($approval_comment));
        } else { // pending update
            $subject = "Submission #{$submission_id} Pending Notification";
            $body = "Submission #{$submission_id} has been marked as <strong>pending</strong> by <strong>{$action_by}</strong> ({$approval_stage}) on {$action_datetime}.<br><br>";
            $body .= "Comment: " . nl2br(htmlspecialchars($approval_comment));
        }

        // If the action was performed by QA and final status is declined or pending,
        // include additional details for adjusters.
        if ($approval_stage === 'qa_status' && ($final_status === 'declined' || $final_status === 'pending')) {
            $body .= "<br><br><strong>Additional Information:</strong><br>";
            $body .= "QA Action performed by: <strong>" . ($qa_approver ? $qa_approver : $action_by) . "</strong>.<br>";
            if (!empty($supervisor_approver)) {
                $body .= "Supervisor/Admin: <strong>{$supervisor_approver}</strong>.";
            }
        }

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        $mail->send();
    } catch (Exception $e) {
        // Optionally log the error: $e->getMessage()
    }
}

/**
 * Get Pending Submissions for Notifications.
 * Filters submissions based on the current user's role.
 */
function getPendingSubmissions($conn)
{
    $role = $_SESSION['role'];
    $where_clause = "approval_status = 'pending'";
    // For supervisor and admin, show pending supervisor approvals.
    if (in_array($role, ['supervisor', 'admin'])) {
        $where_clause .= " AND (supervisor_status IS NULL OR supervisor_status = 'pending')";
    }
    // For QA roles and Quality Control Inspection, show pending QA approvals.
    elseif (in_array($role, ['Quality Assurance Engineer', 'Quality Assurance Supervisor', 'Quality Control Inspection'])) {
        $where_clause .= " AND (qa_status IS NULL OR qa_status = 'pending')";
    }
    $pending = [];
    $sql_pending = "SELECT id, product_name, date FROM submissions WHERE $where_clause ORDER BY date DESC";
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

// Determine if current role is Quality Control Inspection for disabling action buttons.
$isQCInspection = ($_SESSION['role'] === 'Quality Control Inspection');
$disabled = $isQCInspection ? ' disabled' : '';

// ----- Process Quick Approval/Decline Actions -----
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submission_id']) && isset($_POST['approval_action'])) {
    $submission_id = intval($_POST['submission_id']);
    $approval_action = $_POST['approval_action']; // Expected values: "approved" or "declined"
    $approval_comment = isset($_POST['approval_comment']) ? $_POST['approval_comment'] : '';

    // Determine which approval column to update based on the current user's role.
    $current_user_role = $_SESSION['role'];
    if (in_array($current_user_role, ['supervisor', 'admin'])) {
        $approval_column = 'supervisor_status';
        $stmt = $conn->prepare("UPDATE submissions SET remarks = ? WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("si", $approval_comment, $submission_id);
    } elseif (in_array($current_user_role, ['Quality Assurance Engineer', 'Quality Assurance Supervisor'])) {
        $approval_column = 'qa_status';
        $stmt = $conn->prepare("UPDATE submissions SET remarks = ? WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("si", $approval_comment, $submission_id);
    } else {
        die("Unauthorized action.");
    }
    $stmt->execute();
    $stmt->close();

    // No need to retrieve statuses since we've removed those columns
    $message = "Submission #{$submission_id} updated.";
    
    // Don't try to update approval_status as the column doesn't exist anymore
}

// ----- Process Edit Form Submission -----
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_submission_id']) && isset($_POST['new_approval_status'])) {
    $submission_id = intval($_POST['edit_submission_id']);
    $approval_comment = isset($_POST['approval_comment']) ? $_POST['approval_comment'] : '';

    // Update remarks
    $stmt = $conn->prepare("UPDATE submissions SET remarks = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("si", $approval_comment, $submission_id);

    if ($stmt->execute()) {
        $stmt->close();
        $message = "Submission #{$submission_id} updated.";
    } else {
        $message = "Error updating submission #" . $submission_id;
    }
}

// ----- Retrieve Submissions for Display -----
$role = $_SESSION['role'];
$sql_pending = "SELECT * FROM submissions ORDER BY date DESC";
$result_pending = $conn->query($sql_pending);

// ----- Retrieve Other Submissions -----
$sql_other = "SELECT * FROM submissions ORDER BY date DESC";
$result_other = $conn->query($sql_other);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>DMS - Approvals</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/css/jquery.dataTables.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-select/1.7.0/css/select.dataTables.min.css" />
    <!-- DataTables Buttons CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/css/buttons.dataTables.min.css" />
    <!-- DataTables Responsive CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-responsive/2.5.0/css/responsive.dataTables.min.css" />
    <style>
        .alert-bottom-right {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
            max-width: 300px;
        }

        .highlight {
            background-color: #ffff99 !important;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Navigation and Sidebar (omitted for brevity; assume your existing HTML here) -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="../index.php">Sentinel Digitization</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search (optional) -->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
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
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown"
                    style="max-height:300px; overflow-y:auto;">
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
                        <li><span class="dropdown-item-text">No pending submissions.</span></li>
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
    <!-- Main content area -->
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
                            <div class="collapse show" id="collapseDMS" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="submission.php">Records</a>
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
                <div class="container-fluid p-4">
                    <h1 class="">Submissions Approval</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
                    </ol>

                    <?php if ($message):
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
                                            <th>Supervisor Status</th>
                                            <th>QA Status</th>
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
                                                echo "<td>" . htmlspecialchars($row['supervisor_status']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['qa_status']) . "</td>";
                                                echo "<td>" . ucfirst(htmlspecialchars($row['approval_status'])) . "</td>";
                                                echo "<td>";
                                                if ($row['approval_status'] === 'pending') {
                                                    echo '<div class="btn-group" role="group" aria-label="Approval actions">
                                                            <form method="post" class="d-inline">
                                                                <input type="hidden" name="submission_id" value="' . $row['id'] . '">
                                                                <button type="submit" name="approval_action" value="approved" class="btn btn-success btn-sm"' . $disabled . '>
                                                                    Approve
                                                                </button>
                                                            </form>
                                                            <button type="button" class="btn btn-danger btn-sm decline-quick-button" data-id="' . $row['id'] . '"' . $disabled . '>
                                                                Decline
                                                            </button>
                                                          </div>';
                                                }
                                                echo '<button type="button" class="btn btn-secondary btn-sm ms-1 edit-button" data-id="' . $row['id'] . '" data-current="' . $row['approval_status'] . '"' . $disabled . '>
                                                        Edit
                                                    </button>';
                                                echo "</td>";
                                                echo "<td>" . htmlspecialchars((string) $row['approval_comment']) . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='21' class='text-center'>No pending submissions found.</td></tr>";
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
                                            <th>Supervisor Status</th>
                                            <th>QA Status</th>
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
                                                echo "<td>" . htmlspecialchars($row['supervisor_status']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['qa_status']) . "</td>";
                                                echo "<td>" . ucfirst(htmlspecialchars($row['approval_status'])) . "</td>";
                                                echo "<td>";
                                                echo '<button type="button" class="btn btn-secondary btn-sm edit-button" data-id="' . $row['id'] . '" data-current="' . $row['approval_status'] . '"' . $disabled . '>
                                                        Edit
                                                    </button>';
                                                echo "</td>";
                                                echo "<td>" . htmlspecialchars($row['approval_comment']) . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='21' class='text-center'>No approved or declined submissions found.</td></tr>";
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
    <!-- Scripts -->
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS and Extensions from reliable CDN -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {
            var responsiveEnabled = true;
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
                            pendingResponsive = !isResponsive;
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
                            pendingTable.row(target).select();
                            $('html, body').animate({
                                scrollTop: target.offset().top - ($(window).height() / 2) + (target.outerHeight() / 2)
                            }, 500);
                        }
                    }, 300);
                }
            });
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
                            otherResponsive = !isResponsive;
                            dt.destroy();
                            otherTable = initOtherTable(otherResponsive);
                        }
                    }]
                });
            }
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
            if (window.location.hash) {
                var rowId = window.location.hash;
                var rowElement = $(rowId);
                if (rowElement.length) {
                    var table = $.fn.dataTable.Api ? $.fn.dataTable.Api().row(rowElement) : null;
                    $('html, body').animate({ scrollTop: rowElement.offset().top - ($(window).height() / 2) + (rowElement.outerHeight() / 2) }, 500);
                }
            }
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
        $(document).on('click', '.decline-quick-button', function () {
            var submissionId = $(this).data('id');
            $('#edit_submission_id').val(submissionId);
            $('#new_approval_status').val('declined');
            $('#commentDiv').show();
            $('#approval_comment').val('');
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });
        $(document).on('click', '.notification-link', function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var parts = href.split('#');
            var baseUrl = parts[0];
            var hash = parts.length > 1 ? '#' + parts[1] : '';
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