<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Only allow users with proper roles (adjust roles as needed)
$allowed_roles = ['admin', 'supervisor', 'Quality Assurance Engineer', 'Quality Assurance Supervisor', 'Quality Control Inspection'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("Location: ../login.html");
    exit();
}

// Database connection for Parameters system
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "injectionmoldingparameters";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Process approval/decline actions submitted via POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submission_id'], $_POST['approval_action'])) {
    $submission_id = intval($_POST['submission_id']);
    $approval_action = $_POST['approval_action']; // expected values: 'approved' or 'declined'
    
    // Determine which reviewer column to update based on the user role
    $current_role = $_SESSION['role'];
    if (in_array($current_role, ['Quality Assurance Engineer', 'Quality Assurance Supervisor'])) {
        $reviewer_column = "qa_reviewer";
    } else {
        $reviewer_column = "supervisor_reviewer";
    }
    
    // Update the parameters_submissions record (without approval_comment)
    $sql_update = "UPDATE parameters_submissions 
                   SET approval_status = ?, $reviewer_column = ? 
                   WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $approver = $_SESSION['full_name'];
    $stmt->bind_param("ssi", $approval_action, $approver, $submission_id);
    if ($stmt->execute()) {
        $message = "Submission #{$submission_id} updated to " . ucfirst($approval_action) . ".";
    } else {
        $message = "Error updating submission #" . $submission_id . ": " . $stmt->error;
    }
    $stmt->close();
}

// Function to fetch pending parameter submissions
function getPendingSubmissions($conn)
{
    $pending = [];
    // Using the "created_at" column as the submission date.
    $sql = "SELECT id, created_at AS submission_date, approval_status 
            FROM parameters_submissions 
            WHERE approval_status = 'pending' 
            ORDER BY created_at DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pending[] = $row;
        }
    }
    return $pending;
}

$pending_submissions = getPendingSubmissions($conn);
$pending_count = count($pending_submissions);

// Retrieve approval history (approved or declined submissions)
// Note: Removed approval_comment from the query
$sql_history = "SELECT id, created_at AS submission_date, approval_status, qa_reviewer, supervisor_reviewer 
                FROM parameters_submissions 
                WHERE approval_status != 'pending' 
                ORDER BY created_at DESC";
$result_history = $conn->query($sql_history);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parameters - Approvals</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar (reuse your existing navbar code) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.php">Sentinel Digitization</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a href="../logout.php" class="nav-link">Logout</a></li>
        </ul>
    </nav>

    <div class="container mt-4">
        <h1>Parameters Submissions Approval</h1>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <h2>
            Pending Submissions 
            <span class="badge bg-danger"><?php echo $pending_count; ?></span>
        </h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($pending_count > 0): ?>
                    <?php foreach ($pending_submissions as $submission): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($submission['id']); ?></td>
                            <td><?php echo htmlspecialchars($submission['submission_date']); ?></td>
                            <td><?php echo htmlspecialchars($submission['approval_status']); ?></td>
                            <td>
                                <!-- Quick Approval/Decline Buttons -->
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="submission_id" value="<?php echo $submission['id']; ?>">
                                    <button type="submit" name="approval_action" value="approved" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="submission_id" value="<?php echo $submission['id']; ?>">
                                    <button type="submit" name="approval_action" value="declined" class="btn btn-danger btn-sm">Decline</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No pending submissions</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Approval History</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Submission Date</th>
                    <th>Status</th>
                    <th>QA Reviewer</th>
                    <th>Supervisor Reviewer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_history && $result_history->num_rows > 0) {
                    while ($row = $result_history->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['submission_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['approval_status']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['qa_reviewer']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['supervisor_reviewer']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No history found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
