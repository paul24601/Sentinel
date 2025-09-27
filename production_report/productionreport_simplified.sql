-- SIMPLIFIED Production Report Database Schema
-- This matches what your application actually uses

-- Use the existing production database
USE u158529957_spmc_prodreprt;

-- Drop existing tables if they exist (in correct order to avoid foreign key issues)
DROP TABLE IF EXISTS downtime_entries;
DROP TABLE IF EXISTS quality_control_entries;
DROP TABLE IF EXISTS production_reports;
DROP TABLE IF EXISTS users;

-- Drop views if they exist
DROP VIEW IF EXISTS report_summary;
DROP VIEW IF EXISTS quality_control_summary;
DROP VIEW IF EXISTS report_list;

-- Drop procedures if they exist
DROP PROCEDURE IF EXISTS get_report_statistics;

-- Create users table (needed for foreign key)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'supervisor', 'operator') NOT NULL DEFAULT 'operator',
    id_number VARCHAR(50) UNIQUE, -- Added for session compatibility
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create production reports table (ONLY table actually used)
CREATE TABLE production_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    plant VARCHAR(100) NOT NULL DEFAULT 'Plant A',
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
    manpower INT NOT NULL DEFAULT 0,
    reg VARCHAR(50),
    ot VARCHAR(50),
    total_reject INT NOT NULL DEFAULT 0,
    total_good INT NOT NULL DEFAULT 0,
    remarks TEXT,
    created_by INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_report_date (report_date),
    INDEX idx_created_by (created_by),
    INDEX idx_product_name (product_name),
    INDEX idx_shift (shift)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123) - using INSERT IGNORE to avoid duplicates
INSERT IGNORE INTO users (username, password, full_name, email, role, id_number) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
        'System Administrator', 'admin@example.com', 'admin', 'ADM001');

-- Insert sample users - using INSERT IGNORE to avoid duplicates
INSERT IGNORE INTO users (username, password, full_name, email, role, id_number) VALUES
('supervisor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'John Supervisor', 'john.supervisor@example.com', 'supervisor', 'SUP001'),
('operator1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
 'Jane Operator', 'jane.operator@example.com', 'operator', 'OP001');

-- Insert sample production report
INSERT INTO production_reports (
    plant, report_date, shift, shift_hours, 
    product_name, color, part_no,
    id_number1, id_number2, id_number3, ejo_number,
    assembly_line, manpower, reg, ot,
    total_reject, total_good, remarks, created_by
) VALUES (
    'Plant A', CURDATE(), 'Morning', '6:00 AM - 2:00 PM',
    'ASSY FOG LAMP LH TUNDRA', 'Black', 'FL-001-LH',
    'ID001', 'ID002', 'ID003', 'EJO-2024-001',
    'Line-01', 5, '8', '2',
    10, 95, 'Sample production report for testing', 1
);

-- Simple view for reports
CREATE OR REPLACE VIEW report_list AS
SELECT 
    pr.id,
    pr.plant,
    pr.report_date,
    pr.shift,
    pr.shift_hours,
    pr.product_name,
    pr.color,
    pr.part_no,
    pr.assembly_line,
    pr.manpower,
    pr.total_reject,
    pr.total_good,
    (pr.total_reject + pr.total_good) as total_production,
    CASE 
        WHEN (pr.total_reject + pr.total_good) > 0 
        THEN ROUND((pr.total_reject * 100.0) / (pr.total_reject + pr.total_good), 2)
        ELSE 0 
    END as reject_percentage,
    u.full_name as created_by_name,
    pr.created_at
FROM production_reports pr
JOIN users u ON pr.created_by = u.id
ORDER BY pr.created_at DESC;
