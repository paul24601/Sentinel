<?php
// Database setup script for password reset functionality

// Load centralized database configuration
require_once __DIR__ . '/includes/database.php';

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// SQL to create password reset requests table
$sql = "CREATE TABLE IF NOT EXISTS password_reset_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_number VARCHAR(6) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    request_reason TEXT,
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
    admin_id VARCHAR(6),
    admin_response_date DATETIME,
    admin_comment TEXT,
    new_password_hash VARCHAR(255),
    reset_token VARCHAR(64),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_number) REFERENCES users(id_number),
    FOREIGN KEY (admin_id) REFERENCES users(id_number),
    INDEX idx_status (status),
    INDEX idx_request_date (request_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql) === TRUE) {
    echo "Password reset requests table created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$conn->close();
?>
