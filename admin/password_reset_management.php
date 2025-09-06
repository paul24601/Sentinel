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

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_admin');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
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
                    // Email notifications disabled as requested
                    // sendUserNotificationEmail($request_data['id_number'], $request_data['full_name'], 'approved', $admin_comment, $temp_password);
                    $message = "<div class='alert alert-success'>Password reset request approved successfully. User password has been reset to 'injection'.</div>";
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

                // Email notifications disabled as requested
                // sendUserNotificationEmail($request_data['id_number'], $request_data['full_name'], 'denied', $admin_comment);
                $message = "<div class='alert alert-success'>Password reset request has been denied.</div>";
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

<?php include '../includes/navbar.php'; ?>

    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h1 class="text-primary"><i class="fas fa-shield-alt me-2"></i>Password Reset Management</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Password Reset Management</li>
                </ol>
            </div>
            <div class="page-actions">
                <button class="btn btn-outline-primary" onclick="refreshData()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="message-container mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Enhanced Statistics Cards -->
        <div class="row mb-4">
            <?php
            // Calculate statistics
            $total_requests = $requests_result->num_rows;
            $pending_count = 0;
            $approved_count = 0;
            $denied_count = 0;
            $today_count = 0;

            if ($requests_result) {
                $requests_result->data_seek(0); // Reset result pointer
                while ($row = $requests_result->fetch_assoc()) {
                    switch ($row['status']) {
                        case 'pending': $pending_count++; break;
                        case 'approved': $approved_count++; break;
                        case 'denied': $denied_count++; break;
                    }
                    if (date('Y-m-d', strtotime($row['request_date'])) === date('Y-m-d')) {
                        $today_count++;
                    }
                }
                $requests_result->data_seek(0); // Reset again for table display
            }
            ?>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?php echo $total_requests; ?></h3>
                                <p class="mb-0 opacity-75">Total Requests</p>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-list fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?php echo $pending_count; ?></h3>
                                <p class="mb-0 opacity-75">Pending Reviews</p>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-clock fa-2x opacity-75"></i>
                            </div>
                        </div>
                        <?php if ($pending_count > 0): ?>
                            <div class="mt-2">
                                <small class="badge bg-light text-dark">Requires Action</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?php echo $approved_count; ?></h3>
                                <p class="mb-0 opacity-75">Approved</p>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0"><?php echo $denied_count; ?></h3>
                                <p class="mb-0 opacity-75">Denied</p>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-times-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions Bar -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Password Reset Requests</h5>
                        <small class="text-muted">Manage and review user password reset requests</small>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-md-end">
                            <select class="form-select form-select-sm" id="statusFilter" onchange="filterByStatus()">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="denied">Denied</option>
                            </select>
                            <button class="btn btn-outline-secondary btn-sm" onclick="exportToCSV()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Main Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="passwordResetTable" class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="border-0"><i class="fas fa-hashtag me-1"></i>ID</th>
                                <th class="border-0"><i class="fas fa-id-badge me-1"></i>Employee</th>
                                <th class="border-0"><i class="fas fa-user me-1"></i>User Info</th>
                                <th class="border-0"><i class="fas fa-calendar me-1"></i>Request Date</th>
                                <th class="border-0"><i class="fas fa-comment me-1"></i>Reason</th>
                                <th class="border-0"><i class="fas fa-flag me-1"></i>Status</th>
                                <th class="border-0"><i class="fas fa-user-shield me-1"></i>Admin Response</th>
                                <th class="border-0 text-center"><i class="fas fa-cogs me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($requests_result && $requests_result->num_rows > 0): ?>
                                <?php while ($row = $requests_result->fetch_assoc()): ?>
                                    <tr class="request-row" data-status="<?php echo $row['status']; ?>">
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">#<?php echo $row['id']; ?></span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($row['id_number']); ?></div>
                                                    <small class="text-muted">Employee ID</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div>
                                                <div class="fw-bold text-primary"><?php echo htmlspecialchars($row['full_name']); ?></div>
                                                <span class="badge bg-secondary bg-opacity-25 text-dark">
                                                    <?php echo htmlspecialchars($row['role'] ?? 'Unknown'); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="text-nowrap">
                                                <div class="fw-semibold"><?php echo date('M j, Y', strtotime($row['request_date'])); ?></div>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i><?php echo date('g:i A', strtotime($row['request_date'])); ?>
                                                </small>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="reason-cell" style="max-width: 250px;">
                                                <div class="reason-preview">
                                                    <?php echo htmlspecialchars(substr($row['request_reason'], 0, 60)); ?>
                                                    <?php if (strlen($row['request_reason']) > 60): ?>
                                                        <button class="btn btn-link btn-sm p-0 ms-1" onclick="showFullReason('<?php echo htmlspecialchars($row['request_reason']); ?>')">
                                                            <small>...more</small>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                            $statusConfig = [
                                                'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Pending Review'],
                                                'approved' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Approved'],
                                                'denied' => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'Denied']
                                            ];
                                            $config = $statusConfig[$row['status']] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => 'Unknown'];
                                            ?>
                                            <span class="badge bg-<?php echo $config['class']; ?> status-badge">
                                                <i class="fas fa-<?php echo $config['icon']; ?> me-1"></i>
                                                <?php echo $config['text']; ?>
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <?php if ($row['admin_response_date']): ?>
                                                <div class="admin-response">
                                                    <div class="fw-semibold"><?php echo date('M j, Y', strtotime($row['admin_response_date'])); ?></div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user-shield me-1"></i>by: <?php echo htmlspecialchars($row['admin_id']); ?>
                                                    </small>
                                                    <?php if ($row['admin_comment']): ?>
                                                        <button class="btn btn-link btn-sm p-0 d-block" onclick="showAdminComment('<?php echo htmlspecialchars($row['admin_comment']); ?>')">
                                                            <small><i class="fas fa-comment me-1"></i>View Comment</small>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-hourglass-half me-1"></i>Awaiting Review
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="action-buttons">
                                                <?php if ($row['status'] === 'pending'): ?>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-success btn-sm action-btn" 
                                                                onclick="showActionModal(<?php echo $row['id']; ?>, 'approve', '<?php echo htmlspecialchars($row['full_name']); ?>')"
                                                                data-bs-toggle="tooltip" title="Approve Request">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm action-btn" 
                                                                onclick="showActionModal(<?php echo $row['id']; ?>, 'deny', '<?php echo htmlspecialchars($row['full_name']); ?>')"
                                                                data-bs-toggle="tooltip" title="Deny Request">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary bg-opacity-25 text-dark">
                                                        <i class="fas fa-check-double me-1"></i>Processed
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-icon mb-3">
                                                <i class="fas fa-inbox fa-4x text-muted"></i>
                                            </div>
                                            <h5 class="text-muted mb-2">No Password Reset Requests</h5>
                                            <p class="text-muted mb-0">When users submit password reset requests, they will appear here for your review.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

    <!-- Enhanced Action Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" id="actionForm">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="actionModalTitle">
                            <i class="fas fa-shield-alt me-2"></i>Confirm Action
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="request_id" id="actionRequestId">
                        <input type="hidden" name="action" id="actionType">

                        <div id="actionMessage" class="mb-4"></div>

                        <div class="mb-3">
                            <label for="admin_comment" class="form-label fw-semibold">
                                <i class="fas fa-comment me-2"></i>Admin Comment
                            </label>
                            <textarea class="form-control" id="admin_comment" name="admin_comment" rows="4" 
                                      placeholder="Add any comments for the user (optional)..."
                                      style="resize: none;"></textarea>
                            <div class="form-text">This comment will be stored for record keeping purposes.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn" id="actionSubmitBtn">
                            <i class="fas fa-check me-2"></i>Confirm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reason Detail Modal -->
    <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reasonModalLabel">
                        <i class="fas fa-comment-alt me-2"></i>Request Reason
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 bg-light rounded" id="reasonContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Comment Modal -->
    <div class="modal fade" id="adminCommentModal" tabindex="-1" aria-labelledby="adminCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminCommentModalLabel">
                        <i class="fas fa-user-shield me-2"></i>Admin Comment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 bg-light rounded" id="adminCommentContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for enhanced styling -->
    <style>
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        
        .avatar-circle {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .action-btn {
            transition: all 0.2s ease;
            border-width: 2px;
        }
        
        .action-btn:hover {
            transform: scale(1.05);
        }
        
        .status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        
        .request-row {
            transition: background-color 0.2s ease;
        }
        
        .request-row:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        
        .empty-state {
            padding: 3rem 1rem;
        }
        
        .empty-icon {
            opacity: 0.5;
        }
        
        .reason-cell {
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .admin-response {
            font-size: 0.85rem;
        }
        
        .page-actions .btn {
            transition: all 0.2s ease;
        }
        
        .page-actions .btn:hover {
            transform: translateY(-2px);
        }
        
        .message-container .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .btn-group .btn {
            border-radius: 6px;
        }
        
        .btn-group .btn:first-child {
            margin-right: 4px;
        }
        
        @media (max-width: 768px) {
            .action-buttons .btn-group {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                margin-bottom: 4px;
                font-size: 0.8rem;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    <!-- Enhanced JavaScript -->
    <script>
        // Initialize on document ready
        $(document).ready(function() {
            initializeDataTable();
            initializeTooltips();
            initializeAnimations();
            
            // Auto-refresh every 5 minutes
            setInterval(refreshData, 300000);
        });

        function initializeDataTable() {
            $('#passwordResetTable').DataTable({
                responsive: true,
                pageLength: 15,
                lengthMenu: [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "All"]],
                order: [[0, 'desc']],
                columnDefs: [
                    { 
                        targets: [7], // Actions column
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        targets: [4], // Reason column
                        orderable: false
                    }
                ],
                language: {
                    search: "Search requests:",
                    lengthMenu: "Show _MENU_ requests",
                    info: "Showing _START_ to _END_ of _TOTAL_ requests",
                    infoEmpty: "No requests available",
                    emptyTable: "No password reset requests found",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                initComplete: function() {
                    $('.dataTables_filter input')
                        .addClass('form-control')
                        .attr('placeholder', 'Search requests...')
                        .css('margin-left', '0.5rem');
                    $('.dataTables_length select').addClass('form-select');
                    
                    // Add custom styling
                    $('.dataTables_wrapper .dataTables_paginate .paginate_button').addClass('btn btn-sm');
                    $('.dataTables_wrapper .dataTables_paginate .paginate_button.current').addClass('btn-primary');
                }
            });
        }

        function initializeTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        function initializeAnimations() {
            // Animate statistics cards on load
            $('.stat-card').each(function(index) {
                $(this).delay(index * 100).animate({
                    opacity: 1
                }, 500);
            });

            // Add loading state to form submission
            $('#actionForm').on('submit', function() {
                const submitBtn = $('#actionSubmitBtn');
                const originalText = submitBtn.html();
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...')
                         .prop('disabled', true);

                setTimeout(function() {
                    submitBtn.html(originalText).prop('disabled', false);
                }, 3000);
            });
        }

        function showActionModal(requestId, action, userName) {
            document.getElementById('actionRequestId').value = requestId;
            document.getElementById('actionType').value = action;
            document.getElementById('admin_comment').value = '';

            const modal = document.getElementById('actionModal');
            const title = document.getElementById('actionModalTitle');
            const message = document.getElementById('actionMessage');
            const submitBtn = document.getElementById('actionSubmitBtn');

            if (action === 'approve') {
                title.innerHTML = '<i class="fas fa-check-circle me-2 text-success"></i>Approve Password Reset';
                message.innerHTML = `
                    <div class="alert alert-success border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Approve Request</h6>
                                <p class="mb-0">You are about to approve the password reset request for <strong>${userName}</strong>.</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div>
                                <small><strong>Note:</strong> The user's password will be reset to the default password "injection".</small>
                            </div>
                        </div>
                    </div>
                `;
                submitBtn.className = 'btn btn-success';
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Approve Request';
            } else {
                title.innerHTML = '<i class="fas fa-times-circle me-2 text-danger"></i>Deny Password Reset';
                message.innerHTML = `
                    <div class="alert alert-danger border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-times-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Deny Request</h6>
                                <p class="mb-0">You are about to deny the password reset request for <strong>${userName}</strong>.</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-warning border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                            <div>
                                <small><strong>Recommendation:</strong> Please add a comment explaining why the request was denied.</small>
                            </div>
                        </div>
                    </div>
                `;
                submitBtn.className = 'btn btn-danger';
                submitBtn.innerHTML = '<i class="fas fa-times me-2"></i>Deny Request';
            }

            new bootstrap.Modal(modal).show();
        }

        function showFullReason(reason) {
            document.getElementById('reasonContent').textContent = reason;
            new bootstrap.Modal(document.getElementById('reasonModal')).show();
        }

        function showAdminComment(comment) {
            document.getElementById('adminCommentContent').innerHTML = comment.replace(/\n/g, '<br>');
            new bootstrap.Modal(document.getElementById('adminCommentModal')).show();
        }

        function filterByStatus() {
            const filter = document.getElementById('statusFilter').value;
            const table = $('#passwordResetTable').DataTable();
            
            if (filter === '') {
                table.columns(5).search('').draw();
            } else {
                table.columns(5).search(filter).draw();
            }
        }

        function refreshData() {
            const currentUrl = window.location.href;
            const hasParams = currentUrl.includes('?');
            window.location.href = currentUrl + (hasParams ? '&' : '?') + 'refresh=' + Date.now();
        }

        function exportToCSV() {
            // Basic CSV export functionality
            const table = document.getElementById('passwordResetTable');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cols = row.querySelectorAll('td, th');
                let csvRow = [];
                
                for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
                    csvRow.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
                }
                csv.push(csvRow.join(','));
            }
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'password_reset_requests_' + new Date().toISOString().split('T')[0] + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                refreshData();
            }
        });
    </script>

<?php include '../includes/navbar_close.php'; ?>
