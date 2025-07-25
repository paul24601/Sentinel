<?php
// Check tables in dailymonitoringsheet database
$conn = new mysqli('localhost', 'root', 'injectionadmin123', 'dailymonitoringsheet');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Tables in dailymonitoringsheet database:\n";
$result = $conn->query('SHOW TABLES');
while ($row = $result->fetch_array()) {
    echo $row[0] . "\n";
}

// Check if users table exists and its structure
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows > 0) {
    echo "\nUsers table structure:\n";
    $result = $conn->query('DESCRIBE users');
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . ' - ' . $row['Type'] . "\n";
    }
    
    echo "\nSample users data:\n";
    $result = $conn->query('SELECT * FROM users LIMIT 5');
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "\nNo 'users' table found in dailymonitoringsheet database.\n";
    
    // Check submissions table for user data
    echo "\nChecking submissions table for user names:\n";
    $result = $conn->query('SELECT DISTINCT name FROM submissions LIMIT 10');
    while ($row = $result->fetch_assoc()) {
        echo "Name: " . $row['name'] . "\n";
    }
}

$conn->close();
?>
