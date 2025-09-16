<?php
// Script to check and create missing production report tables
require_once __DIR__ . '/../includes/database.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    
    echo "<h2>Checking Production Report Database Tables</h2>\n";
    
    // Check if production_reports table exists
    $result = $conn->query("SHOW TABLES LIKE 'production_reports'");
    if ($result->num_rows == 0) {
        echo "<p>Creating production_reports table...</p>\n";
        
        $sql = "CREATE TABLE production_reports (
            id INT PRIMARY KEY AUTO_INCREMENT,
            report_type ENUM('finishing', 'injection') NOT NULL,
            report_date DATE NOT NULL,
            shift VARCHAR(20) NOT NULL,
            shift_hours VARCHAR(50) NOT NULL,
            product_name VARCHAR(200) NOT NULL,
            color VARCHAR(100) NOT NULL,
            part_no VARCHAR(100) NOT NULL,
            id_number1 VARCHAR(100),
            id_number2 VARCHAR(100),
            id_number3 VARCHAR(100),
            ejo_number VARCHAR(100),
            assembly_line VARCHAR(100),
            machine VARCHAR(100),
            robot_arm ENUM('Yes', 'No'),
            manpower INT,
            reg VARCHAR(50),
            ot VARCHAR(50),
            total_reject INT NOT NULL DEFAULT 0,
            total_good INT NOT NULL DEFAULT 0,
            injection_pressure DECIMAL(10,2),
            molding_temp INT,
            cycle_time DECIMAL(10,2),
            cavity_count INT,
            cooling_time DECIMAL(10,2),
            holding_pressure DECIMAL(10,2),
            material_type VARCHAR(100),
            shot_size DECIMAL(10,2),
            finishing_process VARCHAR(100),
            station_number VARCHAR(50),
            work_order VARCHAR(100),
            finishing_tools VARCHAR(255),
            quality_standard VARCHAR(255),
            remarks TEXT,
            created_by VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if ($conn->query($sql)) {
            echo "<p style='color: green;'>✓ production_reports table created successfully!</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Error creating production_reports table: " . $conn->error . "</p>\n";
        }
    } else {
        echo "<p style='color: green;'>✓ production_reports table exists</p>\n";
    }
    
    // Check if quality_control_entries table exists
    $result = $conn->query("SHOW TABLES LIKE 'quality_control_entries'");
    if ($result->num_rows == 0) {
        echo "<p>Creating quality_control_entries table...</p>\n";
        
        $sql = "CREATE TABLE quality_control_entries (
            id INT PRIMARY KEY AUTO_INCREMENT,
            report_id INT NOT NULL,
            part_name VARCHAR(200) NOT NULL,
            defect VARCHAR(200) NOT NULL,
            time1 INT NOT NULL DEFAULT 0,
            time2 INT NOT NULL DEFAULT 0,
            time3 INT NOT NULL DEFAULT 0,
            time4 INT NOT NULL DEFAULT 0,
            time5 INT NOT NULL DEFAULT 0,
            time6 INT NOT NULL DEFAULT 0,
            time7 INT NOT NULL DEFAULT 0,
            time8 INT NOT NULL DEFAULT 0,
            total INT NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (report_id) REFERENCES production_reports(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if ($conn->query($sql)) {
            echo "<p style='color: green;'>✓ quality_control_entries table created successfully!</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Error creating quality_control_entries table: " . $conn->error . "</p>\n";
        }
    } else {
        echo "<p style='color: green;'>✓ quality_control_entries table exists</p>\n";
    }
    
    // Check if downtime_entries table exists
    $result = $conn->query("SHOW TABLES LIKE 'downtime_entries'");
    if ($result->num_rows == 0) {
        echo "<p>Creating downtime_entries table...</p>\n";
        
        $sql = "CREATE TABLE downtime_entries (
            id INT PRIMARY KEY AUTO_INCREMENT,
            report_id INT NOT NULL,
            description VARCHAR(255) NOT NULL,
            minutes INT NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (report_id) REFERENCES production_reports(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if ($conn->query($sql)) {
            echo "<p style='color: green;'>✓ downtime_entries table created successfully!</p>\n";
        } else {
            echo "<p style='color: red;'>✗ Error creating downtime_entries table: " . $conn->error . "</p>\n";
        }
    } else {
        echo "<p style='color: green;'>✓ downtime_entries table exists</p>\n";
    }
    
    echo "<h3>Database Tables Status Complete!</h3>\n";
    echo "<p><a href='view.php?id=9'>Test the view page now</a></p>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
}
?>
