<?php
require_once 'session_config.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admin privileges required.");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "injectionmoldingparameters";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include the user activity logger
require_once 'user_activity_logger.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unknown Users Cleanup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Unknown Users Management</h1>
        
        <?php if (isset($_POST['fix_records'])): ?>
            <div class="alert alert-success">
                <?php
                $record_ids = $_POST['record_ids'] ?? [];
                $correct_user = $_POST['correct_user'] ?? '';
                
                if (!empty($record_ids) && !empty($correct_user)) {
                    if (fixUnknownUserRecords($conn, $record_ids, $correct_user)) {
                        echo "Successfully updated " . count($record_ids) . " records with user: " . htmlspecialchars($correct_user);
                    } else {
                        echo "Error updating records.";
                    }
                } else {
                    echo "Please select records and specify a user name.";
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Unknown User Records</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $unknown_records = checkForUnknownUsers($conn);
                        if (!empty($unknown_records)): ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="correct_user" class="form-label">Correct User Name:</label>
                                    <input type="text" class="form-control" id="correct_user" name="correct_user" required>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>Record ID</th>
                                                <th>Current User</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($unknown_records as $record): ?>
                                                <tr>
                                                    <td><input type="checkbox" name="record_ids[]" value="<?= htmlspecialchars($record['record_id']) ?>"></td>
                                                    <td><?= htmlspecialchars($record['record_id']) ?></td>
                                                    <td><?= htmlspecialchars($record['submitted_by'] ?: 'NULL') ?></td>
                                                    <td><?= htmlspecialchars($record['submission_date']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <button type="submit" name="fix_records" class="btn btn-primary">Fix Selected Records</button>
                            </form>
                        <?php else: ?>
                            <p class="text-success">No unknown user records found!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Recent User Activity</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $activity_sql = "SELECT * FROM user_activity ORDER BY created_at DESC LIMIT 10";
                        $activity_result = $conn->query($activity_sql);
                        
                        if ($activity_result && $activity_result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Activity</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($activity = $activity_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($activity['full_name']) ?></td>
                                                <td><?= htmlspecialchars($activity['activity_type']) ?></td>
                                                <td><?= htmlspecialchars($activity['created_at']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>No activity logged yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="submission.php" class="btn btn-secondary">Back to Records</a>
        </div>
    </div>

    <script>
        // Select all checkbox functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="record_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
