<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Monitoring Sheet for Injection Department</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Times, sans-serif;
        }
        header {
            background-color: #0000;
            padding: 10px;
            box-shadow: 0 0 3px #000;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        header img {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        .container {
            width: 100%;
            max-width: 100vw;
            overflow-x: auto;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 1px;
            text-align: left;
        }
        th {
            background-color: #0000;
        }
        td[contenteditable] {
            background-color: #ffff;
        }
        .delete-btn {
            background-color: #f44336; /* Red */
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }
        @media (max-width: 768px) {
            th, td {
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Daily Monitoring Sheet</h1>
        <h3>NOTE: Add remarks if Product has NO STANDARD CYCLE TIME AND WEIGHT</h3>
    </header>
    <form id="dataForm">
        <div class="container">
            <table id="dataTable">
                <tr>
                    <th rowspan="2">MACHINE</th>
                    <th rowspan="2">PRN</th>
                    <th rowspan="2">PRODUCT NAME</th>
                    <th colspan="2">CYCLE TIME(s)</th>
                    <th colspan="3">WEIGHT(grams/pc)</th>
                    <th colspan="2">NO. OF CAVITY</th>
                    <th rowspan="2">REMARKS</th>
                </tr>
                <tr>
                    <th>Target</th>
                    <th>Actual</th>
                    <th>Standard</th>
                    <th>Gross</th>
                    <th>Net</th>
                    <th>Designed</th>
                    <th>Active</th>
                </tr>
                <!-- Initial rows of inputs -->
                <?php for ($i = 0; $i < 1; $i++): ?>
                <tr>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                    <td contenteditable="true"></td>
                </tr>
                <?php endfor; ?>
            </table>
            <button type="submit">Submit</button>
        </div>
    </form>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            let data = [];
            const rows = document.querySelectorAll('#dataTable tr');
            rows.forEach((row, index) => {
                if (index > 0) { // Skip the header row
                    let rowData = [];
                    row.querySelectorAll('td').forEach((cell) => {
                        rowData.push(cell.innerText.trim());
                    });
                    if (rowData.length > 0 && rowData.some(cell => cell !== '')) {
                        data.push(rowData);
                    }
                }
            });

            fetch('submit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text())
            .then(data => {
                console.log('Success:', data);
                alert('Data submitted successfully');
                window.location.reload(); // Reload the page to see updated data
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('There was an error submitting the data');
            });
        });
    </script>

    <!-- Display saved data here -->
    <div class="container">
        <h2>Saved Data</h2>
        <table id="savedDataTable">
            <thead>
                <tr>
                    <th>MACHINE</th>
                    <th>PRN</th>
                    <th>PRODUCT NAME</th>
                    <th>Cycle Time Target</th>
                    <th>Cycle Time Actual</th>
                    <th>Weight Standard</th>
                    <th>Weight Gross</th>                    
                    <th>Weight Net</th>
                    <th>No. of Cavity Designed</th>
                    <th>No. of Cavity Active</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be populated with PHP -->
                <?php
                // Connect to the database
                $conn = new mysqli("localhost", "root", "", "dms_db");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch data from the database
                $sql = "SELECT * FROM dms_data";
                $result = $conn->query($sql);

                // Display data in table rows
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['machine']) . "</td>
                            <td>" . htmlspecialchars($row['prn']) . "</td>
                            <td>" . htmlspecialchars($row['product_name']) . "</td>
                            <td>" . htmlspecialchars($row['cycle_time_target']) . "</td>
                            <td>" . htmlspecialchars($row['cycle_time_actual']) . "</td>
                            <td>" . htmlspecialchars($row['weight_standard']) . "</td>
                            <td>" . htmlspecialchars($row['weight_gross']) . "</td>
                            <td>" . htmlspecialchars($row['weight_net']) . "</td>
                            <td>" . htmlspecialchars($row['no_of_cavity_designed']) . "</td>
                            <td>" . htmlspecialchars($row['no_of_cavity_active']) . "</td>
                            <td>" . htmlspecialchars($row['remarks']) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No data found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

