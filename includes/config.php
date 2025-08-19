<?php
/**
 * Main Configuration Loader - Sentinel Project Only
 * Handles environment-specific settings for Sentinel MES
 */

// Prevent direct access
if (!defined('CONFIG_LOADED')) {
    define('CONFIG_LOADED', true);
}

// Detect environment
function detectEnvironment() {
    // If running from CLI, check current directory for localhost indicators
    if (php_sapi_name() === 'cli') {
        $currentDir = getcwd();
        if (strpos($currentDir, 'xampp') !== false || strpos($currentDir, 'C:') !== false) {
            return 'local';
        }
    }
    
    // Check if we're on localhost/XAMPP (local development)
    if (isset($_SERVER['SERVER_NAME']) && 
        (strpos($_SERVER['SERVER_NAME'], 'localhost') !== false || 
         strpos($_SERVER['SERVER_NAME'], '127.0.0.1') !== false ||
         strpos($_SERVER['SERVER_NAME'], '::1') !== false)) {
        return 'local';
    }
    
    // Check for production domain
    if (isset($_SERVER['SERVER_NAME']) && 
        (strpos($_SERVER['SERVER_NAME'], 'mpinternal.xyz') !== false)) {
        return 'production';
    }
    
    // Check for environment variable
    if (isset($_ENV['APP_ENV'])) {
        return $_ENV['APP_ENV'];
    }
    
    // Default to production for web requests without SERVER_NAME
    return 'production';
}

// Get current environment
$environment = detectEnvironment();

// Load environment-specific configuration
switch ($environment) {
    case 'local':
        // Local development settings (XAMPP)
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', 'injectionadmin123'); // Your current Sentinel password
        
        // Sentinel Project Databases
        define('DB_SENTINEL_MAIN', 'injectionmoldingparameters');
        define('DB_SENTINEL_MONITORING', 'dailymonitoringsheet');
        define('DB_SENTINEL_PRODUCTION', 'productionreport');
        define('DB_SENTINEL_SENSORY', 'sensory_data');
        define('DB_SENTINEL_ADMIN', 'admin_sentinel');
        
        break;
        
    case 'production':
        // Production server settings - Sentinel Digital
        define('DB_HOST', 'srv1518.hstgr.io');
        
        // Production Database Configurations - Each database has its own credentials
        define('DB_SENTINEL_MAIN', 'u158529957_spmc_injmold');
        define('DB_SENTINEL_MAIN_USER', 'u158529957_spmc_injmold');
        define('DB_SENTINEL_MAIN_PASS', '!d8K4+bW*o6A');
        
        define('DB_SENTINEL_MONITORING', 'u158529957_spmc_dmonitor');
        define('DB_SENTINEL_MONITORING_USER', 'u158529957_spmc_dmonitor');
        define('DB_SENTINEL_MONITORING_PASS', 'RSzGvru!0S[m');
        
        define('DB_SENTINEL_PRODUCTION', 'u158529957_spmc_prodreprt');
        define('DB_SENTINEL_PRODUCTION_USER', 'u158529957_spmc_prodreprt');
        define('DB_SENTINEL_PRODUCTION_PASS', 'lW1&*F6CaEjr');
        
        define('DB_SENTINEL_SENSORY', 'u158529957_spmc_sensory');
        define('DB_SENTINEL_SENSORY_USER', 'u158529957_spmc_sensory');
        define('DB_SENTINEL_SENSORY_PASS', 'e3Y0@1#U^[N');
        
        define('DB_SENTINEL_ADMIN', 'u158529957_spmc');
        define('DB_SENTINEL_ADMIN_USER', 'u158529957_spmc');
        define('DB_SENTINEL_ADMIN_PASS', '5PM(@ppD8');
        
        // Legacy defines for backward compatibility (using admin db as default)
        define('DB_USER', 'u158529957_spmc');
        define('DB_PASS', '5PM(@ppD8');
        
        break;
        
    case 'staging':
        // Staging server settings
        define('DB_HOST', 'localhost');
        define('DB_USER', 'staging_user');
        define('DB_PASS', 'staging_password');
        
        // Staging Database Names
        define('DB_SENTINEL_MAIN', 'staging_injectionmoldingparameters');
        define('DB_SENTINEL_MONITORING', 'staging_dailymonitoringsheet');
        define('DB_SENTINEL_PRODUCTION', 'staging_productionreport');
        define('DB_SENTINEL_SENSORY', 'staging_sensory_data');
        define('DB_SENTINEL_ADMIN', 'staging_admin_sentinel');
        
        break;
}

// Additional configuration
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// Error reporting based on environment
if ($environment === 'local') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Asia/Manila'); // Adjust as needed

?>
