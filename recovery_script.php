<?php
/**
 * Quick Recovery Script for Centralized Navbar Implementation
 * This script will help restore all pages to use the centralized navbar system
 */

// List of files to convert
$filesToConvert = [
    'dms/index.php',
    'dms/submission.php', 
    'dms/analytics.php',
    'parameters/index.php',
    'parameters/submission.php',
    'parameters/analytics.php'
];

echo "=== Sentinel Centralized Navbar Recovery Script ===\n";
echo "This script will restore the centralized navbar implementation.\n\n";

foreach ($filesToConvert as $file) {
    echo "Processing: $file\n";
    
    if (!file_exists($file)) {
        echo "  ❌ File not found: $file\n";
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Backup original file
    $backupFile = $file . '.backup_' . date('Y-m-d_H-i-s');
    file_put_contents($backupFile, $content);
    echo "  📁 Backup created: $backupFile\n";
    
    // Convert the file
    $converted = convertToNavbarSystem($content, $file);
    
    if ($converted !== false) {
        file_put_contents($file, $converted);
        echo "  ✅ Successfully converted: $file\n";
    } else {
        echo "  ❌ Failed to convert: $file\n";
    }
    
    echo "\n";
}

echo "Recovery complete! Check each file and test the pages.\n";

function convertToNavbarSystem($content, $filename) {
    // Step 1: Update PHP header
    $pattern1 = '/^<\?php\s*(\/\/.*\n)*\s*date_default_timezone_set.*?\n\s*session_start\(\);.*?\n/ms';
    $replacement1 = "<?php\nrequire_once 'session_config.php';\n\n// Load centralized database configuration\nrequire_once __DIR__ . '/../includes/database.php';\nrequire_once __DIR__ . '/../includes/admin_notifications.php';\n\n";
    
    // Step 2: Replace HTML structure with navbar include
    $pattern2 = '/<!DOCTYPE html>.*?<body[^>]*>/ms';
    $replacement2 = "\n// Include centralized navbar\ninclude '../includes/navbar.php';\n?>\n\n    <main class=\"main-content\">";
    
    // Step 3: Add closing structure
    $pattern3 = '/(<\/body>\s*<\/html>)/ms';
    $replacement3 = "    </main>\n\n<?php include '../includes/navbar_close.php'; ?>";
    
    // Apply transformations
    $result = preg_replace($pattern1, $replacement1, $content);
    $result = preg_replace($pattern2, $replacement2, $result);
    $result = preg_replace($pattern3, $replacement3, $result);
    
    return $result;
}
?>
