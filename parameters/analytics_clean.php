<?php
require_once 'session_config.php';

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Get database connection
try {
    $conn = DatabaseManager::getConnection('sentinel_main');
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get admin notifications for current user
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);

// Fetch statistics
$stats = [];

// Total records
$result = $conn->query("SELECT COUNT(*) as total FROM parameter_records");
$stats['total_records'] = $result->fetch_assoc()['total'];

// Records by status
$result = $conn->query("SELECT status, COUNT(*) as count FROM parameter_records GROUP BY status");
$stats['status_distribution'] = [];
while ($row = $result->fetch_assoc()) {
    $stats['status_distribution'][$row['status']] = $row['count'];
}

// Most used machines
$result = $conn->query("
    SELECT MachineName, COUNT(*) as count 
    FROM productmachineinfo 
    GROUP BY MachineName 
    ORDER BY count DESC 
    LIMIT 5
");
$stats['top_machines'] = [];
while ($row = $result->fetch_assoc()) {
    $stats['top_machines'][$row['MachineName']] = $row['count'];
}

// Most common materials
$result = $conn->query("
    SELECT Material1_Type, COUNT(*) as count 
    FROM materialcomposition 
    WHERE Material1_Type IS NOT NULL 
    GROUP BY Material1_Type 
    ORDER BY count DESC 
    LIMIT 5
");
$stats['top_materials'] = [];
while ($row = $result->fetch_assoc()) {
    $stats['top_materials'][$row['Material1_Type']] = $row['count'];
}

// Average cycle times
$result = $conn->query("
    SELECT AVG(CycleTime) as avg_cycle_time 
    FROM timerparameters
");
$stats['avg_cycle_time'] = round($result->fetch_assoc()['avg_cycle_time'], 2);

// Records by day (last 30 days)
$result = $conn->query("
    SELECT DATE(submission_date) as day, COUNT(*) as count 
    FROM parameter_records 
    WHERE submission_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY day 
    ORDER BY day ASC
");
$stats['daily_records'] = [];
while ($row = $result->fetch_assoc()) {
    $stats['daily_records'][$row['day']] = $row['count'];
}

// Average temperatures by zone
$result = $conn->query("
    SELECT 
        AVG(Zone0) as avg_zone0,
        AVG(Zone1) as avg_zone1,
        AVG(Zone2) as avg_zone2,
        AVG(Zone3) as avg_zone3,
        AVG(Zone4) as avg_zone4,
        AVG(Zone5) as avg_zone5
    FROM barrelheatertemperatures
");
$stats['avg_temperatures'] = $result->fetch_assoc();

// Most active adjusters
$result = $conn->query("
    SELECT AdjusterName, COUNT(*) as count 
    FROM personnel 
    WHERE AdjusterName IS NOT NULL 
    GROUP BY AdjusterName 
    ORDER BY count DESC 
    LIMIT 5
");
$stats['top_adjusters'] = [];
while ($row = $result->fetch_assoc()) {
    $stats['top_adjusters'][$row['AdjusterName']] = $row['count'];
}

?>
<?php include '../includes/navbar.php'; ?>
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Parameter Analytics Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Parameters</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>

                    <style>
                        .card {
                            margin-bottom: 20px;
                            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        }

                        .stat-card {
                            text-align: center;
                            padding: 20px;
                        }

                        .stat-value {
                            font-size: 2em;
                            font-weight: bold;
                            color: #0d6efd;
                        }

                        .stat-label {
                            color: #6c757d;
                            font-size: 1.1em;
                        }

                        .chart-container {
                            position: relative;
                            height: 300px;
                            margin-bottom: 20px;
                        }
                    </style>

                    <!-- Overview Stats -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="stat-value"><?= $stats['total_records'] ?></div>
                                <div class="stat-label">Total Records</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="stat-value"><?= $stats['avg_cycle_time'] ?>s</div>
                                <div class="stat-label">Avg Cycle Time</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="stat-value"><?= count($stats['top_machines']) ?></div>
                                <div class="stat-label">Active Machines</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="stat-value"><?= count($stats['top_adjusters']) ?></div>
                                <div class="stat-label">Active Adjusters</div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row 1 -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Records by Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="statusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Daily Records (Last 30 Days)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="dailyChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row 2 -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Top Machines</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="machinesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Top Materials</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="materialsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Temperature Chart -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Average Barrel Temperatures by Zone</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="temperatureChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../js/scripts.js"></script>
    <script>
        // Status Distribution Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_keys($stats['status_distribution'])) ?>,
                datasets: [{
                    data: <?= json_encode(array_values($stats['status_distribution'])) ?>,
                    backgroundColor: ['#0d6efd', '#198754', '#dc3545', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Daily Records Chart
        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode(array_keys($stats['daily_records'])) ?>,
                datasets: [{
                    label: 'Records',
                    data: <?= json_encode(array_values($stats['daily_records'])) ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Top Machines Chart
        new Chart(document.getElementById('machinesChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($stats['top_machines'])) ?>,
                datasets: [{
                    label: 'Records',
                    data: <?= json_encode(array_values($stats['top_machines'])) ?>,
                    backgroundColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Top Materials Chart
        new Chart(document.getElementById('materialsChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($stats['top_materials'])) ?>,
                datasets: [{
                    label: 'Usage',
                    data: <?= json_encode(array_values($stats['top_materials'])) ?>,
                    backgroundColor: '#198754'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Temperature Chart
        new Chart(document.getElementById('temperatureChart'), {
            type: 'line',
            data: {
                labels: ['Zone 0', 'Zone 1', 'Zone 2', 'Zone 3', 'Zone 4', 'Zone 5'],
                datasets: [{
                    label: 'Average Temperature (°C)',
                    data: [
                        <?= $stats['avg_temperatures']['avg_zone0'] ?>,
                        <?= $stats['avg_temperatures']['avg_zone1'] ?>,
                        <?= $stats['avg_temperatures']['avg_zone2'] ?>,
                        <?= $stats['avg_temperatures']['avg_zone3'] ?>,
                        <?= $stats['avg_temperatures']['avg_zone4'] ?>,
                        <?= $stats['avg_temperatures']['avg_zone5'] ?>
                    ],
                    borderColor: '#dc3545',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>

<?php include '../includes/navbar_close.php'; ?>
<?php $conn->close(); ?>
