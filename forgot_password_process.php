<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

session_start();

// Load centralized database configuration
require_once __DIR__ . '/includes/database.php';

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get database connections
try {
    $conn = DatabaseManager::getConnection('sentinel_admin');
    $user_conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    header("Location: forgot_password.html?error=" . urlencode("Database connection error. Please try again later."));
    exit();
}

// Function to send notification email to all admins
function sendAdminNotificationEmail($request_id, $id_number, $full_name, $reason) {
    // Get all admin email addresses (if email field exists) or use a default admin email
    $admin_email = 'injectiondigitization@gmail.com'; // Default admin email
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sentinel.dms.notifications@gmail.com';
        $mail->Password = 'zmys tnix xjjp jbsz'; // Use secure storage for credentials
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('sentinel.dms.notifications@gmail.com', 'Sentinel Password Reset');
        $mail->addAddress($admin_email);

        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request - ID: {$id_number}";
        
        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #005bea; text-align: center;'>Password Reset Request</h2>
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                    <h3>Request Details:</h3>
                    <p><strong>Request ID:</strong> #{$request_id}</p>
                    <p><strong>Employee ID:</strong> {$id_number}</p>
                    <p><strong>Full Name:</strong> {$full_name}</p>
                    <p><strong>Request Date:</strong> " . date('Y-m-d H:i:s') . "</p>
                </div>
                
                <div style='background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <h4 style='color: #856404;'>Reason for Reset:</h4>
                    <p style='color: #856404;'>" . nl2br(htmlspecialchars($reason)) . "</p>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <p>Please log in to the admin panel to review and process this request.</p>
                    <a href='http://143.198.215.249/Sentinel/admin/password_reset_management.php' 
                       style='background-color: #005bea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                        Review Request
                    </a>
                </div>
                
                <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; font-size: 12px; color: #6c757d;'>
                    <p>This is an automated message from the Sentinel System. Please do not reply to this email.</p>
                </div>
            </div>
        ";
        
        $mail->Body = $body;
        $mail->AltBody = "Password Reset Request - ID: {$id_number}, Name: {$full_name}, Reason: {$reason}";
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_number = trim($_POST['id_number'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $reason = trim($_POST['reason'] ?? '');
    
    // Validate input
    if (empty($id_number) || empty($full_name) || empty($reason)) {
        header("Location: forgot_password.html?error=" . urlencode("All fields are required."));
        exit();
    }
    
    // Validate ID number format
    if (!preg_match('/^\d{1,6}$/', $id_number)) {
        header("Location: forgot_password.html?error=" . urlencode("Invalid ID number format."));
        exit();
    }
    
    // Check if user exists in submissions table (dailymonitoringsheet database)
    $check_user_sql = "SELECT DISTINCT name FROM submissions WHERE name = ?";
    $check_stmt = $user_conn->prepare($check_user_sql);
    $check_stmt->bind_param("s", $full_name);
    $check_stmt->execute();
    $user_result = $check_stmt->get_result();
    
    if ($user_result->num_rows === 0) {
        header("Location: forgot_password.html?error=" . urlencode("User not found. Please check your full name."));
        exit();
    }
    
    // Ensure user exists in admin_sentinel.users table for foreign key constraint
    $check_admin_user_sql = "SELECT id_number FROM users WHERE id_number = ?";
    $admin_check_stmt = $conn->prepare($check_admin_user_sql);
    $admin_check_stmt->bind_param("s", $id_number);
    $admin_check_stmt->execute();
    $admin_user_result = $admin_check_stmt->get_result();
    
    // If user doesn't exist in admin_sentinel.users, create them
    if ($admin_user_result->num_rows === 0) {
        $email = strtolower(str_replace(' ', '.', $full_name)) . '@company.com';
        $default_password = password_hash('default123', PASSWORD_DEFAULT);
        
        // Check if email already exists (to prevent duplicate email errors)
        $check_email_sql = "SELECT id_number FROM users WHERE email = ?";
        $email_check_stmt = $conn->prepare($check_email_sql);
        $email_check_stmt->bind_param("s", $email);
        $email_check_stmt->execute();
        $email_result = $email_check_stmt->get_result();
        
        if ($email_result->num_rows === 0) {
            // Email doesn't exist, safe to create user
            $create_user_sql = "INSERT INTO users (id_number, full_name, email, password_hash, role) VALUES (?, ?, ?, ?, 'user')";
            $create_stmt = $conn->prepare($create_user_sql);
            $create_stmt->bind_param("ssss", $id_number, $full_name, $email, $default_password);
            
            if (!$create_stmt->execute()) {
                error_log("Failed to create user in admin_sentinel: " . $create_stmt->error);
                // Continue anyway, as this is not critical for password reset functionality
            }
        } else {
            // Email exists but different ID number - use a unique email
            $email = strtolower(str_replace(' ', '.', $full_name)) . '.' . $id_number . '@company.com';
            $create_user_sql = "INSERT INTO users (id_number, full_name, email, password_hash, role) VALUES (?, ?, ?, ?, 'user')";
            $create_stmt = $conn->prepare($create_user_sql);
            $create_stmt->bind_param("ssss", $id_number, $full_name, $email, $default_password);
            
            if (!$create_stmt->execute()) {
                error_log("Failed to create user in admin_sentinel with unique email: " . $create_stmt->error);
                // Continue anyway, as this is not critical for password reset functionality
            }
        }
    }
    
    // Check if there's already a pending request for this user
    $check_pending_sql = "SELECT id FROM password_reset_requests WHERE id_number = ? AND status = 'pending'";
    $pending_stmt = $conn->prepare($check_pending_sql);
    $pending_stmt->bind_param("s", $id_number);
    $pending_stmt->execute();
    $pending_result = $pending_stmt->get_result();
    
    if ($pending_result->num_rows > 0) {
        header("Location: forgot_password.html?error=" . urlencode("You already have a pending password reset request. Please wait for admin approval."));
        exit();
    }
    
    // Generate reset token
    $reset_token = bin2hex(random_bytes(32));
    
    // Insert password reset request
    $insert_sql = "INSERT INTO password_reset_requests (id_number, full_name, request_reason, reset_token) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssss", $id_number, $full_name, $reason, $reset_token);
    
    if ($insert_stmt->execute()) {
        $request_id = $conn->insert_id;
        
        // Email notifications disabled as requested
        // $email_sent = sendAdminNotificationEmail($request_id, $id_number, $full_name, $reason);
        
        $success_message = "Your password reset request has been submitted successfully. Administrators will review your request shortly.";
        
        header("Location: forgot_password.html?success=" . urlencode($success_message));
        exit();
    } else {
        header("Location: forgot_password.html?error=" . urlencode("Failed to submit your request. Please try again."));
        exit();
    }
} else {
    // Redirect to forgot password page if accessed directly
    header("Location: forgot_password.html");
    exit();
}

$conn->close();
$user_conn->close();
?>
