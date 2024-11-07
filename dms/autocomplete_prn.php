<?php
// autocomplete_prn.php
include 'db_connection.php'; // Your database connection file

if (isset($_GET['term'])) {
    $term = $_GET['term'] . '%'; // Appending % for partial matching
    $stmt = $pdo->prepare("SELECT DISTINCT prn FROM production_report WHERE prn LIKE :term LIMIT 10");
    $stmt->execute(['term' => $term]);
    
    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $prns = array_map(function($row) {
        return $row['prn'];
    }, $results);

    // Send back as JSON
    echo json_encode($prns);
}
