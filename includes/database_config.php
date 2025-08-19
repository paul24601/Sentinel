<?php
/**
 * DEPRECATED: This file is deprecated in favor of the new centralized configuration system.
 * Please use includes/database.php instead.
 * 
 * This file is kept for backward compatibility only.
 */

// Load the new configuration system
require_once __DIR__ . '/database.php';

// Legacy variables for backward compatibility
$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASS_SENTINEL;

// Database names
$db_injectionmolding = DB_SENTINEL_MAIN;
$db_dailymonitoring = DB_SENTINEL_MONITORING;
$db_productionreport = DB_SENTINEL_PRODUCTION;
$db_sensorydata = DB_SENTINEL_SENSORY;

// Legacy function for backward compatibility
function createConnection($database = 'injectionmoldingparameters') {
    $dbMap = [
        'injectionmoldingparameters' => 'sentinel_main',
        'dailymonitoringsheet' => 'sentinel_monitoring',
        'productionreport' => 'sentinel_production',
        'sensory_data' => 'sentinel_sensory',
        'admin_sentinel' => 'sentinel_admin'
    ];
    
    $dbKey = $dbMap[$database] ?? 'sentinel_main';
    return DatabaseManager::getConnection($dbKey);
}

// Create default connection for legacy compatibility
try {
    $conn = DatabaseManager::getConnection('sentinel_main');
} catch (Exception $e) {
    // Handle error appropriately
    error_log("Legacy database connection failed: " . $e->getMessage());
}
?>
?>
