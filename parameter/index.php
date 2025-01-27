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
                                <input type="date" class="form-control" name="Date">
                            </div>
                            <div class="col">
                                <label for="Time" class="form-label">Time</label>
                                <input type="time" class="form-control" name="Time">
                            </div>
                            <div class="col">
                                <label for="MachineName" class="form-label">Machine</label>
                                <input type="text" class="form-control" name="MachineName"
                                    placeholder="Enter Machine Name">
                            </div>
                            <div class="col">
                                <label for="RunNumber" class="form-label">Run No.</label>
                                <input type="text" class="form-control" name="RunNumber" placeholder="Enter Run Number">
                            </div>
                            <div class="col">
                                <label for="Category" class="form-label">Category</label>
                                <input type="text" class="form-control" name="Category" placeholder="Enter Category">
                            </div>
                            <div class="col">
                                <label for="IRN" class="form-label">IRN</label>
                                <input type="text" class="form-control" name="IRN" placeholder="Enter IRN">
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
                                <input type="text" class="form-control" name="product" placeholder="Enter Product">
                            </div>
                            <div class="col">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" name="color" placeholder="Enter Color">
                            </div>
                            <div class="col">
                                <label for="mold-name" class="form-label">Mold Name</label>
                                <input type="text" class="form-control" name="mold-name" placeholder="Enter Mold Name">
                            </div>
                            <div class="col">
                                <label for="prodNo" class="form-label">Product Number</label>
                                <input type="text" class="form-control" name="prodNo" placeholder="Product Number">
                            </div>
                            <div class="col">
                                <label for="cavity" class="form-label">Number of Cavity (Active)</label>
                                <input type="number" class="form-control" name="cavity" placeholder="Number of Cavity">
                            </div>
                            <div class="col">
                                <label for="grossWeight" class="form-label">Gross Weight</label>
                                <input type="text" class="form-control" name="grossWeight"
                                    placeholder="Gross Weight (g)">
                            </div>
                            <div class="col">
                                <label for="netWeight" class="form-label">Net Weight</label>
                                <input type="text" class="form-control" name="netWeight" placeholder="Net Weight (g)">
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
                                <input type="number" class="form-control" name="dryingtime"
                                    placeholder="Select Drying Time">
                            </div>
                            <div class="col">
                                <label for="dryingtemp" class="form-label">Drying Temperature</label>
                                <div class="input -group">
                                    <input type="number" class="form-control" name="dryingtemp"
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
                                <label for="mix2" class="form-label">Mixture 3</label>
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
                                <input type="text" class="form-control" name="colorant" id="colorant"
                                    placeholder="Enter Colorant">
                            </div>
                            <div class="col">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" name="color" id="color"
                                    placeholder="Enter Colorant Color">
                            </div>
                            <div class="col">
                                <label for="colorant-dosage" class="form-label">Colorant Dosage</label>
                                <input type="text" class="form-control" name="colorant-dosage" id="colorant-dosage"
                                    placeholder="Enter Colorant Dosage">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="colorant-stabilizer" class="form-label">Colorant Stabilizer</label>
                                <input type="text" class="form-control" name="colorant-stabilizer"
                                    id="colorant-stabilizer" placeholder="Enter Colorant Stabilizer">
                            </div>
                            <div class="col">
                                <label for="colorant-stabilizer-dosage" class="form-label">Colorant Stabilizer Dosage
                                    (grams)</label>
                                <input type="text" class="form-control" name="colorant-stabilizer-dosage"
                                    id="colorant-stabilizer-dosage"
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
                                <input type="text" class="form-control" name="mold-code" id="mold-code"
                                    placeholder="Enter Mold Code">
                            </div>
                            <div class="col">
                                <label for="clamping-force" class="form-label">Clamping Force</label>
                                <input type="text" class="form-control" name="clamping-force" id="clamping-force"
                                    placeholder="Enter Clamping Force">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="operation-type" class="form-label">Operation</label>
                                <input type="text" class="form-control" name="operation-type" id="operation-type"
                                    placeholder="Enter Type of Operation">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="cooling-media" class="form-label">Cooling Media</label>
                                <input type="text" class="form-control" name="cooling-media" id="cooling-media"
                                    placeholder="Enter Cooling Media">
                            </div>
                            <div class="col">
                                <label for="heating-media" class="form-label">Heating Media</label>
                                <input type="text" class="form-control" name="heating-media" id="heating-media"
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
                                <input type="number" class="form-control" name="fillingTime" id="fillingTime"
                                    placeholder="Filling Time">
                            </div>
                            <div class="col">
                                <label for="holdingTime" class="form-label">Holding Time (s)</label>
                                <input type="number" class="form-control" name="holdingTime" id="holdingTime"
                                    placeholder="Holding Time">
                            </div>
                            <div class="col">
                                <label for="moldOpenCloseTime" class="form-label">Mold Open-Close Time (s)</label>
                                <input type="number" class="form-control" name="moldOpenCloseTime"
                                    id="moldOpenCloseTime" placeholder="Mold Open-Close Time">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="chargingTime" class="form-label">Charging Time (s)</label>
                                <input type="number" class="form-control" name="chargingTime" id="chargingTime"
                                    placeholder="Charging Time">
                            </div>
                            <div class="col">
                                <label for="coolingTime" class="form-label">Cooling Time (s)</label>
                                <input type="number" class="form-control" name="coolingTime" id="coolingTime"
                                    placeholder="Cooling Time">
                            </div>
                            <div class="col">
                                <label for="cycleTime" class="form-label">Cycle Time (s)</label>
                                <input type="number" class="form-control" name="cycleTime" id="cycleTime"
                                    placeholder="Cycle Time">
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
                                    <input type="number" class="form-control" name="nozzleHeaterZone0"
                                        id="nozzleHeaterZone0" placeholder="Nozzle Heater Zone 0 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone1" class="form-label">Barrel Heater Zone 1 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone1"
                                        id="barrelHeaterZone1" placeholder="Barrel Heater Zone 1 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone2" class="form-label">Barrel Heater Zone 2 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone2"
                                        id="barrelHeaterZone2" placeholder="Barrel Heater Zone 2 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone3" class="form-label">Barrel Heater Zone 3 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone3"
                                        id="barrelHeaterZone3" placeholder="Barrel Heater Zone 3 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone4" class="form-label">Barrel Heater Zone 4 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone4"
                                        id="barrelHeaterZone4" placeholder="Barrel Heater Zone 4 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone5" class="form-label">Barrel Heater Zone 5 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone5"
                                        id="barrelHeaterZone5" placeholder="Barrel Heater Zone 5 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone6" class="form-label">Barrel Heater Zone 6 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone6"
                                        id="barrelHeaterZone6" placeholder="Barrel Heater Zone 6 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone7" class="form-label">Barrel Heater Zone 7 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone7"
                                        id="barrelHeaterZone7" placeholder="Barrel Heater Zone 7 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone8" class="form-label">Barrel Heater Zone 8 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone8"
                                        id="barrelHeaterZone8" placeholder="Barrel Heater Zone 8 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone9" class="form-label">Barrel Heater Zone 9 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone9"
                                        id="barrelHeaterZone9" placeholder="Barrel Heater Zone 9 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone10" class="form-label">Barrel Heater Zone 10
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone10"
                                        id="barrelHeaterZone10" placeholder="Barrel Heater Zone 10 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone11" class="form-label">Barrel Heater Zone 11
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone11"
                                        id="barrelHeaterZone11" placeholder="Barrel Heater Zone 11 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone12" class="form-label">Barrel Heater Zone 12
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone12"
                                        id="barrelHeaterZone12" placeholder="Barrel Heater Zone 12 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone13" class="form-label">Barrel Heater Zone 13
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone13"
                                        id="barrelHeaterZone13" placeholder="Barrel Heater Zone 13 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone14" class="form-label">Barrel Heater Zone 14
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone14"
                                        id="barrelHeaterZone14" placeholder="Barrel Heater Zone 14 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone15" class="form-label">Barrel Heater Zone 15
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone15"
                                        id="barrelHeaterZone15" placeholder="Barrel Heater Zone 15 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone16" class="form-label">Barrel Heater Zone 16
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone16"
                                        id="barrelHeaterZone16" placeholder="Barrel Heater Zone 16 (°C)">
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
                                    <input type="number" class="form-control" name="barrelHeaterZone0"
                                        id="barrelHeaterZone0" placeholder="Barrel Heater Zone 0 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone1" class="form-label">Barrel Heater Zone 1 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone1"
                                        id="barrelHeaterZone1" placeholder="Barrel Heater Zone 1 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone2" class="form-label">Barrel Heater Zone 2 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone2"
                                        id="barrelHeaterZone2" placeholder="Barrel Heater Zone 2 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone3" class="form-label">Barrel Heater Zone 3 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone3"
                                        id="barrelHeaterZone3" placeholder="Barrel Heater Zone 3 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone4" class="form-label">Barrel Heater Zone 4 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone4"
                                        id="barrelHeaterZone4" placeholder="Barrel Heater Zone 4 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone5" class="form-label">Barrel Heater Zone 5 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone5"
                                        id="barrelHeaterZone5" placeholder="Barrel Heater Zone 5 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone6" class="form-label">Barrel Heater Zone 6 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone6"
                                        id="barrelHeaterZone6" placeholder="Barrel Heater Zone 6 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone7" class="form-label">Barrel Heater Zone 7 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone7"
                                        id="barrelHeaterZone7" placeholder="Barrel Heater Zone 7 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone8" class="form-label">Barrel Heater Zone 8 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone8"
                                        id="barrelHeaterZone8" placeholder="Barrel Heater Zone 8 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone9" class="form-label">Barrel Heater Zone 9 (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone9"
                                        id="barrelHeaterZone9" placeholder="Barrel Heater Zone 9 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone10" class="form-label">Barrel Heater Zone 10
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone10"
                                        id="barrelHeaterZone10" placeholder="Barrel Heater Zone 10 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone11" class="form-label">Barrel Heater Zone 11
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone11"
                                        id="barrelHeaterZone11" placeholder="Barrel Heater Zone 11 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone12" class="form-label">Barrel Heater Zone 12
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone12"
                                        id="barrelHeaterZone12" placeholder="Barrel Heater Zone 12 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone13" class="form-label">Barrel Heater Zone 13
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone13"
                                        id="barrelHeaterZone13" placeholder="Barrel Heater Zone 13 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone14" class="form-label">Barrel Heater Zone 14
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone14"
                                        id="barrelHeaterZone14" placeholder="Barrel Heater Zone 14 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone15" class="form-label">Barrel Heater Zone 15
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone15"
                                        id="barrelHeaterZone15" placeholder="Barrel Heater Zone 15 (°C)">
                                </div>
                                <div class="col">
                                    <label for="barrelHeaterZone16" class="form-label">Barrel Heater Zone 16
                                        (°C)</label>
                                    <input type="number" class="form-control" name="barrelHeaterZone16"
                                        id="barrelHeaterZone16" placeholder="Barrel Heater Zone 16 (°C)">
                                </div>
                                <div class="col">
                                    <label for="MTCSetting" class="form-label">MTC Setting</label>
                                    <input type="number" class="form-control" name="MTCSetting" id="MTCSetting"
                                        placeholder="MTC Setting">
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
                                    <input type="number" class="form-control" name="moldOpenPos1" id="moldOpenPos1"
                                        placeholder="Position 1">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos2" class="form-label">Position 2</label>
                                    <input type="number" class="form-control" name="moldOpenPos2" id="moldOpenPos2"
                                        placeholder="Position 2">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos3" class="form-label">Position 3</label>
                                    <input type="number" class="form-control" name="moldOpenPos3" id="moldOpenPos3"
                                        placeholder="Position 3">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos4" class="form-label">Position 4</label>
                                    <input type="number" class="form-control" name="moldOpenPos4" id="moldOpenPos4"
                                        placeholder="Position 4">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos5" class="form-label">Position 5</label>
                                    <input type="number" class="form-control" name="moldOpenPos5" id="moldOpenPos5"
                                        placeholder="Position 5">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos6" class="form-label">Position 6</label>
                                    <input type="number" class="form-control" name="moldOpenPos6" id="moldOpenPos6"
                                        placeholder="Position 6">
                                </div>
                            </div>
                            <!-- MO Speed -->
                            <h6>Speed</h6>
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                <div class="col">
                                    <label for="moldOpenSpd1" class="form-label">Speed 1</label>
                                    <input type="number" class="form-control" name="moldOpenSpd1" id="moldOpenSpd1"
                                        placeholder="Speed 1">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd2" class="form-label">Speed 2</label>
                                    <input type="number" class="form-control" name="moldOpenSpd2" id="moldOpenSpd2"
                                        placeholder="Speed 2">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd3" class="form-label">Speed 3</label>
                                    <input type="number" class="form-control" name="moldOpenSpd3" id="moldOpenSpd3"
                                        placeholder="Speed 3">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd4" class="form-label">Speed 4</label>
                                    <input type="number" class="form-control" name="moldOpenSpd4" id="moldOpenSpd4"
                                        placeholder="Speed 4">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd5" class="form-label">Speed 5</label>
                                    <input type="number" class="form-control" name="moldOpenSpd5" id="moldOpenSpd5"
                                        placeholder="Speed 5">
                                </div>
                                <div class="col">
                                    <label for="moldOpenSpd6" class="form-label">Speed 6</label>
                                    <input type="number" class="form-control" name="moldOpenSpd6" id="moldOpenSpd6"
                                        placeholder="Speed 6">
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
                                    <input type="number" class="form-control" name="moldClosePos1" id="moldClosePos1"
                                        placeholder="Position 1">
                                </div>
                                <div class="col">
                                    <label for="moldOpenPos2" class="form-label">Position 2</label>
                                    <input type="number" class="form-control" name="moldOpenPos2" id="moldOpenPos2"
                                        placeholder="Position 2">
                                </div>
                                <div class="col">
                                    <label for="moldClosePos3" class="form-label">Position 3</label>
                                    <input type="number" class="form-control" name="moldClosePos3" id="moldClosePos3"
                                        placeholder="Position 3">
                                </div>
                                <div class="col">
                                    <label for="moldClosePos4" class="form-label">Position 4</label>
                                    <input type="number" class="form-control" name="moldClosePos4" id="moldClosePos4"
                                        placeholder="Position 4">
                                </div>
                                <div class="col">
                                    <label for="moldClosePos5" class="form-label">Position 5</label>
                                    <input type="number" class="form-control" name="moldClosePos5" id="moldClosePos5"
                                        placeholder="Position 5">
                                </div>
                                <div class="col">
                                    <label for="moldClosePos6" class="form-label">Position 6</label>
                                    <input type="number" class="form-control" name="moldClosePos6" id="moldClosePos6"
                                        placeholder="Position 6">
                                </div>
                            </div>
                            <!-- MO Speed -->
                            <h6>Speed</h6>
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-6 g-3">
                                <div class="col">
                                    <label for="moldCloseSpd1" class="form-label">Speed 1</label>
                                    <input type="number" class="form-control" name="moldCloseSpd1" id="moldCloseSpd1"
                                        placeholder="Speed 1">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd2" class="form-label">Speed 2</label>
                                    <input type="number" class="form-control" name="moldCloseSpd2" id="moldCloseSpd2"
                                        placeholder="Speed 2">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd3" class="form-label">Speed 3</label>
                                    <input type="number" class="form-control" name="moldCloseSpd3" id="moldCloseSpd3"
                                        placeholder="Speed 3">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd4" class="form-label">Speed 4</label>
                                    <input type="number" class="form-control" name="moldCloseSpd4" id="moldCloseSpd4"
                                        placeholder="Speed 4">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd5" class="form-label">Speed 5</label>
                                    <input type="number" class="form-control" name="moldCloseSpd5" id="moldCloseSpd5"
                                        placeholder="Speed 5">
                                </div>
                                <div class="col">
                                    <label for="moldCloseSpd6" class="form-label">Speed 6</label>
                                    <input type="number" class="form-control" name="moldCloseSpd6" id="moldCloseSpd6"
                                        placeholder="Speed 6">
                                </div>
                            </div>
                            <!-- MO Pressure -->
                            <h6>Pressure</h6>
                            <div class="row mb-3 row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                                <div class="col">
                                    <label for="moldClosePressure1" class="form-label">Pressure 1</label>
                                    <input type="number" class="form-control" name="moldClosePressure1"
                                        id="moldClosePressure1" placeholder="Pressure 1">
                                </div>
                                <div class="col">
                                    <label for="moldClosePressure2" class="form-label">Pressure 2</label>
                                    <input type="number" class="form-control" name="moldClosePressure2"
                                        id="moldClosePressure2" placeholder="Pressure 2">
                                </div>
                                <div class="col">
                                    <label for="moldClosePressure3" class="form-label">Pressure 3</label>
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
                                    <label for="lowPresTimeLimit" class="form-label">Low Pressure Time Limit</label>
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
                                <input type="number" class="form-control" name="screwRPM1" id="screwRPM1"
                                    placeholder="Screw RPM 1">
                            </div>
                            <div class="col">
                                <label for="screwRPM2" class="form-label">Screw RPM 2</label>
                                <input type="number" class="form-control" name="screwRPM2" id="screwRPM2"
                                    placeholder="Screw RPM 2">
                            </div>
                            <div class="col">
                                <label for="screwRPM3" class="form-label">Screw RPM 3</label>
                                <input type="number" class="form-control" name="screwRPM3" id="screwRPM3"
                                    placeholder="Screw RPM 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="screwSpeed1" class="form-label">Screw Speed 1</label>
                                <input type="number" class="form-control" name="screwSpeed1" id="screwSpeed1"
                                    placeholder="Screw Speed 1">
                            </div>
                            <div class="col">
                                <label for="screwSpeed2" class="form-label">Screw Speed 2</label>
                                <input type="number" class="form-control" name="screwSpeed2" id="screwSpeed2"
                                    placeholder="Screw Speed 2">
                            </div>
                            <div class="col">
                                <label for="screwSpeed3" class="form-label">Screw Speed 3</label>
                                <input type="number" class="form-control" name="screwSpeed3" id="screwSpeed3"
                                    placeholder="Screw Speed 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="plastPressure1" class="form-label">Plast Pressure 1</label>
                                <input type="number" class="form-control" name="plastPressure1" id="plastPressure1"
                                    placeholder="Plast Pressure 1">
                            </div>
                            <div class="col">
                                <label for="plastPressure2" class="form-label">Plast Pressure 2</label>
                                <input type="number" class="form-control" name="plastPressure2" id="plastPressure2"
                                    placeholder="Plast Pressure 2">
                            </div>
                            <div class="col">
                                <label for="plastPressure3" class="form-label">Plast Pressure 3</label>
                                <input type="number" class="form-control" name="plastPressure3" id="plastPressure3"
                                    placeholder="Plast Pressure 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="plastPosition1" class="form-label">Plast Position 1</label>
                                <input type="number" class="form-control" name="plastPosition1" id="plastPosition1"
                                    placeholder="Plast Position 1">
                            </div>
                            <div class="col">
                                <label for="plastPosition2" class="form-label">Plast Position 2</label>
                                <input type="number" class="form-control" name="plastPosition2" id="plastPosition2"
                                    placeholder="Plast Position 2">
                            </div>
                            <div class="col">
                                <label for="plastPosition3" class="form-label">Plast Position 3</label>
                                <input type="number" class="form-control" name="plastPosition3" id="plastPosition3"
                                    placeholder="Plast Position 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="backPressure1" class="form-label">Back Pressure 1</label>
                                <input type="number" class="form-control" name="backPressure1" id="backPressure1"
                                    placeholder="Back Pressure 1">
                            </div>
                            <div class="col">
                                <label for="backPressure2" class="form-label">Back Pressure 2</label>
                                <input type="number" class="form-control" name="backPressure2" id="backPressure2"
                                    placeholder="Back Pressure 2">
                            </div>
                            <div class="col">
                                <label for="backPressure3" class="form-label">Back Pressure 3</label>
                                <input type="number" class="form-control" name="backPressure3" id="backPressure3"
                                    placeholder="Back Pressure 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="backPressureStartPosition" class="form-label">Back Pressure Start
                                    Position</label>
                                <input type="number" class="form-control" name="backPressureStartPosition"
                                    id="backPressureStartPosition" placeholder="Back Pressure Start Position">
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
                                <label for="RecoveryPosition" class="form-label">Recovery Position (mm)</label>
                                <input type="number" class="form-control" name="RecoveryPosition" id="RecoveryPosition"
                                    placeholder="Recovery Position">
                            </div>
                            <div class="col">
                                <label for="SecondStagePosition" class="form-label">Second Stage Position (mm)</label>
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
                                <input type="number" class="form-control" name="ScrewPosition1" id="screwPosition1"
                                    placeholder="Screw Position 1">
                            </div>
                            <div class="col">
                                <label for="screwPosition2" class="form-label">Screw Position 2</label>
                                <input type="number" class="form-control" name="ScrewPosition2" id="screwPosition2"
                                    placeholder="Screw Position 2">
                            </div>
                            <div class="col">
                                <label for="screwPosition3" class="form-label">Screw Position 3</label>
                                <input type="number" class="form-control" name="ScrewPosition3" id="screwPosition3"
                                    placeholder="Screw Position 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="INJSpeed1" class="form-label">Injection Speed 1</label>
                                <input type="number" class="form-control" name="InjectionSpeed1" id="injectionSpeed1"
                                    placeholder="Injection Speed 1">
                            </div>
                            <div class="col">
                                <label for="INJSpeed2" class="form-label">Injection Speed 2</label>
                                <input type="number" class="form-control" name="InjectionSpeed2" id="injectionSpeed2"
                                    placeholder="Injection Speed 2">
                            </div>
                            <div class="col">
                                <label for="INJSpeed3" class="form-label">Injection Speed 3</label>
                                <input type="number" class="form-control" name="InjectionSpeed3" id="injectionSpeed3"
                                    placeholder="Injection Speed 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="INJPressure1" class="form-label">Injection Pressure 1</label>
                                <input type="number" class="form-control" name="InjectionPressure1"
                                    id="injectionPressure1" placeholder="Injection Pressure 1">
                            </div>
                            <div class="col">
                                <label for="INJPressure2" class="form-label">Injection Pressure 2</label>
                                <input type="number" class="form-control" name="InjectionPressure2"
                                    id="injectionPressure2" placeholder="Injection Pressure 2">
                            </div>
                            <div class="col">
                                <label for="INJPressure3" class="form-label">Injection Pressure 3</label>
                                <input type="number" class="form-control" name="InjectionPressure3"
                                    id="injectionPressure3" placeholder="Injection Pressure 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="SuckBackPos" class="form-label">Suck Back Position</label>
                                <input type="number" class="form-control" name="SuckBackPosition" id="suckBackPosition"
                                    placeholder="Suck Back Position">
                            </div>
                            <div class="col">
                                <label for="SuckBackSpeed" class="form-label">Suck Back Speed</label>
                                <input type="number" class="form-control" name="SuckBackSpeed" id="suckBackSpeed"
                                    placeholder="Suck Back Speed">
                            </div>
                            <div class="col">
                                <label for="SuckBackPres" class="form-label">Suck Back Pressure</label>
                                <input type="number" class="form-control" name="SuckBackPressure" id="suckBackPressure"
                                    placeholder="Suck Back Pressure">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="ScrewPosition4" class="form-label">Screw Position 4</label>
                                <input type="number" class="form-control" name="ScrewPosition4" id="screwPosition4"
                                    placeholder="Screw Position 4">
                            </div>
                            <div class="col">
                                <label for="ScrewPosition5" class="form-label">Screw Position 5</label>
                                <input type="number" class="form-control" name="ScrewPosition5" id="ScrewPosition5"
                                    placeholder="Screw Position 5">
                            </div>
                            <div class="col">
                                <label for="ScrewPosition6" class="form-label">Screw Position 6</label>
                                <input type="number" class="form-control" name="ScrewPosition6" id="ScrewPosition6"
                                    placeholder="Screw Position 6">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="InjectionSpeed4" class="form-label">Injection Speed 4</label>
                                <input type="number" class="form-control" name="InjectionSpeed4" id="InjectionSpeed4"
                                    placeholder="Injection Speed 4">
                            </div>
                            <div class="col">
                                <label for="InjectionSpeed5" class="form-label">Injection Speed 5</label>
                                <input type="number" class="form-control" name="InjectionSpeed5" id="InjectionSpeed5"
                                    placeholder="Injection Speed 5">
                            </div>
                            <div class="col">
                                <label for="InjectionSpeed6" class="form-label">Injection Speed 6</label>
                                <input type="number" class="form-control" name="InjectionSpeed6" id="InjectionSpeed6"
                                    placeholder="Injection Speed 6">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="InjectionPressure4" class="form-label">Injection Pressure 4</label>
                                <input type="number" class="form-control" name="InjectionPressure4"
                                    id="InjectionPressure4" placeholder="Injection Pressure 4">
                            </div>
                            <div class="col">
                                <label for="InjectionPressure5" class="form-label">Injection Pressure 5</label>
                                <input type="number" class="form-control" name="InjectionPressure5"
                                    id="InjectionPressure5" placeholder="Injection Pressure 5">
                            </div>
                            <div class="col">
                                <label for="InjectionPressure6" class="form-label">Injection Pressure 6</label>
                                <input type="number" class="form-control" name="InjectionPressure6"
                                    id="InjectionPressure6" placeholder="Injection Pressure 6">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="SprueBreak" class="form-label">Sprue Break</label>
                                <input type="number" class="form-control" name="SprueBreak" id="SprueBreak"
                                    placeholder="Sprue Break">
                            </div>
                            <div class="col">
                                <label for="SprueBreakTime" class="form-label">Sprue Break Time</label>
                                <input type="number" class="form-control" name="SprueBreakTime" id="SprueBreakTime"
                                    placeholder="Sprue Break Time">
                            </div>
                            <div class="col">
                                <label for="InjectionDelay" class="form-label">Injection Delay</label>
                                <input type="number" class="form-control" name="InjectionDelay" id="InjectionDelay"
                                    placeholder="Injection Delay">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="HoldingPres1" class="form-label">Holding Pressure 1</label>
                                <input type="number" class="form-control" name="HoldingPressure1" id="HoldingPres1"
                                    placeholder="Holding Pressure 1">
                            </div>
                            <div class="col">
                                <label for="HoldingPres2" class="form-label">Sprue Break Time</label>
                                <input type="number" class="form-control" name="HoldingPressure2" id="HoldingPres2"
                                    placeholder="Holding Pressure 2">
                            </div>
                            <div class="col">
                                <label for="HoldingPres3" class="form-label">Holding Pressure 3</label>
                                <input type="number" class="form-control" name="HoldingPressure3" id="HoldingPres3"
                                    placeholder="Holding Pressure 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="HoldingSpeed1" class="form-label">Holding Speed 1</label>
                                <input type="number" class="form-control" name="HoldingSpeed1" id="HoldingSpeed1"
                                    placeholder="Holding Speed 1">
                            </div>
                            <div class="col">
                                <label for="HoldingSpeed2" class="form-label">Holding Speed 2</label>
                                <input type="number" class="form-control" name="HoldingSpeed2" id="HoldingSpeed2"
                                    placeholder="Holding Speed 2">
                            </div>
                            <div class="col">
                                <label for="HoldingSpeed3" class="form-label">Holding Speed 3</label>
                                <input type="number" class="form-control" name="HoldingSpeed3" id="HoldingSpeed3"
                                    placeholder="Holding Speed 3">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="HoldingTime1" class="form-label">Holding Time 1</label>
                                <input type="number" class="form-control" name="HoldingTime1" id="HoldingTime1"
                                    placeholder="Holding Time 1">
                            </div>
                            <div class="col">
                                <label for="HoldingTime2" class="form-label">Holding Time 2</label>
                                <input type="number" class="form-control" name="HoldingTime2" id="HoldingTime2"
                                    placeholder="Holding Time 2">
                            </div>
                            <div class="col">
                                <label for="HoldingTime3" class="form-label">Holding Time 3</label>
                                <input type="number" class="form-control" name="HoldingTime3" id="HoldingTime3"
                                    placeholder="Holding Time 3">
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
                                <input type="number" class="form-control" name="AirBlowTimeA" id="airBlowTimeA"
                                    placeholder="Air Blow Time A">
                            </div>
                            <div class="col">
                                <label for="AirBlowPositionA" class="form-label">Air Blow Position A</label>
                                <input type="number" class="form-control" name="AirBlowPositionA" id="airBlowPositionA"
                                    placeholder="Air Blow Position A">
                            </div>
                            <div class="col">
                                <label for="AB A Delay" class="form-label">Air Blow A Delay</label>
                                <input type="number" class="form-control" name="AirBlowADelay" id="airBlowADelay"
                                    placeholder="Air Blow A Delay">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="AirBlowTimeB" class="form-label">Air Blow Time B</label>
                                <input type="number" class="form-control" name="AirBlowTimeB" id="airBlowTimeB"
                                    placeholder="Air Blow Time B">
                            </div>
                            <div class="col">
                                <label for="AirBlowPosB" class="form-label">Air Blow Position B</label>
                                <input type="number" class="form-control" name="ABlowPositionB" id="aBlowPositionB"
                                    placeholder="Air Blow Position B">
                            </div>
                            <div class="col">
                                <label for="AirBlowBDelay" class="form-label">Air Blow B Delay</label>
                                <input type="number" class="form-control" name="AirBlowBDelay" id="airBlowBDelay"
                                    placeholder="Air Blow B Delay">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorForwardPosition1" class="form-label">Ejector Forward Position
                                    1</label>
                                <input type="number" class="form-control" name="EjectorForwardPosition1"
                                    id="EjectorForwardPosition1" placeholder="Ejector Forward Position 1">
                            </div>
                            <div class="col">
                                <label for="EjectorForwardPosition2" class="form-label">Ejector Forward Position
                                    2</label>
                                <input type="number" class="form-control" name="EjectorForwardPosition2"
                                    id="EjectorForwardPosition2" placeholder="Ejector Forward Position 2">
                            </div>
                            <div class="col">
                                <label for="EFSpeed1" class="form-label">Ejector Forward Speed 1</label>
                                <input type="number" class="form-control" name="EjectorForwardSpeed1"
                                    id="ejectorForwardSpeed1" placeholder="Ejector Forward Speed 1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorRetractPosition1" class="form-label">Ejector Retract Position
                                    1</label>
                                <input type="number" class="form-control" name="EjectorRetractPosition1"
                                    id="ejectorRetractPosition1" placeholder="Ejector Retract Position 1">
                            </div>
                            <div class="col">
                                <label for="EjectorRetractPosition2" class="form-label">Ejector Retract Position
                                    2</label>
                                <input type="number" class="form-control" name="EjectorRetractPosition2"
                                    id="ejectorRetractPosition2" placeholder="Ejector Retract Position 2">
                            </div>
                            <div class="col">
                                <label for="Ejector Retract Speed1" class="form-label">Ejector Retract Speed
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
                                <label for="EjectorForwardTime" class="form-label">Ejector Forward Time</label>
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
                                <label for="EjectorRetractTime" class="form-label">Ejector Retract Time</label>
                                <input type="number" class="form-control" name="EjectorRetractTime"
                                    id="ejectorRetractTime" placeholder="Ejector Retract Time">
                            </div>
                            <div class="col">
                                <label for="EjectorForwardSpeed2" class="form-label">Ejector Forward Speed 2</label>
                                <input type="number" class="form-control" name="EjectorForwardSpeed2"
                                    id="ejectorForwardSpeed2" placeholder="Ejector Forward Speed2">
                            </div><!--sub field-->
                            <div class="col">
                                <label for="EjectorForwardPressure1" class="form-label">Ejector Forward Pressure
                                    1</label>
                                <input type="number" class="form-control" name="EjectorForwardPressure1"
                                    id="ejectorForwardPressure1" placeholder="Ejector Forward Pressure 1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorForwardSpeed2" class="form-label">Ejector Forward Speed 2</label>
                                <input type="number" class="form-control" name="EjectorForwardSpeed2"
                                    id="ejectorForwardSpeed2" placeholder="Ejector Forward Speed 2">
                            </div>
                            <!--sub field-->
                            <div class="col">
                                <label for="EjectorForward" class="form-label">Ejector Forward</label>
                                <input type="number" class="form-control" name="EjectorForward" id="ejectorForward"
                                    placeholder="Ejector Forward">
                            </div>
                            <div class="col">
                                <label for="EjectorRetractSpeed2" class="form-label">Ejector Retract Speed 2</label>
                                <input type="number" class="form-control" name="EjectorRetractSpeed2"
                                    id="ejectorRetractSpeed2" placeholder="Ejector Retract Speed 2">
                            </div>
                        </div>
                        <!--sub field-->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="EjectorRetractPressure1" class="form-label">Ejector Retract Pressure
                                    1</label>
                                <input type="number" class="form-control" name="EjectorRetractPressure1"
                                    id="ejectorRetractPressure1" placeholder="Ejector Retract Pressure 1">
                            </div>
                            <div class="col">
                                <label for="EjectorRetractSpeed2" class="form-label">Ejector Retract Speed 2</label>
                                <input type="number" class="form-control" name="EjectorRetractSpeed2"
                                    id="ejectorRetractSpeed2" placeholder="Ejector Retract Speed 2">
                            </div>
                            <!--sub field-->
                            <div class="col">
                                <label for="EjectorRetract" class="form-label">Ejector Retract</label>
                                <input type="number" class="form-control" name="EjectorRetract" id="ejectorRetract"
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
                                    <input type="number" class="form-control" name="coreSetASequence"
                                        id="coreSetASequence" placeholder="Core Set A Sequence">
                                </div>
                                <div class="col">
                                    <label for="coreSetAPressure" class="form-label">Core Set A Pressure ()</label>
                                    <input type="number" class="form-control" name="coreSetAPressure"
                                        id="coreSetAPressure" placeholder="Core Set A Pressure">
                                </div>
                                <div class="col">
                                    <label for="coreSetASpeed" class="form-label">Core Set A Speed</label>
                                    <input type="number" class="form-control" name="coreSetASpeed" id="coreSetASpeed"
                                        placeholder="Core Set A Speed">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="coreSetAPosition" class="form-label">Core Set A Position</label>
                                    <input type="number" class="form-control" name="coreSetAPosition"
                                        id="coreSetAPosition" placeholder="Core Set A Position">
                                </div>
                                <div class="col">
                                    <label for="coreSetATime" class="form-label">Core Set A Time</label>
                                    <input type="number" class="form-control" name="coreSetATime" id="coreSetATime"
                                        placeholder="Core Set A Time">
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
                                    <input type="number" class="form-control" name="corePullASequence"
                                        id="corePullASequence" placeholder="Core Pull A Sequence">
                                </div>
                                <div class="col">
                                    <label for="corePullAPressure" class="form-label">Core Pull A Pressure
                                        ()</label>
                                    <input type="number" class="form-control" name="corePullAPressure"
                                        id="corePullAPressure" placeholder="Core Pull A Pressure">
                                </div>
                                <div class="col">
                                    <label for="corePullASpeed" class="form-label">Core Pull A Speed</label>
                                    <input type="number" class="form-control" name="corePullASpeed" id="corePullASpeed"
                                        placeholder="Core Pull A Speed">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="corePullAPosition" class="form-label">Core Pull A Position</label>
                                    <input type="number" class="form-control" name="corePullAPosition"
                                        id="corePullAPosition" placeholder="Core Pull A Position">
                                </div>
                                <div class="col">
                                    <label for="corePullATime" class="form-label">Core Pull A Time</label>
                                    <input type="number" class="form-control" name="corePullATime" id="corePullATime"
                                        placeholder="Core Pull A Time">
                                </div>
                                <div class="col">
                                    <label for="corePullALimitSwitch" class="form-label">Core Pull A Limit
                                        Switch</label>
                                    <input type="number" class="form-control" name="corePullALimitSwitch"
                                        id="corePullALimitSwitch" placeholder="Core Pull A Limit Switch">
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
                                    <label for="coreSetBSpeed" class="form-label">Core Set B Speed</label>
                                    <input type="number" class="form-control" name="coreSetBSpeed" id="coreSetBSpeed"
                                        placeholder="Core Set B Speed">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="coreSetBPosition" class="form-label">Core Set B Position</label>
                                    <input type="number" class="form-control" name="coreSetBPosition"
                                        id="coreSetBPosition" placeholder="Core Set B Position">
                                </div>
                                <div class="col">
                                    <label for="coreSetBTime" class="form-label">Core Set B Time</label>
                                    <input type="number" class="form-control" name="coreSetBTime" id="coreSetBTime"
                                        placeholder="Core Set B Time">
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
                                    <input type="number" class="form-control" name="corePullBSequence"
                                        id="corePullBSequence" placeholder="Core Pull B Sequence">
                                </div>
                                <div class="col">
                                    <label for="corePullBPressure" class="form-label">Core Pull B Pressure
                                        ()</label>
                                    <input type="number" class="form-control" name="corePullBPressure"
                                        id="corePullBPressure" placeholder="Core Pull B Pressure">
                                </div>
                                <div class="col">
                                    <label for="corePullBSpeed" class="form-label">Core Pull B Speed</label>
                                    <input type="number" class="form-control" name="corePullBSpeed" id="corePullBSpeed"
                                        placeholder="Core Pull B Speed">
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
                                    <label for="corePullBTime" class="form-label">Core Pull B Time</label>
                                    <input type="number" class="form-control" name="corePullBTime" id="corePullBTime"
                                        placeholder="Core Pull B Time">
                                </div>
                                <div class="col">
                                    <label for="corePullBLimitSwitch" class="form-label">Core Pull B Limit
                                        Switch</label>
                                    <input type="number" class="form-control" name="corePullBLimitSwitch"
                                        id="corePullBLimitSwitch" placeholder="Core Pull B Limit Switch">
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
                            <textarea class="form-control" name="additionalInfo" id="additionalInfo" rows="4"
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
                                <input type="number" class="form-control" name="adjuster" id="adjuster"
                                    placeholder="Enter Adjuster Name">
                            </div>
                            <div class="col">
                                <label for="qae" class="form-label">Quality Assurance Engineer Name</label>
                                <input type="text" class="form-control" name="qae" id="qae"
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
                            <!-- Pictures -->
                            <div class="col-md-6">
                                <label for="uploadImages" class="form-label">Upload Images</label>
                                <input class="form-control" type="file" id="uploadImages" name="uploadImages[]"
                                    accept="image/*" multiple>
                                <small class="text-muted">Accepted formats: JPG, PNG, GIF</small>
                            </div>

                            <!-- Videos -->
                            <div class="col-md-6">
                                <label for="uploadVideos" class="form-label">Upload Videos</label>
                                <input class="form-control" type="file" id="uploadVideos" name="uploadVideos[]"
                                    accept="video/*" multiple>
                                <small class="text-muted">Accepted formats: MP4, AVI, MKV</small>
                            </div>
                        </div>

                        <!-- Preview Section for Uploaded Files -->
                        <div class="row">
                            <!-- Image Previews -->
                            <div class="col-md-6">
                                <label class="form-label">Image Previews</label>
                                <div id="imagePreviews" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            <!-- Video Previews -->
                            <div class="col-md-6">
                                <label class="form-label">Video Previews</label>
                                <div id="videoPreviews" class="d-flex flex-wrap gap-2"></div>
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
            const imageFiles = []; // Array to track uploaded image files
            const videoFiles = []; // Array to track uploaded video files

            // Handle image uploads and previews
            document.getElementById('uploadImages').addEventListener('change', function (event) {
                const imagePreviews = document.getElementById('imagePreviews');
                Array.from(event.target.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        imageFiles.push(file); // Add to image file array
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewContainer = document.createElement('div');
                            previewContainer.className = 'position-relative';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-fluid border rounded';
                            img.style.width = '100px';
                            img.style.height = '100px';
                            img.alt = 'Uploaded Image';

                            const removeBtn = document.createElement('button');
                            removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
                            removeBtn.textContent = 'X';
                            removeBtn.style.margin = '5px';
                            removeBtn.addEventListener('click', () => {
                                imageFiles.splice(index, 1); // Remove the file from array
                                previewContainer.remove(); // Remove the preview
                            });

                            previewContainer.appendChild(img);
                            previewContainer.appendChild(removeBtn);
                            imagePreviews.appendChild(previewContainer);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Clear the input to allow re-adding files
                event.target.value = '';
            });

            // Handle video uploads and previews
            document.getElementById('uploadVideos').addEventListener('change', function (event) {
                const videoPreviews = document.getElementById('videoPreviews');
                Array.from(event.target.files).forEach((file, index) => {
                    if (file.type.startsWith('video/')) {
                        videoFiles.push(file); // Add to video file array
                        const url = URL.createObjectURL(file);

                        const previewContainer = document.createElement('div');
                        previewContainer.className = 'position-relative';

                        const video = document.createElement('video');
                        video.src = url;
                        video.className = 'img-fluid border rounded';
                        video.style.width = '150px';
                        video.style.height = '150px';
                        video.controls = true;

                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
                        removeBtn.textContent = 'X';
                        removeBtn.style.margin = '5px';
                        removeBtn.addEventListener('click', () => {
                            videoFiles.splice(index, 1); // Remove the file from array
                            previewContainer.remove(); // Remove the preview
                        });

                        previewContainer.appendChild(video);
                        previewContainer.appendChild(removeBtn);
                        videoPreviews.appendChild(previewContainer);
                    }
                });

                // Clear the input to allow re-adding files
                event.target.value = '';
            });
        </script>



        <script>
            document.getElementById('autofillButton').addEventListener('click', function () {
                // Random number generator for integers
                const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

                // Autofill all input fields
                document.querySelectorAll('input').forEach(input => {
                    if (input.type === 'text' || input.type === 'number' || input.type === 'date' || input.type === 'time') {
                        input.value = randomInt(1, 10); // Random whole number between 1 and 1000
                    }
                });

                // Autofill all textarea fields
                document.querySelectorAll('textarea').forEach(textarea => {
                    textarea.value = randomInt(1, 10); // Random whole number between 1 and 1000
                });

                // Autofill select fields if they exist
                document.querySelectorAll('select').forEach(select => {
                    const options = select.options;
                    if (options.length > 0) {
                        select.selectedIndex = randomInt(0, options.length - 1); // Randomly select an option
                    }
                });
            });
        </script>


</body>

</html>