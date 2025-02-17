<?php
session_start();

// Only allow supervisors and admins to access this page
if (!isset($_SESSION['role']) || 
    ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'supervisor')) {
    header("Location: ../login.html");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "Admin123@plvil";
$dbname = "dailymonitoringsheet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process approval/decline form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submission_id']) && isset($_POST['approval_action'])) {
    $submission_id  = intval($_POST['submission_id']);
    $approval_action = $_POST['approval_action']; // Either "approve" or "decline"
    
    $new_status = ($approval_action === 'approve') ? 'approved' : 'declined';

    // Use a prepared statement for updating the record
    $stmt = $conn->prepare("UPDATE submissions SET approval_status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $submission_id);
    if ($stmt->execute()) {
        $message = "Submission #{$submission_id} has been updated successfully.";
    } else {
        $message = "Error updating submission #" . $submission_id;
    }
    $stmt->close();
}

// Retrieve all submissions (you can modify the query to show only pending submissions if desired)
$sql = "SELECT * FROM submissions ORDER BY date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Submissions Approval</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
       body { padding: 2rem; }
       table { font-size: 0.9rem; }
   </style>
</head>
<body class="container">
   <h1 class="mb-4">Submissions Approval Page</h1>
   
   <?php if ($message): ?>
       <div class="alert alert-info"><?php echo $message; ?></div>
   <?php endif; ?>

   <div class="table-responsive">
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
                   <th>Name</th>
                   <th>Shift</th>
                   <th>Approval Status</th>
                   <th>Action</th>
               </tr>
           </thead>
           <tbody>
               <?php 
               if ($result && $result->num_rows > 0) {
                   while ($row = $result->fetch_assoc()) {
                       echo "<tr>";
                       echo "<td>" . $row['id'] . "</td>";
                       echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['machine']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['prn']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['mold_code']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['cycle_time_target']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['cycle_time_actual']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['weight_standard']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['weight_gross']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['weight_net']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['cavity_designed']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['cavity_active']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['remarks']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                       echo "<td>" . htmlspecialchars($row['shift']) . "</td>";
                       echo "<td>" . ucfirst(htmlspecialchars($row['approval_status'])) . "</td>";
                       echo "<td>";
                       
                       // Only show action buttons if status is "pending"
                       if ($row['approval_status'] === 'pending') {
                           ?>
                           <form method="post" style="display:inline-block;">
                               <input type="hidden" name="submission_id" value="<?php echo $row['id']; ?>">
                               <button type="submit" name="approval_action" value="approve" class="btn btn-success btn-sm">Approve</button>
                           </form>
                           <form method="post" style="display:inline-block; margin-left:5px;">
                               <input type="hidden" name="submission_id" value="<?php echo $row['id']; ?>">
                               <button type="submit" name="approval_action" value="decline" class="btn btn-danger btn-sm">Decline</button>
                           </form>
                           <?php
                       } else {
                           echo "No action";
                       }
                       echo "</td>";
                       echo "</tr>";
                   }
               } else {
                   echo "<tr><td colspan='18' class='text-center'>No submissions found.</td></tr>";
               }
               ?>
           </tbody>
       </table>
   </div>
   
   <a href="../index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
   
   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
