<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Injection Molding Parameter Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-primary-subtle">
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-light text-center">
                <h2>Injection Molding Parameters</h2>
            </div>
            <div class="card-body">
                <form action="submit.php" method="POST">
                    <!-- Section 1: Product and Machine Information -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseProductMachine"
                            role="button" aria-expanded="false" aria-controls="collapseProductMachine">
                            Product and Machine Information
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseProductMachine">
                        <div class="row mb-3 row-cols-1 row-cols-sm-3">
                            <div class="col">
                                <label for="Date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="Date" name="Date">
                            </div>
                            <div class="col">
                                <label for="Time" class="form-label">Time</label>
                                <input type="time" class="form-control" id="Time" name="Time">
                            </div>
                            <div class="col">
                                <label for="MachineName" class="form-label">Machine</label>
                                <input type="text" class="form-control" id="MachineName" name="MachineName"
                                    placeholder="Enter Machine Name">
                            </div>
                            <div class="col">
                                <label for="RunNumber" class="form-label">Run No.</label>
                                <input type="text" class="form-control" id="RunNumber" name="RunNumber"
                                    placeholder="Enter Run Number">
                            </div>
                            <div class="col">
                                <label for="Category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="Category" name="Category"
                                    placeholder="Enter Category">
                            </div>
                            <div class="col">
                                <label for="IRN" class="form-label">IRN</label>
                                <input type="text" class="form-control" id="IRN" name="IRN" placeholder="Enter IRN">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 2: Product Details -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseProductDetails"
                            role="button" aria-expanded="false" aria-controls="collapseProductDetails">
                            Product Details
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseProductDetails">
                        <div class="row mb-3 row-cols-1 row-cols-md-3 row-cols-sm-2">
                            <div class="col">
                                <label for="product" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product" name="product"
                                    placeholder="Enter Product">
                            </div>
                            <div class="col">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" name="color"
                                    placeholder="Enter Color">
                            </div>
                            <div class="col">
                                <label for="mold-name" class="form-label">Mold Name</label>
                                <input type="text" class="form-control" id="mold-name" name="mold-name"
                                    placeholder="Enter Mold Name">
                            </div>
                            <div class="col">
                                <label for="prodNo" class="form-label">Product Number</label>
                                <input type="text" class="form-control" id="prodNo" placeholder="Product Number">
                            </div>
                            <div class="col">
                                <label for="cavity" class="form-label">Number of Cavity (Active)</label>
                                <input type="number" class="form-control" id="cavity" placeholder="Number of Cavity">
                            </div>
                            <div class="col">
                                <label for="grossWeight" class="form-label">Gross Weight</label>
                                <input type="text" class="form-control" id="grossWeight" placeholder="Gross Weight (g)">
                            </div>
                            <div class="col">
                                <label for="netWeight" class="form-label">Net Weight</label>
                                <input type="text" class="form-control" id="netWeight" placeholder="Net Weight (g)">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 3: Material Composition -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseMaterialComposition"
                            role="button" aria-expanded="false" aria-controls="collapseMaterialComposition">
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
                                <input type="number" class="form-control" id="dryingtime"
                                    placeholder="Select Drying Time">
                            </div>
                            <div class="col">
                                <label for="dryingtemp" class="form-label">Drying Temperature</label>
                                <div class="input -group">
                                    <input type="number" class="form-control" id="dryingtemp"
                                        placeholder="Enter Temperature" min="0" max="300" step="0.1">
                                    <span class="input -group-text">°C</span>
                                </div>
                            </div>
                        </div>

                        <!-- Material 1 -->
                        <h6>Material 1</h6>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="type1" class="form-label">Type 1</label>
                                <input type="text" class="form-control" id="type1" placeholder="Enter Type 1">
                            </div>
                            <div class="col">
                                <label for="brand1" class="form-label">Brand 1</label>
                                <input type="text" class="form-control" id="brand1" placeholder="Enter Brand 1">
                            </div>
                            <div class="col">
                                <label for="mix1" class="form-label">Mixture 1</label>
                                <input type="number" class="form-control" id="mix1" placeholder="% Mixture 1">
                            </div>
                        </div>

                        <h6>Material 2</h6>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="type2" class="form-label">Type 2</label>
                                <input type="text" class="form-control" id="type2" placeholder="Enter Type 2">
                            </div>
                            <div class="col">
                                <label for="brand2" class="form-label">Brand 2</label>
                                <input type="text" class="form-control" id="brand2" placeholder="Enter Brand 2">
                            </div>
                            <div class="col">
                                <label for="mix2" class="form-label">Mixture 3</label>
                                <input type="number" class="form-control" id="mix2" placeholder="% Mixture 2">
                            </div>
                        </div>

                        <h6>Material 3</h6>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="type3" class="form-label">Type 3</label>
                                <input type="text" class="form-control" id="type3" placeholder="Enter Type 3">
                            </div>
                            <div class="col">
                                <label for="brand3" class="form-label">Brand 3</label>
                                <input type="text" class="form-control" id="brand3" placeholder="Enter Brand 3">
                            </div>
                            <div class="col">
                                <label for="mix3" class="form-label">Mixture 3</label>
                                <input type="number" class="form-control" id="mix3" placeholder="% Mixture 3">
                            </div>
                        </div>

                        <h6>Material 4</h6>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="type4" class="form-label">Type 4</label>
                                <input type="text" class="form-control" id="type4" placeholder="Enter Type 4">
                            </div>
                            <div class="col">
                                <label for="brand4" class="form-label">Brand 4</label>
                                <input type="text" class="form-control" id="brand4" placeholder="Enter Brand 4">
                            </div>
                            <div class="col">
                                <label for="mix4" class="form-label">Mixture 4</label>
                                <input type="number" class="form-control" id="mix4" placeholder="% Mixture 4">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 4: Colorant Details -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseColorantDetails"
                            role="button" aria-expanded="false" aria-controls="collapseColorantDetails">
                            Colorant Details
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <!-- colorants -->
                    <div class="collapse show" id="collapseColorantDetails">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="colorant" class="form-label">Colorant</label>
                                <input type="text" class="form-control" id="colorant" placeholder="Enter Colorant">
                            </div>
                            <div class="col">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" placeholder="Enter Colorant Color">
                            </div>
                            <div class="col">
                                <label for="colorant-dosage" class="form-label">Colorant Dosage</label>
                                <input type="text" class="form-control" id="colorant-dosage"
                                    placeholder="Enter Colorant Dosage">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="colorant-stabilizer" class="form-label">Colorant Stabilizer</label>
                                <input type="text" class="form-control" id="colorant-stabilizer"
                                    placeholder="Enter Colorant Stabilizer">
                            </div>
                            <div class="col">
                                <label for="colorant-stabilizer-dosage" class="form-label">Colorant Stabilizer Dosage
                                    (grams)</label>
                                <input type="text" class="form-control" id="colorant-stabilizer-dosage"
                                    placeholder="Enter Colorant Stabilizer Dosage (grams) ">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 5: Mold and Operation Specifications -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseMoldandOperationSpecs"
                            role="button" aria-expanded="false" aria-controls="collapseMoldandOperationSpecs">
                            Mold and Operation Specifications
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseMoldandOperationSpecs">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="mold-code" class="form-label">Mold Code</label>
                                <input type="text" class="form-control" id="mold-code" placeholder="Enter Mold Code">
                            </div>
                            <div class="col">
                                <label for="clamping-force" class="form-label">Clamping Force</label>
                                <input type="text" class="form-control" id="clamping-force"
                                    placeholder="Enter Clamping Force">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="operation-type" class="form-label">Operation</label>
                                <input type="text" class="form-control" id="operation-type"
                                    placeholder="Enter Type of Operation">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="cooling-media" class="form-label">Cooling Media</label>
                                <input type="text" class="form-control" id="cooling-media"
                                    placeholder="Enter Cooling Media">
                            </div>
                            <div class="col">
                                <label for="heating-media" class="form-label">Heating Media</label>
                                <input type="text" class="form-control" id="heating-media"
                                    placeholder="Enter Heating Media">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 6: Timer Parameters -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseCycleTime"
                            role="button" aria-expanded="false" aria-controls="collapseCycleTime">
                            Timer Parameters
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseCycleTime">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="fillingTime" class="form-label">Filling Time (s)</label>
                                <input type="number" class="form-control" id="fillingTime" placeholder="Filling Time">
                            </div>
                            <div class="col">
                                <label for="holdingTime" class="form-label">Holding Time (s)</label>
                                <input type="number" class="form-control" id="holdingTime" placeholder="Holding Time">
                            </div>
                            <div class="col">
                                <label for="moldOpenCloseTime" class="form-label">Mold Open-Close Time (s)</label>
                                <input type="number" class="form-control" id="moldOpenCloseTime"
                                    placeholder="Mold Open-Close Time">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="chargingTime" class="form-label">Charging Time (s)</label>
                                <input type="number" class="form-control" id="chargingTime" placeholder="Charging Time">
                            </div>
                            <div class="col">
                                <label for="coolingTime" class="form-label">Cooling Time (s)</label>
                                <input type="number" class="form-control" id="coolingTime" placeholder="Cooling Time">
                            </div>
                            <div class="col">
                                <label for="cycleTime" class="form-label">Cycle Time (s)</label>
                                <input type="number" class="form-control" id="cycleTime" placeholder="Cycle Time">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 7: Temperature Settings -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseTempSettings"
                            role="button" aria-expanded="false" aria-controls="collapseTempSettings">
                            Temperature Settings
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseTempSettings">
                        <!-- Barrel Heater Temperature Zones -->
                        <h5>
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseBarrelHeater"
                                role="button" aria-expanded="false" aria-controls="collapseBarrelHeater">
                                Barrel Heater Temperature Zones
                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                            </a>
                        </h5>
                        <div class="collapse show" id="collapseBarrelHeater">
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-col-lg-6 g-3">
                                <!-- Barrel Heater input s -->
                                <div class="col">
                                    <label for="nozzleHeaterZone0" class="form-label">Nozzle Heater Zone 0 (°C)</label>
                                    <input type="number" class="form-control" id="nozzleHeaterZone0"
                                        placeholder="Nozzle Heater Zone 0 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone1" class="form-label">Barrel Heater Zone 1 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone1"
                                        placeholder="Barrel Heater Zone 1 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone2" class="form-label">Barrel Heater Zone 2 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone2"
                                        placeholder="Barrel Heater Zone 2 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone3" class="form-label">Barrel Heater Zone 3 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone3"
                                        placeholder="Barrel Heater Zone 3 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone4" class="form-label">Barrel Heater Zone 4 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone4"
                                        placeholder="Barrel Heater Zone 4 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone5" class="form-label">Barrel Heater Zone 5 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone5"
                                        placeholder="Barrel Heater Zone 5 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone6" class="form-label">Barrel Heater Zone 6 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone6"
                                        placeholder="Barrel Heater Zone 6 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone7" class="form-label">Barrel Heater Zone 7 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone7"
                                        placeholder="Barrel Heater Zone 7 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone8" class="form-label">Barrel Heater Zone 8 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone8"
                                        placeholder="Barrel Heater Zone 8 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone9" class="form-label">Barrel Heater Zone 9 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone9"
                                        placeholder="Barrel Heater Zone 9 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone10" class="form-label">Barrel Heater Zone 10
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone10"
                                        placeholder="Barrel Heater Zone 10 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone11" class="form-label">Barrel Heater Zone 11
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone11"
                                        placeholder="Barrel Heater Zone 11 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone12" class="form-label">Barrel Heater Zone 12
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone12"
                                        placeholder="Barrel Heater Zone 12 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone13" class="form-label">Barrel Heater Zone 13
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone13"
                                        placeholder="Barrel Heater Zone 13 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone14" class="form-label">Barrel Heater Zone 14
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone14"
                                        placeholder="Barrel Heater Zone 14 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone15" class="form-label">Barrel Heater Zone 15
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone15"
                                        placeholder="Barrel Heater Zone 15 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone16" class="form-label">Barrel Heater Zone 16
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone16"
                                        placeholder="Barrel Heater Zone 16 (°C)">
                                </div>
                            </div>
                        </div>
                        <!-- Mold Heater Controller Temperatures -->
                        <h5>
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseMoldHeater"
                                role="button" aria-expanded="false" aria-controls="collapseMoldHeater">
                                Mold Heater Controller Temperatures
                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                            </a>
                        </h5>
                        <div class="collapse show" id="collapseMoldHeater">
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-col-lg-6 g-3">
                                <div class="col">
                                    <label for="barrelHeaterZone0" class="form-label">Barrel Heater Zone 0 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone0"
                                        placeholder="Barrel Heater Zone 0 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone1" class="form-label">Barrel Heater Zone 1 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone1"
                                        placeholder="Barrel Heater Zone 1 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone2" class="form-label">Barrel Heater Zone 2 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone2"
                                        placeholder="Barrel Heater Zone 2 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone3" class="form-label">Barrel Heater Zone 3 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone3"
                                        placeholder="Barrel Heater Zone 3 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone4" class="form-label">Barrel Heater Zone 4 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone4"
                                        placeholder="Barrel Heater Zone 4 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone5" class="form-label">Barrel Heater Zone 5 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone5"
                                        placeholder="Barrel Heater Zone 5 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone6" class="form-label">Barrel Heater Zone 6 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone6"
                                        placeholder="Barrel Heater Zone 6 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone7" class="form-label">Barrel Heater Zone 7 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone7"
                                        placeholder="Barrel Heater Zone 7 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone8" class="form-label">Barrel Heater Zone 8 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone8"
                                        placeholder="Barrel Heater Zone 8 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone9" class="form-label">Barrel Heater Zone 9 (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone9"
                                        placeholder="Barrel Heater Zone 9 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone10" class="form-label">Barrel Heater Zone 10
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone10"
                                        placeholder="Barrel Heater Zone 10 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone11" class="form-label">Barrel Heater Zone 11
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone11"
                                        placeholder="Barrel Heater Zone 11 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone12" class="form-label">Barrel Heater Zone 12
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone12"
                                        placeholder="Barrel Heater Zone 12 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone13" class="form-label">Barrel Heater Zone 13
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone13"
                                        placeholder="Barrel Heater Zone 13 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone14" class="form-label">Barrel Heater Zone 14
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone14"
                                        placeholder="Barrel Heater Zone 14 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone15" class="form-label">Barrel Heater Zone 15
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone15"
                                        placeholder="Barrel Heater Zone 15 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone16" class="form-label">Barrel Heater Zone 16
                                        (°C)</label>
                                    <input type="number" class="form-control" id="barrelHeaterZone16"
                                        placeholder="Barrel Heater Zone 16 (°C)">
                                </div>
                                <div class="col">
                                    <label for="MTCSetting" class="form-label">MTC Setting</label>
                                    <input type="number" class="form-control" id="MTCSetting" placeholder="MTC Setting">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 8: Molding Settings -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseMoldingSettings"
                            role="button" aria-expanded="false" aria-controls="collapseMoldingSettings">
                            Molding Settings
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseMoldingSettings">
                        <!-- Mold Open -->
                        <h5>
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseMoldOpen"
                                role="button" aria-expanded="false" aria-controls="collapseMoldOpen">
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
                                    <input type="number" class="form-control" id="moldOpenPos1"
                                        placeholder="Position 1">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos2" class="form-label">Position 2</label>
                                    <input type="number" class="form-control" id="moldOpenPos2"
                                        placeholder="Position 2">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos3" class="form-label">Position 3</label>
                                    <input type="number" class="form-control" id="moldOpenPos3"
                                        placeholder="Position 3">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos4" class="form-label">Position 4</label>
                                    <input type="number" class="form-control" id="moldOpenPos4"
                                        placeholder="Position 4">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos5" class="form-label">Position 5</label>
                                    <input type="number" class="form-control" id="moldOpenPos5"
                                        placeholder="Position 5">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos6" class="form-label">Position 6</label>
                                    <input type="number" class="form-control" id="moldOpenPos6"
                                        placeholder="Position 6">
                                </div>
                            </div>
                            <!-- MO Speed -->
                            <h6>Speed</h6>
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                <div class="col">
                                    <label for="moldOpenSpd1" class="form-label">Speed 1</label>
                                    <input type="number" class="form-control" id="moldOpenSpd1" placeholder="Speed 1">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd2" class="form-label">Speed 2</label>
                                    <input type="number" class="form-control" id="moldOpenSpd2" placeholder="Speed 2">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd3" class="form-label">Speed 3</label>
                                    <input type="number" class="form-control" id="moldOpenSpd3" placeholder="Speed 3">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd4" class="form-label">Speed 4</label>
                                    <input type="number" class="form-control" id="moldOpenSpd4" placeholder="Speed 4">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd5" class="form-label">Speed 5</label>
                                    <input type="number" class="form-control" id="moldOpenSpd5" placeholder="Speed 5">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd6" class="form-label">Speed 6</label>
                                    <input type="number" class="form-control" id="moldOpenSpd6" placeholder="Speed 6">
                                </div>
                            </div>
                            <!-- MO Pressure -->
                            <h6>Pressure</h6>
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                <div class="col">
                                    <label for="moldOpenPressure1" class="form-label">Pressure 1</label>
                                    <input type="number" class="form-control" id="moldOpenPressure1"
                                        placeholder="Pressure 1">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPressure2" class="form-label">Pressure 2</label>
                                    <input type="number" class="form-control" id="moldOpenPressure2"
                                        placeholder="Pressure 2">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPressure3" class="form-label">Pressure 3</label>
                                    <input type="number" class="form-control" id="moldOpenPressure3"
                                        placeholder="Pressure 3">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPressure4" class="form-label">Pressure 4</label>
                                    <input type="number" class="form-control" id="moldOpenPressure4"
                                        placeholder="Pressure 4">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPressure5" class="form-label">Pressure 5</label>
                                    <input type="number" class="form-control" id="moldOpenPressure5"
                                        placeholder="Pressure 5">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPressure6" class="form-label">Pressure 6</label>
                                    <input type="number" class="form-control" id="moldOpenPressure6"
                                        placeholder="Pressure 6">
                                </div>
                            </div>
                        </div>
                        <!-- Mold Close -->
                        <h5>
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseMoldClose"
                                role="button" aria-expanded="false" aria-controls="collapseMoldClose">
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
                                    <input type="number" class="form-control" id="moldClosePos1"
                                        placeholder="Position 1">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos2" class="form-label">Position 2</label>
                                    <input type="number" class="form-control" id="moldOpenPos2"
                                        placeholder="Position 2">
                                </div>
                                <div class="col">
                                    <label for="moldClosePos3" class="form-label">Position 3</label>
                                    <input type="number" class="form-control" id="moldClosePos3"
                                        placeholder="Position 3">
                                </div>
                                <div class="col">
                                    <label for="moldClosePos4" class="form-label">Position 4</label>
                                    <input type="number" class="form-control" id="moldClosePos4"
                                        placeholder="Position 4">
                                </div>
                                <div class="col">
                                    <label for="moldClosePos5" class="form-label">Position 5</label>
                                    <input type="number" class="form-control" id="moldClosePos5"
                                        placeholder="Position 5">
                                </div>
                                <div class="col">
                                    <label for="moldClosePos6" class="form-label">Position 6</label>
                                    <input type="number" class="form-control" id="moldClosePos6"
                                        placeholder="Position 6">
                                </div>
                            </div>
                            <!-- MO Speed -->
                            <h6>Speed</h6>
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                <div class="col">
                                    <label for="moldCloseSpd1" class="form-label">Speed 1</label>
                                    <input type="number" class="form-control" id="moldCloseSpd1" placeholder="Speed 1">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd2" class="form-label">Speed 2</label>
                                    <input type="number" class="form-control" id="moldCloseSpd2" placeholder="Speed 2">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd3" class="form-label">Speed 3</label>
                                    <input type="number" class="form-control" id="moldCloseSpd3" placeholder="Speed 3">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd4" class="form-label">Speed 4</label>
                                    <input type="number" class="form-control" id="moldCloseSpd4" placeholder="Speed 4">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd5" class="form-label">Speed 5</label>
                                    <input type="number" class="form-control" id="moldCloseSpd5" placeholder="Speed 5">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd6" class="form-label">Speed 6</label>
                                    <input type="number" class="form-control" id="moldCloseSpd6" placeholder="Speed 6">
                                </div>
                            </div>
                            <!-- MO Pressure -->
                            <h6>Pressure</h6>
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                                <div class="col">
                                    <label for="moldClosePressure1" class="form-label">Pressure 1</label>
                                    <input type="number" class="form-control" id="moldClosePressure1"
                                        placeholder="Pressure 1">
                                </div>
                                <div class="col">
                                    <label for="moldClosePressure2" class="form-label">Pressure 2</label>
                                    <input type="number" class="form-control" id="moldClosePressure2"
                                        placeholder="Pressure 2">
                                </div>
                                <div class="col">
                                    <label for="moldClosePressure3" class="form-label">Pressure 3</label>
                                    <input type="number" class="form-control" id="moldClosePressure3"
                                        placeholder="Pressure 3">
                                </div>
                                <div class="col">
                                    <label for="pclorlp" class="form-label">PLC/LP</label>
                                    <input type="text" class="form-control" id="pclorlp" placeholder="PLC/LP">
                                </div>
                                <div class="col">
                                    <label for="pchorhp" class="form-label">PCH/HP</label>
                                    <input type="text" class="form-control" id="pchorhp" placeholder="PCH/HP">
                                </div>
                                <div class="col">
                                    <label for="lowPresTimeLimit" class="form-label">Low Pressure Time Limit</label>
                                    <input type="text" class="form-control" id="lowPresTimeLimit"
                                        placeholder="Low Pressure Time Limit">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 9: Plasticizing Parameters -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapsePlasticizingParams"
                            role="button" aria-expanded="false" aria-controls="collapsePlasticizingParams">
                            Plasticizing Parameters
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapsePlasticizingParams">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="screwRPM1" class="form-label">Screw RPM 1</label>
                                <input type="number" class="form-control" id="screwRPM1" placeholder="Screw RPM 1">
                            </div>
                            <div class="col">
                                <label for="screwRPM2" class="form-label">Screw RPM 2</label>
                                <input type="number" class="form-control" id="screwRPM2" placeholder="Screw RPM 2">
                            </div>
                            <div class="col">
                                <label for="screwRPM3" class="form-label">Screw RPM 3</label>
                                <input type="number" class="form-control" id="screwRPM3" placeholder="Screw RPM 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="screwSpeed1" class="form-label">Screw Speed 1</label>
                                <input type="number" class="form-control" id="screwSpeed1" placeholder="Screw Speed 1">
                            </div>
                            <div class="col">
                                <label for="screwSpeed2" class="form-label">Screw Speed 2</label>
                                <input type="number" class="form-control" id="screwSpeed2" placeholder="Screw Speed 2">
                            </div>
                            <div class="col">
                                <label for="screwSpeed3" class="form-label">Screw Speed 3</label>
                                <input type="number" class="form-control" id="screwSpeed3" placeholder="Screw Speed 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="plastPressure1" class="form-label">Plast Pressure 1</label>
                                <input type="number" class="form-control" id="plastPressure1"
                                    placeholder="Plast Pressure 1">
                            </div>
                            <div class="col">
                                <label for="plastPressure2" class="form-label">Plast Pressure 2</label>
                                <input type="number" class="form-control" id="plastPressure2"
                                    placeholder="Plast Pressure 2">
                            </div>
                            <div class="col">
                                <label for="plastPressure3" class="form-label">Plast Pressure 3</label>
                                <input type="number" class="form-control" id="plastPressure3"
                                    placeholder="Plast Pressure 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="plastPosition1" class="form-label">Plast Position 1</label>
                                <input type="number" class="form-control" id="plastPosition1"
                                    placeholder="Plast Position 1">
                            </div>
                            <div class="col">
                                <label for="plastPosition2" class="form-label">Plast Position 2</label>
                                <input type="number" class="form-control" id="plastPosition2"
                                    placeholder="Plast Position 2">
                            </div>
                            <div class="col">
                                <label for="plastPosition3" class="form-label">Plast Position 3</label>
                                <input type="number" class="form-control" id="plastPosition3"
                                    placeholder="Plast Position 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="backPressure1" class="form-label">Back Pressure 1</label>
                                <input type="number" class="form-control" id="backPressure1"
                                    placeholder="Back Pressure 1">
                            </div>
                            <div class="col">
                                <label for="backPressure2" class="form-label">Back Pressure 2</label>
                                <input type="number" class="form-control" id="backPressure2"
                                    placeholder="Back Pressure 2">
                            </div>
                            <div class="col">
                                <label for="backPressure3" class="form-label">Back Pressure 3</label>
                                <input type="number" class="form-control" id="backPressure3"
                                    placeholder="Back Pressure 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="backPressureStartPosition" class="form-label">Back Pressure Start
                                    Position</label>
                                <input type="number" class="form-control" id="backPressureStartPosition"
                                    placeholder="Back Pressure Start Position">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 10: Injection Parameters -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseInjectionParams"
                            role="button" aria-expanded="false" aria-controls="collapseInjectionParams">
                            Injection Parameters
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseInjectionParams">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="RecoveryPOS" class="form-label">Recovery Position (mm)</label>
                                <input type="number" class="form-control" id="recoveryPOS"
                                    placeholder="Recovery Position">
                            </div>
                            <div class="col">
                                <label for="SecondStagePosition" class="form-label">Second Stage Position (mm)</label>
                                <input type="text" class="form-control" id="secondStagePosition"
                                    placeholder="Second Stage Position">
                            </div>
                            <div class="col">
                                <label for="Cushion" class="form-label">Cushion (mm)</label>
                                <input type="text" class="form-control" id="cushion" placeholder="Cushion">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="ScrewPosition1" class="form-label">Screw Position 1</label>
                                <input type="text" class="form-control" id="screwPosition1"
                                    placeholder="Screw Position 1">
                            </div>
                            <div class="col">
                                <label for="ScrewPosition2" class="form-label">Screw Position 2</label>
                                <input type="text" class="form-control" id="screwPosition2"
                                    placeholder="Screw Position 2">
                            </div>
                            <div class="col">
                                <label for="ScrewPosition3" class="form-label">Screw Position 3</label>
                                <input type="text" class="form-control" id="screwPosition3"
                                    placeholder="Screw Position 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="INJSpeed1" class="form-label">Injection Speed 1</label>
                                <input type="text" class="form-control" id="injectionSpeed1"
                                    placeholder="Injection Speed 1">
                            </div>
                            <div class="col">
                                <label for="INJSpeed2" class="form-label">Injection Speed 2</label>
                                <input type="text" class="form-control" id="injectionSpeed2"
                                    placeholder="Injection Speed 2">
                            </div>
                            <div class="col">
                                <label for="INJSpeed3" class="form-label">Injection Speed 3</label>
                                <input type="text" class="form-control" id="injectionSpeed3"
                                    placeholder="Injection Speed 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="INJPressure1" class="form-label">Injection Pressure 1</label>
                                <input type="text" class="form-control" id="injectionPressure1"
                                    placeholder="Injection Pressure 1">
                            </div>
                            <div class="col">
                                <label for="INJPressure2" class="form-label">Injection Pressure 2</label>
                                <input type="text" class="form-control" id="injectionPressure2"
                                    placeholder="Injection Pressure 2">
                            </div>
                            <div class="col">
                                <label for="INJPressure3" class="form-label">Injection Pressure 3</label>
                                <input type="text" class="form-control" id="injectionPressure3"
                                    placeholder="Injection Pressure 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="SuckBackPos" class="form-label">Suck Back Position</label>
                                <input type="text" class="form-control" id="suckBackPosition"
                                    placeholder="Suck Back Position">
                            </div>
                            <div class="col">
                                <label for="SuckBackSpeed" class="form-label">Suck Back Speed</label>
                                <input type="text" class="form-control" id="suckBackSpeed"
                                    placeholder="Suck Back Speed">
                            </div>
                            <div class="col">
                                <label for="SuckBackPres" class="form-label">Suck Back Pressure</label>
                                <input type="text" class="form-control" id="suckBackPressure"
                                    placeholder="Suck Back Pressure">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="ScrewPosition4" class="form-label">Screw Position 4</label>
                                <input type="text" class="form-control" id="screwPosition4"
                                    placeholder="Screw Position 4">
                            </div>
                            <div class="col">
                                <label for="ScrewPosition5" class="form-label">Screw Position 5</label>
                                <input type="text" class="form-control" id="ScrewPosition5"
                                    placeholder="Screw Position 5">
                            </div>
                            <div class="col">
                                <label for="ScrewPosition6" class="form-label">Screw Position 6</label>
                                <input type="text" class="form-control" id="ScrewPosition6"
                                    placeholder="Screw Position 6">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="InjectionSpeed4" class="form-label">Injection Speed 4</label>
                                <input type="text" class="form-control" id="InjectionSpeed4"
                                    placeholder="Injection Speed 4">
                            </div>
                            <div class="col">
                                <label for="InjectionSpeed5" class="form-label">Injection Speed 5</label>
                                <input type="text" class="form-control" id="InjectionSpeed5"
                                    placeholder="Injection Speed 5">
                            </div>
                            <div class="col">
                                <label for="InjectionSpeed6" class="form-label">Injection Speed 6</label>
                                <input type="text" class="form-control" id="InjectionSpeed6"
                                    placeholder="Injection Speed 6">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="InjectionPressure4" class="form-label">Injection Pressure 4</label>
                                <input type="text" class="form-control" id="InjectionPressure4"
                                    placeholder="Injection Pressure 4">
                            </div>
                            <div class="col">
                                <label for="InjectionPressure5" class="form-label">Injection Pressure 5</label>
                                <input type="text" class="form-control" id="InjectionPressure5"
                                    placeholder="Injection Pressure 5">
                            </div>
                            <div class="col">
                                <label for="InjectionPressure6" class="form-label">Injection Pressure 6</label>
                                <input type="text" class="form-control" id="InjectionPressure6"
                                    placeholder="Injection Pressure 6">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="SprueBreak" class="form-label">Sprue Break</label>
                                <input type="text" class="form-control" id="SprueBreak" placeholder="Sprue Break">
                            </div>
                            <div class="col">
                                <label for="SprueBreakTime" class="form-label">Sprue Break Time</label>
                                <input type="text" class="form-control" id="SprueBreakTime"
                                    placeholder="Sprue Break Time">
                            </div>
                            <div class="col">
                                <label for="InjectionDelay" class="form-label">Injection Delay</label>
                                <input type="text" class="form-control" id="InjectionDelay"
                                    placeholder="Injection Delay">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="HoldingPres1" class="form-label">Holding Pressure 1</label>
                                <input type="text" class="form-control" id="HoldingPres1"
                                    placeholder="Holding Pressure 1">
                            </div>
                            <div class="col">
                                <label for="HoldingPres2" class="form-label">Sprue Break Time</label>
                                <input type="text" class="form-control" id="HoldingPres2"
                                    placeholder="Holding Pressure 2">
                            </div>
                            <div class="col">
                                <label for="HoldingPres3" class="form-label">Holding Pressure 3</label>
                                <input type="text" class="form-control" id="HoldingPres3"
                                    placeholder="Holding Pressure 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="HoldingSpeed1" class="form-label">Holding Speed 1</label>
                                <input type="text" class="form-control" id="HoldingSpeed1"
                                    placeholder="Holding Speed 1">
                            </div>
                            <div class="col">
                                <label for="HoldingSpeed2" class="form-label">Holding Speed 2</label>
                                <input type="text" class="form-control" id="HoldingSpeed2"
                                    placeholder="Holding Speed 2">
                            </div>
                            <div class="col">
                                <label for="HoldingSpeed3" class="form-label">Holding Speed 3</label>
                                <input type="text" class="form-control" id="HoldingSpeed3"
                                    placeholder="Holding Speed 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="HoldingTime1" class="form-label">Holding Time 1</label>
                                <input type="text" class="form-control" id="HoldingTime1" placeholder="Holding Time 1">
                            </div>
                            <div class="col">
                                <label for="HoldingTime2" class="form-label">Holding Time 2</label>
                                <input type="text" class="form-control" id="HoldingTime2" placeholder="Holding Time 2">
                            </div>
                            <div class="col">
                                <label for="HoldingTime3" class="form-label">Holding Time 3</label>
                                <input type="text" class="form-control" id="HoldingTime3" placeholder="Holding Time 3">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 11: Ejection Parameters -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseEjectionParams"
                            role="button" aria-expanded="false" aria-controls="collapseEjectionParams">
                            Ejection Parameters
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseEjectionParams">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="AirBlowTimeA" class="form-label">Air Blow Time A</label>
                                <input type="text" class="form-control" id="airBlowTime A"
                                    placeholder="Air Blow Time A">
                            </div>
                            <div class="col">
                                <label for="AirBlowPositionA" class="form-label">Air Blow Position A</label>
                                <input type="text" class="form-control" id="airBlowPositionA"
                                    placeholder="Air Blow Position A">
                            </div>
                            <div class="col">
                                <label for="AB A Delay" class="form-label">Air Blow A Delay</label>
                                <input type="text" class="form-control" id="airBlowADelay"
                                    placeholder="Air Blow A Delay">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="AirBlowTimeB" class="form-label">Air Blow Time B</label>
                                <input type="text" class="form-control" id="airBlowTimeB" placeholder="Air Blow Time B">
                            </div>
                            <div class="col">
                                <label for="AirBlowPosB" class="form-label">Air Blow Position B</label>
                                <input type="text" class="form-control" id="aBlowPositionB"
                                    placeholder="Air Blow Position B">
                            </div>
                            <div class="col">
                                <label for="AirBlowBDelay" class="form-label">Air Blow B Delay</label>
                                <input type="text" class="form-control" id="airBlowBDelay"
                                    placeholder="Air Blow B Delay">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorForwardPOS1" class="form-label">Ejector Forward Position
                                    1</label>
                                <input type="text" class="form-control" id="ejectorForwardPOS1"
                                    placeholder="Ejector Forward Position 1">
                            </div>
                            <div class="col">
                                <label for="EjectorForwardPOS2" class="form-label">Ejector Forward Position
                                    2</label>
                                <input type="text" class="form-control" id="ejectorForwardPOS2"
                                    placeholder="Ejector Forward Position 2">
                            </div>
                            <div class="col">
                                <label for="EFSpeed1" class="form-label">Ejector Forward Speed 1</label>
                                <input type="text" class="form-control" id="ejectorForwardSpeed1"
                                    placeholder="Ejector Forward Speed 1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorRetractPosition1" class="form-label">Ejector Retract Position
                                    1</label>
                                <input type="text" class="form-control" id="ejectorRetractPosition1"
                                    placeholder="Ejector Retract Position 1">
                            </div>
                            <div class="col">
                                <label for="EjectorRetractPosition2" class="form-label">Ejector Retract Position
                                    2</label>
                                <input type="text" class="form-control" id="ejectorRetractPosition2"
                                    placeholder="Ejector Retract Position 2">
                            </div>
                            <div class="col">
                                <label for="Ejector Retract Speed1" class="form-label">Ejector Retract Speed
                                    1</label>
                                <input type="text" class="form-control" id="ejectorRetractSpeed1"
                                    placeholder="Ejector Retract Speed 1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorForwardPosition" class="form-label">Ejector Forward
                                    Position</label>
                                <input type="text" class="form-control" id="ejectorForwardPosition"
                                    placeholder="Ejector Forward Position">
                            </div>
                            <div class="col">
                                <label for="EjectorForwardTime" class="form-label">Ejector Forward Time</label>
                                <input type="text" class="form-control" id="ejectorForwardTime"
                                    placeholder="Ejector Forward Time">
                            </div>
                            <div class="col">
                                <label for="EjectorRetractPosition" class="form-label">Ejector Retract
                                    Position</label>
                                <input type="text" class="form-control" id="ejectorRetractPosition"
                                    placeholder="Ejector Retract Position">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorRetractTime" class="form-label">Ejector Retract Time</label>
                                <input type="text" class="form-control" id="ejectorRetractTime"
                                    placeholder="Ejector Retract Time">
                            </div>
                            <div class="col">
                                <label for="EjectorForwardSpeed2" class="form-label">Ejector Forward Speed 2</label>
                                <input type="text" class="form-control" id="ejectorForwardSpeed2"
                                    placeholder="Ejector Forward Speed2">
                            </div><!--sub field-->
                            <div class="col">
                                <label for="EjectorForwardPressure1" class="form-label">Ejector Forward Pressure
                                    1</label>
                                <input type="text" class="form-control" id="ejectorForwardPressure1"
                                    placeholder="Ejector Forward Pressure 1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorForwardSpeed2" class="form-label">Ejector Forward Speed 2</label>
                                <input type="text" class="form-control" id="ejectorForwardSpeed2"
                                    placeholder="Ejector Forward Speed 2">
                            </div>
                            <!--sub field-->
                            <div class="col">
                                <label for="EjectorForward" class="form-label">Ejector Forward</label>
                                <input type="text" class="form-control" id="ejectorForward"
                                    placeholder="Ejector Forward">
                            </div>
                            <div class="col">
                                <label for="EjectorRetractSpeed2" class="form-label">Ejector Retract Speed 2</label>
                                <input type="text" class="form-control" id="ejectorRetractSpeed2"
                                    placeholder="Ejector Retract Speed 2">
                            </div>
                        </div>
                        <!--sub field-->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorRetractPressure1" class="form-label">Ejector Retract Pressure
                                    1</label>
                                <input type="text" class="form-control" id="ejectorRetractPressure1"
                                    placeholder="Ejector Retract Pressure 1">
                            </div>
                            <div class="col">
                                <label for="EjectorRetractSpeed2" class="form-label">Ejector Retract Speed 2</label>
                                <input type="text" class="form-control" id="ejectorRetractSpeed2"
                                    placeholder="Ejector Retract Speed 2">
                            </div>
                            <!--sub field-->
                            <div class="col">
                                <label for="EjectorRetract" class="form-label">Ejector Retract</label>
                                <input type="text" class="form-control" id="ejectorRetract"
                                    placeholder="Ejector Retract">
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 12: Core Settings -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseCorePull" role="button"
                            aria-expanded="false" aria-controls="collapseCorePull">
                            Core Pull Settings
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseCorePull">
                        <h4>
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseCoreSetA"
                                role="button" aria-expanded="false" aria-controls="collapseSetA">
                                Core Set A
                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                            </a>
                        </h4>
                        <div class="collapse show" id="collapseCoreSetA">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="coreSetASequence" class="form-label">Core Set A Sequence</label>
                                    <input type="number" class="form-control" id="coreSetASequence"
                                        placeholder="Core Set A Sequence">
                                </div>
                                <div class="col">
                                    <label for="coreSetAPressure" class="form-label">Core Set A Pressure ()</label>
                                    <input type="number" class="form-control" id="coreSetAPressure"
                                        placeholder="Core Set A Pressure">
                                </div>
                                <div class="col">
                                    <label for="coreSetASpeed" class="form-label">Core Set A Speed</label>
                                    <input type="text" class="form-control" id="coreSetASpeed"
                                        placeholder="Core Set A Speed">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="coreSetAPosition" class="form-label">Core Set A Position</label>
                                    <input type="text" class="form-control" id="coreSetAPosition"
                                        placeholder="Core Set A Position">
                                </div>
                                <div class="col">
                                    <label for="coreSetATime" class="form-label">Core Set A Time</label>
                                    <input type="number" class="form-control" id="coreSetATime"
                                        placeholder="Core Set A Time">
                                </div>
                                <div class="col">
                                    <label for="coreSetALimitSwitch" class="form-label">Core Set A Limit
                                        Switch</label>
                                    <input type="text" class="form-control" id="coreSetALimitSwitch"
                                        placeholder="Core Set A Limit Switch">
                                </div>
                            </div>
                        </div>

                        <h4>
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseCorePullA"
                                role="button" aria-expanded="false" aria-controls="collapseCorePullA">
                                Core Pull A
                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                            </a>
                        </h4>
                        <div class="collapse show" id="collapseCorePullA">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="corePullASequence" class="form-label">Core Pull A Sequence</label>
                                    <input type="number" class="form-control" id="corePullASequence"
                                        placeholder="Core Pull A Sequence">
                                </div>
                                <div class="col">
                                    <label for="corePullAPressure" class="form-label">Core Pull A Pressure
                                        ()</label>
                                    <input type="number" class="form-control" id="corePullAPressure"
                                        placeholder="Core Pull A Pressure">
                                </div>
                                <div class="col">
                                    <label for="corePullASpeed" class="form-label">Core Pull A Speed</label>
                                    <input type="text" class="form-control" id="corePullASpeed"
                                        placeholder="Core Pull A Speed">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="corePullAPosition" class="form-label">Core Pull A Position</label>
                                    <input type="number" class="form-control" id="corePullAPosition"
                                        placeholder="Core Pull A Position">
                                </div>
                                <div class="col">
                                    <label for="corePullATime" class="form-label">Core Pull A Time</label>
                                    <input type="number" class="form-control" id="corePullATime"
                                        placeholder="Core Pull A Time">
                                </div>
                                <div class="col">
                                    <label for="corePullALimitSwitch" class="form-label">Core Pull A Limit
                                        Switch</label>
                                    <input type="text" class="form-control" id="corePullALimitSwitch"
                                        placeholder="Core Pull A Limit Switch">
                                </div>
                            </div>
                        </div>

                        <h4>
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseCoreSetB"
                                role="button" aria-expanded="false" aria-controls="collapseSetA">
                                Core Set B
                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                            </a>
                        </h4>
                        <div class="collapse show" id="collapseCoreSetB">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="coreSetBSequence" class="form-label">Core Set B Sequence</label>
                                    <input type="number" class="form-control" id="coreSetBSequence"
                                        placeholder="Core Set B Sequence">
                                </div>
                                <div class="col">
                                    <label for="coreSetBPressure" class="form-label">Core Set B Pressure
                                        ()</label>
                                    <input type="number" class="form-control" id="coreSetBPressure"
                                        placeholder="Core Set B Pressure">
                                </div>
                                <div class="col">
                                    <label for="coreSetBSpeed" class="form-label">Core Set B Speed</label>
                                    <input type="text" class="form-control" id="coreSetBSpeed"
                                        placeholder="Core Set B Speed">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="coreSetBPosition" class="form-label">Core Set B Position</label>
                                    <input type="text" class="form-control" id="coreSetBPosition"
                                        placeholder="Core Set B Position">
                                </div>
                                <div class="col">
                                    <label for="coreSetBTime" class="form-label">Core Set B Time</label>
                                    <input type="number" class="form-control" id="coreSetBTime"
                                        placeholder="Core Set B Time">
                                </div>
                                <div class="col">
                                    <label for="coreSetBLimitSwitch" class="form-label">Core Set B Limit
                                        Switch</label>
                                    <input type="text" class="form-control" id="coreSetBLimitSwitch"
                                        placeholder="Core Set B Limit Switch">
                                </div>
                            </div>
                        </div>
                        <h4>
                            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseCorePullB"
                                role="button" aria-expanded="false" aria-controls="collapseCoreSetB">
                                Core Pull B
                                <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                            </a>
                        </h4>
                        <div class="collapse show" id="collapseCorePullB">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="corePullBSequence" class="form-label">Core Pull B
                                        Sequence</label>
                                    <input type="number" class="form-control" id="corePullBSequence"
                                        placeholder="Core Pull B Sequence">
                                </div>
                                <div class="col">
                                    <label for="corePullBPressure" class="form-label">Core Pull B Pressure
                                        ()</label>
                                    <input type="number" class="form-control" id="corePullBPressure"
                                        placeholder="Core Pull B Pressure">
                                </div>
                                <div class="col">
                                    <label for="corePullBSpeed" class="form-label">Core Pull B Speed</label>
                                    <input type="text" class="form-control" id="corePullBSpeed"
                                        placeholder="Core Pull B Speed">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="corePullBPosition" class="form-label">Core Pull B
                                        Position</label>
                                    <input type="number" class="form-control" id="corePullBPosition"
                                        placeholder="Core Pull B Position">
                                </div>
                                <div class="col">
                                    <label for="corePullBTime" class="form-label">Core Pull B Time</label>
                                    <input type="number" class="form-control" id="corePullBTime"
                                        placeholder="Core Pull B Time">
                                </div>
                                <div class="col">
                                    <label for="corePullBLimitSwitch" class="form-label">Core Pull B Limit
                                        Switch</label>
                                    <input type="text" class="form-control" id="corePullBLimitSwitch"
                                        placeholder="Core Pull B Limit Switch">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 13: Additional Information -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseAdditionalInfo"
                            role="button" aria-expanded="false" aria-controls="collapseAdditionalInfo">
                            Additional Information
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseAdditionalInfo">
                        <div class="mb-3">
                            <label for="additionalInfo" class="form-label">Additional Info</label>
                            <textarea class="form-control" id="additionalInfo" rows="4"
                                placeholder="Enter any additional info"></textarea>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 14: Personnel -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapsePersonnel"
                            role="button" aria-expanded="false" aria-controls="collapsePersonnel">
                            Personnel
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapsePersonnel">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="adjuster" class="form-label">Adjuster Name</label>
                                <input type="text" class="form-control" id="adjuster" placeholder="Enter Adjuster Name">
                            </div>
                            <div class="col">
                                <label for="qae" class="form-label">Quality Assurance Engineer Name</label>
                                <input type="text" class="form-control" id="qae"
                                    placeholder="Enter Quality Assurance Engineer Name">
                            </div>

                        </div>
                    </div>
                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <!-- Section 15: Attachments --><!-- Section: Attachments -->
                    <h4>
                        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseAttachments"
                            role="button" aria-expanded="false" aria-controls="collapseAttachments">
                            Attachments
                            <i class="bi bi-chevron-down float-end"></i> <!-- Chevron icon -->
                        </a>
                    </h4>
                    <div class="collapse show" id="collapseAttachments">
                        <div class="row mb-3">
                            <!-- Upload Image -->
                            <div class="col-md-6">
                                <label for="uploadImage" class="form-label">Upload Image</label>
                                <input class="form-control" type="file" id="uploadImage" accept="image/*">
                                <small class="text-muted">Accepted formats: JPG, PNG, GIF</small>
                            </div>

                            <!-- Upload Video -->
                            <div class="col-md-6">
                                <label for="uploadVideo" class="form-label">Upload Video</label>
                                <input class="form-control" type="file" id="uploadVideo" accept="video/*">
                                <small class="text-muted">Accepted formats: MP4, AVI, MKV</small>
                            </div>
                        </div>

                        <!-- Preview Section for Uploaded Files -->
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Image Preview</label>
                                <img id="imagePreview" src="" class="img-fluid border rounded" alt="Image Preview"
                                    style="display: none;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Video Preview</label>
                                <video id="videoPreview" class="img-fluid border rounded" controls
                                    style="display: none;">
                                    <source src="" id="videoSource">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>

                    <!-- Add a horizontal line to separate sections -->
                    <hr class="my-4"> <!-- Adds some margin space around the horizontal line -->

                    <button type="button" id="autofillButton" class="btn btn-secondary mt-4">Autofill</button>


                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary mt-4">Submit</button>

                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.getElementById('uploadImage').addEventListener('change', function (event) {
                const imagePreview = document.getElementById('imagePreview');
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('uploadVideo').addEventListener('change', function (event) {
                const videoSource = document.getElementById('videoSource');
                const videoPreview = document.getElementById('videoPreview');
                const file = event.target.files[0];

                if (file) {
                    const url = URL.createObjectURL(file);
                    videoSource.src = url;
                    videoPreview.load();
                    videoPreview.style.display = 'block';
                }
            });

            // Autofill
            // Autofill
            document.getElementById('autofillButton').addEventListener('click', function () {
                // Random number generator for integers
                const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

                // Random float generator
                const randomFloat = (min, max, decimals = 2) =>
                    (Math.random() * (max - min) + min).toFixed(decimals);

                // Autofill function
                const autofillField = (id, value) => {
                    const field = document.getElementById(id);
                    if (field) {
                        field.value = value;
                    }
                };

                // Product and Machine Information
                autofillField('date', new Date().toISOString().split('T')[0]);
                autofillField('time', new Date().toLocaleTimeString('en-US', { hour12: false }).slice(0, 5));
                autofillField('machine', `Machine-${randomInt(1, 10)}`);
                autofillField('runNo', `Run-${randomInt(1000, 9999)}`);
                autofillField('category', `Category-${randomInt(1, 5)}`);
                autofillField('IRN', `IRN-${randomInt(100000, 999999)}`);

                // Product Details
                autofillField('product', `Product-${randomInt(1, 100)}`);
                autofillField('color', `Color-${randomInt(1, 10)}`);
                autofillField('mold-name', `Mold-${randomInt(1, 50)}`);
                autofillField('prodNo', `Prod-${randomInt(1000, 9999)}`);
                autofillField('cavity', randomInt(1, 8));
                autofillField('grossWeight', randomFloat(100, 200));
                autofillField('netWeight', randomFloat(50, 150));

                // Material Composition
                autofillField('dryingtime', randomFloat(1, 10));
                autofillField('dryingtemp', randomInt(50, 100));
                for (let i = 1; i <= 4; i++) {
                    autofillField(`type${i}`, `Type-${i}`);
                    autofillField(`brand${i}`, `Brand-${i}`);
                    autofillField(`mix${i}`, randomFloat(0, 100));
                }

                // Colorant Details
                autofillField('colorant', `Colorant-${randomInt(1, 5)}`);
                autofillField('colorant-dosage', `${randomFloat(1, 5)}%`);
                autofillField('colorant-stabilizer', `Stabilizer-${randomInt(1, 5)}`);
                autofillField('colorant-stabilizer-dosage', `${randomFloat(1, 2)}g`);

                // Timer Parameters
                autofillField('fillingTime', randomFloat(1, 5));
                autofillField('holdingTime', randomFloat(1, 5));
                autofillField('moldOpenCloseTime', randomFloat(1, 5));
                autofillField('chargingTime', randomFloat(1, 5));
                autofillField('coolingTime', randomFloat(1, 5));
                autofillField('cycleTime', randomFloat(1, 5));

                // Temperature Settings
                for (let i = 0; i <= 16; i++) {
                    autofillField(`barrelHeaterZone${i}`, randomInt(150, 300));
                }

                // Mold Operation Specifications
                autofillField('mold-code', `MoldCode-${randomInt(1, 100)}`);
                autofillField('clamping-force', randomInt(50, 500));
                autofillField('operation-type', `Type-${randomInt(1, 5)}`);
                autofillField('cooling-media', `CoolingMedia-${randomInt(1, 5)}`);
                autofillField('heating-media', `HeatingMedia-${randomInt(1, 5)}`);

                // Personnel
                autofillField('adjuster', `Adjuster-${randomInt(1, 100)}`);
                autofillField('qae', `QAE-${randomInt(1, 100)}`);
            });


        </script>

</body>

</html>