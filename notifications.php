<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username   = "root";
$password   = "Admin123@plvil";
$dbname     = "dailymonitoringsheet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Query to select columns including supervisor_status and qa_status
$sql = "SELECT 
            id,
            product_name,
            date,
            name,
            supervisor_status,
            qa_status
        FROM submissions
        WHERE approval_status = 'pending'
        ORDER BY date DESC";

$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query error: ' . $conn->error]);
    exit();
}

$notifications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert 'supervisor_status' and 'qa_status' to booleans
        // Here we consider them "pending" if the string is exactly "pending"
        $row['pending_supervisor'] = ($row['supervisor_status'] === 'pending');
        $row['pending_qa']        = ($row['qa_status'] === 'pending');

        // (Optional) If you don't need the original columns in the JSON, remove them:
        unset($row['supervisor_status'], $row['qa_status']);

        $notifications[] = $row;
    }
}

echo json_encode($notifications);
$conn->close();
?>
