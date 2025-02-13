<?php
session_start();

// Database connection (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "Admin123@plvil";
$dbname = "dailymonitoringsheet";

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

        // Check if the user has changed their password (password_changed column)
        if ($user['password_changed'] == 0) {
            // If the password hasn't been changed, redirect to change_password.php
            header("Location: change_password.php");
            exit();
        }

        // Redirect based on the user's role if password has already been changed
        if ($user['role'] == 'supervisor') {
            header("Location: dms/index.php");
        } else if ($user['role'] == 'adjuster') {
            header("Location: dms/index.php");
        } else if ($user['role'] == 'admin') {
            header("Location: dms/index.php");
        }
        exit();
    } else {
        // Invalid password
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Invalid Password</title>
            <!-- Bootstrap CSS -->
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body class='bg-light d-flex align-items-center justify-content-center vh-100'>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-6 offset-md-3'>
                        <div class='alert alert-danger text-center'>
                            <h4 class='alert-heading'>Invalid Password</h4>
                            <p>The password you entered is incorrect. Please try again.</p>
                            <hr>
                            <a href='login.html' class='btn btn-outline-danger'>Return to Login</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bootstrap JS -->
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
        </body>
        </html>";
    }
} else {
    // User not found
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>User Not Found</title>
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light d-flex align-items-center justify-content-center vh-100'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-6 offset-md-3'>
                    <div class='alert alert-warning text-center'>
                        <h4 class='alert-heading'>User Not Found</h4>
                        <p>The ID number you entered does not match any account. Please check and try again.</p>
                        <hr>
                        <a href='login.html' class='btn btn-outline-warning'>Return to Login</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
    </body>
    </html>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
