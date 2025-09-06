<?php
require_once 'includes/database.php';

echo "<h2>Database Tables Check</h2>";

try {
    $conn = DatabaseManager::getConnection('sentinel_main');
    $result = $conn->query('SHOW TABLES');
    
    echo "<h3>Tables in sentinel_main database:</h3>";
    echo "<ul>";
    $tables = [];
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
    
    // Check for specific tables that might be missing
    $requiredTables = [
        'parameter_records',
        'attachments', 
        'moldparameters',
        'moldoperationspecs',
        'moldcloseparameters',
        'moldopenparameters',
        'moldheatertemperatures'
    ];
    
    echo "<h3>Required vs Existing Tables:</h3>";
    foreach ($requiredTables as $table) {
        if (in_array($table, $tables)) {
            echo "✅ <strong>$table</strong> - EXISTS<br>";
        } else {
            echo "❌ <strong>$table</strong> - MISSING<br>";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
