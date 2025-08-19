<?php
session_start();

echo "<h2>Session Debug Information</h2>\n";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>\n";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>\n";

echo "<h3>Session Variables</h3>\n";
if (empty($_SESSION)) {
    echo "<p style='color: red;'>❌ No session variables found!</p>\n";
} else {
    echo "<ul>\n";
    foreach ($_SESSION as $key => $value) {
        echo "<li><strong>{$key}:</strong> " . htmlspecialchars(print_r($value, true)) . "</li>\n";
    }
    echo "</ul>\n";
}

echo "<h3>Session Configuration</h3>\n";
echo "<p><strong>session.gc_maxlifetime:</strong> " . ini_get('session.gc_maxlifetime') . " seconds</p>\n";
echo "<p><strong>session.cookie_lifetime:</strong> " . ini_get('session.cookie_lifetime') . " seconds</p>\n";
echo "<p><strong>session.save_path:</strong> " . ini_get('session.save_path') . "</p>\n";

echo "<h3>Access Test</h3>\n";
if (isset($_SESSION['role'])) {
    echo "<p><strong>Role detected:</strong> " . $_SESSION['role'] . "</p>\n";
    if ($_SESSION['role'] === 'admin') {
        echo "<p style='color: green;'>✅ Should have admin access</p>\n";
    } else {
        echo "<p style='color: orange;'>⚠️ Not admin role (role: " . $_SESSION['role'] . ")</p>\n";
    }
} else {
    echo "<p style='color: red;'>❌ No role in session</p>\n";
}

if (isset($_SESSION['full_name'])) {
    echo "<p><strong>User:</strong> " . $_SESSION['full_name'] . "</p>\n";
} else {
    echo "<p style='color: red;'>❌ User not logged in</p>\n";
}

echo "<h3>Test Links</h3>\n";
echo "<p><a href='index.php'>Dashboard</a> | <a href='admin/users.php'>Admin Users</a> | <a href='login.html'>Login</a></p>\n";
?>
