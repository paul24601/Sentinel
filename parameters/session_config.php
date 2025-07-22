<?php
// Set session timeout to 30 minutes
ini_set('session.gc_maxlifetime', 1800); // 30 minutes in seconds
ini_set('session.cookie_lifetime', 1800);

// Start the session if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enhanced session validation
function validateSession() {
    // Check if essential session variables exist
    if (!isset($_SESSION['full_name']) || !isset($_SESSION['id_number']) || !isset($_SESSION['role'])) {
        return false;
    }
    
    // Check if session variables are empty
    if (empty(trim($_SESSION['full_name'])) || empty(trim($_SESSION['id_number']))) {
        return false;
    }
    
    return true;
}

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Session has expired, destroy it
    session_unset();
    session_destroy();
    header('Location: ../login.html?error=' . urlencode('Session expired. Please log in again.'));
    exit();
}

// Validate session integrity
if (isset($_SESSION['full_name']) && !validateSession()) {
    // Session is corrupted, destroy it
    error_log("Corrupted session detected: " . json_encode($_SESSION));
    session_unset();
    session_destroy();
    header('Location: ../login.html?error=' . urlencode('Session corrupted. Please log in again.'));
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();
?> 