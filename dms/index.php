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
    // Get the user's role from the session
    $role = $_SESSION['role'];

    // Base query for submissions 
    $sql_pending = "SELECT id, product_name, `date` FROM submissions";
    
    // Sort by date descending
    $sql_pending .= " ORDER BY `date` DESC LIMIT 10";

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
                                <a class="nav-link" href="index.php">Data Entry</a>
                                <a class="nav-link" href="submission.php">Records</a>
                                <a class="nav-link" href="analytics.php">Analytics</a>
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
                                                <div class="input-group">
                                                    <select required class="form-control" id="cycle_time_actual" name="cycle_time_actual" required>
                                                        <option value="" disabled selected>Select cycle time</option>
                                                    </select>
                                                    <button type="button" class="btn btn-outline-secondary" id="refresh_cycle_times">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" id="selected_cycle_time_id" name="selected_cycle_time_id">
                                                <small class="text-muted" id="cycle_time_details"></small>
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
    <script>
    function loadCycleTimes() {
        const machine = $('#machine').val();
        const cycleTimeContainer = $('#cycle_time_actual').parent();
        const cycleTimeDetails = $('#cycle_time_details');

        // If not CLF 750A, show input box instead of dropdown
        if (machine && machine !== 'CLF 750A') {
            // Replace the select element with an input
            cycleTimeContainer.html(`
                <input type="number" class="form-control" id="cycle_time_actual" name="cycle_time_actual" 
                       placeholder="Enter actual cycle time" step="0.01" min="0" required>
            `);
            cycleTimeDetails.html(''); // Clear any existing details
            $('#selected_cycle_time_id').val(''); // Clear the hidden input
            return;
        }

        // For CLF 750A, show the dropdown with auto-cycle times
        if (!machine) {
            $('#cycle_time_actual').html('<option value="" disabled selected>Select machine first</option>');
            return;
        }

        // Restore the select element if it was previously changed to input
        if ($('#cycle_time_actual').is('input')) {
            cycleTimeContainer.html(`
                <div class="input-group">
                    <select required class="form-control" id="cycle_time_actual" name="cycle_time_actual" required>
                        <option value="" disabled selected>Select cycle time</option>
                    </select>
                    <button type="button" class="btn btn-outline-secondary" id="refresh_cycle_times">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            `);
        }

        $.ajax({
            url: 'fetch_auto_cycle_time.php',
            method: 'GET',
            data: { machine: machine },
            success: function(data) {
                const select = $('#cycle_time_actual');
                select.empty();
                select.append('<option value="" disabled selected>Select cycle time</option>');
                
                if (data.length === 0) {
                    select.append('<option value="" disabled>No recent cycle times available</option>');
                    return;
                }
                
                data.forEach(function(item) {
                    // Format the timestamp
                    const timestamp = new Date(item.timestamp);
                    const formattedDate = timestamp.toLocaleDateString();
                    const formattedTime = timestamp.toLocaleTimeString();
                    
                    // Create detailed tooltip text
                    const tooltipText = `ID: ${item.id} | Recorded: ${formattedDate} ${formattedTime} | T1: ${item.temp1}°C, T2: ${item.temp2}°C | P: ${item.pressure}g`;
                    
                    // Create a simpler display text but with visual indicators
                    const displayText = `${item.cycle_time}s ⌚ (ID: ${item.id})`;
                    
                    const option = $('<option></option>')
                        .val(item.cycle_time)
                        .text(displayText)
                        .attr('title', tooltipText) // Add tooltip with full details
                        .css({
                            'font-family': 'monospace',  // Use monospace font for better readability
                            'white-space': 'pre'         // Preserve spacing
                        });
                    
                    option.data('id', item.id);
                    option.data('details', {
                        temp1: item.temp1,
                        temp2: item.temp2,
                        pressure: item.pressure,
                        timestamp: item.timestamp
                    });
                    select.append(option);
                });

                // Initialize Bootstrap tooltips
                $('[title]').tooltip({
                    placement: 'top',
                    trigger: 'hover'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading cycle times:', error);
            }
        });
    }

    // Load cycle times when page loads
    $(document).ready(function() {
        // Load cycle times when machine is selected
        $('#machine').change(function() {
            loadCycleTimes();
        });
        
        // Manual refresh button
        $(document).on('click', '#refresh_cycle_times', function() {
            loadCycleTimes();
        });
        
        // Update hidden input and details when selection changes
        $(document).on('change', '#cycle_time_actual', function() {
            if ($(this).is('select')) {
            const selectedOption = $(this).find('option:selected');
            const details = selectedOption.data('details');
            $('#selected_cycle_time_id').val(selectedOption.data('id'));
            
            // Update details display
            if (details) {
                const detailsHtml = `
                    <div class="mt-2">
                        <strong>Details:</strong><br>
                        Temperature 1: ${details.temp1}°C<br>
                        Temperature 2: ${details.temp2}°C<br>
                        Pressure: ${details.pressure}g<br>
                        Recorded: ${new Date(details.timestamp).toLocaleString()}
                    </div>
                `;
                $('#cycle_time_details').html(detailsHtml);
            } else {
                $('#cycle_time_details').html('');
                }
            }
        });
        
        // Auto-refresh cycle times every 30 seconds if machine is CLF 750A
        setInterval(function() {
            if ($('#machine').val() === 'CLF 750A') {
                loadCycleTimes();
            }
        }, 30000);
    });
    </script>
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Success!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                        <p class="mt-3">Your submission has been successfully recorded.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="window.location.href='index.php'">New Submission</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        // Check for success parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Remove success parameter from URL without refreshing the page
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
    </script>
</body>

</html>