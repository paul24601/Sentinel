-- Supervisor Review System for Parameter Records
-- Execute this SQL to add the supervisor review functionality

-- Create supervisor_reviews table to track who reviewed what
CREATE TABLE IF NOT EXISTS supervisor_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id VARCHAR(50) NOT NULL,
    supervisor_name VARCHAR(100) NOT NULL,
    review_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    review_notes TEXT DEFAULT 'Automatically reviewed by viewing record',
    INDEX idx_record_id (record_id),
    INDEX idx_supervisor (supervisor_name),
    FOREIGN KEY (record_id) REFERENCES parameter_records(record_id) ON DELETE CASCADE
);

-- Add review status columns to parameter_records table
ALTER TABLE parameter_records 
ADD COLUMN review_status ENUM('pending', 'reviewed', 'approved', 'needs_attention') DEFAULT 'pending' AFTER status,
ADD COLUMN first_reviewed_date DATETIME NULL AFTER review_status,
ADD COLUMN last_reviewed_date DATETIME NULL AFTER first_reviewed_date,
ADD COLUMN reviewer_count INT DEFAULT 0 AFTER last_reviewed_date;

-- Create index for better performance
CREATE INDEX idx_review_status ON parameter_records(review_status);
CREATE INDEX idx_first_reviewed_date ON parameter_records(first_reviewed_date);

-- Update existing records to have pending status
UPDATE parameter_records 
SET review_status = 'pending' 
WHERE review_status IS NULL;
