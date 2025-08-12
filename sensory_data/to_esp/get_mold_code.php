<?php
header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "sensory_data";

// Check for 'code' parameter
if (!isset($_GET['code']) || empty($_GET['code'])) {
    echo json_encode(["found" => false, "error" => "Missing code parameter"]);
    exit;
}

$code = $_GET['code'];

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["found" => false, "error" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT mold_name, thickness FROM mold_thickness WHERE mold_number = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "found" => true,
        "mold_name" => $row['mold_name'],
        "thickness" => $row['thickness']
    ]);
} else {
    echo json_encode(["found" => false]);
}

$stmt->close();
$conn->close();
?>
