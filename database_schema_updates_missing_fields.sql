-- ========================================
-- Database Schema Updates for Missing Fields
-- Date: August 6, 2025
-- Purpose: Add missing fields to support all form inputs
-- ========================================

-- 1. Add missing columns to ejectionparameters table
ALTER TABLE `ejectionparameters` 
ADD COLUMN `EjectorForwardTime` float DEFAULT NULL AFTER `EjectorRetractPressure1`,
ADD COLUMN `EjectorRetractTime` float DEFAULT NULL AFTER `EjectorForwardTime`,
ADD COLUMN `EjectorForwardPressure2` float DEFAULT NULL AFTER `EjectorRetractTime`,
ADD COLUMN `EjectorRetractPressure2` float DEFAULT NULL AFTER `EjectorForwardPressure2`;

-- 2. Add missing columns to injectionparameters table (positions 4-6)
ALTER TABLE `injectionparameters` 
ADD COLUMN `ScrewPosition4` float DEFAULT NULL AFTER `HoldingTime3`,
ADD COLUMN `ScrewPosition5` float DEFAULT NULL AFTER `ScrewPosition4`,
ADD COLUMN `ScrewPosition6` float DEFAULT NULL AFTER `ScrewPosition5`,
ADD COLUMN `InjectionSpeed4` float DEFAULT NULL AFTER `ScrewPosition6`,
ADD COLUMN `InjectionSpeed5` float DEFAULT NULL AFTER `InjectionSpeed4`,
ADD COLUMN `InjectionSpeed6` float DEFAULT NULL AFTER `InjectionSpeed5`,
ADD COLUMN `InjectionPressure4` float DEFAULT NULL AFTER `InjectionSpeed6`,
ADD COLUMN `InjectionPressure5` float DEFAULT NULL AFTER `InjectionPressure4`,
ADD COLUMN `InjectionPressure6` float DEFAULT NULL AFTER `InjectionPressure5`;

-- ========================================
-- Verification queries (optional - run to check the updates)
-- ========================================

-- Check ejectionparameters table structure
-- SHOW COLUMNS FROM `ejectionparameters`;

-- Check injectionparameters table structure  
-- SHOW COLUMNS FROM `injectionparameters`;

-- ========================================
-- Rollback queries (in case you need to undo)
-- ========================================

/*
-- To rollback ejectionparameters changes:
ALTER TABLE `ejectionparameters` 
DROP COLUMN `EjectorForwardTime`,
DROP COLUMN `EjectorRetractTime`,
DROP COLUMN `EjectorForwardPressure2`,
DROP COLUMN `EjectorRetractPressure2`;

-- To rollback injectionparameters changes:
ALTER TABLE `injectionparameters` 
DROP COLUMN `ScrewPosition4`,
DROP COLUMN `ScrewPosition5`,
DROP COLUMN `ScrewPosition6`,
DROP COLUMN `InjectionSpeed4`,
DROP COLUMN `InjectionSpeed5`,
DROP COLUMN `InjectionSpeed6`,
DROP COLUMN `InjectionPressure4`,
DROP COLUMN `InjectionPressure5`,
DROP COLUMN `InjectionPressure6`;
*/
