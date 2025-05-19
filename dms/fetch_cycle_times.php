<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "injectionadmin123", "sensory_data");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the last 10 cycle times that haven't been used yet
$sql = "SELECT pc.id, pc.cycle_time, pc.timestamp, pc.machine 
        FROM production_cycle pc 
        LEFT JOIN used_cycle_times uct ON pc.id = uct.cycle_time_id 
        WHERE uct.id IS NULL 
        ORDER BY pc.timestamp DESC 
        LIMIT 10";

$result = $conn->query($sql);
$cycle_times = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cycle_times[] = array(
            'id' => $row['id'],
            'cycle_time' => $row['cycle_time'],
            'timestamp' => $row['timestamp'],
            'machine' => $row['machine']
        );
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($cycle_times);

$conn->close();
?> 