<?php
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

require_once 'session_config.php';
require_once 'parameter_viewer.php'; // Include the enhanced parameter viewer

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html");
    exit();
}

// Load the centralized configuration
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/admin_notifications.php';

// Get database connection using the centralized system
try {
    $conn = DatabaseManager::getConnection('sentinel_main');
    // Set MySQL timezone to Philippine Time (UTC+8)
    $conn->query("SET time_zone = '+08:00'");
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get admin notifications for current user 
$admin_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
$notification_count = getUnviewedNotificationCount($_SESSION['id_number'], $_SESSION['full_name']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchData($conn, $tableName)
{
    $sql = "SELECT * FROM $tableName";
    return $conn->query($sql);
}

// Check if a specific record is requested
$selectedRecordId = isset($_GET['record_id']) ? $_GET['record_id'] : null;

// Fetch master records
$sql = "SELECT pr.* 
        FROM parameter_records pr
        ORDER BY pr.submission_date DESC";
$parameterRecords = $conn->query($sql);

// If a specific record is selected, fetch its details
if ($selectedRecordId) {
    // Fetch detailed record data
    $sql = "SELECT * FROM parameter_records WHERE record_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedRecordId);
    $stmt->execute();
    $recordDetails = $stmt->get_result()->fetch_assoc();

    // Fetch related data tables
    $productMachineInfo = fetchData($conn, "productmachineinfo WHERE record_id = '$selectedRecordId'");
    $productDetails = fetchData($conn, "productdetails WHERE record_id = '$selectedRecordId'");
    $materialComposition = fetchData($conn, "materialcomposition WHERE record_id = '$selectedRecordId'");
    $colorantDetails = fetchData($conn, "colorantdetails WHERE record_id = '$selectedRecordId'");
    $moldOperationSpecs = fetchData($conn, "moldoperationspecs WHERE record_id = '$selectedRecordId'");
    $barrelHeaterTemp = fetchData($conn, "barrelheatertemperatures WHERE record_id = '$selectedRecordId'");
    $moldHeaterTemp = fetchData($conn, "moldheatertemperatures WHERE record_id = '$selectedRecordId'");
    $timerParameters = fetchData($conn, "timerparameters WHERE record_id = '$selectedRecordId'");
    $moldCloseParams = fetchData($conn, "moldcloseparameters WHERE record_id = '$selectedRecordId'");
    $moldOpenParams = fetchData($conn, "moldopenparameters WHERE record_id = '$selectedRecordId'");
    $plasticizingParams = fetchData($conn, "plasticizingparameters WHERE record_id = '$selectedRecordId'");
    $injectionParams = fetchData($conn, "injectionparameters WHERE record_id = '$selectedRecordId'");
    $ejectionParams = fetchData($conn, "ejectionparameters WHERE record_id = '$selectedRecordId'");
    $corePullSettings = fetchData($conn, "corepullsettings WHERE record_id = '$selectedRecordId'");
    $additionalInformation = fetchData($conn, "additionalinformation WHERE record_id = '$selectedRecordId'");
    $personnel = fetchData($conn, "personnel WHERE record_id = '$selectedRecordId'");
}

?>
<?php include '../includes/navbar.php'; ?>

<!-- CSS for Submission page styling -->

                <div class="container-fluid px-4">
                    <h1 class="mt-4">Parameters Records</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Parameters</a></li>
                        <li class="breadcrumb-item active">Records</li>
                    </ol>

                    <?php if ($selectedRecordId && $recordDetails): ?>
                        <!-- Detailed Record View -->
                        <div class="mb-4">
                            <a href="submission.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Records</a>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white" style="background: linear-gradient(135deg, #007bff, #0056b3) !important; background-color: #007bff !important; color: white !important;">
                                <div class="d-flex justify-content-between">
                                    <h5><?= htmlspecialchars($recordDetails['title']) ?></h5>
                                    <span>Record ID: <?= htmlspecialchars($selectedRecordId) ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <p><strong>Submitted by:</strong> <?= htmlspecialchars($recordDetails['submitted_by']) ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Submission Date:</strong> <?= htmlspecialchars($recordDetails['submission_date']) ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-success"><?= htmlspecialchars(ucfirst($recordDetails['status'])) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <?php if ($recordDetails['description']): ?>
                                    <div class="mb-3">
                                        <p><strong>Description:</strong></p>
                                        <p><?= nl2br(htmlspecialchars($recordDetails['description'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Enhanced Parameter Display Sections -->
                        <?php 
                        // Combine all parameter data for the enhanced viewer
                        $allParameterData = [];
                        
                        // Collect data from all tables
                        if ($productMachineInfo && $productMachineInfo->num_rows > 0) {
                            $allParameterData = array_merge($allParameterData, $productMachineInfo->fetch_assoc());
                            $productMachineInfo->data_seek(0); // Reset pointer
                        }
                        
                        if ($productDetails && $productDetails->num_rows > 0) {
                            $allParameterData = array_merge($allParameterData, $productDetails->fetch_assoc());
                            $productDetails->data_seek(0);
                        }
                        
                        if ($materialComposition && $materialComposition->num_rows > 0) {
                            $allParameterData = array_merge($allParameterData, $materialComposition->fetch_assoc());
                            $materialComposition->data_seek(0);
                        }
                        
                        if ($barrelHeaterTemp && $barrelHeaterTemp->num_rows > 0) {
                            $barrelData = $barrelHeaterTemp->fetch_assoc();
                            foreach ($barrelData as $key => $value) {
                                $allParameterData['Barrel_' . $key] = $value;
                            }
                            $barrelHeaterTemp->data_seek(0);
                        }
                        
                        if ($moldHeaterTemp && $moldHeaterTemp->num_rows > 0) {
                            $moldData = $moldHeaterTemp->fetch_assoc();
                            foreach ($moldData as $key => $value) {
                                $allParameterData['Mold_' . $key] = $value;
                            }
                            $moldHeaterTemp->data_seek(0);
                        }
                        
                        // Add other parameter tables
                        $otherTables = [
                            'moldOperationSpecs', 'timerParameters', 'moldCloseParams', 
                            'moldOpenParams', 'plasticizingParams', 'injectionParams', 
                            'ejectionParams', 'additionalInformation', 'personnel'
                        ];
                        
                        foreach ($otherTables as $table) {
                            if (isset($$table) && $$table && $$table->num_rows > 0) {
                                $allParameterData = array_merge($allParameterData, $$table->fetch_assoc());
                                $$table->data_seek(0);
                            }
                        }
                        ?>
                        
                        <!-- Machine Information Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Machine Information',
                            'fas fa-cogs',
                            'primary',
                            [
                                'MachineName' => ['label' => 'Machine Name', 'type' => 'text'],
                                'RunNumber' => ['label' => 'Run Number', 'type' => 'text'],
                                'Category' => ['label' => 'Category', 'type' => 'text'],
                                'IRN' => ['label' => 'IRN', 'type' => 'text'],
                                'Date' => ['label' => 'Date', 'type' => 'date'],
                                'Time' => ['label' => 'Time', 'type' => 'time'],
                                'startTime' => ['label' => 'Start Time', 'type' => 'time'],
                                'endTime' => ['label' => 'End Time', 'type' => 'time']
                            ],
                            $allParameterData
                        ); ?>
                        
                        <!-- Product Details Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Product Details',
                            'fas fa-cube',
                            'info',
                            [
                                'ProductName' => ['label' => 'Product Name', 'type' => 'text'],
                                'ProductNumber' => ['label' => 'Product Number', 'type' => 'text'],
                                'Color' => ['label' => 'Color', 'type' => 'text'],
                                'MoldName' => ['label' => 'Mold Name', 'type' => 'text'],
                                'CavityActive' => ['label' => 'Active Cavities', 'type' => 'number'],
                                'GrossWeight' => ['label' => 'Gross Weight', 'type' => 'decimal', 'unit' => 'g'],
                                'NetWeight' => ['label' => 'Net Weight', 'type' => 'decimal', 'unit' => 'g'],
                                'DryingTime' => ['label' => 'Drying Time', 'type' => 'decimal', 'unit' => 'hours'],
                                'DryingTemperature' => ['label' => 'Drying Temperature', 'type' => 'decimal', 'unit' => '°C']
                            ],
                            $allParameterData
                        ); ?>
                        
                        <!-- Material Composition Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Material Composition',
                            'fas fa-flask',
                            'secondary',
                            [
                                'MaterialName' => ['label' => 'Material Name', 'type' => 'text'],
                                'Percentage' => ['label' => 'Percentage', 'type' => 'decimal', 'unit' => '%'],
                                'Supplier' => ['label' => 'Supplier', 'type' => 'text'],
                                'Grade' => ['label' => 'Grade', 'type' => 'text'],
                                'LotNumber' => ['label' => 'Lot Number', 'type' => 'text'],
                                'Properties' => ['label' => 'Properties', 'type' => 'text']
                            ],
                            $allParameterData
                        ); ?>
                        
                        <!-- Barrel Heater Temperatures Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Barrel Heater Temperatures',
                            'fas fa-thermometer-half',
                            'warning',
                            [
                                'Barrel_Zone1' => ['label' => 'Zone 1', 'type' => 'decimal', 'unit' => '°C'],
                                'Barrel_Zone2' => ['label' => 'Zone 2', 'type' => 'decimal', 'unit' => '°C'],
                                'Barrel_Zone3' => ['label' => 'Zone 3', 'type' => 'decimal', 'unit' => '°C'],
                                'Barrel_Zone4' => ['label' => 'Zone 4', 'type' => 'decimal', 'unit' => '°C'],
                                'Barrel_Zone5' => ['label' => 'Zone 5', 'type' => 'decimal', 'unit' => '°C'],
                                'Barrel_Zone6' => ['label' => 'Zone 6', 'type' => 'decimal', 'unit' => '°C'],
                                'Barrel_Zone7' => ['label' => 'Zone 7', 'type' => 'decimal', 'unit' => '°C'],
                                'Barrel_Zone8' => ['label' => 'Zone 8', 'type' => 'decimal', 'unit' => '°C']
                            ],
                            $allParameterData
                        ); ?>
                        
                        <!-- Mold Heater Temperatures Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Mold Heater Temperatures',
                            'fas fa-fire',
                            'danger',
                            [
                                'Mold_Zone1' => ['label' => 'Zone 1', 'type' => 'decimal', 'unit' => '°C'],
                                'Mold_Zone2' => ['label' => 'Zone 2', 'type' => 'decimal', 'unit' => '°C'],
                                'Mold_Zone3' => ['label' => 'Zone 3', 'type' => 'decimal', 'unit' => '°C'],
                                'Mold_Zone4' => ['label' => 'Zone 4', 'type' => 'decimal', 'unit' => '°C'],
                                'Mold_Zone5' => ['label' => 'Zone 5', 'type' => 'decimal', 'unit' => '°C'],
                                'Mold_Zone6' => ['label' => 'Zone 6', 'type' => 'decimal', 'unit' => '°C'],
                                'Mold_Zone7' => ['label' => 'Zone 7', 'type' => 'decimal', 'unit' => '°C'],
                                'Mold_Zone8' => ['label' => 'Zone 8', 'type' => 'decimal', 'unit' => '°C']
                            ],
                            $allParameterData
                        ); ?>

                        <!-- Mold Close Parameters Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Mold Close Parameters',
                            'fas fa-compress-arrows-alt',
                            'warning',
                            [
                                'MoldClosePos1' => ['label' => 'Position 1', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldClosePos2' => ['label' => 'Position 2', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldClosePos3' => ['label' => 'Position 3', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldClosePos4' => ['label' => 'Position 4', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldClosePos5' => ['label' => 'Position 5', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldClosePos6' => ['label' => 'Position 6', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldCloseSpd1' => ['label' => 'Speed 1', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldCloseSpd2' => ['label' => 'Speed 2', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldCloseSpd3' => ['label' => 'Speed 3', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldCloseSpd4' => ['label' => 'Speed 4', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldCloseSpd5' => ['label' => 'Speed 5', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldCloseSpd6' => ['label' => 'Speed 6', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldClosePressure1' => ['label' => 'Pressure 1', 'type' => 'decimal', 'unit' => 'bar'],
                                'MoldClosePressure2' => ['label' => 'Pressure 2', 'type' => 'decimal', 'unit' => 'bar'],
                                'MoldClosePressure3' => ['label' => 'Pressure 3', 'type' => 'decimal', 'unit' => 'bar'],
                                'MoldClosePressure4' => ['label' => 'Pressure 4', 'type' => 'decimal', 'unit' => 'bar'],
                                'PCLORLP' => ['label' => 'PC LO/RLP', 'type' => 'text'],
                                'PCHORHP' => ['label' => 'PC HO/RHP', 'type' => 'text'],
                                'LowPresTimeLimit' => ['label' => 'Low Pressure Time Limit', 'type' => 'decimal', 'unit' => 's']
                            ],
                            $allParameterData
                        ); ?>

                        <!-- Mold Open Parameters Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Mold Open Parameters',
                            'fas fa-expand-arrows-alt',
                            'info',
                            [
                                'MoldOpenPos1' => ['label' => 'Position 1', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldOpenPos2' => ['label' => 'Position 2', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldOpenPos3' => ['label' => 'Position 3', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldOpenPos4' => ['label' => 'Position 4', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldOpenPos5' => ['label' => 'Position 5', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldOpenPos6' => ['label' => 'Position 6', 'type' => 'decimal', 'unit' => 'mm'],
                                'MoldOpenSpd1' => ['label' => 'Speed 1', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldOpenSpd2' => ['label' => 'Speed 2', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldOpenSpd3' => ['label' => 'Speed 3', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldOpenSpd4' => ['label' => 'Speed 4', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldOpenSpd5' => ['label' => 'Speed 5', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldOpenSpd6' => ['label' => 'Speed 6', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'MoldOpenPressure1' => ['label' => 'Pressure 1', 'type' => 'decimal', 'unit' => 'bar'],
                                'MoldOpenPressure2' => ['label' => 'Pressure 2', 'type' => 'decimal', 'unit' => 'bar'],
                                'MoldOpenPressure3' => ['label' => 'Pressure 3', 'type' => 'decimal', 'unit' => 'bar'],
                                'MoldOpenPressure4' => ['label' => 'Pressure 4', 'type' => 'decimal', 'unit' => 'bar'],
                                'MoldOpenPressure5' => ['label' => 'Pressure 5', 'type' => 'decimal', 'unit' => 'bar'],
                                'MoldOpenPressure6' => ['label' => 'Pressure 6', 'type' => 'decimal', 'unit' => 'bar']
                            ],
                            $allParameterData
                        ); ?>

                        <!-- Process Parameters Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Process Parameters',
                            'fas fa-sliders-h',
                            'success',
                            [
                                'InjectionPressure' => ['label' => 'Injection Pressure', 'type' => 'decimal', 'unit' => 'bar'],
                                'InjectionSpeed' => ['label' => 'Injection Speed', 'type' => 'decimal', 'unit' => 'mm/s'],
                                'HoldPressure' => ['label' => 'Hold Pressure', 'type' => 'decimal', 'unit' => 'bar'],
                                'HoldTime' => ['label' => 'Hold Time', 'type' => 'decimal', 'unit' => 's'],
                                'CoolingTime' => ['label' => 'Cooling Time', 'type' => 'decimal', 'unit' => 's'],
                                'CycleTime' => ['label' => 'Cycle Time', 'type' => 'decimal', 'unit' => 's'],
                                'ScrewSpeed' => ['label' => 'Screw Speed', 'type' => 'decimal', 'unit' => 'rpm'],
                                'BackPressure' => ['label' => 'Back Pressure', 'type' => 'decimal', 'unit' => 'bar']
                            ],
                            $allParameterData
                        ); ?>
                        
                        <!-- Core Pull Settings Section -->
                        <?php if ($corePullSettings && $corePullSettings->num_rows > 0): ?>
                            <div class="card mb-4">
                                <div class="card-header bg-dark text-white">
                                    <i class="fas fa-arrows-alt me-2"></i>Core Pull Settings
                                    <span class="badge bg-light text-dark ms-2"><?= $corePullSettings->num_rows ?> configurations</span>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th><i class="fas fa-layer-group me-1"></i>Section</th>
                                                <th><i class="fas fa-list-ol me-1"></i>Sequence</th>
                                                <th><i class="fas fa-tachometer-alt me-1"></i>Pressure</th>
                                                <th><i class="fas fa-shipping-fast me-1"></i>Speed</th>
                                                <th><i class="fas fa-crosshairs me-1"></i>Position</th>
                                                <th><i class="fas fa-clock me-1"></i>Time</th>
                                                <th><i class="fas fa-toggle-on me-1"></i>Limit Switch</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $corePullSettings->fetch_assoc()): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($row['Section'] ?? '') ?></span>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['Sequence'] ?? 'Not set') ?></td>
                                                    <td>
                                                        <?php if (!empty($row['Pressure'])): ?>
                                                            <strong><?= htmlspecialchars($row['Pressure']) ?></strong> bar
                                                        <?php else: ?>
                                                            <span class="text-muted fst-italic">Not set</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($row['Speed'])): ?>
                                                            <strong><?= htmlspecialchars($row['Speed']) ?></strong> mm/s
                                                        <?php else: ?>
                                                            <span class="text-muted fst-italic">Not set</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($row['Position'])): ?>
                                                            <strong><?= htmlspecialchars($row['Position']) ?></strong> mm
                                                        <?php else: ?>
                                                            <span class="text-muted fst-italic">Not set</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($row['Time'])): ?>
                                                            <strong><?= htmlspecialchars($row['Time']) ?></strong> s
                                                        <?php else: ?>
                                                            <span class="text-muted fst-italic">Not set</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($row['LimitSwitch'])): ?>
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check me-1"></i><?= htmlspecialchars($row['LimitSwitch']) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted fst-italic">Not set</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Personnel Information Section -->
                        <?= ParameterViewer::renderParameterSection(
                            'Personnel Information',
                            'fas fa-users',
                            'primary',
                            [
                                'AdjusterName' => ['label' => 'Adjuster Name', 'type' => 'text'],
                                'QAEName' => ['label' => 'QAE Name', 'type' => 'text'],
                                'startTime' => ['label' => 'Start Time', 'type' => 'time'],
                                'endTime' => ['label' => 'End Time', 'type' => 'time']
                            ],
                            $allParameterData
                        ); ?>
                        
                        <!-- Additional Information Section -->
                        <?php if ($additionalInformation && $additionalInformation->num_rows > 0): ?>
                            <?php $additionalData = $additionalInformation->fetch_assoc(); ?>
                            <?php if (!empty(array_filter($additionalData, function($v) { return $v !== null && $v !== ''; }))): ?>
                                <div class="card mb-4">
                                    <div class="card-header bg-dark text-white">
                                        <i class="fas fa-info-circle me-2"></i>Additional Information
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <?php foreach ($additionalData as $key => $value): ?>
                                                <?php if ($key !== 'record_id' && $key !== 'id' && $value !== null && $value !== ''): ?>
                                                    <div class="col-md-6">
                                                        <div class="border rounded p-3 bg-light">
                                                            <strong class="text-dark"><?= ucwords(str_replace('_', ' ', $key)) ?></strong>
                                                            <div class="mt-1"><?= nl2br(htmlspecialchars($value)) ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- Records List View -->
                        <div class="card">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table me-1"></i>
                                    Parameters Records (<?= $parameterRecords->num_rows ?>)
                                </div>
                                <div class="btn-group">
                                    <a href="export_data.php?format=pdf" class="btn btn-danger btn-sm" target="_blank">
                                        <i class="fas fa-file-pdf"></i> Export All PDF
                                    </a>
                                    <a href="export_data.php?format=excel" class="btn btn-success btn-sm" target="_blank">
                                        <i class="fas fa-file-excel"></i> Export All Excel
                                    </a>
                                    <a href="export_data.php?format=sql" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-database"></i> Export All SQL
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="parametersTable" class="table table-striped table-bordered table-records">
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
                                            <?php if ($parameterRecords && $parameterRecords->num_rows > 0): ?>
                                                <?php while ($row = $parameterRecords->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['record_id']) ?></td>
                                                        <td><?= htmlspecialchars($row['title']) ?></td>
                                                        <td><?= htmlspecialchars($row['submitted_by']) ?></td>
                                                        <td><?= htmlspecialchars($row['submission_date']) ?></td>
                                                        <td>
                                                            <span class="badge bg-success">
                                                                <?= htmlspecialchars(ucfirst($row['status'])) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a href="submission.php?record_id=<?= urlencode($row['record_id']) ?>" 
                                                                   class="btn btn-outline-primary btn-sm" 
                                                                   title="View Details">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button class="btn btn-outline-info btn-sm print-record"
                                                                    data-record-id="<?= htmlspecialchars($row['record_id']) ?>"
                                                                    title="Print Record">
                                                                    <i class="fas fa-print"></i>
                                                                </button>
                                                                <form method="POST" action="index.php" class="d-inline">
                                                                    <input type="hidden" name="clone_record_id"
                                                                        value="<?= htmlspecialchars($row['record_id']) ?>">
                                                                    <button type="submit" class="btn btn-outline-warning btn-sm"
                                                                            title="Apply to Form">
                                                                        <i class="fas fa-clone"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No records found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

    <script>
        $(document).ready(function() {
            // DataTables will be initialized by universal script
            
            // Print record handler
            $('.print-record').click(function () {
                const recordId = $(this).data('record-id');
                window.open('print_record.php?record_id=' + recordId, '_blank');
            });
        });
    </script>

<?php include '../includes/navbar_close.php'; ?>
