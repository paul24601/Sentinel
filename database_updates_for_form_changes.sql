-- ============================================================================
-- SQL SCRIPT TO UPDATE DATABASE STRUCTURE FOR PARAMETERS FORM CHANGES
-- Execute this script to align database with the updated parameters/index.php form
-- ============================================================================

-- 1. UPDATE moldoperationspecs TABLE TO MATCH NEW FORM STRUCTURE
-- ============================================================================

-- Add new cooling media fields to replace single CoolingMedia field
ALTER TABLE moldoperationspecs 
ADD COLUMN IF NOT EXISTS StationaryCoolingMedia VARCHAR(255) DEFAULT NULL COMMENT 'Stationary cooling media selection',
ADD COLUMN IF NOT EXISTS MovableCoolingMedia VARCHAR(255) DEFAULT NULL COMMENT 'Movable cooling media selection',
ADD COLUMN IF NOT EXISTS CoolingMediaRemarks TEXT DEFAULT NULL COMMENT 'Additional cooling media remarks';

-- Migrate existing CoolingMedia data to new structure (preserve existing data)
UPDATE moldoperationspecs 
SET StationaryCoolingMedia = CoolingMedia, MovableCoolingMedia = CoolingMedia 
WHERE CoolingMedia IS NOT NULL AND CoolingMedia != '';

-- Drop old CoolingMedia column after migration (optional - uncomment if needed)
-- ALTER TABLE moldoperationspecs DROP COLUMN CoolingMedia;

-- 2. CREATE MOLD OPEN PARAMETERS TABLE
-- ============================================================================

CREATE TABLE IF NOT EXISTS moldopenparameters (
    id INT(11) NOT NULL AUTO_INCREMENT,
    record_id VARCHAR(20) DEFAULT NULL COMMENT 'Links to parameter_records table',
    -- Position fields (6 positions for mold open)
    MoldOpenPos1 FLOAT DEFAULT NULL COMMENT 'Mold open position 1',
    MoldOpenPos2 FLOAT DEFAULT NULL COMMENT 'Mold open position 2', 
    MoldOpenPos3 FLOAT DEFAULT NULL COMMENT 'Mold open position 3',
    MoldOpenPos4 FLOAT DEFAULT NULL COMMENT 'Mold open position 4',
    MoldOpenPos5 FLOAT DEFAULT NULL COMMENT 'Mold open position 5',
    MoldOpenPos6 FLOAT DEFAULT NULL COMMENT 'Mold open position 6',
    -- Speed fields (6 speeds for mold open)
    MoldOpenSpd1 FLOAT DEFAULT NULL COMMENT 'Mold open speed 1',
    MoldOpenSpd2 FLOAT DEFAULT NULL COMMENT 'Mold open speed 2',
    MoldOpenSpd3 FLOAT DEFAULT NULL COMMENT 'Mold open speed 3',
    MoldOpenSpd4 FLOAT DEFAULT NULL COMMENT 'Mold open speed 4',
    MoldOpenSpd5 FLOAT DEFAULT NULL COMMENT 'Mold open speed 5',
    MoldOpenSpd6 FLOAT DEFAULT NULL COMMENT 'Mold open speed 6',
    -- Pressure fields (6 pressures for mold open)
    MoldOpenPressure1 FLOAT DEFAULT NULL COMMENT 'Mold open pressure 1',
    MoldOpenPressure2 FLOAT DEFAULT NULL COMMENT 'Mold open pressure 2',
    MoldOpenPressure3 FLOAT DEFAULT NULL COMMENT 'Mold open pressure 3',
    MoldOpenPressure4 FLOAT DEFAULT NULL COMMENT 'Mold open pressure 4',
    MoldOpenPressure5 FLOAT DEFAULT NULL COMMENT 'Mold open pressure 5',
    MoldOpenPressure6 FLOAT DEFAULT NULL COMMENT 'Mold open pressure 6',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_record_id_moldopen (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Mold open parameters including positions, speeds, and pressures';

-- 3. CREATE MOLD CLOSE PARAMETERS TABLE
-- ============================================================================

CREATE TABLE IF NOT EXISTS moldcloseparameters (
    id INT(11) NOT NULL AUTO_INCREMENT,
    record_id VARCHAR(20) DEFAULT NULL COMMENT 'Links to parameter_records table',
    -- Position fields (6 positions for mold close)
    MoldClosePos1 FLOAT DEFAULT NULL COMMENT 'Mold close position 1',
    MoldClosePos2 FLOAT DEFAULT NULL COMMENT 'Mold close position 2',
    MoldClosePos3 FLOAT DEFAULT NULL COMMENT 'Mold close position 3',
    MoldClosePos4 FLOAT DEFAULT NULL COMMENT 'Mold close position 4',
    MoldClosePos5 FLOAT DEFAULT NULL COMMENT 'Mold close position 5',
    MoldClosePos6 FLOAT DEFAULT NULL COMMENT 'Mold close position 6',
    -- Speed fields (6 speeds for mold close)
    MoldCloseSpd1 FLOAT DEFAULT NULL COMMENT 'Mold close speed 1',
    MoldCloseSpd2 FLOAT DEFAULT NULL COMMENT 'Mold close speed 2',
    MoldCloseSpd3 FLOAT DEFAULT NULL COMMENT 'Mold close speed 3',
    MoldCloseSpd4 FLOAT DEFAULT NULL COMMENT 'Mold close speed 4',
    MoldCloseSpd5 FLOAT DEFAULT NULL COMMENT 'Mold close speed 5',
    MoldCloseSpd6 FLOAT DEFAULT NULL COMMENT 'Mold close speed 6',
    -- Pressure fields (4 pressures for mold close - INCLUDING NEW PRESSURE 4)
    MoldClosePressure1 FLOAT DEFAULT NULL COMMENT 'Mold close pressure 1',
    MoldClosePressure2 FLOAT DEFAULT NULL COMMENT 'Mold close pressure 2',
    MoldClosePressure3 FLOAT DEFAULT NULL COMMENT 'Mold close pressure 3',
    MoldClosePressure4 FLOAT DEFAULT NULL COMMENT 'Mold close pressure 4 - NEW FIELD ADDED',
    -- Additional pressure control fields
    PCLORLP VARCHAR(255) DEFAULT NULL COMMENT 'PCL/LP parameter',
    PCHORHP VARCHAR(255) DEFAULT NULL COMMENT 'PCH/HP parameter', 
    LowPresTimeLimit FLOAT DEFAULT NULL COMMENT 'Low pressure time limit',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_record_id_moldclose (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Mold close parameters including positions, speeds, and pressures with Pressure 4';

-- 4. UPDATE moldheatertemperatures TABLE - REMOVE ZONE 0
-- ============================================================================

-- Check if Zone0 column exists and remove it (mold heater zone 0 was removed from form)
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'injectionmoldingparameters' 
    AND TABLE_NAME = 'moldheatertemperatures' 
    AND COLUMN_NAME = 'Zone0'
);

-- Drop Zone0 column if it exists
SET @sql = IF(@column_exists > 0, 
    'ALTER TABLE moldheatertemperatures DROP COLUMN Zone0', 
    'SELECT "Zone0 column does not exist" as message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 5. UPDATE OPERATION TYPE VALUES TO MATCH NEW FORM OPTIONS
-- ============================================================================

-- Update existing operation types to match new form dropdown values
-- Old: Manual, Semi-Auto, Auto
-- New: semi-automatic, automatic, robot arm, manual
UPDATE moldoperationspecs 
SET OperationType = CASE 
    WHEN OperationType = 'Manual' THEN 'manual'
    WHEN OperationType = 'Semi-Auto' THEN 'semi-automatic'
    WHEN OperationType = 'Auto' THEN 'automatic'
    WHEN OperationType = 'automatic' THEN 'automatic'  -- Keep if already updated
    WHEN OperationType = 'semi-automatic' THEN 'semi-automatic'  -- Keep if already updated
    WHEN OperationType = 'robot arm' THEN 'robot arm'  -- Keep if already updated
    WHEN OperationType = 'manual' THEN 'manual'  -- Keep if already updated
    ELSE OperationType
END;

-- 6. ADD PERFORMANCE INDEXES
-- ============================================================================

-- Add indexes for better query performance
CREATE INDEX IF NOT EXISTS idx_record_id_moldoperationspecs ON moldoperationspecs(record_id);
CREATE INDEX IF NOT EXISTS idx_record_id_moldheatertemperatures ON moldheatertemperatures(record_id);

-- 7. VERIFICATION QUERIES (RUN THESE TO CHECK CHANGES)
-- ============================================================================

-- Verify moldoperationspecs structure
-- DESCRIBE moldoperationspecs;

-- Verify new mold open table
-- DESCRIBE moldopenparameters;

-- Verify new mold close table  
-- DESCRIBE moldcloseparameters;

-- Verify moldheatertemperatures structure (Zone0 should be removed)
-- DESCRIBE moldheatertemperatures;

-- Check operation type values
-- SELECT DISTINCT OperationType FROM moldoperationspecs;

-- Check cooling media structure
-- SELECT StationaryCoolingMedia, MovableCoolingMedia, CoolingMediaRemarks FROM moldoperationspecs LIMIT 5;

-- ============================================================================
-- SUMMARY OF CHANGES MADE:
-- ============================================================================

/*
FORM CHANGES REFLECTED IN DATABASE:

1. ✅ MOLD HEATER ZONE 0 REMOVAL
   - Removed Zone0 column from moldheatertemperatures table

2. ✅ OPERATION DROPDOWN UPDATE  
   - Updated values: semi-automatic, automatic, robot arm, manual
   - Migrated existing data to new format

3. ✅ COOLING MEDIA RESTRUCTURE
   - Added StationaryCoolingMedia column
   - Added MovableCoolingMedia column  
   - Added CoolingMediaRemarks column
   - Migrated existing data to new structure

4. ✅ MOLD CLOSE PRESSURE 4 ADDITION
   - Created moldcloseparameters table with MoldClosePressure4 field
   - Includes all positions, speeds, and pressures for mold close

5. ✅ MOLD OPEN AND CLOSE SEPARATION
   - Created separate moldopenparameters table
   - Created separate moldcloseparameters table
   - Both tables support full position/speed/pressure arrays

6. ✅ PERFORMANCE OPTIMIZATION
   - Added indexes for better query performance
   - Proper data types and constraints
*/

-- ============================================================================
-- NEXT STEPS:
-- ============================================================================

/*
AFTER RUNNING THIS SQL SCRIPT:

1. Update parameters/submit.php to handle new field names:
   - stationary-cooling-media
   - movable-cooling-media  
   - cooling-media-remarks
   - moldClosePressure4
   - All mold open/close parameters

2. Update parameters/submission.php to display new structure:
   - Show separated mold open and close sections
   - Display new cooling media fields
   - Handle missing Zone0 in mold heater temperatures

3. Test form submission with new fields

4. Update any reports/analytics to use new field structure
*/
