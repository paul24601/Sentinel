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

// Generate a unique record_id
$record_id = 'PARAM_' . date('Ymd') . '_' . substr(uniqid(), -5);

// Insert into parameter_records table
session_start();
$submitted_by = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Unknown User';
$title = isset($_POST['MachineName']) ? $_POST['MachineName'] . ' - ' . $_POST['product'] : 'Unnamed Record';
$description = isset($_POST['additionalInfo']) ? $_POST['additionalInfo'] : '';

$sql = "INSERT INTO parameter_records (record_id, submitted_by, title, description) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $record_id, $submitted_by, $title, $description);
if (!$stmt->execute()) {
    $errors[] = "Error creating record: " . $stmt->error;
}

// Initialize variables to track errors
$errors = [];

// Insert into productmachineinfo table
if (isset($_POST['Date'], $_POST['Time'], $_POST['MachineName'], $_POST['RunNumber'], $_POST['Category'], $_POST['IRN'])) {
    $sql = "INSERT INTO productmachineinfo (record_id, Date, Time, MachineName, RunNumber, Category, IRN)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $record_id, $_POST['Date'], $_POST['Time'], $_POST['MachineName'], $_POST['RunNumber'], $_POST['Category'], $_POST['IRN']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into productmachineinfo: " . $stmt->error;
    }
}

// Insert into productdetails table
if (isset($_POST['product'], $_POST['color'], $_POST['mold-name'], $_POST['prodNo'], $_POST['cavity'], $_POST['grossWeight'], $_POST['netWeight'])) {
    $sql = "INSERT INTO productdetails (record_id, ProductName, Color, MoldName, ProductNumber, CavityActive, GrossWeight, NetWeight)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssidd", $record_id, $_POST['product'], $_POST['color'], $_POST['mold-name'], $_POST['prodNo'], $_POST['cavity'], $_POST['grossWeight'], $_POST['netWeight']);
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
                record_id, DryingTime, DryingTemperature, 
                Material1_Type, Material1_Brand, Material1_MixturePercentage,
                Material2_Type, Material2_Brand, Material2_MixturePercentage,
                Material3_Type, Material3_Brand, Material3_MixturePercentage,
                Material4_Type, Material4_Brand, Material4_MixturePercentage
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sddssdssdssdssd",
        $record_id,
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
    $sql = "INSERT INTO colorantdetails (record_id, Colorant, Color, Dosage, Stabilizer, StabilizerDosage)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $record_id, $_POST['colorant'], $_POST['colorantColor'], $_POST['colorant-dosage'], $_POST['colorant-stabilizer'], $_POST['colorant-stabilizer-dosage']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into colorantdetails: " . $stmt->error;
    }
}

// Insert into moldoperationspecs table
if (isset($_POST['mold-code'], $_POST['clamping-force'], $_POST['operation-type'], $_POST['cooling-media'], $_POST['heating-media'])) {
    $sql = "INSERT INTO moldoperationspecs (record_id, MoldCode, ClampingForce, OperationType, CoolingMedia, HeatingMedia)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $record_id, $_POST['mold-code'], $_POST['clamping-force'], $_POST['operation-type'], $_POST['cooling-media'], $_POST['heating-media']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into moldoperationspecs: " . $stmt->error;
    }
}

// Insert into timerparameters table
if (isset($_POST['fillingTime'], $_POST['holdingTime'], $_POST['moldOpenCloseTime'], $_POST['chargingTime'], $_POST['coolingTime'], $_POST['cycleTime'])) {
    $sql = "INSERT INTO timerparameters (record_id, FillingTime, HoldingTime, MoldOpenCloseTime, ChargingTime, CoolingTime, CycleTime)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdddddd", $record_id, $_POST['fillingTime'], $_POST['holdingTime'], $_POST['moldOpenCloseTime'], $_POST['chargingTime'], $_POST['coolingTime'], $_POST['cycleTime']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into timerparameters: " . $stmt->error;
    }
}

// Insert into barrelheatertemperatures table
if (isset($_POST['barrelHeaterZone0'], $_POST['barrelHeaterZone1'], $_POST['barrelHeaterZone2'])) {
    $sql = "INSERT INTO barrelheatertemperatures (
                record_id, Zone0, Zone1, Zone2, Zone3, Zone4, Zone5, Zone6, Zone7, Zone8, Zone9,
                Zone10, Zone11, Zone12, Zone13, Zone14, Zone15, Zone16
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sddddddddddddddddd",
        $record_id,
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
                record_id, Zone0, Zone1, Zone2, Zone3, Zone4, Zone5, Zone6, Zone7, Zone8, Zone9,
                Zone10, Zone11, Zone12, Zone13, Zone14, Zone15, Zone16, MTCSetting
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sdddddddddddddddddd",
        $record_id,
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
                record_id, ScrewRPM1, ScrewRPM2, ScrewRPM3,
                ScrewSpeed1, ScrewSpeed2, ScrewSpeed3,
                PlastPressure1, PlastPressure2, PlastPressure3,
                PlastPosition1, PlastPosition2, PlastPosition3,
                BackPressure1, BackPressure2, BackPressure3,
                BackPressureStartPosition
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sdddddddddddddddd",
        $record_id,
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
                record_id, RecoveryPosition, SecondStagePosition, Cushion,
                ScrewPosition1, ScrewPosition2, ScrewPosition3,
                InjectionSpeed1, InjectionSpeed2, InjectionSpeed3,
                InjectionPressure1, InjectionPressure2, InjectionPressure3,
                SuckBackPosition, SuckBackSpeed, SuckBackPressure,
                SprueBreak, SprueBreakTime, InjectionDelay,
                HoldingPressure1, HoldingPressure2, HoldingPressure3,
                HoldingSpeed1, HoldingSpeed2, HoldingSpeed3,
                HoldingTime1, HoldingTime2, HoldingTime3
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sddddddddddddddddddddddddddd",
        $record_id,
        $_POST['RecoveryPosition'],
        $_POST['SecondStagePosition'],
        $_POST['Cushion'],
        $_POST['ScrewPosition1'],
        $_POST['ScrewPosition2'],
        $_POST['ScrewPosition3'],
        $_POST['InjectionSpeed1'],
        $_POST['InjectionSpeed2'],
        $_POST['InjectionSpeed3'],
        $_POST['InjectionPressure1'],
        $_POST['InjectionPressure2'],
        $_POST['InjectionPressure3'],
        $_POST['SuckBackPosition'],
        $_POST['SuckBackSpeed'],
        $_POST['SuckBackPressure'],
        $_POST['SprueBreak'],
        $_POST['SprueBreakTime'],
        $_POST['InjectionDelay'],
        $_POST['HoldingPressure1'],
        $_POST['HoldingPressure2'],
        $_POST['HoldingPressure3'],
        $_POST['HoldingSpeed1'],
        $_POST['HoldingSpeed2'],
        $_POST['HoldingSpeed3'],
        $_POST['HoldingTime1'],
        $_POST['HoldingTime2'],
        $_POST['HoldingTime3']
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into injectionparameters: " . $stmt->error;
    }
}



// Insert into ejectionparameters table
if (isset($_POST['AirBlowTimeA'], $_POST['EjectorForwardPosition1'], $_POST['EjectorForwardSpeed2'])) {
    $sql = "INSERT INTO ejectionparameters (
                record_id, AirBlowTimeA, AirBlowPositionA, AirBlowADelay,
                AirBlowTimeB, AirBlowPositionB, AirBlowBDelay,
                EjectorForwardPosition1, EjectorForwardPosition2, EjectorForwardSpeed1,
                EjectorRetractPosition1, EjectorRetractPosition2, EjectorRetractSpeed1,
                EjectorForwardSpeed2, EjectorForwardPressure1, EjectorRetractSpeed2,
                EjectorRetractPressure1
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sdddddddddddddddd",
        $record_id,
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

    $sqlCore = "INSERT INTO corepullsettings (record_id, Section, Sequence, Pressure, Speed, Position, Time, LimitSwitch)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtCore = $conn->prepare($sqlCore);

    foreach ($coreSections as $section => $types) {
        foreach ($types as $type => $fields) {
            $sectionName = "Core " . ucfirst($type) . " " . $section;
            $stmtCore->bind_param(
                "sssdddds",
                $record_id,
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
    $sql = "INSERT INTO additionalinformation (record_id, Info) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $record_id, $_POST['additionalInfo']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into additionalinformation: " . $stmt->error;
    }
}

// Insert into personnel table
if (isset($_POST['adjuster'], $_POST['qae'])) {
    $sql = "INSERT INTO personnel (record_id, AdjusterName, QAEName) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $record_id, $_POST['adjuster'], $_POST['qae']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into personnel: " . $stmt->error;
    }
}

// Modified File Upload Handling
function handleFileUpload($files, $uploadDir, $relativePath, $typeCategory, $allowedExtensions, $maxSize, $irn, $date, $time, $machineNumber, $runNumber)
{
    $uploadedFiles = [];
    $errors = [];

    if (!isset($files['name']) || !is_array($files['name'])) {
        return ['files' => $uploadedFiles, 'errors' => ["No {$typeCategory} files found"]];
    }

    // Ensure upload directory exists
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            return ['files' => $uploadedFiles, 'errors' => ["Failed to create upload directory: {$uploadDir}"]];
        }
    }

    if (!is_writable($uploadDir)) {
        @chmod($uploadDir, 0777); // Try to make it writable
        if (!is_writable($uploadDir)) {
            return ['files' => $uploadedFiles, 'errors' => ["Upload directory is not writable: {$uploadDir}"]];
        }
    }

    foreach ($files['name'] as $key => $name) {
        if (empty($name)) continue; // Skip empty file slots
        
        if ($files['error'][$key] !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
                UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form",
                UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded",
                UPLOAD_ERR_NO_FILE => "No file was uploaded",
                UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
                UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
                UPLOAD_ERR_EXTENSION => "File upload stopped by extension"
            ];
            
            $errorMessage = isset($errorMessages[$files['error'][$key]]) 
                ? $errorMessages[$files['error'][$key]] 
                : "Unknown upload error";
                
            $errors[] = "Error uploading {$name}: {$errorMessage}";
            continue;
        }

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            $errors[] = "Invalid file extension for {$name}. Allowed: " . implode(', ', $allowedExtensions);
            continue;
        }

        if ($files['size'][$key] > $maxSize) {
            $errors[] = "File {$name} exceeds size limit of " . ($maxSize / 1000000) . "MB";
            continue;
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
        $tmp = $files['tmp_name'][$key];
        $mimeType = mime_content_type($tmp);         // e.g. "video/mp4"
        if (move_uploaded_file($tmp, $targetPath)) {
            $uploadedFiles[] = [
                'name' => $name, // Store original filename for display
                'saveName' => $safeName,
                'path' => $relativePath . $safeName,
                'type' => $mimeType,
            ];
        } else {
            $errors[] = "Failed to move uploaded file {$name} to {$targetPath}. PHP error: " . error_get_last()['message'] ?? 'Unknown error';
        }
    }
    
    return ['files' => $uploadedFiles, 'errors' => $errors];
}

// Handle file uploads with proper validation
// Determine the correct path for uploads - try both server-style and local paths
$serverPath = '/var/www/html/parameters/uploads/'; // Cloud server path
$localPath = $_SERVER['DOCUMENT_ROOT'] . '/Sentinel/parameters/uploads/'; // Local XAMPP path
$alternativePath = dirname(__FILE__) . '/uploads/'; // Relative to current script

// Test which path is valid and writable
if (is_dir($serverPath) && is_writable($serverPath)) {
    $uploadDir = $serverPath;
} elseif (is_dir($localPath) && is_writable($localPath)) {
    $uploadDir = $localPath;
} else {
    // Create uploads directory if it doesn't exist
    if (!is_dir($alternativePath)) {
        mkdir($alternativePath, 0777, true);
    }
    $uploadDir = $alternativePath;
}

// Make absolutely sure the directory exists and is writable
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
@chmod($uploadDir, 0777);

// Log the path being used for debugging
error_log("Using upload directory: " . $uploadDir);
$relativePath = 'parameters/uploads/';
$uploadedImages = [];
$uploadedVideos = [];
$fileUploadErrors = [];

// Sanitize parameters for filename
$irn = preg_replace('/[^A-Za-z0-9]/', '', $_POST['IRN']);
$dateFormatted = preg_replace('/[^0-9]/', '', $_POST['Date']); // YYYYMMDD
$timeFormatted = preg_replace('/[^0-9]/', '', $_POST['Time']); // HHMMSS
$machineNumber = preg_replace('/[^A-Za-z0-9]/', '', $_POST['MachineName']);
$runNumber = preg_replace('/[^A-Za-z0-9]/', '', $_POST['RunNumber']);

// Process file uploads
if (isset($_FILES['uploadImages']) && !empty($_FILES['uploadImages']['name'][0])) {
    $imageUploadResult = handleFileUpload(
        $_FILES['uploadImages'],
        $uploadDir,
        $relativePath,
        'image',
        ['jpg', 'jpeg', 'png', 'gif'],
        5000000, // 5MB
        $irn,
        $dateFormatted,
        $timeFormatted,
        $machineNumber,
        $runNumber
    );
    
    $uploadedImages = $imageUploadResult['files'];
    $fileUploadErrors = array_merge($fileUploadErrors, $imageUploadResult['errors']);
}

if (isset($_FILES['uploadVideos']) && !empty($_FILES['uploadVideos']['name'][0])) {
    $videoUploadResult = handleFileUpload(
        $_FILES['uploadVideos'],
        $uploadDir,
        $relativePath,
        'video',
        ['mp4', 'avi', 'mov', 'mkv'],
        50000000, // 50MB
        $irn,
        $dateFormatted,
        $timeFormatted,
        $machineNumber,
        $runNumber
    );
    
    $uploadedVideos = $videoUploadResult['files'];
    $fileUploadErrors = array_merge($fileUploadErrors, $videoUploadResult['errors']);
}

// Add file upload errors to the main errors array
if (!empty($fileUploadErrors)) {
    $errors = array_merge($errors ?? [], $fileUploadErrors);
}

// Insert attachments into database
if (!empty($uploadedImages) || !empty($uploadedVideos)) {
    $sql = "INSERT INTO attachments (record_id, FileName, FilePath, FileType) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    foreach (array_merge($uploadedImages, $uploadedVideos) as $file) {
        $stmt->bind_param(
            "ssss",
            $record_id,
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
    $_SESSION['success_message'] = "Data submitted successfully with Record ID: " . $record_id;
    if (!empty($uploadedImages) || !empty($uploadedVideos)) {
        $fileCount = count($uploadedImages) + count($uploadedVideos);
        $_SESSION['success_message'] .= " with $fileCount attachment(s)";
    }
    header("Location: index.php?record_id=" . $record_id);
    exit();
} else {
    $conn->rollback();
    $_SESSION['error_message'] = "Errors occurred: " . implode("<br>", $errors);
    header("Location: index.php");
    exit();
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
