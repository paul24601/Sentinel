<?php
// Database debug tool to check users and their roles
require_once 'includes/database.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    
    echo "<h2>User Database Debug</h2>\n";
    
    // Check if users table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Users table exists</p>\n";
        
        // Get all users and their roles
        $sql = "SELECT id_number, full_name, role, password_changed FROM users ORDER BY role, full_name";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            echo "<h3>All Users</h3>\n";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
            echo "<tr><th>ID Number</th><th>Full Name</th><th>Role</th><th>Password Changed</th></tr>\n";
            
            while ($row = $result->fetch_assoc()) {
                $rowStyle = '';
                if ($row['role'] === 'admin') {
                    $rowStyle = 'background-color: #e7f3ff;';
                }
                echo "<tr style='{$rowStyle}'>";
                echo "<td>" . htmlspecialchars($row['id_number']) . "</td>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td><strong>" . htmlspecialchars($row['role']) . "</strong></td>";
                echo "<td>" . ($row['password_changed'] ? 'Yes' : 'No') . "</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
            
            // Count by role
            $roleSql = "SELECT role, COUNT(*) as count FROM users GROUP BY role ORDER BY count DESC";
            $roleResult = $conn->query($roleSql);
            
            echo "<h3>Users by Role</h3>\n";
            echo "<ul>\n";
            while ($roleRow = $roleResult->fetch_assoc()) {
                echo "<li><strong>" . htmlspecialchars($roleRow['role']) . ":</strong> " . $roleRow['count'] . " users</li>\n";
            }
            echo "</ul>\n";
            
        } else {
            echo "<p style='color: orange;'>⚠️ No users found in the table</p>\n";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Users table does not exist!</p>\n";
        
        // Show available tables
        $tablesResult = $conn->query("SHOW TABLES");
        echo "<h3>Available Tables</h3>\n";
        echo "<ul>\n";
        while ($tableRow = $tablesResult->fetch_array()) {
            echo "<li>" . $tableRow[0] . "</li>\n";
        }
        echo "</ul>\n";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>
