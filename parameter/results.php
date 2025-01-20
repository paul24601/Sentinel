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

// Fetch data from all tables
$productMachineInfo = fetchData($conn, 'productmachineinfo');
$productDetails = fetchData($conn, 'productdetails');
$materialComposition = fetchData($conn, 'materialcomposition');
$colorantDetails = fetchData($conn, 'colorantdetails');
$moldOperationSpecs = fetchData($conn, 'moldoperationspecs');
$timerParameters = fetchData($conn, 'timerparameters');
$barrelHeaterTemperatures = fetchData($conn, 'barrelheatertemperatures');
$moldHeaterTemperatures = fetchData($conn, 'moldheatertemperatures');
$plasticizingParameters = fetchData($conn, 'plasticizingparameters');
$injectionParameters = fetchData($conn, 'injectionparameters');
$ejectionParameters = fetchData($conn, 'ejectionparameters');
$corePullSettings = fetchData($conn, 'corepullsettings');
$additionalInformation = fetchData($conn, 'additionalinformation');
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
                                <td><?= $row['id'] ?></td>
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
                                <td><?= $row['id'] ?></td>
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
                            <th>Material 1 Type</th>
                            <th>Material 1 Brand</th>
                            <th>Material 1 Mixture</th>
                            <th>Material 2 Type</th>
                            <th>Material 2 Brand</th>
                            <th>Material 2 Mixture</th>
                            <th>Material 3 Type</th>
                            <th>Material 3 Brand</th>
                            <th>Material 3 Mixture</th>
                            <th>Material 4 Type</th>
                            <th>Material 4 Brand</th>
                            <th>Material 4 Mixture</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $materialComposition->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['DryingTime'] ?></td>
                                <td><?= $row['DryingTemperature'] ?></td>
                                <td><?= $row['Material1_Type'] ?></td>
                                <td><?= $row['Material1_Brand'] ?></td>
                                <td><?= $row['Material1_MixturePercentage'] ?></td>
                                <td><?= $row['Material2_Type'] ?></td>
                                <td><?= $row['Material2_Brand'] ?></td>
                                <td><?= $row['Material2_MixturePercentage'] ?></td>
                                <td><?= $row['Material3_Type'] ?></td>
                                <td><?= $row['Material3_Brand'] ?></td>
                                <td><?= $row['Material3_MixturePercentage'] ?></td>
                                <td><?= $row['Material4_Type'] ?></td>
                                <td><?= $row['Material4_Brand'] ?></td>
                                <td><?= $row['Material4_MixturePercentage'] ?></td>
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
                                <td><?= $row['id'] ?></td>
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

        <!-- Mold Operation Specs -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Mold Operation Specs</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Mold Code</th>
                            <th>Clamping Force</th>
                            <th>Operation Type</th>
                            <th>Cooling Media</th>
                            <th>Heating Media</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $moldOperationSpecs->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['MoldCode'] ?></td>
                                <td><?= $row['ClampingForce'] ?></td>
                                <td><?= $row['OperationType'] ?></td>
                                <td><?= $row['CoolingMedia'] ?></td>
                                <td><?= $row['HeatingMedia'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Timer Parameters -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Timer Parameters</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Filling Time</th>
                            <th>Holding Time</th>
                            <th>Mold Open/Close Time</th>
                            <th>Charging Time</th>
                            <th>Cooling Time</th>
                            <th>Cycle Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $timerParameters->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['FillingTime'] ?></td>
                                <td><?= $row['HoldingTime'] ?></td>
                                <td><?= $row['MoldOpenCloseTime'] ?></td>
                                <td><?= $row['ChargingTime'] ?></td>
                                <td><?= $row['CoolingTime'] ?></td>
                                <td><?= $row['CycleTime'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Barrel Heater Temperatures -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">Barrel Heater Temperatures</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Zone 0</th>
                            <th>Zone 1</th>
                            <th>Zone 2</th>
                            <th>Zone 3</th>
                            <th>Zone 4</th>
                            <th>Zone 5</th>
                            <th>Zone 6</th>
                            <th>Zone 7</th>
                            <th>Zone 8</th>
                            <th>Zone 9</th>
                            <th>Zone 10</th>
                            <th>Zone 11</th>
                            <th>Zone 12</th>
                            <th>Zone 13</th>
                            <th>Zone 14</th>
                            <th>Zone 15</th>
                            <th>Zone 16</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $barrelHeaterTemperatures->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <?php for ($i = 0; $i <= 16; $i++) : ?>
                                    <td><?= $row["Zone$i"] ?></td>
                                <?php endfor; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mold Heater Temperatures -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Mold Heater Temperatures</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Zone 0</th>
                            <th>Zone 1</th>
                            <th>Zone 2</th>
                            <th>Zone 3</th>
                            <th>Zone 4</th>
                            <th>Zone 5</th>
                            <th>Zone 6</th>
                            <th>Zone 7</th>
                            <th>Zone 8</th>
                            <th>Zone 9</th>
                            <th>Zone 10</th>
                            <th>Zone 11</th>
                            <th>Zone 12</th>
                            <th>Zone 13</th>
                            <th>Zone 14</th>
                            <th>Zone 15</th>
                            <th>Zone 16</th>
                            <th>MTC Setting</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $moldHeaterTemperatures->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <?php for ($i = 0; $i <= 16; $i++) : ?>
                                    <td><?= $row["Zone$i"] ?></td>
                                <?php endfor; ?>
                                <td><?= $row['MTCSetting'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Plasticizing Parameters -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Plasticizing Parameters</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Screw RPM 1</th>
                            <th>Screw RPM 2</th>
                            <th>Screw RPM 3</th>
                            <th>Screw Speed 1</th>
                            <th>Screw Speed 2</th>
                            <th>Screw Speed 3</th>
                            <th>Plast Pressure 1</th>
                            <th>Plast Pressure 2</th>
                            <th>Plast Pressure 3</th>
                            <th>Plast Position 1</th>
                            <th>Plast Position 2</th>
                            <th>Plast Position 3</th>
                            <th>Back Pressure 1</th>
                            <th>Back Pressure 2</th>
                            <th>Back Pressure 3</th>
                            <th>Back Pressure Start Position</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $plasticizingParameters->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['ScrewRPM1'] ?></td>
                                <td><?= $row['ScrewRPM2'] ?></td>
                                <td><?= $row['ScrewRPM3'] ?></td>
                                <td><?= $row['ScrewSpeed1'] ?></td>
                                <td><?= $row['ScrewSpeed2'] ?></td>
                                <td><?= $row['ScrewSpeed3'] ?></td>
                                <td><?= $row['PlastPressure1'] ?></td>
                                <td><?= $row['PlastPressure2'] ?></td>
                                <td><?= $row['PlastPressure3'] ?></td>
                                <td><?= $row['PlastPosition1'] ?></td>
                                <td><?= $row['PlastPosition2'] ?></td>
                                <td><?= $row['PlastPosition3'] ?></td>
                                <td><?= $row['BackPressure1'] ?></td>
                                <td><?= $row['BackPressure2'] ?></td>
                                <td><?= $row['BackPressure3'] ?></td>
                                <td><?= $row['BackPressureStartPosition'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Injection Parameters -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">Injection Parameters</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Recovery Position</th>
                            <th>Second Stage Position</th>
                            <th>Cushion</th>
                            <th>Screw Position 1</th>
                            <th>Screw Position 2</th>
                            <th>Screw Position 3</th>
                            <th>Injection Speed 1</th>
                            <th>Injection Speed 2</th>
                            <th>Injection Speed 3</th>
                            <th>Injection Pressure 1</th>
                            <th>Injection Pressure 2</th>
                            <th>Injection Pressure 3</th>
                            <th>Suck Back Position</th>
                            <th>Suck Back Speed</th>
                            <th>Suck Back Pressure</th>
                            <th>Sprue Break</th>
                            <th>Sprue Break Time</th>
                            <th>Injection Delay</th>
                            <th>Holding Pressure 1</th>
                            <th>Holding Pressure 2</th>
                            <th>Holding Pressure 3</th>
                            <th>Holding Speed 1</th>
                            <th>Holding Speed 2</th>
                            <th>Holding Speed 3</th>
                            <th>Holding Time 1</th>
                            <th>Holding Time 2</th>
                            <th>Holding Time 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $injectionParameters->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['RecoveryPosition'] ?></td>
                                <td><?= $row['SecondStagePosition'] ?></td>
                                <td><?= $row['Cushion'] ?></td>
                                <td><?= $row['ScrewPosition1'] ?></td>
                                <td><?= $row['ScrewPosition2'] ?></td>
                                <td><?= $row['ScrewPosition3'] ?></td>
                                <td><?= $row['InjectionSpeed1'] ?></td>
                                <td><?= $row['InjectionSpeed2'] ?></td>
                                <td><?= $row['InjectionSpeed3'] ?></td>
                                <td><?= $row['InjectionPressure1'] ?></td>
                                <td><?= $row['InjectionPressure2'] ?></td>
                                <td><?= $row['InjectionPressure3'] ?></td>
                                <td><?= $row['SuckBackPosition'] ?></td>
                                <td><?= $row['SuckBackSpeed'] ?></td>
                                <td><?= $row['SuckBackPressure'] ?></td>
                                <td><?= $row['SprueBreak'] ?></td>
                                <td><?= $row['SprueBreakTime'] ?></td>
                                <td><?= $row['InjectionDelay'] ?></td>
                                <td><?= $row['HoldingPressure1'] ?></td>
                                <td><?= $row['HoldingPressure2'] ?></td>
                                <td><?= $row['HoldingPressure3'] ?></td>
                                <td><?= $row['HoldingSpeed1'] ?></td>
                                <td><?= $row['HoldingSpeed2'] ?></td>
                                <td><?= $row['HoldingSpeed3'] ?></td>
                                <td><?= $row['HoldingTime1'] ?></td>
                                <td><?= $row['HoldingTime2'] ?></td>
                                <td><?= $row['HoldingTime3'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Additional Information -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Additional Information</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $additionalInformation->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['Info'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ejection Parameters -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">Ejection Parameters</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Air Blow Time A</th>
                            <th>Air Blow Position A</th>
                            <th>Air Blow A Delay</th>
                            <th>Air Blow Time B</th>
                            <th>Air Blow Position B</th>
                            <th>Air Blow B Delay</th>
                            <th>Ejector Forward Position 1</th>
                            <th>Ejector Forward Position 2</th>
                            <th>Ejector Forward Speed 1</th>
                            <th>Ejector Retract Position 1</th>
                            <th>Ejector Retract Position 2</th>
                            <th>Ejector Retract Speed 1</th>
                            <th>Ejector Forward Speed 2</th>
                            <th>Ejector Forward Pressure 1</th>
                            <th>Ejector Retract Speed 2</th>
                            <th>Ejector Retract Pressure 1</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $ejectionParameters->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['AirBlowTimeA'] ?></td>
                                <td><?= $row['AirBlowPositionA'] ?></td>
                                <td><?= $row['AirBlowADelay'] ?></td>
                                <td><?= $row['AirBlowTimeB'] ?></td>
                                <td><?= $row['AirBlowPositionB'] ?></td>
                                <td><?= $row['AirBlowBDelay'] ?></td>
                                <td><?= $row['EjectorForwardPosition1'] ?></td>
                                <td><?= $row['EjectorForwardPosition2'] ?></td>
                                <td><?= $row['EjectorForwardSpeed1'] ?></td>
                                <td><?= $row['EjectorRetractPosition1'] ?></td>
                                <td><?= $row['EjectorRetractPosition2'] ?></td>
                                <td><?= $row['EjectorRetractSpeed1'] ?></td>
                                <td><?= $row['EjectorForwardSpeed2'] ?></td>
                                <td><?= $row['EjectorForwardPressure1'] ?></td>
                                <td><?= $row['EjectorRetractSpeed2'] ?></td>
                                <td><?= $row['EjectorRetractPressure1'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Core Pull Settings -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">Core Pull Settings</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Section</th>
                            <th>Sequence</th>
                            <th>Pressure</th>
                            <th>Speed</th>
                            <th>Position</th>
                            <th>Time</th>
                            <th>Limit Switch</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $corePullSettings->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['Section'] ?></td>
                                <td><?= $row['Sequence'] ?></td>
                                <td><?= $row['Pressure'] ?></td>
                                <td><?= $row['Speed'] ?></td>
                                <td><?= $row['Position'] ?></td>
                                <td><?= $row['Time'] ?></td>
                                <td><?= $row['LimitSwitch'] ?></td>
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
