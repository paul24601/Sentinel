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

// Function to get pending submissions for notifications
function getPendingSubmissions($conn)
{
    $pending = [];
    // Get the user's role from the session
    $role = $_SESSION['role'];

    // Base query for submissions pending overall approval
    $sql_pending = "SELECT id, product_name, `date` FROM submissions WHERE approval_status = 'pending'";

    // Check the role and append additional conditions for individual approvals
    if (in_array($role, ['supervisor', 'admin'])) {
        $sql_pending .= " AND (supervisor_status IS NULL OR supervisor_status = 'pending')";
    } elseif (in_array($role, ['Quality Assurance Engineer', 'Quality Assurance Supervisor', 'Quality Control Inspection'])) {
        $sql_pending .= " AND (qa_status IS NULL OR qa_status = 'pending')";
    }

    $sql_pending .= " ORDER BY `date` DESC";

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

$recordCreated = false;

// Export CSV (Allow only if user is admin or supervisor)
if (isset($_GET['export_csv'])) {
    if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'supervisor')) {
        $filename = "DMS_data_" . date('Ymd') . ".csv";
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output = fopen("php://output", "w");
        $headers = [
            "ID",
            "Date",
            "Product Name",
            "Machine",
            "PRN",
            "Mold Code",
            "Cycle Time (Target)",
            "Cycle Time (Actual)",
            "Cycle Time Difference",
            "Weight (Standard)",
            "Weight (Gross)",
            "Weight (Net)",
            "Cavity (Designed)",
            "Cavity (Active)",
            "Remarks",
            "Name",
            "Shift"
        ];
        fputcsv($output, $headers);

        // Only export fully approved submissions
        $sql = "SELECT id, date, product_name, machine, prn, mold_code, cycle_time_target, cycle_time_actual, 
                (cycle_time_target - cycle_time_actual) AS cycle_time_difference, weight_standard, weight_gross, 
                weight_net, cavity_designed, cavity_active, remarks, name, shift 
                FROM submissions WHERE approval_status = 'approved'";
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
            <title>Unauthorized Access</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body class='bg-light d-flex align-items-center justify-content-center vh-100'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-6 offset-md-3'>
                        <div class='alert alert-danger text-center'>
                            <h4 class='alert-heading'>Unauthorized Access</h4>
                            <p>You do not have permission to export this data.</p>
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

// Insert form data into database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $product_name = $_POST['product_name'];
    $machine = $_POST['machine'];
    $prn = $_POST['prn'];
    $mold_code = $_POST['mold_code'];
    $cycle_time_target = $_POST['cycle_time_target'];
    $cycle_time_actual = $_POST['cycle_time_actual'];
    $weight_standard = $_POST['weight_standard'];
    $weight_gross = $_POST['weight_gross'];
    $weight_net = $_POST['weight_net'];
    $cavity_designed = $_POST['cavity_designed'];
    $cavity_active = $_POST['cavity_active'];
    $remarks = $_POST['remarks'];
    $name = $_POST['name'];
    $shift = $_POST['shift'];

    $sql = "INSERT INTO submissions (date, product_name, machine, prn, mold_code, cycle_time_target, cycle_time_actual, weight_standard, weight_gross, weight_net, cavity_designed, cavity_active, remarks, name, shift) 
            VALUES ('$date', '$product_name', '$machine', '$prn', '$mold_code', '$cycle_time_target', '$cycle_time_actual', '$weight_standard', '$weight_gross', '$weight_net', '$cavity_designed', '$cavity_active', '$remarks', '$name', '$shift')";

    if ($conn->query($sql) === TRUE) {
        $recordCreated = true;

        // Configure and send email notification using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sentinel.dms.notifications@gmail.com';
            $mail->Password = 'zmys tnix xjjp jbsz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('sentinel.dms.notifications@gmail.com', 'DMS Notifications');

            // Email for admins/supervisors
            $mail->addAddress('injectiondigitization@gmail.com');
            // Email for the QA team
            $mail->addAddress('qa.dms.notifications@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'New Pending Submission Notification';

            $mail->Body = "
        Dear Team,<br><br>
        A new submission for product: <strong>{$product_name}</strong> has been created on <strong>{$date}</strong> and is pending approval.<br><br>
        Please review it in the system by clicking <a href='http://143.198.215.249/main-landing/dms/approval.php'>here</a>.<br><br>
        If you're not logged in, please click <a href='http://143.198.215.249/main-landing/login.html'>here</a> to log in.<br><br>
        Regards,<br>
        DMS System
    ";

            $mail->AltBody = "Dear Team,\n\nA new submission for product: {$product_name} has been created on {$date} and is pending approval.\n\nReview it at: http://143.198.215.249/main-landing/dms/approval.php\n\nIf you're not logged in, visit: http://143.198.215.249/main-landing/login.html\n\nRegards,\nDMS System";

            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve data from database - only show fully approved submissions
$sql = "SELECT id, date, product_name, machine, prn, mold_code, cycle_time_target, 
        cycle_time_actual, 
        (cycle_time_target - cycle_time_actual) AS cycle_time_difference, 
        weight_standard, weight_gross, weight_net, cavity_designed, cavity_active, 
        remarks, name, shift 
        FROM submissions WHERE approval_status = 'approved'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMS - Records</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <!-- DataTables Scroller CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/scroller/2.2.0/css/scroller.dataTables.min.css">
    <style>
        .table-container {
            border: 1px solid #ddd;
            padding: 1rem;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel Digitization</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
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
                                    <a class="nav-link active" href="submission.php">Records</a>
                                    <a class="nav-link" href="approval.php">Approvals</a>
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
                                    <a class="nav-link active" href="submission.php">Records</a>
                                    <a class="nav-link" href="analytics.php">Analytics</a>
                                    <a class="nav-link" href="approval.php">Approvals</a>
                                    <a class="nav-link" href="declined_submissions.php">Declined</a>
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
                    <h1 class="">Records</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
                    </ol>
                    <div class="container-fluid my-5">
                        <?php if ($recordCreated): ?>
                            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="successModalLabel">Submission Pending</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Your submission is pending and will be reviewed by your supervisor.
                                        </div>
                                        <div class="modal-footer">
                                            <!-- Link to the data entry page for another submission -->
                                            <a href="index.php" class="btn btn-primary">Make Another Submission</a>
                                            <!-- Link to remain on the records page -->
                                            <a href="submission.php" class="btn btn-secondary">Stay on Records Page</a>
                                        </div>
                                    </div>
                                </div>
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
                                                <th>PRN</th>
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
                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-grid">
                                <a href="?export_csv=1" class="btn btn-success m-3">Export to CSV</a>
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
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../assets/demo/chart-area-demo.js"></script>
    <script src="../assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Load Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Load DataTables Core JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- Load DataTables Buttons extension -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <!-- Load Buttons HTML5 export -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <!-- Load Buttons Print view -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <!-- Supporting libraries for export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#submissionTable').DataTable({
                pageLength: 10,
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 100, 200],
                dom: 'lBfrtip',  // 'l' adds the length dropdown along with Buttons 'B'
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            <?php if ($recordCreated): ?>
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            <?php endif; ?>
        });
    </script>
</body>

</html>