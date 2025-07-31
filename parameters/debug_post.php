<?php
// Debug script to see what POST data is being sent
echo "<h2>POST Data Debug</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Raw POST Data:</h3>";
    echo "<pre>";
    foreach ($_POST as $key => $value) {
        if (in_array($key, ['Date', 'Time', 'startTime', 'endTime'])) {
            echo "$key = '$value'\n";
        }
    }
    echo "</pre>";
    
    // Test our time formatting function
    echo "<h3>Time Processing Test:</h3>";
    
    function formatPhilippineTime($timeValue) {
        if (empty($timeValue)) {
            return null;
        }
        
        echo "Input: '$timeValue' -> ";
        
        // If it's already in H:i:s format, use it directly
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeValue)) {
            echo "Matched HH:MM:SS format -> '$timeValue'\n";
            return $timeValue;
        }
        
        // If it's in H:i format, add seconds
        if (preg_match('/^\d{2}:\d{2}$/', $timeValue)) {
            $result = $timeValue . ':00';
            echo "Matched HH:MM format -> '$result'\n";
            return $result;
        }
        
        // Try to parse as timestamp and convert to Philippine time
        try {
            $dt = new DateTime($timeValue);
            $dt->setTimezone(new DateTimeZone('Asia/Manila'));
            $result = $dt->format('H:i:s');
            echo "Parsed as DateTime -> '$result'\n";
            return $result;
        } catch (Exception $e) {
            // Fallback to current Philippine time
            $result = date('H:i:s');
            echo "Exception: " . $e->getMessage() . " -> Fallback: '$result'\n";
            return $result;
        }
    }
    
    if (!empty($_POST['startTime'])) {
        echo "startTime: ";
        $processed = formatPhilippineTime($_POST['startTime']);
        echo "<br>";
    }
    
    if (!empty($_POST['endTime'])) {
        echo "endTime: ";
        $processed = formatPhilippineTime($_POST['endTime']);
        echo "<br>";
    }
    
    if (!empty($_POST['Time'])) {
        echo "Time: ";
        $processed = formatPhilippineTime($_POST['Time']);
        echo "<br>";
    }
    
} else {
    echo "<p>No POST data received. Submit the form to see debug information.</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #333; }
h3 { color: #666; border-bottom: 1px solid #ccc; }
pre { background: #f0f0f0; padding: 10px; }
</style>
