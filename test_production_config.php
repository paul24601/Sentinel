<?php
/**
 * Test Production Database Configuration
 * This file tests if the production database credentials work correctly
 */

// Force production environment for testing
$_ENV['APP_ENV'] = 'production';

require_once 'includes/database.php';

echo "<h2>Testing Production Database Configuration</h2>\n";
echo "<p>Testing connection to Sentinel Digital servers...</p>\n";

$databases = [
    'sentinel_admin' => 'Admin Database',
    'sentinel_monitoring' => 'Daily Monitoring Sheet',
    'sentinel_main' => 'Injection Molding Parameters',
    'sentinel_production' => 'Production Reports',
    'sentinel_sensory' => 'Sensory Data'
];

foreach ($databases as $db_key => $db_name) {
    echo "<h3>Testing {$db_name} ({$db_key})</h3>\n";
    
    try {
        $conn = DatabaseManager::getConnection($db_key);
        echo "<p style='color: green;'>✅ SUCCESS: Connected to {$db_name}</p>\n";
        echo "<p>Server Info: " . $conn->server_info . "</p>\n";
        echo "<p>Host Info: " . $conn->host_info . "</p>\n";
        
        // Test a simple query
        $result = $conn->query("SELECT 1 as test");
        if ($result) {
            echo "<p style='color: green;'>✅ Query test passed</p>\n";
        } else {
            echo "<p style='color: orange;'>⚠️ Query test failed: " . $conn->error . "</p>\n";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ FAILED: " . $e->getMessage() . "</p>\n";
    }
    
    echo "<hr>\n";
}

echo "<h3>Configuration Summary</h3>\n";
echo "<p><strong>Environment:</strong> " . (function_exists('detectEnvironment') ? detectEnvironment() : 'production') . "</p>\n";
echo "<p><strong>DB Host:</strong> " . DB_HOST . "</p>\n";
echo "<p><strong>Databases configured:</strong> " . count($databases) . "</p>\n";

?>
