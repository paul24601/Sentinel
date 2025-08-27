<?php
/**
 * SQL File Cleaner for Remote Hosting
 * Processes injectionmoldingparameters.sql for remote deployment
 */

echo "=== SQL File Cleaner for Remote Hosting ===\n";

$input_file = 'injectionmoldingparameters.sql';
$output_file = 'injectionmoldingparameters_cleaned.sql';

if (!file_exists($input_file)) {
    die("❌ Error: {$input_file} not found!\n");
}

echo "📁 Reading input file: {$input_file}\n";
$content = file_get_contents($input_file);
$original_size = strlen($content);

echo "🧹 Cleaning SQL content...\n";

// Remove problematic statements for shared hosting
$content = preg_replace('/^--.*$/m', '', $content); // Remove comments
$content = preg_replace('/\/\*.*?\*\//s', '', $content); // Remove block comments
$content = preg_replace('/^SET\s+.*$/m', '', $content); // Remove SET statements
$content = preg_replace('/^START TRANSACTION.*$/m', '', $content); // Remove transaction
$content = preg_replace('/^COMMIT.*$/m', '', $content); // Remove commit
$content = preg_replace('/^.*AUTO_INCREMENT=\d+.*$/m', '', $content); // Remove auto increment declarations

// Make CREATE TABLE statements safer
$content = preg_replace('/CREATE TABLE\s+`([^`]+)`/', 'CREATE TABLE IF NOT EXISTS `$1`', $content);

// Clean up multiple newlines
$content = preg_replace('/\n\s*\n+/', "\n\n", $content);
$content = trim($content);

// Add header
$header = "-- Injection Molding Parameters Database\n";
$header .= "-- Cleaned for remote hosting deployment\n";
$header .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
$header .= "-- Original file size: " . number_format($original_size) . " bytes\n";
$header .= "-- \n";
$header .= "-- INSTRUCTIONS:\n";
$header .= "-- 1. Create database in cPanel (e.g., 'injmold' becomes 'u158529957_injmold')\n";
$header .= "-- 2. Import this file via phpMyAdmin\n";
$header .= "-- 3. Ensure user has ALL PRIVILEGES on the database\n\n";

$content = $header . $content;

file_put_contents($output_file, $content);
$cleaned_size = strlen($content);

echo "✅ Cleaning complete!\n";
echo "📊 Original size: " . number_format($original_size) . " bytes\n";
echo "📊 Cleaned size: " . number_format($cleaned_size) . " bytes\n";
echo "📊 Size reduction: " . number_format($original_size - $cleaned_size) . " bytes\n";
echo "💾 Output file: {$output_file}\n\n";

// Validate the cleaned SQL
echo "🔍 Validating cleaned SQL...\n";
$statements = explode(';', $content);
$create_tables = 0;
$inserts = 0;
$alters = 0;

foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if (empty($stmt)) continue;
    
    if (preg_match('/^CREATE TABLE/i', $stmt)) {
        $create_tables++;
    } elseif (preg_match('/^INSERT INTO/i', $stmt)) {
        $inserts++;
    } elseif (preg_match('/^ALTER TABLE/i', $stmt)) {
        $alters++;
    }
}

echo "📋 Found statements:\n";
echo "  - CREATE TABLE: {$create_tables}\n";
echo "  - INSERT INTO: {$inserts}\n";
echo "  - ALTER TABLE: {$alters}\n";

echo "\n🎯 Next Steps:\n";
echo "1. Upload {$output_file} to your hosting\n";
echo "2. Create database in cPanel: 'injmold' (becomes u158529957_injmold)\n";
echo "3. Assign user u158529957_spmc_injmold with ALL PRIVILEGES\n";
echo "4. Import via phpMyAdmin\n";
echo "5. Update your application's database configuration\n";
?>
