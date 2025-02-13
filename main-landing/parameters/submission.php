<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html");
    exit();
}
?>

<?php
$servername = "localhost";
$username = "root";
$password = "Admin123@plvil";
$dbname = "injectionmoldingparameters";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchData($conn, $tableName)
{
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
$personnel = fetchData($conn, 'personnel');  // Added personnel
$attachments = fetchData($conn, 'attachments');  // Added attachments
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Parameters - Data Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI for Autocomplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {
            $('table').each(function () {
                $(this).DataTable({
                    responsive: true
                });
            });
        });
    </script>

</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel Digitization</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="../index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Systems</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                            aria-expanded="false" aria-controls="collapseDMS">
                            <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                            DMS
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseDMS" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="../dms/index.php">Data Entry</a>
                                <a class="nav-link" href="../dms/submission.php">Records</a>
                                <a class="nav-link" href="../dms/analytics.php">Analytics</a>
                            </nav>
                        </div>


                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseParameters" aria-expanded="false"
                            aria-controls="collapseParameters">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Parameters
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseParameters" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="index.php">Data Entry</a>
                                <a class="nav-link active" href="#">Records</a>
                                <a class="nav-link" href="analytics.php">Data Analytics</a>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Admin</div>
                        <a class="nav-link" href="../admin/users.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Values
                        </a>
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Analysis
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $_SESSION['full_name']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid p-4">
                    <h1 class="">Records</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
                    </ol>
                    <!--FORMS-->
                    
    <div class="container my-5">
        <!-- Product Machine Info -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Product Machine Info</div>
            <div class="card-body table-responsive">
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
                        <?php while ($row = $productMachineInfo->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $productDetails->fetch_assoc()): ?>
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
            <div class="card-body table-responsive table-responsive">
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
                        <?php while ($row = $materialComposition->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $colorantDetails->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $moldOperationSpecs->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $timerParameters->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $barrelHeaterTemperatures->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <?php for ($i = 0; $i <= 16; $i++): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $moldHeaterTemperatures->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <?php for ($i = 0; $i <= 16; $i++): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $plasticizingParameters->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $injectionParameters->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $additionalInformation->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $ejectionParameters->fetch_assoc()): ?>
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
            <div class="card-body table-responsive">
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
                        <?php while ($row = $corePullSettings->fetch_assoc()): ?>
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

        <!-- Add Personnel Section -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">Personnel</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Adjuster Name</th>
                            <th>Quality Assurance Engineer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $personnel->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['AdjusterName']) ?></td>
                                <td><?= htmlspecialchars($row['QAEName']) ?></td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Attachments -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">Attachments</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Path</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $attachments->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['FileName']) ?></td>
                                <td><?= htmlspecialchars($row['FileType']) ?></td>
                                <td><?= htmlspecialchars($row['FilePath']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2025 Sentinel OJT</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../assets/demo/chart-area-demo.js"></script>
    <script src="../assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $conn->close(); ?>