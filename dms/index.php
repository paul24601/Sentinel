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
require_once '../includes/database.php';
require_once '../includes/admin_notifications.php';

// Create connection using DatabaseManager
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);
?>
<?php include '../includes/navbar.php'; ?>

    <div class="container-fluid px-4">
        <h1 class="mt-4">Data Entry</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Injection Department</li>
        </ol>
    </div>

    <!--FORMS-->
    <div class="container-fluid my-5">
        <div class="card shadow">
            <div class="card-body">
                <!-- JavaScript for form functionality -->
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
                                    minLength: 1,
                                    select: function (event, ui) {
                                        // Populate form fields with standard parameters
                                        $('#mold_code').val(ui.item.mold_code);
                                        $('#cycle_time_target').val(ui.item.cycle_time_target);
                                        $('#weight_standard').val(ui.item.weight_standard);
                                        $('#cavity_designed').val(ui.item.cavity_designed);

                                        // Clear actual values to ensure they are entered manually
                                        $('#weight_gross').val('');
                                        $('#weight_net').val('');
                                        $('#cavity_active').val('');

                                        // If machine is CLF 750A, refresh cycle times
                                        if ($('#machine').val() === 'CLF 750A') {
                                            loadCycleTimes();
                                        }
                                    },
                                    focus: function (event, ui) {
                                        // When hovering over a suggestion, preview the data in the fields
                                        $('#mold_code').val(ui.item.mold_code);
                                        $('#cycle_time_target').val(ui.item.cycle_time_target);
                                        $('#weight_standard').val(ui.item.weight_standard);
                                        $('#cavity_designed').val(ui.item.cavity_designed);
                                        return false;
                                    }
                                }).autocomplete("instance")._renderItem = function (ul, item) {
                                    // Custom rendering of autocomplete items
                                    return $("<li>")
                                        .append("<div>" + item.label + 
                                               "<br><small class='text-muted'>Target Cycle: " + item.cycle_time_target + 
                                               "s | Standard Weight: " + item.weight_standard + 
                                               "g | Cavities: " + item.cavity_designed + "</small></div>")
                                        .appendTo(ul);
                                };

                                // Load cycle times function
                                function loadCycleTimes() {
                                    // For DMS, we use manual input for all machines
                                    const cycleTimeContainer = $('#cycle_time_actual').parent();
                                    cycleTimeContainer.html(`
                                        <input type="number" class="form-control" id="cycle_time_actual" name="cycle_time_actual" 
                                               placeholder="Enter actual cycle time" step="0.01" min="0" required>
                                    `);
                                }

                                // Load manual input when machine is selected
                                $('#machine').change(function() {
                                    loadCycleTimes();
                                });

                    // Initial load
                    loadCycleTimes();
                });
                </script>

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
                        <label for="prn" class="form-label">IRN</label>
                        <input required type="text" class="form-control" id="prn" name="prn"
                            placeholder="Enter IRN">
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
                            <div class="col-12 col-sm-6 col-md-6">
                                <label for="cycle-time-target" class="form-label">Target</label>
                                <input required type="number" class="form-control"
                                    id="cycle_time_target" name="cycle_time_target"
                                    placeholder="Enter target cycle time" min="0" required>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6">
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
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="weight-standard" class="form-label">Standard</label>
                                <input required type="number" step="0.01" class="form-control"
                                    id="weight_standard" name="weight_standard"
                                    placeholder="Enter standard weight" min="0" required>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="weight-gross" class="form-label">Gross</label>
                                <input required type="number" step="0.01" class="form-control"
                                    id="weight_gross" name="weight_gross"
                                    placeholder="Enter gross weight" min="0" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4">
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
                            <div class="col-12 col-sm-6 col-md-6">
                                <label for="cavity-designed" class="form-label">Designed</label>
                                <input required type="number" class="form-control" id="cavity_designed"
                                    name="cavity_designed"
                                    placeholder="Enter designed number of cavities" min="0" required>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6">
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
                <div class="d-grid mb-5">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
</main>

<?php include '../includes/navbar_close.php'; ?>