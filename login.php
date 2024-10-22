<?php
session_start();

// Database connection (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "production_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

        // Redirect based on the user's role
        if ($user['role'] == 'supervisor') {
            header("Location: admin.php");
        } else if ($user['role'] == 'adjuster') {
            header("Location: dms/index.php");
        } else if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        }
        exit();
    } else {
        // Invalid password
        echo "Invalid password. <a href='login.html'>Try again</a>";
    }
} else {
    // User not found
    echo "User not found. <a href='login.html'>Try again</a>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
