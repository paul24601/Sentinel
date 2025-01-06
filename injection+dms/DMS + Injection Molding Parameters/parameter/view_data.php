<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "sentinel";
$dbname = "injection_molding";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve all data from the table
$sql = "SELECT * FROM molding_parameters";  // Replace with the exact table name
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Injection Molding Parameters Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h1 class="text-center mb-4">Injection Molding Parameters Data</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Machine</th>
                        <th>Production Run Number</th>
                        <th>Category</th>
                        <th>IRN</th>
                        <th>Product Name</th>
                        <th>Color</th>
                        <th>Mold Name</th>
                        <th>Product Number</th>
                        <th>Cavity</th>
                        <th>Gross Weight</th>
                        <th>Net Weight</th>
                        <th>Drying Time</th>
                        <th>Drying Temp</th>
                        <th>Material Type 1</th>
                        <th>Material Brand 1</th>
                        <th>Material Mix 1</th>
                        <th>Material Type 2</th>
                        <th>Material Brand 2</th>
                        <th>Material Mix 2</th>
                        <th>Material Type 3</th>
                        <th>Material Brand 3</th>
                        <th>Material Mix 3</th>
                        <th>Material Type 4</th>
                        <th>Material Brand 4</th>
                        <th>Material Mix 4</th>
                        <th>Colorant</th>
                        <th>Colorant Color</th>
                        <th>Colorant Dosage</th>
                        <th>Colorant Stabilizer</th>
                        <th>Colorant Stabilizer Dosage</th>
                        <th>Mold Code</th>
                        <th>Clamping Force</th>
                        <th>Operation Type</th>
                        <th>Cooling Media</th>
                        <th>Heating Media</th>
                        <th>Filling Time</th>
                        <th>Holding Time</th>
                        <th>Mold Open/Close Time</th>
                        <th>Charging Time</th>
                        <th>Cooling Time</th>
                        <th>Cycle Time</th>
                        <th>Additional Info</th>
                        <th>Operator Name</th>
                        <th>Inspector Name</th>
                        <th>Image</th>
                        <th>Video</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['time']) ?></td>
                            <td><?= htmlspecialchars($row['machine']) ?></td>
                            <td><?= htmlspecialchars($row['productionRunNumber']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['IRN']) ?></td>
                            <td><?= htmlspecialchars($row['productName']) ?></td>
                            <td><?= htmlspecialchars($row['color']) ?></td>
                            <td><?= htmlspecialchars($row['moldName']) ?></td>
                            <td><?= htmlspecialchars($row['productNumber']) ?></td>
                            <td><?= htmlspecialchars($row['cavity']) ?></td>
                            <td><?= htmlspecialchars($row['grossWeight']) ?></td>
                            <td><?= htmlspecialchars($row['netWeight']) ?></td>
                            <td><?= htmlspecialchars($row['dryingTime']) ?></td>
                            <td><?= htmlspecialchars($row['dryingTemp']) ?></td>
                            <td><?= htmlspecialchars($row['materialType1']) ?></td>
                            <td><?= htmlspecialchars($row['materialBrand1']) ?></td>
                            <td><?= htmlspecialchars($row['materialMix1']) ?></td>
                            <td><?= htmlspecialchars($row['materialType2']) ?></td>
                            <td><?= htmlspecialchars($row['materialBrand2']) ?></td>
                            <td><?= htmlspecialchars($row['materialMix2']) ?></td>
                            <td><?= htmlspecialchars($row['materialType3']) ?></td>
                            <td><?= htmlspecialchars($row['materialBrand3']) ?></td>
                            <td><?= htmlspecialchars($row['materialMix3']) ?></td>
                            <td><?= htmlspecialchars($row['materialType4']) ?></td>
                            <td><?= htmlspecialchars($row['materialBrand4']) ?></td>
                            <td><?= htmlspecialchars($row['materialMix4']) ?></td>
                            <td><?= htmlspecialchars($row['colorant']) ?></td>
                            <td><?= htmlspecialchars($row['colorantColor']) ?></td>
                            <td><?= htmlspecialchars($row['colorantDosage']) ?></td>
                            <td><?= htmlspecialchars($row['colorantStabilizer']) ?></td>
                            <td><?= htmlspecialchars($row['colorantStabilizerDosage']) ?></td>
                            <td><?= htmlspecialchars($row['moldCode']) ?></td>
                            <td><?= htmlspecialchars($row['clampingForce']) ?></td>
                            <td><?= htmlspecialchars($row['operationType']) ?></td>
                            <td><?= htmlspecialchars($row['coolingMedia']) ?></td>
                            <td><?= htmlspecialchars($row['heatingMedia']) ?></td>
                            <td><?= htmlspecialchars($row['fillingTime']) ?></td>
                            <td><?= htmlspecialchars($row['holdingTime']) ?></td>
                            <td><?= htmlspecialchars($row['moldOpenCloseTime']) ?></td>
                            <td><?= htmlspecialchars($row['chargingTime']) ?></td>
                            <td><?= htmlspecialchars($row['coolingTime']) ?></td>
                            <td><?= htmlspecialchars($row['cycleTime']) ?></td>
                            <td><?= htmlspecialchars($row['additionalInfo']) ?></td>
                            <td><?= htmlspecialchars($row['operatorName']) ?></td>
                            <td><?= htmlspecialchars($row['inspectorName']) ?></td>
                            <td><a href="<?= htmlspecialchars($row['imagePath']) ?>" target="_blank">View Image</a></td>
                            <td><a href="<?= htmlspecialchars($row['videoPath']) ?>" target="_blank">View Video</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center">No data found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
