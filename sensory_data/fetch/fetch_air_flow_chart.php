<?php
$conn = new mysqli("localhost", "root", "", "sensory_data");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$year = date('Y');

$sql = "SELECT DATE(timestamp) as date, 
            AVG(flow_rate) as flow_rate 
        FROM air_flow
        WHERE MONTH(timestamp) = $month AND YEAR(timestamp) = $year
        GROUP BY DATE(timestamp) 
        ORDER BY timestamp ASC";

$result = $conn->query($sql);

$data = [
    'days' => [],
    'flow_rate' => []
];

while ($row = $result->fetch_assoc()) {
    $data['days'][] = date('d', strtotime($row['date']));
    $data['flow_rate'][] = round($row['flow_rate'], 2);
}

echo json_encode($data);
?>
