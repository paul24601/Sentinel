<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Load the centralized configuration
require_once __DIR__ . '/../includes/database.php';

// Get database connection using the centralized system
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get the search term from the request
$term = isset($_GET['term']) ? $_GET['term'] : '';

// Prepare the SQL query to search for products and their parameters
$sql = "SELECT DISTINCT p.product_name, p.mold_code, p.cycle_time_target, p.weight_standard, p.cavity_designed 
        FROM product_parameters p 
        WHERE p.product_name LIKE ? 
        ORDER BY p.product_name";

$stmt = $conn->prepare($sql);
$searchTerm = "%" . $term . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$products = array();
while ($row = $result->fetch_assoc()) {
    $products[] = array(
        'label' => $row['product_name'],
        'value' => $row['product_name'],
        'mold_code' => $row['mold_code'],
        'cycle_time_target' => $row['cycle_time_target'],
        'weight_standard' => $row['weight_standard'],
        'cavity_designed' => $row['cavity_designed']
    );
}

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
?>
