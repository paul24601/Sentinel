<?php
// Database setup script for wizard autosave functionality
require_once __DIR__ . '/../includes/database.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_main');
    
    // Create wizard_autosave table
    $sql = "CREATE TABLE IF NOT EXISTS wizard_autosave (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(255) NOT NULL,
        wizard_data LONGTEXT NOT NULL,
        current_step INT DEFAULT 1,
        last_saved TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user (user_id),
        INDEX idx_user_saved (user_id, last_saved)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql)) {
        echo "✅ wizard_autosave table created successfully\n";
    } else {
        echo "❌ Error creating wizard_autosave table: " . $conn->error . "\n";
    }
    
    // Create index for better performance
    $sql = "CREATE INDEX IF NOT EXISTS idx_last_saved ON wizard_autosave (last_saved)";
    if ($conn->query($sql)) {
        echo "✅ Index created successfully\n";
    } else {
        echo "❌ Error creating index: " . $conn->error . "\n";
    }
    
    echo "\n🎉 Wizard autosave database setup completed!\n";
    
} catch (Exception $e) {
    echo "❌ Database setup error: " . $e->getMessage() . "\n";
}
?>
