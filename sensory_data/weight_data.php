<?php
$conn = new mysqli("localhost", "root", "", "sensory_data");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM production_cycle ORDER BY timestamp DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weight Data</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        body {
            background: linear-gradient(to bottom, #1a1a1a, #1a1a1a, #1a1a1a, #1a1a1a, #1a1a1a, #1a1a1a, #1a1a1a,rgb(27, 37, 23));
            background-attachment: fixed;
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            margin: 54px;
            color: white;
        }

        .logo {
            height: 80px;
        }

        /* Nav Bar */
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
        /* Nav Bar */

        .section {
            overflow-x: hidden;
            background-color:rgb(16, 16, 16);
            padding: 32px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
            margin: 20px 0px;
        }

        .content-header {
            margin: 0 0 24px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Production Parameters */
        .status-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        .inactive { background-color: red; }
        .active { background-color: green; }

        .inactive-border {border-left: 10px solid; border-left-color: red;}
        .active-border {border-left: 10px solid; border-left-color: green;}

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.6; }
            100% { transform: scale(1); opacity: 1; }
        }

        .card-container {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
            width: -webkit-fill-available;
            text-align: center;
            position: relative;
        }

        .temperature1-card {
            border-left: 10px solid;
            border-left-color: #FFB347; /* Matches the orange graph */
        }

        .temperature2-card {
            border-left: 10px solid;
            border-left-color: #FF6347; /* Matches the red graph */
        }
        
        .pressure-card {
            border-left: 10px solid;
            border-left-color: #417630; /* Matches the green graph */
        }

        h2 {
            margin: 0;
        }

        .chart-container {
            width: 120px;  /* Set fixed width for proper alignment */
            height: 120px; /* Ensures circular charts don't get squished */
            margin: 10px auto; /* Centers the chart */
            position: relative;
        }
        
        canvas {
            display: block;
            width: 100% !important;
            height: 100% !important;
        }
        /* Production Parameters */

        /* Table */
        .table-controls {
            margin-left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .btn-download:hover {
            background-color: #365b25;
        }

        .styled-table {
            margin: 0;
            border-collapse: collapse;
            font-size: 0.9em;
            width: -webkit-fill-available;
            background-color: #1a1a1a;
            color: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #417630;
            color: #ffffff;
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

        #show-entries, #filter-month {
            padding: 8px;
            background-color: #222;
            color: white;
            border: 1px solid #417630;
            border-radius: 5px;
            font-size: 14px;
        }
        /* Table */

        label {
            color: white;
            font-size: 16px;
            margin-right: 5px;
        }
    </style>
</head>
<div>
    
    <!-- Nav Bar -->
        <div class="navbar">
            <img src="/sensory_data/pics/logo 1.png" alt="logo" class="logo">
            <ul>
                <li><a href="production_cycle.php">Production Cycle</a></li>
                <li><a href="#">Weight Data</a></li>
                <li class="dropdown">
                    <a href="realtime_parameters.php">Real-time Parameters</a>
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

    <h1 style="text-align: left; color:rgb(78, 187, 42);">WEIGHT DATA</h1>

    <!-- Table -->
        <div class="section">
            <div class="content-header">
                <div class="table-controls">
                    <div class="table-controls">
                        <div class="by_number">
                            <label for="show-entries">Show</label>
                            <select id="show-entries" onchange="updateTableDisplay()">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        
                        <div style="margin-left: 20px;" class="by_month">
                            <label for="show-entries">Filter by month</label>
                            <select id="filter-month" onchange="updateTableDisplay()">
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
            </div>

            <script>
                function updateTableDisplay() {
                    let rows = document.querySelectorAll("#sensorTable tbody tr");
                    let showCount = parseInt(document.getElementById("show-entries").value);
                    let selectedMonth = parseInt(document.getElementById("filter-month").value); // Get selected month
                    let currentYear = new Date().getFullYear(); // Get current year

                    let visibleRows = 0;
                    rows.forEach(row => {
                        let timestampCell = row.cells[row.cells.length - 1].innerText; // Get timestamp
                        let rowDate = new Date(timestampCell);
                        let rowMonth = rowDate.getMonth() + 1; // JS months are 0-based
                        let rowYear = rowDate.getFullYear();

                        // Show only if the row is in the selected month & year
                        if (rowMonth === selectedMonth && rowYear === currentYear) {
                            row.style.display = visibleRows < showCount ? "" : "none";
                            visibleRows++;
                        } else {
                            row.style.display = "none";
                        }
                    });
                }

                // Automatically set the filter-month dropdown to the current month
                document.addEventListener("DOMContentLoaded", function () {
                    let currentMonth = new Date().getMonth() + 1; // Get current month (1-based)
                    document.getElementById("filter-month").value = currentMonth;
                    updateTableDisplay();
                });
                // Initial setup
                document.addEventListener("DOMContentLoaded", updateTableDisplay);
            </script>

            <table id="sensorTable" class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Gross Weight (kg)</th>
                        <th>Net Weight (kg)</th>
                        <th>Difference (kg)</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "sensory_data");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "SELECT * FROM weight_data ORDER BY timestamp DESC";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['product']; ?></td>
                        <td><?php echo $row['gross_weight']; ?></td>
                        <td><?php echo $row['net_weight']; ?></td>
                        <td><?php echo $row['difference']; ?></td>
                        <td><?php echo $row['timestamp']; ?></td>
                    </tr>
                    <?php }
                    $conn->close(); ?>
                </tbody>
            </table>
                    
            <a href="generate_pdf.php?table=production_cycle" class="btn-download">Download PDF</a>
            <a href="generate_excel.php?table=production_cycle" class="btn-download" style="margin-left: 20px;">Download Excel</a>
        </div>
    <!-- Table -->

</body>
</html>
