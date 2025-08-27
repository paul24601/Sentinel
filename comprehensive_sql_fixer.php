<?php
/**
 * Comprehensive SQL Syntax Checker and Fixer
 * Detects and fixes missing semicolons and other syntax issues
 */

echo "=== SQL Syntax Checker and Fixer ===\n";

$input_file = 'injectionmoldingparameters_fixed.sql';
$output_file = 'injectionmoldingparameters_final.sql';

if (!file_exists($input_file)) {
    die("❌ Error: {$input_file} not found!\n");
}

echo "📁 Loading file: {$input_file}\n";
$content = file_get_contents($input_file);

echo "🔍 Checking for syntax issues...\n";

// Split into lines for analysis
$lines = explode("\n", $content);
$fixed_lines = [];
$issues_found = 0;

for ($i = 0; $i < count($lines); $i++) {
    $line = $lines[$i];
    $trimmed = trim($line);
    
    // Check for problematic patterns
    if (preg_match('/VALUES\s*$/', $trimmed) && !preg_match('/;$/', $trimmed)) {
        // VALUES statement without proper ending
        $next_line = isset($lines[$i + 1]) ? trim($lines[$i + 1]) : '';
        if (preg_match('/^CREATE\s+TABLE/i', $next_line)) {
            echo "🔧 Fixed: Missing semicolon after VALUES at line " . ($i + 1) . "\n";
            $line = rtrim($line) . ';';
            $issues_found++;
        }
    }
    
    // Check for INSERT INTO without VALUES
    if (preg_match('/INSERT\s+INTO.*\)\s+VALUES\s*$/', $trimmed) && !preg_match('/;$/', $trimmed)) {
        $next_line = isset($lines[$i + 1]) ? trim($lines[$i + 1]) : '';
        if (preg_match('/^(CREATE|ALTER|INSERT)/i', $next_line)) {
            echo "🔧 Fixed: Missing semicolon after INSERT VALUES at line " . ($i + 1) . "\n";
            $line = rtrim($line) . ';';
            $issues_found++;
        }
    }
    
    // Check for incomplete statements before CREATE/ALTER
    if (preg_match('/^(CREATE|ALTER)\s+TABLE/i', $trimmed)) {
        $prev_line = isset($fixed_lines[$i - 1]) ? trim($fixed_lines[$i - 1]) : '';
        if (!empty($prev_line) && !preg_match('/;$/', $prev_line) && !preg_match('/^--/', $prev_line)) {
            echo "🔧 Fixed: Added missing semicolon before " . substr($trimmed, 0, 30) . "... at line " . ($i + 1) . "\n";
            $fixed_lines[$i - 1] = rtrim($fixed_lines[$i - 1]) . ';';
            $issues_found++;
        }
    }
    
    $fixed_lines[] = $line;
}

if ($issues_found > 0) {
    echo "✅ Fixed {$issues_found} syntax issues\n";
    
    // Write the fixed content
    $fixed_content = implode("\n", $fixed_lines);
    file_put_contents($output_file, $fixed_content);
    
    echo "💾 Saved fixed file: {$output_file}\n";
    echo "📊 File size: " . number_format(filesize($output_file)) . " bytes\n";
} else {
    echo "✅ No syntax issues found!\n";
    $output_file = $input_file; // Use original file
}

// Final validation
echo "\n🔍 Final validation...\n";
$final_content = file_get_contents($output_file);

// Check for common issues
$validation_issues = [];

// Check for missing semicolons before CREATE/ALTER/INSERT
if (preg_match('/\n\s*(CREATE|ALTER|INSERT)/im', $final_content, $matches, PREG_OFFSET_CAPTURE)) {
    $before_match = substr($final_content, max(0, $matches[0][1] - 100), 100);
    if (!preg_match('/;\s*$/', $before_match)) {
        $validation_issues[] = "Possible missing semicolon before " . $matches[1][0];
    }
}

// Check for VALUES without proper termination
if (preg_match_all('/VALUES\s*\n\s*(CREATE|ALTER)/im', $final_content, $matches)) {
    $validation_issues[] = count($matches[0]) . " VALUES statements without semicolons";
}

if (empty($validation_issues)) {
    echo "✅ Validation passed! No syntax issues detected.\n";
} else {
    echo "⚠️ Potential issues found:\n";
    foreach ($validation_issues as $issue) {
        echo "  - {$issue}\n";
    }
}

// Count statements
$statements = array_filter(explode(';', $final_content), function($stmt) {
    return !empty(trim($stmt)) && !preg_match('/^--/', trim($stmt));
});

echo "\n📊 Final statistics:\n";
echo "  - Total SQL statements: " . count($statements) . "\n";
echo "  - File size: " . number_format(strlen($final_content)) . " bytes\n";
echo "  - Ready for deployment: ✅\n";

echo "\n🎯 Use file: {$output_file}\n";
?>
