-- Production Report System Database Update Script
-- This script adds the new fields for Finishing and Injection report types

-- First, check if the report_type column exists and add it if it doesn't
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'production_reports'
    AND COLUMN_NAME = 'report_type'
);

SET @sql = IF(@column_exists = 0,
    'ALTER TABLE production_reports ADD COLUMN report_type ENUM(''finishing'', ''injection'') NOT NULL DEFAULT ''finishing'' AFTER id',
    'SELECT "report_type column already exists" as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add injection-specific fields
SET @sql = 'ALTER TABLE production_reports 
ADD COLUMN IF NOT EXISTS injection_pressure DECIMAL(10,2) AFTER total_good,
ADD COLUMN IF NOT EXISTS molding_temp INT AFTER injection_pressure,
ADD COLUMN IF NOT EXISTS cycle_time DECIMAL(10,2) AFTER molding_temp,
ADD COLUMN IF NOT EXISTS cavity_count INT AFTER cycle_time,
ADD COLUMN IF NOT EXISTS cooling_time DECIMAL(10,2) AFTER cavity_count,
ADD COLUMN IF NOT EXISTS holding_pressure DECIMAL(10,2) AFTER cooling_time,
ADD COLUMN IF NOT EXISTS material_type VARCHAR(100) AFTER holding_pressure,
ADD COLUMN IF NOT EXISTS shot_size DECIMAL(10,2) AFTER material_type';

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add finishing-specific fields
SET @sql = 'ALTER TABLE production_reports 
ADD COLUMN IF NOT EXISTS finishing_process VARCHAR(100) AFTER shot_size,
ADD COLUMN IF NOT EXISTS station_number VARCHAR(50) AFTER finishing_process,
ADD COLUMN IF NOT EXISTS work_order VARCHAR(100) AFTER station_number,
ADD COLUMN IF NOT EXISTS finishing_tools VARCHAR(255) AFTER work_order,
ADD COLUMN IF NOT EXISTS quality_standard VARCHAR(255) AFTER finishing_tools';

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS idx_production_reports_type ON production_reports(report_type);
CREATE INDEX IF NOT EXISTS idx_production_reports_type_date ON production_reports(report_type, report_date);

-- Display confirmation message
SELECT 'Production Report database schema updated successfully!' as message;
