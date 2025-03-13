<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "dailymonitoringsheet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: login.html");
    exit();
}

$full_name = $_SESSION['full_name'];
$message = "";

// Handle edit submission: update record and set approval_status back to pending
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submission_id'])) {
    $submission_id = intval($_POST['submission_id']);
    $date = $_POST['date'];
    $product_name = $_POST['product_name'];
    $machine = $_POST['machine'];
    $prn = $_POST['prn'];
    $mold_code = intval($_POST['mold_code']);
    $cycle_time_target = intval($_POST['cycle_time_target']);
    $cycle_time_actual = intval($_POST['cycle_time_actual']);
    $weight_standard = floatval($_POST['weight_standard']);
    $weight_gross = floatval($_POST['weight_gross']);
    $weight_net = floatval($_POST['weight_net']);
    $cavity_designed = intval($_POST['cavity_designed']);
    $cavity_active = intval($_POST['cavity_active']);
    $remarks = $_POST['remarks'];
    $shift = $_POST['shift'];

    // Verify that this submission belongs to the logged-in user
    $check_sql = "SELECT * FROM submissions WHERE id = ? AND name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $submission_id, $full_name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update the submission with all the form fields and set status to pending.
        // Notice the use of backticks for the reserved word `date`.
        $update_sql = "UPDATE submissions 
                       SET `date` = ?, product_name = ?, machine = ?, prn = ?, mold_code = ?, 
                           cycle_time_target = ?, cycle_time_actual = ?, weight_standard = ?, 
                           weight_gross = ?, weight_net = ?, cavity_designed = ?, cavity_active = ?, 
                           remarks = ?, shift = ?, approval_status = 'pending'
                       WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "ssssiiidddiissi",
            $date,
            $product_name,
            $machine,
            $prn,
            $mold_code,
            $cycle_time_target,
            $cycle_time_actual,
            $weight_standard,
            $weight_gross,
            $weight_net,
            $cavity_designed,
            $cavity_active,
            $remarks,
            $shift,
            $submission_id
        );

        if ($update_stmt->execute()) {
            $message = "Submission successfully updated and resubmitted for approval.";
        } else {
            $message = "Error updating submission: " . $conn->error;
        }
    } else {
        $message = "Unauthorized action.";
    }
}

// Fetch declined submissions belonging only to the logged-in user
$sql = "SELECT * FROM submissions WHERE approval_status = 'declined' AND name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $full_name);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Declined Submissions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2 class="mb-4">Your Declined Submissions</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Submissions Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Product Name</th>
                <th>Machine</th>
                <th>PRN</th>
                <th>Mold Code</th>
                <th>Cycle Time Target</th>
                <th>Cycle Time Actual</th>
                <th>Weight Standard</th>
                <th>Weight Gross</th>
                <th>Weight Net</th>
                <th>Cavity Designed</th>
                <th>Cavity Active</th>
                <th>Remarks</th>
                <th>Shift</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['machine']); ?></td>
                    <td><?php echo htmlspecialchars($row['prn']); ?></td>
                    <td><?php echo htmlspecialchars($row['mold_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['cycle_time_target']); ?></td>
                    <td><?php echo htmlspecialchars($row['cycle_time_actual']); ?></td>
                    <td><?php echo htmlspecialchars($row['weight_standard']); ?></td>
                    <td><?php echo htmlspecialchars($row['weight_gross']); ?></td>
                    <td><?php echo htmlspecialchars($row['weight_net']); ?></td>
                    <td><?php echo htmlspecialchars($row['cavity_designed']); ?></td>
                    <td><?php echo htmlspecialchars($row['cavity_active']); ?></td>
                    <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                    <td><?php echo htmlspecialchars($row['shift']); ?></td>
                    <td>
                        <!-- The Edit button carries data attributes for all fields -->
                        <button class="btn btn-primary btn-sm edit-btn" data-id="<?php echo $row['id']; ?>"
                            data-date="<?php echo $row['date']; ?>"
                            data-product_name="<?php echo htmlspecialchars($row['product_name']); ?>"
                            data-machine="<?php echo htmlspecialchars($row['machine']); ?>"
                            data-prn="<?php echo htmlspecialchars($row['prn']); ?>"
                            data-mold_code="<?php echo $row['mold_code']; ?>"
                            data-cycle_time_target="<?php echo $row['cycle_time_target']; ?>"
                            data-cycle_time_actual="<?php echo $row['cycle_time_actual']; ?>"
                            data-weight_standard="<?php echo $row['weight_standard']; ?>"
                            data-weight_gross="<?php echo $row['weight_gross']; ?>"
                            data-weight_net="<?php echo $row['weight_net']; ?>"
                            data-cavity_designed="<?php echo $row['cavity_designed']; ?>"
                            data-cavity_active="<?php echo $row['cavity_active']; ?>"
                            data-remarks="<?php echo htmlspecialchars($row['remarks']); ?>"
                            data-name="<?php echo htmlspecialchars($row['name']); ?>"
                            data-shift="<?php echo htmlspecialchars($row['shift']); ?>">
                            Edit
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="declined_submissions.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Submission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hidden field for submission ID -->
                        <input type="hidden" name="submission_id" value="">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" max="<?php echo date('Y-m-d'); ?>"
                                    required>
                            </div>
                            <div class="mb-3 col-md-8">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="product_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Machine</label>
                                <select class="form-control" name="machine" required>
                                    <option value="" disabled>Select a machine</option>
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
                            <div class="mb-3 col-md-6">
                                <label class="form-label">PRN</label>
                                <input type="text" class="form-control" name="prn" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Mold Code</label>
                                <input type="number" class="form-control" name="mold_code" max="9999" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Cycle Time Target</label>
                                <input type="number" class="form-control" name="cycle_time_target" min="0" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Cycle Time Actual</label>
                                <input type="number" class="form-control" name="cycle_time_actual" min="0" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Weight Standard</label>
                                <input type="number" step="0.01" class="form-control" name="weight_standard" min="0"
                                    required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Weight Gross</label>
                                <input type="number" step="0.01" class="form-control" name="weight_gross" min="0"
                                    required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Weight Net</label>
                                <input type="number" step="0.01" class="form-control" name="weight_net" min="0"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Cavity Designed</label>
                                <input type="number" class="form-control" name="cavity_designed" min="0" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Cavity Active</label>
                                <input type="number" class="form-control" name="cavity_active" min="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" readonly required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Shift</label>
                                <select class="form-control" name="shift" required>
                                    <option value="" disabled>Select your shift</option>
                                    <option value="1st shift">1st Shift</option>
                                    <option value="2nd shift">2nd Shift</option>
                                    <option value="3rd shift">3rd Shift</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.edit-btn').on('click', function () {
                var button = $(this);
                // Populate the modal fields using the button's data attributes
                $('#editModal input[name="submission_id"]').val(button.data('id'));
                $('#editModal input[name="date"]').val(button.data('date'));
                $('#editModal input[name="product_name"]').val(button.data('product_name'));
                $('#editModal select[name="machine"]').val(button.data('machine'));
                $('#editModal input[name="prn"]').val(button.data('prn'));
                $('#editModal input[name="mold_code"]').val(button.data('mold_code'));
                $('#editModal input[name="cycle_time_target"]').val(button.data('cycle_time_target'));
                $('#editModal input[name="cycle_time_actual"]').val(button.data('cycle_time_actual'));
                $('#editModal input[name="weight_standard"]').val(button.data('weight_standard'));
                $('#editModal input[name="weight_gross"]').val(button.data('weight_gross'));
                $('#editModal input[name="weight_net"]').val(button.data('weight_net'));
                $('#editModal input[name="cavity_designed"]').val(button.data('cavity_designed'));
                $('#editModal input[name="cavity_active"]').val(button.data('cavity_active'));
                $('#editModal textarea[name="remarks"]').val(button.data('remarks'));
                $('#editModal input[name="name"]').val(button.data('name'));
                $('#editModal select[name="shift"]').val(button.data('shift'));
                // Show the modal
                $('#editModal').modal('show');
            });
        });
    </script>
</body>

</html>