<?php
$term = isset($_GET['term']) ? $_GET['term'] : '';

// Database connection
$conn = new mysqli("localhost", "root", "", "production_data");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepared statement to fetch the first recorded entry for each product_name
$sql = "
SELECT t1.product_name, t1.mold_code, t1.cycle_time_target, t1.weight_standard, t1.cavity_designed
FROM submissions t1
JOIN (
    SELECT product_name, MIN(id) AS first_entry
    FROM submissions
    WHERE product_name LIKE CONCAT('%', ?, '%')
    GROUP BY product_name
) t2 ON t1.product_name = t2.product_name AND t1.id = t2.first_entry
LIMIT 10;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $term);
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to store the data
$data = array();

if ($result->num_rows > 0) {
    // Fetch data and store it in the $data array
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'label' => $row['product_name'], 
            'value' => $row['product_name'],
            'mold_code' => $row['mold_code'],
            'cycle_time_target' => (float) $row['cycle_time_target'],
            'weight_standard' => (float) $row['weight_standard'],
            'cavity_designed' => (int) $row['cavity_designed']
        );
    }
}

// Return the data as JSON
header('Content-Type: application/json'); // Set the content type
echo json_encode($data);

$stmt->close();
$conn->close();
?>
