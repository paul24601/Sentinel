<?php
// Test forgot password functionality
echo "<h2>Testing Forgot Password Process</h2>";

// Database connection
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";

// Test admin_sentinel connection
echo "<h3>Testing Database Connections:</h3>";
$admin_conn = new mysqli($servername, $username, $password, "admin_sentinel");
if ($admin_conn->connect_error) {
    echo "❌ admin_sentinel connection failed: " . $admin_conn->connect_error . "<br>";
} else {
    echo "✅ admin_sentinel connection successful<br>";
}

$user_conn = new mysqli($servername, $username, $password, "dailymonitoringsheet");
if ($user_conn->connect_error) {
    echo "❌ dailymonitoringsheet connection failed: " . $user_conn->connect_error . "<br>";
} else {
    echo "✅ dailymonitoringsheet connection successful<br>";
}

// Test table structures
echo "<h3>Testing Table Structures:</h3>";

// Check password_reset_requests table
$result = $admin_conn->query("SHOW CREATE TABLE password_reset_requests");
if ($result) {
    echo "✅ password_reset_requests table exists<br>";
} else {
    echo "❌ password_reset_requests table missing. Creating it...<br>";
    
    $create_table_sql = "
    CREATE TABLE IF NOT EXISTS password_reset_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_number VARCHAR(10) NOT NULL,
        full_name VARCHAR(255) NOT NULL,
        request_reason TEXT NOT NULL,
        reset_token VARCHAR(255) NOT NULL,
        status ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        admin_comment TEXT NULL,
        processed_by VARCHAR(255) NULL,
        processed_at TIMESTAMP NULL,
        FOREIGN KEY (id_number) REFERENCES users(id_number) ON DELETE CASCADE
    )";
    
    if ($admin_conn->query($create_table_sql)) {
        echo "✅ password_reset_requests table created successfully<br>";
    } else {
        echo "❌ Failed to create password_reset_requests table: " . $admin_conn->error . "<br>";
    }
}

// Check users table
$result = $admin_conn->query("SHOW CREATE TABLE users");
if ($result) {
    echo "✅ users table exists<br>";
} else {
    echo "❌ users table missing. Creating it...<br>";
    
    $create_users_sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT,
        id_number VARCHAR(10) PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($admin_conn->query($create_users_sql)) {
        echo "✅ users table created successfully<br>";
    } else {
        echo "❌ Failed to create users table: " . $admin_conn->error . "<br>";
    }
}

// Test user lookup in submissions table
echo "<h3>Testing User Validation:</h3>";
$result = $user_conn->query("SELECT DISTINCT name FROM submissions LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "✅ Found users in submissions table:<br>";
    while ($row = $result->fetch_assoc()) {
        echo "- " . htmlspecialchars($row['name']) . "<br>";
    }
} else {
    echo "❌ No users found in submissions table<br>";
}

// Test PHPMailer files
echo "<h3>Testing Email Components:</h3>";
$phpmailer_files = [
    'PHPMailer/src/Exception.php',
    'PHPMailer/src/PHPMailer.php',
    'PHPMailer/src/SMTP.php'
];

foreach ($phpmailer_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}

$admin_conn->close();
$user_conn->close();
?>
