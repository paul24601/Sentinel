<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "dailymonitoringsheet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $product_name = strtoupper($_POST['product_name']);
        $cycle_time_target = (float) $_POST['cycle_time_target'];
        $weight_standard = (float) $_POST['weight_standard'];
        $cavity_designed = (int) $_POST['cavity_designed'];
        $mold_code = (int) $_POST['mold_code'];

        $sql = "INSERT INTO product_parameters 
                    (product_name, cycle_time_target, weight_standard, cavity_designed, mold_code) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sddii", $product_name, $cycle_time_target, $weight_standard, $cavity_designed, $mold_code);

        if ($stmt->execute()) {
            header("Location: product_parameters.php?success=1");
            exit();
        } else {
            header("Location: product_parameters.php?error=1");
            exit();
        }

    } elseif ($action === 'update') {
        $cycle_time_target = (float) $_POST['cycle_time_target'];
        $weight_standard = (float) $_POST['weight_standard'];
        $cavity_designed = (int) $_POST['cavity_designed'];
        $mold_code = (int) $_POST['mold_code'];

        if (!empty($_POST['original_product_name'])) {
            // Rename + update all fields
            $original_name = strtoupper($_POST['original_product_name']);
            $new_name = strtoupper($_POST['product_name']);

            $sql = "UPDATE product_parameters
                    SET product_name       = ?,
                        cycle_time_target  = ?,
                        weight_standard    = ?,
                        cavity_designed    = ?,
                        mold_code          = ?
                    WHERE product_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sddiis",
                $new_name,
                $cycle_time_target,
                $weight_standard,
                $cavity_designed,
                $mold_code,
                $original_name
            );

        } else {
            // Only update fields (no rename)
            $product_name = strtoupper($_POST['product_name']);

            $sql = "UPDATE product_parameters
                    SET cycle_time_target = ?,
                        weight_standard   = ?,
                        cavity_designed   = ?,
                        mold_code         = ?
                    WHERE product_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ddiis",
                $cycle_time_target,
                $weight_standard,
                $cavity_designed,
                $mold_code,
                $product_name
            );
        }

        if ($stmt->execute()) {
            header("Location: product_parameters.php?success=1");
            exit();
        } else {
            header("Location: product_parameters.php?error=1");
            exit();
        }

    } elseif ($action === 'delete') {
        $product_name = strtoupper($_POST['product_name']);

        $sql = "DELETE FROM product_parameters WHERE product_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $product_name);

        if ($stmt->execute()) {
            header("Location: product_parameters.php?success=1");
            exit();
        } else {
            header("Location: product_parameters.php?error=1");
            exit();
        }
    }
}

// Get unique product names from submissions table that don't have parameters set
$sql_missing = "SELECT DISTINCT s.product_name 
                FROM submissions s 
                LEFT JOIN product_parameters p ON s.product_name = p.product_name 
                WHERE p.product_name IS NULL";
$result_missing = $conn->query($sql_missing);

// Get all product parameters
$sql_parameters = "SELECT * FROM product_parameters ORDER BY product_name";
$result_parameters = $conn->query($sql_parameters);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Parameters - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="sb-nav fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel OJT</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search (optional)-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
        <!-- Navbar Notifications and User Dropdown-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle position-relative" id="notifDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if ($pending_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $pending_count ?>
                        </span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end scrollable" aria-labelledby="notifDropdown">
                    <?php if ($pending_count > 0): ?>
                        <?php foreach ($pending_submissions as $pending): ?>
                            <li>
                                <a class="dropdown-item" href="dms/approval.php#submission-<?= $pending['id'] ?>">
                                    Submission #<?= $pending['id'] ?> -
                                    <?= htmlspecialchars($pending['product_name']) ?><br>
                                    <small><?= date("M d, Y", strtotime($pending['date'])) ?></small>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><span class="dropdown-item-text">No pending submissions.</span></li>
                    <?php endif; ?>
                </ul>
            </li>
            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <?php if ($_SESSION['role'] === 'Quality Control Inspection'): ?>
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="../index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Systems</div>
                            <!-- DMS with only Records and Approvals -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                                aria-expanded="false" aria-controls="collapseDMS">
                                <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                                DMS
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseDMS" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../dms/submission.php">Records</a>
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
                                    <a class="nav-link" href="../parameters/submission.php">Records</a>
                                </nav>
                            </div>
                        <?php else: ?>
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="../index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Systems</div>
                            <!-- DMS -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                                aria-expanded="false" aria-controls="collapseDMS">
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
                            <!-- Parameters -->
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
                            <!-- Production -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                data-bs-target="#collapseProduction" aria-expanded="false"
                                aria-controls="collapseProduction">
                                <div class="sb-nav-link-icon"><i class="fas fa-sheet-plastic"></i></div>
                                Production
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseProduction" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../production_report/index.php">Data Entry</a>
                                    <a class="nav-link" href="#">Data Visualization</a>
                                    <a class="nav-link" href="#">Data Analytics</a>
                                </nav>
                            </div>
                            <!-- Admin -->
                            <div class="sb-sidenav-menu-heading">Admin</div>
                            <a class="nav-link" href="../admin/users.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                                Users
                            </a>
                            <a class="nav-link" href="../admin/product_parameters.php">
                                <div class="sb-nav-link-icon active"><i class="fas fa-chart-area"></i></div>
                                Values
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Analysis
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= htmlspecialchars($_SESSION['full_name']); ?>
                </div>
            </nav>
        </div>
        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Product Parameters</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Product Parameters</li>
                    </ol>

                    <!-- Add New Parameters Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-plus me-1"></i>
                            Add Product Parameters
                        </div>
                        <div class="card-body">
                            <form id="parameterForm" method="POST" class="needs-validation" novalidate>
                                <input type="hidden" name="action" id="formAction" value="add">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="product_name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name"
                                            required>
                                        <div class="invalid-feedback">Please enter a product name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mold_code" class="form-label">Mold Code</label>
                                        <input type="number" class="form-control" id="mold_code" name="mold_code"
                                            required>
                                        <div class="invalid-feedback">Please enter a mold code.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="cycle_time_target" class="form-label">Target Cycle Time</label>
                                        <input type="number" step="0.01" class="form-control" id="cycle_time_target"
                                            name="cycle_time_target" required>
                                        <div class="invalid-feedback">Please enter a target cycle time.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="weight_standard" class="form-label">Standard Weight</label>
                                        <input type="number" step="0.01" class="form-control" id="weight_standard"
                                            name="weight_standard" required>
                                        <div class="invalid-feedback">Please enter a standard weight.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cavity_designed" class="form-label">Designed Cavity</label>
                                        <input type="number" class="form-control" id="cavity_designed"
                                            name="cavity_designed" required>
                                        <div class="invalid-feedback">Please enter the designed cavity.</div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" id="submitBtn">Add Parameters</button>
                                <button type="button" class="btn btn-danger" id="deleteBtn"
                                    style="display: none;">Delete Parameters</button>
                            </form>
                        </div>
                    </div>

                    <!-- Parameters Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Product Parameters
                        </div>
                        <div class="card-body">
                            <table id="parametersTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Mold Code</th>
                                        <th>Target Cycle Time</th>
                                        <th>Standard Weight</th>
                                        <th>Designed Cavity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result_parameters->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                                            <td><?= htmlspecialchars($row['mold_code']) ?></td>
                                            <td><?= htmlspecialchars($row['cycle_time_target']) ?></td>
                                            <td><?= htmlspecialchars($row['weight_standard']) ?></td>
                                            <td><?= htmlspecialchars($row['cavity_designed']) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary edit-btn"
                                                    data-product='<?= htmlspecialchars(json_encode($row), ENT_QUOTES) ?>'>
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Edit Parameters Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editParameterForm" method="POST" novalidate class="needs-validation">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="original_product_name" id="original_product_name">

                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Product Parameters</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <!-- (fields omitted for brevity; same as before) -->
                            </div>

                            <div class="modal-footer">
                                <!-- New Delete button -->
                                <button type="button" class="btn btn-danger" id="modalDeleteBtn">
                                    Delete
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            new simpleDatatables.DataTable("#parametersTable");

            // Main form: show update/delete if existing product selected
            $('#product_name').on('change', function () {
                const selected = $(this).val();
                const existing = <?php
                $params = [];
                $result_parameters->data_seek(0);
                while ($r = $result_parameters->fetch_assoc()) {
                    $params[$r['product_name']] = $r;
                }
                echo json_encode($params);
                ?>;
                if (existing[selected]) {
                    $('#mold_code').val(existing[selected].mold_code);
                    $('#cycle_time_target').val(existing[selected].cycle_time_target);
                    $('#weight_standard').val(existing[selected].weight_standard);
                    $('#cavity_designed').val(existing[selected].cavity_designed);
                    $('#formAction').val('update');
                    $('#submitBtn').text('Update Parameters');
                    $('#deleteBtn').show();
                } else {
                    $('#mold_code, #cycle_time_target, #weight_standard, #cavity_designed').val('');
                    $('#formAction').val('add');
                    $('#submitBtn').text('Add Parameters');
                    $('#deleteBtn').hide();
                }
            });

            // Edit button: populate and show modal
            $('.edit-btn').on('click', function () {
                const data = JSON.parse($(this).attr('data-product'));
                $('#original_product_name').val(data.product_name);
                $('#edit_product_name').val(data.product_name);
                $('#edit_mold_code').val(data.mold_code);
                $('#edit_cycle_time_target').val(data.cycle_time_target);
                $('#edit_weight_standard').val(data.weight_standard);
                $('#edit_cavity_designed').val(data.cavity_designed);
                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            });

            // Modal delete button: switch to 'delete' action and submit
            $('#modalDeleteBtn').on('click', function () {
                if (confirm('Are you sure you want to delete these parameters?')) {
                    // change hidden action to 'delete'
                    $('#editParameterForm').find('input[name="action"]').val('delete');
                    // submit the form
                    $('#editParameterForm').submit();
                }
            });


            // Modal form validation
            $('#editParameterForm').on('submit', function (e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                $(this).addClass('was-validated');
            });

            // Main form delete
            $('#deleteBtn').on('click', function () {
                if (confirm('Are you sure you want to delete these parameters?')) {
                    $('#formAction').val('delete');
                    $('#parameterForm').submit();
                }
            });

            // Main form validation
            $('#parameterForm').on('submit', function (e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                $(this).addClass('was-validated');
            });

            // Success/error alerts
            const params = new URLSearchParams(window.location.search);
            if (params.get('success') === '1') {
                alert('Operation completed successfully!');
                history.replaceState({}, document.title, window.location.pathname);
            } else if (params.get('error') === '1') {
                alert('An error occurred. Please try again.');
                history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>

</html>