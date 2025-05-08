<?php
$conn = new mysqli("localhost", "root", "injectionadmin123", "sensory_data");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM realtime_parameters ORDER BY timestamp DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Real-time Parameters</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        body {
            background: linear-gradient(to bottom, #1a1a1a, #1a1a1a, #1a1a1a, #1a1a1a, #1a1a1a, #1a1a1a, #1a1a1a, #304728);
            background-attachment: fixed;
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            margin: 54px;
            color: white;
        }

        .logo {
            height: 80px;
        }

        /* Nav Bar*/
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color:rgb(16, 16, 16);
            padding: 10px 54px;
            box-shadow: 0 0 48px #417630;
            margin: -54px -54px 0px -54px;
        }

        .navbar .logo {
            height: 50px;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            padding: 0;
        }

        .navbar ul li {
            margin: 0 15px;
        }
        
        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
        }

        .navbar ul li a:hover {
            color: #f4a261;
        }
        /* Cards */

        .content-header {
            margin: 0 0 24px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section {
            overflow-x: hidden;
            background-color:rgb(16, 16, 16);
            padding: 32px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
            margin: 20px 0px;
        }
        
        .btn-download {
            display: inline-block;
            margin-top: 24px;
            padding: 10px 20px;
            background-color: #417630;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
        }

        h2 {
            margin: 0px;
        }

        .container {
            display: flex;
            justify-content: space-between;
        }

        .content {
            width: 50%;
        }

        /* Real-time Parameters */
        .card-container {
            display: flex;
            gap: 20px;
        }

        .card {
            width: -webkit-fill-available;
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
            text-align: center;
        }

        .temperature1-card {
            border-left: 10px solid;
            border-left-color: #FFB347;
        }

        .temperature2-card {
            border-left: 10px solid;
            border-left-color: #FF6347;
        }

        .humidity-card {
            border-left: 10px solid;
            border-left-color: #32CD32;
        }

        .waterflow-card {
            border-left: 10px solid;
            border-left-color: #1E90FF;
        }

        .airflow-card {
            border-left: 10px solid;
            border-left-color: #8b8b8b;
        }

        .chart-container {
            width: 100%;
            height: 50px;
        }

        canvas {
            width: 100% !important;
            height: 50px !important;
        }
        /* Real-time Parameters */

        .btn {
            cursor: pointer;
            border: none;
            font-family: inherit;
            display: inline-block;
            padding: 10px 20px;
            background-color: #417630;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #365b25;
        }

        /* Table */
        .styled-table {
            margin-top: 0px;
            width: -webkit-fill-available;
            border-collapse: collapse;
            font-size: 0.9em;
            font-family: 'Montserrat', sans-serif;
            min-width: 400px;
            background-color: #1a1a1a;
            color: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #417630;
            color: white;
            text-align: center;
        }

        .styled-table th, .styled-table td {
            padding: 12px 15px;
            text-align: center;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #444;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #333;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #417630;
        }

        .styled-table tbody tr.active-row {
            font-weight: bold;
            color: #009879;
        }

        #show-entries, #filter-month, #month-select {
            cursor: pointer;
            padding: 11px;
            background-color: #222;
            color: white;
            border: 1px solid #417630;
            border-radius: 5px;
            font-size: 14px;
            height: fit-content;
        }

        #month-select {
            margin: 0px 20px;
        }

        label {
            color: white;
            font-size: 16px;
            margin-right: 5px;
        }
        
        .table-controls {
            margin-left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        /* Table */
    </style>
</head>
<body>
    <!-- Nav Bar -->
        <div class="navbar">
            <img src="/sensory_data/pics/logo 1.png" alt="logo" class="logo">
            <ul>
                <li><a href="production_cycle.php">Production Cycle</a></li>
                <li class="dropdown">
                    <a href="#">Real-time Parameters</a>
                    <div class="dropdown-content">
                        <ul>
                            <li><a href="motor_temperatures.php">Motor Temperatures</a></li>
                            <li><a href="water_flow.php">Water Flow</a></li>
                            <li><a href="air_flow.php">Air Flow</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <style>
            .navbar ul {
                list-style-type: none;
            }

            .navbar ul li {
                display: inline-block;
                position: relative;
            }

            .dropdown {
                position: relative;
                display: inline-block;
                cursor: pointer;
            }

            .dropdown-content {
                position: absolute;
                background-color: rgb(16, 16, 16);
                min-width: 180px;
                box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
                z-index: 1;
                opacity: 0;
                transform: translateY(-10px);
                visibility: hidden;
                transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
            }

            .dropdown-content li {
                padding: 12px 0px;
            }

            .dropdown-content ul {
                list-style: none;
                padding: 0;
                margin: 0;
                flex-direction: column;
            }

            .dropdown:hover .dropdown-content {
                opacity: 1;
                transform: translateY(0);
                visibility: visible;
            }
        </style>
    <!-- Nav Bar -->
    
    <h1 style="text-align: left; color:rgb(78, 187, 42);">REAL-TIME PARAMETERS</h1>
    
    <!-- Cards -->
        <div class="section">
                <div class="content-header">
                    <h2>Real-time Parameters</h2>
                </div>
                
                <?php
                    // Fetch the latest row from the database
                    $sql = "SELECT tempC_01, tempC_02, humidity, water_flow FROM realtime_parameters ORDER BY timestamp DESC LIMIT 1";
                    $result = $conn->query($sql);
                    $data = $result->fetch_assoc();
                ?>
                
                <div class="card-container">
                    <div class="card temperature1-card">
                        <h2 id="temp01-value">--°C</h2>
                        <p>Temp 01</p>
                        <div class="chart-container">
                            <canvas id="chartTemp01"></canvas>
                        </div>
                    </div>

                    <div class="card temperature2-card">
                        <h2 id="temp02-value">--°C</h2>
                        <p>Temp 02</p>
                        <div class="chart-container">
                            <canvas id="chartTemp02"></canvas>
                        </div>
                    </div>

                    <div class="card humidity-card">
                        <h2 id="humidity-value">--%</h2>
                        <p>Humidity</p>
                        <div class="chart-container">
                            <canvas id="chartHumidity"></canvas>
                        </div>
                    </div>

                    <div class="card waterflow-card">
                        <h2 id="waterflow-value">-- L/m</h2>
                        <p>Water Flow</p>
                        <div class="chart-container">
                            <canvas id="chartWaterFlow"></canvas>
                        </div>
                    </div>
                    
                    <div class="card airflow-card">
                        <h2 id="airflow-value">-- FPM</h2>
                        <p>Air Flow</p>
                        <div class="chart-container">
                            <canvas id="chartAirFlow"></canvas>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        let chartTemp01, chartTemp02, chartHumidity, chartWaterFlow, chartAirFlow;

                        function fetchRealtimeData() {
                            fetch("fetch/fetch_latest_data.php?type=realtime")
                                .then(response => response.json())
                                .then(data => {
                                    // Update text values
                                    document.getElementById("temp01-value").innerText = data.tempC_01[0] + "°C";
                                    document.getElementById("temp02-value").innerText = data.tempC_02[0] + "°C";
                                    document.getElementById("humidity-value").innerText = data.humidity[0] + "%";
                                    document.getElementById("waterflow-value").innerText = data.water_flow[0] + " L/m";
                                    document.getElementById("airflow-value").innerText = data.air_flow[0] + " FPM";

                                    // Update charts dynamically
                                    updateChart(chartTemp01, data.tempC_01.reverse());
                                    updateChart(chartTemp02, data.tempC_02.reverse());
                                    updateChart(chartHumidity, data.humidity.reverse());
                                    updateChart(chartWaterFlow, data.water_flow.reverse());
                                    updateChart(chartAirFlow, data.air_flow.reverse());
                                })
                                .catch(error => console.error("Error fetching real-time data:", error));
                        }

                        function createChart(canvasId, color) {
                            return new Chart(document.getElementById(canvasId), {
                                type: "line",
                                data: {
                                    labels: Array.from({length: 10}, (_, i) => i + 1),
                                    datasets: [{
                                        data: [],
                                        borderColor: color,
                                        borderWidth: 2,
                                        pointRadius: 2,
                                        fill: false,
                                        tension: 0.3
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        x: { display: false },
                                        y: { display: false }
                                    },
                                    plugins: { legend: { display: false } }
                                }
                            });
                        }

                        function updateChart(chart, newData) {
                            if (chart) {
                                chart.data.datasets[0].data = newData;
                                chart.update();
                            }
                        }

                        // Initialize charts
                        chartTemp01 = createChart("chartTemp01", "#FFB347");
                        chartTemp02 = createChart("chartTemp02", "#FF6347");
                        chartHumidity = createChart("chartHumidity", "#32CD32");
                        chartWaterFlow = createChart("chartWaterFlow", "#1E90FF");
                        chartAirFlow = createChart("chartAirFlow", "#8b8b8b");

                        // Fetch initial data
                        fetchRealtimeData();

                        // Auto-update every 5 seconds
                        setInterval(fetchRealtimeData, 5000);
                    });
                </script>
            </div>
        </div>
    <!-- Cards -->

    <!-- Graph -->
        <div class="section">
            <div class="content-header">
                <h2 style="text-align: left">Average Readings</h2>

                <div class="table-controls">
                    <button class="btn" onclick="changeMonth(-1)">&#9665;</button>
                    <select id="month-select" onchange="updateChart()">
                        <?php
                        for ($m = 1; $m <= 12; $m++) {
                            $monthName = date('F', mktime(0, 0, 0, $m, 1));
                            $selected = ($m == date('m')) ? "selected" : "";
                            echo "<option value='$m' $selected>$monthName</option>";
                        }
                        ?>
                    </select>
                    <button class="btn" onclick="changeMonth(1)">&#9655;</button>
                </div>
            </div>
            
            <div style="box-shadow: 2px 2px 10px rgba(0,0,0,0.3); background: #1a1a1a; padding: 12px; border-radius: 10px;">
                <div class="data">
                    <div class="content-data">
                        <div class="chart">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

            <script>
                var chart;

                function fetchChartData(month) {
                    fetch(`fetch/fetch_chart_data.php?month=${month}`)
                        .then(response => response.json())
                        .then(data => {
                            chart.updateOptions({
                                series: [
                                    { name: 'Temperature_01 (°C)', data: data.tempC_01 },
                                    { name: 'Temperature_02 (°C)', data: data.tempC_02 },
                                    { name: 'Humidity (%)', data: data.humidity },
                                    { name: 'Water Flow (L/min)', data: data.waterFlow },
                                    { name: 'Air Flow (FPM)', data: data.airFlow }
                                ],
                                xaxis: { categories: data.days }
                            });
                        });
                }

                function changeMonth(step) {
                    let select = document.getElementById('month-select');
                    let newMonth = parseInt(select.value) + step;

                    if (newMonth >= 1 && newMonth <= 12) {
                        select.value = newMonth;
                        updateChart();
                    }
                }

                function updateChart() {
                    let selectedMonth = document.getElementById('month-select').value;
                    fetchChartData(selectedMonth);
                }

                document.addEventListener("DOMContentLoaded", function () {
                    var options = {
                        series: [],
                        chart: {
                            height: 400,
                            type: 'area',
                            background: '#1a1a1a',
                            foreColor: '#ffffff'
                        },
                        colors: ['#FFB347', '#FF6347', '#32CD32', '#1E90FF', '#8b8b8b'],
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 2 },
                        grid: { borderColor: '#444', strokeDashArray: 5 },
                        xaxis: { categories: [] },
                        yaxis: { labels: { style: { colors: '#F8F8F8' } } },
                        tooltip: { theme: "dark" },
                        fill: {
                            type: 'gradient',
                            gradient: { shade: 'dark', type: "vertical", gradientToColors: ['#365b25', '#2a4720', '#4d7c3d'], stops: [0, 100] }
                        },
                        legend: { labels: { colors: '#ffffff' } }
                    };

                    chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();

                    updateChart();
                });
            </script>
        </div>
    </div>
    <!-- Graph -->

    <!-- Table -->
        <div class="section">
            <div class="content-header">
                <h2>Parameter History</h2>

                <div class="table-controls">
                    <div class="by_number">
                        <label for="show-entries">Show</label>
                        <select id="show-entries" onchange="reloadPage()">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <div class="by_month" style="margin-left: 20px;">
                        <label for="filter-month">Filter by month</label>
                        <select id="filter-month" onchange="reloadPage()">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>
            </div>

            <script>
                function reloadPage() {
                    let month = document.getElementById("filter-month").value;
                    let entries = document.getElementById("show-entries").value;
                    window.location.href = `?month=${month}&entries=${entries}`;
                }

                // Set dropdowns to match current URL parameters
                document.addEventListener("DOMContentLoaded", function () {
                    let urlParams = new URLSearchParams(window.location.search);
                    let selectedMonth = urlParams.get("month") || new Date().getMonth() + 1;
                    let selectedEntries = urlParams.get("entries") || 10;

                    document.getElementById("filter-month").value = selectedMonth;
                    document.getElementById("show-entries").value = selectedEntries;
                });
            </script>

            <?php
            $selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
            $showEntries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
            $currentYear = date('Y');

            $sql = "SELECT * FROM realtime_parameters 
                    WHERE MONTH(timestamp) = $selectedMonth 
                    AND YEAR(timestamp) = $currentYear 
                    ORDER BY timestamp DESC 
                    LIMIT $showEntries";

            $result = $conn->query($sql);
            ?>

            <table id="sensorTable" class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Temperature_01 (°C)</th>
                        <th>Humidity (%)</th>
                        <th>Temperature_02 (°C)</th>
                        <th>Water Flow (L/m)</th>
                        <th>Air Flow (FPM)</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['tempC_01']; ?></td>
                        <td><?php echo $row['humidity']; ?></td>
                        <td><?php echo $row['tempC_02']; ?></td>
                        <td><?php echo $row['water_flow']; ?></td>
                        <td><?php echo $row['air_flow']; ?></td>
                        <td><?php echo $row['timestamp']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php $conn->close(); ?>

            <a href="generate_pdf.php?table=realtime_parameters" class="btn-download">Download PDF</a>
            <a href="generate_excel.php?table=realtime_parameters" class="btn-download" style="margin-left: 20px;">Download Excel</a>
        </div>
    <!-- Table -->
</body>
</html>
