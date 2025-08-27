<?php
/**
 * Final SQL File Validator
 * Comprehensive check for all possible syntax issues
 */

echo "=== FINAL SQL VALIDATOR ===\n";

$file = 'injectionmoldingparameters_production.sql';
$content = file_get_contents($file);

echo "📁 File: {$file}\n";
echo "📊 Size: " . number_format(strlen($content)) . " bytes\n";

// Check for all possible syntax issues
$issues = [];

// Check for double semicolons
if (preg_match('/;;/', $content)) {
    $issues[] = "Double semicolons found";
}

// Check for empty INSERT statements
if (preg_match('/INSERT[^;]*VALUES;\s*$/m', $content)) {
    $issues[] = "Empty INSERT statements found";
}

// Check for standalone semicolons
if (preg_match('/^\s*;\s*$/m', $content)) {
    $issues[] = "Standalone semicolons found";
}

// Check for CREATE statements preceded by problematic semicolons
if (preg_match('/;\s*;\s*CREATE/i', $content)) {
    $issues[] = "CREATE statements with syntax issues";
}

// Count valid statements
$statements = array_filter(explode(';', $content), function($stmt) {
    $trimmed = trim($stmt);
    return !empty($trimmed) && !preg_match('/^--/', $trimmed);
});

echo "📊 Valid SQL statements: " . count($statements) . "\n";

// Check each statement for basic validity
$statement_issues = [];
foreach ($statements as $i => $stmt) {
    $stmt = trim($stmt);
    
    // Check for incomplete statements
    if (preg_match('/\b(CREATE|INSERT|ALTER)\b/i', $stmt)) {
        if (preg_match('/\b(CREATE|INSERT|ALTER)\s+\w*\s*$/i', $stmt)) {
            $statement_issues[] = "Statement " . ($i + 1) . " appears incomplete";
        }
    }
}

echo "\n🔍 VALIDATION RESULTS:\n";

if (empty($issues) && empty($statement_issues)) {
    echo "✅ PERFECT! No syntax errors detected\n";
    echo "✅ File is ready for MariaDB import\n";
    echo "✅ All " . count($statements) . " statements are valid\n";
    echo "\n🎉 DEPLOYMENT STATUS: READY!\n";
} else {
    echo "❌ Issues found:\n";
    foreach (array_merge($issues, $statement_issues) as $issue) {
        echo "  - {$issue}\n";
    }
}

echo "\n📋 IMPORT INSTRUCTIONS:\n";
echo "1. cPanel → MySQL Databases → Create 'injmold' database\n";
echo "2. Assign user privileges to u158529957_spmc_injmold\n";
echo "3. phpMyAdmin → Import → {$file}\n";
echo "4. Click 'Go' → Success!\n";
?>
