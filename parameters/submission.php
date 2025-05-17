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
$password = "injectionadmin123";
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

// Fetch master records first
$sql = "SELECT * FROM parameter_records ORDER BY submission_date DESC";
$parameterRecords = $conn->query($sql);

// Fetch master records count
$countSql = "SELECT COUNT(*) as total FROM parameter_records";
$countResult = $conn->query($countSql);
$recordsCount = $countResult->fetch_assoc()['total'];

// Fetch detailed data for specific record if requested
$selectedRecordId = isset($_GET['record_id']) ? $_GET['record_id'] : null;
$recordDetails = null;

if ($selectedRecordId) {
    // Fetch product machine info for this record
    $sql = "SELECT * FROM productmachineinfo WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $productMachineInfo = $stmt->get_result();
    
    // Fetch product details for this record
    $sql = "SELECT * FROM productdetails WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $productDetails = $stmt->get_result();
    
    // Fetch material composition for this record
    $sql = "SELECT * FROM materialcomposition WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $materialComposition = $stmt->get_result();
    
    // Fetch colorant details for this record
    $sql = "SELECT * FROM colorantdetails WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $colorantDetails = $stmt->get_result();
    
    // Fetch mold operation specs for this record
    $sql = "SELECT * FROM moldoperationspecs WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $moldOperationSpecs = $stmt->get_result();
    
    // Fetch timer parameters for this record
    $sql = "SELECT * FROM timerparameters WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $timerParameters = $stmt->get_result();
    
    // Fetch barrel heater temperatures for this record
    $sql = "SELECT * FROM barrelheatertemperatures WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $barrelHeaterTemperatures = $stmt->get_result();
    
    // Fetch mold heater temperatures for this record
    $sql = "SELECT * FROM moldheatertemperatures WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $moldHeaterTemperatures = $stmt->get_result();
    
    // Fetch plasticizing parameters for this record
    $sql = "SELECT * FROM plasticizingparameters WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $plasticizingParameters = $stmt->get_result();
    
    // Fetch injection parameters for this record
    $sql = "SELECT * FROM injectionparameters WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $injectionParameters = $stmt->get_result();
    
    // Fetch ejection parameters for this record
    $sql = "SELECT * FROM ejectionparameters WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $ejectionParameters = $stmt->get_result();
    
    // Fetch core pull settings for this record
    $sql = "SELECT * FROM corepullsettings WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $corePullSettings = $stmt->get_result();
    
    // Fetch additional information for this record
    $sql = "SELECT * FROM additionalinformation WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $additionalInformation = $stmt->get_result();
    
    // Fetch personnel for this record
    $sql = "SELECT * FROM personnel WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $personnel = $stmt->get_result();
    
    // Fetch attachments for this record
    $sql = "SELECT * FROM attachments WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $attachments = $stmt->get_result();
} else {
    // For the main records view, we don't need to fetch individual tables
    $productMachineInfo = [];
    $productDetails = [];
    $materialComposition = [];
    $colorantDetails = [];
    $moldOperationSpecs = [];
    $timerParameters = [];
    $barrelHeaterTemperatures = [];
    $moldHeaterTemperatures = [];
    $plasticizingParameters = [];
    $injectionParameters = [];
    $ejectionParameters = [];
    $corePullSettings = [];
    $additionalInformation = [];
    $personnel = [];
    $attachments = [];
}
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
    <!-- DataTables core CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <!-- DataTables Responsive extension CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
    
    <!-- Custom styles -->
    <link rel="stylesheet" href="styles.css" />

    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Sentinel Digitization</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
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
                        <?php if ($_SESSION['role'] === 'Quality Control Inspection'): ?>
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="../index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>

                            <div class="sb-sidenav-menu-heading">Systems</div>
                            <!-- DMS with only Records and Approvals -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDMS"
                                aria-expanded="false" aria-controls="collapseDMS">
                                <div class="sb-nav-link-icon"><i class="fas fa-people-roof"></i></div>
                                DMS
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseDMS" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../dms/submission.php">Records</a>
                                </nav>
                            </div>

                            <!-- Parameters with only Records -->
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
                                    <a class="nav-link active" href="submission.php">Records</a>
                                </nav>
                            </div>
                        <?php else: ?>
                            <!-- Full sidebar for all other roles -->
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
                                    <a class="nav-link active" href="submission.php">Data Visualization</a>
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
                        <?php endif; ?>
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
                    <h1 class="">Parameters Data Visualization</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department Records</li>
                    </ol>
                    
                    <?php if ($selectedRecordId): ?>
                        <!-- Show back button when viewing detailed record -->
                        <div class="mb-4">
                            <a href="submission.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Records</a>
                        </div>
                        
                        <!-- Detailed Record View -->
                    <div class="container-fluid my-5">
                            <!-- Display details for selected record -->
                            <?php 
                            // Fetch the selected record details
                            $sql = "SELECT * FROM parameter_records WHERE record_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $selectedRecordId);
                            $stmt->execute();
                            $recordData = $stmt->get_result()->fetch_assoc();
                            ?>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <div class="d-flex justify-content-between">
                                        <h5><?= htmlspecialchars($recordData['title']) ?></h5>
                                        <span>Record ID: <?= htmlspecialchars($selectedRecordId) ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <p><strong>Submitted by:</strong> <?= htmlspecialchars($recordData['submitted_by']) ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Submission Date:</strong> <?= htmlspecialchars($recordData['submission_date']) ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Status:</strong> <span class="badge bg-success"><?= htmlspecialchars(ucfirst($recordData['status'])) ?></span></p>
                                        </div>
                                    </div>
                                    <?php if ($recordData['description']): ?>
                                        <div class="mb-3">
                                            <p><strong>Description:</strong></p>
                                            <p><?= nl2br(htmlspecialchars($recordData['description'])) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                        <!-- Product Machine Info -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">Product Machine Info</div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
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
                            <div class="card-header bg-info text-white">Material Composition</div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Drying Time</th>
                                            <th>Drying Temperature</th>
                                            <th>Material 1</th>
                                            <th>Mix %</th>
                                            <th>Material 2</th>
                                            <th>Mix %</th>
                                            <th>Material 3</th>
                                            <th>Mix %</th>
                                            <th>Material 4</th>
                                            <th>Mix %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $materialComposition->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['DryingTime'] ?></td>
                                                <td><?= $row['DryingTemperature'] ?></td>
                                                <td><?= $row['Material1_Type'] ? $row['Material1_Type'] . ' (' . $row['Material1_Brand'] . ')' : '-' ?></td>
                                                <td><?= $row['Material1_MixturePercentage'] ?: '-' ?></td>
                                                <td><?= $row['Material2_Type'] ? $row['Material2_Type'] . ' (' . $row['Material2_Brand'] . ')' : '-' ?></td>
                                                <td><?= $row['Material2_MixturePercentage'] ?: '-' ?></td>
                                                <td><?= $row['Material3_Type'] ? $row['Material3_Type'] . ' (' . $row['Material3_Brand'] . ')' : '-' ?></td>
                                                <td><?= $row['Material3_MixturePercentage'] ?: '-' ?></td>
                                                <td><?= $row['Material4_Type'] ? $row['Material4_Type'] . ' (' . $row['Material4_Brand'] . ')' : '-' ?></td>
                                                <td><?= $row['Material4_MixturePercentage'] ?: '-' ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Colorant Details -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">Colorant Details</div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
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
                                                <td><?= $row['Colorant'] ?: '-' ?></td>
                                                <td><?= $row['Color'] ?: '-' ?></td>
                                                <td><?= $row['Dosage'] ?: '-' ?></td>
                                                <td><?= $row['Stabilizer'] ?: '-' ?></td>
                                                <td><?= $row['StabilizerDosage'] ?: '-' ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                            <!-- Continue with other detailed tables... -->
                            
                        <!-- Mold Operation Specifications -->
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">Mold & Operation Specifications</div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
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
                                                <td><?= $row['MoldCode'] ?: '-' ?></td>
                                                <td><?= $row['ClampingForce'] ?: '-' ?></td>
                                                <td><?= $row['OperationType'] ?: '-' ?></td>
                                                <td><?= $row['CoolingMedia'] ?: '-' ?></td>
                                                <td><?= $row['HeatingMedia'] ?: '-' ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Timer Parameters -->
                        <div class="card mb-4">
                            <div class="card-header bg-danger text-white">Timer Parameters</div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
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
                                                <td><?= $row['FillingTime'] ?> s</td>
                                                <td><?= $row['HoldingTime'] ?> s</td>
                                                <td><?= $row['MoldOpenCloseTime'] ?> s</td>
                                                <td><?= $row['ChargingTime'] ?> s</td>
                                                <td><?= $row['CoolingTime'] ?> s</td>
                                                <td><?= $row['CycleTime'] ?> s</td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Temperature Settings -->
                        <div class="card mb-4">
                            <div class="card-header bg-danger text-white">Temperature Settings</div>
                            <div class="card-body">
                                <h5 class="card-title">Barrel Heater Temperatures</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <?php 
                                                $barrelRow = $barrelHeaterTemperatures->fetch_assoc();
                                                if ($barrelRow) {
                                                    foreach ($barrelRow as $key => $value) {
                                                        if ($key != 'record_id' && $key != 'id') {
                                                            echo "<th>{$key}</th>";
                                                        }
                                                    }
                                                    // Reset the result set pointer
                                                    $barrelHeaterTemperatures->data_seek(0);
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $barrelHeaterTemperatures->fetch_assoc()): ?>
                                                <tr>
                                                    <?php foreach ($row as $key => $value): ?>
                                                        <?php if ($key != 'record_id' && $key != 'id'): ?>
                                                            <td><?= $value ?: '-' ?> °C</td>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <h5 class="card-title mt-4">Mold Heater Temperatures</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <?php 
                                                $moldRow = $moldHeaterTemperatures->fetch_assoc();
                                                if ($moldRow) {
                                                    foreach ($moldRow as $key => $value) {
                                                        if ($key != 'record_id' && $key != 'id') {
                                                            echo "<th>{$key}</th>";
                                                        }
                                                    }
                                                    // Reset the result set pointer
                                                    $moldHeaterTemperatures->data_seek(0);
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $moldHeaterTemperatures->fetch_assoc()): ?>
                                                <tr>
                                                    <?php foreach ($row as $key => $value): ?>
                                                        <?php if ($key != 'record_id' && $key != 'id'): ?>
                                                            <td><?= $value ?: '-' ?> <?= $key === 'MTCSetting' ? '' : '°C' ?></td>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Plasticizing Parameters -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">Plasticizing Parameters</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Position 1</th>
                                                <th>Position 2</th>
                                                <th>Position 3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $plasticRow = $plasticizingParameters->fetch_assoc();
                                            if ($plasticRow): 
                                            ?>
                                                <tr>
                                                    <th class="table-light">Screw RPM</th>
                                                    <td><?= $plasticRow['ScrewRPM1'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['ScrewRPM2'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['ScrewRPM3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Screw Speed</th>
                                                    <td><?= $plasticRow['ScrewSpeed1'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['ScrewSpeed2'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['ScrewSpeed3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Plasticizing Pressure</th>
                                                    <td><?= $plasticRow['PlastPressure1'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['PlastPressure2'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['PlastPressure3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Plasticizing Position</th>
                                                    <td><?= $plasticRow['PlastPosition1'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['PlastPosition2'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['PlastPosition3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Back Pressure</th>
                                                    <td><?= $plasticRow['BackPressure1'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['BackPressure2'] ?: '-' ?></td>
                                                    <td><?= $plasticRow['BackPressure3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Back Pressure Start Position</th>
                                                    <td colspan="3"><?= $plasticRow['BackPressureStartPosition'] ?: '-' ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Injection Parameters -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">Injection Parameters</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Recovery Position:</strong> <?= $injectionParameters->fetch_assoc()['RecoveryPosition'] ?? '-' ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Second Stage Position:</strong> <?= $injectionParameters->fetch_assoc()['SecondStagePosition'] ?? '-' ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Cushion:</strong> <?= $injectionParameters->fetch_assoc()['Cushion'] ?? '-' ?></p>
                                    </div>
                                </div>
                                
                                <?php 
                                // Reset result pointer
                                $injectionParameters->data_seek(0);
                                $injParams = $injectionParameters->fetch_assoc();
                                ?>
                                
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Position 1</th>
                                                <th>Position 2</th>
                                                <th>Position 3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($injParams): ?>
                                                <tr>
                                                    <th class="table-light">Screw Position</th>
                                                    <td><?= $injParams['ScrewPosition1'] ?: '-' ?></td>
                                                    <td><?= $injParams['ScrewPosition2'] ?: '-' ?></td>
                                                    <td><?= $injParams['ScrewPosition3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Injection Speed</th>
                                                    <td><?= $injParams['InjectionSpeed1'] ?: '-' ?></td>
                                                    <td><?= $injParams['InjectionSpeed2'] ?: '-' ?></td>
                                                    <td><?= $injParams['InjectionSpeed3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Injection Pressure</th>
                                                    <td><?= $injParams['InjectionPressure1'] ?: '-' ?></td>
                                                    <td><?= $injParams['InjectionPressure2'] ?: '-' ?></td>
                                                    <td><?= $injParams['InjectionPressure3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Holding Pressure</th>
                                                    <td><?= $injParams['HoldingPressure1'] ?: '-' ?></td>
                                                    <td><?= $injParams['HoldingPressure2'] ?: '-' ?></td>
                                                    <td><?= $injParams['HoldingPressure3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Holding Speed</th>
                                                    <td><?= $injParams['HoldingSpeed1'] ?: '-' ?></td>
                                                    <td><?= $injParams['HoldingSpeed2'] ?: '-' ?></td>
                                                    <td><?= $injParams['HoldingSpeed3'] ?: '-' ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="table-light">Holding Time</th>
                                                    <td><?= $injParams['HoldingTime1'] ?: '-' ?></td>
                                                    <td><?= $injParams['HoldingTime2'] ?: '-' ?></td>
                                                    <td><?= $injParams['HoldingTime3'] ?: '-' ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <p><strong>Suck Back Position:</strong> <?= $injParams['SuckBackPosition'] ?? '-' ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Suck Back Speed:</strong> <?= $injParams['SuckBackSpeed'] ?? '-' ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Suck Back Pressure:</strong> <?= $injParams['SuckBackPressure'] ?? '-' ?></p>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <p><strong>Sprue Break:</strong> <?= $injParams['SprueBreak'] ?? '-' ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Sprue Break Time:</strong> <?= $injParams['SprueBreakTime'] ?? '-' ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Injection Delay:</strong> <?= $injParams['InjectionDelay'] ?? '-' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ejection Parameters -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">Ejection Parameters</div>
                            <div class="card-body">
                                <h5 class="mb-3">Air Blow Settings</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Parameter</th>
                                                    <th>Air Blow A</th>
                                                    <th>Air Blow B</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $ejectionRow = $ejectionParameters->fetch_assoc();
                                                if ($ejectionRow): 
                                                ?>
                                                    <tr>
                                                        <th class="table-light">Time</th>
                                                        <td><?= $ejectionRow['AirBlowTimeA'] ?: '-' ?></td>
                                                        <td><?= $ejectionRow['AirBlowTimeB'] ?: '-' ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-light">Position</th>
                                                        <td><?= $ejectionRow['AirBlowPositionA'] ?: '-' ?></td>
                                                        <td><?= $ejectionRow['AirBlowPositionB'] ?: '-' ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="table-light">Delay</th>
                                                        <td><?= $ejectionRow['AirBlowADelay'] ?: '-' ?></td>
                                                        <td><?= $ejectionRow['AirBlowBDelay'] ?: '-' ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <h5 class="mt-4 mb-3">Ejector Settings</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Forward Position 1</th>
                                                <th>Forward Position 2</th>
                                                <th>Retract Position 1</th>
                                                <th>Retract Position 2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($ejectionRow): ?>
                                                <tr>
                                                    <th class="table-light">Position</th>
                                                    <td><?= $ejectionRow['EjectorForwardPosition1'] ?: '-' ?></td>
                                                    <td><?= $ejectionRow['EjectorForwardPosition2'] ?: '-' ?></td>
                                                    <td><?= $ejectionRow['EjectorRetractPosition1'] ?: '-' ?></td>
                                                    <td><?= $ejectionRow['EjectorRetractPosition2'] ?: '-' ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Forward Speed 1</th>
                                                <th>Forward Speed 2</th>
                                                <th>Retract Speed 1</th>
                                                <th>Retract Speed 2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($ejectionRow): ?>
                                                <tr>
                                                    <th class="table-light">Speed</th>
                                                    <td><?= $ejectionRow['EjectorForwardSpeed1'] ?: '-' ?></td>
                                                    <td><?= $ejectionRow['EjectorForwardSpeed2'] ?: '-' ?></td>
                                                    <td><?= $ejectionRow['EjectorRetractSpeed1'] ?: '-' ?></td>
                                                    <td><?= $ejectionRow['EjectorRetractSpeed2'] ?: '-' ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th>Forward Pressure</th>
                                                <th>Retract Pressure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($ejectionRow): ?>
                                                <tr>
                                                    <th class="table-light">Pressure</th>
                                                    <td><?= $ejectionRow['EjectorForwardPressure1'] ?: '-' ?></td>
                                                    <td><?= $ejectionRow['EjectorRetractPressure1'] ?: '-' ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Core Pull Settings -->
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">Core Pull Settings</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
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
                                                    <td><strong><?= $row['Section'] ?></strong></td>
                                                    <td><?= $row['Sequence'] ?: '-' ?></td>
                                                    <td><?= $row['Pressure'] ?: '-' ?></td>
                                                    <td><?= $row['Speed'] ?: '-' ?></td>
                                                    <td><?= $row['Position'] ?: '-' ?></td>
                                                    <td><?= $row['Time'] ?: '-' ?></td>
                                                    <td><?= $row['LimitSwitch'] ?: '-' ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">Additional Information</div>
                            <div class="card-body">
                                <?php 
                                $additionalInfo = $additionalInformation->fetch_assoc();
                                if ($additionalInfo && !empty($additionalInfo['Info'])): 
                                ?>
                                    <div class="p-3 border rounded">
                                        <?= nl2br(htmlspecialchars($additionalInfo['Info'])) ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No additional information provided.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Personnel -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">Personnel</div>
                            <div class="card-body">
                                <?php 
                                $personnelInfo = $personnel->fetch_assoc();
                                if ($personnelInfo): 
                                ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Adjuster Name:</strong> <?= $personnelInfo['AdjusterName'] ?: '-' ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>QAE Name:</strong> <?= $personnelInfo['QAEName'] ?: '-' ?></p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No personnel information available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Attachments -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">Attachments</div>
                            <div class="card-body">
                                <?php if ($attachments->num_rows > 0): ?>
                                    <div class="row">
                                        <?php while ($file = $attachments->fetch_assoc()): 
                                            $fileType = explode('/', $file['FileType'])[0]; // Get image, video, etc
                                            $isImage = ($fileType == 'image');
                                            $icon = $isImage ? 'fa-image' : ($fileType == 'video' ? 'fa-video' : 'fa-file');
                                            $fileName = basename($file['FileName']);
                                            $filePath = '../' . $file['FilePath']; // Adjust path as needed
                                        ?>
                                            <div class="col-md-4 col-sm-6 mb-4">
                                                <div class="card h-100">
                                                    <?php if ($isImage): ?>
                                                        <img src="<?= $filePath ?>" class="card-img-top attachment-thumbnail" alt="<?= $fileName ?>">
                                                    <?php else: ?>
                                                        <div class="card-img-top text-center py-5 bg-light">
                                                            <i class="fas <?= $icon ?> fa-4x text-secondary"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="card-body">
                                                        <h6 class="card-title text-truncate"><?= $fileName ?></h6>
                                                        <p class="card-text">
                                                            <small class="text-muted">Type: <?= $file['FileType'] ?></small>
                                                        </p>
                                                        <a href="<?= $filePath ?>" class="btn btn-sm btn-primary" target="_blank">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                        <a href="<?= $filePath ?>" class="btn btn-sm btn-secondary" download>
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No attachments available for this record.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        </div>

                    <?php else: ?>
                        <!-- Main Records View - Display card with summary of all records -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Parameters Records (<?= $recordsCount ?>)
                            </div>
                            <div class="card-body">
                                <table id="recordsTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Record ID</th>
                                            <th>Title</th>
                                            <th>Submitted By</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($record = $parameterRecords->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($record['record_id']) ?></td>
                                                <td><?= htmlspecialchars($record['title']) ?></td>
                                                <td><?= htmlspecialchars($record['submitted_by']) ?></td>
                                                <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($record['submission_date']))) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $record['status'] === 'active' ? 'success' : ($record['status'] === 'archived' ? 'warning' : 'danger') ?>">
                                                        <?= htmlspecialchars(ucfirst($record['status'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="submission.php?record_id=<?= htmlspecialchars($record['record_id']) ?>" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <button class="btn btn-info btn-sm print-record" 
                                                            data-record-id="<?= htmlspecialchars($record['record_id']) ?>">
                                                        <i class="fas fa-print"></i> Print
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-record" 
                                                            data-record-id="<?= htmlspecialchars($record['record_id']) ?>">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
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

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#recordsTable').DataTable({
                    responsive: true,
                order: [[3, 'desc']], // Sort by date column descending
                pageLength: 25
            });
            
            // Print record handler
            $('.print-record').click(function() {
                const recordId = $(this).data('record-id');
                window.open('print_record.php?record_id=' + recordId, '_blank');
            });
            
            // Delete record handler
            $('.delete-record').click(function() {
                const recordId = $(this).data('record-id');
                if (confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
                    window.location.href = 'delete_record.php?record_id=' + recordId;
                }
            });
        });
    </script>
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
    <!-- DataTables core JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Responsive extension JS -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

</body>

</html>
<?php $conn->close(); ?>