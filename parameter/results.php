<?php
$servername = "localhost";
$username = "root";
$password = "Admin123@plvil";
$dbname = "injectionmoldingparameters";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchData($conn, $tableName) {
    $sql = "SELECT * FROM $tableName";
    return $conn->query($sql);
}

$productMachineInfo = fetchData($conn, 'productmachineinfo');
$productDetails = fetchData($conn, 'productdetails');
$materialComposition = fetchData($conn, 'materialcomposition');
$colorantDetails = fetchData($conn, 'colorantdetails');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4">Submission Results</h1>

        <!-- Product Machine Info -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Product Machine Info</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Machine Name</th>
                            <th>Run Number</th>
                            <th>Category</th>
                            <th>IRN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $productMachineInfo->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['ID'] ?></td>
                                <td><?= $row['Date'] ?></td>
                                <td><?= $row['Time'] ?></td>
                                <td><?= $row['MachineName'] ?></td>
                                <td><?= $row['RunNumber'] ?></td>
                                <td><?= $row['Category'] ?></td>
                                <td><?= $row['IRN'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Product Details -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Product Details</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Color</th>
                            <th>Mold Name</th>
                            <th>Product Number</th>
                            <th>Cavity Active</th>
                            <th>Gross Weight</th>
                            <th>Net Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $productDetails->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['ID'] ?></td>
                                <td><?= $row['ProductName'] ?></td>
                                <td><?= $row['Color'] ?></td>
                                <td><?= $row['MoldName'] ?></td>
                                <td><?= $row['ProductNumber'] ?></td>
                                <td><?= $row['CavityActive'] ?></td>
                                <td><?= $row['GrossWeight'] ?></td>
                                <td><?= $row['NetWeight'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Material Composition -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">Material Composition</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Drying Time</th>
                            <th>Drying Temperature</th>
                            <th>Type</th>
                            <th>Brand</th>
                            <th>Mixture Percentage</th>
                            <th>Material Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $materialComposition->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['ID'] ?></td>
                                <td><?= $row['DryingTime'] ?></td>
                                <td><?= $row['DryingTemperature'] ?></td>
                                <td><?= $row['Type'] ?></td>
                                <td><?= $row['Brand'] ?></td>
                                <td><?= $row['MixturePercentage'] ?></td>
                                <td><?= $row['MaterialOrder'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Colorant Details -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">Colorant Details</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Colorant</th>
                            <th>Color</th>
                            <th>Dosage</th>
                            <th>Stabilizer</th>
                            <th>Stabilizer Dosage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $colorantDetails->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['ID'] ?></td>
                                <td><?= $row['Colorant'] ?></td>
                                <td><?= $row['Color'] ?></td>
                                <td><?= $row['Dosage'] ?></td>
                                <td><?= $row['Stabilizer'] ?></td>
                                <td><?= $row['StabilizerDosage'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conn->close(); ?>
