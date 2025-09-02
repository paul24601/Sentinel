<?php
/**
 * Centralized Database Connection Manager - Sentinel Project Only
 * Provides unified database connections for Sentinel MES
 */

// Load configuration if not already loaded
if (!defined('CONFIG_LOADED')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Database Connection Class
 */
class DatabaseManager {
    private static $connections = [];
    
    /**
     * Get database connection for specific database
     * @param string $database Database identifier
     * @return mysqli Connection object
     * @throws Exception If connection fails
     */
    public static function getConnection($database = 'sentinel_main') {
        // Return existing connection if available
        if (isset(self::$connections[$database])) {
            // Simple check - if connection exists and is a mysqli object, return it
            if (self::$connections[$database] instanceof mysqli) {
                return self::$connections[$database];
            } else {
                // Remove invalid connection
                unset(self::$connections[$database]);
            }
        }
        
        // Create new connection
        try {
            $conn = self::createConnection($database);
            self::$connections[$database] = $conn;
            return $conn;
        } catch (Exception $e) {
            error_log("Database connection failed for {$database}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create new database connection
     * @param string $database Database identifier
     * @return mysqli Connection object
     */
    private static function createConnection($database) {
        $config = self::getDatabaseConfig($database);
        
        // Create connection
        $conn = new mysqli(
            $config['host'],
            $config['user'],
            $config['password'],
            $config['database']
        );
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Set charset
        if (!$conn->set_charset(DB_CHARSET)) {
            throw new Exception("Error setting charset: " . $conn->error);
        }
        
        return $conn;
    }
    
    /**
     * Get database configuration
     * @param string $database Database identifier
     * @return array Database configuration
     */
    private static function getDatabaseConfig($database) {
        // Check if we're in production and have individual database credentials
        $isProduction = (defined('DB_SENTINEL_MAIN_USER') && !empty(DB_SENTINEL_MAIN_USER));
        
        if ($isProduction) {
            // Production environment - each database has its own credentials
            $configs = [
                'sentinel_main' => [
                    'host' => DB_HOST,
                    'user' => DB_SENTINEL_MAIN_USER,
                    'password' => DB_SENTINEL_MAIN_PASS,
                    'database' => DB_SENTINEL_MAIN
                ],
                'sentinel_monitoring' => [
                    'host' => DB_HOST,
                    'user' => DB_SENTINEL_MONITORING_USER,
                    'password' => DB_SENTINEL_MONITORING_PASS,
                    'database' => DB_SENTINEL_MONITORING
                ],
                'sentinel_production' => [
                    'host' => DB_HOST,
                    'user' => DB_SENTINEL_PRODUCTION_USER,
                    'password' => DB_SENTINEL_PRODUCTION_PASS,
                    'database' => DB_SENTINEL_PRODUCTION
                ],
                'sentinel_sensory' => [
                    'host' => DB_HOST,
                    'user' => DB_SENTINEL_SENSORY_USER,
                    'password' => DB_SENTINEL_SENSORY_PASS,
                    'database' => DB_SENTINEL_SENSORY
                ],
                'sentinel_admin' => [
                    'host' => DB_HOST,
                    'user' => DB_SENTINEL_ADMIN_USER,
                    'password' => DB_SENTINEL_ADMIN_PASS,
                    'database' => DB_SENTINEL_ADMIN
                ]
            ];
        } else {
            // Local/staging environment - shared credentials
            $configs = [
                'sentinel_main' => [
                    'host' => DB_HOST,
                    'user' => DB_USER,
                    'password' => DB_PASS,
                    'database' => DB_SENTINEL_MAIN
                ],
                'sentinel_monitoring' => [
                    'host' => DB_HOST,
                    'user' => DB_USER,
                    'password' => DB_PASS,
                    'database' => DB_SENTINEL_MONITORING
                ],
                'sentinel_production' => [
                    'host' => DB_HOST,
                    'user' => DB_USER,
                    'password' => DB_PASS,
                    'database' => DB_SENTINEL_PRODUCTION
                ],
                'sentinel_sensory' => [
                    'host' => DB_HOST,
                    'user' => DB_USER,
                    'password' => DB_PASS,
                    'database' => DB_SENTINEL_SENSORY
                ],
                'sentinel_admin' => [
                    'host' => DB_HOST,
                    'user' => DB_USER,
                    'password' => DB_PASS,
                    'database' => DB_SENTINEL_ADMIN
                ]
            ];
        }
        
        if (!isset($configs[$database])) {
            throw new Exception("Unknown database configuration: {$database}");
        }
        
        return $configs[$database];
    }
    
    /**
     * Close all connections
     */
    public static function closeAllConnections() {
        foreach (self::$connections as $key => $conn) {
            if ($conn instanceof mysqli) {
                try {
                    @$conn->close(); // Suppress warnings for already closed connections
                } catch (Exception $e) {
                    // Ignore errors on close
                }
            }
        }
        self::$connections = [];
    }
    
    /**
     * Close specific connection
     * @param string $database Database identifier
     */
    public static function closeConnection($database) {
        if (isset(self::$connections[$database])) {
            self::$connections[$database]->close();
            unset(self::$connections[$database]);
        }
    }
}

/**
 * Legacy compatibility functions
 * These functions provide backward compatibility with existing code
 */

/**
 * Get Sentinel main database connection
 * @return mysqli
 */
function getSentinelConnection() {
    return DatabaseManager::getConnection('sentinel_main');
}

/**
 * Get Sentinel monitoring database connection  
 * @return mysqli
 */
function getSentinelMonitoringConnection() {
    return DatabaseManager::getConnection('sentinel_monitoring');
}

/**
 * Get Sentinel production database connection
 * @return mysqli
 */
function getSentinelProductionConnection() {
    return DatabaseManager::getConnection('sentinel_production');
}

/**
 * Get Sentinel admin database connection
 * @return mysqli
 */
function getSentinelAdminConnection() {
    return DatabaseManager::getConnection('sentinel_admin');
}

/**
 * Get Sentinel sensory database connection
 * @return mysqli
 */
function getSentinelSensoryConnection() {
    return DatabaseManager::getConnection('sentinel_sensory');
}

// Register shutdown function to close connections
register_shutdown_function(function() {
    DatabaseManager::closeAllConnections();
});

?>
