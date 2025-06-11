-- Create and use the database
DROP DATABASE IF EXISTS productionreport;
CREATE DATABASE productionreport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE productionreport;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'supervisor', 'operator') NOT NULL DEFAULT 'operator',
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create production reports table
CREATE TABLE production_reports (
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
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create quality control entries table
CREATE TABLE quality_control_entries (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create downtime entries table
CREATE TABLE downtime_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT NOT NULL,
    description VARCHAR(255) NOT NULL,
    minutes INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES production_reports(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create indexes for better performance
CREATE INDEX idx_production_reports_date ON production_reports(report_date);
CREATE INDEX idx_production_reports_created_by ON production_reports(created_by);
CREATE INDEX idx_quality_control_entries_report ON quality_control_entries(report_id);
CREATE INDEX idx_downtime_entries_report ON downtime_entries(report_id);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, full_name, email, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@example.com', 'admin');

-- Insert sample users
INSERT INTO users (username, password, full_name, email, role) VALUES
('supervisor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Supervisor', 'john.supervisor@example.com', 'supervisor'),
('operator1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Operator', 'jane.operator@example.com', 'operator');

-- Insert sample production report
INSERT INTO production_reports (
    plant, report_date, shift, shift_hours, 
    product_name, color, part_no,
    id_number1, id_number2, id_number3, ejo_number,
    assembly_line, manpower, reg, ot,
    total_reject, total_good, remarks, created_by
) VALUES (
    'Plant A', CURDATE(), 'Morning', '6:00 AM - 2:00 PM',
    'Test Product XYZ', 'Black', 'TP-123-456',
    'ID001', 'ID002', 'ID003', 'EJO-2024-001',
    'Line-01', 5, '8', '2',
    10, 95, 'Sample production report for testing', 1
);

-- Get the last inserted report ID
SET @report_id = LAST_INSERT_ID();

-- Insert sample quality control entries
INSERT INTO quality_control_entries (
    report_id, part_name, defect,
    time1, time2, time3, time4, time5, time6, time7, time8, total
) VALUES
(@report_id, 'Part A', 'Scratch', 2, 1, 3, 0, 1, 2, 0, 1, 10),
(@report_id, 'Part B', 'Dent', 1, 2, 0, 1, 0, 1, 1, 0, 6),
(@report_id, 'Part C', 'Color Mismatch', 0, 1, 1, 2, 0, 0, 1, 1, 6);

-- Insert sample downtime entries
INSERT INTO downtime_entries (report_id, description, minutes) VALUES
(@report_id, 'Machine Maintenance', 30),
(@report_id, 'Material Change', 15),
(@report_id, 'Technical Issue', 45);

-- Create view for report summary
CREATE OR REPLACE VIEW report_summary AS
SELECT 
    pr.id,
    pr.plant,
    pr.report_date,
    pr.shift,
    pr.product_name,
    pr.assembly_line,
    pr.total_reject,
    pr.total_good,
    u.full_name as created_by_name,
    pr.created_at,
    (SELECT COUNT(*) FROM quality_control_entries WHERE report_id = pr.id) as defect_entries,
    (SELECT COUNT(*) FROM downtime_entries WHERE report_id = pr.id) as downtime_entries,
    (SELECT SUM(minutes) FROM downtime_entries WHERE report_id = pr.id) as total_downtime_minutes
FROM production_reports pr
JOIN users u ON pr.created_by = u.id;

-- Create view for quality control summary
CREATE OR REPLACE VIEW quality_control_summary AS
SELECT 
    pr.id as report_id,
    pr.report_date,
    pr.shift,
    qc.part_name,
    qc.defect,
    qc.total as defect_count,
    (qc.total * 100.0 / NULLIF(pr.total_good + pr.total_reject, 0)) as defect_percentage
FROM production_reports pr
JOIN quality_control_entries qc ON pr.id = qc.report_id;

-- Create procedure for getting report statistics
DELIMITER //
CREATE PROCEDURE get_report_statistics(IN start_date DATE, IN end_date DATE)
BEGIN
    SELECT 
        COUNT(*) as total_reports,
        SUM(total_good) as total_good_parts,
        SUM(total_reject) as total_reject_parts,
        (SUM(total_reject) * 100.0 / NULLIF(SUM(total_good + total_reject), 0)) as reject_percentage,
        (SELECT SUM(minutes) FROM downtime_entries de 
         JOIN production_reports pr ON de.report_id = pr.id 
         WHERE pr.report_date BETWEEN start_date AND end_date) as total_downtime
    FROM production_reports
    WHERE report_date BETWEEN start_date AND end_date;
END //
DELIMITER ;

-- Create trigger to update total reject count
DELIMITER //
CREATE TRIGGER update_total_reject AFTER INSERT ON quality_control_entries
FOR EACH ROW
BEGIN
    UPDATE production_reports 
    SET total_reject = (
        SELECT SUM(total) 
        FROM quality_control_entries 
        WHERE report_id = NEW.report_id
    )
    WHERE id = NEW.report_id;
END //
DELIMITER ; 