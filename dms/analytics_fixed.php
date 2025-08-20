<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'supervisor' && $_SESSION['role'] !== 'admin')) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Access Denied</title>
        <!-- Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light d-flex align-items-center justify-content-center vh-100'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-6 offset-md-3'>
                    <div class='alert alert-danger text-center'>
                        <h4 class='alert-heading'>Access Denied</h4>
                        <p>You are not authorized to view this page. Only supervisors or admins can access it.</p>
                        <hr>
                        <button onclick='goBack()' class='btn btn-outline-danger'>Return to Previous Page</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
        <!-- JavaScript to go back to the previous page -->
        <script>
            function goBack() {
                window.history.back();
            }
        </script>
    </body>
    </html>";
    exit();
}

// --- Database Connection & Notification Functionality --- //
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Get database connection using the centralized system
try {
    $conn = DatabaseManager::getConnection('sentinel_monitoring');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);

// REMARKS - SQL query to fetch unique product names
$sqlProductNames = "SELECT DISTINCT product_name FROM submissions WHERE product_name IS NOT NULL ORDER BY product_name";
$resultProductNames = $conn->query($sqlProductNames);

$productNames = [];
if ($resultProductNames->num_rows > 0) {
    while ($row = $resultProductNames->fetch_assoc()) {
        $productNames[] = $row['product_name'];
    }
}

// SQL query to fetch product remarks along with date, mold code, and cycle time difference
$sqlRemarks = "SELECT product_name, date, mold_code, remarks, 
                      (cycle_time_target - cycle_time_actual) AS cycle_time_difference 
               FROM submissions 
               WHERE remarks IS NOT NULL 
                 AND (cycle_time_target - cycle_time_actual) < 0
               ORDER BY product_name, date";

$resultRemarks = $conn->query($sqlRemarks);

$remarksData = [];
if ($resultRemarks->num_rows > 0) {
    while ($row = $resultRemarks->fetch_assoc()) {
        $product_name = $row['product_name'];
        if (!isset($remarksData[$product_name])) {
            $remarksData[$product_name] = [];
        }
        $remarksData[$product_name][] = [
            'date' => $row['date'],
            'mold_code' => $row['mold_code'],
            'remark' => $row['remarks'],
            'cycle_time_difference' => $row['cycle_time_difference']
        ];
    }
}

// PRODUCT VARIANCE
$sqlProductVariance = "SELECT product_name, 
                              date,
                              machine,
                              cycle_time_target, 
                              cycle_time_actual 
                       FROM submissions 
                       WHERE cycle_time_target IS NOT NULL 
                         AND cycle_time_actual IS NOT NULL";

$resultProductVariance = $conn->query($sqlProductVariance);
$productVarianceData = [];

if ($resultProductVariance->num_rows > 0) {
    while ($row = $resultProductVariance->fetch_assoc()) {
        $target = $row['cycle_time_target'];
        $actual = $row['cycle_time_actual'];
        $productName = $row['product_name'];
        $date = $row['date'];
        $machine = $row['machine'];

        // Check if target is zero to avoid division by zero
        if ($target == 0) {
            $variancePercentage = 0;
        } else {
            $variancePercentage = (($actual - $target) / $target) * 100;
        }

        $productVarianceData[] = [
            'product_name' => $productName,
            'date' => $date,
            'machine' => $machine,
            'variance_percentage' => $variancePercentage
        ];
    }
}

// Fetching unique combinations of machines and mold codes with dates
$sqlMachineMoldCombination = "SELECT product_name, CONCAT(machine, ' - ', mold_code) AS machine_mold_combination, date 
                              FROM submissions 
                              WHERE machine IS NOT NULL 
                                AND mold_code IS NOT NULL 
                              ORDER BY date, machine, mold_code";

$resultMachineMoldCombination = $conn->query($sqlMachineMoldCombination);

$machineMoldData = [];
if ($resultMachineMoldCombination->num_rows > 0) {
    while ($row = $resultMachineMoldCombination->fetch_assoc()) {
        $machineMoldData[] = [
            'product_name' => $row['product_name'],
            'machine_mold_combination' => $row['machine_mold_combination'],
            'date' => $row['date']
        ];
    }
}

// Connection will be closed automatically by DatabaseManager
?>
<?php include '../includes/navbar.php'; ?>

<!-- Additional CSS and Scripts for Analytics -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function toggleFilters() {
    const sortBy = document.getElementById('sortBy');
    const dayFilters = document.getElementById('dayFilters');
    const weekFilters = document.getElementById('weekFilters');
    const monthFilters = document.getElementById('monthFilters');
    const yearFilters = document.getElementById('yearFilters');
    const allTimeFilters = document.getElementById('allTimeFilters');

    if (!sortBy) return; // Safety check
    
    const sortByValue = sortBy.value;

    if (dayFilters) dayFilters.style.display = sortByValue === 'day' ? 'block' : 'none';
    if (weekFilters) weekFilters.style.display = sortByValue === 'week' ? 'block' : 'none';
    if (monthFilters) monthFilters.style.display = sortByValue === 'month' ? 'block' : 'none';
    if (yearFilters) yearFilters.style.display = sortByValue === 'year' ? 'block' : 'none';
    if (allTimeFilters) allTimeFilters.style.display = sortByValue === 'all_time' ? 'block' : 'none';
}
window.onload = toggleFilters; // Call on load to set initial visibility
</script>

            <main>
                <div class="container-fluid p-4">
                    <h1 class="">Analytics</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">DMS - Analytics</li>
                    </ol>
                    
                    <!-- charts -->
                    <div class="container-fluid my-5">
                        <div class="row row-cols-1">
                            <!-- Cycle Time Variance Dashboard -->
                            <div class="col">
                                <div class="card shadow mb-3">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h2>Cycle Time Variance by Product</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="cycleTimeVarianceTable" class="table table-striped table-hover" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Product Name</th>
                                                        <th>Machine Number</th>
                                                        <th>Variance Percentage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($productVarianceData as $data): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($data['date']); ?></td>
                                                            <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($data['machine']); ?></td>
                                                            <td><?php echo number_format($data['variance_percentage'], 2); ?>%</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks Dashboard -->
                            <div class="col">
                                <div class="card shadow mb-3">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h2>Product Remarks</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="productDropdown" class="form-label">Select Product:</label>
                                            <select id="productDropdown" class="form-select" onchange="loadRemarksTable()">
                                                <option value="">Select a product</option>
                                                <?php foreach ($productNames as $product_name): ?>
                                                    <option value="<?php echo htmlspecialchars($product_name); ?>">
                                                        <?php echo htmlspecialchars($product_name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div id="remarksContainer" class="table-responsive">
                                            <table id="remarksTable" class="table table-striped table-hover" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Mold Number</th>
                                                        <th>Remark</th>
                                                        <th>Cycle Time Difference</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table rows will be dynamically inserted here -->
                                                </tbody>
                                            </table>
                                            <p class="text-muted" id="noDataMessage">Select a product name to view remarks.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mold Machine Combination Chart -->
                            <div class="col">
                                <div class="card shadow mb-3">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h2>Machine-Mold Combinations Over Time</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="productFilter" class="form-label">Filter by Product:</label>
                                            <select id="productFilter" class="form-select">
                                                <option value="all">All Products</option>
                                                <?php foreach ($productNames as $product_name): ?>
                                                    <option value="<?php echo htmlspecialchars($product_name); ?>">
                                                        <?php echo htmlspecialchars($product_name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <canvas id="machineMoldChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart.js and DataTables functionality -->
                    <script>
                        // PHP data to JavaScript
                        const machineMoldData = <?php echo json_encode($machineMoldData); ?>;
                        const remarksData = <?php echo json_encode($remarksData); ?>;

                        function formatDataForBarChart(data) {
                            const counts = {};
                            data.forEach(item => {
                                if (!counts[item.machine_mold_combination]) {
                                    counts[item.machine_mold_combination] = 0;
                                }
                                counts[item.machine_mold_combination]++;
                            });

                            return {
                                labels: Object.keys(counts),
                                data: Object.values(counts)
                            };
                        }

                        const ctx = document.getElementById('machineMoldChart').getContext('2d');
                        let machineMoldChart;

                        function calculateSmoothedTrendLine(data) {
                            const smoothedYValues = [];
                            for (let i = 0; i < data.length; i++) {
                                const prev = data[i - 1] || data[i];
                                const next = data[i + 1] || data[i];
                                smoothedYValues.push((prev + data[i] + next) / 3);
                            }
                            return smoothedYValues;
                        }

                        function createBarChart(filteredData) {
                            if (machineMoldChart) {
                                machineMoldChart.destroy();
                            }

                            const chartData = formatDataForBarChart(filteredData);
                            const smoothedTrendLineData = calculateSmoothedTrendLine(chartData.data);

                            machineMoldChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: chartData.labels,
                                    datasets: [
                                        {
                                            label: 'Occurrences of Machine-Mold Combinations',
                                            data: chartData.data,
                                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                            borderColor: 'rgba(54, 162, 235, 1)',
                                            borderWidth: 1
                                        },
                                        {
                                            label: 'Smoothed Trend Line',
                                            data: smoothedTrendLineData,
                                            type: 'line',
                                            borderColor: 'rgba(255, 99, 132, 1)',
                                            borderWidth: 2,
                                            fill: false,
                                            pointRadius: 0,
                                            tension: 0.4
                                        }
                                    ]
                                },
                                options: {
                                    scales: {
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Machine-Mold Combinations'
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Occurrences'
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: true
                                        }
                                    }
                                }
                            });
                        }

                        function filterDataByProduct(productName) {
                            const filteredData = productName === 'all'
                                ? machineMoldData
                                : machineMoldData.filter(item => item.product_name === productName);
                            createBarChart(filteredData);
                        }

                        function loadRemarksTable() {
                            const productDropdown = document.getElementById('productDropdown');
                            const selectedProduct = productDropdown.value;
                            const remarksTableBody = document.querySelector('#remarksTable tbody');
                            const noDataMessage = document.getElementById('noDataMessage');

                            remarksTableBody.innerHTML = '';

                            if (selectedProduct && remarksData[selectedProduct]) {
                                noDataMessage.style.display = 'none';
                                remarksData[selectedProduct].forEach(remark => {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                        <td>${remark.date}</td>
                                        <td>${remark.mold_code}</td>
                                        <td>${remark.remark}</td>
                                        <td>${remark.cycle_time_difference}</td>
                                    `;
                                    remarksTableBody.appendChild(row);
                                });
                            } else {
                                noDataMessage.style.display = 'block';
                            }
                        }

                        // Event listeners
                        document.getElementById('productFilter').addEventListener('change', function () {
                            filterDataByProduct(this.value);
                        });

                        // Initialize
                        filterDataByProduct('all');
                        
                        // Initialize DataTables when DOM is ready
                        $(document).ready(function() {
                            $('#cycleTimeVarianceTable').DataTable({
                                responsive: true,
                                paging: true,
                                searching: true,
                                ordering: true,
                                pageLength: 25
                            });

                            $('#remarksTable').DataTable({
                                responsive: true,
                                paging: true,
                                searching: true,
                                ordering: true,
                                pageLength: 25
                            });
                        });
                    </script>
                </div>
            </main>

<?php include '../includes/navbar_close.php'; ?>
