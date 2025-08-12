<?php
date_default_timezone_set('Asia/Manila'); // or your correct timezone

$isAjax = isset($_GET['ajax']) && $_GET['ajax'] === '1';
ob_start();

session_start();

$conn = new mysqli("localhost", "root", "", "sensory_data");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Sensory Data</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/logo-2.png">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/webpage_defaults.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/table.css">
    
</head>
<body>
    <script src="script/navbar-sidebar.js"></script>

    <!-- Navbar -->
    <div class="navbar">
        <!-- Sidebar Toggle (Logo) -->
        <div id="sidebarToggle">
            <i class="fa fa-bars" style="color: #417630; font-size: 2rem; cursor: pointer;"></i> 
            <a href="#"><img src="images/logo-1.png" style="height: 36px"></a>
        </div>
        

        <!-- Right Icon with Logout Dropdown -->
        <div class="navbar-right" style="position: relative;">
            <i class="fa fa-user-circle" style="font-size: 2rem; color:#417630; cursor:pointer;" id="userIcon"></i>
            <div id="userDropdown">
                <a href="#">Settings</a>
                <a href="#">Logout</a>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="tabs">
            <p>CORE</p>
            <div class="sidebar-link-group">
                <a href="#" class="sidebar-link sidebar-active"><i class='bx  bx-dashboard-alt'></i> Dashboard</a>
            </div>
            <p>SYSTEMS</p>
            <div class="sidebar-link-group">
                <a href="#" class="sidebar-link">
                    <i class='bx  bx-timer'></i> Production Cycle
                    <span class="fa fa-caret-down" style="margin-left:8px;"></span>
                </a>
                <div class="sidebar-submenu">
                    <a href="production_cycle.php?machine=CLF750A" onclick="setMachineSession('CLF750A')">CLF 750A</a>
                    <a href="production_cycle.php?machine=CLF750B" onclick="setMachineSession('CLF750B')">CLF 750B</a>
                    <a href="production_cycle.php?machine=CLF750C" onclick="setMachineSession('CLF750C')">CLF 750C</a>
                </div>
                <script>
                    function setMachineSession(machine) {
                        // Store selected machine in sessionStorage
                        sessionStorage.setItem('selectedMachine', machine);
                    }
                </script>
            </div>
            <div class="sidebar-link-group">
                <a href="#" class="sidebar-link">
                    <i class='bx  bx-chart-network'></i>  Real-time Parameters
                    <span class="fa fa-caret-down" style="margin-left:8px;"></span>
                </a>
                <div class="sidebar-submenu" style="display:none;">
                    <a href="motor_temperatures.php">Motor Temperatures</a>
                    <a href="#">Coolant Flow Rates</a>
                </div>
            </div>
            <div class="sidebar-link-group">
                <a href="#" class="sidebar-link">
                    <i class='bx  bx-dumbbell'></i> Weight Data
                    <span class="fa fa-caret-down" style="margin-left:8px;"></span>
                </a>
                <div class="sidebar-submenu" style="display:none;">
                    <a href="weights.php">Gross/Net Weights</a>
                </div>
            </div>
        </div>
        <div id="sidebar-footer" class="sidebar-footer">
            <span style="font-size: 0.75rem; color: #646464">Logged in as:</span>
            <span>User123</span>
        </div>
    </div>

    <!-- Main -->
    <div class="main-content">

        <div class="header">
            <div class="header-left">
                <h3>Dashboard</h3>
                <span>Sensory Data</span>
            </div>
            <div class="header-right">
            </div>
        </div>
        
        <!-- Active Machines -->
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "injectionadmin123";
        $database = "sensory_data";

        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get all production_cycle_* tables
        $tables = [];
        $tableResult = $conn->query("SHOW TABLES LIKE 'production_cycle_%'");
        while ($row = $tableResult->fetch_array()) {
            $tables[] = $row[0]; // table name
        }
        ?>

        <div class="section">
            <div class="content-header">
                <h2 style="margin: 0;">Production Status</h2>
            </div>

            <div class="card-container" id="machine-card-container">
                <?php
                foreach ($tables as $table) {
                    // Clean machine name from table name (e.g., 'production_cycle_clf750a' => 'CLF 750A')
                    $machine_code = strtoupper(str_replace('production_cycle_', '', $table));
                    $machine_label = preg_replace('/(\D+)(\d+)/', '$1 $2', $machine_code); // Add space between letters and numbers

                    // Fetch latest row
                    $latest_sql = "SELECT * FROM `$table` ORDER BY timestamp DESC LIMIT 1";
                    $latest_result = $conn->query($latest_sql);
                    if ($latest_result && $latest_result->num_rows > 0) {
                        $latest = $latest_result->fetch_assoc();

                        // Determine cycle status
                        $cycle_status = (int)$latest['cycle_status'];
                        $borderClass = 'inactive-border';
                        $dotClass = 'inactive';

                        // Calculate inactive duration
                        $now = new DateTime();
                        $lastTimestamp = new DateTime($latest['timestamp']);
                        $diff = $now->diff($lastTimestamp);

                        if ($cycle_status === 0 || $cycle_status === 1) {
                            // Determine style based on status
                            if ($cycle_status === 0) {
                                $borderClass = 'active-border-open';
                                $dotClass = 'open';
                            } else {
                                $borderClass = 'active-border';
                                $dotClass = 'active';
                            }

                            // Find last time where cycle_time is NOT NULL and recycle_time == 0
                            $latestTimestampStr = $latest['timestamp'];
                            $active_sql = "SELECT timestamp FROM `$table` WHERE cycle_time != 0 AND recycle_time = 0 AND timestamp <= '$latestTimestampStr' ORDER BY timestamp DESC LIMIT 1";
                            $active_result = $conn->query($active_sql);

                            if ($active_result && $active_result->num_rows > 0) {
                                $active_row = $active_result->fetch_assoc();
                                $active_start = new DateTime($active_row['timestamp']);
                                $now = new DateTime();
                                $active_diff = $now->diff($active_start);

                                $days = $active_diff->days;
                                $hours = $active_diff->h;
                                $minutes = $active_diff->i;

                                $dayPart = $days > 0 ? "{$days}d " : "";
                                $hourPart = $hours > 0 ? "{$hours}h " : "";
                                $minutePart = $minutes > 0 ? "{$minutes}m " : "";

                                $statusText = trim("{$dayPart}{$hourPart}{$minutePart} active");
                            } else {
                                $statusText = "Active time unknown";
                            }
                        } else {
                            // Default: calculate inactive duration
                            $now = new DateTime();
                            $lastTimestamp = new DateTime($latest['timestamp']);
                            $diff = $now->diff($lastTimestamp);

                            $days = $diff->days;
                            $hours = $diff->h;
                            $minutes = $diff->i;

                            $dayPart = $days > 0 ? "{$days}d " : "";
                            $hourPart = $hours > 0 ? "{$hours}h " : "";
                            $minutePart = $minutes > 0 ? "{$minutes}m " : "";

                            $statusText = trim("{$dayPart}{$hourPart}{$minutePart} inactive");
                        }

                        // Handle product display
                        $productDisplay = ($cycle_status === 2) ? '---' : htmlspecialchars($latest['product']);
                        $productName = explode('|', $productDisplay)[0];  // Get text before " | "
                        $productName = trim($productName);

                        // Output the card
                        echo <<<HTML
                        <a href="production_cycle.php?machine={$machine_code}" style="text-decoration: none; color: inherit;">
                            <div class="card machine-card {$borderClass}">
                                <div class="status-container">
                                    <div class="status-indicator {$dotClass}"></div>
                                    <h2 class="machine-name">{$machine_label}</h2>
                                </div>
                                <p class="status-text">{$statusText}</p>
                                <span class="current-product-label">Current Product:<br></span>
                                <p class="current-product-value">{$productName}</p>
                            </div>
                        </a>
                        HTML;
                    }
                }
                ?>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">ARB 50</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">SUM 260C</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">SUM 650</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">MIT 650D</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">SUM 260C</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">TOS 650A</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">TOS 850A</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">TOS 850B</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">TOS 850C</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">CLF 950A</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">CLF 950B</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">MIT 1050B</h2>
                    </div>
                    <p class="status-text">is inactive</p>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>
            </div>
            <?php
            if ($isAjax) {
                ob_end_flush(); // Output only the cards
                exit;
            }
            ?>

            <script>
                function refreshMachineCards() {
                    fetch(window.location.pathname + '?ajax=1')
                        .then(res => res.text())
                        .then(html => {
                            const container = document.getElementById("machine-card-container");
                            const tempDiv = document.createElement("div");
                            tempDiv.innerHTML = html;

                            const newCards = tempDiv.querySelector("#machine-card-container");
                            if (newCards) {
                                container.innerHTML = newCards.innerHTML;
                            }
                        })
                        .catch(err => console.error("Failed to refresh machine cards:", err));
                }

                document.addEventListener("DOMContentLoaded", () => {
                    refreshMachineCards(); // Initial fetch
                    setInterval(refreshMachineCards, 5000); // Auto-refresh every 5 sec
                });
            </script>
        </div>
        
        <!-- Last Cycle Data -->
        <div class="section">
            <div class="content-header">
                <h2 style="margin: 0;">Last Cycle Data</h2>
            </div>

            <div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Machine Name</th>
                            <th>Last Cycle Time (seconds)</th>
                            <th>Last Processing Time (seconds)</th>
                            <th>Last Recycle Time (seconds)</th>
                            <th>Timestamp</th>
                            <th>Last Gross Weight (g)</th>
                            <th>Last Net Weight (g)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
                <script>
                    function fetchLastCycleData() {
                        fetch('fetch/fetch_production_cycle_last_entries.php')
                            .then(res => res.json())
                            .then(data => {
                                const tbody = document.querySelector(".styled-table tbody");
                                tbody.innerHTML = "";

                                if (Array.isArray(data)) {
                                    data.forEach(row => {
                                        const tr = document.createElement("tr");
                                        tr.innerHTML = `
                                            <td>${row.machine}</td>
                                            <td>${row.cycle_time}</td>
                                            <td>${row.processing_time}</td>
                                            <td>${row.recycle_time}</td>
                                            <td>${row.timestamp}</td>
                                            <td>${parseFloat(row.gross_weight).toFixed(2)}</td>
                                            <td>${parseFloat(row.net_weight).toFixed(2)}</td>
                                        `;
                                        tbody.appendChild(tr);
                                    });
                                } else {
                                    tbody.innerHTML = `<tr><td colspan="7">No data found</td></tr>`;
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching last cycles:', error);
                                const tbody = document.querySelector(".styled-table tbody");
                                tbody.innerHTML = `<tr><td colspan="7">Error loading data</td></tr>`;
                            });
                    }

                    document.addEventListener("DOMContentLoaded", function () {
                        fetchLastCycleData(); // Initial fetch
                        setInterval(fetchLastCycleData, 15000); // Auto-refresh every 15 seconds
                    });
                </script>
            </div>
        </div>

        <!-- Average Cycle Times -->
        <div class="section">
            <div class="content-header">
                <h2 style="margin: 0;">Daily Cycle Data</h2>

                <div class="section-controls">
                    <div class="by_month" style="width: min-content;">
                        <label for="filter-month">Month</label>
                        <select id="filter-month" onchange="onMonthChange()">
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

            <div class="contents">
                <!-- Machine Selection -->
                <div class="machine-tabs">
                    <label for="machine-select" style="display:none;">Select Machine:</label>
                    <div id="machine-tab-list" class="machine-tab-list">
                        <button class="machine-tab" data-machine="ARB50" onclick="selectMachineTab(this)" disabled>ARB50</button>
                        <button class="machine-tab" data-machine="SUM 260C" onclick="selectMachineTab(this)" disabled>SUM 260C</button>
                        <button class="machine-tab" data-machine="SUM 350" onclick="selectMachineTab(this)" disabled>SUM 350</button>
                        <button class="machine-tab" data-machine="MIT 650D" onclick="selectMachineTab(this)" disabled>MIT 650D</button>
                        <button class="machine-tab" data-machine="TOS 650A" onclick="selectMachineTab(this)" disabled>TOS 650A</button>
                        <button class="machine-tab active" data-machine="CLF 750A" onclick="selectMachineTab(this)">CLF 750A</button>
                        <button class="machine-tab" data-machine="CLF 750B" onclick="selectMachineTab(this)">CLF 750B</button>
                        <button class="machine-tab" data-machine="CLF 750C" onclick="selectMachineTab(this)">CLF 750C</button>
                        <button class="machine-tab" data-machine="TOS 850A" onclick="selectMachineTab(this)" disabled>TOS 850A</button>
                        <button class="machine-tab" data-machine="TOS 850B" onclick="selectMachineTab(this)" disabled>TOS 850B</button>
                        <button class="machine-tab" data-machine="TOS 850C" onclick="selectMachineTab(this)" disabled>TOS 850C</button>
                        <button class="machine-tab" data-machine="CLF 950A" onclick="selectMachineTab(this)" disabled>CLF 950A</button>
                        <button class="machine-tab" data-machine="CLF 950B" onclick="selectMachineTab(this)" disabled>CLF 950B</button>
                        <button class="machine-tab" data-machine="MIT 1050B" onclick="selectMachineTab(this)" disabled>MIT 1050B</button>
                    </div>
                </div>

                <!-- Graph -->
                <div class="chart-container" style="width: -webkit-fill-available;">
                    <div class="chart-scroll-wrapper">    
                        <div id="chart"></div>
                        <div id="no-data-message" style="text-align:center; font-size: 12px; color: #646464; margin-top: 0px;"></div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                    <script>
                        function onMonthChange() {
                            updateChart();
                        }

                        function selectMachineTab(tab) {
                            document.querySelectorAll('.machine-tab').forEach(b => b.classList.remove('active'));
                            tab.classList.add('active');
                            updateChart(tab.getAttribute('data-machine'));
                            onMonthChange();
                        }

                        // Set default month and week selection to current
                        function setDefaultMonthAndWeek() {
                            const now = new Date();
                            const month = now.getMonth() + 1; // 1-based
                            // Set month select
                            const monthSelect = document.getElementById('filter-month');
                            monthSelect.value = month;
                        }
                        

                        // Chart palette
                        const rootStyles = getComputedStyle(document.documentElement);
                        const palette = {
                            green: rootStyles.getPropertyValue('--green').trim() || '#417630',
                            darkGreen: rootStyles.getPropertyValue('--dark-green').trim() || '#23441e',
                            orange: rootStyles.getPropertyValue('--orange').trim() || '#f59c2f',
                            white: rootStyles.getPropertyValue('--white').trim() || '#d8d8d8',
                            gray: rootStyles.getPropertyValue('--gray').trim() || '#1a1a1a',
                            lightGray: rootStyles.getPropertyValue('--light-gray').trim() || '#646464',
                            black: rootStyles.getPropertyValue('--black').trim() || '#101010'
                        };

                        // Chart instance
                        let chart = null;

                        // Update chart based on selected week/machine
                        async function updateChart(selectedMachine = null) {
                            const machine = selectedMachine || document.querySelector('.machine-tab.active')?.getAttribute('data-machine');
                            const month = document.getElementById('filter-month').value;

                            const response = await fetch(`fetch/fetch_average_cycle_time.php?machine=${encodeURIComponent(machine)}&month=${month}`);
                            const result = await response.json();

                            if (result.status !== 'success') {
                                document.getElementById('chart').innerHTML = `
                                                                            <div style="
                                                                                height: 100%;
                                                                                display: flex;
                                                                                justify-content: center;
                                                                                align-items: center;
                                                                                color: red;
                                                                                font-size: 0.75rem;
                                                                                font-family: 'Montserrat', sans-serif;
                                                                                text-align: center;
                                                                                padding: 174px;
                                                                            ">
                                                                                ${result.message}
                                                                            </div>`;
                                return;
                            }
                            
                            // Also show days without data
                            const chartData = result.data;

                            const numericMonth = Number(month); // convert string to number
                            const year = new Date().getFullYear();
                            const daysInMonth = new Date(year, numericMonth, 0).getDate();

                            const allDays = [];
                            for (let i = 1; i <= daysInMonth; i++) {
                                const d = new Date(year, numericMonth - 1, i);
                                allDays.push(d.toLocaleDateString('sv-SE')); // → "YYYY-MM-DD" in local time
                            }

                            // Map actual data by date for quick lookup
                            const dataMap = {};
                            chartData.forEach(row => {
                                dataMap[row.date] = row;
                            });

                            // Create series data aligned with full month
                            const categories = [];
                            const avgSeries = [];
                            const minSeries = [];
                            const maxSeries = [];

                            allDays.forEach(dateStr => {
                                const d = new Date(dateStr);
                                categories.push(d.getDate().toString());

                                
                                if (dataMap[dateStr]) {
                                    avgSeries.push(dataMap[dateStr].average);
                                    minSeries.push(dataMap[dateStr].min);
                                    maxSeries.push(dataMap[dateStr].max);
                                } else {
                                    avgSeries.push(null);
                                    minSeries.push(null);
                                    maxSeries.push(null);
                                }
                            });

                            const noDataMessage = document.getElementById('no-data-message');

                            if (chartData.length === 0) {
                                if (chart) {
                                    chart.updateSeries([
                                        { name: 'Average', data: [] },
                                        { name: 'Minimum', data: [] },
                                        { name: 'Maximum', data: [] }
                                    ]);
                                }
                                noDataMessage.textContent = "No data for this month.";
                                return;
                            } else {
                                noDataMessage.textContent = ""; // Clear the message when data exists
                            }

                            const options = {
                                series: [
                                    { name: 'Average', data: avgSeries },
                                    { name: 'Minimum', data: minSeries },
                                    { name: 'Maximum', data: maxSeries }
                                ],
                                chart: {
                                    type: 'bar',
                                    height: 350,
                                    background: palette.black,
                                    toolbar: { show: false }
                                },
                                plotOptions: {
                                bar: {
                                    columnWidth: '55%',
                                    borderRadius: 2,
                                    borderRadiusApplication: 'end', // Apply only to top
                                    dataLabels: { position: 'top' }
                                }
                                },
                                colors: ['#417630', '#f59c2f', '#adadad'],
                                dataLabels: { enabled: false },
                                legend: {
                                position: 'bottom',
                                labels: { colors: [palette.white] }
                                },
                                xaxis: {
                                    type: 'category',
                                    categories: categories,
                                    labels: {
                                        style: {
                                            colors: palette.white,
                                            fontFamily: 'Montserrat, sans-serif'
                                        }
                                    },
                                    axisBorder: { color: palette.lightGray },
                                    axisTicks: { color: palette.lightGray }
                                },
                                yaxis: {
                                    labels: {
                                        style: {
                                            colors: palette.white,
                                            fontFamily: 'Montserrat, sans-serif'
                                        }
                                    }
                                },
                                tooltip: {
                                    theme: 'dark'
                                },
                                grid: {
                                    borderColor: palette.lightGray,
                                    strokeDashArray: 4
                                }
                            };

                            if (chart) {
                                chart.updateOptions(options, true, true);
                            } else {
                                chart = new ApexCharts(document.querySelector("#chart"), options);
                                chart.render();
                            }
                        }

                        // Set defaults on page load
                        document.addEventListener('DOMContentLoaded', function() {
                            setDefaultMonthAndWeek();
                            updateChart();
                        });
                    </script>
                    <style>
                        /* ApexCharts custom tooltip and legend color overrides */
                        .apexcharts-tooltip {
                            background: var(--gray) !important;
                            color: var(--white) !important;
                            border: 1px solid var(--green) !important;
                            font-family: var(--font-main), sans-serif !important;
                        }
                        .apexcharts-legend-text {
                            color: var(--white) !important;
                            font-family: var(--font-main), sans-serif !important;
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</body>
</html>