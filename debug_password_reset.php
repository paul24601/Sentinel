<?php
// Check the database structure for password reset functionality
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";

echo "<h2>Database Structure Check</h2>";

// Check admin_sentinel database
echo "<h3>admin_sentinel database:</h3>";
$conn = new mysqli($servername, $username, $password, "admin_sentinel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if tables exist
$tables = ['users', 'password_reset_requests'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p>✅ Table '$table' exists</p>";
        
        // Show structure
        $structure = $conn->query("DESCRIBE $table");
        echo "<table border='1' style='margin-bottom: 20px;'>";
        echo "<tr><th colspan='6'>Structure of $table</th></tr>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $structure->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . ($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Table '$table' does not exist</p>";
    }
}

// Check sample data
echo "<h3>Sample password reset requests:</h3>";
$result = $conn->query("SELECT * FROM password_reset_requests ORDER BY created_at DESC LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "<table border='1'>";
    $first = true;
    while ($row = $result->fetch_assoc()) {
        if ($first) {
            echo "<tr>";
            foreach (array_keys($row) as $key) {
                echo "<th>$key</th>";
            }
            echo "</tr>";
            $first = false;
        }
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . ($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No password reset requests found</p>";
}

$conn->close();

// Check dailymonitoringsheet database
echo "<h3>dailymonitoringsheet database (for user validation):</h3>";
$user_conn = new mysqli($servername, $username, $password, "dailymonitoringsheet");
if ($user_conn->connect_error) {
    die("Connection failed: " . $user_conn->connect_error);
}

$result = $user_conn->query("SELECT COUNT(DISTINCT name) as user_count FROM submissions");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p>✅ Found " . $row['user_count'] . " unique users in submissions table</p>";
}

$user_conn->close();
?>
