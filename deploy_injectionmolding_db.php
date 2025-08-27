<?php
/**
 * Remote Database Deployment Script
 * Deploys injectionmoldingparameters.sql to remote shared hosting database
 */

// Remote database configuration - UPDATE THESE VALUES
$remote_host = 'localhost'; // or your actual remote host (check cPanel for correct host)
$remote_username = 'u158529957_spmc_injmold';
$remote_password = 'YOUR_ACTUAL_PASSWORD'; // ⚠️ REPLACE WITH YOUR REAL PASSWORD
$remote_database = 'u158529957_injmold'; // ⚠️ UPDATE WITH YOUR ACTUAL DATABASE NAME

echo "=== Remote Database Deployment Script ===\n";
echo "Target Database: {$remote_database}\n";
echo "Username: {$remote_username}\n\n";

try {
    // Connect to remote database
    echo "1. Connecting to remote database...\n";
    $conn = new mysqli($remote_host, $remote_username, $remote_password, $remote_database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "✅ Connected successfully!\n\n";
    
    // Read the original SQL file
    echo "2. Reading SQL file...\n";
    $sql_file = 'injectionmoldingparameters.sql';
    
    if (!file_exists($sql_file)) {
        throw new Exception("SQL file not found: {$sql_file}");
    }
    
    $sql_content = file_get_contents($sql_file);
    echo "✅ SQL file loaded (" . strlen($sql_content) . " characters)\n\n";
    
    // Clean up the SQL content for remote deployment
    echo "3. Processing SQL for remote deployment...\n";
    
    // Remove problematic statements
    $sql_content = preg_replace('/^--.*$/m', '', $sql_content); // Remove comments
    $sql_content = preg_replace('/\/\*.*?\*\//s', '', $sql_content); // Remove block comments
    $sql_content = str_replace('`injectionmoldingparameters`', "`{$remote_database}`", $sql_content);
    
    // Split into individual statements
    $statements = array_filter(
        explode(';', $sql_content),
        function($stmt) {
            return trim($stmt) !== '' && !preg_match('/^\s*$/', $stmt);
        }
    );
    
    echo "✅ Found " . count($statements) . " SQL statements\n\n";
    
    // Execute statements one by one
    echo "4. Executing SQL statements...\n";
    $success_count = 0;
    $error_count = 0;
    
    foreach ($statements as $index => $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        // Skip SET statements that might cause issues
        if (preg_match('/^SET\s+(?:SQL_MODE|time_zone|.*CHARACTER_SET|.*COLLATION)/i', $statement)) {
            continue;
        }
        
        echo "Executing statement " . ($index + 1) . "... ";
        
        if ($conn->query($statement)) {
            echo "✅ Success\n";
            $success_count++;
        } else {
            echo "❌ Error: " . $conn->error . "\n";
            echo "Statement: " . substr($statement, 0, 100) . "...\n";
            $error_count++;
        }
    }
    
    echo "\n=== Deployment Summary ===\n";
    echo "✅ Successful: {$success_count}\n";
    echo "❌ Errors: {$error_count}\n";
    
    if ($error_count === 0) {
        echo "\n🎉 Deployment completed successfully!\n";
    } else {
        echo "\n⚠️ Deployment completed with errors. Check the output above.\n";
    }
    
    // Test the deployment
    echo "\n5. Testing deployment...\n";
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "✅ Tables in database:\n";
        while ($row = $result->fetch_array()) {
            echo "  - " . $row[0] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

echo "\n=== Instructions for Manual Deployment ===\n";
echo "If this script fails, follow these steps:\n\n";
echo "1. Log into your cPanel/hosting control panel\n";
echo "2. Go to phpMyAdmin\n";
echo "3. Select your database: {$remote_database}\n";
echo "4. Click 'Import' tab\n";
echo "5. Upload the injectionmoldingparameters.sql file\n";
echo "6. Make sure 'Partial import' is unchecked\n";
echo "7. Click 'Go' to import\n\n";

echo "=== Common Issues & Solutions ===\n";
echo "• Access denied: Make sure the database exists and user has privileges\n";
echo "• Wrong database name: Use the full prefixed name (u158529957_xxx)\n";
echo "• Timeout: Import smaller chunks or increase limits\n";
echo "• Character set issues: Ensure UTF8 support is enabled\n";
?>
