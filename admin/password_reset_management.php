<?php
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Include PHPMailer files
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection details
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "admin_sentinel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Function to send approval/denial email to user
function sendUserNotificationEmail($id_number, $full_name, $status, $admin_comment = '', $new_password = '') {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sentinel.dms.notifications@gmail.com';
        $mail->Password = 'zmys tnix xjjp jbsz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('sentinel.dms.notifications@gmail.com', 'Sentinel Password Reset');
        $mail->addAddress('user.notifications@gmail.com'); // You might want to add user-specific emails

        $mail->isHTML(true);
        
        if ($status === 'approved') {
            $mail->Subject = "Password Reset Approved - ID: {$id_number}";
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #28a745; text-align: center;'>Password Reset Approved</h2>
                    <div style='background-color: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                        <p>Dear <strong>{$full_name}</strong>,</p>
                        <p>Your password reset request has been <strong>approved</strong> by an administrator.</p>
                        <p><strong>Employee ID:</strong> {$id_number}</p>
                        <p><strong>New Temporary Password:</strong> <code style='background-color: #f8f9fa; padding: 2px 4px; border-radius: 3px;'>{$new_password}</code></p>
                    </div>
                    
                    <div style='background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <h4 style='color: #856404;'>Important Instructions:</h4>
                        <ol style='color: #856404;'>
                            <li>Log in with your new temporary password</li>
                            <li>You will be prompted to change your password immediately</li>
                            <li>Choose a strong, unique password</li>
                            <li>Keep your password secure and do not share it</li>
                        </ol>
                    </div>";
            
            if (!empty($admin_comment)) {
                $body .= "
                    <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <h4>Admin Comment:</h4>
                        <p>" . nl2br(htmlspecialchars($admin_comment)) . "</p>
                    </div>";
            }
            
            $body .= "
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='http://localhost/Sentinel/login.html' 
                           style='background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                            Login Now
                        </a>
                    </div>
                </div>";
        } else {
            $mail->Subject = "Password Reset Denied - ID: {$id_number}";
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #dc3545; text-align: center;'>Password Reset Denied</h2>
                    <div style='background-color: #f8d7da; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                        <p>Dear <strong>{$full_name}</strong>,</p>
                        <p>Your password reset request has been <strong>denied</strong> by an administrator.</p>
                        <p><strong>Employee ID:</strong> {$id_number}</p>
                    </div>";
            
            if (!empty($admin_comment)) {
                $body .= "
                    <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <h4>Admin Comment:</h4>
                        <p>" . nl2br(htmlspecialchars($admin_comment)) . "</p>
                    </div>";
            }
            
            $body .= "
                    <div style='background-color: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <h4 style='color: #0c5460;'>What can you do?</h4>
                        <ul style='color: #0c5460;'>
                            <li>Contact your supervisor or IT department</li>
                            <li>Submit a new request with additional information</li>
                            <li>Speak with an administrator in person</li>
                        </ul>
                    </div>
                </div>";
        }
        
        $mail->Body = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['request_id'])) {
        $request_id = intval($_POST['request_id']);
        $action = $_POST['action'];
        $admin_comment = trim($_POST['admin_comment'] ?? '');
        $admin_id = $_SESSION['id_number'];
        
        if ($action === 'approve') {
            // Generate new temporary password
            $temp_password = 'Temp' . rand(1000, 9999) . '!';
            $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
            
            // Update password reset request
            $update_request_sql = "UPDATE password_reset_requests SET 
                                   status = 'approved', 
                                   admin_id = ?, 
                                   admin_response_date = NOW(), 
                                   admin_comment = ?,
                                   new_password_hash = ?
                                   WHERE id = ?";
            $update_stmt = $conn->prepare($update_request_sql);
            $update_stmt->bind_param("sssi", $admin_id, $admin_comment, $hashed_password, $request_id);
            
            if ($update_stmt->execute()) {
                // Get request details
                $get_request_sql = "SELECT id_number, full_name FROM password_reset_requests WHERE id = ?";
                $get_stmt = $conn->prepare($get_request_sql);
                $get_stmt->bind_param("i", $request_id);
                $get_stmt->execute();
                $request_result = $get_stmt->get_result();
                $request_data = $request_result->fetch_assoc();
                
                // Update user password and reset password_changed flag
                $update_user_sql = "UPDATE users SET password = ?, password_changed = 0 WHERE id_number = ?";
                $update_user_stmt = $conn->prepare($update_user_sql);
                $update_user_stmt->bind_param("ss", $hashed_password, $request_data['id_number']);
                
                if ($update_user_stmt->execute()) {
                    // Send email notification
                    sendUserNotificationEmail($request_data['id_number'], $request_data['full_name'], 'approved', $admin_comment, $temp_password);
                    $message = "<div class='alert alert-success'>Password reset request approved successfully. User has been notified.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Failed to update user password.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Failed to update request status.</div>";
            }
            
        } elseif ($action === 'deny') {
            // Update password reset request
            $update_request_sql = "UPDATE password_reset_requests SET 
                                   status = 'denied', 
                                   admin_id = ?, 
                                   admin_response_date = NOW(), 
                                   admin_comment = ?
                                   WHERE id = ?";
            $update_stmt = $conn->prepare($update_request_sql);
            $update_stmt->bind_param("ssi", $admin_id, $admin_comment, $request_id);
            
            if ($update_stmt->execute()) {
                // Get request details for email
                $get_request_sql = "SELECT id_number, full_name FROM password_reset_requests WHERE id = ?";
                $get_stmt = $conn->prepare($get_request_sql);
                $get_stmt->bind_param("i", $request_id);
                $get_stmt->execute();
                $request_result = $get_stmt->get_result();
                $request_data = $request_result->fetch_assoc();
                
                // Send email notification
                sendUserNotificationEmail($request_data['id_number'], $request_data['full_name'], 'denied', $admin_comment);
                $message = "<div class='alert alert-success'>Password reset request denied. User has been notified.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Failed to update request status.</div>";
            }
        }
    }
}

// Get all password reset requests
$requests_sql = "SELECT pr.*, u.role 
                 FROM password_reset_requests pr 
                 LEFT JOIN users u ON pr.id_number = u.id_number 
                 ORDER BY pr.request_date DESC";
$requests_result = $conn->query($requests_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Password Reset Management - Sentinel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.3.0/css/all.css" rel="stylesheet">
    <style>
        .status-pending { color: #ffc107; }
        .status-approved { color: #28a745; }
        .status-denied { color: #dc3545; }
        .card-header {
            background: linear-gradient(135deg, #005bea, #00c6fb);
            color: white;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">
                            <i class="fas fa-key"></i> Password Reset Management
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Reset Requests</h5>
                            <a href="../dms/index.php" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Employee ID</th>
                                        <th>Full Name</th>
                                        <th>Role</th>
                                        <th>Request Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Admin Response</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($requests_result && $requests_result->num_rows > 0): ?>
                                        <?php while ($row = $requests_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><strong><?php echo htmlspecialchars($row['id_number']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php echo htmlspecialchars($row['role'] ?? 'Unknown'); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M j, Y g:i A', strtotime($row['request_date'])); ?></td>
                                                <td>
                                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                        <?php echo htmlspecialchars(substr($row['request_reason'], 0, 100)); ?>
                                                        <?php if (strlen($row['request_reason']) > 100) echo '...'; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="status-<?php echo $row['status']; ?>">
                                                        <i class="fas fa-<?php echo $row['status'] === 'pending' ? 'clock' : ($row['status'] === 'approved' ? 'check-circle' : 'times-circle'); ?>"></i>
                                                        <?php echo ucfirst($row['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($row['admin_response_date']): ?>
                                                        <small>
                                                            <?php echo date('M j, Y', strtotime($row['admin_response_date'])); ?>
                                                            <br>by: <?php echo htmlspecialchars($row['admin_id']); ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-muted">Pending</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($row['status'] === 'pending'): ?>
                                                        <button class="btn btn-sm btn-success me-1" 
                                                                onclick="showActionModal(<?php echo $row['id']; ?>, 'approve', '<?php echo htmlspecialchars($row['full_name']); ?>')">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" 
                                                                onclick="showActionModal(<?php echo $row['id']; ?>, 'deny', '<?php echo htmlspecialchars($row['full_name']); ?>')">
                                                            <i class="fas fa-times"></i> Deny
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-muted">Processed</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No password reset requests found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="actionModalTitle">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="request_id" id="actionRequestId">
                        <input type="hidden" name="action" id="actionType">
                        
                        <p id="actionMessage"></p>
                        
                        <div class="mb-3">
                            <label for="admin_comment" class="form-label">Comment (Optional)</label>
                            <textarea class="form-control" id="admin_comment" name="admin_comment" rows="3" 
                                      placeholder="Add any comments for the user..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" id="actionSubmitBtn">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showActionModal(requestId, action, userName) {
            document.getElementById('actionRequestId').value = requestId;
            document.getElementById('actionType').value = action;
            
            const modal = document.getElementById('actionModal');
            const title = document.getElementById('actionModalTitle');
            const message = document.getElementById('actionMessage');
            const submitBtn = document.getElementById('actionSubmitBtn');
            
            if (action === 'approve') {
                title.textContent = 'Approve Password Reset';
                message.innerHTML = `Are you sure you want to approve the password reset request for <strong>${userName}</strong>?<br><small class="text-muted">A new temporary password will be generated and sent to the user.</small>`;
                submitBtn.className = 'btn btn-success';
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Approve Request';
            } else {
                title.textContent = 'Deny Password Reset';
                message.innerHTML = `Are you sure you want to deny the password reset request for <strong>${userName}</strong>?`;
                submitBtn.className = 'btn btn-danger';
                submitBtn.innerHTML = '<i class="fas fa-times"></i> Deny Request';
            }
            
            new bootstrap.Modal(modal).show();
        }
    </script>
</body>
</html>
