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
?>


<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "Admin123@plvil";
$dbname = "dailymonitoringsheet";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// REMARKS
// SQL query to fetch unique product names
$sqlProductNames = "SELECT DISTINCT product_name FROM submissions WHERE product_name IS NOT NULL ORDER BY product_name";
$resultProductNames = $conn->query($sqlProductNames);

$productNames = [];
if ($resultProductNames->num_rows > 0) {
    while ($row = $resultProductNames->fetch_assoc()) {
        $productNames[] = $row['product_name'];
    }
}

// SQL query to fetch product name remarks along with date, mold number, and cycle time difference
$sqlRemarks = "SELECT product_name, date, mold_code, remarks, 
                      (cycle_time_target - cycle_time_actual) AS cycle_time_difference 
               FROM submissions 
               WHERE remarks IS NOT NULL 
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
// Query to fetch product names, date, machine number, and cycle time data
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

        // Calculate variance percentage
        $variancePercentage = (($actual - $target) / $target) * 100;

        // Store in array
        $productVarianceData[] = [
            'product_name' => $productName,
            'date' => $date,
            'machine' => $machine,
            'variance_percentage' => $variancePercentage
        ];
    }
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>DMS - Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Chart.js Date Adapter -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script>
        function toggleFilters() {
            const sortBy = document.getElementById('sortBy').value;
            const dayFilters = document.getElementById('dayFilters');
            const weekFilters = document.getElementById('weekFilters');
            const monthFilters = document.getElementById('monthFilters');
            const yearFilters = document.getElementById('yearFilters');
            const allTimeFilters = document.getElementById('allTimeFilters');

            dayFilters.style.display = sortBy === 'day' ? 'block' : 'none';
            weekFilters.style.display = sortBy === 'week' ? 'block' : 'none';
            monthFilters.style.display = sortBy === 'month' ? 'block' : 'none';
            yearFilters.style.display = sortBy === 'year' ? 'block' : 'none';
            allTimeFilters.style.display = sortBy === 'all_time' ? 'block' : 'none'; // Handle all time case
        }
        window.onload = toggleFilters; // Call on load to set initial visibility
    </script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel Digitization</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="../index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Systems</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                            aria-expanded="false" aria-controls="collapseDMS">
                            <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                            DMS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseDMS" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="index.php">Data Entry</a>
                                <a class="nav-link" href="submission.php">Records</a>
                                <a class="nav-link active" href="#">Analytics</a>
                            </nav>
                        </div>


                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseParameters" aria-expanded="false"
                            aria-controls="collapseParameters">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Parameters
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseParameters" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="../parameters/index.php">Data Entry</a>
                                <a class="nav-link" href="../parameters/submission.php">Data Visualization</a>
                                <a class="nav-link" href="../parameters/analytics.php">Data Analytics</a>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Admin</div>
                        <a class="nav-link" href="../admin/users.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Values
                        </a>
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Analysis
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $_SESSION['full_name']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid p-4">
                    <h1 class="">Analytics</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
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
                                            <table id="cycleTimeVarianceTable" class="table table-striped display"
                                                style="width:100%">
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
                                                            <td><?php echo number_format($data['variance_percentage'], 2); ?>%
                                                            </td>
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
                                <!-- Container for dropdown and remarks display -->
                                <div class="card shadow mb-3">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h2>Product Remarks</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="productDropdown" class="form-label">Select Product:</label>
                                            <select id="productDropdown" class="form-select"
                                                onchange="loadRemarksTable()">
                                                <option value="">Select a product</option>
                                                <?php foreach ($productNames as $product_name): ?>
                                                    <option value="<?php echo htmlspecialchars($product_name); ?>">
                                                        <?php echo htmlspecialchars($product_name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div id="remarksContainer" class="table-responsive">
                                            <table id="remarksTable" class="display nowrap" style="width:100%">
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
                                            <p class="text-muted" id="noDataMessage">Select a product name to view
                                                remarks.</p>
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

                    <!-- remarks section -->
                    <script>
                        // PHP array to JavaScript
                        const remarksData = <?php echo json_encode($remarksData); ?>;

                        // Initialize DataTable
                        const table = $('#remarksTable').DataTable();

                        // Function to load remarks based on selected machine
                        function loadRemarksTable() {
                            const selectProduct = $('#productDropdown').val();

                            // Clear previous table data
                            table.clear().draw();

                            if (selectProduct && remarksData[selectProduct]) {
                                // Hide no data message
                                $('#noDataMessage').hide();

                                // Populate table with remarks data
                                remarksData[selectProduct].forEach(remark => {
                                    table.row.add([
                                        remark.date,
                                        remark.mold_code,
                                        remark.remark,
                                        remark.cycle_time_difference
                                    ]).draw();
                                });
                            } else {
                                $('#noDataMessage').show();
                            }
                        }
                    </script>

                    <script>
                        $(document).ready(function () {
                            // Initialize DataTable
                            const table = $('#cycleTimeVarianceTable').DataTable({
                                responsive: true,
                                paging: true,
                                searching: true,
                                ordering: true,
                                order: [[3, 'desc']], // Order by Variance Percentage by default
                                columnDefs: [
                                    { targets: 3, orderable: true } // Enable sorting on Variance Percentage
                                ]
                            });

                            // Apply conditional formatting
                            table.rows().every(function () {
                                const row = this.node();
                                const variancePercentageCell = $(row).find('td:eq(3)');
                                const variancePercentage = parseFloat(variancePercentageCell.text());

                                if (variancePercentage >= 1.00 && variancePercentage <= 10.99) {
                                    variancePercentageCell.css('background-color', 'yellow');
                                } else if (variancePercentage >= 11.00 && variancePercentage <= 25.99) {
                                    variancePercentageCell.css('background-color', 'orange');
                                } else if (variancePercentage > 26.00) {
                                    variancePercentageCell.css('background-color', 'red');
                                }
                            });
                        });
                    </script>


                    <!-- Chart.js Script -->
                    <script>
                        const machineMoldData = <?php echo json_encode($machineMoldData); ?>;

                        function formatDataForBarChart(data) {
                            // Count occurrences of machine-mold combinations for each product
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
                            // Generate x-values as the indices of the data points
                            const xValues = Array.from({ length: data.length }, (_, i) => i + 1);
                            const yValues = data;

                            // Interpolate y-values for smoothing using cubic spline or similar
                            // For simplicity, we'll use a lightweight smoothing function
                            const smoothedYValues = [];
                            for (let i = 0; i < yValues.length; i++) {
                                const prev = yValues[i - 1] || yValues[i];
                                const next = yValues[i + 1] || yValues[i];
                                smoothedYValues.push((prev + yValues[i] + next) / 3); // Simple average smoothing
                            }

                            return smoothedYValues;
                        }

                        function createBarChart(filteredData) {
                            if (machineMoldChart) {
                                machineMoldChart.destroy(); // Destroy previous chart instance if it exists
                            }

                            const chartData = formatDataForBarChart(filteredData);

                            // Calculate smoothed trend line data
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
                                            pointRadius: 0, // Remove points for a smoother line
                                            tension: 0.4 // Adds curve to the line for smoothness
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
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function (tooltipItem) {
                                                    return tooltipItem.datasetIndex === 0
                                                        ? `Occurrences: ${tooltipItem.raw}`
                                                        : `Trend Line Value: ${tooltipItem.raw.toFixed(2)}`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }

                        function filterDataByProduct(productName) {
                            const filteredData = productName === 'all'
                                ? machineMoldData
                                : machineMoldData.filter(item => item.machine_mold_combination.includes(productName));
                            createBarChart(filteredData);
                        }

                        document.getElementById('productFilter').addEventListener('change', function () {
                            const productName = this.value;
                            filterDataByProduct(productName);
                        });

                        // Initialize chart with all data
                        filterDataByProduct('all');

                    </script>


                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2025 Sentinel OJT</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../assets/demo/chart-area-demo.js"></script>
    <script src="../assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>