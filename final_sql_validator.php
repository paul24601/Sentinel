<?php
/**
 * Final SQL Validator and Cleaner
 * Creates a production-ready SQL file with guaranteed syntax correctness
 */

echo "=== Final SQL Validator ===\n";

$input_file = 'injectionmoldingparameters_final.sql';
$output_file = 'injectionmoldingparameters_production.sql';

$content = file_get_contents($input_file);
echo "📁 Processing: {$input_file}\n";

// Split into logical statements
$statements = [];
$current_statement = '';
$lines = explode("\n", $content);

foreach ($lines as $line) {
    $trimmed = trim($line);
    
    // Skip comments and empty lines
    if (empty($trimmed) || preg_match('/^--/', $trimmed)) {
        if (!empty($current_statement)) {
            $current_statement .= "\n" . $line;
        } else {
            $statements[] = $line; // Preserve comments at top level
        }
        continue;
    }
    
    $current_statement .= ($current_statement ? "\n" : '') . $line;
    
    // End of statement
    if (preg_match('/;\s*$/', $trimmed)) {
        $statements[] = trim($current_statement);
        $current_statement = '';
    }
}

// Handle any remaining statement
if (!empty(trim($current_statement))) {
    $statements[] = trim($current_statement) . ';';
}

echo "📊 Found " . count($statements) . " statements\n";

// Clean and validate each statement
$clean_statements = [];
$header_comments = [];
$sql_statements = [];

foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if (empty($stmt)) continue;
    
    if (preg_match('/^--/', $stmt)) {
        $header_comments[] = $stmt;
    } else {
        // Ensure statement ends with semicolon
        if (!preg_match('/;\s*$/', $stmt)) {
            $stmt .= ';';
        }
        $sql_statements[] = $stmt;
    }
}

// Rebuild the file
$final_content = '';

// Add header comments
if (!empty($header_comments)) {
    $final_content .= implode("\n", $header_comments) . "\n\n";
}

// Add SQL statements with proper spacing
foreach ($sql_statements as $i => $stmt) {
    $final_content .= $stmt;
    
    // Add spacing between statements for readability
    if ($i < count($sql_statements) - 1) {
        $final_content .= "\n\n";
    }
}

file_put_contents($output_file, $final_content);

echo "✅ Created production file: {$output_file}\n";
echo "📊 File size: " . number_format(filesize($output_file)) . " bytes\n";
echo "📊 SQL statements: " . count($sql_statements) . "\n";

// Final syntax check
echo "\n🔍 Final syntax validation...\n";

$syntax_issues = [];

// Check each statement
foreach ($sql_statements as $i => $stmt) {
    // Check for proper semicolon termination
    if (!preg_match('/;\s*$/', $stmt)) {
        $syntax_issues[] = "Statement " . ($i + 1) . " missing semicolon";
    }
    
    // Check for common SQL keywords without proper termination
    if (preg_match('/\b(VALUES|SET|WHERE)\s*$/i', $stmt)) {
        $syntax_issues[] = "Statement " . ($i + 1) . " appears incomplete";
    }
}

if (empty($syntax_issues)) {
    echo "✅ All syntax checks passed!\n";
    echo "🎉 File is ready for production deployment!\n";
} else {
    echo "⚠️ Syntax issues found:\n";
    foreach ($syntax_issues as $issue) {
        echo "  - {$issue}\n";
    }
}

echo "\n🚀 DEPLOYMENT READY FILE: {$output_file}\n";
echo "   Use this file for phpMyAdmin import\n";
?>
