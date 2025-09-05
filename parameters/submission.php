<?php
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

require_once 'session_config.php';

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html");
    exit();
}

// Load the centralized configuration
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Get database connection using the centralized system
try {
    $conn = DatabaseManager::getConnection('sentinel_main');
    // Set MySQL timezone to Philippine Time (UTC+8)
    $conn->query("SET time_zone = '+08:00'");
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchData($conn, $tableName)
{
    $sql = "SELECT * FROM $tableName";
    return $conn->query($sql);
}

// Check if a specific record is requested
$selectedRecordId = isset($_GET['record_id']) ? $_GET['record_id'] : null;

// Fetch master records
$sql = "SELECT pr.* 
        FROM parameter_records pr
        ORDER BY pr.submission_date DESC";
$parameterRecords = $conn->query($sql);

// If a specific record is selected, fetch its details
if ($selectedRecordId) {
    // Fetch detailed record data
    $sql = "SELECT * FROM parameter_records WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $recordDetails = $stmt->get_result()->fetch_assoc();

    // Fetch related data tables
    $productMachineInfo = fetchData($conn, "productmachineinfo WHERE record_id = '$selectedRecordId'");
    $barrelHeaterTemp = fetchData($conn, "barrelheatertemperatures WHERE record_id = '$selectedRecordId'");
    $timerParameters = fetchData($conn, "timerparameters WHERE record_id = '$selectedRecordId'");
    $materialComposition = fetchData($conn, "materialcomposition WHERE record_id = '$selectedRecordId'");
    $moldParameters = fetchData($conn, "moldparameters WHERE record_id = '$selectedRecordId'");
    $additionalInformation = fetchData($conn, "additionalinformation WHERE record_id = '$selectedRecordId'");
    $personnel = fetchData($conn, "personnel WHERE record_id = '$selectedRecordId'");
    $attachments = fetchData($conn, "attachments WHERE record_id = '$selectedRecordId'");
}

?>
<?php include '../includes/navbar.php'; ?>

<!-- CSS for Submission page styling -->

                <div class="container-fluid px-4">
                    <h1 class="mt-4">Parameters Records</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Parameters</a></li>
                        <li class="breadcrumb-item active">Records</li>
                    </ol>

                    <?php if ($selectedRecordId && $recordDetails): ?>
                        <!-- Detailed Record View -->
                        <div class="mb-4">
                            <a href="submission.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Records</a>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #007bff, #0056b3) !important; background-color: #007bff !important; color: white !important;">
                                <div class="d-flex justify-content-between">
                                    <h5><?= htmlspecialchars($recordDetails['title']) ?></h5>
                                    <span>Record ID: <?= htmlspecialchars($selectedRecordId) ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <p><strong>Submitted by:</strong> <?= htmlspecialchars($recordDetails['submitted_by']) ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Submission Date:</strong> <?= htmlspecialchars($recordDetails['submission_date']) ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-success"><?= htmlspecialchars(ucfirst($recordDetails['status'])) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <?php if ($recordDetails['description']): ?>
                                    <div class="mb-3">
                                        <p><strong>Description:</strong></p>
                                        <p><?= nl2br(htmlspecialchars($recordDetails['description'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Data Tables for detailed view -->
                        <?php if ($productMachineInfo && $productMachineInfo->num_rows > 0): ?>
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">Product Machine Info</div>
                                <div class="card-body table-responsive">
                                    <table id="productMachineInfoTable" class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Machine Name</th>
                                                <th>Run Number</th>
                                                <th>Category</th>
                                                <th>IRN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $productMachineInfo->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['Date'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['Time'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['MachineName'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['RunNumber'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['Category'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['IRN'] ?? '') ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- Records List View -->
                        <div class="card">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table me-1"></i>
                                    Parameters Records (<?= $parameterRecords->num_rows ?>)
                                </div>
                                <div class="btn-group">
                                    <a href="export_data.php?format=pdf" class="btn btn-danger btn-sm" target="_blank">
                                        <i class="fas fa-file-pdf"></i> Export All PDF
                                    </a>
                                    <a href="export_data.php?format=excel" class="btn btn-success btn-sm" target="_blank">
                                        <i class="fas fa-file-excel"></i> Export All Excel
                                    </a>
                                    <a href="export_data.php?format=sql" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-database"></i> Export All SQL
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="parametersTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Record ID</th>
                                                <th>Title</th>
                                                <th>Submitted By</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($parameterRecords && $parameterRecords->num_rows > 0): ?>
                                                <?php while ($row = $parameterRecords->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['record_id']) ?></td>
                                                        <td><?= htmlspecialchars($row['title']) ?></td>
                                                        <td><?= htmlspecialchars($row['submitted_by']) ?></td>
                                                        <td><?= htmlspecialchars($row['submission_date']) ?></td>
                                                        <td>
                                                            <span class="badge bg-success">
                                                                <?= htmlspecialchars(ucfirst($row['status'])) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="submission.php?record_id=<?= urlencode($row['record_id']) ?>" 
                                                               class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                            <button class="btn btn-info btn-sm print-record"
                                                                data-record-id="<?= htmlspecialchars($row['record_id']) ?>">
                                                                <i class="fas fa-print"></i> Print
                                                            </button>
                                                            <form method="POST" action="index.php" class="d-inline">
                                                                <input type="hidden" name="clone_record_id"
                                                                    value="<?= htmlspecialchars($row['record_id']) ?>">
                                                                <button type="submit" class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-clone"></i> Apply to Form
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No records found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

    <script>
        $(document).ready(function() {
            // DataTables will be initialized by universal script
            
            // Print record handler
            $('.print-record').click(function () {
                const recordId = $(this).data('record-id');
                window.open('print_record.php?record_id=' + recordId, '_blank');
            });
        });
    </script>

<?php include '../includes/navbar_close.php'; ?>
