<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Include PHPMailer files and declare namespaces at the top
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load the centralized configuration
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Get database connection using the centralized system
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);

$recordCreated = false;

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

        $sql = "SELECT id, date, product_name, machine, prn, mold_code, cycle_time_target, 
                cycle_time_actual, (cycle_time_target - cycle_time_actual) AS cycle_time_difference, 
                weight_standard, weight_gross, weight_net, cavity_designed, cavity_active, 
                remarks, name, shift FROM submissions";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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

// Retrieve data from database - show all submissions
$sql = "SELECT id, date, product_name, machine, prn, mold_code, cycle_time_target, 
        cycle_time_actual, 
        (cycle_time_target - cycle_time_actual) AS cycle_time_difference, 
        weight_standard, weight_gross, weight_net, cavity_designed, cavity_active, 
        remarks, name, shift 
        FROM submissions";
$result = $conn->query($sql);
?>
<?php include '../includes/navbar.php'; ?>

<!-- Additional CSS for submission page -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
.table-container {
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
}

.card-body {
    background-color: #f8f9fa;
}

.btn-export {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.btn-export:hover {
    background-color: #218838;
}
</style>

            <main>
                <div class="container-fluid p-4">
                    <h1 class="">Records</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">DMS - Records</li>
                    </ol>
                    
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
                        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'supervisor')): ?>
                            <div class="mb-3">
                                <a href="submission.php?export_csv=1" class="btn btn-export">
                                    <i class="fas fa-download"></i> Export to CSV
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card">
                            <div class="card-body table-container bg-white">
                                <div class="table-responsive">
                                    <table id="submissionTable" class="table table-bordered table-striped pt-3">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Product Name</th>
                                                <th>Machine</th>
                                                <th>IRN</th>
                                                <th>Mold Code</th>
                                                <th>Cycle Time (Target)</th>
                                                <th>Cycle Time (Actual)</th>
                                                <th>Cycle Time Difference</th>
                                                <th>Weight (Standard)</th>
                                                <th>Weight (Gross)</th>
                                                <th>Weight (Net)</th>
                                                <th>Cavity (Designed)</th>
                                                <th>Cavity (Active)</th>
                                                <th>Remarks</th>
                                                <th>Name</th>
                                                <th>Shift</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row["id"] . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["machine"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["prn"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["mold_code"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["cycle_time_target"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["cycle_time_actual"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["cycle_time_difference"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["weight_standard"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["weight_gross"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["weight_net"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["cavity_designed"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["cavity_active"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["remarks"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["shift"]) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='17' class='text-center'>No records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DataTables initialization -->
                <script>
                    $(document).ready(function() {
                        $('#submissionTable').DataTable({
                            responsive: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            pageLength: 25,
                            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                            language: {
                                search: "Search records:",
                                lengthMenu: "Show _MENU_ records per page",
                                info: "Showing _START_ to _END_ of _TOTAL_ records",
                                infoEmpty: "No records available",
                                infoFiltered: "(filtered from _MAX_ total records)"
                            }
                        });

                        // Show success modal if record was created
                        <?php if ($recordCreated): ?>
                            $('#successModal').modal('show');
                        <?php endif; ?>
                    });
                </script>
            </main>

<?php include '../includes/navbar_close.php'; ?>
