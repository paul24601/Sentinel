<?php
session_start();

// Load centralized database configuration
require_once __DIR__ . '/includes/database.php';

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch data from the form
$id_number = $_POST['id_number'] ?? '';
$password_input = $_POST['password'] ?? '';

// Prepare and execute the SQL statement to fetch the user by id_number
$sql = "SELECT * FROM users WHERE id_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_number);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    // Fetch the user data
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password_input, $user['password'])) {
        // Password is correct, set session variables
        $_SESSION['id_number'] = $user['id_number'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time(); // Set initial activity timestamp

        // Check if the user has changed their password (password_changed column)
        if ($user['password_changed'] == 0) {
            // If the password hasn't been changed, redirect to change_password.php
            header("Location: change_password.php");
            exit();
        }

        // Redirect based on the user's role if password has already been changed
        header("Location: index.php");
        exit();
    } else {
        // Invalid password
        header("Location: login.html?error=" . urlencode("Invalid password. Please try again."));
        exit();
    }
} else {
    // User not found
    header("Location: login.html?error=" . urlencode("User not found. Please check your ID number and try again."));
    exit();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>