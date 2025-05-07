<?php
$conn = new mysqli("localhost", "root", "", "sensory_data");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$year = date('Y');

$sql = "SELECT DATE(timestamp) as date, 
            AVG(tempC_01) as tempC_01, 
            AVG(tempC_02) as tempC_02, 
            AVG(humidity) as humidity, 
            AVG(water_flow) as water_flow,
            AVG(air_flow) as air_flow 
        FROM realtime_parameters 
        WHERE MONTH(timestamp) = $month AND YEAR(timestamp) = $year
        GROUP BY DATE(timestamp) 
        ORDER BY timestamp ASC";

$result = $conn->query($sql);

$data = [
    'days' => [],
    'tempC_01' => [],
    'tempC_02' => [],
    'humidity' => [],
    'waterFlow' => [],
    'airFlow' => []
];

while ($row = $result->fetch_assoc()) {
    $data['days'][] = date('d', strtotime($row['date']));
    $data['tempC_01'][] = round($row['tempC_01'], 2);
    $data['tempC_02'][] = round($row['tempC_02'], 2);
    $data['humidity'][] = round($row['humidity'], 2);
    $data['waterFlow'][] = round($row['water_flow'], 2);
    $data['airFlow'][] = round($row['air_flow'], 2);
}

echo json_encode($data);
?>
