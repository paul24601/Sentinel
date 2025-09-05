<?php
session_start();
$_SESSION['full_name'] = 'Test User';
$_SESSION['role'] = 'admin';
$_SESSION['id_number'] = '12345';

// Mock some data for testing
$admin_notifications = [];
$notification_count = 0;
?>

<?php include 'includes/navbar.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Bootstrap Component Test</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Test Instructions</h5>
                </div>
                <div class="card-body">
                    <p>Please test the following components:</p>
                    <ul>
                        <li><strong>Sidebar Toggle:</strong> Click the hamburger menu (☰) in the top navbar</li>
                        <li><strong>Notification Dropdown:</strong> Click the bell icon (🔔) in the top navbar</li>
                        <li><strong>User Dropdown:</strong> Click the user icon (👤) in the top navbar</li>
                        <li><strong>Sidebar Navigation:</strong> Try expanding/collapsing menu items in the sidebar</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Console Logs</h5>
                </div>
                <div class="card-body">
                    <p>Open your browser's developer console (F12) to see initialization logs.</p>
                    <button class="btn btn-primary" onclick="testBootstrap()">Test Bootstrap</button>
                    <button class="btn btn-secondary" onclick="testDropdowns()">Test Dropdowns</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Sample Table with DataTables</h5>
                </div>
                <div class="card-body">
                    <table id="testTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td>John Doe</td><td>john@example.com</td><td>Active</td></tr>
                            <tr><td>2</td><td>Jane Smith</td><td>jane@example.com</td><td>Active</td></tr>
                            <tr><td>3</td><td>Bob Johnson</td><td>bob@example.com</td><td>Inactive</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testBootstrap() {
    console.log('Testing Bootstrap...');
    console.log('Bootstrap version:', typeof bootstrap !== 'undefined' ? 'Available' : 'Not Available');
    console.log('jQuery version:', typeof $ !== 'undefined' ? $.fn.jquery : 'Not Available');
    
    // Test dropdown programmatically
    const notifDropdown = document.getElementById('notificationDropdown');
    if (notifDropdown) {
        console.log('Notification dropdown found:', notifDropdown);
        const dropdown = bootstrap.Dropdown.getOrCreateInstance(notifDropdown);
        console.log('Dropdown instance:', dropdown);
    }
}

function testDropdowns() {
    console.log('Testing all dropdowns...');
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    console.log('Found dropdowns:', dropdowns.length);
    
    dropdowns.forEach((dropdown, index) => {
        console.log(`Dropdown ${index + 1}:`, dropdown.id, dropdown);
        const instance = bootstrap.Dropdown.getOrCreateInstance(dropdown);
        console.log(`Instance ${index + 1}:`, instance);
    });
}

// Initialize test table
$(document).ready(function() {
    $('#testTable').DataTable({
        pageLength: 10,
        responsive: true
    });
    
    console.log('Test table initialized');
});
</script>

<?php include 'includes/navbar_close.php'; ?>
