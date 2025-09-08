<?php
header('Content-Type: application/json');

// Load centralized database configuration
require_once __DIR__ . '/includes/database.php';

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Query to select columns that exist in the database
$sql = "SELECT 
            id,
            product_name,
            date,
            name
        FROM submissions
        ORDER BY date DESC
        LIMIT 10";

$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query error: ' . $conn->error]);
    exit();
}

$notifications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Since approval columns no longer exist, assume all are pending
        $row['pending_supervisor'] = true;
        $row['pending_qa'] = true;

        $notifications[] = $row;
    }
}

echo json_encode($notifications);
// Connection will be closed automatically by DatabaseManager
?>
