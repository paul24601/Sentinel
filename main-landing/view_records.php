<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch records
$sql = "SELECT * FROM records"; // Adjust 'records' to your table name
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center">View Records</h2>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Submitted Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="submissionTable" class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Machine</th>
                                <th>PRN</th>
                                <th>Product Name</th>
                                <th>Cycle Time (Target)</th>
                                <th>Cycle Time (Actual)</th>
                                <th>Weight (Standard)</th>
                                <th>Weight (Gross)</th>
                                <th>Weight (Net)</th>
                                <th>Cavity (Designed)</th>
                                <th>Cavity (Active)</th>
                                <th>Remarks</th>
                                <th>Name</th>
                                <th>Shift</th>
                                <th>Search</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["machine"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["prn"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["cycle_time_target"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["cycle_time_actual"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["weight_standard"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["weight_gross"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["weight_net"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["cavity_designed"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["cavity_active"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["remarks"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["shift"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["search"]) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='16' class='text-center'>No records found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <a href="dms2.html" class="btn btn-primary mt-3">Back to Form</a>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#submissionTable').DataTable(); // Initialize DataTables
        });
    </script>
</body>
</html>
