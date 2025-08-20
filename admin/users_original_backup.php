<?php
session_start();

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Load admin notifications for all page loads
require_once __DIR__ . '/../includes/admin_notifications.php';

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = count(array_filter($admin_notifications, function($n) { return !$n['is_viewed']; }));

// Get database connections
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
    $admin_conn = DatabaseManager::getConnection('sentinel_admin');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Include centralized navbar
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin - Users</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI for Autocomplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel Digitization</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                   <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="../index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Systems</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                           data-bs-target="#collapseDMS" aria-expanded="false" aria-controls="collapseDMS">
                            <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                            DMS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseDMS" aria-labelledby="headingOne"
                             data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="../dms/index.php">Data Entry</a>
                                <a class="nav-link" href="../dms/submission.php">Records</a>
                                <a class="nav-link" href="../dms/analytics.php">Analytics</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                           data-bs-target="#collapseParameters" aria-expanded="false"
                           aria-controls="collapseParameters">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Parameters
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseParameters" aria-labelledby="headingOne"
                             data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="../parameters/index.php">Data Entry</a>
                                <a class="nav-link" href="../parameters/submission.php">Data Visualization</a>
                                <a class="nav-link" href="../parameters/analytics.php">Data Analytics</a>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Admin</div>
                        <a class="nav-link active" href="users.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="password_reset_management.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                            Password Reset Requests
                            <?php if ($pending_requests_count > 0): ?>
                                <span class="badge bg-danger ms-2"><?= $pending_requests_count ?></span>
                            <?php endif; ?>
                        </a>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Values
                        </a>
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Analysis
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $_SESSION['full_name']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid p-4">
                    <h1 class="">Users</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
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
                                                <div class="col-md-3">
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
                                                echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
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
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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
</body>
</html>

<?php
$conn->close();
if (isset($admin_conn)) {
    $admin_conn->close();
}
?>
