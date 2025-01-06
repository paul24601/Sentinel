<?php
session_start(); // Start session to access the user's role

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recordCreated = false;

// Export CSV (Allow only if user is admin or supervisor)
if (isset($_GET['export_csv'])) {
    if (isset($_SESSION['role']) && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'supervisor')) {
        $filename = "DMS_data_" . date('Ymd') . ".csv";
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output = fopen("php://output", "w");
        $headers = ["ID", "Date", "Product Name", "Machine", "PRN", "Mold Code", "Cycle Time (Target)", "Cycle Time (Actual)", 
                    "Cycle Time Difference", "Weight (Standard)", "Weight (Gross)", "Weight (Net)", "Cavity (Designed)", 
                    "Cavity (Active)", "Remarks", "Name", "Shift"];
        fputcsv($output, $headers);

        $sql = "SELECT id, date, product_name, machine, prn, mold_code, cycle_time_target, cycle_time_actual, 
                (cycle_time_target - cycle_time_actual) AS cycle_time_difference, weight_standard, weight_gross, 
                weight_net, cavity_designed, cavity_active, remarks, name, shift 
                FROM submissions";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, $row);
            }
        }
        fclose($output);
        exit();
    } else {
        // Redirect or show an error message if the user is not authorized
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Unauthorized Access</title>
            <!-- Bootstrap CSS -->
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
            <!-- Bootstrap JS -->
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
            <!-- JavaScript to go back to the previous page -->
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
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve data from database
$sql = "SELECT id, date, product_name, machine, prn, mold_code, cycle_time_target, 
        cycle_time_actual, 
        (cycle_time_target - cycle_time_actual) AS cycle_time_difference, 
        weight_standard, weight_gross, weight_net, cavity_designed, cavity_active, 
        remarks, name, shift 
        FROM submissions";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMS System Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <style>
        .table-container {
            border: 1px solid #ddd;
            padding: 1rem;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }
    </style>
</head>

<body class="bg-secondary-subtle">
    <div class="container my-5">

        <?php if ($recordCreated): ?>
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Success</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            New record created successfully.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2 class="text-center">Daily Monitoring Sheet Data</h2>
            </div>
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

            <div class="row px-3">
                <div class="d-grid col-12 col-md-6">
                    <a href="index.php" class="btn btn-secondary mt-3">Back to Form</a>
                </div>
                <div class="d-grid col-12 col-md-6">
                    <a href="?export_csv=1" class="btn btn-success mt-3">Export to CSV</a>
                </div>
            </div>

            <div class="row px-3">
                <div class="d-grid col-12 col-md-6">
                    <a href="../admin_dashboard.php" class="btn btn-info mt-3">Admin Dashboard</a>
                </div>
                
                <div class="d-grid col-12 col-md-6">
                    <a href="../admin.php" class="btn btn-primary mt-3">View DMS Analytics</a>
                </div>
            </div>

            <!-- Logout Button -->
            <div class="d-grid p-3 pt-0">
                <a href="../logout.php" class="btn btn-danger mt-3">Log out</a>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#submissionTable').DataTable({
                "pageLength": 10,
                "responsive": true,
                "lengthMenu": [5, 10, 25, 50, 100, 200]
            });
        });

        <?php if ($recordCreated): ?>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        <?php endif; ?>
    </script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    
</body>

</html>
