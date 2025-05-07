<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "sensory_data");
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Check request type
$type = isset($_GET['type']) ? $_GET['type'] : 'cycle'; 

if ($type === 'realtime') {
    // Fetch last 10 values from realtime_parameters
    $sql = "SELECT flow_rate FROM air_flow ORDER BY timestamp DESC LIMIT 10";
    $result = $conn->query($sql);

    $data = ["flow_rate" => []];

    while ($row = $result->fetch_assoc()) {
        $data["flow_rate"][] = $row["flow_rate"];
    }
}

$conn->close();
echo json_encode($data);
?>

