<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';  // Default XAMPP MySQL username
$db_pass = '';      // Default XAMPP MySQL password
$db_name = 'productionreport';

// Create connection with error handling
try {
    // First connect without database to create it if it doesn't exist
    $temp_conn = new mysqli($db_host, $db_user, $db_pass);
    if ($temp_conn->connect_error) {
        throw new Exception("Connection failed: " . $temp_conn->connect_error);
    }

    // Create database if it doesn't exist
    $temp_conn->query("CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $temp_conn->close();

    // Now connect with the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set charset to utf8mb4
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Error setting charset: " . $conn->error);
    }

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