<?php
// System status check for Sentinel MES
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🏭 Sentinel MES System Status</h1>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.status-ok { color: green; font-weight: bold; }
.status-error { color: red; font-weight: bold; }
.status-warning { color: orange; font-weight: bold; }
table { border-collapse: collapse; width: 100%; margin: 20px 0; }
th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
th { background-color: #f2f2f2; font-weight: bold; }
.module-box { background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007cba; }
.action-buttons { margin: 20px 0; }
.btn { background: #007cba; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px; display: inline-block; }
.btn:hover { background: #005a8b; }
.btn-success { background: #28a745; }
.btn-warning { background: #ffc107; color: #212529; }
.btn-danger { background: #dc3545; }
</style>";

echo "<div class='container'>";

// Load centralized database configuration
require_once __DIR__ . '/includes/database.php';

// Test databases
$databases = [
    'injectionmoldingparameters' => 'Main Parameters Database',
    'dailymonitoringsheet' => 'Daily Monitoring & User Management',
    'productionreport' => 'Production Reports',
    'sensory_data' => 'Sensor Data & IoT'
];

echo "<h2>📊 Database Status</h2>";
echo "<table>";
echo "<tr><th>Database</th><th>Description</th><th>Status</th><th>Tables</th><th>Sample Data</th></tr>";

$allDatabasesOk = true;

foreach ($databases as $dbname => $description) {
    echo "<tr>";
    echo "<td><strong>$dbname</strong></td>";
    echo "<td>$description</td>";
    
    try {
        // Map database names to our new system
        $dbMap = [
            'injectionmoldingparameters' => 'sentinel_main',
            'dailymonitoringsheet' => 'sentinel_monitoring',
            'productionreport' => 'sentinel_production',
            'sensory_data' => 'sentinel_sensory'
        ];
        
        $dbKey = $dbMap[$dbname] ?? 'sentinel_main';
        $conn = DatabaseManager::getConnection($dbKey);
        
        if (!$conn) {
            echo "<td class='status-error'>❌ Not Connected</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            $allDatabasesOk = false;
        } else {
            echo "<td class='status-ok'>✅ Connected</td>";
            
            // Get table count
            $result = $conn->query("SHOW TABLES");
            $tableCount = $result ? $result->num_rows : 0;
            echo "<td>$tableCount tables</td>";
            
            // Check for sample data
            if ($tableCount > 0) {
                $tables = [];
                while ($row = $result->fetch_array()) {
                    $tables[] = $row[0];
                }
                
                $sampleData = "No data";
                if (!empty($tables)) {
                    $firstTable = $tables[0];
                    $dataResult = $conn->query("SELECT COUNT(*) as count FROM `$firstTable`");
                    if ($dataResult) {
                        $dataRow = $dataResult->fetch_assoc();
                        $sampleData = $dataRow['count'] . " records in $firstTable";
                    }
                }
                echo "<td>$sampleData</td>";
            } else {
                echo "<td class='status-warning'>No tables</td>";
            }
            
            $conn->close();
        }
    } catch (Exception $e) {
        echo "<td class='status-error'>❌ Error: " . $e->getMessage() . "</td>";
        echo "<td>-</td>";
        echo "<td>-</td>";
        $allDatabasesOk = false;
    }
    
    echo "</tr>";
}

echo "</table>";

// Check key modules
echo "<h2>🧩 Module Status</h2>";
$modules = [
    'parameters/' => ['Production Parameters Module', 'Main production data entry'],
    'dms/' => ['Document Management System', 'Document workflow and approval'],
    'admin/' => ['Administration Module', 'User and system management'],
    'production_report/' => ['Production Reports', 'Production analytics and reporting'],
    'sensory_data/' => ['Sensor Data Module', 'IoT sensor data collection']
];

foreach ($modules as $path => $info) {
    echo "<div class='module-box'>";
    echo "<h4>📁 {$info[0]}</h4>";
    echo "<p><em>{$info[1]}</em></p>";
    
    $fullPath = __DIR__ . '/' . $path;
    if (is_dir($fullPath)) {
        $indexFile = $fullPath . 'index.php';
        if (file_exists($indexFile)) {
            echo "<p class='status-ok'>✅ Module Ready - <a href='$path' target='_blank'>Open Module</a></p>";
        } else {
            echo "<p class='status-warning'>⚠️ Directory exists, but no index.php found</p>";
        }
    } else {
        echo "<p class='status-error'>❌ Module directory not found</p>";
    }
    echo "</div>";
}

// Test user authentication
echo "<h2>🔐 User Authentication Status</h2>";
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    
    if (!$conn) {
        echo "<p class='status-error'>❌ Cannot connect to user database</p>";
    } else {
        $result = $conn->query("SHOW TABLES LIKE 'users'");
        if ($result && $result->num_rows > 0) {
            $userResult = $conn->query("SELECT COUNT(*) as user_count FROM users");
            if ($userResult) {
                $row = $userResult->fetch_assoc();
                echo "<p class='status-ok'>✅ Users table found - " . $row['user_count'] . " users registered</p>";
                
                // Check for sample user
                $sampleUser = $conn->query("SELECT id_number, full_name FROM users LIMIT 1");
                if ($sampleUser && $sampleUser->num_rows > 0) {
                    $user = $sampleUser->fetch_assoc();
                    echo "<p>👤 Sample user: " . htmlspecialchars($user['full_name']) . " (ID: " . htmlspecialchars($user['id_number']) . ")</p>";
                }
            }
        } else {
            echo "<p class='status-warning'>⚠️ Users table not found - authentication may not work</p>";
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "<p class='status-error'>❌ Authentication test failed: " . $e->getMessage() . "</p>";
}

echo "<h2>🚀 Quick Actions</h2>";
echo "<div class='action-buttons'>";

if (!$allDatabasesOk) {
    echo "<a href='setup_database.php' class='btn btn-warning'>🔧 Setup/Repair Databases</a>";
}

echo "<a href='login.html' class='btn btn-success'>🔐 Login to System</a>";
echo "<a href='index.php' class='btn'>🏠 Main Dashboard</a>";
echo "<a href='parameters/' class='btn'>⚙️ Parameters Module</a>";
echo "<a href='dms/' class='btn'>📋 Document Management</a>";
echo "</div>";

echo "<h2>📋 System Information</h2>";
echo "<table>";
echo "<tr><th>Component</th><th>Value</th></tr>";
echo "<tr><td><strong>PHP Version</strong></td><td>" . PHP_VERSION . "</td></tr>";
echo "<tr><td><strong>Server Software</strong></td><td>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td><strong>Document Root</strong></td><td>" . $_SERVER['DOCUMENT_ROOT'] . "</td></tr>";
echo "<tr><td><strong>Current Time</strong></td><td>" . date('Y-m-d H:i:s T') . "</td></tr>";
echo "<tr><td><strong>System Path</strong></td><td>" . __DIR__ . "</td></tr>";
echo "</table>";

if ($allDatabasesOk) {
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>🎉 System Status: READY</h3>";
    echo "<p>All databases are connected and your Sentinel MES system is ready for use!</p>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>⚠️ System Status: NEEDS SETUP</h3>";
    echo "<p>Some databases need to be set up. Please run the database setup first.</p>";
    echo "</div>";
}

echo "</div>";
?>
