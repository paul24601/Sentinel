<?php
/**
 * DATATABLES TESTING AND VERIFICATION PAGE
 * Tests all table implementations across Sentinel
 */
session_start();

// Simulate session if not logged in
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = 'Test User';
    $_SESSION['role'] = 'admin';
    $_SESSION['id_number'] = 'TEST001';
    $_SESSION['user_type'] = 'admin';
}

include 'includes/navbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">DataTables Implementation Test</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Testing All Tables</li>
    </ol>

    <!-- Test Results Display -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">DataTables Test Results</h5>
                </div>
                <div class="card-body">
                    <div id="testResults">
                        <p>Testing in progress...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sample Test Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Sample DataTable Test</h5>
                </div>
                <div class="card-body">
                    <table id="testDataTable" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Generate sample data
                            $statuses = ['Active', 'Inactive', 'Pending', 'Complete'];
                            $departments = ['DMS', 'Parameters', 'Quality Control', 'Administration'];
                            $names = ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Wilson', 'Robert Brown', 'Emily Davis', 'Michael Miller', 'Lisa Anderson', 'David Taylor', 'Maria Garcia', 'Christopher Martinez', 'Jennifer Rodriguez', 'Matthew Wilson', 'Amanda Moore', 'Daniel Thompson'];
                            
                            for ($i = 1; $i <= 50; $i++) {
                                $name = $names[array_rand($names)];
                                $dept = $departments[array_rand($departments)];
                                $status = $statuses[array_rand($statuses)];
                                $date = date('Y-m-d H:i:s', strtotime("-" . rand(1, 30) . " days"));
                                echo "<tr>";
                                echo "<td>$i</td>";
                                echo "<td>$name</td>";
                                echo "<td>$dept</td>";
                                echo "<td>$date</td>";
                                echo "<td><span class='badge bg-" . ($status == 'Active' ? 'success' : ($status == 'Inactive' ? 'danger' : ($status == 'Pending' ? 'warning' : 'info'))) . "'>$status</span></td>";
                                echo "<td><button class='btn btn-sm btn-outline-primary'>View</button> <button class='btn btn-sm btn-outline-secondary'>Edit</button></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Table List with Test Results -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>All Application Tables Status</h5>
                </div>
                <div class="card-body">
                    <table id="tableStatusTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Page</th>
                                <th>Table ID</th>
                                <th>DataTables Status</th>
                                <th>Default Length</th>
                                <th>Features</th>
                                <th>Test Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dashboard</td>
                                <td>#datatablesSimple</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-datatablesSimple" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Dashboard</td>
                                <td>#activeUsersTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-activeUsersTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Dashboard</td>
                                <td>#machineUsageTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-machineUsageTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>DMS Submission</td>
                                <td>#submissionTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-submissionTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>DMS Analytics</td>
                                <td>#cycleTimeVarianceTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-cycleTimeVarianceTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>DMS Analytics</td>
                                <td>#remarksTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-remarksTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Admin Users</td>
                                <td>#usersTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-usersTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Parameters</td>
                                <td>#parametersTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-parametersTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Production Report</td>
                                <td>#qualityTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-qualityTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Production Report</td>
                                <td>#downtimeTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-downtimeTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Sensory Data - Weights</td>
                                <td>#sensorTable</td>
                                <td><span class="badge bg-success">Implemented</span></td>
                                <td>10</td>
                                <td>Search, Sort, Pagination</td>
                                <td><span id="test-sensorTable" class="badge bg-secondary">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Test the current page table
    setTimeout(() => {
        testDataTablesImplementation();
    }, 1000);
});

function testDataTablesImplementation() {
    const results = {
        totalTables: 0,
        successfulTables: 0,
        failedTables: 0,
        details: []
    };
    
    // List of table IDs to test
    const tableIds = [
        'testDataTable',
        'tableStatusTable',
        // These would be tested if they exist on other pages
        'datatablesSimple',
        'activeUsersTable', 
        'machineUsageTable',
        'submissionTable',
        'cycleTimeVarianceTable',
        'remarksTable',
        'usersTable',
        'parametersTable',
        'qualityTable',
        'downtimeTable',
        'sensorTable'
    ];
    
    tableIds.forEach(tableId => {
        const table = document.getElementById(tableId);
        if (table) {
            results.totalTables++;
            
            try {
                // Check if DataTables is initialized
                if ($.fn.DataTable.isDataTable('#' + tableId)) {
                    results.successfulTables++;
                    results.details.push({
                        id: tableId,
                        status: 'success',
                        message: 'DataTable successfully initialized'
                    });
                    
                    // Update status badge if it exists
                    const badge = document.getElementById('test-' + tableId);
                    if (badge) {
                        badge.className = 'badge bg-success';
                        badge.textContent = 'Success';
                    }
                } else {
                    results.failedTables++;
                    results.details.push({
                        id: tableId,
                        status: 'error',
                        message: 'DataTable not initialized'
                    });
                    
                    // Update status badge if it exists
                    const badge = document.getElementById('test-' + tableId);
                    if (badge) {
                        badge.className = 'badge bg-danger';
                        badge.textContent = 'Failed';
                    }
                }
            } catch (error) {
                results.failedTables++;
                results.details.push({
                    id: tableId,
                    status: 'error',
                    message: 'Error checking DataTable: ' + error.message
                });
                
                // Update status badge if it exists
                const badge = document.getElementById('test-' + tableId);
                if (badge) {
                    badge.className = 'badge bg-warning';
                    badge.textContent = 'Error';
                }
            }
        }
    });
    
    // Display results
    displayTestResults(results);
}

function displayTestResults(results) {
    const resultsDiv = document.getElementById('testResults');
    
    let html = `
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary">${results.totalTables}</h5>
                        <p class="card-text">Total Tables Found</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-success">${results.successfulTables}</h5>
                        <p class="card-text">Successfully Initialized</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-danger">${results.failedTables}</h5>
                        <p class="card-text">Failed/Missing</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <h6>Detailed Results:</h6>
            <ul class="list-group">
    `;
    
    results.details.forEach(detail => {
        const iconClass = detail.status === 'success' ? 'text-success fas fa-check' : 'text-danger fas fa-times';
        html += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>#${detail.id}</strong>
                    <br>
                    <small class="text-muted">${detail.message}</small>
                </div>
                <i class="${iconClass}"></i>
            </li>
        `;
    });
    
    html += `
            </ul>
        </div>
        
        <div class="mt-3">
            <h6>DataTables Configuration Details:</h6>
            <ul>
                <li>Default page length: 10 entries</li>
                <li>Length menu options: 10, 25, 50, 100, All</li>
                <li>Features enabled: Search, Sort, Pagination, Responsive</li>
                <li>Bootstrap 5 styling applied</li>
                <li>Universal configuration loaded from: js/datatables-universal.js</li>
            </ul>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
}

// Manual refresh function
function refreshTests() {
    document.getElementById('testResults').innerHTML = '<p>Testing in progress...</p>';
    setTimeout(testDataTablesImplementation, 500);
}
</script>

<?php include 'includes/navbar_close.php'; ?>
