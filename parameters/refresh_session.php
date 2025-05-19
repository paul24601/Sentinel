<?php
session_start();

// Update the session's last activity time
$_SESSION['last_activity'] = time();

// Send success response
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?> 