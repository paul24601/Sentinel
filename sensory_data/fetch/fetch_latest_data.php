<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "injectionadmin123", "sensory_data");
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Check request type
$type = isset($_GET['type']) ? $_GET['type'] : 'cycle'; 

if ($type === 'realtime') {
    // Fetch last 10 values from realtime_parameters
    $sql = "SELECT tempC_01, tempC_02, humidity, water_flow,air_flow FROM realtime_parameters ORDER BY timestamp DESC LIMIT 10";
    $result = $conn->query($sql);

    $data = ["tempC_01" => [], "tempC_02" => [], "humidity" => [], 0, "air_flow" => []];

    while ($row = $result->fetch_assoc()) {
        $data["tempC_01"][] = $row["tempC_01"];
        $data["tempC_02"][] = $row["tempC_02"];
        $data["humidity"][] = $row["humidity"];
        $data["water_flow"][] = $row["water_flow"];
        $data["air_flow"][] = $row["air_flow"];
    }
} else {
    // Fetch latest production cycle data
    $sql = "SELECT tempC_01, tempC_02, pressure, cycle_status FROM production_cycle ORDER BY timestamp DESC LIMIT 1";
    $result = $conn->query($sql);

    $data = $result->fetch_assoc() ?: ["error" => "No data found"];
}

$conn->close();
echo json_encode($data);
?>

