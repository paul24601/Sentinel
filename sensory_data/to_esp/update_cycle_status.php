<?php
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$database = "sensory_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'cycle_status' parameter is received
if (isset($_GET['cycle_status'])) {
    $cycle_status = intval($_GET['cycle_status']);
    $cycle_time = isset($_GET['cycle_time']) ? intval($_GET['cycle_time']) : 0;
    $recycle_time = isset($_GET['recycle_time']) ? intval($_GET['recycle_time']) : 0;
    $machine = isset($_GET['machine']) ? $conn->real_escape_string($_GET['machine']) : '';

    if ($cycle_status == 1) {
        // INSERT new cycle with status = 1
        $stmt = $conn->prepare("INSERT INTO production_cycle (cycle_status, cycle_time, recycle_time, machine, timestamp) VALUES (1, 0, ?, ?, NOW())");
        $stmt->bind_param("is", $recycle_time, $machine);
    } else {
        // UPDATE last cycle with status = 1 → status = 0 and set cycle_time
        $stmt = $conn->prepare("UPDATE production_cycle 
                                SET cycle_status = 0, cycle_time = ? 
                                WHERE cycle_status = 1 
                                ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("i", $cycle_time);
    }

    if ($stmt->execute()) {
        echo "Status updated successfully!";
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No status parameter received!";
}

$conn->close();
?>