<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html");
    exit();
}

// Only allow supervisors, admins and adjusters to access this page
if (
    !isset($_SESSION['role']) ||
    ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'supervisor' && $_SESSION['role'] !== 'adjuster')
) {
    header("Location: ../login.html");
    exit();
}

// --- Database Connection & Notification Functionality --- //
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "dailymonitoringsheet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get pending submissions for notifications
function getPendingSubmissions($conn)
{
    $pending = [];
    $sql_pending = "SELECT id, product_name, `date` FROM submissions WHERE approval_status = 'pending' ORDER BY date DESC";
    $result_pending = $conn->query($sql_pending);
    if ($result_pending && $result_pending->num_rows > 0) {
        while ($row = $result_pending->fetch_assoc()) {
            $pending[] = $row;
        }
    }
    return $pending;
}

$pending_submissions = getPendingSubmissions($conn);
$pending_count = count($pending_submissions);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>DMS - Data Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery and jQuery UI for Autocomplete -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script>
        $(function () {
            // Enable popovers
            $('[data-bs-toggle="popover"]').popover();

            // Convert product name input to uppercase on every keystroke
            $('#product_name').on('input', function () {
                this.value = this.value.toUpperCase();
            });

            $("#product_name").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "autocomplete.php",
                        dataType: "json",
                        data: { term: request.term },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    // Populate other fields with numeric and non-numeric data
                    $('#mold_code').val(ui.item.mold_code);
                    $('#cycle_time_target').val(ui.item.cycle_time_target);
                    $('#weight_standard').val(ui.item.weight_standard);
                    $('#cavity_designed').val(ui.item.cavity_designed);
                },
                focus: function (event, ui) {
                    // When hovering over a suggestion, preview the data in the fields
                    $('#mold_code').val(ui.item.mold_code);
                    $('#cycle_time_target').val(ui.item.cycle_time_target);
                    $('#weight_standard').val(ui.item.weight_standard);
                    $('#cavity_designed').val(ui.item.cavity_designed);
                    return false;
                }
            });

            // PRN Autocomplete setup
            $("#prn").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "autocomplete_prn.php",
                        dataType: "json",
                        data: { term: request.term },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    // Additional actions when a PRN is selected (if needed)
                },
                focus: function (event, ui) {
                    return false;
                }
            });
        });
    </script>
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
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <!-- (Optional search form can go here) -->
        </form>
        <!-- Navbar Notifications and User Dropdown-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle position-relative" id="notifDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <?php if ($pending_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $pending_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown"
                    style="max-height:300px; overflow-y:auto;">
                    <?php if ($pending_count > 0): ?>
                        <?php foreach ($pending_submissions as $pending): ?>
                            <li>
                                <a class="dropdown-item notification-link"
                                    href="approval.php?refresh=1#submission-<?php echo $pending['id']; ?>">
                                    Submission #<?php echo $pending['id']; ?> -
                                    <?php echo htmlspecialchars($pending['product_name']); ?>
                                    <br>
                                    <small><?php echo date("M d, Y", strtotime($pending['date'])); ?></small>
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
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                            aria-expanded="false" aria-controls="collapseDMS">
                            <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                            DMS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseDMS" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link active" href="#">Data Entry</a>
                                <a class="nav-link" href="submission.php">Records</a>
                                <a class="nav-link" href="analytics.php">Analytics</a>
                                <a class="nav-link" href="approval.php">Approvals</a>
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
                        <a class="nav-link" href="../admin/users.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                            Users
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
                    <h1 class="">Data Entry</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
                    </ol>
                    <!-- FORMS -->
                    <div class="container-fluid opacity-90">
                        <div class="card shadow">
                            <div class="card-body">
                                <!-- Form starts here -->
                                <form action="submission.php" method="POST">
                                    <!-- Date -->
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input required type="date" class="form-control" id="date" name="date"
                                            max="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <!-- Product Name -->
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Product Name</label>
                                        <span tabindex="0" class="text-body-secondary" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus"
                                            data-bs-content="The first data submitted for the corresponding Product Name would be shown.">
                                            <i class="bi bi-info-circle"></i>
                                        </span>
                                        <input required type="text" id="product_name" name="product_name"
                                            class="form-control mt-2" placeholder="Enter Product Name">
                                    </div>
                                    <!-- Machine -->
                                    <div class="mb-3">
                                        <label for="machine" class="form-label">Machine</label>
                                        <select class="form-control" id="machine" name="machine" required>
                                            <option value="" disabled selected>Select a machine</option>
                                            <option value="ARB 50">ARB 50</option>
                                            <option value="SUM 260C">SUM 260C</option>
                                            <option value="SUM 350">SUM 350</option>
                                            <option value="MIT 650D">MIT 650D</option>
                                            <option value="TOS 650A">TOS 650A</option>
                                            <option value="CLF 750A">CLF 750A</option>
                                            <option value="CLF 750B">CLF 750B</option>
                                            <option value="CLF 750C">CLF 750C</option>
                                            <option value="TOS 850A">TOS 850A</option>
                                            <option value="TOS 850B">TOS 850B</option>
                                            <option value="TOS 850C">TOS 850C</option>
                                            <option value="CLF 950A">CLF 950A</option>
                                            <option value="CLF 950B">CLF 950B</option>
                                            <option value="MIT 1050B">MIT 1050B</option>
                                        </select>
                                    </div>
                                    <!-- PRN -->
                                    <div class="mb-3">
                                        <label for="prn" class="form-label">PRN</label>
                                        <input required type="text" class="form-control" id="prn" name="prn"
                                            placeholder="Enter PRN">
                                    </div>
                                    <!-- Mold Code -->
                                    <div class="mb-3">
                                        <label for="mold_code" class="form-label">Mold Code</label>
                                        <input required type="number" class="form-control" id="mold_code"
                                            name="mold_code" placeholder="Enter Mold Code" max="9999" required>
                                    </div>
                                    <!-- Cycle Time -->
                                    <div class="mb-4">
                                        <h5 class="mb-3">Cycle Time</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="cycle-time-target" class="form-label">Target</label>
                                                <input required type="number" class="form-control"
                                                    id="cycle_time_target" name="cycle_time_target"
                                                    placeholder="Enter target cycle time" min="0" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cycle-time-actual" class="form-label">Actual</label>
                                                <input required type="number" class="form-control"
                                                    id="cycle_time_actual" name="cycle_time_actual"
                                                    placeholder="Enter actual cycle time" min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Weight -->
                                    <div class="mb-4">
                                        <h5 class="mb-3">WEIGHT (grams/pc)</h5>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="weight-standard" class="form-label">Standard</label>
                                                <input required type="number" step="0.01" class="form-control"
                                                    id="weight_standard" name="weight_standard"
                                                    placeholder="Enter standard weight" min="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="weight-gross" class="form-label">Gross</label>
                                                <input required type="number" step="0.01" class="form-control"
                                                    id="weight_gross" name="weight_gross"
                                                    placeholder="Enter gross weight" min="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="weight-net" class="form-label">Net</label>
                                                <input required type="number" step="0.01" class="form-control"
                                                    id="weight_net" name="weight_net" placeholder="Enter net weight"
                                                    min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Number of Cavity -->
                                    <div class="mb-4">
                                        <h5 class="mb-3">Number of Cavity</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="cavity-designed" class="form-label">Designed</label>
                                                <input required type="number" class="form-control" id="cavity_designed"
                                                    name="cavity_designed"
                                                    placeholder="Enter designed number of cavities" min="0" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cavity-active" class="form-label">Active</label>
                                                <input required type="number" class="form-control" id="cavity_active"
                                                    name="cavity_active" placeholder="Enter active number of cavities"
                                                    min="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Remarks -->
                                    <div class="mb-3">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <span tabindex="0" class="text-body-secondary" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus"
                                            data-bs-content="Add remarks if Product has NO STANDARD CYCLE TIME AND WEIGHT.">
                                            <i class="bi bi-info-circle"></i>
                                        </span>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                            placeholder="Enter any remarks"></textarea>
                                    </div>
                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input required type="text" class="form-control" id="name" name="name"
                                            value="<?php echo $_SESSION['full_name']; ?>" readonly required>
                                    </div>
                                    <!-- Shift -->
                                    <div class="mb-3">
                                        <label for="shift" class="form-label">Shift</label>
                                        <select class="form-select" id="shift" name="shift" required>
                                            <option value="" selected disabled>Select your shift</option>
                                            <option value="1st shift">1st Shift</option>
                                            <option value="2nd shift">2nd Shift</option>
                                            <option value="3rd shift">3rd Shift</option>
                                        </select>
                                    </div>
                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
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
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../assets/demo/chart-area-demo.js"></script>
    <script src="../assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>