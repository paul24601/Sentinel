<?php
session_start();

$conn = new mysqli("localhost", "root", "injectionadmin123", "sensory_data");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | TS - Sensory Data</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/logo-2.png">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/webpage_defaults.css">
    <link rel="stylesheet" href="css/dashboard.css">
    
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
                <span>Technical Service Department - Sensory Data</span>
            </div>
            <div class="header-right">
            </div>
        </div>
        
        <!-- Active Machines -->
        <div class="section">
            <div class="content-header">
                <h2 style="margin: 0;">Active Machines</h2>
                <div class="section-controls">
                    <input type="text" placeholder="Search machine...">
                    <button type="button">
                        <i class="fa fa-sort"></i> Sort
                    </button>
                </div>
            </div>

            <!-- Machine Cards -->
            <div class="card-container">
                <div id="status-card" class="card machine-card active-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator active"></div>
                        <h2 id="machine-name">CLF 750A</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">1L PEPSI #4135</p>
                </div>
                
                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">ARB 50</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">SUM 260C</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">SUM 650</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">MIT 650D</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">SUM 260C</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">TOS 650A</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">CLF 750B</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">CLF 750C</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">TOS 850A</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">TOS 850B</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">TOS 850C</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">CLF 950A</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">CLF 950B</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>

                <div id="status-card" class="card machine-card inactive-border">
                    <div class="status-container">
                        <div id="status-indicator" class="status-indicator inactive"></div>
                        <h2 id="machine-name">MIT 1050B</h2>
                    </div>
                    <span class="current-product-label">Current Product:<br></span>
                    <p class="current-product-value">---</p>
                </div>
            </div>
        </div>

        <!-- Average Cycle Times -->
        <div class="section">
            <div class="content-header">
                <h2 style="margin: 0;">Average Cycle Times</h2>
                <div class="section-controls">
                    <div class="by_number">
                        <label for="show-entries">Week</label>
                        <select id="show-entries" onchange="updateTableDisplay()">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                
                    <div class="by_month">
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
                    <button class="machine-tab" data-machine="ARB50" onclick="selectMachineTab(this)">ARB50</button>
                    <button class="machine-tab" data-machine="SUM 260C" onclick="selectMachineTab(this)">SUM 260C</button>
                    <button class="machine-tab" data-machine="SUM 350" onclick="selectMachineTab(this)">SUM 350</button>
                    <button class="machine-tab" data-machine="MIT 650D" onclick="selectMachineTab(this)">MIT 650D</button>
                    <button class="machine-tab" data-machine="TOS 650A" onclick="selectMachineTab(this)">TOS 650A</button>
                    <button class="machine-tab active" data-machine="CLF 750A" onclick="selectMachineTab(this)">CLF 750A</button>
                    <button class="machine-tab" data-machine="CLF 750B" onclick="selectMachineTab(this)">CLF 750B</button>
                    <button class="machine-tab" data-machine="CLF 750C" onclick="selectMachineTab(this)">CLF 750C</button>
                    <button class="machine-tab" data-machine="TOS 850A" onclick="selectMachineTab(this)">TOS 850A</button>
                    <button class="machine-tab" data-machine="TOS 850B" onclick="selectMachineTab(this)">TOS 850B</button>
                    <button class="machine-tab" data-machine="TOS 850C" onclick="selectMachineTab(this)">TOS 850C</button>
                    <button class="machine-tab" data-machine="CLF 950A" onclick="selectMachineTab(this)">CLF 950A</button>
                    <button class="machine-tab" data-machine="CLF 950B" onclick="selectMachineTab(this)">CLF 950B</button>
                    <button class="machine-tab" data-machine="MIT 1050B" onclick="selectMachineTab(this)">MIT 1050B</button>
                    </div>
                </div>

                <script>
                    function selectMachineTab(tab) {
                        document.querySelectorAll('.machine-tab').forEach(b => b.classList.remove('active'));
                        tab.classList.add('active');
                        updateChart(tab.getAttribute('data-machine'));
                    }
                    function selectMachine(btn) {
                        document.querySelectorAll('.machine-btn').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        updateChart(btn.getAttribute('data-machine'));
                    }
                </script>

                <!-- Cycle Times Graph -->
                <div class="chart-container" style="width: -webkit-fill-available;">
                    <div id="chart"></div>
                    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                    <script>
                        // Example data for 5 weeks, each week has 7 days
                        // You can replace this with your real data source
                        const allData = {
                            "CLF 750A": {
                            actual: [
                                [112, 68, 88, 58, 60, 118, 58], // week 1
                                [120, 70, 90, 60, 62, 110, 65], // week 2
                                [115, 72, 85, 59, 61, 112, 60], // week 3
                                [118, 74, 87, 61, 63, 115, 62], // week 4
                                [119, 75, 89, 62, 64, 117, 63]  // week 5
                            ],
                            expected: [
                                [120, 75, 100, 60, 60, 120, 60],
                                [120, 75, 100, 60, 60, 120, 60],
                                [120, 75, 100, 60, 60, 120, 60],
                                [120, 75, 100, 60, 60, 120, 60],
                                [120, 75, 100, 60, 60, 120, 60]
                            ]
                            },
                            // Add other machines here if needed
                        };

                        // Set default month and week selection to current
                        function setDefaultMonthAndWeek() {
                            const now = new Date();
                            const month = now.getMonth() + 1; // 1-based
                            const day = now.getDate();
                            // Set month select
                            const monthSelect = document.getElementById('filter-month');
                            monthSelect.value = month;
                            // Calculate week number in month
                            const week = Math.ceil((day + (new Date(now.getFullYear(), now.getMonth(), 1).getDay() || 7) - 1) / 7);
                            const weekSelect = document.getElementById('show-entries');
                            weekSelect.value = week;
                        }

                        // When month changes, set week to the current week of that month if it's this month, else week 1
                        function onMonthChange() {
                            const now = new Date();
                            const selectedMonth = parseInt(document.getElementById('filter-month').value, 10);
                            const weekSelect = document.getElementById('show-entries');
                            if (selectedMonth === now.getMonth() + 1) {
                            // Current month: set to current week
                            const day = now.getDate();
                            const week = Math.ceil((day + (new Date(now.getFullYear(), now.getMonth(), 1).getDay() || 7) - 1) / 7);
                            weekSelect.value = week;
                            } else {
                            // Other month: default to week 1
                            weekSelect.value = 1;
                            }
                            updateTableDisplay();
                        }

                        // Helper to get day labels for a week (e.g., Mon, Tue, ...)
                        function getWeekDayLabels(week, month, year) {
                            // Find the first day of the month
                            const firstDay = new Date(year, month - 1, 1);
                            // Find the first day of the requested week
                            const firstWeekDay = new Date(firstDay);
                            firstWeekDay.setDate(1 + (week - 1) * 7);
                            let labels = [];
                            for (let i = 0; i < 7; i++) {
                            const d = new Date(firstWeekDay);
                            d.setDate(firstWeekDay.getDate() + i);
                            // Only show days that are in the current month
                            if (d.getMonth() === month - 1) {
                                labels.push(d.toLocaleDateString(undefined, { weekday: 'short', day: 'numeric' }));
                            } else {
                                labels.push('');
                            }
                            }
                            return labels;
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
                        function updateChart(selectedMachine) {
                            const week = parseInt(document.getElementById('show-entries').value, 10) - 1;
                            const month = parseInt(document.getElementById('filter-month').value, 10);
                            const year = new Date().getFullYear();
                            const machine = selectedMachine || document.querySelector('.machine-tab.active').getAttribute('data-machine');
                            const machineData = allData[machine] || allData["CLF 750A"];
                            const actualValues = machineData.actual[week] || [];
                            const expectedValues = machineData.expected[week] || [];
                            const labels = getWeekDayLabels(week + 1, month, year);

                            // Build dataPoints array for ApexCharts
                            const dataPoints = actualValues.map((val, i) => ({
                            x: labels[i] || (i + 1).toString(),
                            y: val,
                            goals: [
                                {
                                name: 'Expected',
                                value: expectedValues[i],
                                strokeHeight: 5,
                                strokeColor: palette.orange
                                }
                            ]
                            }));

                            const options = {
                            series: [
                                {
                                name: 'Actual',
                                data: dataPoints
                                }
                            ],
                            chart: {
                                height: 350,
                                type: 'bar',
                                toolbar: { show: false },
                                background: palette.black
                            },
                            plotOptions: {
                                bar: {
                                columnWidth: '60%',
                                borderRadius: 4,
                                colors: {
                                    backgroundBarColors: [palette.lightGray],
                                    backgroundBarOpacity: 0.2
                                }
                                }
                            },
                            colors: [palette.green],
                            dataLabels: {
                                enabled: false
                            },
                            legend: {
                                show: true,
                                showForSingleSeries: true,
                                customLegendItems: ['Actual Cycle Time', 'Expected Cycle Time'],
                                markers: {
                                fillColors: [palette.green, palette.orange]
                                },
                                labels: {
                                colors: [palette.white]
                                }
                            },
                            xaxis: {
                                labels: {
                                style: {
                                    colors: palette.white,
                                    fontFamily: rootStyles.getPropertyValue('--font-main').trim() || 'Montserrat, sans-serif'
                                }
                                },
                                axisBorder: {
                                color: palette.lightGray
                                },
                                axisTicks: {
                                color: palette.lightGray
                                }
                            },
                            yaxis: {
                                labels: {
                                style: {
                                    colors: palette.white,
                                    fontFamily: rootStyles.getPropertyValue('--font-main').trim() || 'Montserrat, sans-serif'
                                }
                                }
                            },
                            tooltip: {
                                theme: 'dark',
                                style: {
                                fontFamily: rootStyles.getPropertyValue('--font-main').trim() || 'Montserrat, sans-serif'
                                }
                            },
                            grid: {
                                borderColor: palette.lightGray,
                                strokeDashArray: 4
                            },
                            states: {
                                hover: {
                                filter: {
                                    type: 'lighten',
                                    value: 0.15
                                }
                                },
                                active: {
                                filter: {
                                    type: 'darken',
                                    value: 0.15
                                }
                                }
                            }
                            };

                            if (chart) {
                            chart.updateOptions(options, true, true);
                            } else {
                            chart = new ApexCharts(document.querySelector("#chart"), options);
                            chart.render();
                            }
                        }

                        // Dummy function for compatibility
                        function updateTableDisplay() {
                            updateChart();
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