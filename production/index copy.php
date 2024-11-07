<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Report Form</title>
</head>

<body>
    <h2>Production Report Form</h2>
    <!-- Form starts here -->
    <form action="process_production_report.php" method="POST">
        
        <!-- Plant -->
        <div>
            <label for="plant">Plant</label>
            <input type="text" id="plant" name="plant" placeholder="Enter Plant Name" required>
        </div>
        
        <!-- Date -->
        <div>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <!-- Shift -->
        <div>
            <label for="shift">Shift</label>
            <select id="shift" name="shift" required>
                <option value="" disabled selected>Select Shift</option>
                <option value="1st Shift">1st Shift</option>
                <option value="2nd Shift">2nd Shift</option>
                <option value="3rd Shift">3rd Shift</option>
            </select>
        </div>

        <!-- Shift Hours -->
        <div>
            <label for="shift_hours">Shift Hours</label>
            <input type="number" id="shift_hours" name="shift_hours" placeholder="Enter Shift Hours" min="0" required>
        </div>

        <!-- Product Name -->
        <div>
            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" name="product_name" placeholder="Enter Product Name" required>
        </div>

        <!-- Color -->
        <div>
            <label for="color">Color</label>
            <input type="text" id="color" name="color" placeholder="Enter Color" required>
        </div>

        <!-- Part Number -->
        <div>
            <label for="part_number">Part Number</label>
            <input type="text" id="part_number" name="part_number" placeholder="Enter Part Number" required>
        </div>

        <!-- Process -->
        <div>
            <label for="process">Process</label>
            <input type="text" id="process" name="process" placeholder="Enter Process" required>
        </div>

        <!-- ID Number 1 -->
        <div>
            <label for="id_number_1">ID Number 1</label>
            <input type="text" id="id_number_1" name="id_number_1" placeholder="Enter ID Number 1" required>
        </div>

        <!-- ID Number 2 -->
        <div>
            <label for="id_number_2">ID Number 2</label>
            <input type="text" id="id_number_2" name="id_number_2" placeholder="Enter ID Number 2" required>
        </div>

        <!-- ID Number 3 -->
        <div>
            <label for="id_number_3">ID Number 3</label>
            <input type="text" id="id_number_3" name="id_number_3" placeholder="Enter ID Number 3" required>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit">Submit Report</button>
        </div>

        <!-- Additional Links (Optional) -->
        <div>
            <a href="view_reports.php">View Reports</a> | 
            <a href="../admin_dashboard.php">Admin Dashboard</a>
        </div>

        <!-- Logout Button -->
        <div>
            <a href="../logout.php">Log Out</a>
        </div>
    </form>
</body>

</html>
