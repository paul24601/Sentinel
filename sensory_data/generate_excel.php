<?php
header("Content-Type: application/vnd.ms-excel");
$table = isset($_GET['table']) ? $_GET['table'] : 'production_cycle';
header("Content-Disposition: attachment; filename={$table}.xls");
header("Pragma: no-cache");
header("Expires: 0");

$conn = new mysqli("localhost", "root", "", "sensory_data");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define columns based on table
if ($table == "production_cycle") {
    echo "ID\tCycle Time (seconds)\tRecycle Time (seconds)\tPressure (g)\tTemperature_01 (°C)\tTemperature_01 (°F)\tHumidity (%)\tTemperature_02 (°C)\tTemperature_02 (°F)\tTimestamp\n";
    $sql = "SELECT * FROM production_cycle ORDER BY timestamp DESC";
} else {
    echo "ID\tTemperature_01 (°C)\tTemperature_01 (°F)\tHumidity\tTemperature_02 (°C)\tTemperature_02 (°F)\tWater Flow\tAir Flow\tTimestamp\n";
    $sql = "SELECT * FROM realtime_parameters ORDER BY timestamp DESC";
}

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    if ($table == "production_cycle") {
        echo "{$row['id']}\t{$row['cycle_time']}\t{$row['recycle_time']}\t{$row['pressure']}\t{$row['tempC_01']}\t{$row['tempF_01']}\t{$row['humidity']}\t{$row['tempC_02']}\t{$row['tempF_02']}\t{$row['timestamp']}\n";
    } else {
        echo "{$row['id']}\t{$row['tempC_01']}\t{$row['tempF_01']}\t{$row['humidity']}\t{$row['tempC_02']}\t{$row['tempF_02']}\t{$row['water_flow']}\t{$row['air_flow']}\t{$row['timestamp']}\n";
    }
}

$conn->close();
?>
