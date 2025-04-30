<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

// Database connection details
$servername = "localhost";
$username = "root";
$password = "injectionadmin123";
$dbname = "injectionmoldingparameters";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$conn->begin_transaction();
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to track errors
$errors = [];

// Insert into productmachineinfo table
if (isset($_POST['Date'], $_POST['Time'], $_POST['MachineName'], $_POST['RunNumber'], $_POST['Category'], $_POST['IRN'])) {
    $sql = "INSERT INTO productmachineinfo (Date, Time, MachineName, RunNumber, Category, IRN)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $_POST['Date'], $_POST['Time'], $_POST['MachineName'], $_POST['RunNumber'], $_POST['Category'], $_POST['IRN']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into productmachineinfo: " . $stmt->error;
    }
}

// Insert into productdetails table
if (isset($_POST['product'], $_POST['color'], $_POST['mold-name'], $_POST['prodNo'], $_POST['cavity'], $_POST['grossWeight'], $_POST['netWeight'])) {
    $sql = "INSERT INTO productdetails (ProductName, Color, MoldName, ProductNumber, CavityActive, GrossWeight, NetWeight)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssidd", $_POST['product'], $_POST['color'], $_POST['mold-name'], $_POST['prodNo'], $_POST['cavity'], $_POST['grossWeight'], $_POST['netWeight']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into productdetails: " . $stmt->error;
    }
}

// Insert into materialcomposition table
if (isset($_POST['dryingtime'], $_POST['dryingtemp'])) {
    // Ensure optional material fields are set or provide defaults
    $type1 = isset($_POST['type1']) ? $_POST['type1'] : null;
    $brand1 = isset($_POST['brand1']) ? $_POST['brand1'] : null;
    $mix1 = isset($_POST['mix1']) ? $_POST['mix1'] : null;

    $type2 = isset($_POST['type2']) ? $_POST['type2'] : null;
    $brand2 = isset($_POST['brand2']) ? $_POST['brand2'] : null;
    $mix2 = isset($_POST['mix2']) ? $_POST['mix2'] : null;

    $type3 = isset($_POST['type3']) ? $_POST['type3'] : null;
    $brand3 = isset($_POST['brand3']) ? $_POST['brand3'] : null;
    $mix3 = isset($_POST['mix3']) ? $_POST['mix3'] : null;

    $type4 = isset($_POST['type4']) ? $_POST['type4'] : null;
    $brand4 = isset($_POST['brand4']) ? $_POST['brand4'] : null;
    $mix4 = isset($_POST['mix4']) ? $_POST['mix4'] : null;

    $sql = "INSERT INTO materialcomposition (
                DryingTime, DryingTemperature, 
                Material1_Type, Material1_Brand, Material1_MixturePercentage,
                Material2_Type, Material2_Brand, Material2_MixturePercentage,
                Material3_Type, Material3_Brand, Material3_MixturePercentage,
                Material4_Type, Material4_Brand, Material4_MixturePercentage
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ddssdssdssdssd",
        $_POST['dryingtime'],           // Drying Time
        $_POST['dryingtemp'],           // Drying Temperature
        $type1,
        $brand1,
        $mix1,         // Material 1
        $type2,
        $brand2,
        $mix2,         // Material 2
        $type3,
        $brand3,
        $mix3,         // Material 3
        $type4,
        $brand4,
        $mix4          // Material 4
    );

    if (!$stmt->execute()) {
        $errors[] = "Error inserting into materialcomposition: " . $stmt->error;
    }
}


// Insert into colorantdetails table
if (isset($_POST['colorant'], $_POST['color'], $_POST['colorant-dosage'], $_POST['colorant-stabilizer'], $_POST['colorant-stabilizer-dosage'])) {
    $sql = "INSERT INTO colorantdetails (Colorant, Color, Dosage, Stabilizer, StabilizerDosage)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $_POST['colorant'], $_POST['colorantColor'], $_POST['colorant-dosage'], $_POST['colorant-stabilizer'], $_POST['colorant-stabilizer-dosage']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into colorantdetails: " . $stmt->error;
    }
}

// Insert into moldoperationspecs table
if (isset($_POST['mold-code'], $_POST['clamping-force'], $_POST['operation-type'], $_POST['cooling-media'], $_POST['heating-media'])) {
    $sql = "INSERT INTO moldoperationspecs (MoldCode, ClampingForce, OperationType, CoolingMedia, HeatingMedia)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $_POST['mold-code'], $_POST['clamping-force'], $_POST['operation-type'], $_POST['cooling-media'], $_POST['heating-media']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into moldoperationspecs: " . $stmt->error;
    }
}

// Insert into timerparameters table
if (isset($_POST['fillingTime'], $_POST['holdingTime'], $_POST['moldOpenCloseTime'], $_POST['chargingTime'], $_POST['coolingTime'], $_POST['cycleTime'])) {
    $sql = "INSERT INTO timerparameters (FillingTime, HoldingTime, MoldOpenCloseTime, ChargingTime, CoolingTime, CycleTime)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dddddd", $_POST['fillingTime'], $_POST['holdingTime'], $_POST['moldOpenCloseTime'], $_POST['chargingTime'], $_POST['coolingTime'], $_POST['cycleTime']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into timerparameters: " . $stmt->error;
    }
}

// Insert into barrelheatertemperatures table
if (isset($_POST['barrelHeaterZone0'], $_POST['barrelHeaterZone1'], $_POST['barrelHeaterZone2'])) {
    $sql = "INSERT INTO barrelheatertemperatures (
                Zone0, Zone1, Zone2, Zone3, Zone4, Zone5, Zone6, Zone7, Zone8, Zone9,
                Zone10, Zone11, Zone12, Zone13, Zone14, Zone15, Zone16
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ddddddddddddddddd",
        $_POST['barrelHeaterZone0'],
        $_POST['barrelHeaterZone1'],
        $_POST['barrelHeaterZone2'],
        $_POST['barrelHeaterZone3'],
        $_POST['barrelHeaterZone4'],
        $_POST['barrelHeaterZone5'],
        $_POST['barrelHeaterZone6'],
        $_POST['barrelHeaterZone7'],
        $_POST['barrelHeaterZone8'],
        $_POST['barrelHeaterZone9'],
        $_POST['barrelHeaterZone10'],
        $_POST['barrelHeaterZone11'],
        $_POST['barrelHeaterZone12'],
        $_POST['barrelHeaterZone13'],
        $_POST['barrelHeaterZone14'],
        $_POST['barrelHeaterZone15'],
        $_POST['barrelHeaterZone16']
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into barrelheatertemperatures: " . $stmt->error;
    }
}

// Insert into moldheatertemperatures table
if (isset($_POST['Zone0'], $_POST['Zone1'], $_POST['MTCSetting'])) {
    $sql = "INSERT INTO moldheatertemperatures (
                Zone0, Zone1, Zone2, Zone3, Zone4, Zone5, Zone6, Zone7, Zone8, Zone9,
                Zone10, Zone11, Zone12, Zone13, Zone14, Zone15, Zone16, MTCSetting
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "dddddddddddddddddd",
        $_POST['Zone0'],
        $_POST['Zone1'],
        $_POST['Zone2'],
        $_POST['Zone3'],
        $_POST['Zone4'],
        $_POST['Zone5'],
        $_POST['Zone6'],
        $_POST['Zone7'],
        $_POST['Zone8'],
        $_POST['Zone9'],
        $_POST['Zone10'],
        $_POST['Zone11'],
        $_POST['Zone12'],
        $_POST['Zone13'],
        $_POST['Zone14'],
        $_POST['Zone15'],
        $_POST['Zone16'],
        $_POST['MTCSetting']
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into moldheatertemperatures: " . $stmt->error;
    }
}

// Insert into plasticizingparameters table
if (isset($_POST['screwRPM1'], $_POST['screwSpeed1'], $_POST['plastPressure1'])) {
    $sql = "INSERT INTO plasticizingparameters (
                ScrewRPM1, ScrewRPM2, ScrewRPM3,
                ScrewSpeed1, ScrewSpeed2, ScrewSpeed3,
                PlastPressure1, PlastPressure2, PlastPressure3,
                PlastPosition1, PlastPosition2, PlastPosition3,
                BackPressure1, BackPressure2, BackPressure3,
                BackPressureStartPosition
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "dddddddddddddddd",
        $_POST['screwRPM1'],
        $_POST['screwRPM2'],
        $_POST['screwRPM3'],
        $_POST['screwSpeed1'],
        $_POST['screwSpeed2'],
        $_POST['screwSpeed3'],
        $_POST['plastPressure1'],
        $_POST['plastPressure2'],
        $_POST['plastPressure3'],
        $_POST['plastPosition1'],
        $_POST['plastPosition2'],
        $_POST['plastPosition3'],
        $_POST['backPressure1'],
        $_POST['backPressure2'],
        $_POST['backPressure3'],
        $_POST['backPressureStartPosition']
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into plasticizingparameters: " . $stmt->error;
    }
}

// Insert into injectionparameters table
if (isset($_POST['RecoveryPosition'], $_POST['ScrewPosition1'], $_POST['InjectionSpeed1'])) {
    $sql = "INSERT INTO injectionparameters (
                RecoveryPosition, SecondStagePosition, Cushion,
                ScrewPosition1, ScrewPosition2, ScrewPosition3,
                InjectionSpeed1, InjectionSpeed2, InjectionSpeed3,
                InjectionPressure1, InjectionPressure2, InjectionPressure3,
                SuckBackPosition, SuckBackSpeed, SuckBackPressure,
                SprueBreak, SprueBreakTime, InjectionDelay,
                HoldingPressure1, HoldingPressure2, HoldingPressure3,
                HoldingSpeed1, HoldingSpeed2, HoldingSpeed3,
                HoldingTime1, HoldingTime2, HoldingTime3
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Assign POST values to variables, with fallbacks
    $RecoveryPosition = $_POST['RecoveryPosition'] ?? null;
    $SecondStagePosition = $_POST['SecondStagePosition'] ?? null;
    $Cushion = $_POST['Cushion'] ?? null;
    $ScrewPosition1 = $_POST['ScrewPosition1'] ?? null;
    $ScrewPosition2 = $_POST['ScrewPosition2'] ?? null;
    $ScrewPosition3 = $_POST['ScrewPosition3'] ?? null;
    $InjectionSpeed1 = $_POST['InjectionSpeed1'] ?? null;
    $InjectionSpeed2 = $_POST['InjectionSpeed2'] ?? null;
    $InjectionSpeed3 = $_POST['InjectionSpeed3'] ?? null;
    $InjectionPressure1 = $_POST['InjectionPressure1'] ?? null;
    $InjectionPressure2 = $_POST['InjectionPressure2'] ?? null;
    $InjectionPressure3 = $_POST['InjectionPressure3'] ?? null;
    $SuckBackPosition = $_POST['SuckBackPosition'] ?? null;
    $SuckBackSpeed = $_POST['SuckBackSpeed'] ?? null;
    $SuckBackPressure = $_POST['SuckBackPressure'] ?? null;
    $SprueBreak = $_POST['SprueBreak'] ?? null;
    $SprueBreakTime = $_POST['SprueBreakTime'] ?? null;
    $InjectionDelay = $_POST['InjectionDelay'] ?? null;
    $HoldingPressure1 = $_POST['HoldingPressure1'] ?? null;
    $HoldingPressure2 = $_POST['HoldingPressure2'] ?? null;
    $HoldingPressure3 = $_POST['HoldingPressure3'] ?? null;
    $HoldingSpeed1 = $_POST['HoldingSpeed1'] ?? null;
    $HoldingSpeed2 = $_POST['HoldingSpeed2'] ?? null;
    $HoldingSpeed3 = $_POST['HoldingSpeed3'] ?? null;
    $HoldingTime1 = $_POST['HoldingTime1'] ?? null;
    $HoldingTime2 = $_POST['HoldingTime2'] ?? null;
    $HoldingTime3 = $_POST['HoldingTime3'] ?? null;

    // Bind the parameters
    $stmt->bind_param(
        "ddddddddddddddddddddddddddd",
        $RecoveryPosition,
        $SecondStagePosition,
        $Cushion,
        $ScrewPosition1,
        $ScrewPosition2,
        $ScrewPosition3,
        $InjectionSpeed1,
        $InjectionSpeed2,
        $InjectionSpeed3,
        $InjectionPressure1,
        $InjectionPressure2,
        $InjectionPressure3,
        $SuckBackPosition,
        $SuckBackSpeed,
        $SuckBackPressure,
        $SprueBreak,
        $SprueBreakTime,
        $InjectionDelay,
        $HoldingPressure1,
        $HoldingPressure2,
        $HoldingPressure3,
        $HoldingSpeed1,
        $HoldingSpeed2,
        $HoldingSpeed3,
        $HoldingTime1,
        $HoldingTime2,
        $HoldingTime3
    );
} else {
    echo "Required fields are missing.";
}



// Insert into ejectionparameters table
if (isset($_POST['AirBlowTimeA'], $_POST['EjectorForwardPosition1'], $_POST['EjectorForwardSpeed2'])) {
    $sql = "INSERT INTO ejectionparameters (
                AirBlowTimeA, AirBlowPositionA, AirBlowADelay,
                AirBlowTimeB, AirBlowPositionB, AirBlowBDelay,
                EjectorForwardPosition1, EjectorForwardPosition2, EjectorForwardSpeed1,
                EjectorRetractPosition1, EjectorRetractPosition2, EjectorRetractSpeed1,
                EjectorForwardSpeed2, EjectorForwardPressure1, EjectorRetractSpeed2,
                EjectorRetractPressure1
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "dddddddddddddddd",
        $_POST['AirBlowTimeA'],
        $_POST['AirBlowPositionA'],
        $_POST['AirBlowADelay'],
        $_POST['AirBlowTimeB'],
        $_POST['AirBlowPositionB'],
        $_POST['AirBlowBDelay'],
        $_POST['EjectorForwardPosition1'],
        $_POST['EjectorForwardPosition2'],
        $_POST['EjectorForwardSpeed1'],
        $_POST['EjectorRetractPosition1'],
        $_POST['EjectorRetractPosition2'],
        $_POST['EjectorRetractSpeed1'],
        $_POST['EjectorForwardSpeed2'],
        $_POST['EjectorForwardPressure1'],
        $_POST['EjectorRetractSpeed2'],
        $_POST['EjectorRetractPressure1']
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into ejectionparameters: " . $stmt->error;
    }
}

// Insert into corepullsettings table
if (isset($_POST['coreSetASequence'], $_POST['coreSetAPressure'], $_POST['coreSetASpeed'])) {
    // Insert Core Pull Settings for all sections
    $coreSections = [
        'A' => [
            'Set' => [
                'sequence' => $_POST['coreSetASequence'] ?? null,
                'pressure' => $_POST['coreSetAPressure'] ?? null,
                'speed' => $_POST['coreSetASpeed'] ?? null,
                'position' => $_POST['coreSetAPosition'] ?? null,
                'time' => $_POST['coreSetATime'] ?? null,
                'limit' => $_POST['coreSetALimitSwitch'] ?? null
            ],
            'Pull' => [
                'sequence' => $_POST['corePullASequence'] ?? null,
                'pressure' => $_POST['corePullAPressure'] ?? null,
                'speed' => $_POST['corePullASpeed'] ?? null,
                'position' => $_POST['corePullAPosition'] ?? null,
                'time' => $_POST['corePullATime'] ?? null,
                'limit' => $_POST['corePullALimitSwitch'] ?? null
            ]
        ],
        'B' => [
            'Set' => [
                'sequence' => $_POST['coreSetBSequence'] ?? null,
                'pressure' => $_POST['coreSetBPressure'] ?? null,
                'speed' => $_POST['coreSetBSpeed'] ?? null,
                'position' => $_POST['coreSetBPosition'] ?? null,
                'time' => $_POST['coreSetBTime'] ?? null,
                'limit' => $_POST['coreSetBLimitSwitch'] ?? null
            ],
            'Pull' => [
                'sequence' => $_POST['corePullBSequence'] ?? null,
                'pressure' => $_POST['corePullBPressure'] ?? null,
                'speed' => $_POST['corePullBSpeed'] ?? null,
                'position' => $_POST['corePullBPosition'] ?? null,
                'time' => $_POST['corePullBTime'] ?? null,
                'limit' => $_POST['corePullBLimitSwitch'] ?? null
            ]
        ]
    ];

    $sqlCore = "INSERT INTO corepullsettings (Section, Sequence, Pressure, Speed, Position, Time, LimitSwitch)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtCore = $conn->prepare($sqlCore);

    foreach ($coreSections as $section => $types) {
        foreach ($types as $type => $fields) {
            $sectionName = "Core " . ucfirst($type) . " " . $section;
            $stmtCore->bind_param(
                "sidddds",
                $sectionName,
                $fields['sequence'],
                $fields['pressure'],
                $fields['speed'],
                $fields['position'],
                $fields['time'],
                $fields['limit']
            );
            if (!$stmtCore->execute()) {
                $errors[] = "Error inserting core settings for $sectionName: " . $stmtCore->error;
            }
        }
    }
}

// Insert into additionalinformation table
if (isset($_POST['additionalInfo'])) {
    $sql = "INSERT INTO additionalinformation (Info) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_POST['additionalInfo']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into additionalinformation: " . $stmt->error;
    }
}

// Insert into personnel table
if (isset($_POST['adjuster'], $_POST['qae'])) {
    $sql = "INSERT INTO personnel (AdjusterName, QAEName) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $_POST['adjuster'], $_POST['qae']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into personnel: " . $stmt->error;
    }
}

// Modified File Upload Handling
function handleFileUpload($files, $uploadDir, $typeCategory, $allowedExtensions, $maxSize, $irn, $date, $time, $machineNumber, $runNumber)
{
    $uploadedFiles = [];

    if (!isset($files['name']) || !is_array($files['name'])) {
        return $uploadedFiles;
    }

    foreach ($files['name'] as $key => $name) {
        if ($files['error'][$key] !== UPLOAD_ERR_OK) {
            error_log("Upload error for file $name: Code " . $files['error'][$key]);
            continue;
        }

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            error_log("Invalid extension for file $name");
            continue;
        }

        if ($files['size'][$key] > $maxSize) {
            error_log("File $name exceeds size limit");
            continue;
        }

        // Create upload directory if not exists
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                error_log("Failed to create directory: $uploadDir");
                continue;
            }
        }

        // Generate unique and safe filename
        $uniqueId = uniqid();
        $safeName = sprintf(
            "%s_%s_%s_%s_%s_%s.%s", // Added unique ID
            $irn,
            $date,
            $time,
            $machineNumber,
            $runNumber,
            $uniqueId,
            $ext
        );
        $targetPath = $uploadDir . $safeName;

        // Move uploaded file and log errors
        if (move_uploaded_file($files['tmp_name'][$key], $targetPath)) {
            $uploadedFiles[] = [
                'name' => $safeName, // Use sanitized name, not original
                'path' => $targetPath,
                'type' => $typeCategory
            ];
        } else {
            error_log("Failed to move uploaded file $name to $targetPath");
            error_log("Check permissions for: " . $uploadDir);
        }
    }
    return $uploadedFiles;
}

// Handle file uploads with proper validation
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/parameters/uploads/';
$uploadedImages = [];
$uploadedVideos = [];

// Sanitize parameters for filename
$irn = preg_replace('/[^A-Za-z0-9]/', '', $_POST['IRN']);
$dateFormatted = preg_replace('/[^0-9]/', '', $_POST['Date']); // YYYYMMDD
$timeFormatted = preg_replace('/[^0-9]/', '', $_POST['Time']); // HHMMSS
$machineNumber = preg_replace('/[^A-Za-z0-9]/', '', $_POST['MachineName']);
$runNumber = preg_replace('/[^A-Za-z0-9]/', '', $_POST['RunNumber']);

// Handle file uploads with proper validation
$uploadedImages = [];
$uploadedVideos = [];

if (isset($_FILES['uploadImages'])) {
    $uploadedImages = handleFileUpload(
        $_FILES['uploadImages'],
        $uploadDir,
        'image',
        ['jpg', 'jpeg', 'png', 'gif'],
        5000000, // 5MB
        $irn,
        $dateFormatted,
        $timeFormatted,
        $machineNumber,
        $runNumber
    );
}

if (isset($_FILES['uploadVideos'])) {
    $uploadedVideos = handleFileUpload(
        $_FILES['uploadVideos'],
        $uploadDir,
        'video',
        ['mp4', 'avi', 'mov', 'mkv'],
        50000000, // 50MB
        $irn,
        $dateFormatted,
        $timeFormatted,
        $machineNumber,
        $runNumber
    );
}

// Insert attachments into database
if (!empty($uploadedImages) || !empty($uploadedVideos)) {
    $sql = "INSERT INTO attachments (FileName, FilePath, FileType) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    foreach (array_merge($uploadedImages, $uploadedVideos) as $file) {
        $stmt->bind_param(
            "sss",
            $file['name'],
            $file['path'],
            $file['type']
        );

        if (!$stmt->execute()) {
            $errors[] = "Error inserting attachment: " . $stmt->error;
        }
    }
}

// -------------------------
// Finalize transaction and set modal content
if (empty($errors)) {
    $conn->commit();
    $modalHeader = '<div class="modal-header bg-success text-white">
                      <h5 class="modal-title" id="resultModalLabel">Success</h5>
                    </div>';
    $modalBody = '<div class="modal-body">All data has been submitted successfully! You will be redirected shortly.</div>';
} else {
    $conn->rollback();
    $errorMsg = implode('<br>', $errors);
    $modalHeader = '<div class="modal-header bg-danger text-white">
                      <h5 class="modal-title" id="resultModalLabel">Error</h5>
                    </div>';
    $modalBody = '<div class="modal-body">The following errors occurred:<br>' . $errorMsg . '</div>';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Redirect after 3 seconds -->
    <meta http-equiv="refresh" content="3;url=submission.php">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submission Result</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Bootstrap Modal Popup -->
    <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?php echo $modalHeader; ?>
                <?php echo $modalBody; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Show the modal on page load
            $('#resultModal').modal('show');
        });
    </script>
</body>

</html>