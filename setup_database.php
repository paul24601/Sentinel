<?php
// Database setup script for Sentinel MES local testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🏭 Sentinel MES Database Setup</h1>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.status-ok { color: green; font-weight: bold; }
.status-error { color: red; font-weight: bold; }
.status-warning { color: orange; font-weight: bold; }
table { border-collapse: collapse; width: 100%; margin: 20px 0; }
th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
th { background-color: #f2f2f2; font-weight: bold; }
.progress { background: #f0f0f0; border-radius: 4px; padding: 10px; margin: 10px 0; }
</style>";

echo "<div class='container'>";

// Load centralized database configuration
require_once __DIR__ . '/includes/database.php';

// List of databases needed for Sentinel MES
$databases = [
    'injectionmoldingparameters' => [
        'description' => 'Main Parameters Database',
        'sql_file' => 'injectionmoldingparameters.sql'
    ],
    'dailymonitoringsheet' => [
        'description' => 'Daily Monitoring & User Management', 
        'sql_file' => 'submissions.sql'
    ],
    'productionreport' => [
        'description' => 'Production Reports',
        'sql_file' => 'production_report/productionreport.sql'
    ],
    'sensory_data' => [
        'description' => 'Sensor Data & IoT',
        'sql_file' => 'sensory_data/database copy/sensory_data.sql'
    ]
];

echo "<h2>📊 Setting Up Databases</h2>";

foreach ($databases as $dbname => $info) {
    echo "<div class='progress'>";
    echo "<h3>🔧 Setting up: <strong>$dbname</strong></h3>";
    echo "<p><em>{$info['description']}</em></p>";
    
    try {
        // Connect without database to create it
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='status-ok'>✅ Database '$dbname' created successfully</p>";
        } else {
            echo "<p class='status-error'>❌ Error creating database '$dbname': " . $conn->error . "</p>";
        }
        
        $conn->close();
        
        // Import SQL file if it exists
        $sqlFilePath = __DIR__ . '/' . $info['sql_file'];
        if (file_exists($sqlFilePath)) {
            echo "<p>📁 Found SQL file: {$info['sql_file']}</p>";
            echo "<p>⏳ Importing data...</p>";
            
            // Connect to the specific database
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, $dbname);
            
            if ($conn->connect_error) {
                throw new Exception("Connection to $dbname failed: " . $conn->connect_error);
            }
            
            $conn->set_charset("utf8mb4");
            
            // Read and execute SQL file
            $sqlContent = file_get_contents($sqlFilePath);
            
            // Use multi_query for better SQL file handling
            if ($conn->multi_query($sqlContent)) {
                $successCount = 0;
                do {
                    if ($result = $conn->store_result()) {
                        $result->free();
                    }
                    $successCount++;
                } while ($conn->next_result());
                
                echo "<p class='status-ok'>✅ SQL file imported successfully ($successCount operations)</p>";
            } else {
                echo "<p class='status-warning'>⚠️ Some queries may have failed. Error: " . $conn->error . "</p>";
            }
            
            $conn->close();
        } else {
            echo "<p class='status-warning'>⚠️ SQL file not found: {$info['sql_file']}</p>";
            echo "<p>📝 Database created but no initial data imported</p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='status-error'>❌ Error: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
}

echo "<h2>🎉 Setup Complete!</h2>";
echo "<p><strong>Your Sentinel MES databases are now ready!</strong></p>";

echo "<h3>🚀 Next Steps:</h3>";
echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
echo "<ol>";
echo "<li><a href='system_status.php' style='color: #0066cc;'>Check System Status</a></li>";
echo "<li><a href='login.html' style='color: #0066cc;'>Go to Login Page</a></li>";
echo "<li><a href='parameters/' style='color: #0066cc;'>Access Parameters Module</a></li>";
echo "<li><a href='dms/' style='color: #0066cc;'>Access Document Management</a></li>";
echo "</ol>";
echo "</div>";

echo "</div>";
?>
