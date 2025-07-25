<?php
// Simple test version of forgot password process
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Test started...<br>";

// Database connection test
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";

echo "Testing admin_sentinel database connection...<br>";
$conn = new mysqli($servername, $username, $password, "admin_sentinel");
if ($conn->connect_error) {
    echo "Admin database connection failed: " . $conn->connect_error . "<br>";
    exit();
} else {
    echo "Admin database connected successfully!<br>";
}

echo "Testing dailymonitoringsheet database connection...<br>";
$user_conn = new mysqli($servername, $username, $password, "dailymonitoringsheet");
if ($user_conn->connect_error) {
    echo "User database connection failed: " . $user_conn->connect_error . "<br>";
    exit();
} else {
    echo "User database connected successfully!<br>";
}

// Test table existence
echo "Testing password_reset_requests table...<br>";
$result = $conn->query("DESCRIBE password_reset_requests");
if ($result) {
    echo "password_reset_requests table exists!<br>";
    echo "Columns: ";
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . " ";
    }
    echo "<br>";
} else {
    echo "password_reset_requests table does not exist or error: " . $conn->error . "<br>";
}

echo "Testing users table in dailymonitoringsheet...<br>";
$result = $user_conn->query("DESCRIBE users");
if ($result) {
    echo "users table exists in dailymonitoringsheet!<br>";
    echo "Columns: ";
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . " ";
    }
    echo "<br>";
} else {
    echo "users table does not exist in dailymonitoringsheet or error: " . $user_conn->error . "<br>";
}

// Test POST data if available
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST data received:<br>";
    echo "ID Number: " . ($_POST['id_number'] ?? 'not set') . "<br>";
    echo "Full Name: " . ($_POST['full_name'] ?? 'not set') . "<br>";
    echo "Reason: " . ($_POST['reason'] ?? 'not set') . "<br>";
} else {
    echo "No POST data received. This is a GET request.<br>";
}

$conn->close();
$user_conn->close();
echo "Test completed successfully!";
?>
