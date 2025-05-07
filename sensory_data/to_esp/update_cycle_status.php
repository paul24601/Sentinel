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
    $cycle_status = intval($_GET['cycle_status']); // Ensure integer value (0 or 1)
    $cycle_time = isset($_GET['cycle_time']) ? intval($_GET['cycle_time']) : 0; // Get cycle duration if available
    $recycle_time = isset($_GET['recycle_time']) ? intval($_GET['recycle_time']) : 0; // Get recycle time if available

    if ($cycle_status == 1) {
        // Insert a new row with cycle_status = 1, including the last recorded recycle time
        $stmt = $conn->prepare("INSERT INTO production_cycle (cycle_status, cycle_time, recycle_time, timestamp) VALUES (1, 0, ?, NOW())");
        $stmt->bind_param("i", $recycle_time);
    } else {
        // Update the latest row where cycle_status = 1, setting cycle_status = 0, cycle_time
        $stmt = $conn->prepare("UPDATE production_cycle 
                                SET cycle_status = 0, cycle_time = ? 
                                WHERE cycle_status = 1 
                                ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("i", $cycle_time);
    }

    // Execute the query
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
