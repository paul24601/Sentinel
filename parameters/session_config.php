<?php
// Set session timeout to 30 minutes
ini_set('session.gc_maxlifetime', 1800); // 30 minutes in seconds
ini_set('session.cookie_lifetime', 1800);

// Start the session if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Session has expired, destroy it
    session_unset();
    session_destroy();
    header('Location: ../login.html');
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();
?> 