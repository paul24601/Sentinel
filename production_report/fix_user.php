<?php
session_start();

require_once __DIR__ . '/../includes/database.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_production');
    
    echo "<h3>Fixing User ID Issue</h3>";
    
    // Check if user with ID 000000 already exists
    $checkUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $userId = '000000';
    $checkUser->bind_param("s", $userId);
    $checkUser->execute();
    $result = $checkUser->get_result();
    
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ User with ID 000000 already exists!</p>";
    } else {
        echo "<p>Creating user with ID 000000...</p>";
        
        // Insert the missing user
        $insertUser = $conn->prepare("INSERT INTO users (id, username, password, full_name, email, role, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
        
        $id = '000000';
        $username = 'aeron.daliva';
        $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // Default password hash
        $fullName = 'Aeron Paul Daliva';
        $email = 'aeron.daliva@example.com';
        $role = 'admin';
        
        $insertUser->bind_param("ssssss", $id, $username, $password, $fullName, $email, $role);
        
        if ($insertUser->execute()) {
            echo "<p style='color: green;'>✓ Successfully created user with ID 000000!</p>";
            echo "<p><strong>User Details:</strong></p>";
            echo "<ul>";
            echo "<li>ID: $id</li>";
            echo "<li>Username: $username</li>";
            echo "<li>Full Name: $fullName</li>";
            echo "<li>Email: $email</li>";
            echo "<li>Role: $role</li>";
            echo "</ul>";
        } else {
            echo "<p style='color: red;'>✗ Failed to create user: " . $insertUser->error . "</p>";
        }
    }
    
    echo "<h3>Updated Users Table:</h3>";
    $result = $conn->query("SELECT id, username, full_name, email, role FROM users ORDER BY id");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; padding: 5px;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Full Name</th><th>Email</th><th>Role</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $highlight = ($row['id'] == '000000') ? 'background-color: lightgreen;' : '';
            echo "<tr style='$highlight'>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['full_name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<div style='background: lightgreen; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>✅ Issue Fixed!</h3>";
    echo "<p>The foreign key constraint error should now be resolved.</p>";
    echo "<p><strong>You can now try submitting production reports again!</strong></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}
?>

<p><a href="index.php">← Back to Production Report Form</a> | <a href="debug_user.php">Check User Status Again</a></p>
