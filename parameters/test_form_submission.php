<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Form Submission Test</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>🔍 Testing Form Field Reception:</h3>";
    
    // Test critical fields
    $criticalFields = [
        'moldOpenPos1', 'moldOpenPos2', 'moldOpenPos3', 'moldOpenPos4', 'moldOpenPos5', 'moldOpenPos6',
        'moldOpenSpd1', 'moldOpenSpd2', 'moldOpenSpd3', 'moldOpenSpd4', 'moldOpenSpd5', 'moldOpenSpd6',
        'moldOpenPressure1', 'moldOpenPressure2', 'moldOpenPressure3', 'moldOpenPressure4', 'moldOpenPressure5', 'moldOpenPressure6',
        'moldClosePos1', 'moldClosePos2', 'moldClosePos3', 'moldClosePos4', 'moldClosePos5', 'moldClosePos6',
        'moldCloseSpd1', 'moldCloseSpd2', 'moldCloseSpd3', 'moldCloseSpd4', 'moldCloseSpd5', 'moldCloseSpd6',
        'moldClosePressure1', 'moldClosePressure2', 'moldClosePressure3', 'moldClosePressure4',
        'stationary-cooling-media', 'movable-cooling-media', 'stationary-cooling-media-remarks', 'movable-cooling-media-remarks',
        'operation-type', 'Zone1', 'Zone2', 'Zone3'
    ];
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field Name</th><th>Status</th><th>Value</th></tr>";
    
    foreach ($criticalFields as $field) {
        $status = isset($_POST[$field]) ? "✅ RECEIVED" : "❌ MISSING";
        $value = isset($_POST[$field]) ? htmlspecialchars($_POST[$field]) : "N/A";
        $color = isset($_POST[$field]) ? "lightgreen" : "lightcoral";
        echo "<tr style='background-color: $color;'><td>$field</td><td>$status</td><td>$value</td></tr>";
    }
    echo "</table>";
    
    echo "<h3>📊 Complete POST Data:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 400px; overflow-y: auto;'>";
    print_r($_POST);
    echo "</pre>";
    
} else {
    echo "<p>Please submit the form to test field reception.</p>";
    echo "<a href='index.php' class='btn btn-primary'>Go to Parameters Form</a>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 20px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
.btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 10px 0; }
</style>
