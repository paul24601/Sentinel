<?php
session_start();


// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Access Denied</title>
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light d-flex align-items-center justify-content-center vh-100'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-6 offset-md-3'>
                    <div class='alert alert-danger text-center'>
                        <h4 class='alert-heading'>Access Denied</h4>
                        <p>You must be an admin to view this page.</p>
                        <hr>
                        <button onclick='goBack()' class='btn btn-outline-danger'>Return to Previous Page</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
        <!-- JavaScript to go back to the previous page -->
        <script>
            function goBack() {
                window.history.back();
            }
        </script>
    </body>
    </html>";
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "Admin123@plvil";
$dbname = "dailymonitoringsheet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div class='alert alert-danger'>Connection failed: " . $conn->connect_error . "</div>");
}

// Handle the form submission to add a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $id_number = $_POST['id_number'];
    $full_name = $_POST['full_name'];
    $password = !empty($_POST['password']) ? $_POST['password'] : "injection";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    $sql = "INSERT INTO users (id_number, full_name, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $id_number, $full_name, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                User added successfully!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Error: " . $stmt->error . "
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }


    $stmt->close();
}


// Handle individual password_changed reset and set the password to 'injection'
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_user_password_changed'])) {
    $user_id = $_POST['user_id'];

    // Securely hash the new password 'injection'
    $new_password = password_hash("injection", PASSWORD_DEFAULT);

    // Update the password and password_changed value to 0 for the specific user
    $sql = "UPDATE users SET password = ?, password_changed = 0 WHERE id_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $new_password, $user_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Password reset successfully to 'injection' for user ID: $user_id.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Error resetting password: " . $stmt->error . "
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }


    $stmt->close();
}


// Fetch all users from the database
$sql = "SELECT id_number, full_name, role, password_changed FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Bootstrap JS (with Popper.js for tooltip and popover positioning) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-..."
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-..."
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

</head>

<body class="bg-primary-subtle">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Admin Dashboard</h1>

        <div class="card shadow mb-5">
            <div class="card-header">
                <h2 class="card-title">Add New User</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input required type="hidden" name="add_user" value="1">
                    <div class="mb-3">
                        <label for="id_number" class="form-label">ID Number:</label>
                        <input required type="text" class="form-control" name="id_number" required>
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name:</label>
                        <input required type="text" class="form-control" name="full_name" required>
                    </div>

                    <div class="mb-3">

                        <label for="password" class="form-label">Password:</label>
                        <!-- Popover Note -->
                        <span tabindex="0" class="text-body-secondary" data-bs-toggle="popover"
                            data-bs-trigger="hover focus"
                            data-bs-content="Leave the input required blank to set password to default.">
                            <i class="bi bi-info-circle"></i>
                        </span>
                        <input required type="password" class="form-control" name="password">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role:</label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="adjuster">Adjuster</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-5">
            <div class="card-header">
                <h2 class="card-title">Existing Users</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped border">
                        <thead>
                            <tr>
                                <th scope="col">ID Number</th>
                                <th scope="col">Full Name</th>
                                <th scope="col">Role</th>
                                <th scope="col">Password Changed</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                // Output data for each row
                                while ($row = $result->fetch_assoc()) {
                                    // Skip the admin user with ID 000000 and Mariela Ilustre Segura with ID 000001
                                    if (
                                        ($row['id_number'] == '000000' && $row['full_name'] == 'Aeron Paul Daliva') ||
                                        ($row['id_number'] == '000001' && $row['full_name'] == 'Mariela Ilustre Segura')
                                    ) {
                                        continue;
                                    }
                                    echo "<tr>
                                <td>" . $row['id_number'] . "</td>
                                <td>" . $row['full_name'] . "</td>
                                <td>" . $row['role'] . "</td>
                                <td>" . ($row['password_changed'] ? 'Yes' : 'No') . "</td>
                                <td>
                                    <form method='POST' action=''>
                                        <input required type='hidden' name='user_id' value='" . $row['id_number'] . "'>
                                        <button type='submit' name='reset_user_password_changed' class='btn btn-warning btn-sm'>Reset</button>
                                    </form>
                                </td>
                            </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
                            }
                            ?>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>

        <div class="card shadow mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="d-grid col-12 col-md-6">
                        <a href="admin.php" class="btn btn-primary mt-3">View DMS Analytics</a>
                    </div>
                    <div class="d-grid col-12 col-md-6">
                        <a href="dms/process_form.php" class="btn btn-secondary mt-3">View Submitted Records</a>
                    </div>
                </div>
                <div class="row">
                    <div class="d-grid col-12 col-md-6">
                        <!-- Button to redirect to DMS -->
                        <a href="dms/index.php" class="btn btn-info mt-3">Go to Form</a>
                    </div>
                    <div class="d-grid col-12 col-md-6">
                        <!-- Logout Button -->
                        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>

</body>

</html>

<?php
$conn->close();
?>