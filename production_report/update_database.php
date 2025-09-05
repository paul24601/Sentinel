<?php
/**
 * Database Update Script for Production Report System
 * This script adds the new fields for Finishing and Injection report types
 */

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';

try {
    // Get database connection
    $conn = DatabaseManager::getConnection('sentinel_monitoring');

    echo "<h2>Production Report Database Update</h2>\n";
    echo "<p>Updating database schema...</p>\n";

    // Add report_type column if it doesn't exist
    $sql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'production_reports' 
            AND COLUMN_NAME = 'report_type'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $conn->query("ALTER TABLE production_reports ADD COLUMN report_type ENUM('finishing', 'injection') NOT NULL DEFAULT 'finishing' AFTER id");
        echo "<p>✓ Added report_type column</p>\n";
    } else {
        echo "<p>✓ report_type column already exists</p>\n";
    }

    // Add injection-specific fields
    $injection_fields = [
        'injection_pressure DECIMAL(10,2)',
        'molding_temp INT',
        'cycle_time DECIMAL(10,2)',
        'cavity_count INT',
        'cooling_time DECIMAL(10,2)',
        'holding_pressure DECIMAL(10,2)',
        'material_type VARCHAR(100)',
        'shot_size DECIMAL(10,2)'
    ];

    foreach ($injection_fields as $field) {
        $field_name = explode(' ', $field)[0];
        $sql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'production_reports' 
                AND COLUMN_NAME = '$field_name'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if ($row['count'] == 0) {
            $conn->query("ALTER TABLE production_reports ADD COLUMN $field AFTER total_good");
            echo "<p>✓ Added $field_name column</p>\n";
        } else {
            echo "<p>✓ $field_name column already exists</p>\n";
        }
    }

    // Add machine and robot_arm fields
    $machine_fields = [
        'machine VARCHAR(100)' => 'machine',
        'robot_arm ENUM("Yes", "No")' => 'robot_arm'
    ];

    foreach ($machine_fields as $field => $field_name) {
        $sql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'production_reports' 
                AND COLUMN_NAME = '$field_name'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if ($row['count'] == 0) {
            $conn->query("ALTER TABLE production_reports ADD COLUMN $field AFTER assembly_line");
            echo "<p>✓ Added $field_name column</p>\n";
        } else {
            echo "<p>✓ $field_name column already exists</p>\n";
        }
    }

    // Update assembly_line to allow NULL for injection reports
    $sql = "SELECT IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'production_reports' 
            AND COLUMN_NAME = 'assembly_line'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    if ($row['IS_NULLABLE'] === 'NO') {
        $conn->query("ALTER TABLE production_reports MODIFY COLUMN assembly_line VARCHAR(100) NULL");
        echo "<p>✓ Updated assembly_line to allow NULL values</p>\n";
    } else {
        echo "<p>✓ Assembly_line column already allows NULL values</p>\n";
    }

    // Add finishing-specific fields
    $finishing_fields = [
        'finishing_process VARCHAR(100)',
        'station_number VARCHAR(50)',
        'work_order VARCHAR(100)',
        'finishing_tools VARCHAR(255)',
        'quality_standard VARCHAR(255)'
    ];

    foreach ($finishing_fields as $field) {
        $field_name = explode(' ', $field)[0];
        $sql = "SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'production_reports' 
                AND COLUMN_NAME = '$field_name'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if ($row['count'] == 0) {
            $conn->query("ALTER TABLE production_reports ADD COLUMN $field AFTER total_good");
            echo "<p>✓ Added $field_name column</p>\n";
        } else {
            echo "<p>✓ $field_name column already exists</p>\n";
        }
    }

    // Add indexes
    $conn->query("CREATE INDEX IF NOT EXISTS idx_production_reports_type ON production_reports(report_type)");
    $conn->query("CREATE INDEX IF NOT EXISTS idx_production_reports_type_date ON production_reports(report_type, report_date)");
    echo "<p>✓ Added database indexes</p>\n";

    echo "<h3 style='color: green;'>Database update completed successfully!</h3>\n";
    echo "<p><a href='index.php'>Go to Production Report System</a></p>\n";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>Error updating database:</h3>\n";
    echo "<p>" . $e->getMessage() . "</p>\n";
}
?>
