<?php
session_start();

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Get database connections
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    $admin_conn = DatabaseManager::getConnection('sentinel_admin');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submissions for adding users, updating access, etc.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        // Add new user logic
        $id_number = $_POST['id_number'];
        $first_name = trim($_POST['first_name']);
        $middle_name = trim($_POST['middle_name']);
        $last_name = trim($_POST['last_name']);

        // Construct full name
        $full_name = $first_name;
        if (!empty($middle_name)) {
            $full_name .= ' ' . $middle_name;
        }
        $full_name .= ' ' . $last_name;

        $role = $_POST['role'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : password_hash('injection', PASSWORD_DEFAULT);
        $departments = isset($_POST['departments']) ? $_POST['departments'] : [];

        // Insert user
        $sql = "INSERT INTO users (id_number, full_name, password, role, password_changed) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $id_number, $full_name, $password, $role);

        if ($stmt->execute()) {
            // Insert department associations
            if (!empty($departments)) {
                foreach ($departments as $dept_id) {
                    $dept_sql = "INSERT INTO user_departments (user_id_number, department_id) VALUES (?, ?)";
                    $dept_stmt = $conn->prepare($dept_sql);
                    $dept_stmt->bind_param("si", $id_number, $dept_id);
                    $dept_stmt->execute();
                }
            }
            echo "<script>alert('User added successfully!'); window.location.href='users.php';</script>";
        } else {
            echo "<script>alert('Error adding user: " . $conn->error . "');</script>";
        }
    }

    if (isset($_POST['update_user_access'])) {
        // Update user department access
        $user_id = $_POST['user_id'];
        $departments = isset($_POST['user_departments']) ? $_POST['user_departments'] : [];

        // Delete existing department associations
        $delete_sql = "DELETE FROM user_departments WHERE user_id_number = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("s", $user_id);
        $delete_stmt->execute();

        // Insert new department associations
        if (!empty($departments)) {
            foreach ($departments as $dept_id) {
                $insert_sql = "INSERT INTO user_departments (user_id_number, department_id) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("si", $user_id, $dept_id);
                $insert_stmt->execute();
            }
        }

        echo "<script>alert('User access updated successfully!'); window.location.href='users.php';</script>";
    }

    if (isset($_POST['reset_user_password_changed'])) {
        // Reset password_changed flag
        $user_id = $_POST['user_id'];
        $sql = "UPDATE users SET password_changed = 0 WHERE id_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Password reset flag updated successfully!'); window.location.href='users.php';</script>";
        } else {
            echo "<script>alert('Error updating password reset flag: " . $conn->error . "');</script>";
        }
    }
}

// Fetch all users from the database
$sql = "SELECT * FROM users ORDER BY full_name";
$result = $conn->query($sql);

// Fetch all departments
$dept_sql = "SELECT * FROM departments ORDER BY name";
$dept_result = $conn->query($dept_sql);
$departments = [];
if ($dept_result && $dept_result->num_rows > 0) {
    while ($dept_row = $dept_result->fetch_assoc()) {
        $departments[] = $dept_row;
    }
}

// Fetch user-department mappings
$user_dept_sql = "SELECT ud.user_id_number, d.name as department_name 
                  FROM user_departments ud 
                  JOIN departments d ON ud.department_id = d.id 
                  ORDER BY ud.user_id_number, d.name";
$user_dept_result = $conn->query($user_dept_sql);
$user_departments_map = [];
if ($user_dept_result && $user_dept_result->num_rows > 0) {
    while ($ud_row = $user_dept_result->fetch_assoc()) {
        $user_departments_map[$ud_row['user_id_number']][] = $ud_row['department_name'];
    }
}

// Include centralized navbar
include '../includes/navbar.php';
?>

                <div class="container-fluid p-4">
                    <h1 class="">Users</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Admin - User Management</li>
                    </ol>
                    <!--FORMS-->
                    <div class="container-fluid">
                        <div class="card shadow mb-5">
                            <div class="card-header">
                                <h2 class="card-title">Add New User</h2>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="add_user" value="1">

                                    <div class="mb-3">
                                        <label for="id_number" class="form-label">ID Number:</label>
                                        <input required type="text" class="form-control" name="id_number">
                                    </div>

                                    <!-- Split Full Name into First, Middle, Last -->
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name:</label>
                                        <input required type="text" class="form-control" name="first_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Middle Name (Optional):</label>
                                        <input type="text" class="form-control" name="middle_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name:</label>
                                        <input required type="text" class="form-control" name="last_name">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password:</label>
                                        <!-- Popover Note -->
                                        <span tabindex="0" class="text-body-secondary" data-bs-toggle="popover"
                                              data-bs-trigger="hover focus"
                                              data-bs-content="Leave blank to set password to 'injection'.">
                                            <i class="bi bi-info-circle"></i>
                                        </span>
                                        <input type="password" class="form-control" name="password">
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role:</label>
                                        <select name="role" class="form-select" required>
                                            <option value="" disabled selected>Select Role</option>
                                            <option value="adjuster">Adjuster</option>
                                            <option value="supervisor">Supervisor</option>
                                            <option value="admin">Admin</option>
                                            <option value="Quality Control Inspection">Quality Control Inspection</option>
                                            <option value="Quality Assurance Engineer">Quality Assurance Engineer</option>
                                            <option value="Quality Assurance Supervisor">Quality Assurance Supervisor</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Departments (select one or more):</label>
                                        <div class="row">
                                            <?php foreach ($departments as $dept): ?>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="departments[]" value="<?= $dept['id'] ?>" id="dept<?= $dept['id'] ?>">
                                                        <label class="form-check-label" for="dept<?= $dept['id'] ?>">
                                                            <?= htmlspecialchars($dept['name']) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Add User</button>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow">
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
                                                <th scope="col">Departments</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                // Output data for each row
                                                while ($row = $result->fetch_assoc()) {
                                                    // Skip the admin user with ID 000000 (Aeron Paul Daliva)
                                                    // and ID 000001 (Mariela Ilustre Segura)
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
                                                        <td>" . (isset($user_departments_map[$row['id_number']]) ? implode(', ', $user_departments_map[$row['id_number']]) : '<span class="text-muted">None</span>') . "</td>
                                                        <td>
                                                            <button class='btn btn-primary btn-sm me-2' onclick='editUserAccess(\"" . $row['id_number'] . "\", \"" . htmlspecialchars($row['full_name']) . "\")'>
                                                                <i class='fas fa-edit'></i> Edit Access
                                                            </button>
                                                            <form method='POST' action='' style='display: inline;'>
                                                                <input type='hidden' name='user_id' value='" . $row['id_number'] . "'>
                                                                <button type='submit' name='reset_user_password_changed' class='btn btn-warning btn-sm'>
                                                                    <i class='fas fa-key'></i> Reset
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2025 Sentinel OJT</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Edit User Access Modal -->
    <div class="modal fade" id="editUserAccessModal" tabindex="-1" aria-labelledby="editUserAccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserAccessModalLabel">Edit User Access</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="mb-3">
                            <label class="form-label"><strong>User:</strong> <span id="edit_user_name"></span></label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department Access (select one or more):</label>
                            <div id="departments_list">
                                <!-- Departments will be loaded dynamically via JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_user_access" class="btn btn-primary">Update Access</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../assets/demo/chart-area-demo.js"></script>
    <script src="../assets/demo/chart-bar-demo.js"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                pageLength: 10,
                responsive: true,
                order: [[0, 'asc']],
                language: {
                    search: "Search users:",
                    lengthMenu: "Show _MENU_ users",
                    info: "Showing _START_ to _END_ of _TOTAL_ users"
                }
            });
        });

        // Store departments data for the modal
        const departments = <?php echo json_encode($departments); ?>;

        // Store current user departments for loading into modal
        const userDepartments = <?php echo json_encode($user_departments_map); ?>;

        function editUserAccess(userId, userName) {
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_user_name').textContent = userName;

            // Clear and populate departments list
            const departmentsList = document.getElementById('departments_list');
            departmentsList.innerHTML = '';

            // Get current user's departments
            const currentUserDepts = userDepartments[userId] || [];

            departments.forEach(dept => {
                const isChecked = currentUserDepts.includes(dept.name) ? 'checked' : '';

                const checkboxHtml = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="user_departments[]" 
                               value="${dept.id}" id="user_dept${dept.id}" ${isChecked}>
                        <label class="form-check-label" for="user_dept${dept.id}">
                            ${dept.name}
                        </label>
                    </div>
                `;
                departmentsList.innerHTML += checkboxHtml;
            });

            new bootstrap.Modal(document.getElementById('editUserAccessModal')).show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>

<?php include '../includes/navbar_close.php'; ?>

</body>
</html>

<?php
// Connection will be closed automatically by DatabaseManager
if (isset($admin_conn)) {
    $admin_conn->close();
}
?>
