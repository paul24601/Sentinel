-- Add record_id column to all existing tables
ALTER TABLE productmachineinfo ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE productdetails ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE materialcomposition ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE colorantdetails ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE moldoperationspecs ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE timerparameters ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE barrelheatertemperatures ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE moldheatertemperatures ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE plasticizingparameters ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE injectionparameters ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE ejectionparameters ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE corepullsettings ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE additionalinformation ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE personnel ADD COLUMN record_id VARCHAR(20) AFTER id;
ALTER TABLE attachments ADD COLUMN record_id VARCHAR(20) AFTER id;

-- Create a master records table
CREATE TABLE IF NOT EXISTS parameter_records (
    record_id VARCHAR(20) PRIMARY KEY,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'archived', 'deleted') DEFAULT 'active',
    submitted_by VARCHAR(100),
    title VARCHAR(255),
    description TEXT
);

-- Create indexes for faster joins on record_id
CREATE INDEX idx_record_id_productmachineinfo ON productmachineinfo(record_id);
CREATE INDEX idx_record_id_productdetails ON productdetails(record_id);
CREATE INDEX idx_record_id_materialcomposition ON materialcomposition(record_id);
CREATE INDEX idx_record_id_colorantdetails ON colorantdetails(record_id);
CREATE INDEX idx_record_id_moldoperationspecs ON moldoperationspecs(record_id);
CREATE INDEX idx_record_id_timerparameters ON timerparameters(record_id);
CREATE INDEX idx_record_id_barrelheatertemperatures ON barrelheatertemperatures(record_id);
CREATE INDEX idx_record_id_moldheatertemperatures ON moldheatertemperatures(record_id);
CREATE INDEX idx_record_id_plasticizingparameters ON plasticizingparameters(record_id);
CREATE INDEX idx_record_id_injectionparameters ON injectionparameters(record_id);
CREATE INDEX idx_record_id_ejectionparameters ON ejectionparameters(record_id);
CREATE INDEX idx_record_id_corepullsettings ON corepullsettings(record_id);
CREATE INDEX idx_record_id_additionalinformation ON additionalinformation(record_id);
CREATE INDEX idx_record_id_personnel ON personnel(record_id);
CREATE INDEX idx_record_id_attachments ON attachments(record_id); 