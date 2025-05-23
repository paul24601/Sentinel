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

// Get the machine from the request
$machine = isset($_GET['machine']) ? $_GET['machine'] : null;

// Get the last 5 cycle times for the specified machine that haven't been used yet
$sql = "SELECT pc.id, pc.cycle_time, pc.recycle_time, pc.timestamp, pc.tempC_01, pc.tempC_02, pc.pressure 
        FROM production_cycle pc 
        LEFT JOIN used_cycle_times uct ON pc.id = uct.cycle_time_id 
        WHERE uct.id IS NULL 
        AND pc.cycle_time > 0  -- Only get completed cycles
        AND pc.machine = ?  -- Filter by machine
        ORDER BY pc.timestamp DESC 
        LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $machine);
$stmt->execute();
$result = $stmt->get_result();

$cycle_times = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cycle_times[] = array(
            'id' => $row['id'],
            'cycle_time' => $row['cycle_time'],
            'recycle_time' => $row['recycle_time'],
            'timestamp' => $row['timestamp'],
            'temp1' => $row['tempC_01'],
            'temp2' => $row['tempC_02'],
            'pressure' => $row['pressure']
        );
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($cycle_times);

$stmt->close();
$conn->close();
?> 