<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Load the centralized configuration
require_once __DIR__ . '/../includes/database.php';

// Get database connection using the centralized system
try {
    $conn = DatabaseManager::getConnection('sentinel_sensory');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get the last 10 cycle times that haven't been used yet
$sql = "SELECT pc.id, pc.cycle_time, pc.recycle_time, pc.timestamp, pc.tempC_01, pc.tempC_02, pc.pressure 
        FROM production_cycle pc 
        LEFT JOIN used_cycle_times uct ON pc.id = uct.cycle_time_id 
        WHERE uct.id IS NULL 
        AND pc.cycle_time > 0  -- Only get completed cycles
        ORDER BY pc.timestamp DESC 
        LIMIT 10";

$result = $conn->query($sql);
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

$conn->close();
?> 