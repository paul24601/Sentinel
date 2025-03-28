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

// Get the selected sorting option
$sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : 'day'; // Default to sorting by day

// SQL query for sorting based on user selection
switch ($sort_by) {
    case 'week':
        $month = isset($_POST['week_month']) ? $_POST['week_month'] : date('F');

        // Simplified query to handle week sorting
        $sql = "SELECT CONCAT(YEAR(date), ' Week ', WEEK(date, 1)) AS period, 
                           SUM(IF(cycle_time_actual >= cycle_time_target, 1, 0)) AS fail, 
                           SUM(IF(cycle_time_actual < cycle_time_target, 1, 0)) AS pass 
                    FROM submissions 
                    WHERE MONTHNAME(date) = '$month'
                    GROUP BY YEAR(date), WEEK(date, 1)
                    ORDER BY YEAR(date), WEEK(date, 1)";
        break;

    case 'month':
        $year = isset($_POST['month_year']) ? $_POST['month_year'] : date('Y');
        $sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS period, 
                           SUM(IF(cycle_time_actual > cycle_time_target, 1, 0)) AS fail, 
                           SUM(IF(cycle_time_actual <= cycle_time_target, 1, 0)) AS pass 
                    FROM submissions 
                    WHERE YEAR(date) = $year 
                    GROUP BY DATE_FORMAT(date, '%Y-%m') 
                    ORDER BY MIN(date)";
        break;

    case 'year':
        $decade = isset($_POST['decade']) ? $_POST['decade'] : 2020; // Default to the latest decade
        $startYear = $decade;
        $endYear = $decade + 9;

        $sql = "SELECT YEAR(date) AS period, 
                       SUM(IF(cycle_time_actual > cycle_time_target, 1, 0)) AS fail, 
                       SUM(IF(cycle_time_actual <= cycle_time_target, 1, 0)) AS pass 
                FROM submissions 
                WHERE YEAR(date) BETWEEN $startYear AND $endYear 
                GROUP BY YEAR(date) 
                ORDER BY YEAR(date)";
        break;

    case 'all_time':
        $sortOption = isset($_POST['allTimeSortBy']) ? $_POST['allTimeSortBy'] : 'default';
        $groupBy = "DATE_FORMAT(date, '%Y-%m')";

        switch ($sortOption) {
            case 'most_passing':
                $sql = "SELECT $groupBy AS period, 
                                   SUM(IF(cycle_time_actual > cycle_time_target, 1, 0)) AS fail, 
                                   SUM(IF(cycle_time_actual <= cycle_time_target, 1, 0)) AS pass 
                            FROM submissions 
                            GROUP BY $groupBy 
                            ORDER BY pass DESC"; // Sort by pass count descending
                break;

            case 'most_failing':
                $sql = "SELECT $groupBy AS period, 
                                   SUM(IF(cycle_time_actual > cycle_time_target, 1, 0)) AS fail, 
                                   SUM(IF(cycle_time_actual <= cycle_time_target, 1, 0)) AS pass 
                            FROM submissions 
                            GROUP BY $groupBy 
                            ORDER BY fail DESC"; // Sort by fail count descending
                break;

            default: // Default sorting
                $sql = "SELECT $groupBy AS period, 
                                   SUM(IF(cycle_time_actual > cycle_time_target, 1, 0)) AS fail, 
                                   SUM(IF(cycle_time_actual <= cycle_time_target, 1, 0)) AS pass 
                            FROM submissions 
                            GROUP BY $groupBy 
                            ORDER BY MIN(date)"; // Default order
                break;
        }
        break;

    default:
        $range = isset($_POST['day_range']) ? $_POST['day_range'] : 'this_week';
        $dateCondition = '';
        if ($range == 'this_week') {
            $dateCondition = "WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
        } elseif ($range == 'last_1_week') {
            $dateCondition = "WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        } elseif ($range == 'last_2_weeks') {
            $dateCondition = "WHERE date >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)";
        }
        $sql = "SELECT DATE(date) AS period, 
                           SUM(IF(cycle_time_actual > cycle_time_target, 1, 0)) AS fail, 
                           SUM(IF(cycle_time_actual <= cycle_time_target, 1, 0)) AS pass 
                    FROM submissions 
                    $dateCondition 
                    GROUP BY DATE(date) 
                    ORDER BY period";
        break;
}

$data = ['labels' => [], 'pass' => [], 'fail' => []];

$result = $conn->query($sql);
// Variable to track if data exists
$dataExists = false;

$totalPass = 0;  // Initialize total pass count
$totalFail = 0;  // Initialize total fail count

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['period'];
        $data['pass'][] = (int) $row['pass'];
        $data['fail'][] = (int) $row['fail'];
        $dataExists = true;  // Set to true when data is found

        // Accumulate pass and fail counts
        $totalPass += (int) $row['pass'];
        $totalFail += (int) $row['fail'];
    }
}

// Calculate pass and fail percentages
$totalCount = $totalPass + $totalFail;
$passPercentage = $totalCount > 0 ? ($totalPass / $totalCount) * 100 : 0;
$failPercentage = $totalCount > 0 ? ($totalFail / $totalCount) * 100 : 0;

// SQL query to fetch machine names and the difference between target and actual cycle time
$sqlDifference = "SELECT machine, 
                         (cycle_time_actual - cycle_time_target) AS cycle_time_difference
                  FROM submissions 
                  WHERE cycle_time_target IS NOT NULL 
                  AND cycle_time_actual IS NOT NULL";

$resultDifference = $conn->query($sqlDifference);
$dataDifference = ['labels' => [], 'differences' => []];

if ($resultDifference->num_rows > 0) { 
    while ($row = $resultDifference->fetch_assoc()) {
        $dataDifference['labels'][] = $row['machine'];  // Machine name on X axis
        $dataDifference['differences'][] = (float) $row['cycle_time_difference'];  // Difference on Y axis
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metrics Chart</title>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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

<body class="bg-primary-subtle">
    <div class="container my-5">
        <div class="row row-cols-1">
            <div class="col">
                <!-- container for pass/fail -->
                <div class="card shadow mb-3">
                    <div class="card-header bg-primary text-white text-center">
                        <h2>Daily Pass/Fail Metrics Bar Chart</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Sorting Options Form -->
                            <form method="POST" class="col-12 col-md-8 mb-4" id="sortForm">
                                <div class="row mb-3 align-items-end">
                                    <div class="col-md-6">
                                        <label for="sortBy" class="form-label">Sort By:</label>
                                        <select name="sort_by" id="sortBy" class="form-select"
                                            onchange="document.getElementById('sortForm').submit();">
                                            <option value="day" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'day' ? 'selected' : ''; ?>>Day</option>
                                            <option value="week" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'week' ? 'selected' : ''; ?>>Week</option>
                                            <option value="month" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'month' ? 'selected' : ''; ?>>Month</option>
                                            <option value="year" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'year' ? 'selected' : ''; ?>>Year</option>
                                            <option value="all_time" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'all_time' ? 'selected' : ''; ?>>All Time</option>
                                        </select>
                                    </div>

                                    <!-- Day Filters -->
                                    <div id="dayFilters" class="col-md-6 mt-3" style="display:none;">
                                        <label for="dayRange" class="form-label">Select Range:</label>
                                        <select name="day_range" id="dayRange" class="form-select"
                                            onchange="document.getElementById('sortForm').submit();">
                                            <option value="this_week" <?php echo isset($_POST['day_range']) && $_POST['day_range'] == 'this_week' ? 'selected' : ''; ?>>This Week
                                            </option>
                                            <option value="last_1_week" <?php echo isset($_POST['day_range']) && $_POST['day_range'] == 'last_1_week' ? 'selected' : ''; ?>>Last 1 Week
                                            </option>
                                            <option value="last_2_weeks" <?php echo isset($_POST['day_range']) && $_POST['day_range'] == 'last_2_weeks' ? 'selected' : ''; ?>>Last 2 Weeks
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Week Filters -->
                                    <div id="weekFilters" class="col-md-6 mt-3" style="display:none;">
                                        <label for="weekMonth" class="form-label">Select Month:</label>
                                        <select name="week_month" id="weekMonth" class="form-select"
                                            onchange="document.getElementById('sortForm').submit();">
                                            <?php
                                            // Get the current month
                                            $currentMonth = date('F');

                                            // Array of all months
                                            $months = [
                                                'January',
                                                'February',
                                                'March',
                                                'April',
                                                'May',
                                                'June',
                                                'July',
                                                'August',
                                                'September',
                                                'October',
                                                'November',
                                                'December'
                                            ];

                                            // Loop through each month and set the current month as selected by default
                                            foreach ($months as $month) {
                                                echo '<option value="' . $month . '" ' .
                                                    (isset($_POST['week_month']) ? ($_POST['week_month'] == $month ? 'selected' : '') : ($month == $currentMonth ? 'selected' : '')) .
                                                    '>' . $month . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Month Filters -->
                                    <div id="monthFilters" class="col-md-6 mt-3" style="display:none;">
                                        <label for="monthYear" class="form-label">Select Year:</label>
                                        <select name="month_year" id="monthYear" class="form-select"
                                            onchange="document.getElementById('sortForm').submit();">
                                            <?php
                                            // Dynamically generate year options
                                            $currentYear = date("Y");
                                            for ($year = $currentYear; $year >= 2000; $year--) {
                                                echo '<option value="' . $year . '" ' . (isset($_POST['month_year']) && $_POST['month_year'] == $year ? 'selected' : '') . '>' . $year . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Year Filters -->
                                    <div id="yearFilters" class="col-md-6 mt-3" style="display:none;">
                                        <label for="decade" class="form-label">Select Decade:</label>
                                        <select name="decade" id="decade" class="form-select"
                                            onchange="document.getElementById('sortForm').submit();">
                                            <?php
                                            // Assuming you want to cover decades from 2000 to 2020
                                            for ($year = 2020; $year >= 2000; $year -= 10) {
                                                echo '<option value="' . $year . '" ' . (isset($_POST['decade']) && $_POST['decade'] == $year ? 'selected' : '') . '>' . $year . 's</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- All Time Filter -->
                                    <div id="allTimeFilters" class="col-md-6 mt-3" style="display:none;">
                                        <label for="allTimeSortBy" class="form-label">Sort By:</label>
                                        <select name="allTimeSortBy" id="allTimeSortBy" class="form-select"
                                            onchange="document.getElementById('sortForm').submit();">
                                            <option value="default" <?php echo isset($_POST['allTimeSortBy']) && $_POST['allTimeSortBy'] == 'default' ? 'selected' : ''; ?>>Default
                                            </option>
                                            <option value="most_passing" <?php echo isset($_POST['allTimeSortBy']) && $_POST['allTimeSortBy'] == 'most_passing' ? 'selected' : ''; ?>>Most
                                                Passing Month
                                            </option>
                                            <option value="most_failing" <?php echo isset($_POST['allTimeSortBy']) && $_POST['allTimeSortBy'] == 'most_failing' ? 'selected' : ''; ?>>Most
                                                Failing Month
                                            </option>
                                        </select>
                                    </div>

                                </div>
                            </form>

                            <!-- Pass and Fail Rate Cards -->
                            <div class="col-12 col-md-4">
                                <div class="row g-1 mb-3">
                                    <div class="col-12 col-sm-6">
                                        <div class="card text-secondary-subtle"
                                            style="border: none; background-color: rgba(75, 192, 192, 0.6);">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">Pass Rate</h5>
                                                <p class="card-text">
                                                    <?php
                                                    $total = array_sum($data['pass']) + array_sum($data['fail']);
                                                    $passRate = $total > 0 ? (array_sum($data['pass']) / $total) * 100 : 0;
                                                    echo number_format($passRate, 2) . '%';
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="card text-secondary-subtle"
                                            style="border: none; background-color: rgba(255, 99, 132, 0.6);">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">Fail Rate</h5>
                                                <p class="card-text">
                                                    <?php
                                                    $failRate = $total > 0 ? (array_sum($data['fail']) / $total) * 100 : 0;
                                                    echo number_format($failRate, 2) . '%';
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!-- Display No Data Available Message -->
                            <h3 class="<?php echo $dataExists ? 'd-none' : ''; ?> text-center m-3" style='color:red;'>No
                                data
                                available
                            </h3>

                            <canvas id="metricsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script>
        //pass/fail chart
        const ctx = document.getElementById('metricsChart').getContext('2d');
        const metricsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($data['labels']); ?>,
                datasets: [
                    {
                        label: 'Pass',
                        data: <?php echo json_encode($data['pass']); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    },
                    {
                        label: 'Fail',
                        data: <?php echo json_encode($data['fail']); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>