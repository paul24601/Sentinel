# Database Schema Updates and Form Field Fixes

## Overview
This update fixes the missing form fields that were not being saved to the database and ensures proper "Apply to Form" functionality.

## Step 1: Update Database Schema
Run the SQL file to add missing columns:
```sql
-- Execute this file in your MySQL database
mysql -u root -p injectionmoldingparameters < database_schema_updates_missing_fields.sql
```
Or run the SQL commands directly in phpMyAdmin or your preferred MySQL client.

## Step 2: Files Updated
The following files have been automatically updated:
1. `parameters/submit.php` - Updated to save the new fields
2. `parameters/index.php` - Updated field mappings for cloning functionality

## What Was Fixed

### ✅ **Injection Parameters (Positions 4-6)**
- Added `ScrewPosition4`, `ScrewPosition5`, `ScrewPosition6`
- Added `InjectionSpeed4`, `InjectionSpeed5`, `InjectionSpeed6`  
- Added `InjectionPressure4`, `InjectionPressure5`, `InjectionPressure6`

### ✅ **Ejector Parameters**
- Added `EjectorForwardTime`
- Added `EjectorRetractTime`
- Added `EjectorForwardPressure2`
- Added `EjectorRetractPressure2`

### ✅ **Core Pull Settings**
- Fixed special handling for relational core pull data
- All core pull fields now populate correctly during "Apply to Form"

### ✅ **End Time Recording**
- Fixed end time recording during form submission
- Fixed end time handling when using "Apply to Form" 
- Ensured clean session start when cloning records

## Testing
After running the database update:
1. Submit a form with all field types to verify saving works
2. Use "Apply to Form" to verify all fields populate correctly
3. Check that end time is recorded properly on form submission
4. Verify that cloned forms start with fresh start/end times

## Rollback
If you need to undo the database changes, uncomment and run the rollback queries at the bottom of `database_schema_updates_missing_fields.sql`.

## Summary
All previously non-functional form fields should now:
- ✅ Save to database correctly
- ✅ Populate during "Apply to Form" cloning
- ✅ Have proper end time recording
