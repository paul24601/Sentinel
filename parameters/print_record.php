<?php
require_once 'session_config.php';

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: ../login.html");
    exit();
}

// Check if record_id is provided
if (!isset($_GET['record_id']) || empty($_GET['record_id'])) {
    echo "<div style='text-align:center; margin-top:50px;'>";
    echo "<h2>Error: No Record ID Provided</h2>";
    echo "<p>Please specify a valid record ID to print.</p>";
    echo "<a href='submission.php'>Return to Records</a>";
    echo "</div>";
    exit();
}

$record_id = $_GET['record_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "injectionmoldingparameters";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the record details
$sql = "SELECT * FROM parameter_records WHERE record_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $record_id);
$stmt->execute();
$recordData = $stmt->get_result()->fetch_assoc();

if (!$recordData) {
    echo "<div style='text-align:center; margin-top:50px;'>";
    echo "<h2>Error: Record Not Found</h2>";
    echo "<p>The requested record could not be found.</p>";
    echo "<a href='submission.php'>Return to Records</a>";
    echo "</div>";
    exit();
}

// Fetch all data for this record as arrays
function fetchRecordData($conn, $table, $record_id) {
    $sql = "SELECT * FROM $table WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

$productMachineInfo = fetchRecordData($conn, 'productmachineinfo', $record_id);
$productDetails = fetchRecordData($conn, 'productdetails', $record_id);
$materialComposition = fetchRecordData($conn, 'materialcomposition', $record_id);
$colorantDetails = fetchRecordData($conn, 'colorantdetails', $record_id);
$moldOperationSpecs = fetchRecordData($conn, 'moldoperationspecs', $record_id);
$timerParameters = fetchRecordData($conn, 'timerparameters', $record_id);
$barrelHeaterTemperatures = fetchRecordData($conn, 'barrelheatertemperatures', $record_id);
$moldHeaterTemperatures = fetchRecordData($conn, 'moldheatertemperatures', $record_id);
$plasticizingParameters = fetchRecordData($conn, 'plasticizingparameters', $record_id);
$injectionParameters = fetchRecordData($conn, 'injectionparameters', $record_id);
$ejectionParameters = fetchRecordData($conn, 'ejectionparameters', $record_id);
$corePullSettings = fetchRecordData($conn, 'corepullsettings', $record_id);
$additionalInformation = fetchRecordData($conn, 'additionalinformation', $record_id);
$personnel = fetchRecordData($conn, 'personnel', $record_id);

// Fetch attachments as array
$sql = "SELECT * FROM attachments WHERE record_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $record_id);
$stmt->execute();
$attachments = [];
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $attachments[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Record: <?= htmlspecialchars($recordData['title']) ?></title>
    <!-- Bootstrap CSS for printing -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body { font-size: 12px; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            table { font-size: 10px; }
            .section-title { background-color: #f5f5f5 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .card { border: 1px solid #ddd !important; margin-bottom: 15px !important; page-break-inside: avoid; }
            .card-header { background-color: #f8f9fa !important; border-bottom: 1px solid #ddd !important; }
        }
        .section-title { background-color: #f5f5f5; padding: 10px; margin-bottom: 15px; border-left: 5px solid #0d6efd; }
        .data-row { margin-bottom: 8px; }
        .data-label { font-weight: bold; margin-right: 5px; }
        .print-header { text-align: center; margin-bottom: 20px; padding: 20px; border-bottom: 2px solid #0d6efd; }
        .table th { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Print Controls (not printed) -->
        <div class="row mb-4 no-print">
            <div class="col-12">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="submission.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Records
                </a>
            </div>
        </div>
        <!-- Print Header -->
        <div class="print-header">
            <h1>Injection Molding Parameters</h1>
            <h3><?= htmlspecialchars($recordData['title']) ?></h3>
            <p>Record ID: <?= htmlspecialchars($record_id) ?></p>
            <p>Date: <?= date('Y-m-d', strtotime($recordData['submission_date'])) ?></p>
        </div>
        <!-- Record Information -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Record Information</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"><p><strong>Submitted by:</strong> <?= htmlspecialchars($recordData['submitted_by']) ?></p></div>
                    <div class="col-md-4"><p><strong>Submission Date:</strong> <?= htmlspecialchars($recordData['submission_date']) ?></p></div>
                    <div class="col-md-4"><p><strong>Status:</strong> <?= htmlspecialchars(ucfirst($recordData['status'])) ?></p></div>
                </div>
                <?php if ($recordData['description']): ?>
                <div class="row"><div class="col-12"><p><strong>Description:</strong></p><p><?= nl2br(htmlspecialchars($recordData['description'])) ?></p></div></div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Product and Machine Information -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Product and Machine Information</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Date</th><th>Time</th><th>Machine</th><th>Run Number</th><th>Category</th><th>IRN</th></tr></thead>
                    <tbody>
                    <?php foreach ($productMachineInfo as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Date']) ?></td>
                            <td><?= htmlspecialchars($row['Time']) ?></td>
                            <td><?= htmlspecialchars($row['MachineName']) ?></td>
                            <td><?= htmlspecialchars($row['RunNumber']) ?></td>
                            <td><?= htmlspecialchars($row['Category']) ?></td>
                            <td><?= htmlspecialchars($row['IRN']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Product Details -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Product Details</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Product Name</th><th>Color</th><th>Mold Name</th><th>Product Number</th><th>Cavity Active</th><th>Gross Weight</th><th>Net Weight</th></tr></thead>
                    <tbody>
                    <?php foreach ($productDetails as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ProductName']) ?></td>
                            <td><?= htmlspecialchars($row['Color']) ?></td>
                            <td><?= htmlspecialchars($row['MoldName']) ?></td>
                            <td><?= htmlspecialchars($row['ProductNumber']) ?></td>
                            <td><?= htmlspecialchars($row['CavityActive']) ?></td>
                            <td><?= htmlspecialchars($row['GrossWeight']) ?></td>
                            <td><?= htmlspecialchars($row['NetWeight']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Material Composition -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Material Composition</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Drying Time</th><th>Drying Temperature</th><th>Material 1</th><th>Mix %</th><th>Material 2</th><th>Mix %</th><th>Material 3</th><th>Mix %</th><th>Material 4</th><th>Mix %</th></tr></thead>
                    <tbody>
                    <?php foreach ($materialComposition as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['DryingTime']) ?></td>
                            <td><?= htmlspecialchars($row['DryingTemperature']) ?></td>
                            <td><?= $row['Material1_Type'] ? htmlspecialchars($row['Material1_Type'] . ' (' . $row['Material1_Brand'] . ')') : '-' ?></td>
                            <td><?= htmlspecialchars($row['Material1_MixturePercentage']) ?: '-' ?></td>
                            <td><?= $row['Material2_Type'] ? htmlspecialchars($row['Material2_Type'] . ' (' . $row['Material2_Brand'] . ')') : '-' ?></td>
                            <td><?= htmlspecialchars($row['Material2_MixturePercentage']) ?: '-' ?></td>
                            <td><?= $row['Material3_Type'] ? htmlspecialchars($row['Material3_Type'] . ' (' . $row['Material3_Brand'] . ')') : '-' ?></td>
                            <td><?= htmlspecialchars($row['Material3_MixturePercentage']) ?: '-' ?></td>
                            <td><?= $row['Material4_Type'] ? htmlspecialchars($row['Material4_Type'] . ' (' . $row['Material4_Brand'] . ')') : '-' ?></td>
                            <td><?= htmlspecialchars($row['Material4_MixturePercentage']) ?: '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Colorant Details -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Colorant Details</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Colorant</th><th>Color</th><th>Dosage</th><th>Stabilizer</th><th>Stabilizer Dosage</th></tr></thead>
                    <tbody>
                    <?php foreach ($colorantDetails as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Colorant']) ?></td>
                            <td><?= htmlspecialchars($row['Color']) ?></td>
                            <td><?= htmlspecialchars($row['Dosage']) ?></td>
                            <td><?= htmlspecialchars($row['Stabilizer']) ?></td>
                            <td><?= htmlspecialchars($row['StabilizerDosage']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Mold Operation Specs -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Mold Operation Specifications</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Mold Code</th><th>Clamping Force</th><th>Operation Type</th><th>Cooling Media</th><th>Heating Media</th></tr></thead>
                    <tbody>
                    <?php foreach ($moldOperationSpecs as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['MoldCode']) ?></td>
                            <td><?= htmlspecialchars($row['ClampingForce']) ?></td>
                            <td><?= htmlspecialchars($row['OperationType']) ?></td>
                            <td><?= htmlspecialchars($row['CoolingMedia']) ?></td>
                            <td><?= htmlspecialchars($row['HeatingMedia']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Timer Parameters -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Timer Parameters</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Filling Time</th><th>Holding Time</th><th>Mold Open/Close Time</th><th>Charging Time</th><th>Cooling Time</th><th>Cycle Time</th></tr></thead>
                    <tbody>
                    <?php foreach ($timerParameters as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['FillingTime']) ?></td>
                            <td><?= htmlspecialchars($row['HoldingTime']) ?></td>
                            <td><?= htmlspecialchars($row['MoldOpenCloseTime']) ?></td>
                            <td><?= htmlspecialchars($row['ChargingTime']) ?></td>
                            <td><?= htmlspecialchars($row['CoolingTime']) ?></td>
                            <td><?= htmlspecialchars($row['CycleTime']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Barrel Heater Temperatures -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Barrel Heater Temperatures</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><?php for ($i = 0; $i <= 16; $i++): ?><th>Zone <?= $i ?></th><?php endfor; ?></tr></thead>
                    <tbody>
                    <?php foreach ($barrelHeaterTemperatures as $row): ?><tr><?php for ($i = 0; $i <= 16; $i++): ?><td><?= htmlspecialchars($row["Zone$i"]) ?> °C</td><?php endfor; ?></tr><?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Mold Heater Temperatures -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Mold Heater Temperatures</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><?php for ($i = 0; $i <= 16; $i++): ?><th>Zone <?= $i ?></th><?php endfor; ?><th>MTC Setting</th></tr></thead>
                    <tbody>
                    <?php foreach ($moldHeaterTemperatures as $row): ?><tr><?php for ($i = 0; $i <= 16; $i++): ?><td><?= htmlspecialchars($row["Zone$i"]) ?> °C</td><?php endfor; ?><td><?= htmlspecialchars($row['MTCSetting']) ?></td></tr><?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Plasticizing Parameters -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Plasticizing Parameters</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Parameter</th><th>Stage 1</th><th>Stage 2</th><th>Stage 3</th></tr></thead>
                    <tbody>
                    <?php foreach ($plasticizingParameters as $row): ?>
                        <tr><td>Screw RPM</td><td><?= htmlspecialchars($row['ScrewRPM1']) ?></td><td><?= htmlspecialchars($row['ScrewRPM2']) ?></td><td><?= htmlspecialchars($row['ScrewRPM3']) ?></td></tr>
                        <tr><td>Screw Speed</td><td><?= htmlspecialchars($row['ScrewSpeed1']) ?></td><td><?= htmlspecialchars($row['ScrewSpeed2']) ?></td><td><?= htmlspecialchars($row['ScrewSpeed3']) ?></td></tr>
                        <tr><td>Plast Pressure</td><td><?= htmlspecialchars($row['PlastPressure1']) ?></td><td><?= htmlspecialchars($row['PlastPressure2']) ?></td><td><?= htmlspecialchars($row['PlastPressure3']) ?></td></tr>
                        <tr><td>Plast Position</td><td><?= htmlspecialchars($row['PlastPosition1']) ?></td><td><?= htmlspecialchars($row['PlastPosition2']) ?></td><td><?= htmlspecialchars($row['PlastPosition3']) ?></td></tr>
                        <tr><td>Back Pressure</td><td><?= htmlspecialchars($row['BackPressure1']) ?></td><td><?= htmlspecialchars($row['BackPressure2']) ?></td><td><?= htmlspecialchars($row['BackPressure3']) ?></td></tr>
                        <tr><td>Back Pressure Start Position</td><td colspan="3"><?= htmlspecialchars($row['BackPressureStartPosition']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Injection Parameters -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Injection Parameters</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Parameter</th><th>Stage 1</th><th>Stage 2</th><th>Stage 3</th></tr></thead>
                    <tbody>
                    <?php foreach ($injectionParameters as $row): ?>
                        <tr><td>Screw Position</td><td><?= htmlspecialchars($row['ScrewPosition1']) ?></td><td><?= htmlspecialchars($row['ScrewPosition2']) ?></td><td><?= htmlspecialchars($row['ScrewPosition3']) ?></td></tr>
                        <tr><td>Injection Speed</td><td><?= htmlspecialchars($row['InjectionSpeed1']) ?></td><td><?= htmlspecialchars($row['InjectionSpeed2']) ?></td><td><?= htmlspecialchars($row['InjectionSpeed3']) ?></td></tr>
                        <tr><td>Injection Pressure</td><td><?= htmlspecialchars($row['InjectionPressure1']) ?></td><td><?= htmlspecialchars($row['InjectionPressure2']) ?></td><td><?= htmlspecialchars($row['InjectionPressure3']) ?></td></tr>
                        <tr><td>Holding Pressure</td><td><?= htmlspecialchars($row['HoldingPressure1']) ?></td><td><?= htmlspecialchars($row['HoldingPressure2']) ?></td><td><?= htmlspecialchars($row['HoldingPressure3']) ?></td></tr>
                        <tr><td>Holding Speed</td><td><?= htmlspecialchars($row['HoldingSpeed1']) ?></td><td><?= htmlspecialchars($row['HoldingSpeed2']) ?></td><td><?= htmlspecialchars($row['HoldingSpeed3']) ?></td></tr>
                        <tr><td>Holding Time</td><td><?= htmlspecialchars($row['HoldingTime1']) ?></td><td><?= htmlspecialchars($row['HoldingTime2']) ?></td><td><?= htmlspecialchars($row['HoldingTime3']) ?></td></tr>
                        <tr><td>Recovery Position</td><td colspan="3"><?= htmlspecialchars($row['RecoveryPosition']) ?></td></tr>
                        <tr><td>Second Stage Position</td><td colspan="3"><?= htmlspecialchars($row['SecondStagePosition']) ?></td></tr>
                        <tr><td>Cushion</td><td colspan="3"><?= htmlspecialchars($row['Cushion']) ?></td></tr>
                        <tr><td>Suck Back Position</td><td colspan="3"><?= htmlspecialchars($row['SuckBackPosition']) ?></td></tr>
                        <tr><td>Suck Back Speed</td><td colspan="3"><?= htmlspecialchars($row['SuckBackSpeed']) ?></td></tr>
                        <tr><td>Suck Back Pressure</td><td colspan="3"><?= htmlspecialchars($row['SuckBackPressure']) ?></td></tr>
                        <tr><td>Sprue Break</td><td colspan="3"><?= htmlspecialchars($row['SprueBreak']) ?></td></tr>
                        <tr><td>Sprue Break Time</td><td colspan="3"><?= htmlspecialchars($row['SprueBreakTime']) ?></td></tr>
                        <tr><td>Injection Delay</td><td colspan="3"><?= htmlspecialchars($row['InjectionDelay']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Ejection Parameters -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Ejection Parameters</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Parameter</th><th>Forward 1</th><th>Forward 2</th><th>Retract 1</th><th>Retract 2</th></tr></thead>
                    <tbody>
                    <?php foreach ($ejectionParameters as $row): ?>
                        <tr><td>Position</td><td><?= htmlspecialchars($row['EjectorForwardPosition1']) ?></td><td><?= htmlspecialchars($row['EjectorForwardPosition2']) ?></td><td><?= htmlspecialchars($row['EjectorRetractPosition1']) ?></td><td><?= htmlspecialchars($row['EjectorRetractPosition2']) ?></td></tr>
                        <tr><td>Speed</td><td><?= htmlspecialchars($row['EjectorForwardSpeed1']) ?></td><td><?= htmlspecialchars($row['EjectorForwardSpeed2']) ?></td><td><?= htmlspecialchars($row['EjectorRetractSpeed1']) ?></td><td><?= htmlspecialchars($row['EjectorRetractSpeed2']) ?></td></tr>
                        <tr><td>Pressure</td><td><?= htmlspecialchars($row['EjectorForwardPressure1']) ?></td><td>-</td><td><?= htmlspecialchars($row['EjectorRetractPressure1']) ?></td><td>-</td></tr>
                        <tr><td>Air Blow Time A</td><td colspan="4"><?= htmlspecialchars($row['AirBlowTimeA']) ?></td></tr>
                        <tr><td>Air Blow Position A</td><td colspan="4"><?= htmlspecialchars($row['AirBlowPositionA']) ?></td></tr>
                        <tr><td>Air Blow A Delay</td><td colspan="4"><?= htmlspecialchars($row['AirBlowADelay']) ?></td></tr>
                        <tr><td>Air Blow Time B</td><td colspan="4"><?= htmlspecialchars($row['AirBlowTimeB']) ?></td></tr>
                        <tr><td>Air Blow Position B</td><td colspan="4"><?= htmlspecialchars($row['AirBlowPositionB']) ?></td></tr>
                        <tr><td>Air Blow B Delay</td><td colspan="4"><?= htmlspecialchars($row['AirBlowBDelay']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Core Pull Settings -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Core Pull Settings</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Section</th><th>Sequence</th><th>Pressure</th><th>Speed</th><th>Position</th><th>Time</th><th>Limit Switch</th></tr></thead>
                    <tbody>
                    <?php foreach ($corePullSettings as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Section']) ?></td>
                            <td><?= htmlspecialchars($row['Sequence']) ?></td>
                            <td><?= htmlspecialchars($row['Pressure']) ?></td>
                            <td><?= htmlspecialchars($row['Speed']) ?></td>
                            <td><?= htmlspecialchars($row['Position']) ?></td>
                            <td><?= htmlspecialchars($row['Time']) ?></td>
                            <td><?= htmlspecialchars($row['LimitSwitch']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Additional Information -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Additional Information</h5></div>
            <div class="card-body">
                <?php if (!empty($additionalInformation) && !empty($additionalInformation[0]['Info'])): ?>
                    <div class="p-3 border rounded"><?= nl2br(htmlspecialchars($additionalInformation[0]['Info'])) ?></div>
                <?php else: ?>
                    <p class="text-muted">No additional information provided.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Personnel -->
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Personnel</h5></div>
            <div class="card-body">
                <?php if (!empty($personnel)): ?>
                    <div class="row">
                        <div class="col-md-6"><p><strong>Adjuster:</strong> <?= htmlspecialchars($personnel[0]['AdjusterName']) ?></p></div>
                        <div class="col-md-6"><p><strong>QAE:</strong> <?= htmlspecialchars($personnel[0]['QAEName']) ?></p></div>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No personnel information available.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Attachments -->
        <?php if (!empty($attachments)): ?>
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Attachments</h5></div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($attachments as $file): ?>
                        <div class="col-md-4 mb-3">
                            <p><strong>File:</strong> <?= htmlspecialchars($file['FileName']) ?></p>
                            <p><strong>Type:</strong> <?= htmlspecialchars($file['FileType']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <!-- Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js"></script>
</body>
</html> 