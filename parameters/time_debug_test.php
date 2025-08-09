<?php
// Debug page to test time functionality
date_default_timezone_set('Asia/Manila');

// Function to convert time to Philippine timezone (same as in submit.php)
function formatPhilippineTime($timeValue) {
    if (empty($timeValue)) {
        echo "DEBUG - formatPhilippineTime received empty value<br>";
        return null;
    }
    
    echo "DEBUG - formatPhilippineTime received: " . $timeValue . "<br>";
    
    // If it's already in H:i:s format, use it directly
    if (preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $timeValue)) {
        echo "DEBUG - formatPhilippineTime returning H:i:s format: " . $timeValue . "<br>";
        return $timeValue;
    }
    
    // If it's in H:i format, add seconds
    if (preg_match('/^\d{1,2}:\d{2}$/', $timeValue)) {
        $result = $timeValue . ':00';
        echo "DEBUG - formatPhilippineTime returning H:i format with seconds: " . $result . "<br>";
        return $result;
    }
    
    // Fallback to current Philippine time
    $fallback = date('H:i:s');
    echo "DEBUG - formatPhilippineTime fallback to current time: " . $fallback . "<br>";
    return $fallback;
}

echo "<h2>Time Debug Test Page</h2>";
echo "<p>Current Philippine Time: " . date('Y-m-d H:i:s') . "</p>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>Time Processing Results:</h3>";
    $startTime = !empty($_POST['startTime']) ? formatPhilippineTime($_POST['startTime']) : null;
    $endTime = !empty($_POST['endTime']) ? formatPhilippineTime($_POST['endTime']) : null;
    
    echo "Processed startTime: " . ($startTime ?? 'NULL') . "<br>";
    echo "Processed endTime: " . ($endTime ?? 'NULL') . "<br>";
} else {
    echo "<h3>Test Form:</h3>";
    echo '<form method="POST">';
    echo 'Start Time: <input type="text" name="startTime" value="10:30:25" placeholder="H:i:s format"><br><br>';
    echo 'End Time: <input type="text" name="endTime" value="11:45:30" placeholder="H:i:s format"><br><br>';
    echo '<input type="submit" value="Test Time Processing">';
    echo '</form>';
}
?>
