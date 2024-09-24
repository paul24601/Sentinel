<?php
$term = isset($_GET['term']) ? $_GET['term'] : '';

// Database connection
$conn = new mysqli("localhost", "root", "", "production_data");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepared statement to fetch the most recent entry for each product_name
$sql = "
SELECT t1.product_name, t1.machine, t1.prn, t1.mold_code, t1.cycle_time_target, t1.cycle_time_actual, 
       t1.weight_standard, t1.weight_gross, t1.weight_net, t1.cavity_designed, t1.cavity_active, 
       t1.remarks, t1.name, t1.shift
FROM submissions t1
JOIN (
    SELECT product_name, MAX(id) AS latest
    FROM submissions
    WHERE product_name LIKE CONCAT('%', ?, '%')
    GROUP BY product_name
) t2 ON t1.product_name = t2.product_name AND t1.id = t2.latest
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
            'machine' => $row['machine'],
            'prn' => $row['prn'],
            'mold_code' => $row['mold_code'],
            'cycle_time_target' => (float) $row['cycle_time_target'],
            'cycle_time_actual' => (float) $row['cycle_time_actual'],
            'weight_standard' => (float) $row['weight_standard'],
            'weight_gross' => (float) $row['weight_gross'],
            'weight_net' => (float) $row['weight_net'],
            'cavity_designed' => (int) $row['cavity_designed'],
            'cavity_active' => (int) $row['cavity_active'],
            'remarks' => $row['remarks'],
            'name' => $row['name'],
            'shift' => $row['shift']
        );
    }
}

// Return the data as JSON
header('Content-Type: application/json'); // Set the content type
echo json_encode($data);

$stmt->close();
$conn->close();
?>
