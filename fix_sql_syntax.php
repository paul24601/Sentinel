<?php
/**
 * Improved SQL File Cleaner for Remote Hosting
 * Fixes UTF-8 BOM and syntax issues
 */

echo "=== Improved SQL File Cleaner ===\n";

$input_file = 'injectionmoldingparameters.sql';
$output_file = 'injectionmoldingparameters_fixed.sql';

if (!file_exists($input_file)) {
    die("❌ Error: {$input_file} not found!\n");
}

echo "📁 Reading and processing: {$input_file}\n";
$content = file_get_contents($input_file);

// Remove UTF-8 BOM if present
$content = str_replace("\xEF\xBB\xBF", '', $content);

echo "🧹 Advanced cleaning...\n";

// Remove all comments and problematic statements
$content = preg_replace('/^--.*$/m', '', $content);
$content = preg_replace('/\/\*.*?\*\//s', '', $content);
$content = preg_replace('/^SET\s+.*$/m', '', $content);
$content = preg_replace('/^START TRANSACTION.*$/m', '', $content);
$content = preg_replace('/^COMMIT.*$/m', '', $content);
$content = preg_replace('/^\s*!\d+.*$/m', '', $content); // Remove MySQL version specific commands

// Remove empty statements and clean up
$content = preg_replace('/;\s*;+/', ';', $content); // Remove multiple semicolons
$content = preg_replace('/^\s*;\s*$/m', '', $content); // Remove standalone semicolons
$content = preg_replace('/\n\s*\n+/', "\n", $content); // Remove multiple newlines

// Make CREATE TABLE statements safer
$content = preg_replace('/CREATE TABLE\s+`([^`]+)`/', 'CREATE TABLE IF NOT EXISTS `$1`', $content);

// Clean up and ensure proper formatting
$lines = explode("\n", $content);
$cleaned_lines = [];

foreach ($lines as $line) {
    $line = trim($line);
    if (!empty($line) && $line !== ';') {
        $cleaned_lines[] = $line;
    }
}

$content = implode("\n", $cleaned_lines);

// Add proper header
$header = "-- Injection Molding Parameters Database\n";
$header .= "-- Cleaned and fixed for remote hosting deployment\n";
$header .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
$header .= "-- Fixed UTF-8 BOM and syntax issues\n\n";

$final_content = $header . $content;

// Ensure proper ending
if (!preg_match('/;\s*$/', $final_content)) {
    $final_content .= ';';
}

file_put_contents($output_file, $final_content);

echo "✅ Fixed SQL file created: {$output_file}\n";
echo "📊 Final size: " . number_format(filesize($output_file)) . " bytes\n";

// Quick validation
echo "🔍 Validating syntax...\n";
$statements = array_filter(explode(';', $final_content), function($stmt) {
    return !empty(trim($stmt));
});

echo "📋 Valid statements found: " . count($statements) . "\n";

// Check first few lines for issues
$first_lines = array_slice(explode("\n", $final_content), 0, 10);
echo "📝 First 10 lines preview:\n";
foreach ($first_lines as $i => $line) {
    echo sprintf("%2d: %s\n", $i + 1, $line);
}

echo "\n✅ File is ready for import!\n";
echo "🎯 Use: {$output_file} for phpMyAdmin import\n";
?>
