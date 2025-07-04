<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html");
    exit();
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
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

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
                                <a class="nav-link active" href="#">Data Entry</a>
                                <a class="nav-link" href="submission.php">Data Visualization</a>
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
                    <h1 class="">Data Entry</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Injection Department</li>
                    </ol>
                    
                    <!-- Notification container -->
                    <div id="notification-container">
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $_SESSION['success_message'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error_message'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>
                    </div>
                    
                    <!--FORMS-->
                    <div class="container-fluid my-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <form action="submit.php" method="POST" enctype="multipart/form-data">
                                    <!-- Section 1: Product and Machine Information -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseProductMachine" role="button" aria-expanded="false"
                                            aria-controls="collapseProductMachine">
                                            Product and Machine Information
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseProductMachine">
                                        <div class="row mb-3 row-cols-1 row-cols-sm-3">
                                            <div class="col">
                                                <label for="Date" class="form-label">Date</label>
                                                <input type="date" class="form-control" id="currentDate" name="Date"
                                                    value="<?php echo date('Y-m-d'); ?>" readonly>
                                            </div>
                                            <div class="col">
                                                <label for="Time" class="form-label">Time</label>
                                                <input type="time" class="form-control" id="currentTime" name="Time"
                                                    value="<?php echo date('H:i'); ?>" readonly>
                                            </div>
                                            <div class="col">
                                                <label for="MachineName" class="form-label">Machine</label>
                                                <select class="form-control" id="MachineName" name="MachineName" required>
                                                    <option value="" disabled selected>Select a machine</option>
                                                    <option value="ARB 50">ARB 50</option>
                                                    <option value="SUM 260C">SUM 260C</option>
                                                    <option value="SUM 350">SUM 350</option>
                                                    <option value="MIT 650D">MIT 650D</option>
                                                    <option value="TOS 650A">TOS 650A</option>
                                                    <option value="CLF 750A">CLF 750A</option>
                                                    <option value="CLF 750B">CLF 750B</option>
                                                    <option value="CLF 750C">CLF 750C</option>
                                                    <option value="TOS 850A">TOS 850A</option>
                                                    <option value="TOS 850B">TOS 850B</option>
                                                    <option value="TOS 850C">TOS 850C</option>
                                                    <option value="CLF 950A">CLF 950A</option>
                                                    <option value="CLF 950B">CLF 950B</option>
                                                    <option value="MIT 1050B">MIT 1050B</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="RunNumber" class="form-label">Run No.</label>
                                                <input type="text" class="form-control" name="RunNumber"
                                                    placeholder="Enter Run Number">
                                            </div>
                                            <div class="col">
                                                <label for="Category" class="form-label">Category</label>
                                                <select class="form-control" name="Category" required>
                                                    <option value="" disabled selected>Select Category</option>
                                                    <option value="Colorant Testing">Colorant Testing</option>
                                                    <option value="Machine Preventive Maintenance">Machine Preventive Maintenance</option>
                                                    <option value="Mass Production">Mass Production</option>
                                                    <option value="Material Testing">Material Testing</option>
                                                    <option value="Mold Preventive Maintenance">Mold Preventive Maintenance</option>
                                                    <option value="New Mold Testing">New Mold Testing</option>
                                                    <option value="Product Improvement">Product Improvement</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="IRN" class="form-label">IRN</label>
                                                <input type="text" class="form-control" name="IRN"
                                                    placeholder="Enter IRN">
                                            </div>
                                            <div class="col">
                                                <label for="startTime" class="form-label">Start Time</label>
                                                <input type="time" class="form-control" name="startTime" required>
                                            </div>
                                            <div class="col">
                                                <label for="endTime" class="form-label">End Time</label>
                                                <input type="time" class="form-control" name="endTime" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 2: Product Details -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseProductDetails" role="button" aria-expanded="false"
                                            aria-controls="collapseProductDetails">
                                            Product Details
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseProductDetails">
                                        <div class="row mb-3 row-cols-1 row-cols-md-3 row-cols-sm-2">
                                            <div class="col">
                                                <label for="product" class="form-label">Product Name</label>
                                                <input type="text" class="form-control" name="product" id="product" placeholder="Enter Product Name" required>
                                            </div>
                                            <div class="col">
                                                <label for="color" class="form-label">Color</label>
                                                <input type="text" class="form-control" name="color"
                                                    placeholder="Enter Color">
                                            </div>
                                            <div class="col">
                                                <label for="prodNo" class="form-label">Product Number</label>
                                                <input type="text" class="form-control" name="prodNo"
                                                    placeholder="Product Number">
                                            </div>
                                            <div class="col">
                                                <label for="mold-name" class="form-label">Mold Name</label>
                                                <input type="text" class="form-control" name="mold-name"
                                                    placeholder="Enter Mold Name">
                                            </div>
                                            <div class="col">
                                                <label for="cavity" class="form-label">Number of Cavity (Active)</label>
                                                <input type="number" class="form-control" name="cavity"
                                                    placeholder="Number of Cavity">
                                            </div>
                                            <div class="col">
                                                <label for="grossWeight" class="form-label">Gross Weight</label>
                                                <input type="text" class="form-control" name="grossWeight"
                                                    placeholder="Gross Weight (g)">
                                            </div>
                                            <div class="col">
                                                <label for="netWeight" class="form-label">Net Weight</label>
                                                <input type="text" class="form-control" name="netWeight"
                                                    placeholder="Net Weight (g)">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 3: Material Composition -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseMaterialComposition" role="button" aria-expanded="false"
                                            aria-controls="collapseMaterialComposition">
                                            Material Composition
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <!-- materials -->
                                    <div class="collapse show" id="collapseMaterialComposition">
                                        <!-- Drying -->
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="dryingtime" class="form-label">Drying Time</label>
                                                <input type="number" class="form-control" name="dryingtime"
                                                    placeholder="Select Drying Time">
                                            </div>
                                            <div class="col">
                                                <label for="dryingtemp" class="form-label">Drying Temperature</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="dryingtemp"
                                                        placeholder="Enter Temperature" min="0" max="300" step="0.1">
                                                    <span class="input-group-text">°C</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Material 1 -->
                                        <h6>Material 1</h6>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="type1" class="form-label">Type 1</label>
                                                <input type="text" class="form-control" name="type1" id="type1"
                                                    placeholder="Enter Type 1">
                                            </div>
                                            <div class="col">
                                                <label for="brand1" class="form-label">Brand 1</label>
                                                <input type="text" class="form-control" name="brand1" id="brand1"
                                                    placeholder="Enter Brand 1">
                                            </div>
                                            <div class="col">
                                                <label for="mix1" class="form-label">Mixture 1</label>
                                                <input type="number" class="form-control" name="mix1" id="mix1"
                                                    placeholder="% Mixture 1">
                                            </div>
                                        </div>

                                        <h6>Material 2</h6>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="type2" class="form-label">Type 2</label>
                                                <input type="text" class="form-control" name="type2" id="type2"
                                                    placeholder="Enter Type 2">
                                            </div>
                                            <div class="col">
                                                <label for="brand2" class="form-label">Brand 2</label>
                                                <input type="text" class="form-control" name="brand2" id="brand2"
                                                    placeholder="Enter Brand 2">
                                            </div>
                                            <div class="col">
                                                <label for="mix2" class="form-label">Mixture 2</label>
                                                <input type="number" class="form-control" name="mix2" id="mix2"
                                                    placeholder="% Mixture 2">
                                            </div>
                                        </div>

                                        <h6>Material 3</h6>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="type3" class="form-label">Type 3</label>
                                                <input type="text" class="form-control" name="type3" id="type3"
                                                    placeholder="Enter Type 3">
                                            </div>
                                            <div class="col">
                                                <label for="brand3" class="form-label">Brand 3</label>
                                                <input type="text" class="form-control" name="brand3" id="brand3"
                                                    placeholder="Enter Brand 3">
                                            </div>
                                            <div class="col">
                                                <label for="mix3" class="form-label">Mixture 3</label>
                                                <input type="number" class="form-control" name="mix3" id="mix3"
                                                    placeholder="% Mixture 3">
                                            </div>
                                        </div>

                                        <h6>Material 4</h6>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="type4" class="form-label">Type 4</label>
                                                <input type="text" class="form-control" name="type4" id="type4"
                                                    placeholder="Enter Type 4">
                                            </div>
                                            <div class="col">
                                                <label for="brand4" class="form-label">Brand 4</label>
                                                <input type="text" class="form-control" name="brand4" id="brand4"
                                                    placeholder="Enter Brand 4">
                                            </div>
                                            <div class="col">
                                                <label for="mix4" class="form-label">Mixture 4</label>
                                                <input type="number" class="form-control" name="mix4" id="mix4"
                                                    placeholder="% Mixture 4">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 4: Colorant Details -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseColorantDetails" role="button" aria-expanded="false"
                                            aria-controls="collapseColorantDetails">
                                            Colorant Details
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <!-- colorants -->
                                    <div class="collapse show" id="collapseColorantDetails">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="colorant" class="form-label">Colorant</label>
                                                <input type="text" class="form-control" name="colorant" id="colorant"
                                                    placeholder="Enter Colorant">
                                            </div>
                                            <div class="col">
                                                <label for="colorantColor" class="form-label">Color</label>
                                                <input type="text" class="form-control" name="colorantColor"
                                                    id="colorantColor" placeholder="Enter Colorant Color">
                                            </div>
                                            <div class="col">
                                                <label for="colorant-dosage" class="form-label">Colorant Dosage</label>
                                                <input type="text" class="form-control" name="colorant-dosage"
                                                    id="colorant-dosage" placeholder="Enter Colorant Dosage">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="colorant-stabilizer" class="form-label">Colorant
                                                    Stabilizer</label>
                                                <input type="text" class="form-control" name="colorant-stabilizer"
                                                    id="colorant-stabilizer" placeholder="Enter Colorant Stabilizer">
                                            </div>
                                            <div class="col">
                                                <label for="colorant-stabilizer-dosage" class="form-label">Colorant
                                                    Stabilizer Dosage
                                                    (grams)</label>
                                                <input type="text" class="form-control"
                                                    name="colorant-stabilizer-dosage" id="colorant-stabilizer-dosage"
                                                    placeholder="Enter Colorant Stabilizer Dosage (grams) ">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 5: Mold and Operation Specifications -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseMoldandOperationSpecs" role="button" aria-expanded="false"
                                            aria-controls="collapseMoldandOperationSpecs">
                                            Mold and Operation Specifications
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseMoldandOperationSpecs">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="mold-code" class="form-label">Mold Code</label>
                                                <input type="text" class="form-control" name="mold-code" id="mold-code"
                                                    placeholder="Enter Mold Code">
                                            </div>
                                            <div class="col">
                                                <label for="clamping-force" class="form-label">Clamping Force</label>
                                                <input type="text" class="form-control" name="clamping-force"
                                                    id="clamping-force" placeholder="Enter Clamping Force">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="operation-type" class="form-label">Operation</label>
                                                <input type="text" class="form-control" name="operation-type"
                                                    id="operation-type" placeholder="Enter Type of Operation">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="cooling-media" class="form-label">Cooling Media</label>
                                                <input type="text" class="form-control" name="cooling-media"
                                                    id="cooling-media" placeholder="Enter Cooling Media">
                                            </div>
                                            <div class="col">
                                                <label for="heating-media" class="form-label">Heating Media</label>
                                                <input type="text" class="form-control" name="heating-media"
                                                    id="heating-media" placeholder="Enter Heating Media">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 6: Timer Parameters -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseCycleTime" role="button" aria-expanded="false"
                                            aria-controls="collapseCycleTime">
                                            Timer Parameters
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseCycleTime">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="fillingTime" class="form-label">Filling Time (s)</label>
                                                <input type="number" class="form-control" name="fillingTime"
                                                    id="fillingTime" placeholder="Filling Time">
                                            </div>
                                            <div class="col">
                                                <label for="holdingTime" class="form-label">Holding Time (s)</label>
                                                <input type="number" class="form-control" name="holdingTime"
                                                    id="holdingTime" placeholder="Holding Time">
                                            </div>
                                            <div class="col">
                                                <label for="moldOpenCloseTime" class="form-label">Mold Open-Close Time
                                                    (s)</label>
                                                <input type="number" class="form-control" name="moldOpenCloseTime"
                                                    id="moldOpenCloseTime" placeholder="Mold Open-Close Time">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="chargingTime" class="form-label">Charging Time (s)</label>
                                                <input type="number" class="form-control" name="chargingTime"
                                                    id="chargingTime" placeholder="Charging Time">
                                            </div>
                                            <div class="col">
                                                <label for="coolingTime" class="form-label">Cooling Time (s)</label>
                                                <input type="number" class="form-control" name="coolingTime"
                                                    id="coolingTime" placeholder="Cooling Time">
                                            </div>
                                            <div class="col">
                                                <label for="cycleTime" class="form-label">Cycle Time (s)</label>
                                                <input type="number" class="form-control" name="cycleTime"
                                                    id="cycleTime" placeholder="Cycle Time">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 7: Temperature Settings -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseTempSettings" role="button" aria-expanded="false"
                                            aria-controls="collapseTempSettings">
                                            Temperature Settings
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseTempSettings">
                                        <!-- Barrel Heater Temperature Zones -->
                                        <h5>
                                            <a class="text-decoration-none" data-bs-toggle="collapse"
                                                href="#collapseBarrelHeater" role="button" aria-expanded="false"
                                                aria-controls="collapseBarrelHeater">
                                                Barrel Heater Temperature Zones
                                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                            </a>
                                        </h5>
                                        <div class="collapse show" id="collapseBarrelHeater">
                                            <div
                                                class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-col-lg-6 g-3">
                                                <!-- Barrel Heater input s -->
                                                <div class="col">
                                                    <label for="barrelHeaterZone0" class="form-label">Barrel Heater Zone
                                                        0 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone0"
                                                        id="barrelHeaterZone0" placeholder="Barrel Heater Zone 0 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone1" class="form-label">Barrel Heater Zone
                                                        1 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone1"
                                                        id="barrelHeaterZone1" placeholder="Barrel Heater Zone 1 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone2" class="form-label">Barrel Heater Zone
                                                        2 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone2"
                                                        id="barrelHeaterZone2" placeholder="Barrel Heater Zone 2 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone3" class="form-label">Barrel Heater Zone
                                                        3 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone3"
                                                        id="barrelHeaterZone3" placeholder="Barrel Heater Zone 3 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone4" class="form-label">Barrel Heater Zone
                                                        4 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone4"
                                                        id="barrelHeaterZone4" placeholder="Barrel Heater Zone 4 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone5" class="form-label">Barrel Heater Zone
                                                        5 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone5"
                                                        id="barrelHeaterZone5" placeholder="Barrel Heater Zone 5 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone6" class="form-label">Barrel Heater Zone
                                                        6 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone6"
                                                        id="barrelHeaterZone6" placeholder="Barrel Heater Zone 6 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone7" class="form-label">Barrel Heater Zone
                                                        7 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone7"
                                                        id="barrelHeaterZone7" placeholder="Barrel Heater Zone 7 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone8" class="form-label">Barrel Heater Zone
                                                        8 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone8"
                                                        id="barrelHeaterZone8" placeholder="Barrel Heater Zone 8 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone9" class="form-label">Barrel Heater Zone
                                                        9 (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone9"
                                                        id="barrelHeaterZone9" placeholder="Barrel Heater Zone 9 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone10" class="form-label">Barrel Heater
                                                        Zone 10
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone10"
                                                        id="barrelHeaterZone10"
                                                        placeholder="Barrel Heater Zone 10 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone11" class="form-label">Barrel Heater
                                                        Zone 11
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone11"
                                                        id="barrelHeaterZone11"
                                                        placeholder="Barrel Heater Zone 11 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone12" class="form-label">Barrel Heater
                                                        Zone 12
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone12"
                                                        id="barrelHeaterZone12"
                                                        placeholder="Barrel Heater Zone 12 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone13" class="form-label">Barrel Heater
                                                        Zone 13
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone13"
                                                        id="barrelHeaterZone13"
                                                        placeholder="Barrel Heater Zone 13 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone14" class="form-label">Barrel Heater
                                                        Zone 14
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone14"
                                                        id="barrelHeaterZone14"
                                                        placeholder="Barrel Heater Zone 14 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone15" class="form-label">Barrel Heater
                                                        Zone 15
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone15"
                                                        id="barrelHeaterZone15"
                                                        placeholder="Barrel Heater Zone 15 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="barrelHeaterZone16" class="form-label">Barrel Heater
                                                        Zone 16
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="barrelHeaterZone16"
                                                        id="barrelHeaterZone16"
                                                        placeholder="Barrel Heater Zone 16 (°C)">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Mold Heater Controller Temperatures -->
                                        <h5>
                                            <a class="text-decoration-none" data-bs-toggle="collapse"
                                                href="#collapseMoldHeater" role="button" aria-expanded="false"
                                                aria-controls="collapseMoldHeater">
                                                Mold Heater Controller Temperatures
                                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                            </a>
                                        </h5>
                                        <div class="collapse show" id="collapseMoldHeater">
                                            <div
                                                class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-col-lg-6 g-3">
                                                <div class="col">
                                                    <label for="Zone0" class="form-label">Mold Heater Zone 0
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone0" id="Zone0"
                                                        placeholder="Mold Heater Zone 0 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone1" class="form-label">Mold Heater Zone 1
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone1" id="Zone1"
                                                        placeholder="Mold Heater Zone 1 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone2" class="form-label">Mold Heater Zone 2
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone2" id="Zone2"
                                                        placeholder="Mold Heater Zone 2 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone3" class="form-label">Mold Heater Zone 3
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone3" id="Zone3"
                                                        placeholder="Mold Heater Zone 3 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone4" class="form-label">Mold Heater Zone 4
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone4" id="Zone4"
                                                        placeholder="Mold Heater Zone 4 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone5" class="form-label">Mold Heater Zone 5
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone5" id="Zone5"
                                                        placeholder="Mold Heater Zone 5 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone6" class="form-label">Mold Heater Zone 6
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone6" id="Zone6"
                                                        placeholder="Mold Heater Zone 6 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone7" class="form-label">Mold Heater Zone 7
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone7" id="Zone7"
                                                        placeholder="Mold Heater Zone 7 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone8" class="form-label">Mold Heater Zone 8
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone8" id="Zone8"
                                                        placeholder="Mold Heater Zone 8 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone9" class="form-label">Mold Heater Zone 9
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone9" id="Zone9"
                                                        placeholder="Mold Heater Zone 9 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone10" class="form-label">Mold Heater Zone 10
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone10" id="Zone10"
                                                        placeholder="Mold Heater Zone 10 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone11" class="form-label">Mold Heater Zone 11
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone11" id="Zone11"
                                                        placeholder="Mold Heater Zone 11 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone12" class="form-label">Mold Heater Zone 12
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone12" id="Zone12"
                                                        placeholder="Mold Heater Zone 12 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone13" class="form-label">Mold Heater Zone 13
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone13" id="Zone13"
                                                        placeholder="Mold Heater Zone 13 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone14" class="form-label">Mold Heater Zone 14
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone14" id="Zone14"
                                                        placeholder="Mold Heater Zone 14 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone15" class="form-label">Mold Heater Zone 15
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone15" id="Zone15"
                                                        placeholder="Mold Heater Zone 15 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="Zone16" class="form-label">Mold Heater Zone 16
                                                        (°C)</label>
                                                    <input type="number" class="form-control" name="Zone16" id="Zone16"
                                                        placeholder="Mold Heater Zone 16 (°C)">
                                                </div>
                                                <div class="col">
                                                    <label for="MTCSetting" class="form-label">MTC Setting</label>
                                                    <input type="number" class="form-control" name="MTCSetting"
                                                        id="MTCSetting" placeholder="MTC Setting">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 8: Molding Settings -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseMoldingSettings" role="button" aria-expanded="false"
                                            aria-controls="collapseMoldingSettings">
                                            Molding Settings
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseMoldingSettings">
                                        <!-- Mold Open -->
                                        <h5>
                                            <a class="text-decoration-none" data-bs-toggle="collapse"
                                                href="#collapseMoldOpen" role="button" aria-expanded="false"
                                                aria-controls="collapseMoldOpen">
                                                Mold Open
                                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                            </a>
                                        </h5>
                                        <div class="collapse show" id="collapseMoldOpen">
                                            <!-- Mold Open input s -->
                                            <!-- MO Position -->
                                            <h6>Position</h6>
                                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                                <div class="col">
                                                    <label for="moldOpenPos1" class="form-label">Position 1</label>
                                                    <input type="number" class="form-control" name="moldOpenPos1"
                                                        id="moldOpenPos1" placeholder="Position 1">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPos2" class="form-label">Position 2</label>
                                                    <input type="number" class="form-control" name="moldOpenPos2"
                                                        id="moldOpenPos2" placeholder="Position 2">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPos3" class="form-label">Position 3</label>
                                                    <input type="number" class="form-control" name="moldOpenPos3"
                                                        id="moldOpenPos3" placeholder="Position 3">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPos4" class="form-label">Position 4</label>
                                                    <input type="number" class="form-control" name="moldOpenPos4"
                                                        id="moldOpenPos4" placeholder="Position 4">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPos5" class="form-label">Position 5</label>
                                                    <input type="number" class="form-control" name="moldOpenPos5"
                                                        id="moldOpenPos5" placeholder="Position 5">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPos6" class="form-label">Position 6</label>
                                                    <input type="number" class="form-control" name="moldOpenPos6"
                                                        id="moldOpenPos6" placeholder="Position 6">
                                                </div>
                                            </div>
                                            <!-- MO Speed -->
                                            <h6>Speed</h6>
                                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                                <div class="col">
                                                    <label for="moldOpenSpd1" class="form-label">Speed 1</label>
                                                    <input type="number" class="form-control" name="moldOpenSpd1"
                                                        id="moldOpenSpd1" placeholder="Speed 1">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenSpd2" class="form-label">Speed 2</label>
                                                    <input type="number" class="form-control" name="moldOpenSpd2"
                                                        id="moldOpenSpd2" placeholder="Speed 2">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenSpd3" class="form-label">Speed 3</label>
                                                    <input type="number" class="form-control" name="moldOpenSpd3"
                                                        id="moldOpenSpd3" placeholder="Speed 3">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenSpd4" class="form-label">Speed 4</label>
                                                    <input type="number" class="form-control" name="moldOpenSpd4"
                                                        id="moldOpenSpd4" placeholder="Speed 4">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenSpd5" class="form-label">Speed 5</label>
                                                    <input type="number" class="form-control" name="moldOpenSpd5"
                                                        id="moldOpenSpd5" placeholder="Speed 5">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenSpd6" class="form-label">Speed 6</label>
                                                    <input type="number" class="form-control" name="moldOpenSpd6"
                                                        id="moldOpenSpd6" placeholder="Speed 6">
                                                </div>
                                            </div>
                                            <!-- MO Pressure -->
                                            <h6>Pressure</h6>
                                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                                <div class="col">
                                                    <label for="moldOpenPressure1" class="form-label">Pressure 1</label>
                                                    <input type="number" class="form-control" name="moldOpenPressure1"
                                                        id="moldOpenPressure1" placeholder="Pressure 1">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPressure2" class="form-label">Pressure 2</label>
                                                    <input type="number" class="form-control" name="moldOpenPressure2"
                                                        id="moldOpenPressure2" placeholder="Pressure 2">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPressure3" class="form-label">Pressure 3</label>
                                                    <input type="number" class="form-control" name="moldOpenPressure3"
                                                        id="moldOpenPressure3" placeholder="Pressure 3">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPressure4" class="form-label">Pressure 4</label>
                                                    <input type="number" class="form-control" name="moldOpenPressure4"
                                                        id="moldOpenPressure4" placeholder="Pressure 4">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPressure5" class="form-label">Pressure 5</label>
                                                    <input type="number" class="form-control" name="moldOpenPressure5"
                                                        id="moldOpenPressure5" placeholder="Pressure 5">
                                                </div>
                                                <div class="col">
                                                    <label for="moldOpenPressure6" class="form-label">Pressure 6</label>
                                                    <input type="number" class="form-control" name="moldOpenPressure6"
                                                        id="moldOpenPressure6" placeholder="Pressure 6">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Mold Close -->
                                        <h5>
                                            <a class="text-decoration-none" data-bs-toggle="collapse"
                                                href="#collapseMoldClose" role="button" aria-expanded="false"
                                                aria-controls="collapseMoldClose">
                                                Mold Close
                                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                            </a>
                                        </h5>
                                        <div class="collapse show" id="collapseMoldClose">
                                            <!-- Mold Open input s -->
                                            <!-- MC Position -->
                                            <h6>Position</h6>
                                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                                <div class="col">
                                                    <label for="moldClosePos1" class="form-label">Position 1</label>
                                                    <input type="number" class="form-control" name="moldClosePos1"
                                                        id="moldClosePos1" placeholder="Position 1">
                                                </div>
                                                <div class="col">
                                                    <label for="moldClosePos2" class="form-label">Position 2</label>
                                                    <input type="number" class="form-control" name="moldClosePos2"
                                                        id="moldClosePos2" placeholder="Position 2">
                                                </div>
                                                <div class="col">
                                                    <label for="moldClosePos3" class="form-label">Position 3</label>
                                                    <input type="number" class="form-control" name="moldClosePos3"
                                                        id="moldClosePos3" placeholder="Position 3">
                                                </div>
                                                <div class="col">
                                                    <label for="moldClosePos4" class="form-label">Position 4</label>
                                                    <input type="number" class="form-control" name="moldClosePos4"
                                                        id="moldClosePos4" placeholder="Position 4">
                                                </div>
                                                <div class="col">
                                                    <label for="moldClosePos5" class="form-label">Position 5</label>
                                                    <input type="number" class="form-control" name="moldClosePos5"
                                                        id="moldClosePos5" placeholder="Position 5">
                                                </div>
                                                <div class="col">
                                                    <label for="moldClosePos6" class="form-label">Position 6</label>
                                                    <input type="number" class="form-control" name="moldClosePos6"
                                                        id="moldClosePos6" placeholder="Position 6">
                                                </div>
                                            </div>
                                            <!-- MO Speed -->
                                            <h6>Speed</h6>
                                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                                <div class="col">
                                                    <label for="moldCloseSpd1" class="form-label">Speed 1</label>
                                                    <input type="number" class="form-control" name="moldCloseSpd1"
                                                        id="moldCloseSpd1" placeholder="Speed 1">
                                                </div>
                                                <div class="col">
                                                    <label for="moldCloseSpd2" class="form-label">Speed 2</label>
                                                    <input type="number" class="form-control" name="moldCloseSpd2"
                                                        id="moldCloseSpd2" placeholder="Speed 2">
                                                </div>
                                                <div class="col">
                                                    <label for="moldCloseSpd3" class="form-label">Speed 3</label>
                                                    <input type="number" class="form-control" name="moldCloseSpd3"
                                                        id="moldCloseSpd3" placeholder="Speed 3">
                                                </div>
                                                <div class="col">
                                                    <label for="moldCloseSpd4" class="form-label">Speed 4</label>
                                                    <input type="number" class="form-control" name="moldCloseSpd4"
                                                        id="moldCloseSpd4" placeholder="Speed 4">
                                                </div>
                                                <div class="col">
                                                    <label for="moldCloseSpd5" class="form-label">Speed 5</label>
                                                    <input type="number" class="form-control" name="moldCloseSpd5"
                                                        id="moldCloseSpd5" placeholder="Speed 5">
                                                </div>
                                                <div class="col">
                                                    <label for="moldCloseSpd6" class="form-label">Speed 6</label>
                                                    <input type="number" class="form-control" name="moldCloseSpd6"
                                                        id="moldCloseSpd6" placeholder="Speed 6">
                                                </div>
                                            </div>
                                            <!-- MO Pressure -->
                                            <h6>Pressure</h6>
                                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                                                <div class="col">
                                                    <label for="moldClosePressure1" class="form-label">Pressure
                                                        1</label>
                                                    <input type="number" class="form-control" name="moldClosePressure1"
                                                        id="moldClosePressure1" placeholder="Pressure 1">
                                                </div>
                                                <div class="col">
                                                    <label for="moldClosePressure2" class="form-label">Pressure
                                                        2</label>
                                                    <input type="number" class="form-control" name="moldClosePressure2"
                                                        id="moldClosePressure2" placeholder="Pressure 2">
                                                </div>
                                                <div class="col">
                                                    <label for="moldClosePressure3" class="form-label">Pressure
                                                        3</label>
                                                    <input type="number" class="form-control" name="moldClosePressure3"
                                                        id="moldClosePressure3" placeholder="Pressure 3">
                                                </div>
                                                <div class="col">
                                                    <label for="pclorlp" class="form-label">PLC/LP</label>
                                                    <input type="text" class="form-control" name="pclorlp" id="pclorlp"
                                                        placeholder="PLC/LP">
                                                </div>
                                                <div class="col">
                                                    <label for="pchorhp" class="form-label">PCH/HP</label>
                                                    <input type="text" class="form-control" name="pchorhp" id="pchorhp"
                                                        placeholder="PCH/HP">
                                                </div>
                                                <div class="col">
                                                    <label for="lowPresTimeLimit" class="form-label">Low Pressure Time
                                                        Limit</label>
                                                    <input type="number" class="form-control" name="lowPresTimeLimit"
                                                        id="lowPresTimeLimit" placeholder="Low Pressure Time Limit">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 9: Plasticizing Parameters -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapsePlasticizingParams" role="button" aria-expanded="false"
                                            aria-controls="collapsePlasticizingParams">
                                            Plasticizing Parameters
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapsePlasticizingParams">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="screwRPM1" class="form-label">Screw RPM 1</label>
                                                <input type="number" class="form-control" name="screwRPM1"
                                                    id="screwRPM1" placeholder="Screw RPM 1">
                                            </div>
                                            <div class="col">
                                                <label for="screwRPM2" class="form-label">Screw RPM 2</label>
                                                <input type="number" class="form-control" name="screwRPM2"
                                                    id="screwRPM2" placeholder="Screw RPM 2">
                                            </div>
                                            <div class="col">
                                                <label for="screwRPM3" class="form-label">Screw RPM 3</label>
                                                <input type="number" class="form-control" name="screwRPM3"
                                                    id="screwRPM3" placeholder="Screw RPM 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="screwSpeed1" class="form-label">Screw Speed 1</label>
                                                <input type="number" class="form-control" name="screwSpeed1"
                                                    id="screwSpeed1" placeholder="Screw Speed 1">
                                            </div>
                                            <div class="col">
                                                <label for="screwSpeed2" class="form-label">Screw Speed 2</label>
                                                <input type="number" class="form-control" name="screwSpeed2"
                                                    id="screwSpeed2" placeholder="Screw Speed 2">
                                            </div>
                                            <div class="col">
                                                <label for="screwSpeed3" class="form-label">Screw Speed 3</label>
                                                <input type="number" class="form-control" name="screwSpeed3"
                                                    id="screwSpeed3" placeholder="Screw Speed 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="plastPressure1" class="form-label">Plast Pressure 1</label>
                                                <input type="number" class="form-control" name="plastPressure1"
                                                    id="plastPressure1" placeholder="Plast Pressure 1">
                                            </div>
                                            <div class="col">
                                                <label for="plastPressure2" class="form-label">Plast Pressure 2</label>
                                                <input type="number" class="form-control" name="plastPressure2"
                                                    id="plastPressure2" placeholder="Plast Pressure 2">
                                            </div>
                                            <div class="col">
                                                <label for="plastPressure3" class="form-label">Plast Pressure 3</label>
                                                <input type="number" class="form-control" name="plastPressure3"
                                                    id="plastPressure3" placeholder="Plast Pressure 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="plastPosition1" class="form-label">Plast Position 1</label>
                                                <input type="number" class="form-control" name="plastPosition1"
                                                    id="plastPosition1" placeholder="Plast Position 1">
                                            </div>
                                            <div class="col">
                                                <label for="plastPosition2" class="form-label">Plast Position 2</label>
                                                <input type="number" class="form-control" name="plastPosition2"
                                                    id="plastPosition2" placeholder="Plast Position 2">
                                            </div>
                                            <div class="col">
                                                <label for="plastPosition3" class="form-label">Plast Position 3</label>
                                                <input type="number" class="form-control" name="plastPosition3"
                                                    id="plastPosition3" placeholder="Plast Position 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="backPressure1" class="form-label">Back Pressure 1</label>
                                                <input type="number" class="form-control" name="backPressure1"
                                                    id="backPressure1" placeholder="Back Pressure 1">
                                            </div>
                                            <div class="col">
                                                <label for="backPressure2" class="form-label">Back Pressure 2</label>
                                                <input type="number" class="form-control" name="backPressure2"
                                                    id="backPressure2" placeholder="Back Pressure 2">
                                            </div>
                                            <div class="col">
                                                <label for="backPressure3" class="form-label">Back Pressure 3</label>
                                                <input type="number" class="form-control" name="backPressure3"
                                                    id="backPressure3" placeholder="Back Pressure 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="backPressureStartPosition" class="form-label">Back Pressure
                                                    Start
                                                    Position</label>
                                                <input type="number" class="form-control"
                                                    name="backPressureStartPosition" id="backPressureStartPosition"
                                                    placeholder="Back Pressure Start Position">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 10: Injection Parameters -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseInjectionParams" role="button" aria-expanded="false"
                                            aria-controls="collapseInjectionParams">
                                            Injection Parameters
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseInjectionParams">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="RecoveryPosition" class="form-label">Recovery Position
                                                    (mm)</label>
                                                <input type="number" class="form-control" name="RecoveryPosition"
                                                    id="RecoveryPosition" placeholder="Recovery Position">
                                            </div>
                                            <div class="col">
                                                <label for="SecondStagePosition" class="form-label">Second Stage
                                                    Position (mm)</label>
                                                <input type="number" class="form-control" name="SecondStagePosition"
                                                    id="SecondStagePosition" placeholder="Second Stage Position">
                                            </div>
                                            <div class="col">
                                                <label for="Cushion" class="form-label">Cushion (mm)</label>
                                                <input type="number" class="form-control" name="Cushion" id="Cushion"
                                                    placeholder="Cushion">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="screwPosition1" class="form-label">Screw Position 1</label>
                                                <input type="number" class="form-control" name="ScrewPosition1"
                                                    id="screwPosition1" placeholder="Screw Position 1">
                                            </div>
                                            <div class="col">
                                                <label for="screwPosition2" class="form-label">Screw Position 2</label>
                                                <input type="number" class="form-control" name="ScrewPosition2"
                                                    id="screwPosition2" placeholder="Screw Position 2">
                                            </div>
                                            <div class="col">
                                                <label for="screwPosition3" class="form-label">Screw Position 3</label>
                                                <input type="number" class="form-control" name="ScrewPosition3"
                                                    id="screwPosition3" placeholder="Screw Position 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="INJSpeed1" class="form-label">Injection Speed 1</label>
                                                <input type="number" class="form-control" name="InjectionSpeed1"
                                                    id="injectionSpeed1" placeholder="Injection Speed 1">
                                            </div>
                                            <div class="col">
                                                <label for="INJSpeed2" class="form-label">Injection Speed 2</label>
                                                <input type="number" class="form-control" name="InjectionSpeed2"
                                                    id="injectionSpeed2" placeholder="Injection Speed 2">
                                            </div>
                                            <div class="col">
                                                <label for="INJSpeed3" class="form-label">Injection Speed 3</label>
                                                <input type="number" class="form-control" name="InjectionSpeed3"
                                                    id="injectionSpeed3" placeholder="Injection Speed 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="INJPressure1" class="form-label">Injection Pressure
                                                    1</label>
                                                <input type="number" class="form-control" name="InjectionPressure1"
                                                    id="injectionPressure1" placeholder="Injection Pressure 1">
                                            </div>
                                            <div class="col">
                                                <label for="INJPressure2" class="form-label">Injection Pressure
                                                    2</label>
                                                <input type="number" class="form-control" name="InjectionPressure2"
                                                    id="injectionPressure2" placeholder="Injection Pressure 2">
                                            </div>
                                            <div class="col">
                                                <label for="INJPressure3" class="form-label">Injection Pressure
                                                    3</label>
                                                <input type="number" class="form-control" name="InjectionPressure3"
                                                    id="injectionPressure3" placeholder="Injection Pressure 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="SuckBackPos" class="form-label">Suck Back Position</label>
                                                <input type="number" class="form-control" name="SuckBackPosition"
                                                    id="suckBackPosition" placeholder="Suck Back Position">
                                            </div>
                                            <div class="col">
                                                <label for="SuckBackSpeed" class="form-label">Suck Back Speed</label>
                                                <input type="number" class="form-control" name="SuckBackSpeed"
                                                    id="suckBackSpeed" placeholder="Suck Back Speed">
                                            </div>
                                            <div class="col">
                                                <label for="SuckBackPres" class="form-label">Suck Back Pressure</label>
                                                <input type="number" class="form-control" name="SuckBackPressure"
                                                    id="suckBackPressure" placeholder="Suck Back Pressure">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="ScrewPosition4" class="form-label">Screw Position 4</label>
                                                <input type="number" class="form-control" name="ScrewPosition4"
                                                    id="screwPosition4" placeholder="Screw Position 4">
                                            </div>
                                            <div class="col">
                                                <label for="ScrewPosition5" class="form-label">Screw Position 5</label>
                                                <input type="number" class="form-control" name="ScrewPosition5"
                                                    id="ScrewPosition5" placeholder="Screw Position 5">
                                            </div>
                                            <div class="col">
                                                <label for="ScrewPosition6" class="form-label">Screw Position 6</label>
                                                <input type="number" class="form-control" name="ScrewPosition6"
                                                    id="ScrewPosition6" placeholder="Screw Position 6">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="InjectionSpeed4" class="form-label">Injection Speed
                                                    4</label>
                                                <input type="number" class="form-control" name="InjectionSpeed4"
                                                    id="InjectionSpeed4" placeholder="Injection Speed 4">
                                            </div>
                                            <div class="col">
                                                <label for="InjectionSpeed5" class="form-label">Injection Speed
                                                    5</label>
                                                <input type="number" class="form-control" name="InjectionSpeed5"
                                                    id="InjectionSpeed5" placeholder="Injection Speed 5">
                                            </div>
                                            <div class="col">
                                                <label for="InjectionSpeed6" class="form-label">Injection Speed
                                                    6</label>
                                                <input type="number" class="form-control" name="InjectionSpeed6"
                                                    id="InjectionSpeed6" placeholder="Injection Speed 6">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="InjectionPressure4" class="form-label">Injection Pressure
                                                    4</label>
                                                <input type="number" class="form-control" name="InjectionPressure4"
                                                    id="InjectionPressure4" placeholder="Injection Pressure 4">
                                            </div>
                                            <div class="col">
                                                <label for="InjectionPressure5" class="form-label">Injection Pressure
                                                    5</label>
                                                <input type="number" class="form-control" name="InjectionPressure5"
                                                    id="InjectionPressure5" placeholder="Injection Pressure 5">
                                            </div>
                                            <div class="col">
                                                <label for="InjectionPressure6" class="form-label">Injection Pressure
                                                    6</label>
                                                <input type="number" class="form-control" name="InjectionPressure6"
                                                    id="InjectionPressure6" placeholder="Injection Pressure 6">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="SprueBreak" class="form-label">Sprue Break</label>
                                                <input type="number" class="form-control" name="SprueBreak"
                                                    id="SprueBreak" placeholder="Sprue Break">
                                            </div>
                                            <div class="col">
                                                <label for="SprueBreakTime" class="form-label">Sprue Break Time</label>
                                                <input type="number" class="form-control" name="SprueBreakTime"
                                                    id="SprueBreakTime" placeholder="Sprue Break Time">
                                            </div>
                                            <div class="col">
                                                <label for="InjectionDelay" class="form-label">Injection Delay</label>
                                                <input type="number" class="form-control" name="InjectionDelay"
                                                    id="InjectionDelay" placeholder="Injection Delay">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="HoldingPres1" class="form-label">Holding Pressure 1</label>
                                                <input type="number" class="form-control" name="HoldingPressure1"
                                                    id="HoldingPres1" placeholder="Holding Pressure 1">
                                            </div>
                                            <div class="col">
                                                <label for="HoldingPres2" class="form-label">Sprue Break Time</label>
                                                <input type="number" class="form-control" name="HoldingPressure2"
                                                    id="HoldingPres2" placeholder="Holding Pressure 2">
                                            </div>
                                            <div class="col">
                                                <label for="HoldingPres3" class="form-label">Holding Pressure 3</label>
                                                <input type="number" class="form-control" name="HoldingPressure3"
                                                    id="HoldingPres3" placeholder="Holding Pressure 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="HoldingSpeed1" class="form-label">Holding Speed 1</label>
                                                <input type="number" class="form-control" name="HoldingSpeed1"
                                                    id="HoldingSpeed1" placeholder="Holding Speed 1">
                                            </div>
                                            <div class="col">
                                                <label for="HoldingSpeed2" class="form-label">Holding Speed 2</label>
                                                <input type="number" class="form-control" name="HoldingSpeed2"
                                                    id="HoldingSpeed2" placeholder="Holding Speed 2">
                                            </div>
                                            <div class="col">
                                                <label for="HoldingSpeed3" class="form-label">Holding Speed 3</label>
                                                <input type="number" class="form-control" name="HoldingSpeed3"
                                                    id="HoldingSpeed3" placeholder="Holding Speed 3">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="HoldingTime1" class="form-label">Holding Time 1</label>
                                                <input type="number" class="form-control" name="HoldingTime1"
                                                    id="HoldingTime1" placeholder="Holding Time 1">
                                            </div>
                                            <div class="col">
                                                <label for="HoldingTime2" class="form-label">Holding Time 2</label>
                                                <input type="number" class="form-control" name="HoldingTime2"
                                                    id="HoldingTime2" placeholder="Holding Time 2">
                                            </div>
                                            <div class="col">
                                                <label for="HoldingTime3" class="form-label">Holding Time 3</label>
                                                <input type="number" class="form-control" name="HoldingTime3"
                                                    id="HoldingTime3" placeholder="Holding Time 3">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 11: Ejection Parameters -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseEjectionParams" role="button" aria-expanded="false"
                                            aria-controls="collapseEjectionParams">
                                            Ejection Parameters
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseEjectionParams">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="AirBlowTimeA" class="form-label">Air Blow Time A</label>
                                                <input type="number" class="form-control" name="AirBlowTimeA"
                                                    id="airBlowTimeA" placeholder="Air Blow Time A">
                                            </div>
                                            <div class="col">
                                                <label for="AirBlowPositionA" class="form-label">Air Blow Position
                                                    A</label>
                                                <input type="number" class="form-control" name="AirBlowPositionA"
                                                    id="airBlowPositionA" placeholder="Air Blow Position A">
                                            </div>
                                            <div class="col">
                                                <label for="AB A Delay" class="form-label">Air Blow A Delay</label>
                                                <input type="number" class="form-control" name="AirBlowADelay"
                                                    id="airBlowADelay" placeholder="Air Blow A Delay">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="AirBlowTimeB" class="form-label">Air Blow Time B</label>
                                                <input type="number" class="form-control" name="AirBlowTimeB"
                                                    id="airBlowTimeB" placeholder="Air Blow Time B">
                                            </div>
                                            <div class="col">
                                                <label for="AirBlowPositionB" class="form-label">Air Blow Position
                                                    B</label>
                                                <input type="number" class="form-control" name="AirBlowPositionB"
                                                    id="airBlowPositionB" placeholder="Air Blow Position B">
                                            </div>
                                            <div class="col">
                                                <label for="AirBlowBDelay" class="form-label">Air Blow B Delay</label>
                                                <input type="number" class="form-control" name="AirBlowBDelay"
                                                    id="airBlowBDelay" placeholder="Air Blow B Delay">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="EjectorForwardPosition1" class="form-label">Ejector Forward
                                                    Position
                                                    1</label>
                                                <input type="number" class="form-control" name="EjectorForwardPosition1"
                                                    id="EjectorForwardPosition1"
                                                    placeholder="Ejector Forward Position 1">
                                            </div>
                                            <div class="col">
                                                <label for="EjectorForwardPosition2" class="form-label">Ejector Forward
                                                    Position
                                                    2</label>
                                                <input type="number" class="form-control" name="EjectorForwardPosition2"
                                                    id="EjectorForwardPosition2"
                                                    placeholder="Ejector Forward Position 2">
                                            </div>
                                            <div class="col">
                                                <label for="EFSpeed1" class="form-label">Ejector Forward Speed 1</label>
                                                <input type="number" class="form-control" name="EjectorForwardSpeed1"
                                                    id="ejectorForwardSpeed1" placeholder="Ejector Forward Speed 1">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="EjectorRetractPosition1" class="form-label">Ejector Retract
                                                    Position
                                                    1</label>
                                                <input type="number" class="form-control" name="EjectorRetractPosition1"
                                                    id="ejectorRetractPosition1"
                                                    placeholder="Ejector Retract Position 1">
                                            </div>
                                            <div class="col">
                                                <label for="EjectorRetractPosition2" class="form-label">Ejector Retract
                                                    Position
                                                    2</label>
                                                <input type="number" class="form-control" name="EjectorRetractPosition2"
                                                    id="ejectorRetractPosition2"
                                                    placeholder="Ejector Retract Position 2">
                                            </div>
                                            <div class="col">
                                                <label for="Ejector Retract Speed1" class="form-label">Ejector Retract
                                                    Speed
                                                    1</label>
                                                <input type="number" class="form-control" name="EjectorRetractSpeed1"
                                                    id="ejectorRetractSpeed1" placeholder="Ejector Retract Speed 1">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="EjectorForwardPosition" class="form-label">Ejector Forward
                                                    Position</label>
                                                <input type="number" class="form-control" name="EjectorForwardPosition"
                                                    id="ejectorForwardPosition" placeholder="Ejector Forward Position">
                                            </div>
                                            <div class="col">
                                                <label for="EjectorForwardTime" class="form-label">Ejector Forward
                                                    Time</label>
                                                <input type="number" class="form-control" name="EjectorForwardTime"
                                                    id="ejectorForwardTime" placeholder="Ejector Forward Time">
                                            </div>
                                            <div class="col">
                                                <label for="EjectorRetractPosition" class="form-label">Ejector Retract
                                                    Position</label>
                                                <input type="number" class="form-control" name="EjectorRetractPosition"
                                                    id="ejectorRetractPosition" placeholder="Ejector Retract Position">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="EjectorRetractTime" class="form-label">Ejector Retract
                                                    Time</label>
                                                <input type="number" class="form-control" name="EjectorRetractTime"
                                                    id="ejectorRetractTime" placeholder="Ejector Retract Time">
                                            </div>
                                            <div class="col">
                                                <label for="EjectorForwardSpeed2" class="form-label">Ejector Forward
                                                    Speed 2</label>
                                                <input type="number" class="form-control" name="EjectorForwardSpeed2"
                                                    id="ejectorForwardSpeed2" placeholder="Ejector Forward Speed2">
                                            </div><!--sub field-->
                                            <div class="col">
                                                <label for="EjectorForwardPressure1" class="form-label">Ejector Forward
                                                    Pressure
                                                    1</label>
                                                <input type="number" class="form-control" name="EjectorForwardPressure1"
                                                    id="ejectorForwardPressure1"
                                                    placeholder="Ejector Forward Pressure 1">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="EjectorForwardSpeed2" class="form-label">Ejector Forward
                                                    Speed 2</label>
                                                <input type="number" class="form-control" name="EjectorForwardSpeed2"
                                                    id="ejectorForwardSpeed2" placeholder="Ejector Forward Speed 2">
                                            </div>
                                            <!--sub field-->
                                            <div class="col">
                                                <label for="EjectorForward" class="form-label">Ejector Forward</label>
                                                <input type="number" class="form-control" name="EjectorForward"
                                                    id="ejectorForward" placeholder="Ejector Forward">
                                            </div>
                                            <div class="col">
                                                <label for="EjectorRetractSpeed2" class="form-label">Ejector Retract
                                                    Speed 2</label>
                                                <input type="number" class="form-control" name="EjectorRetractSpeed2"
                                                    id="ejectorRetractSpeed2" placeholder="Ejector Retract Speed 2">
                                            </div>
                                        </div>
                                        <!--sub field-->
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="EjectorRetractPressure1" class="form-label">Ejector Retract
                                                    Pressure
                                                    1</label>
                                                <input type="number" class="form-control" name="EjectorRetractPressure1"
                                                    id="ejectorRetractPressure1"
                                                    placeholder="Ejector Retract Pressure 1">
                                            </div>
                                            <div class="col">
                                                <label for="EjectorRetractSpeed2" class="form-label">Ejector Retract
                                                    Speed 2</label>
                                                <input type="number" class="form-control" name="EjectorRetractSpeed2"
                                                    id="ejectorRetractSpeed2" placeholder="Ejector Retract Speed 2">
                                            </div>
                                            <!--sub field-->
                                            <div class="col">
                                                <label for="EjectorRetract" class="form-label">Ejector Retract</label>
                                                <input type="number" class="form-control" name="EjectorRetract"
                                                    id="ejectorRetract" placeholder="Ejector Retract">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 12: Core Settings -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseCorePull" role="button" aria-expanded="false"
                                            aria-controls="collapseCorePull">
                                            Core Pull Settings
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseCorePull">
                                        <h4>
                                            <a class="text-decoration-none" data-bs-toggle="collapse"
                                                href="#collapseCoreSetA" role="button" aria-expanded="false"
                                                aria-controls="collapseSetA">
                                                Core Set A
                                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                            </a>
                                        </h4>
                                        <div class="collapse show" id="collapseCoreSetA">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="coreSetASequence" class="form-label">Core Set A
                                                        Sequence</label>
                                                    <input type="number" class="form-control" name="coreSetASequence"
                                                        id="coreSetASequence" placeholder="Core Set A Sequence">
                                                </div>
                                                <div class="col">
                                                    <label for="coreSetAPressure" class="form-label">Core Set A Pressure
                                                        ()</label>
                                                    <input type="number" class="form-control" name="coreSetAPressure"
                                                        id="coreSetAPressure" placeholder="Core Set A Pressure">
                                                </div>
                                                <div class="col">
                                                    <label for="coreSetASpeed" class="form-label">Core Set A
                                                        Speed</label>
                                                    <input type="number" class="form-control" name="coreSetASpeed"
                                                        id="coreSetASpeed" placeholder="Core Set A Speed">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="coreSetAPosition" class="form-label">Core Set A
                                                        Position</label>
                                                    <input type="number" class="form-control" name="coreSetAPosition"
                                                        id="coreSetAPosition" placeholder="Core Set A Position">
                                                </div>
                                                <div class="col">
                                                    <label for="coreSetATime" class="form-label">Core Set A Time</label>
                                                    <input type="number" class="form-control" name="coreSetATime"
                                                        id="coreSetATime" placeholder="Core Set A Time">
                                                </div>
                                                <div class="col">
                                                    <label for="coreSetALimitSwitch" class="form-label">Core Set A Limit
                                                        Switch</label>
                                                    <input type="number" class="form-control" name="coreSetALimitSwitch"
                                                        id="coreSetALimitSwitch" placeholder="Core Set A Limit Switch">
                                                </div>
                                            </div>
                                        </div>

                                        <h4>
                                            <a class="text-decoration-none" data-bs-toggle="collapse"
                                                href="#collapseCorePullA" role="button" aria-expanded="false"
                                                aria-controls="collapseCorePullA">
                                                Core Pull A
                                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                            </a>
                                        </h4>
                                        <div class="collapse show" id="collapseCorePullA">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="corePullASequence" class="form-label">Core Pull A
                                                        Sequence</label>
                                                    <input type="number" class="form-control" name="corePullASequence"
                                                        id="corePullASequence" placeholder="Core Pull A Sequence">
                                                </div>
                                                <div class="col">
                                                    <label for="corePullAPressure" class="form-label">Core Pull A
                                                        Pressure
                                                        ()</label>
                                                    <input type="number" class="form-control" name="corePullAPressure"
                                                        id="corePullAPressure" placeholder="Core Pull A Pressure">
                                                </div>
                                                <div class="col">
                                                    <label for="corePullASpeed" class="form-label">Core Pull A
                                                        Speed</label>
                                                    <input type="number" class="form-control" name="corePullASpeed"
                                                        id="corePullASpeed" placeholder="Core Pull A Speed">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="corePullAPosition" class="form-label">Core Pull A
                                                        Position</label>
                                                    <input type="number" class="form-control" name="corePullAPosition"
                                                        id="corePullAPosition" placeholder="Core Pull A Position">
                                                </div>
                                                <div class="col">
                                                    <label for="corePullATime" class="form-label">Core Pull A
                                                        Time</label>
                                                    <input type="number" class="form-control" name="corePullATime"
                                                        id="corePullATime" placeholder="Core Pull A Time">
                                                </div>
                                                <div class="col">
                                                    <label for="corePullALimitSwitch" class="form-label">Core Pull A
                                                        Limit
                                                        Switch</label>
                                                    <input type="number" class="form-control"
                                                        name="corePullALimitSwitch" id="corePullALimitSwitch"
                                                        placeholder="Core Pull A Limit Switch">
                                                </div>
                                            </div>
                                        </div>

                                        <h4>
                                            <a class="text-decoration-none" data-bs-toggle="collapse"
                                                href="#collapseCoreSetB" role="button" aria-expanded="false"
                                                aria-controls="collapseSetA">
                                                Core Set B
                                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                            </a>
                                        </h4>
                                        <div class="collapse show" id="collapseCoreSetB">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="coreSetBSequence" class="form-label">Core Set B
                                                        Sequence</label>
                                                    <input type="number" class="form-control" name="coreSetBSequence"
                                                        id="coreSetBSequence" placeholder="Core Set B Sequence">
                                                </div>
                                                <div class="col">
                                                    <label for="coreSetBPressure" class="form-label">Core Set B Pressure
                                                        ()</label>
                                                    <input type="number" class="form-control" name="coreSetBPressure"
                                                        id="coreSetBPressure" placeholder="Core Set B Pressure">
                                                </div>
                                                <div class="col">
                                                    <label for="coreSetBSpeed" class="form-label">Core Set B
                                                        Speed</label>
                                                    <input type="number" class="form-control" name="coreSetBSpeed"
                                                        id="coreSetBSpeed" placeholder="Core Set B Speed">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="coreSetBPosition" class="form-label">Core Set B
                                                        Position</label>
                                                    <input type="number" class="form-control" name="coreSetBPosition"
                                                        id="coreSetBPosition" placeholder="Core Set B Position">
                                                </div>
                                                <div class="col">
                                                    <label for="coreSetBTime" class="form-label">Core Set B Time</label>
                                                    <input type="number" class="form-control" name="coreSetBTime"
                                                        id="coreSetBTime" placeholder="Core Set B Time">
                                                </div>
                                                <div class="col">
                                                    <label for="coreSetBLimitSwitch" class="form-label">Core Set B Limit
                                                        Switch</label>
                                                    <input type="number" class="form-control" name="coreSetBLimitSwitch"
                                                        id="coreSetBLimitSwitch" placeholder="Core Set B Limit Switch">
                                                </div>
                                            </div>
                                        </div>
                                        <h4>
                                            <a class="text-decoration-none" data-bs-toggle="collapse"
                                                href="#collapseCorePullB" role="button" aria-expanded="false"
                                                aria-controls="collapseCoreSetB">
                                                Core Pull B
                                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                            </a>
                                        </h4>
                                        <div class="collapse show" id="collapseCorePullB">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="corePullBSequence" class="form-label">Core Pull B
                                                        Sequence</label>
                                                    <input type="number" class="form-control" name="corePullBSequence"
                                                        id="corePullBSequence" placeholder="Core Pull B Sequence">
                                                </div>
                                                <div class="col">
                                                    <label for="corePullBPressure" class="form-label">Core Pull B
                                                        Pressure
                                                        ()</label>
                                                    <input type="number" class="form-control" name="corePullBPressure"
                                                        id="corePullBPressure" placeholder="Core Pull B Pressure">
                                                </div>
                                                <div class="col">
                                                    <label for="corePullBSpeed" class="form-label">Core Pull B
                                                        Speed</label>
                                                    <input type="number" class="form-control" name="corePullBSpeed"
                                                        id="corePullBSpeed" placeholder="Core Pull B Speed">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="corePullBPosition" class="form-label">Core Pull B
                                                        Position</label>
                                                    <input type="number" class="form-control" name="corePullBPosition"
                                                        id="corePullBPosition" placeholder="Core Pull B Position">
                                                </div>
                                                <div class="col">
                                                    <label for="corePullBTime" class="form-label">Core Pull B
                                                        Time</label>
                                                    <input type="number" class="form-control" name="corePullBTime"
                                                        id="corePullBTime" placeholder="Core Pull B Time">
                                                </div>
                                                <div class="col">
                                                    <label for="corePullBLimitSwitch" class="form-label">Core Pull B
                                                        Limit
                                                        Switch</label>
                                                    <input type="number" class="form-control"
                                                        name="corePullBLimitSwitch" id="corePullBLimitSwitch"
                                                        placeholder="Core Pull B Limit Switch">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 13: Additional Information -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseAdditionalInfo" role="button" aria-expanded="false"
                                            aria-controls="collapseAdditionalInfo">
                                            Additional Information
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseAdditionalInfo">
                                        <div class="mb-3">
                                            <label for="additionalInfo" class="form-label">Additional Info</label>
                                            <textarea class="form-control" name="additionalInfo" id="additionalInfo"
                                                rows="4" placeholder="Enter any additional info"></textarea>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 14: Personnel -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapsePersonnel" role="button" aria-expanded="false"
                                            aria-controls="collapsePersonnel">
                                            Personnel
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapsePersonnel">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="adjuster" class="form-label">Adjuster Name</label>
                                                <input type="text" class="form-control" name="adjuster" id="adjuster"
                                                    value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>"
                                                    readonly required>
                                            </div>

                                            <div class="col">
                                                <label for="qae" class="form-label">Quality Assurance Engineer
                                                    Name</label>
                                                <input type="text" class="form-control" name="qae" id="qae"
                                                    placeholder="Enter Quality Assurance Engineer Name">
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <!-- Section 15: Attachments --><!-- Section: Attachments -->
                                    <h4>
                                        <a class="text-decoration-none" data-bs-toggle="collapse"
                                            href="#collapseAttachments" role="button" aria-expanded="false"
                                            aria-controls="collapseAttachments">
                                            Attachments
                                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                                        </a>
                                    </h4>
                                    <div class="collapse show" id="collapseAttachments">
                                        <div class="row mb-3">
                                            <!-- Image Upload -->
                                            <div class="col-md-6">
                                                <label for="uploadImages" class="form-label">Upload Images</label>
                                                <input class="form-control" type="file" id="uploadImages"
                                                    name="uploadImages[]" accept="image/jpeg, image/png, image/gif"
                                                    multiple>
                                                <small class="text-muted">Max 5 images (JPG, PNG, GIF)</small>
                                            </div>

                                            <!-- Video Upload -->
                                            <div class="col-md-6">
                                                <label for="uploadVideos" class="form-label">Upload Videos</label>
                                                <input class="form-control" type="file" id="uploadVideos"
                                                    name="uploadVideos[]" accept="video/mp4, video/avi, video/mkv"
                                                    multiple>
                                                <small class="text-muted">Max 2 videos (MP4, AVI, MKV)</small>
                                            </div>
                                        </div>

                                        <!-- Preview Containers -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div id="imagePreviews" class="d-flex flex-wrap gap-2 mb-3"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="videoPreviews" class="d-flex flex-wrap gap-2 mb-3"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add a horizontal line to separate sections -->
                                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                                    <button type="button" id="autofillButton"
                                        class="btn btn-secondary mt-4">Autofill</button>


                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary mt-4">Submit</button>

                                </form>
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

    <script>
        const MAX_IMAGES = 5;
        const MAX_VIDEOS = 2;
        let selectedImageFiles = [];
        let selectedVideoFiles = [];

        // Product list for autocomplete
        const productList = [
            "120L Trash Bin Cover",
            "120L Trash Bin Screws",
            "12oz Shell Plastic Crate",
            "12oz Shell Plastic Crate (COKE)",
            "12oz Shell Plastic Crate (PEPSI)",
            "16L Square Pail Body",
            "1L Container Body",
            "1L Shell Plastic Crate",
            "1L Shell Plastic Crate (Coke)",
            "1L Shell Plastic Crate (Pepsi)",
            "2.6L Square Pail Cover",
            "2.8L Basin Cover",
            "20F BODY",
            "20F COVER",
            "24B Crate",
            "25F Body",
            "25F/55F Cover",
            "25F/55F Pin",
            "2FT HANGER",
            "3.5L Cover Wonder Mayo",
            "3.5L P. Jar Body",
            "30F Body",
            "30F/50F Cover",
            "36-2M",
            "36-B2",
            "36-BJ",
            "50-BJ Bottom",
            "50-BJ Cover",
            "50-BJ Frame",
            "50-BJ Sides",
            "50-BJ Sliding Lock",
            "50-BJ Top",
            "50F Body",
            "55F Body",
            "80F Body",
            "80F cover",
            "8oz Shell Plastic Crate",
            "8oz Shell Plastic Crate (COKE)",
            "8oz Shell Plastic Crate (PEPSI)",
            "9-B2",
            "Adjustable Footing - Dispenser Body",
            "Aries Side Chair",
            "B-2",
            "B1",
            "B1-A",
            "B1-C",
            "B1-P",
            "Bale Handle",
            "Banera",
            "Bin Box",
            "CF-1J",
            "CF-1JN",
            "Chick Crate",
            "Chicken House Footing  8\" w/ Slot",
            "Chicken House Footing 10\" w/ Slot",
            "Chicken House Footing 12\"",
            "Chicken House Footing 12\" w/ Slot",
            "Chicken House Footing 8\"",
            "Chicken House Matting 2x2",
            "Chicken House Matting 2x4",
            "Chicken House Footing 10\"",
            "CL-1",
            "CL-1B",
            "CL-10",
            "CL-2",
            "CL-2B",
            "CL-3",
            "CL-4",
            "CL-4B",
            "CL-5",
            "CL-6",
            "CL-7",
            "CL-8",
            "CL-9",
            "Closure Cap",
            "Closure Spout (Bridge)",
            "CM-1",
            "CM-2",
            "CM-2B",
            "CM-2F",
            "CM-2J",
            "CM-3",
            "CM-3B",
            "CM-4",
            "CM-4F",
            "CM-5",
            "CM-6",
            "CM-7",
            "CM-8",
            "CNS-1",
            "CNS-1C",
            "CNS-2",
            "CNS-2C",
            "CNS-3",
            "CNS-3C",
            "CP-1 Collapsible Crate BASE",
            "CP-1 Collapsible Crate HANDLE",
            "CP-1 Collapsible Long/Short Side",
            "CP-2 Collapsible BASE",
            "CP-2 Collapsible Crate Arrowlock",
            "CP-2 Collapsible Crate Pin Long",
            "CP-2 Collapsible Crate Pin Short",
            "CP-2 Collapsible LONG SIDE",
            "CP-2 Collapsible SHORT SIDE",
            "Crate Cover #1",
            "Crate Cover #2",
            "Crate Cover #3",
            "Crate Cover #4",
            "Crate Cover #5",
            "Crate Lavel Holder",
            "Crate Tag Holder #2",
            "Crate Tag Holder #3",
            "Crate Tag Holder 4-1",
            "Crown Stool",
            "CS-1",
            "Customizable Trolley Bracket #125",
            "Customizable Trolley Bracket #75",
            "Customizable Trolley Bracket Support",
            "Divider 3\" - 400 (SHORT)",
            "Divider 3\" - 600 (LONG)",
            "Divider 7\" - 400 (SHORT)",
            "DIvider 7\" - 600 (LONG)",
            "Drying Tray (small)",
            "Drying Tray PROFOOD",
            "DW-1",
            "DW-2",
            "Egg Tray SML",
            "Egg Tray XL",
            "EZ-3 CRATE",
            "EZ-4 CRATE",
            "EZ-5 CRATE",
            "Food Court Tray",
            "Grass matting",
            "H1 Intellect Left Handed L/R",
            "H1 Mega Intellect Left Handed L/R",
            "H1-B LEG L/R",
            "H2 Intellect Left Handed L/R",
            "H2 Mega Intellect Left Handed L/R",
            "H20J",
            "H3 Intellect Left Handed L/R",
            "H3 Mega Intellect Left Handed L/R",
            "HYDROPHONICS NET CUP",
            "Intellect Side Chair",
            "Kiddie Chair",
            "Kiddie Desk Compartment",
            "Kiddie Desk Top",
            "Kiddie Hinge",
            "Kiddie Leg Cap",
            "Kiddie Side Deco",
            "LCC Bottom",
            "LCC LENGTH Side",
            "LCC Open Top Cover",
            "LCC Sliding Door",
            "LCC Top Cover",
            "LCC WIDTH Side",
            "Leg Footing",
            "Leo Arm Chair",
            "Leo Side Chair",
            "Lid Spout Closure",
            "N-30",
            "N-50",
            "N-75",
            "N-120",
            "Park Bench Leg",
            "Park Bench pad",
            "PB-1",
            "PB-1L",
            "PB-NS1",
            "Pig Pallet",
            "Pisces Arm Chair",
            "Pisces Side Chair",
            "PL-1",
            "PL-1P",
            "PL-1PN",
            "PL-2",
            "PL-2S",
            "PL-3J",
            "PL-3JS",
            "PM-1",
            "PM-1S",
            "PM-2",
            "PM-2H",
            "PM-2PC",
            "PM-2S",
            "PM-5",
            "PM-5S",
            "PNS-1",
            "PNS-2",
            "PNS-2C",
            "PNS-3",
            "PNS-3C",
            "Profood Round Tray",
            "Round Table 32\"",
            "Round Table 37\"",
            "Soap Crate 1",
            "Spout Cover",
            "Spout Natural",
            "Square Table Top 30\"",
            "Table Leg",
            "Table Plug",
            "Table Top Dispenser Body",
            "TABLET SEAT & BACK REST",
            "Tansan Table Top",
            "TD-1",
            "TD-2",
            "TD-3",
            "TD-4",
            "TD-5",
            "TD-6",
            "TD-7",
            "TL-1",
            "TL-2",
            "TP131",
            "TP131.5",
            "TP331J",
            "TP381.5J",
            "TP382J",
            "Trolley 1 Cart 620 x 400 x 60 mm",
            "Tubular hanger",
            "Tumbler 20 oz",
            "Tumbler 9.5 oz",
            "Tumbler L 12 oz",
            "Tumbler L 16 oz",
            "UTILITY BIN 10L RECTANGULAR",
            "VP1 Body",
            "VP1 Cover",
            "GROCERY BASKET HANDLE",
            "GROCERY BASKET",
            "TD-8",
            "TRASH BIN LEVER",
            "TRASH BIN PEDAL",
            "BUS BOX 5\"",
            "TUNA LOIN COVER",
            "INTELLECT MEGA L/R",
            "TRASH BIN PUSH PIN",
            "N-130",
            "TABLET SEAT",
            "TABLET BACKREST",
            "COLOR SWATCH",
            "PB-NS1 LOCK",
            "24B CRATE COVER",
            "PL-3JPC",
            "4 COMPARTMENT CUTLERY TRAY",
            "PBL-CL2",
            "8oz FULL DEPTH",
            "H1 INTELLECT SIDECHAIR",
            "MEGA SIDECHAIR",
            "H3 INTELLECT SIDE CHAIR",
            "MEGA LEFT HANDED L/R",
            "TD-9",
            "ET-3",
            "30L TRASHBIN COVER",
            "30L TRASHBIN SWING LID",
            "30L TRASHBIN BODY",
            "800ml Crate",
            "PNS-2S",
            "BUS BOX 7\"",
            "EZ-2 CRATE",
            "SETTING TRAY",
            "EZ COVER",
            "N-70",
            "Donut Tray",
            "Cake Crate",
            "1.5L Coke Foot Crate",
            "7L Wilkins Foot Crate"
        ];

        // Initialize autocomplete for product input
        $(document).ready(function() {
            $("#product").autocomplete({
                source: function(request, response) {
                    const term = request.term.toLowerCase();
                    const matches = productList.filter(product => 
                        product.toLowerCase().includes(term)
                    );
                    response(matches);
                },
                minLength: 1,
                delay: 100,
                autoFocus: true,
                classes: {
                    "ui-autocomplete": "dropdown-menu",
                    "ui-menu-item": "dropdown-item"
                }
            });
        });

        // Image Handler
        document.getElementById('uploadImages').addEventListener('change', (e) => {
            const files = Array.from(e.target.files);

            // Check if adding these files would exceed the limit
            if (selectedImageFiles.length + files.length > MAX_IMAGES) {
                alert(`Maximum ${MAX_IMAGES} images allowed`);
                return;
            }

            // Store the files and create previews
            files.forEach(file => {
                if (!file.type.startsWith('image/')) return;

                selectedImageFiles.push(file);
                const reader = new FileReader();
                reader.onload = () => createImagePreview(reader.result, selectedImageFiles.length - 1);
                reader.readAsDataURL(file);
            });
        });

        // Video Handler
        document.getElementById('uploadVideos').addEventListener('change', (e) => {
            const files = Array.from(e.target.files);

            // Check if adding these files would exceed the limit
            if (selectedVideoFiles.length + files.length > MAX_VIDEOS) {
                alert(`Maximum ${MAX_VIDEOS} videos allowed`);
                return;
            }

            // Store the files and create previews
            files.forEach(file => {
                if (!file.type.startsWith('video/')) return;

                selectedVideoFiles.push(file);
                createVideoPreview(URL.createObjectURL(file), selectedVideoFiles.length - 1);
            });
        });

        // Preview Creation
        function createImagePreview(url, index) {
            const preview = document.createElement('div');
            preview.className = 'position-relative';
            preview.innerHTML = `
                <img src="${url}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                <button type="button" class="btn-close bg-danger position-absolute top-0 end-0 m-1" data-index="${index}"></button>
    `;
            preview.querySelector('button').onclick = (e) => removeImagePreview(e.target.dataset.index);
            document.getElementById('imagePreviews').appendChild(preview);
        }

        function createVideoPreview(url, index) {
            const preview = document.createElement('div');
            preview.className = 'position-relative';
            preview.innerHTML = `
                <video controls class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
            <source src="${url}" type="video/mp4">
        </video>
                <button type="button" class="btn-close bg-danger position-absolute top-0 end-0 m-1" data-index="${index}"></button>
    `;
            preview.querySelector('button').onclick = (e) => removeVideoPreview(e.target.dataset.index);
            document.getElementById('videoPreviews').appendChild(preview);
        }

        // Remove previews
        function removeImagePreview(index) {
            selectedImageFiles.splice(index, 1);
            refreshImagePreviews();
        }

        function removeVideoPreview(index) {
            selectedVideoFiles.splice(index, 1);
            refreshVideoPreviews();
        }

        // Refresh all previews
        function refreshImagePreviews() {
            const container = document.getElementById('imagePreviews');
            container.innerHTML = '';
            
            selectedImageFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = () => createImagePreview(reader.result, index);
                reader.readAsDataURL(file);
            });
        }

        function refreshVideoPreviews() {
            const container = document.getElementById('videoPreviews');
            container.innerHTML = '';
            
            selectedVideoFiles.forEach((file, index) => {
                createVideoPreview(URL.createObjectURL(file), index);
            });
        }

        // Add form submission handler
        document.querySelector('form').addEventListener('submit', function(e) {
            // Create new FormData from the form
            const formData = new FormData(this);
            
            // Clear existing files and add our selected files
            formData.delete('uploadImages[]');
            formData.delete('uploadVideos[]');
            
            selectedImageFiles.forEach(file => {
                formData.append('uploadImages[]', file);
            });
            
            selectedVideoFiles.forEach(file => {
                formData.append('uploadVideos[]', file);
            });
            
            // Update the form data before submission
            // Note: This approach doesn't require DataTransfer API
        });

        // Check for any success/error messages in the session
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['success_message'])): ?>
                showNotification('<?= htmlspecialchars($_SESSION['success_message']) ?>', 'success');
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                showNotification('<?= htmlspecialchars($_SESSION['error_message']) ?>', 'danger');
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        });

        // Notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4`;
            notification.style.zIndex = '9999';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 150);
            }, 5000);
        }
    </script>

    <script>
        document.getElementById('autofillButton').addEventListener('click', function () {
            // Helper function for random integer generation
            const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

            // Helper function for random decimal generation
            const randomDecimal = (min, max, decimals = 1) => {
                const value = min + Math.random() * (max - min);
                return value.toFixed(decimals);
            };
            
            // Sample realistic values for different field types
            const productNames = ['Plastic Container', 'Bottle Cap', 'Phone Case', 'Water Bottle', 'Food Container', 'Toy Box'];
            const colors = ['Clear', 'Black', 'Blue', 'Red', 'White', 'Gray', 'Green', 'Yellow'];
            const machines = ['ARB 50', 'SUM 260C', 'SUM 350', 'MIT 650D', 'TOS 650A', 'CLF 750A', 'CLF 750B'];
            const materialTypes = ['PP', 'PE', 'HDPE', 'PET', 'PC', 'ABS', 'PVC'];
            const materialBrands = ['Polypropylene A', 'Polyethylene B', 'Polycarbonate C', 'ABS Premium', 'PolyBlend X'];
            const colorants = ['None', 'Blue Dye', 'Carbon Black', 'Titanium White', 'Red Pigment', 'Green Dye'];
            const stabilizers = ['UV Stabilizer', 'Heat Stabilizer', 'Antioxidant', 'Light Stabilizer'];
            const coolingMedia = ['Chilled Water', 'Glycol Solution', 'Oil Cooling'];
            const heatingMedia = ['Hot Oil', 'Electrical', 'Steam'];
            const moldCodes = ['MC101', 'MC202', 'MC303', 'MC404', 'MC505'];
            const adjusterNames = ['John Smith', 'Jane Doe', 'Mike Johnson', 'Sarah Williams', 'Robert Brown'];
            
            // First, set the step attribute for number inputs to allow decimals
            document.querySelectorAll('input[type="number"]').forEach(input => {
                if (!input.hasAttribute('step') || input.getAttribute('step') === '1') {
                    input.setAttribute('step', '0.01');
                }
            });
            
            // Populate fields based on their ID or name
            document.querySelectorAll('input, select, textarea').forEach(field => {
                // Skip the date and time inputs so they keep the real-time values
                if (field.id === 'currentDate' || field.id === 'currentTime') {
                    return;
                }
                
                const fieldId = field.id.toLowerCase();
                const fieldName = field.name.toLowerCase();
                
                // Handle different field types
                if (field.type === 'select-one') {
                    // For select elements, choose a random option
                    if (field.options.length > 0) {
                        field.selectedIndex = randomInt(0, field.options.length - 1);
                    }
                    return;
                }
                
                // For specific field types
                if (fieldName.includes('machine') || fieldId.includes('machine')) {
                    field.value = machines[randomInt(0, machines.length - 1)];
                }
                else if (fieldName.includes('product') || fieldId.includes('product')) {
                    field.value = productNames[randomInt(0, productNames.length - 1)];
                }
                else if (fieldName.includes('color') || fieldId.includes('color')) {
                    field.value = colors[randomInt(0, colors.length - 1)];
                }
                else if (fieldName.includes('runnumber') || fieldId.includes('runnumber')) {
                    field.value = 'RN' + randomInt(1000, 9999);
                }
                else if (fieldName.includes('category') || fieldId.includes('category')) {
                    field.value = ['Containers', 'Caps', 'Accessories', 'Packaging', 'Automotive'][randomInt(0, 4)];
                }
                else if (fieldName.includes('irn') || fieldId.includes('irn')) {
                    field.value = 'IRN' + randomInt(10000, 99999);
                }
                else if (fieldName.includes('moldname') || fieldId.includes('moldname')) {
                    field.value = 'MOLD-' + String.fromCharCode(65 + randomInt(0, 25)) + randomInt(100, 999);
                }
                else if (fieldName.includes('prodno') || fieldId.includes('prodno')) {
                    field.value = 'PN-' + randomInt(1000, 9999);
                }
                else if (fieldName.includes('cavity') || fieldId.includes('cavity')) {
                    field.value = randomInt(1, 16);
                }
                else if (fieldName.includes('weight') || fieldId.includes('weight')) {
                    field.value = randomDecimal(10, 500, 1);
                }
                else if (fieldName.includes('type') && (fieldName.includes('material') || fieldId.includes('material'))) {
                    field.value = materialTypes[randomInt(0, materialTypes.length - 1)];
                }
                else if (fieldName.includes('brand') && (fieldName.includes('material') || fieldId.includes('material'))) {
                    field.value = materialBrands[randomInt(0, materialBrands.length - 1)];
                }
                else if (fieldName.includes('mix') && (fieldName.includes('material') || fieldId.includes('material'))) {
                    field.value = randomDecimal(1, 100, 2);
                }
                else if (fieldName.includes('colorant') || fieldId.includes('colorant')) {
                    field.value = colorants[randomInt(0, colorants.length - 1)];
                }
                else if (fieldName.includes('stabilizer') || fieldId.includes('stabilizer')) {
                    field.value = stabilizers[randomInt(0, stabilizers.length - 1)];
                }
                else if (fieldName.includes('dosage') || fieldId.includes('dosage')) {
                    field.value = randomDecimal(0.5, 10, 1) + '%';
                }
                else if (fieldName.includes('mold-code') || fieldId.includes('mold-code')) {
                    field.value = moldCodes[randomInt(0, moldCodes.length - 1)];
                }
                else if (fieldName.includes('clamping') || fieldId.includes('clamping')) {
                    field.value = randomDecimal(20, 200, 1) + ' tons';
                }
                else if (fieldName.includes('cooling') || fieldId.includes('cooling')) {
                    field.value = coolingMedia[randomInt(0, coolingMedia.length - 1)];
                }
                else if (fieldName.includes('heating') || fieldId.includes('heating')) {
                    field.value = heatingMedia[randomInt(0, heatingMedia.length - 1)];
                }
                else if (fieldName.includes('operation') || fieldId.includes('operation')) {
                    field.value = ['Auto', 'Semi-Auto', 'Manual'][randomInt(0, 2)];
                }
                else if (fieldName.includes('time')) {
                    // Time values (seconds) - more realistic ranges with decimals
                    field.value = randomDecimal(0.5, 30, 1);
                }
                else if (fieldName.includes('temperature') || fieldName.includes('temp') || 
                        fieldId.includes('temperature') || fieldId.includes('temp') || 
                        fieldName.includes('zone') || fieldId.includes('zone')) {
                    // Temperature values with decimals
                    field.value = randomDecimal(30, 250, 1);
                }
                else if (fieldName.includes('pressure') || fieldId.includes('pressure')) {
                    // Pressure values with decimals
                    field.value = randomDecimal(10, 200, 1);
                }
                else if (fieldName.includes('speed') || fieldId.includes('speed')) {
                    // Speed values with decimals
                    field.value = randomDecimal(10, 100, 1);
                }
                else if (fieldName.includes('position') || fieldId.includes('position')) {
                    // Position values with decimals
                    field.value = randomDecimal(10, 150, 2);
                }
                else if (fieldName.includes('rpm') || fieldId.includes('rpm')) {
                    // RPM values with decimals
                    field.value = randomDecimal(30, 180, 1);
                }
                else if (fieldName.includes('adjuster') || fieldId.includes('adjuster')) {
                    // Keep the current adjuster name since it's usually the logged-in user
                    if (!field.value) {
                        field.value = adjusterNames[randomInt(0, adjusterNames.length - 1)];
                    }
                }
                else if (fieldName.includes('qae') || fieldId.includes('qae')) {
                    // QA Engineer name
                    field.value = 'QA ' + adjusterNames[randomInt(0, adjusterNames.length - 1)];
                }
                else if (field.tagName.toLowerCase() === 'textarea') {
                    field.value = 'Production run completed with standard parameters. All quality checks passed. Material batch: MB-' + randomInt(1000, 9999);
                }
                else if (field.type === 'number') {
                    // Generic number fields get appropriate random values based on field size with decimals
                    if (fieldName.includes('cushion')) {
                        field.value = randomDecimal(3, 10, 2);
                    } else {
                        field.value = randomDecimal(1, 100, 2);
                    }
                }
                else if (field.type === 'text' && !field.value) {
                    // Generic text fields that haven't been set yet
                    field.value = 'Sample-' + randomInt(1000, 9999);
                }
            });
        });
    </script>

    <!-- JavaScript for real-time date and time -->
    <script>
        // Function to update date and time fields with real-time values
        function updateDateTime() {
            const now = new Date();
            
            // Format date as YYYY-MM-DD for the date input
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            
            // Format time as HH:MM (without seconds)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const formattedTime = `${hours}:${minutes}`;
            
            // Update input values
            document.getElementById('currentDate').value = formattedDate;
            document.getElementById('currentTime').value = formattedTime;
        }
        
        // Update date and time immediately when page loads
        updateDateTime();
        
        // Update date and time every minute
        setInterval(updateDateTime, 60000);
    </script>

    <script>
    // Add form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const requiredFields = form.querySelectorAll('[required]');
        
        // Add validation styles
        requiredFields.forEach(field => {
            field.classList.add('required-field');
            const label = field.previousElementSibling;
            if (label) {
                label.innerHTML += ' <span class="text-danger">*</span>';
            }
        });

        // Add input validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill in all required fields', 'danger');
            }
        });

        // Add real-time validation
        requiredFields.forEach(field => {
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });
        });

        // Add number input validation
        const numberInputs = form.querySelectorAll('input[type="number"]');
        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                const value = parseFloat(this.value);
                const min = parseFloat(this.min);
                const max = parseFloat(this.max);
                
                if (value < min) {
                    this.value = min;
                } else if (value > max) {
                    this.value = max;
                }
            });
        });

        // Add section collapse/expand all functionality
        const addCollapseAllButton = () => {
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'mb-3';
            buttonContainer.innerHTML = `
                <button type="button" class="btn btn-outline-secondary me-2" id="expandAll">
                    <i class="fas fa-expand-alt"></i> Expand All
                </button>
                <button type="button" class="btn btn-outline-secondary" id="collapseAll">
                    <i class="fas fa-compress-alt"></i> Collapse All
                </button>
            `;
            form.insertBefore(buttonContainer, form.firstChild);

            document.getElementById('expandAll').addEventListener('click', () => {
                document.querySelectorAll('.collapse').forEach(collapse => {
                    bootstrap.Collapse.getInstance(collapse)?.show();
                });
            });

            document.getElementById('collapseAll').addEventListener('click', () => {
                document.querySelectorAll('.collapse').forEach(collapse => {
                    bootstrap.Collapse.getInstance(collapse)?.hide();
                });
            });
        };

        addCollapseAllButton();

        // Add form progress indicator
        const addProgressIndicator = () => {
            const sections = document.querySelectorAll('.collapse');
            const progressContainer = document.createElement('div');
            progressContainer.className = 'progress mb-4';
            progressContainer.innerHTML = `
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            `;
            form.insertBefore(progressContainer, form.firstChild);

            const updateProgress = () => {
                const filledFields = form.querySelectorAll('input[required]:not([value=""]), select[required]:not([value=""]), textarea[required]:not([value=""])').length;
                const totalFields = requiredFields.length;
                const progress = (filledFields / totalFields) * 100;
                progressContainer.querySelector('.progress-bar').style.width = `${progress}%`;
                progressContainer.querySelector('.progress-bar').setAttribute('aria-valuenow', progress);
            };

            form.addEventListener('input', updateProgress);
            updateProgress();
        };

        addProgressIndicator();

        // Add form autosave
        const addAutosave = () => {
            const AUTOSAVE_KEY = 'parameter_form_autosave';
            
            const saveForm = () => {
                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });
                localStorage.setItem(AUTOSAVE_KEY, JSON.stringify(data));
            };

            const loadForm = () => {
                const saved = localStorage.getItem(AUTOSAVE_KEY);
                if (saved) {
                    const data = JSON.parse(saved);
                    Object.entries(data).forEach(([key, value]) => {
                        const field = form.querySelector(`[name="${key}"]`);
                        if (field) {
                            field.value = value;
                        }
                    });
                    updateProgress();
                }
            };

            form.addEventListener('input', saveForm);
            loadForm();

            // Clear autosave on successful submission
            form.addEventListener('submit', () => {
                localStorage.removeItem(AUTOSAVE_KEY);
            });
        };

        addAutosave();
    });

    // Add styles
    const style = document.createElement('style');
    style.textContent = `
        .required-field {
            border-left: 3px solid #dc3545;
        }
        .required-field.is-valid {
            border-left: 3px solid #198754;
        }
        .progress {
            height: 10px;
        }
        .collapse {
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Autocomplete styling */
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            background-color: #fff;
            z-index: 9999;
        }
        
        .ui-menu-item {
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            font-size: 0.875rem;
        }
        
        .ui-menu-item:last-child {
            border-bottom: none;
        }
        
        .ui-menu-item:hover,
        .ui-menu-item.ui-state-focus {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .ui-menu-item.ui-state-active {
            background-color: #0d6efd;
            color: #fff;
        }
        
        .ui-helper-hidden-accessible {
            display: none;
        }
    `;
    document.head.appendChild(style);
    </script>
</body>

</html>