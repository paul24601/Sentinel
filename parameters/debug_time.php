<?php
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

echo "<h2>Time Debug Information</h2>";
echo "<p><strong>Current PHP Date/Time Functions:</strong></p>";
echo "date_default_timezone_get(): " . date_default_timezone_get() . "<br>";
echo "date('Y-m-d H:i:s'): " . date('Y-m-d H:i:s') . "<br>";
echo "date('H:i:s'): " . date('H:i:s') . "<br>";

echo "<p><strong>Server Information:</strong></p>";
echo "Server timezone: " . ini_get('date.timezone') . "<br>";
echo "System time: " . exec('date') . "<br>";

// Test database connection and time storage
$conn = new mysqli("localhost", "root", "injectionadmin123", "injectionmoldingparameters");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Test time insertion
$testTime = date('H:i:s'); // Current Philippine time
echo "<p><strong>Test Time Insertion:</strong></p>";
echo "PHP Generated Time: " . $testTime . "<br>";

// Test what gets stored in database
$sql = "SELECT NOW() as server_time, TIME(NOW()) as server_time_only";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo "MySQL Server Time: " . $row['server_time'] . "<br>";
echo "MySQL Time Only: " . $row['server_time_only'] . "<br>";

// Check recent records
$sql = "SELECT Date, Time, startTime, endTime FROM productmachineinfo ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);
echo "<p><strong>Recent Database Records:</strong></p>";
while ($row = $result->fetch_assoc()) {
    echo "Date: " . $row['Date'] . ", Time: " . $row['Time'] . ", Start: " . $row['startTime'] . ", End: " . $row['endTime'] . "<br>";
}

$conn->close();
?>
