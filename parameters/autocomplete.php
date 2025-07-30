<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "injectionmoldingparameters";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the request
$term = isset($_GET['term']) ? $_GET['term'] : '';

// Prepare the SQL query to search for distinct product names from the productdetails table
$sql = "SELECT DISTINCT ProductName 
        FROM productdetails 
        WHERE ProductName LIKE ? 
        ORDER BY ProductName";

$stmt = $conn->prepare($sql);
$searchTerm = "%" . $term . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$products = array();
while ($row = $result->fetch_assoc()) {
    $products[] = array(
        'label' => $row['ProductName'],
        'value' => $row['ProductName']
    );
}

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
?>
