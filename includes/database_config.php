<?php
// Database configuration for Sentinel MES
// This file centralizes database connection settings

// Main database credentials
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";

// Database names used by different modules
$db_injectionmolding = "injectionmoldingparameters";  // Main parameters database
$db_dailymonitoring = "dailymonitoringsheet";         // DMS and user management
$db_productionreport = "productionreport";            // Production reports
$db_sensorydata = "sensory_data";                     // Sensor/IoT data

// Create connection function for different databases
function createConnection($database = 'injectionmoldingparameters') {
    global $servername, $username, $password;
    
    try {
        $conn = new mysqli($servername, $username, $password, $database);
        $conn->set_charset("utf8mb4");
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        return $conn;
    } catch (Exception $e) {
        error_log("Database connection failed: " . $e->getMessage());
        
        // Return JSON error for AJAX requests
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Database connection failed. Please contact system administrator.'
            ]);
            exit;
        }
        
        die("Connection failed. Please check configuration.");
    }
}

// Legacy compatibility - create default connection
try {
    $conn = createConnection($db_injectionmolding);
} catch (Exception $e) {
    // Handle error appropriately
}
?>
