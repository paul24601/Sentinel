<?php
require_once __DIR__ . '/../includes/db_connect.php';

try {
    // Enable error reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    // Start transaction
    $conn->begin_transaction();

    // Create users table
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        role ENUM('admin', 'supervisor', 'operator') NOT NULL DEFAULT 'operator',
        is_active BOOLEAN NOT NULL DEFAULT TRUE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create production reports table
    $conn->query("CREATE TABLE IF NOT EXISTS production_reports (
        id INT PRIMARY KEY AUTO_INCREMENT,
        plant VARCHAR(100) NOT NULL,
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
        assembly_line VARCHAR(100) NOT NULL,
        manpower INT NOT NULL,
        reg VARCHAR(50),
        ot VARCHAR(50),
        total_reject INT NOT NULL DEFAULT 0,
        total_good INT NOT NULL DEFAULT 0,
        remarks TEXT,
        created_by INT NOT NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create quality control entries table
    $conn->query("CREATE TABLE IF NOT EXISTS quality_control_entries (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create downtime entries table
    $conn->query("CREATE TABLE IF NOT EXISTS downtime_entries (
        id INT PRIMARY KEY AUTO_INCREMENT,
        report_id INT NOT NULL,
        description VARCHAR(255) NOT NULL,
        minutes INT NOT NULL DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (report_id) REFERENCES production_reports(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Create indexes for better performance
    $conn->query("CREATE INDEX IF NOT EXISTS idx_production_reports_date ON production_reports(report_date)");
    $conn->query("CREATE INDEX IF NOT EXISTS idx_production_reports_created_by ON production_reports(created_by)");
    $conn->query("CREATE INDEX IF NOT EXISTS idx_quality_control_entries_report ON quality_control_entries(report_id)");
    $conn->query("CREATE INDEX IF NOT EXISTS idx_downtime_entries_report ON downtime_entries(report_id)");

    // Create default admin user if not exists
    $default_admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT IGNORE INTO users (username, password, full_name, email, role) 
                  VALUES ('admin', '$default_admin_password', 'System Administrator', 'admin@example.com', 'admin')");

    // Commit transaction
    $conn->commit();

    echo "Database setup completed successfully!\n";
    echo "Default admin credentials:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    echo "\nPlease change these credentials immediately after first login.";

} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($conn)) {
        $conn->rollback();
    }
    
    echo "Error setting up database: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// Close connection
if (isset($conn)) {
    $conn->close();
} 