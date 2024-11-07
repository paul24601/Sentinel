<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'supervisor' && $_SESSION['role'] !== 'admin')) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Access Denied</title>
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
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
        <script>
            function goBack() {
                window.history.back();
            }
        </script>
    </body>
    </html>";
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "production_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching unique combinations of machines and mold codes with dates
$sqlMachineMoldCombination = "SELECT CONCAT(machine, ' - ', mold_code) AS machine_mold_combination, date 
                              FROM submissions 
                              WHERE machine IS NOT NULL AND mold_code IS NOT NULL 
                              ORDER BY date, machine, mold_code";

$resultMachineMoldCombination = $conn->query($sqlMachineMoldCombination);
$machineMoldData = [];

if ($resultMachineMoldCombination->num_rows > 0) {
    while ($row = $resultMachineMoldCombination->fetch_assoc()) {
        $machineMoldData[] = [
            'machine_mold_combination' => $row['machine_mold_combination'],
            'date' => $row['date']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine-Mold Chart</title>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js Date Adapter -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

</head>

<body class="bg-primary-subtle">
    <div class="container my-5">
        <div class="row">
            <div class="col">
                <div class="card shadow mb-3">
                    <div class="card-header bg-primary text-white text-center">
                        <h2>Machine-Mold Combinations Over Time</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="timeFilter" class="form-label">Filter by Time:</label>
                            <select id="timeFilter" class="form-select">
                                <option value="all">All Time</option>
                                <option value="day">Last Day</option>
                                <option value="week">Last Week</option>
                                <option value="month">Last Month</option>
                                <option value="year">Last Year</option>
                            </select>
                        </div>
                        <canvas id="machineMoldChart"></canvas>
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Chart.js Script -->
    <script>
        const machineMoldData = <?php echo json_encode($machineMoldData); ?>;

        function formatDataForChart(data) {
            return data.map(item => ({
                x: new Date(item.date),
                y: item.machine_mold_combination
            }));
        }

        const ctx = document.getElementById('machineMoldChart').getContext('2d');
        let machineMoldChart;

        function createChart(filteredData) {
            if (machineMoldChart) {
                machineMoldChart.destroy(); // Destroy previous chart instance if it exists
            }

            machineMoldChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Machine-Mold Combinations',
                        data: formatDataForChart(filteredData),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false,
                        tension: 0.1 // Slight curve to the lines for a smoother appearance
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'yyyy-MM-dd'
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            type: 'category',
                            labels: filteredData.map(item => item.machine_mold_combination),
                            title: {
                                display: true,
                                text: 'Machine-Mold Combinations'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const date = tooltipItem.raw.x.toISOString().split('T')[0];
                                    return `Date: ${date}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function filterData(timeframe) {
            const now = new Date();
            const filteredData = machineMoldData.filter(item => {
                const itemDate = new Date(item.date);
                switch (timeframe) {
                    case 'day':
                        return itemDate >= new Date(now.setDate(now.getDate() - 1));
                    case 'week':
                        return itemDate >= new Date(now.setDate(now.getDate() - 7));
                    case 'month':
                        return itemDate >= new Date(now.setMonth(now.getMonth() - 1));
                    case 'year':
                        return itemDate >= new Date(now.setFullYear(now.getFullYear() - 1));
                    default:
                        return true; // No filtering
                }
            });
            createChart(filteredData);
        }

        document.getElementById('timeFilter').addEventListener('change', function() {
            const timeframe = this.value;
            filterData(timeframe);
        });

        // Initialize chart with all data
        filterData('all');
    </script>

</body>

</html>
