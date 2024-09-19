<?php
$term = $_GET['term'];

// Database connection
$conn = new mysqli("localhost", "root", "", "production_data");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the most recent entry for each product_name
$sql = "
SELECT t1.product_name, t1.machine, t1.prn, t1.mold_code, t1.cycle_time_target, t1.cycle_time_actual, 
       t1.weight_standard, t1.weight_gross, t1.weight_net, t1.cavity_designed, t1.cavity_active, 
       t1.remarks, t1.name, t1.shift, t1.search
FROM submissions t1
JOIN (
    SELECT product_name, MAX(id) AS latest
    FROM submissions
    WHERE product_name LIKE '%$term%'
    GROUP BY product_name
) t2 ON t1.product_name = t2.product_name AND t1.id = t2.latest
LIMIT 10;

";

$result = $conn->query($sql);

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
            'cycle_time_target' => (float) $row['cycle_time_target'], // Cast to float
            'cycle_time_actual' => (float) $row['cycle_time_actual'], // Cast to float
            'weight_standard' => (float) $row['weight_standard'], // Cast to float
            'weight_gross' => (float) $row['weight_gross'], // Cast to float
            'weight_net' => (float) $row['weight_net'], // Cast to float
            'cavity_designed' => (int) $row['cavity_designed'], // Cast to integer
            'cavity_active' => (int) $row['cavity_active'], // Cast to integer
            'remarks' => $row['remarks'],
            'name' => $row['name'],
            'shift' => $row['shift'],
            'search' => $row['search']
        );
        
    }
}

// Return the data as JSON
echo json_encode($data);

$conn->close();
?>
