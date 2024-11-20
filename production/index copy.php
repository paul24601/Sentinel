<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Report Form</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Production Report Form</h2>
    <!-- Form starts here -->
    <form action="process_production_report.php" method="POST">

        <!-- Plant Information Section -->
        <fieldset>
            <legend>Plant Information</legend>
            <div>
                <label for="plant">Plant</label>
                <input type="text" id="plant" name="plant" placeholder="Enter Plant Name" required>
            </div>

            <div>
                <label for="date">Date</label>
                <input type="date" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div>
                <label for="shift">Shift</label>
                <select id="shift" name="shift" required>
                    <option value="" disabled selected>Select Shift</option>
                    <option value="1st Shift">1st Shift</option>
                    <option value="2nd Shift">2nd Shift</option>
                    <option value="3rd Shift">3rd Shift</option>
                </select>
            </div>

            <div>
                <label for="shift_hours">Shift Hours</label>
                <input type="number" id="shift_hours" name="shift_hours" placeholder="Enter Shift Hours" min="0"
                    required>
            </div>
        </fieldset>

        <!-- Product Details Section -->
        <fieldset>
            <legend>Product Details</legend>
            <div>
                <label for="product_name">Product Name</label>
                <input type="text" id="product_name" name="product_name" placeholder="Enter Product Name" required>
            </div>

            <div>
                <label for="color">Color</label>
                <input type="text" id="color" name="color" placeholder="Enter Color" required>
            </div>

            <div>
                <label for="part_number">Part Number</label>
                <input type="text" id="part_number" name="part_number" placeholder="Enter Part Number" required>
            </div>

            <div>
                <label for="process">Process</label>
                <input type="text" id="process" name="process" placeholder="Enter Process" required>
            </div>
        </fieldset>

        <!-- ID Numbers Section -->
        <fieldset>
            <legend>ID Numbers</legend>
            <div>
                <label for="id_number_1">ID Number 1</label>
                <input type="text" id="id_number_1" name="id_number_1" placeholder="Enter ID Number 1" required>
            </div>

            <div>
                <label for="id_number_2">ID Number 2</label>
                <input type="text" id="id_number_2" name="id_number_2" placeholder="Enter ID Number 2" required>
            </div>

            <div>
                <label for="id_number_3">ID Number 3</label>
                <input type="text" id="id_number_3" name="id_number_3" placeholder="Enter ID Number 3" required>
            </div>
        </fieldset>

        <!-- Manpower Allocation Section -->
        <fieldset>
            <legend>Manpower Allocation</legend>
            <div>
                <label for="fjo">FJO #</label>
                <input type="text" id="fjo" name="fjo" placeholder="Enter FJO #" required>
            </div>

            <div>
                <label for="manpower_allocation">Manpower Allocation</label>
                <input type="text" id="manpower_allocation" name="manpower_allocation"
                    placeholder="Enter Manpower Allocation" required>
            </div>

            <div>
                <label for="reg">REG</label>
                <input type="text" id="reg" name="reg" placeholder="Enter Regular Hours" required>
            </div>

            <div>
                <label for="ot">OT</label>
                <input type="text" id="ot" name="ot" placeholder="Enter Overtime Hours" required>
            </div>
        </fieldset>

        <!-- Assembly Line / Table Section -->
        <fieldset>
            <legend>Assembly Line / Table</legend>
            <div>
                <label for="assembly_line">Assembly Line # / Table #</label>
                <input type="text" id="assembly_line" name="assembly_line" placeholder="Enter Assembly Line or Table #"
                    required>
            </div>
        </fieldset>

        <!-- Production Details Table -->
        <fieldset>
            <legend>Production Details</legend>
            <table>
                <thead>
                    <tr>
                        <th>PART NAME</th>
                        <th>DEFECT</th>
                        <th colspan="12">TIME</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows for data entry (Adjust number of rows as needed) -->
                    <?php for ($i = 0; $i < 10; $i++): ?>
                        <tr>
                            <td><input type="text" name="part_name_<?php echo $i; ?>" placeholder="Enter Part Name"></td>
                            <td><input type="text" name="defect_<?php echo $i; ?>" placeholder="Enter Defect"></td>
                            <?php for ($j = 0; $j < 12; $j++): ?>
                                <td><input type="number" name="time_<?php echo $i . '_' . $j; ?>" min="0" placeholder="0"></td>
                            <?php endfor; ?>
                            <td><input type="number" name="total_<?php echo $i; ?>" min="0" placeholder="0" readonly></td>
                        </tr>
                    <?php endfor; ?>
                    <!-- Total Rows -->
                    <tr>
                        <td colspan="13">TOTAL REJECT</td>
                        <td><input type="number" name="total_reject" placeholder="0" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="13">TOTAL GOOD</td>
                        <td><input type="number" name="total_good" placeholder="0" readonly></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <!-- Downtime Data Table Section -->
        <fieldset>
            <legend>Downtime Data</legend>
            <table>
                <thead>
                    <tr>
                        <th>Downtime Reason</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Downtime (minutes)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows for downtime data entry (Adjust number of rows as needed) -->
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <tr>
                            <td><input type="text" name="downtime_reason_<?php echo $i; ?>"
                                    placeholder="Enter Downtime Reason"></td>
                            <td><input type="time" name="start_time_<?php echo $i; ?>" required></td>
                            <td><input type="time" name="end_time_<?php echo $i; ?>" required></td>
                            <td><input type="number" name="total_downtime_<?php echo $i; ?>" min="0" placeholder="0"
                                    readonly></td>
                        </tr>
                    <?php endfor; ?>
                    <!-- Total Downtime -->
                    <tr>
                        <td colspan="3">Total Downtime</td>
                        <td><input type="number" name="total_downtime_all" placeholder="0" readonly></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <!-- Remarks Section -->
        <fieldset>
            <legend>Remarks</legend>
            <div>
                <label for="remarks">Remarks</label>
                <textarea id="remarks" name="remarks" rows="4" cols="50"
                    placeholder="Enter any additional remarks or notes here..."></textarea>
            </div>
        </fieldset>

        <!-- Submit Button Section -->
        <fieldset>
            <div>
                <button type="submit">Submit Report</button>
            </div>
        </fieldset>

        <!-- Additional Links -->
        <fieldset>
            <legend>Navigation</legend>
            <div>
                <a href="view_reports.php">View Reports</a> |
                <a href="../admin_dashboard.php">Admin Dashboard</a>
            </div>
            <div>
                <a href="../logout.php">Log Out</a>
            </div>
        </fieldset>
    </form>
</body>

</html>