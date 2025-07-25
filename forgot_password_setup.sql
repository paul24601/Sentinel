-- Password Reset Request Table
CREATE TABLE IF NOT EXISTS password_reset_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_number VARCHAR(6) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    request_reason TEXT,
    request_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
    admin_id VARCHAR(6),
    admin_response_date DATETIME,
    admin_comment TEXT,
    new_password_hash VARCHAR(255),
    reset_token VARCHAR(64),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_number) REFERENCES users(id_number),
    FOREIGN KEY (admin_id) REFERENCES users(id_number),
    INDEX idx_status (status),
    INDEX idx_request_date (request_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
