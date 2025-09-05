<?php
/**
 * API endpoint to fetch product names for autocomplete
 */

header('Content-Type: application/json');

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';

try {
    // Get database connection
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    
    // Get search term from query parameter
    $search = $_GET['search'] ?? '';
    
    // Query to get product names from injectionmoldingparameters table
    if (empty($search)) {
        // Return all products if no search term
        $sql = "SELECT DISTINCT product_name 
                FROM injectionmoldingparameters 
                ORDER BY product_name 
                LIMIT 50";
        $stmt = $conn->prepare($sql);
    } else {
        // Return filtered products if search term provided
        $sql = "SELECT DISTINCT product_name 
                FROM injectionmoldingparameters 
                WHERE product_name LIKE ? 
                ORDER BY product_name 
                LIMIT 20";
        $stmt = $conn->prepare($sql);
        $searchTerm = '%' . $search . '%';
        $stmt->bind_param('s', $searchTerm);
    }
    $stmt->execute();
    
    $result = $stmt->get_result();
    $products = [];
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row['product_name'];
    }
    
    echo json_encode($products);
    
} catch (Exception $e) {
    error_log("Error fetching product names: " . $e->getMessage());
    echo json_encode([]);
}
?>
