<?php
// Quick fix script to create production_reports table
$host = 'localhost';
$user = 'root';
$pass = 'injectionadmin123';
$db = 'dailymonitoringsheet';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    echo 'Connected to: ' . $db . "\n";
    
    // Create production_reports table
    $sql = "CREATE TABLE IF NOT EXISTS production_reports (
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
        manpower INT NOT NULL,
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
    )";
    
    if ($conn->query($sql)) {
        echo "Table production_reports created successfully!\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
    
    // Check if table exists
    $result = $conn->query("SHOW TABLES LIKE 'production_reports'");
    if ($result->num_rows > 0) {
        echo "Table exists and is ready to use!\n";
    }
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
