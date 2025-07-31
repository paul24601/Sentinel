-- ============================================================================
-- FIXED SQL SCRIPT TO UPDATE DATABASE STRUCTURE FOR PARAMETERS FORM CHANGES
-- Execute this script to align database with the updated parameters/index.php form
-- ============================================================================

-- 1. Try to remove Zone0 (ignore error if doesn't exist)
ALTER TABLE moldheatertemperatures DROP COLUMN Zone0;

-- 2. Create moldopenparameters table if it doesn't exist
CREATE TABLE IF NOT EXISTS moldopenparameters (
    id INT(11) NOT NULL AUTO_INCREMENT,
    record_id VARCHAR(50) NOT NULL,
    MoldOpenPos1 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPos2 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPos3 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPos4 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPos5 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPos6 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenSpd1 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenSpd2 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenSpd3 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenSpd4 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenSpd5 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenSpd6 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPressure1 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPressure2 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPressure3 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPressure4 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPressure5 DECIMAL(10,3) DEFAULT NULL,
    MoldOpenPressure6 DECIMAL(10,3) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_record_id (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Create moldcloseparameters table if it doesn't exist
CREATE TABLE IF NOT EXISTS moldcloseparameters (
    id INT(11) NOT NULL AUTO_INCREMENT,
    record_id VARCHAR(50) NOT NULL,
    MoldClosePos1 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePos2 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePos3 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePos4 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePos5 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePos6 DECIMAL(10,3) DEFAULT NULL,
    MoldCloseSpd1 DECIMAL(10,3) DEFAULT NULL,
    MoldCloseSpd2 DECIMAL(10,3) DEFAULT NULL,
    MoldCloseSpd3 DECIMAL(10,3) DEFAULT NULL,
    MoldCloseSpd4 DECIMAL(10,3) DEFAULT NULL,
    MoldCloseSpd5 DECIMAL(10,3) DEFAULT NULL,
    MoldCloseSpd6 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePressure1 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePressure2 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePressure3 DECIMAL(10,3) DEFAULT NULL,
    MoldClosePressure4 DECIMAL(10,3) DEFAULT NULL,
    PCLORLP VARCHAR(255) DEFAULT NULL,
    PCHORHP VARCHAR(255) DEFAULT NULL,
    LowPresTimeLimit DECIMAL(10,3) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_record_id (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Update moldoperationspecs table to add new cooling media fields
ALTER TABLE moldoperationspecs 
ADD COLUMN StationaryCoolingMedia VARCHAR(255) DEFAULT NULL,
ADD COLUMN MovableCoolingMedia VARCHAR(255) DEFAULT NULL,
ADD COLUMN StationaryCoolingMediaRemarks TEXT DEFAULT NULL,
ADD COLUMN MovableCoolingMediaRemarks TEXT DEFAULT NULL;

-- 5. Migrate existing cooling media data (if exists)
UPDATE moldoperationspecs 
SET StationaryCoolingMedia = CoolingMedia, 
    MovableCoolingMedia = CoolingMedia 
WHERE CoolingMedia IS NOT NULL AND CoolingMedia != '';

-- Optional: Drop old CoolingMedia column (uncomment if needed)
-- ALTER TABLE moldoperationspecs DROP COLUMN CoolingMedia;
