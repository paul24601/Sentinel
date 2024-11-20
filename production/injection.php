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
            text-align: left;
        }

        label {
            display: inline-block;
            width: 150px;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: calc(100% - 160px);
            margin: 5px 0;
            padding: 5px;
        }

        textarea {
            width: calc(100% - 160px);
            margin: 5px 0;
        }

        .form-section {
            margin-bottom: 20px;
        }

        .form-section legend {
            font-weight: bold;
        }

        fieldset {
            margin: 10px 0;
            padding: 15px;
        }

        button {
            padding: 10px 20px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h2>Production Report Form</h2>
    <!-- Form starts here -->
    <form action="process_form.php" method="POST">

        <!-- General Information Section -->
        <fieldset>
            <legend>General Information</legend>
            <div>
                <label for="plant">Plant:</label>
                <input type="text" id="plant" name="plant" placeholder="Enter Plant Name" required>
            </div>
            <div>
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div>
                <label for="shift">Shift:</label>
                <select id="shift" name="shift" required>
                    <option value="" disabled selected>Select Shift</option>
                    <option value="1st">1st Shift</option>
                    <option value="2nd">2nd Shift</option>
                    <option value="3rd">3rd Shift</option>
                </select>
            </div>
            <div>
                <label for="shift_hours">Shift Hours:</label>
                <input type="number" id="shift_hours" name="shift_hours" placeholder="Enter Shift Hours" min="0" required>
            </div>
        </fieldset>

        <!-- Product Details Section -->
        <fieldset>
            <legend>Product Details</legend>
            <div>
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" placeholder="Enter Product Name" required>
            </div>
            <div>
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" placeholder="Enter Color" required>
            </div>
            <div>
                <label for="part_number">Part Number:</label>
                <input type="text" id="part_number" name="part_number" placeholder="Enter Part Number" required>
            </div>
            <div>
                <label for="process">Process:</label>
                <input type="text" id="process" name="process" placeholder="Enter Process" required>
            </div>
        </fieldset>

        <!-- Submit Button Section -->
        <div class="form-section">
            <button type="submit">Submit Report</button>
        </div>
    </form>
</body>

</html>
