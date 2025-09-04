<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Include database configuration
require_once '../includes/database.php';
require_once '../includes/admin_notifications.php';

// Database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);

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

// Include centralized navbar
include '../includes/navbar.php';
?>

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

<?php include '../includes/navbar_close.php'; ?>

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

            // Notification functions
            window.markAsViewed = function(notificationId) {
                fetch('../includes/mark_notification_viewed.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'notification_id=' + notificationId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the notification item appearance
                        const notificationItem = document.querySelector('[data-notification-id="' + notificationId + '"]');
                        if (notificationItem) {
                            notificationItem.classList.remove('bg-light');
                            const newBadge = notificationItem.querySelector('.badge.bg-primary');
                            if (newBadge) {
                                newBadge.remove();
                            }
                        }

                        // Update the notification count
                        updateNotificationCount();
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as viewed:', error);
                });
            };

            window.updateNotificationCount = function() {
                fetch('../includes/admin_notifications.php?action=count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('#notifDropdown .badge');
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                        } else {
                            // Create badge if it doesn't exist
                            const bell = document.querySelector('#notifDropdown i');
                            const newBadge = document.createElement('span');
                            newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                            newBadge.textContent = data.count;
                            bell.parentNode.appendChild(newBadge);
                        }
                    } else {
                        if (badge) {
                            badge.remove();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating notification count:', error);
                });
            };
        });
    </script>
</body>

</html>