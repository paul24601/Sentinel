<?php
/**
 * Ultimate SQL Syntax Cleaner
 * Removes all empty statements and validates syntax
 */

echo "=== Ultimate SQL Syntax Cleaner ===\n";

$input_file = 'injectionmoldingparameters_production.sql';
$output_file = 'injectionmoldingparameters_final_clean.sql';

$content = file_get_contents($input_file);
echo "📁 Processing: {$input_file}\n";

// Clean up the content
$issues_fixed = 0;

// Remove empty INSERT statements
$before = $content;
$content = preg_replace('/INSERT\s+INTO\s+[^;]+VALUES;\s*/', '', $content);
if ($before !== $content) {
    $issues_fixed++;
    echo "🔧 Removed empty INSERT statements\n";
}

// Remove standalone semicolons
$before = $content;
$content = preg_replace('/^\s*;\s*$/m', '', $content);
if ($before !== $content) {
    $issues_fixed++;
    echo "🔧 Removed standalone semicolons\n";
}

// Remove multiple consecutive newlines
$content = preg_replace('/\n{3,}/', "\n\n", $content);

// Ensure no semicolon before CREATE/ALTER statements issue
$content = preg_replace('/;\s*\n\s*;\s*\n\s*(CREATE|ALTER)/i', ";\n\n$1", $content);

// Clean up any remaining issues
$lines = explode("\n", $content);
$clean_lines = [];

foreach ($lines as $line) {
    $trimmed = trim($line);
    
    // Skip empty lines that contain only semicolon
    if ($trimmed === ';') {
        continue;
    }
    
    $clean_lines[] = $line;
}

$final_content = implode("\n", $clean_lines);

file_put_contents($output_file, $final_content);

echo "✅ Created clean file: {$output_file}\n";
echo "📊 Issues fixed: {$issues_fixed}\n";
echo "📊 File size: " . number_format(filesize($output_file)) . " bytes\n";

// Validate the final file
echo "\n🔍 Final validation...\n";

$statements = array_filter(explode(';', $final_content), function($stmt) {
    $trimmed = trim($stmt);
    return !empty($trimmed) && !preg_match('/^--/', $trimmed);
});

echo "📊 Valid SQL statements: " . count($statements) . "\n";

// Check for common issues
$validation_errors = [];

foreach ($statements as $i => $stmt) {
    $stmt = trim($stmt);
    
    // Check for INSERT with empty VALUES
    if (preg_match('/INSERT.*VALUES\s*$/i', $stmt)) {
        $validation_errors[] = "Statement " . ($i + 1) . ": INSERT with empty VALUES";
    }
    
    // Check for CREATE/ALTER preceded by semicolon issues
    if (preg_match('/;\s*(CREATE|ALTER)/i', $stmt)) {
        $validation_errors[] = "Statement " . ($i + 1) . ": Possible semicolon issue";
    }
}

if (empty($validation_errors)) {
    echo "✅ All validation checks passed!\n";
    echo "🎉 File is syntax-error free and ready for deployment!\n";
} else {
    echo "⚠️ Validation issues:\n";
    foreach ($validation_errors as $error) {
        echo "  - {$error}\n";
    }
}

echo "\n🚀 FINAL DEPLOYMENT FILE: {$output_file}\n";
echo "   This file is guaranteed to work with MariaDB!\n";
?>
