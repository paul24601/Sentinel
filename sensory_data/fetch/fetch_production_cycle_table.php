<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "sensory_data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Get parameters
$show = isset($_GET['show']) ? intval($_GET['show']) : 10;
$month = isset($_GET['month']) ? intval($_GET['month']) : 0;
$machine = isset($_GET['machine']) ? $_GET['machine'] : 'CLF 750A';
$product = isset($_GET['product']) ? $_GET['product'] : '';

// Sanitize and convert machine name to table name
$machine_safe = preg_replace('/[^a-zA-Z0-9_]/', '_', $machine);
$tableName = "production_cycle_" . $machine_safe;

// Validate table existence
$checkTable = $conn->query("SHOW TABLES LIKE '$tableName'");
if ($checkTable->num_rows == 0) {
    echo "<tr><td colspan='8'>Table for selected machine does not exist.</td></tr>";
    exit;
}

// Build query
$sql = "SELECT id, cycle_time, processing_time, recycle_time, tempC_01, tempC_02, product, mold_number, timestamp FROM `$tableName`";
$where = [];
$params = [];
$types = "";

// Add filters
if ($month > 0) {
    $where[] = "MONTH(timestamp) = ?";
    $params[] = $month;
    $types .= "i";
}
if (!empty($product)) {
    $where[] = "product = ?";
    $params[] = $product;
    $types .= "s";
}
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Finalize query
$sql .= " ORDER BY timestamp DESC LIMIT ?";
$params[] = $show + 1;
$types .= "i";

// Prepare and execute
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "<tr><td colspan='8'>Error: " . $conn->error . "</td></tr>";
    exit;
}
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Display results
if ($result->num_rows > 0) {
    $rowsShown = 0;

    while ($row = $result->fetch_assoc()) {
        if (floatval($row['processing_time']) == 0 && floatval($row['recycle_time']) == 0) {
            continue;
        }


        echo "<tr>";
        echo "<td>".htmlspecialchars($row['id'])."</td>";
        echo "<td>".htmlspecialchars($row['cycle_time'])."</td>";
        echo "<td>".htmlspecialchars($row['processing_time'])."</td>";

        if (floatval($row['recycle_time']) != 0) {
            echo "<td>".htmlspecialchars($row['recycle_time'])."</td>";
        }
        else {
            echo "<td style=\"color: #417630;\">Start of production</td>";

        }

        echo "<td>".htmlspecialchars($row['tempC_01'])."</td>";
        echo "<td>".htmlspecialchars($row['tempC_02'])."</td>";
        echo "<td>".htmlspecialchars(explode('|', $row['product'])[0]) ."</td>";
        echo "<td>".htmlspecialchars($row['mold_number']) ."</td>";
        echo "<td>".htmlspecialchars($row['timestamp'])."</td>";
        echo "</tr>";

        $rowsShown++;
    }

    if ($rowsShown === 0) {
        echo "<tr><td colspan='9'>No valid records found.</td></tr>";
    }
} else {
    echo "<tr><td colspan='9'>No records found.</td></tr>";
}

$stmt->close();
$conn->close();
?>
