<?php
require_once 'includes/database.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_main');
    
    echo "=== Mold Close Parameters Table Structure ===\n";
    $result = $conn->query("DESCRIBE moldcloseparameters");
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    
    echo "\n=== Mold Open Parameters Table Structure ===\n";
    $result = $conn->query("DESCRIBE moldopenparameters");
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    
    echo "\n=== Sample Mold Close Data ===\n";
    $result = $conn->query("SELECT * FROM moldcloseparameters LIMIT 1");
    if ($row = $result->fetch_assoc()) {
        foreach ($row as $field => $value) {
            echo "$field: $value\n";
        }
    }
    
    echo "\n=== Sample Mold Open Data ===\n";
    $result = $conn->query("SELECT * FROM moldopenparameters LIMIT 1");
    if ($row = $result->fetch_assoc()) {
        foreach ($row as $field => $value) {
            echo "$field: $value\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
