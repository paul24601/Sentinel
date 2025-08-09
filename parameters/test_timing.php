<?php
// Test script to check timing logic
date_default_timezone_set('Asia/Manila');

// Test the formatPhilippineTime function
function formatPhilippineTime($timeValue) {
    if (empty($timeValue)) {
        return null;
    }
    
    // Handle placeholder values - if it's 00:00, treat it as empty/unset
    if ($timeValue === '00:00' || $timeValue === '00:00:00') {
        return null;
    }
    
    // If it's already in H:i:s format, use it directly
    if (preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $timeValue)) {
        return $timeValue;
    }
    
    // If it's in H:i format, add seconds
    if (preg_match('/^\d{1,2}:\d{2}$/', $timeValue)) {
        return $timeValue . ':00';
    }
    
    // Fallback to current Philippine time
    return date('H:i:s');
}

echo "<h2>Testing Timing Logic</h2>";

// Test different scenarios
$testCases = [
    'empty' => '',
    'null' => null,
    'placeholder_00:00' => '00:00',
    'placeholder_00:00:00' => '00:00:00',
    'valid_time_HH:MM' => '14:30',
    'valid_time_HH:MM:SS' => '14:30:45'
];

foreach ($testCases as $label => $testValue) {
    $result = formatPhilippineTime($testValue);
    echo "<p><strong>$label</strong>: Input='$testValue' → Output='$result'</p>";
}

echo "<hr>";

// Test our timing logic
echo "<h3>Current Timing Logic Test</h3>";

$_POST['startTime'] = '14:30:00';  // Simulate start time
$_POST['endTime'] = '00:00';       // Simulate placeholder end time

$startTime = !empty($_POST['startTime']) ? formatPhilippineTime($_POST['startTime']) : null;
$endTime = !empty($_POST['endTime']) ? formatPhilippineTime($_POST['endTime']) : null;

echo "<p>POST startTime: " . ($_POST['startTime'] ?? 'NOT SET') . "</p>";
echo "<p>POST endTime: " . ($_POST['endTime'] ?? 'NOT SET') . "</p>";
echo "<p>After formatPhilippineTime - startTime: " . ($startTime ?? 'NULL') . "</p>";
echo "<p>After formatPhilippineTime - endTime: " . ($endTime ?? 'NULL') . "</p>";

// Apply our logic
$currentTime = date('H:i:s');
$endTime = $currentTime;
echo "<p>FORCED endTime to current time: " . $endTime . "</p>";

if (empty($startTime)) {
    $startTime = date('H:i:s', strtotime('-5 minutes'));
    echo "<p>Set startTime to 5 minutes ago: " . $startTime . "</p>";
}

// Add delay
sleep(1);
$endTime = date('H:i:s');
echo "<p>Final endTime after delay: " . $endTime . "</p>";

echo "<p><strong>FINAL VALUES</strong></p>";
echo "<p>startTime: " . ($startTime ?? 'NULL') . "</p>";
echo "<p>endTime: " . ($endTime ?? 'NULL') . "</p>";
echo "<p>Times are different: " . ($startTime !== $endTime ? 'YES' : 'NO') . "</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
?>
