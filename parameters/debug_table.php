<?php
// Check the structure of the corepullsettings table
$conn = new mysqli("localhost", "root", "injectionadmin123", "injectionmoldingparameters");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

echo "<h2>Table Structure: corepullsettings</h2>";
$result = $conn->query("DESCRIBE corepullsettings");
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}

echo "<h2>Sample Data from corepullsettings</h2>";
$result = $conn->query("SELECT * FROM corepullsettings LIMIT 5");
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
    echo "No data found or error: " . $conn->error;
}

$conn->close();
?>
