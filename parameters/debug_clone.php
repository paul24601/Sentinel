<?php
// Debug script to check what data is being cloned
session_start();

if (!isset($_GET['record_id'])) {
    die("Please provide a record_id parameter, e.g., ?record_id=PARAM_20250731_da614");
}

$recordId = $_GET['record_id'];

$conn = new mysqli("localhost", "root", "injectionadmin123", "injectionmoldingparameters");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$clonedData = [];

// Load all necessary tables and combine fields
$tables = [
    'productmachineinfo',
    'productdetails',
    'materialcomposition',
    'colorantdetails',
    'moldoperationspecs',
    'timerparameters',
    'barrelheatertemperatures',
    'moldheatertemperatures',
    'moldcloseparameters',      // Added missing table
    'moldopenparameters',       // Added missing table
    'plasticizingparameters',
    'injectionparameters',
    'ejectionparameters',
    'corepullsettings',
    'additionalinformation',
    'personnel'
];

echo "<h2>Debug: Clone Data for Record ID: " . htmlspecialchars($recordId) . "</h2>";

foreach ($tables as $table) {
    echo "<h3>Table: $table</h3>";
    $sql = "SELECT * FROM $table WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $recordId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0; font-family: monospace;'>";
        echo "<strong>Found " . count($row) . " fields:</strong><br>";
        foreach ($row as $field => $value) {
            $displayValue = $value === null ? 'NULL' : (empty($value) ? 'EMPTY' : $value);
            echo "$field = $displayValue<br>";
        }
        echo "</div>";
        $clonedData = array_merge($clonedData, $row);
    } else {
        echo "<p style='color: #999;'>No data found in this table.</p>";
    }
    $stmt->close();
}

echo "<h3>Summary</h3>";
echo "<p><strong>Total fields loaded:</strong> " . count($clonedData) . "</p>";

// Count non-empty fields
$nonEmptyFields = 0;
foreach ($clonedData as $field => $value) {
    if ($value !== null && $value !== '') {
        $nonEmptyFields++;
    }
}
echo "<p><strong>Non-empty fields:</strong> $nonEmptyFields</p>";

echo "<h3>All Fields for JavaScript (sample)</h3>";
echo "<div style='background: #e6f3ff; padding: 10px; margin: 10px 0; font-family: monospace; max-height: 300px; overflow-y: auto;'>";
echo "<pre>" . json_encode($clonedData, JSON_PRETTY_PRINT) . "</pre>";
echo "</div>";

$conn->close();
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #333; }
h3 { color: #666; border-bottom: 1px solid #ccc; }
</style>
