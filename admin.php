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
    <script>
        function toggleFilters() {
            const sortBy = document.getElementById('sortBy').value;
            const dayFilters = document.getElementById('dayFilters');
            const weekFilters = document.getElementById('weekFilters');
            const monthFilters = document.getElementById('monthFilters');
            const yearFilters = document.getElementById('yearFilters');

            dayFilters.style.display = sortBy === 'day' ? 'block' : 'none';
            weekFilters.style.display = sortBy === 'week' ? 'block' : 'none';
            monthFilters.style.display = sortBy === 'month' ? 'block' : 'none';
            yearFilters.style.display = sortBy === 'year' ? 'block' : 'none';
        }
        
        window.onload = toggleFilters; // Call on load to set initial visibility
    </script>
</head>
<body>
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h2>Daily Pass/Fail Metrics Stacked Bar Chart</h2>
            </div>
            <div class="card-body">
                <!-- Sorting Options Form -->
                <form method="POST" class="mb-4" id="sortForm">
                    <label for="sortBy" class="form-label">Sort By:</label>
                    <select name="sort_by" id="sortBy" class="form-select" onchange="document.getElementById('sortForm').submit();">
                        <option value="day" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'day' ? 'selected' : ''; ?>>Day</option>
                        <option value="week" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'week' ? 'selected' : ''; ?>>Week</option>
                        <option value="month" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'month' ? 'selected' : ''; ?>>Month</option>
                        <option value="year" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'year' ? 'selected' : ''; ?>>Year</option>
                        <option value="all_time" <?php echo isset($_POST['sort_by']) && $_POST['sort_by'] == 'all_time' ? 'selected' : ''; ?>>All Time</option>
                    </select>

                    <!-- Day Filters -->
                    <div id="dayFilters" class="mt-3" style="display:none;">
                        <label for="dayRange" class="form-label">Select Range:</label>
                        <select name="day_range" id="dayRange" class="form-select" onchange="document.getElementById('sortForm').submit();">
                            <option value="this_week" <?php echo isset($_POST['day_range']) && $_POST['day_range'] == 'this_week' ? 'selected' : ''; ?>>This Week</option>
                            <option value="last_1_week" <?php echo isset($_POST['day_range']) && $_POST['day_range'] == 'last_1_week' ? 'selected' : ''; ?>>Last 1 Week</option>
                            <option value="last_2_weeks" <?php echo isset($_POST['day_range']) && $_POST['day_range'] == 'last_2_weeks' ? 'selected' : ''; ?>>Last 2 Weeks</option>
                        </select>
                    </div>

                    <!-- Week Filters -->
                    <div id="weekFilters" class="mt-3" style="display:none;">
                        <label for="weekMonth" class="form-label">Select Month:</label>
                        <select name="week_month" id="weekMonth" class="form-select" onchange="document.getElementById('sortForm').submit();">
                            <option value="January" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'January' ? 'selected' : ''; ?>>January</option>
                            <option value="February" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'February' ? 'selected' : ''; ?>>February</option>
                            <option value="March" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'March' ? 'selected' : ''; ?>>March</option>
                            <option value="April" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'April' ? 'selected' : ''; ?>>April</option>
                            <option value="May" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'May' ? 'selected' : ''; ?>>May</option>
                            <option value="June" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'June' ? 'selected' : ''; ?>>June</option>
                            <option value="July" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'July' ? 'selected' : ''; ?>>July</option>
                            <option value="August" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'August' ? 'selected' : ''; ?>>August</option>
                            <option value="September" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'September' ? 'selected' : ''; ?>>September</option>
                            <option value="October" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'October' ? 'selected' : ''; ?>>October</option>
                            <option value="November" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'November' ? 'selected' : ''; ?>>November</option>
                            <option value="December" <?php echo isset($_POST['week_month']) && $_POST['week_month'] == 'December' ? 'selected' : ''; ?>>December</option>
                        </select>
                    </div>

                    <!-- Month Filters -->
                    <div id="monthFilters" class="mt-3" style="display:none;">
                        <label for="monthYear" class="form-label">Select Year:</label>
                        <select name="month_year" id="monthYear" class="form-select" onchange="document.getElementById('sortForm').submit();">
                            <?php
                            // Dynamically generate year options
                            $currentYear = date("Y");
                            for ($year = $currentYear; $year >= 2000; $year--) {
                                echo '<option value="' . $year . '" ' . (isset($_POST['month_year']) && $_POST['month_year'] == $year ? 'selected' : '') . '>' . $year . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </form>

                <canvas id="metricsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "production_data";

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
    $sql = "SELECT YEAR(date) AS year, WEEK(date, 1) AS week_num, 
                   SUM(IF(cycle_time_actual >= cycle_time_target, 1, 0)) AS pass, 
                   SUM(IF(cycle_time_actual < cycle_time_target, 1, 0)) AS fail 
            FROM submissions 
            WHERE MONTH(date) = MONTH(STR_TO_DATE('$month', '%M')) 
            GROUP BY year, week_num 
            ORDER BY year, week_num";
    break;
    
        case 'month':
            $year = isset($_POST['month_year']) ? $_POST['month_year'] : date('Y');
            $sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS period, 
                           SUM(IF(cycle_time_actual >= cycle_time_target, 1, 0)) AS pass, 
                           SUM(IF(cycle_time_actual < cycle_time_target, 1, 0)) AS fail 
                    FROM submissions 
                    WHERE YEAR(date) = $year 
                    GROUP BY DATE_FORMAT(date, '%Y-%m') 
                    ORDER BY MIN(date)";
            break;
        case 'year':
            $sql = "SELECT YEAR(date) AS period, 
                           SUM(IF(cycle_time_actual >= cycle_time_target, 1, 0)) AS pass, 
                           SUM(IF(cycle_time_actual < cycle_time_target, 1, 0)) AS fail 
                    FROM submissions 
                    GROUP BY YEAR(date) 
                    ORDER BY period";
            break;
        case 'all_time':
            $sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS period, 
                           SUM(IF(cycle_time_actual >= cycle_time_target, 1, 0)) AS pass, 
                           SUM(IF(cycle_time_actual < cycle_time_target, 1, 0)) AS fail 
                    FROM submissions 
                    GROUP BY DATE_FORMAT(date, '%Y-%m') 
                    ORDER BY MIN(date)";
            break;
        case 'day':
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
                           SUM(IF(cycle_time_actual >= cycle_time_target, 1, 0)) AS pass, 
                           SUM(IF(cycle_time_actual < cycle_time_target, 1, 0)) AS fail 
                    FROM submissions 
                    $dateCondition 
                    GROUP BY DATE(date) 
                    ORDER BY period";
            break;
    }

    $result = $conn->query($sql);
    $data = ['labels' => [], 'pass' => [], 'fail' => []];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data['labels'][] = $row['period'];
            $data['pass'][] = (int)$row['pass'];
            $data['fail'][] = (int)$row['fail'];
        }
    }
    $conn->close();
    ?>

    <script>
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
