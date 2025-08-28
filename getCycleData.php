<?php
session_start();
if (!isset($_SESSION['full_name'])) {
    http_response_code(403);
    exit();
}

// Load centralized database configuration
require_once __DIR__ . '/includes/database.php';

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

$sqlCycleChart = "SELECT DATE(`date`) as day, AVG(cycle_time_actual) as avg_cycle_time 
                  FROM submissions 
                  GROUP BY day 
                  ORDER BY day";
$resultCycleChart = $conn->query($sqlCycleChart);
$cycleLabels = [];
$cycleData = [];
if ($resultCycleChart && $resultCycleChart->num_rows > 0) {
    while($row = $resultCycleChart->fetch_assoc()) {
        $cycleLabels[] = $row['day'];
        $cycleData[] = round($row['avg_cycle_time'], 2);
    }
}
echo json_encode(['labels' => $cycleLabels, 'data' => $cycleData]);
?>
