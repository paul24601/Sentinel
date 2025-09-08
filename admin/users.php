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

                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">User Management</h1>
                            <p class="mb-0 text-muted">Manage system users, roles, and department access</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary fs-6 me-2">
                                <i class="fas fa-users me-1"></i>
                                <?php echo $result->num_rows; ?> Users
                            </span>
                        </div>
                    </div>

                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">User Management</li>
                    </ol>

                    <!-- Add New User Card -->
                    <div class="card shadow-lg mb-4" style="border: none; border-radius: 15px; overflow: hidden;">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="d-flex align-items-center text-white">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-white bg-opacity-20 rounded-circle p-2">
                                        <i class="fas fa-user-plus fa-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold">Add New User</h4>
                                    <small class="opacity-75">Create a new system user account</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4" style="background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);">
                            <form method="POST" action="" class="needs-validation" novalidate>
                                <input type="hidden" name="add_user" value="1">

                                <div class="row g-3">
                                    <!-- ID Number -->
                                    <div class="col-md-6">
                                        <label for="id_number" class="form-label fw-semibold">
                                            <i class="fas fa-id-card text-primary me-1"></i>ID Number
                                        </label>
                                        <input required type="text" class="form-control form-control-lg" 
                                               name="id_number" id="id_number" 
                                               placeholder="Enter employee ID">
                                        <div class="invalid-feedback">Please provide a valid ID number.</div>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-6">
                                        <label for="role" class="form-label fw-semibold">
                                            <i class="fas fa-user-tag text-primary me-1"></i>Role
                                        </label>
                                        <select name="role" class="form-select form-select-lg" id="role" required>
                                            <option value="" disabled selected>Select Role</option>
                                            <option value="adjuster">Adjuster</option>
                                            <option value="supervisor">Supervisor</option>
                                            <option value="admin">Admin</option>
                                            <option value="Quality Control Inspection">Quality Control Inspection</option>
                                            <option value="Quality Assurance Engineer">Quality Assurance Engineer</option>
                                            <option value="Quality Assurance Supervisor">Quality Assurance Supervisor</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a role.</div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <!-- First Name -->
                                    <div class="col-md-4">
                                        <label for="first_name" class="form-label fw-semibold">
                                            <i class="fas fa-user text-primary me-1"></i>First Name
                                        </label>
                                        <input required type="text" class="form-control form-control-lg" 
                                               name="first_name" id="first_name" 
                                               placeholder="First name">
                                        <div class="invalid-feedback">Please provide a first name.</div>
                                    </div>

                                    <!-- Middle Name -->
                                    <div class="col-md-4">
                                        <label for="middle_name" class="form-label fw-semibold">
                                            <i class="fas fa-user text-secondary me-1"></i>Middle Name
                                            <small class="text-muted">(Optional)</small>
                                        </label>
                                        <input type="text" class="form-control form-control-lg" 
                                               name="middle_name" id="middle_name" 
                                               placeholder="Middle name">
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-md-4">
                                        <label for="last_name" class="form-label fw-semibold">
                                            <i class="fas fa-user text-primary me-1"></i>Last Name
                                        </label>
                                        <input required type="text" class="form-control form-control-lg" 
                                               name="last_name" id="last_name" 
                                               placeholder="Last name">
                                        <div class="invalid-feedback">Please provide a last name.</div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <!-- Password -->
                                    <div class="col-md-12">
                                        <label for="password" class="form-label fw-semibold">
                                            <i class="fas fa-lock text-primary me-1"></i>Password
                                            <span tabindex="0" class="text-info ms-2" data-bs-toggle="popover"
                                                  data-bs-trigger="hover focus" data-bs-placement="top"
                                                  data-bs-content="Leave blank to set password to 'injection'."
                                                  style="cursor: help;">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        </label>
                                        <input type="password" class="form-control form-control-lg" 
                                               name="password" id="password" 
                                               placeholder="Enter password (leave blank for default)">
                                        <div class="form-text">
                                            <i class="fas fa-shield-alt text-success me-1"></i>
                                            Default password: <code>injection</code>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-building text-primary me-1"></i>Department Access
                                        <small class="text-muted">(Select one or more)</small>
                                    </label>
                                    <div class="row g-2 mt-1">
                                        <?php foreach ($departments as $dept): ?>
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <div class="form-check form-check-lg bg-white rounded p-3 border">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="departments[]" value="<?= $dept['id'] ?>" 
                                                           id="dept<?= $dept['id'] ?>">
                                                    <label class="form-check-label fw-medium" for="dept<?= $dept['id'] ?>">
                                                        <i class="fas fa-industry text-secondary me-2"></i>
                                                        <?= htmlspecialchars($dept['name']) ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4 pt-3">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-user-plus me-2"></i>Add User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Existing Users Card -->
                    <div class="card shadow-lg" style="border: none; border-radius: 15px; overflow: hidden;">
                        <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
                            <div class="d-flex align-items-center justify-content-between text-white">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2">
                                            <i class="fas fa-users fa-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold">Existing Users</h4>
                                        <small class="opacity-75">Manage user accounts and permissions</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-white text-dark fs-6">
                                        <i class="fas fa-database me-1"></i>
                                        <?php echo $result->num_rows; ?> Total
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0" style="background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);">
                            <div class="table-responsive">
                                <table id="usersTable" class="table table-hover mb-0" style="background: transparent;">
                                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <tr class="text-white">
                                            <th scope="col" class="border-0 py-3 px-4">
                                                <i class="fas fa-id-card me-2"></i>ID Number
                                            </th>
                                            <th scope="col" class="border-0 py-3">
                                                <i class="fas fa-user me-2"></i>Full Name
                                            </th>
                                            <th scope="col" class="border-0 py-3">
                                                <i class="fas fa-user-tag me-2"></i>Role
                                            </th>
                                            <th scope="col" class="border-0 py-3 text-center">
                                                <i class="fas fa-key me-2"></i>Password Status
                                            </th>
                                            <th scope="col" class="border-0 py-3">
                                                <i class="fas fa-building me-2"></i>Departments
                                            </th>
                                            <th scope="col" class="border-0 py-3 text-center">
                                                <i class="fas fa-cogs me-2"></i>Actions
                                            </th>
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
                                                    
                                                    $passwordStatus = $row['password_changed'] ? 'Changed' : 'Default';
                                                    $passwordBadgeClass = $row['password_changed'] ? 'bg-success' : 'bg-warning';
                                                    $passwordIcon = $row['password_changed'] ? 'fa-check-circle' : 'fa-exclamation-triangle';
                                                    
                                                    echo "<tr class='border-0'>
                                                        <td class='px-4 py-3'>
                                                            <span class='badge bg-primary fs-6 px-3 py-2'>
                                                                <i class='fas fa-id-badge me-1'></i>" . htmlspecialchars($row['id_number']) . "
                                                            </span>
                                                        </td>
                                                        <td class='py-3'>
                                                            <div class='d-flex align-items-center'>
                                                                <div class='bg-primary bg-opacity-10 rounded-circle p-2 me-3'>
                                                                    <i class='fas fa-user text-primary'></i>
                                                                </div>
                                                                <div>
                                                                    <div class='fw-semibold text-dark'>" . htmlspecialchars($row['full_name']) . "</div>
                                                                    <small class='text-muted'>Employee</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class='py-3'>
                                                            <span class='badge bg-info text-white px-3 py-2'>
                                                                <i class='fas fa-user-tag me-1'></i>" . htmlspecialchars($row['role']) . "
                                                            </span>
                                                        </td>
                                                        <td class='py-3 text-center'>
                                                            <span class='badge $passwordBadgeClass px-3 py-2'>
                                                                <i class='fas $passwordIcon me-1'></i>$passwordStatus
                                                            </span>
                                                        </td>
                                                        <td class='py-3'>
                                                            <div class='d-flex flex-wrap gap-1'>";
                                                            
                                                    if (isset($user_departments_map[$row['id_number']])) {
                                                        foreach ($user_departments_map[$row['id_number']] as $dept) {
                                                            echo "<span class='badge bg-secondary text-white px-2 py-1 small'>
                                                                    <i class='fas fa-building me-1'></i>" . htmlspecialchars($dept) . "
                                                                  </span>";
                                                        }
                                                    } else {
                                                        echo "<span class='text-muted fst-italic'>
                                                                <i class='fas fa-minus-circle me-1'></i>None assigned
                                                              </span>";
                                                    }
                                                    
                                                    echo "</div>
                                                        </td>
                                                        <td class='py-3 text-center'>
                                                            <div class='btn-group btn-group-sm' role='group'>
                                                                <button class='btn btn-outline-primary btn-sm' 
                                                                        onclick='editUserAccess(\"" . htmlspecialchars($row['id_number']) . "\", \"" . htmlspecialchars($row['full_name']) . "\")'
                                                                        title='Edit Department Access'>
                                                                    <i class='fas fa-edit'></i>
                                                                </button>
                                                                <form method='POST' action='' class='d-inline'>
                                                                    <input type='hidden' name='user_id' value='" . htmlspecialchars($row['id_number']) . "'>
                                                                    <button type='submit' name='reset_user_password_changed' 
                                                                            class='btn btn-outline-warning btn-sm'
                                                                            title='Reset Password Flag'>
                                                                        <i class='fas fa-key'></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center py-5'>
                                                        <i class='fas fa-users fa-3x text-muted mb-3'></i>
                                                        <div class='text-muted'>No users found</div>
                                                      </td></tr>";
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
        </div>
    </div>

    <!-- Edit User Access Modal -->
    <div class="modal fade" id="editUserAccessModal" tabindex="-1" aria-labelledby="editUserAccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border: none; border-radius: 15px; overflow: hidden;">
                <form method="POST">
                    <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <div class="d-flex align-items-center text-white">
                            <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                <i class="fas fa-user-edit fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="modal-title mb-0 fw-bold" id="editUserAccessModalLabel">Edit User Access</h5>
                                <small class="opacity-75">Manage department permissions</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" style="background: linear-gradient(135deg, #f8f9ff 0%, #f1f3ff 100%);">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 border border-primary border-opacity-25">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-user text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <label class="form-label fw-semibold mb-0">User:</label>
                                    <div class="fw-bold text-primary fs-5" id="edit_user_name"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-building text-primary me-2"></i>Department Access
                                <small class="text-muted">(Select one or more departments)</small>
                            </label>
                            <div id="departments_list" class="row g-2 mt-2">
                                <!-- Departments will be loaded dynamically via JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" name="update_user_access" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Access
                        </button>
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
        // Store departments data for the modal (global scope)
        const departments = <?php echo json_encode($departments); ?>;
        // Store current user departments for loading into modal (global scope)
        const userDepartments = <?php echo json_encode($user_departments_map); ?>;

        // Global function for editing user access
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
                    <div class="col-12 col-sm-6">
                        <div class="form-check form-check-lg bg-white rounded-3 p-3 border ${isChecked ? 'border-primary bg-primary bg-opacity-10' : ''}">
                            <input class="form-check-input" type="checkbox" name="user_departments[]" 
                                   value="${dept.id}" id="user_dept${dept.id}" ${isChecked}>
                            <label class="form-check-label fw-medium" for="user_dept${dept.id}">
                                <i class="fas fa-industry text-secondary me-2"></i>
                                ${dept.name}
                            </label>
                        </div>
                    </div>
                `;
                departmentsList.innerHTML += checkboxHtml;
            });

            new bootstrap.Modal(document.getElementById('editUserAccessModal')).show();
        }

        $(document).ready(function () {
            // DataTables will be initialized by universal script
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Form validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
            
            // Enhanced department checkbox interaction
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[type="checkbox"][name="departments[]"], input[type="checkbox"][name="user_departments[]"]')) {
                    const container = e.target.closest('.form-check');
                    if (e.target.checked) {
                        container.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
                    } else {
                        container.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
                    }
                }
            });
        });
    </script>

<?php include '../includes/navbar_close.php'; ?>

</body>
</html>

<?php
// Connections will be closed automatically by DatabaseManager
?>
