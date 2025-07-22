<?php
// User activity logging for debugging unknown user issues
// This file can be included in submit.php to track user submission activities

function logUserActivity($conn, $activity_type, $record_id = null, $additional_info = null) {
    // Create user_activity table if it doesn't exist
    $createTable = "
    CREATE TABLE IF NOT EXISTS user_activity (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id_number VARCHAR(10),
        full_name VARCHAR(255),
        activity_type VARCHAR(100),
        record_id VARCHAR(50),
        session_data TEXT,
        additional_info TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->query($createTable);
    
    // Log the activity
    $sql = "INSERT INTO user_activity (user_id_number, full_name, activity_type, record_id, session_data, additional_info, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    $user_id = $_SESSION['id_number'] ?? 'UNKNOWN';
    $full_name = $_SESSION['full_name'] ?? 'UNKNOWN_USER';
    $session_data = json_encode($_SESSION);
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $stmt->bind_param("ssssssss", $user_id, $full_name, $activity_type, $record_id, $session_data, $additional_info, $ip_address, $user_agent);
    $stmt->execute();
}

// Function to check for unknown users in database
function checkForUnknownUsers($conn) {
    $sql = "SELECT record_id, submitted_by, submission_date FROM parameter_records WHERE submitted_by = '' OR submitted_by IS NULL OR submitted_by = 'UNKNOWN_USER' ORDER BY submission_date DESC";
    $result = $conn->query($sql);
    
    $unknown_records = [];
    while ($row = $result->fetch_assoc()) {
        $unknown_records[] = $row;
    }
    
    return $unknown_records;
}

// Function to fix unknown user records
function fixUnknownUserRecords($conn, $record_ids, $correct_user_name) {
    $sql = "UPDATE parameter_records SET submitted_by = ? WHERE record_id IN (" . str_repeat('?,', count($record_ids) - 1) . "?)";
    $stmt = $conn->prepare($sql);
    
    $types = str_repeat('s', count($record_ids) + 1);
    $params = array_merge([$correct_user_name], $record_ids);
    
    $stmt->bind_param($types, ...$params);
    return $stmt->execute();
}
?>
