<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    http_response_code(401); // Unauthorized
    exit('Not logged in');
}

// Refresh the session
session_regenerate_id(true);

// Update the session timestamp
$_SESSION['last_activity'] = time();

// Return success response
http_response_code(200);
echo json_encode(['status' => 'success']);
?> 