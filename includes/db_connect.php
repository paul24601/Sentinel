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
$db_host = DB_HOST;
$db_user = DB_USER;
$db_pass = DB_PASS_SENTINEL;
$db_name = DB_SENTINEL_PRODUCTION;

// Use the new connection system
try {
    $conn = DatabaseManager::getConnection('sentinel_production');
} catch (Exception $e) {
    // Log the error (you might want to use a proper logging system)
    error_log("Database connection error: " . $e->getMessage());
    
    // If this is accessed via AJAX, return JSON error
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed. Please contact system administrator.'
        ]);
        exit;
    }
    
    // Otherwise show a generic error
    die("A system error has occurred. Please try again later.");
}
?> 