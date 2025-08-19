<?php
/**
 * Environment Debug Tool
 * This file helps debug environment detection and database configuration
 */

require_once 'includes/config.php';

echo "<h2>Environment Debug Information</h2>\n";

echo "<h3>Server Information</h3>\n";
echo "<p><strong>SERVER_NAME:</strong> " . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'Not set') . "</p>\n";
echo "<p><strong>HTTP_HOST:</strong> " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'Not set') . "</p>\n";
echo "<p><strong>SERVER_ADDR:</strong> " . (isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'Not set') . "</p>\n";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'Not set') . "</p>\n";

echo "<h3>Environment Detection</h3>\n";
$detectedEnv = detectEnvironment();
echo "<p><strong>Detected Environment:</strong> " . $detectedEnv . "</p>\n";

echo "<h3>Database Configuration</h3>\n";
echo "<p><strong>DB_HOST:</strong> " . (defined('DB_HOST') ? DB_HOST : 'Not defined') . "</p>\n";

if ($detectedEnv === 'production') {
    echo "<p><strong>Production Mode Detected</strong></p>\n";
    echo "<p><strong>Admin DB:</strong> " . (defined('DB_SENTINEL_ADMIN') ? DB_SENTINEL_ADMIN : 'Not defined') . "</p>\n";
    echo "<p><strong>Admin User:</strong> " . (defined('DB_SENTINEL_ADMIN_USER') ? DB_SENTINEL_ADMIN_USER : 'Not defined') . "</p>\n";
    echo "<p><strong>Monitoring DB:</strong> " . (defined('DB_SENTINEL_MONITORING') ? DB_SENTINEL_MONITORING : 'Not defined') . "</p>\n";
    echo "<p><strong>Monitoring User:</strong> " . (defined('DB_SENTINEL_MONITORING_USER') ? DB_SENTINEL_MONITORING_USER : 'Not defined') . "</p>\n";
} else {
    echo "<p><strong>Local Mode Detected</strong></p>\n";
    echo "<p><strong>DB_USER:</strong> " . (defined('DB_USER') ? DB_USER : 'Not defined') . "</p>\n";
    echo "<p><strong>Monitoring DB:</strong> " . (defined('DB_SENTINEL_MONITORING') ? DB_SENTINEL_MONITORING : 'Not defined') . "</p>\n";
}

echo "<h3>Connection Test</h3>\n";
try {
    require_once 'includes/database.php';
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    echo "<p style='color: green;'>✅ SUCCESS: Database connection established</p>\n";
    echo "<p>Server Info: " . $conn->server_info . "</p>\n";
    echo "<p>Host Info: " . $conn->host_info . "</p>\n";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ FAILED: " . $e->getMessage() . "</p>\n";
}

?>
