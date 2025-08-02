<?php
// Test script to verify the entire forgot password workflow
echo "<h1>Forgot Password System Test</h1>";

$servername = "localhost";
$username = "root";
$password = "injectionadmin123";

echo "<h2>Testing Complete Workflow</h2>";

// 1. Check database connections
echo "<h3>1. Database Connections</h3>";
$admin_conn = new mysqli($servername, $username, $password, "admin_sentinel");
$user_conn = new mysqli($servername, $username, $password, "dailymonitoringsheet");

if ($admin_conn->connect_error) {
    echo "❌ admin_sentinel connection failed<br>";
} else {
    echo "✅ admin_sentinel connection successful<br>";
}

if ($user_conn->connect_error) {
    echo "❌ dailymonitoringsheet connection failed<br>";
} else {
    echo "✅ dailymonitoringsheet connection successful<br>";
}

// 2. Check table structures
echo "<h3>2. Database Tables</h3>";
$tables_check = [
    'admin_sentinel.users' => 'SELECT COUNT(*) as count FROM users',
    'admin_sentinel.password_reset_requests' => 'SELECT COUNT(*) as count FROM password_reset_requests',
    'dailymonitoringsheet.submissions' => 'SELECT COUNT(DISTINCT name) as count FROM submissions'
];

foreach ($tables_check as $table => $query) {
    if (strpos($table, 'admin_sentinel') !== false) {
        $result = $admin_conn->query($query);
    } else {
        $result = $user_conn->query($query);
    }
    
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ $table: {$row['count']} records<br>";
    } else {
        echo "❌ $table: Error accessing table<br>";
    }
}

// 3. Check file accessibility
echo "<h3>3. File Accessibility</h3>";
$files_to_test = [
    'forgot_password.html',
    'forgot_password_process.php',
    'admin/password_reset_management.php',
    'admin/users.php'
];

foreach ($files_to_test as $file) {
    $url = "http://143.198.215.249/$file";
    echo "📝 <a href='$url' target='_blank'>$file</a> - ";
    if (file_exists($file)) {
        echo "✅ File exists<br>";
    } else {
        echo "❌ File missing<br>";
    }
}

// 4. Check for pending requests
echo "<h3>4. Current Status</h3>";
$pending_result = $admin_conn->query("SELECT COUNT(*) as count FROM password_reset_requests WHERE status = 'pending'");
if ($pending_result) {
    $pending_row = $pending_result->fetch_assoc();
    echo "📋 Pending password reset requests: {$pending_row['count']}<br>";
}

$all_requests = $admin_conn->query("SELECT * FROM password_reset_requests ORDER BY created_at DESC LIMIT 5");
if ($all_requests && $all_requests->num_rows > 0) {
    echo "<h4>Recent Password Reset Requests:</h4>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>ID Number</th><th>Full Name</th><th>Status</th><th>Created</th><th>Reason</th></tr>";
    while ($row = $all_requests->fetch_assoc()) {
        $status_color = $row['status'] == 'pending' ? 'orange' : ($row['status'] == 'approved' ? 'green' : 'red');
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['id_number']}</td>";
        echo "<td>{$row['full_name']}</td>";
        echo "<td style='color: $status_color; font-weight: bold;'>{$row['status']}</td>";
        echo "<td>{$row['created_at']}</td>";
        echo "<td>" . substr($row['request_reason'], 0, 50) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "📋 No password reset requests found<br>";
}

// 5. Test workflow summary
echo "<h3>5. Workflow Summary</h3>";
echo "<div style='background-color: #e7f3ff; border: 1px solid #b3d9ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h4>How the Password Reset System Works:</h4>";
echo "<ol>";
echo "<li><strong>User Request:</strong> User fills out form at <a href='forgot_password.html' target='_blank'>forgot_password.html</a></li>";
echo "<li><strong>Validation:</strong> System checks if user exists in the submissions table</li>";
echo "<li><strong>Storage:</strong> Request is saved to admin_sentinel.password_reset_requests table</li>";
echo "<li><strong>Notification:</strong> Email is sent to administrators</li>";
echo "<li><strong>Admin Review:</strong> Admin reviews requests at <a href='admin/password_reset_management.php' target='_blank'>password_reset_management.php</a></li>";
echo "<li><strong>User Management:</strong> Admin can also access via <a href='admin/users.php' target='_blank'>users.php</a> (with notification badge)</li>";
echo "<li><strong>Approval/Denial:</strong> Admin approves or denies the request</li>";
echo "<li><strong>User Notification:</strong> User receives email notification of decision</li>";
echo "</ol>";
echo "</div>";

// 6. Quick test form
echo "<h3>6. Quick Test</h3>";
echo "<div style='background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h4>Test the Forgot Password Form</h4>";
echo "<p>To test the system:</p>";
echo "<ol>";
echo "<li>Go to <a href='forgot_password.html' target='_blank'>forgot_password.html</a></li>";
echo "<li>Use a valid user name from your submissions table</li>";
echo "<li>Fill out the form and submit</li>";
echo "<li>Check <a href='admin/password_reset_management.php' target='_blank'>admin/password_reset_management.php</a> for the request</li>";
echo "<li>Process the request as an admin</li>";
echo "</ol>";
echo "</div>";

$admin_conn->close();
$user_conn->close();
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
h1, h2, h3 { color: #333; }
h1 { border-bottom: 3px solid #005bea; padding-bottom: 10px; }
h2 { border-bottom: 2px solid #ccc; padding-bottom: 8px; }
h3 { border-bottom: 1px solid #ddd; padding-bottom: 5px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f1f1f1; }
a { color: #005bea; }
</style>
