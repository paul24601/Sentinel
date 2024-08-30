<?php
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password
$dbname = "dms_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    echo "No valid JSON data was received.";
    exit;
}

// Process each row of data
foreach ($data as $row) {
    if (!empty($row)) {
        $machine = $conn->real_escape_string($row[0]);
        $prn = $conn->real_escape_string($row[1]);
        $product_name = $conn->real_escape_string($row[2]);
        $cycle_time_target = (float)$row[3];
        $cycle_time_actual = (float)$row[4];
        $weight_standard = (float)$row[5];
        $weight_gross = (float)$row[6];
        $weight_net = (float)$row[7];
        $no_of_cavity_designed = (int)$row[8];
        $no_of_cavity_active = (int)$row[9];
        $remarks = $conn->real_escape_string($row[10]);

        $sql = "INSERT INTO dms_data (machine, prn, product_name, cycle_time_target, cycle_time_actual, weight_standard, weight_gross, weight_net, no_of_cavity_designed, no_of_cavity_active, remarks) 
                VALUES ('$machine', '$prn', '$product_name', '$cycle_time_target', '$cycle_time_actual', '$weight_standard', '$weight_gross', '$weight_net', '$no_of_cavity_designed', '$no_of_cavity_active', '$remarks')";

        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

echo "New records created successfully";

$conn->close();
?>
