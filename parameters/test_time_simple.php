<?php
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

echo "<h2>Simple Time Test - Philippine Time</h2>";

// Test current times
echo "<h3>Current Times:</h3>";
echo "PHP date(): " . date('Y-m-d H:i:s') . "<br>";
echo "PHP time(): " . date('H:i:s') . "<br>";
echo "PHP timezone: " . date_default_timezone_get() . "<br>";

// Test what happens when we store these times
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');
$startTime = date('H:i:s');
$endTime = date('H:i:s', strtotime('+5 minutes'));

echo "<h3>Sample Form Values:</h3>";
echo "Date: $currentDate<br>";
echo "Time: $currentTime<br>";
echo "Start Time: $startTime<br>";
echo "End Time: $endTime<br>";

// Test database connection and insertion
try {
    $conn = new mysqli("localhost", "root", "injectionadmin123", "injectionmoldingparameters");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set MySQL timezone
    $conn->query("SET time_zone = '+08:00'");
    
    // Test insertion with a sample record
    $record_id = 'TEST_' . date('Ymd_His');
    
    echo "<h3>Testing Database Insertion:</h3>";
    echo "Record ID: $record_id<br>";
    
    // Insert test data
    $sql = "INSERT INTO productmachineinfo (record_id, Date, Time, startTime, endTime, MachineName, RunNumber, Category, IRN) 
            VALUES (?, ?, ?, ?, ?, 'TEST_MACHINE', 'TEST_RUN', 'TEST_CATEGORY', 'TEST_IRN')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $record_id, $currentDate, $currentTime, $startTime, $endTime);
    
    if ($stmt->execute()) {
        echo "<span style='color: green;'>✓ Successfully inserted test record</span><br>";
        
        // Retrieve the data to see how it was stored
        $sql = "SELECT Date, Time, startTime, endTime FROM productmachineinfo WHERE record_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $record_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        echo "<h3>Retrieved from Database:</h3>";
        echo "Date: " . $row['Date'] . "<br>";
        echo "Time: " . $row['Time'] . "<br>";
        echo "Start Time: " . $row['startTime'] . "<br>";
        echo "End Time: " . $row['endTime'] . "<br>";
        
        // Clean up test record
        $conn->query("DELETE FROM productmachineinfo WHERE record_id = '$record_id'");
        echo "<br><span style='color: orange;'>Test record cleaned up</span>";
        
    } else {
        echo "<span style='color: red;'>✗ Failed to insert: " . $stmt->error . "</span><br>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span>";
}

echo "<h3>JavaScript Test:</h3>";
echo "<div id='jsTest'></div>";
?>

<script>
// Test the JavaScript time functions
function getCurrentPhilippineTime() {
    const now = new Date();
    // Convert to Philippine time (UTC+8)
    const philippineTime = new Date(now.getTime() + (8 * 60 * 60 * 1000));
    return philippineTime;
}

function formatTime(date) {
    const hours = String(date.getUTCHours()).padStart(2, '0');
    const minutes = String(date.getUTCMinutes()).padStart(2, '0');
    const seconds = String(date.getUTCSeconds()).padStart(2, '0');
    return `${hours}:${minutes}:${seconds}`;
}

const now = getCurrentPhilippineTime();
const timeString = formatTime(now);

document.getElementById('jsTest').innerHTML = `
    <strong>JavaScript Philippine Time:</strong> ${timeString}<br>
    <strong>Browser Local Time:</strong> ${new Date().toLocaleTimeString()}<br>
    <strong>Test Status:</strong> <span style="color: green;">JavaScript functions working</span>
`;
</script>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #333; }
h3 { color: #666; border-bottom: 1px solid #ccc; }
</style>
