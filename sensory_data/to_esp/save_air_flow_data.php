<?php
date_default_timezone_set('Asia/Manila');

$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "sensory_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$airFlow = isset($_GET['airFlow']) ? floatval($_GET['airFlow']) : null;

$currentMinute = date('Y-m-d H:i:00'); // Round to start of the minute

// Check if an entry for the current minute already exists
$stmt = $conn->prepare("SELECT id FROM air_flow WHERE timestamp = ?");
$stmt->bind_param("s", $currentMinute);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If an entry exists, update it
    if ($airFlow !== null) { // AIR FLOW
        $stmt = $conn->prepare("UPDATE air_flow SET flow_rate = ? WHERE timestamp = ?");
        $stmt->bind_param("ds", $airFlow, $currentMinute);
    }
} else {
    // If no entry exists, insert a new one
    if ($airFlow !== null) { // AIR FLOW
        $stmt = $conn->prepare("INSERT INTO air_flow (flow_rate, timestamp) VALUES (?, ?)");
        $stmt->bind_param("ds", $airFlow, $currentMinute);
    }
}

if ($stmt->execute()) {
    echo "Data saved successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
