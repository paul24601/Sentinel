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

<style>
    .status-pending { color: #ffc107; font-weight: bold; }
    .status-approved { color: #28a745; font-weight: bold; }
    .status-denied { color: #dc3545; font-weight: bold; }
    
    .card-header {
        background: linear-gradient(135deg, #005bea, #00c6fb);
        color: white;
    }
    
    /* Statistics Cards */
    .stats-cards {
        margin-bottom: 2rem;
    }
    
    .stat-card {
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-icon {
        font-size: 2rem;
        margin-right: 1rem;
    }
    
    /* Table Improvements */
    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .search-bar {
        background: #f8f9fa;
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .action-buttons .btn {
        margin: 2px;
    }
    
    /* DataTable custom styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        padding: 6px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin-left: 0.25rem;
        border-radius: 0.25rem;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5rem;
    }
    
    /* Responsive table improvements */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .action-buttons .btn {
            display: block;
            width: 100%;
            margin: 2px 0;
        }
        
        .stats-cards .col-xl-3 {
            margin-bottom: 1rem;
        }
    }
    
    /* Main content area fix */
    #layoutSidenav_content {
        width: 100%;
        overflow-x: hidden;
    }
    
    #layoutSidenav_content main {
        flex: 1 0 auto;
        width: 100%;
    }
    
    .container-fluid {
        max-width: 100%;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    
    /* Empty state styling */
    .empty-state {
        padding: 3rem 2rem;
    }
    
    .empty-state i {
        opacity: 0.5;
    }
</style>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Password Reset Management</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Password Reset Management</li>
        </ol>

        <?php echo $message; ?>

        <!-- Statistics Cards -->
        <div class="row stats-cards">
            <?php
            // Calculate statistics
            $total_requests = $requests_result->num_rows;
            $pending_count = 0;
            $approved_count = 0;
            $denied_count = 0;
            
            if ($requests_result) {
                $requests_result->data_seek(0); // Reset result pointer
                while ($row = $requests_result->fetch_assoc()) {
                    switch ($row['status']) {
                        case 'pending': $pending_count++; break;
                        case 'approved': $approved_count++; break;
                        case 'denied': $denied_count++; break;
                    }
                }
                $requests_result->data_seek(0); // Reset again for table display
            }
            ?>
            
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-primary text-white mb-4">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-list stat-icon"></i>
                        <div>
                            <div class="h4 mb-0"><?php echo $total_requests; ?></div>
                            <div>Total Requests</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-warning text-white mb-4">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-clock stat-icon"></i>
                        <div>
                            <div class="h4 mb-0"><?php echo $pending_count; ?></div>
                            <div>Pending</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-success text-white mb-4">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-check-circle stat-icon"></i>
                        <div>
                            <div class="h4 mb-0"><?php echo $approved_count; ?></div>
                            <div>Approved</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-danger text-white mb-4">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-times-circle stat-icon"></i>
                        <div>
                            <div class="h4 mb-0"><?php echo $denied_count; ?></div>
                            <div>Denied</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="table-container">
            <div class="card-header">
                <h3 class="mb-0">
                    <i class="fas fa-key"></i> Password Reset Requests
                </h3>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="passwordResetTable" class="table table-striped table-hover mb-0">
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
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($requests_result && $requests_result->num_rows > 0): ?>
                                <?php while ($row = $requests_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><span class="badge bg-info"><?php echo htmlspecialchars($row['id_number']); ?></span></td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($row['full_name']); ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo htmlspecialchars($row['role'] ?? 'Unknown'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <?php echo date('M j, Y', strtotime($row['request_date'])); ?>
                                                <br><span class="text-muted"><?php echo date('g:i A', strtotime($row['request_date'])); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-width: 200px;">
                                                <span title="<?php echo htmlspecialchars($row['request_reason']); ?>" 
                                                      data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <?php echo htmlspecialchars(substr($row['request_reason'], 0, 50)); ?>
                                                    <?php if (strlen($row['request_reason']) > 50) echo '...'; ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusIcon = '';
                                            switch($row['status']) {
                                                case 'pending':
                                                    $statusClass = 'warning';
                                                    $statusIcon = 'clock';
                                                    break;
                                                case 'approved':
                                                    $statusClass = 'success';
                                                    $statusIcon = 'check-circle';
                                                    break;
                                                case 'denied':
                                                    $statusClass = 'danger';
                                                    $statusIcon = 'times-circle';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <i class="fas fa-<?php echo $statusIcon; ?> me-1"></i>
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['admin_response_date']): ?>
                                                <div class="small">
                                                    <div class="fw-bold"><?php echo date('M j, Y', strtotime($row['admin_response_date'])); ?></div>
                                                    <div class="text-muted">by: <?php echo htmlspecialchars($row['admin_id']); ?></div>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <?php if ($row['status'] === 'pending'): ?>
                                                    <button class="btn btn-sm btn-success" 
                                                            onclick="showActionModal(<?php echo $row['id']; ?>, 'approve', '<?php echo htmlspecialchars($row['full_name']); ?>')"
                                                            data-bs-toggle="tooltip" title="Approve Request">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="showActionModal(<?php echo $row['id']; ?>, 'deny', '<?php echo htmlspecialchars($row['full_name']); ?>')"
                                                            data-bs-toggle="tooltip" title="Deny Request">
                                                        <i class="fas fa-times"></i> Deny
                                                    </button>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-check-double me-1"></i>Processed
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted">No password reset requests found</h5>
                                            <p class="text-muted mb-0">When users submit password reset requests, they will appear here.</p>
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

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            $('#passwordResetTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']], // Sort by ID descending (newest first)
                columnDefs: [
                    { 
                        targets: [8], // Actions column
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: [5], // Reason column
                        orderable: false
                    }
                ],
                language: {
                    search: "Search requests:",
                    lengthMenu: "Show _MENU_ requests per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ password reset requests",
                    infoEmpty: "No requests available",
                    emptyTable: "No password reset requests found",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                initComplete: function() {
                    // Add custom styling to search box
                    $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Search requests...');
                    $('.dataTables_length select').addClass('form-select');
                }
            });
        });

        function showActionModal(requestId, action, userName) {
            document.getElementById('actionRequestId').value = requestId;
            document.getElementById('actionType').value = action;
            document.getElementById('admin_comment').value = ''; // Clear previous comment
            
            const modal = document.getElementById('actionModal');
            const title = document.getElementById('actionModalTitle');
            const message = document.getElementById('actionMessage');
            const submitBtn = document.getElementById('actionSubmitBtn');
            
            if (action === 'approve') {
                title.textContent = 'Approve Password Reset';
                message.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Are you sure you want to approve the password reset request for <strong>${userName}</strong>?
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <small>The user's password will be reset to the default password "injection".</small>
                    </div>
                `;
                submitBtn.className = 'btn btn-success';
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Approve Request';
            } else {
                title.textContent = 'Deny Password Reset';
                message.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        Are you sure you want to deny the password reset request for <strong>${userName}</strong>?
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <small>Please consider adding a comment explaining why the request was denied.</small>
                    </div>
                `;
                submitBtn.className = 'btn btn-danger';
                submitBtn.innerHTML = '<i class="fas fa-times"></i> Deny Request';
            }
            
            new bootstrap.Modal(modal).show();
        }

        // Add some animation and feedback
        $(document).ready(function() {
            // Animate statistics cards on load
            $('.stat-card').each(function(index) {
                $(this).delay(index * 100).animate({
                    opacity: 1
                }, 500);
            });

            // Add hover effects to action buttons
            $('.action-buttons .btn').hover(
                function() {
                    $(this).removeClass('btn-sm').addClass('btn-md');
                },
                function() {
                    $(this).removeClass('btn-md').addClass('btn-sm');
                }
            );

            // Auto-refresh table every 5 minutes
            setInterval(function() {
                location.reload();
            }, 300000); // 5 minutes
        });

        // Add loading state to form submission
        $('form').on('submit', function() {
            const submitBtn = $('#actionSubmitBtn');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
            
            // Re-enable after 3 seconds in case of slow response
            setTimeout(function() {
                submitBtn.html(originalText).prop('disabled', false);
            }, 3000);
        });
    </script>

<?php include '../includes/navbar_close.php'; ?>
