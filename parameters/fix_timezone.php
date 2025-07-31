<?php
// Database configuration for timezone fix
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

// Include database configuration
require_once '../includes/database_config.php';

try {
    $conn = new mysqli("localhost", "root", "injectionadmin123", "injectionmoldingparameters");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set MySQL timezone to match PHP timezone
    $conn->query("SET time_zone = '+08:00'");
    
    echo "<h2>Database Timezone Fix</h2>";
    echo "<p>Setting MySQL timezone to Philippine Time (UTC+8)...</p>";
    
    // Check current MySQL timezone
    $result = $conn->query("SELECT @@global.time_zone, @@session.time_zone, NOW() as current_time");
    $row = $result->fetch_assoc();
    echo "<p><strong>MySQL Timezone Info:</strong></p>";
    echo "Global timezone: " . $row['@@global.time_zone'] . "<br>";
    echo "Session timezone: " . $row['@@session.time_zone'] . "<br>";
    echo "Current MySQL time: " . $row['current_time'] . "<br><br>";
    
    // Test Philippine time generation
    $currentPhTime = date('H:i:s');
    echo "<p><strong>PHP Philippine Time:</strong> " . $currentPhTime . "</p>";
    
    // Test TIME column behavior
    echo "<h3>Testing TIME Column Storage</h3>";
    
    // Create a test table to understand TIME behavior
    $conn->query("DROP TABLE IF EXISTS time_test");
    $conn->query("CREATE TABLE time_test (
        id INT AUTO_INCREMENT PRIMARY KEY,
        test_time TIME,
        test_datetime DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Insert current Philippine time
    $stmt = $conn->prepare("INSERT INTO time_test (test_time, test_datetime) VALUES (?, ?)");
    $currentDateTime = date('Y-m-d H:i:s');
    $stmt->bind_param("ss", $currentPhTime, $currentDateTime);
    $stmt->execute();
    
    // Retrieve and display
    $result = $conn->query("SELECT * FROM time_test ORDER BY id DESC LIMIT 1");
    $row = $result->fetch_assoc();
    
    echo "<p><strong>Stored Values:</strong></p>";
    echo "TIME column: " . $row['test_time'] . "<br>";
    echo "DATETIME column: " . $row['test_datetime'] . "<br>";
    echo "TIMESTAMP column: " . $row['created_at'] . "<br>";
    
    // Clean up test table
    $conn->query("DROP TABLE time_test");
    
    echo "<h3>Recommendation</h3>";
    echo "<p>TIME columns work correctly when:</p>";
    echo "<ul>";
    echo "<li>PHP timezone is set to 'Asia/Manila'</li>";
    echo "<li>MySQL session timezone is set to '+08:00'</li>";
    echo "<li>Time values are formatted as 'HH:MM:SS'</li>";
    echo "</ul>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
