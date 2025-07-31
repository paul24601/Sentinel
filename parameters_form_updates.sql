-- SQL script to update the injection molding parameters database
-- to support the new form changes made to parameters/index.php
-- Run this script after making the form modifications

-- ============================================================================
-- TABLE MODIFICATIONS FOR FORM UPDATES
-- ============================================================================

-- 1. Update moldoperationspecs table to support the new cooling media fields
-- Replace single CoolingMedia with separate stationary and movable cooling media

-- First, check if the new columns don't already exist
ALTER TABLE moldoperationspecs 
ADD COLUMN IF NOT EXISTS StationaryCoolingMedia VARCHAR(255) DEFAULT NULL COMMENT 'Stationary cooling media type';

ALTER TABLE moldoperationspecs 
ADD COLUMN IF NOT EXISTS MovableCoolingMedia VARCHAR(255) DEFAULT NULL COMMENT 'Movable cooling media type';

ALTER TABLE moldoperationspecs 
ADD COLUMN IF NOT EXISTS CoolingMediaRemarks TEXT DEFAULT NULL COMMENT 'Additional remarks about cooling media';

-- Migrate existing CoolingMedia data to both stationary and movable fields
-- (This preserves existing data by copying it to both new fields)
UPDATE moldoperationspecs 
SET StationaryCoolingMedia = CoolingMedia, 
    MovableCoolingMedia = CoolingMedia 
WHERE CoolingMedia IS NOT NULL AND CoolingMedia != '';

-- Drop the old CoolingMedia column (optional - uncomment if you want to remove it)
-- ALTER TABLE moldoperationspecs DROP COLUMN CoolingMedia;

-- ============================================================================
-- CREATE MOLD CLOSE PARAMETERS TABLE
-- ============================================================================

-- Create table for mold close parameters if it doesn't exist
CREATE TABLE IF NOT EXISTS moldcloseparameters (
    id INT(11) NOT NULL AUTO_INCREMENT,
    record_id VARCHAR(20) DEFAULT NULL COMMENT 'Links to parameter_records table',
    -- Position fields (6 positions)
    MoldClosePos1 FLOAT DEFAULT NULL COMMENT 'Mold close position 1',
    MoldClosePos2 FLOAT DEFAULT NULL COMMENT 'Mold close position 2',
    MoldClosePos3 FLOAT DEFAULT NULL COMMENT 'Mold close position 3',
    MoldClosePos4 FLOAT DEFAULT NULL COMMENT 'Mold close position 4',
    MoldClosePos5 FLOAT DEFAULT NULL COMMENT 'Mold close position 5',
    MoldClosePos6 FLOAT DEFAULT NULL COMMENT 'Mold close position 6',
    -- Speed fields (6 speeds)
    MoldCloseSpd1 FLOAT DEFAULT NULL COMMENT 'Mold close speed 1',
    MoldCloseSpd2 FLOAT DEFAULT NULL COMMENT 'Mold close speed 2',
    MoldCloseSpd3 FLOAT DEFAULT NULL COMMENT 'Mold close speed 3',
    MoldCloseSpd4 FLOAT DEFAULT NULL COMMENT 'Mold close speed 4',
    MoldCloseSpd5 FLOAT DEFAULT NULL COMMENT 'Mold close speed 5',
    MoldCloseSpd6 FLOAT DEFAULT NULL COMMENT 'Mold close speed 6',
    -- Pressure fields (4 pressures - now includes Pressure 4)
    MoldClosePressure1 FLOAT DEFAULT NULL COMMENT 'Mold close pressure 1',
    MoldClosePressure2 FLOAT DEFAULT NULL COMMENT 'Mold close pressure 2',
    MoldClosePressure3 FLOAT DEFAULT NULL COMMENT 'Mold close pressure 3',
    MoldClosePressure4 FLOAT DEFAULT NULL COMMENT 'Mold close pressure 4 - NEW FIELD',
    -- Additional pressure fields
    PCLORLP VARCHAR(255) DEFAULT NULL COMMENT 'PCL/LP parameter',
    PCHORHP VARCHAR(255) DEFAULT NULL COMMENT 'PCH/HP parameter',
    LowPresTimeLimit FLOAT DEFAULT NULL COMMENT 'Low pressure time limit',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_record_id_moldclose (record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Mold close parameters including positions, speeds, and pressures';

-- ============================================================================
-- UPDATE OPERATION TYPE VALUES
-- ============================================================================

-- Update existing operation types to match the new form options
-- Old values: Manual, Semi-Auto, Auto
-- New values: semi-automatic, automatic, robot arm, manual

UPDATE moldoperationspecs 
SET OperationType = CASE 
    WHEN OperationType = 'Manual' THEN 'manual'
    WHEN OperationType = 'Semi-Auto' THEN 'semi-automatic'
    WHEN OperationType = 'Auto' THEN 'automatic'
    ELSE OperationType
END
WHERE OperationType IN ('Manual', 'Semi-Auto', 'Auto');

-- ============================================================================
-- ADD INDEXES FOR PERFORMANCE
-- ============================================================================

-- Add indexes on record_id for better join performance
CREATE INDEX IF NOT EXISTS idx_record_id_moldoperationspecs ON moldoperationspecs(record_id);

-- ============================================================================
-- VERIFICATION QUERIES
-- ============================================================================

-- Run these queries to verify the changes were applied correctly:

-- 1. Check moldoperationspecs table structure
-- DESCRIBE moldoperationspecs;

-- 2. Check moldcloseparameters table structure  
-- DESCRIBE moldcloseparameters;

-- 3. Verify operation type updates
-- SELECT DISTINCT OperationType FROM moldoperationspecs;

-- 4. Check for any records with old cooling media data
-- SELECT COUNT(*) as records_with_old_cooling_media FROM moldoperationspecs WHERE CoolingMedia IS NOT NULL;

-- ============================================================================
-- NOTES FOR DEVELOPERS
-- ============================================================================

/*
FORM CHANGES IMPLEMENTED:
1. ✅ Removed mold heater zone 0 from the form
2. ✅ Added Pressure 4 to mold close section (already exists as Speed 4 was already there)
3. ✅ Updated operation dropdown to: semi-automatic, automatic, robot arm, manual
4. ✅ Split cooling media into stationary and movable with remarks field

DATABASE CHANGES REQUIRED:
1. ✅ Added StationaryCoolingMedia, MovableCoolingMedia, CoolingMediaRemarks to moldoperationspecs
2. ✅ Created moldcloseparameters table with Pressure 4 support
3. ✅ Updated operation type values to match new form options

NEXT STEPS:
1. Update parameters/submit.php to handle the new fields
2. Update any reports/analytics that reference the old field names
3. Test form submission with new fields
4. Update data visualization to show new cooling media structure
*/

-- ============================================================================
-- ROLLBACK SCRIPT (if needed)
-- ============================================================================

/*
-- Uncomment and run these commands if you need to rollback the changes:

-- Restore original CoolingMedia field (if it was dropped)
-- ALTER TABLE moldoperationspecs ADD COLUMN CoolingMedia VARCHAR(255) DEFAULT NULL;
-- UPDATE moldoperationspecs SET CoolingMedia = StationaryCoolingMedia WHERE StationaryCoolingMedia IS NOT NULL;

-- Remove new cooling media fields
-- ALTER TABLE moldoperationspecs DROP COLUMN StationaryCoolingMedia;
-- ALTER TABLE moldoperationspecs DROP COLUMN MovableCoolingMedia; 
-- ALTER TABLE moldoperationspecs DROP COLUMN CoolingMediaRemarks;

-- Drop mold close parameters table
-- DROP TABLE moldcloseparameters;

-- Restore old operation types
-- UPDATE moldoperationspecs SET OperationType = CASE 
--     WHEN OperationType = 'manual' THEN 'Manual'
--     WHEN OperationType = 'semi-automatic' THEN 'Semi-Auto'
--     WHEN OperationType = 'automatic' THEN 'Auto'
--     ELSE OperationType
-- END;
*/
