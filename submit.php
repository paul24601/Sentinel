<?php
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

// Function to convert time to Philippine timezone
function formatPhilippineTime($timeValue) {
    if (empty($timeValue)) {
        return null;
    }
    
    // Handle placeholder values - if it's 00:00, treat it as empty/unset
    if ($timeValue === '00:00' || $timeValue === '00:00:00') {
        return null;
    }
    
    // If it's already in H:i:s format, use it directly
    if (preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $timeValue)) {
        return $timeValue;
    }
    
    // If it's in H:i format, add seconds
    if (preg_match('/^\d{1,2}:\d{2}$/', $timeValue)) {
        return $timeValue . ':00';
    }
    
    // Fallback to current Philippine time
    return date('H:i:s');
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session first and check authentication
session_start();

// Include user activity logger
require_once 'user_activity_logger.php';

// Check if the user is logged in
if (!isset($_SESSION['full_name']) || empty($_SESSION['full_name'])) {
    // If not logged in, redirect to the login page
    header("Location: ../login.html?error=" . urlencode("Session expired. Please log in again."));
    exit();
}

// Check session validity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Session has expired
    session_unset();
    session_destroy();
    header("Location: ../login.html?error=" . urlencode("Session expired. Please log in again."));
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Additional session validation for long submissions
if (!isset($_SESSION['full_name']) || empty(trim($_SESSION['full_name']))) {
    error_log("Empty full_name in session during submission: " . print_r($_SESSION, true));
    header("Location: ../login.html?error=" . urlencode("Invalid user session detected."));
    exit();
}

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

// Set MySQL timezone to Philippine Time (UTC+8)
$conn->query("SET time_zone = '+08:00'");

$conn->begin_transaction();
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate a unique record_id
$record_id = 'PARAM_' . date('Ymd') . '_' . substr(uniqid(), -5);

// Insert into parameter_records table
$submitted_by = $_SESSION['full_name']; // No need for fallback since we've verified the session

// Double-check session validity and provide fallback
if (empty($submitted_by) || !isset($_SESSION['id_number'])) {
    // Log this incident and redirect to login
    error_log("Invalid session detected during submission: " . json_encode($_SESSION));
    $_SESSION['error_message'] = "Your session has expired. Please log in again to submit data.";
    header("Location: ../login.html?error=" . urlencode("Session expired during submission."));
    exit();
}

$title = isset($_POST['MachineName']) ? $_POST['MachineName'] . ' - ' . $_POST['product'] : 'Unnamed Record';
$description = isset($_POST['additionalInfo']) ? $_POST['additionalInfo'] : '';

// Handle start and end time with proper Philippine time formatting
error_log("=== TIMING DEBUG START ===");
error_log("POST startTime: " . ($_POST['startTime'] ?? 'NOT SET'));
error_log("POST endTime: " . ($_POST['endTime'] ?? 'NOT SET'));

$startTime = !empty($_POST['startTime']) ? formatPhilippineTime($_POST['startTime']) : null;
$endTime = !empty($_POST['endTime']) ? formatPhilippineTime($_POST['endTime']) : null;

error_log("After formatPhilippineTime - startTime: " . ($startTime ?? 'NULL'));
error_log("After formatPhilippineTime - endTime: " . ($endTime ?? 'NULL'));

// SIMPLE SERVER-SIDE SOLUTION: Always set current time as end time
$currentTime = date('H:i:s');
$endTime = $currentTime;
error_log("FORCED endTime to current time: " . $endTime);

// If no start time provided, set it to 5 minutes ago
if (empty($startTime)) {
    $startTime = date('H:i:s', strtotime('-5 minutes'));
    error_log("Set startTime to 5 minutes ago: " . $startTime);
}

// Add a small delay to ensure times are different
sleep(1);
$endTime = date('H:i:s');
error_log("Final endTime after delay: " . $endTime);

// Debug logging for time values (keep for troubleshooting)
error_log("FINAL VALUES - startTime: " . ($startTime ?? 'NULL'));
error_log("FINAL VALUES - endTime: " . ($endTime ?? 'NULL'));
error_log("Times are different: " . ($startTime !== $endTime ? 'YES' : 'NO'));
error_log("=== TIMING DEBUG END ===");

// Use end time for submission_date if available, otherwise use current time
$submissionDateTime = null;
if (!empty($endTime) && !empty($_POST['Date'])) {
    // Combine the date and end time for the submission timestamp
    $submissionDateTime = $_POST['Date'] . ' ' . $endTime;
} else {
    // Fallback to current timestamp
    $submissionDateTime = date('Y-m-d H:i:s');
}

$sql = "INSERT INTO parameter_records (record_id, submission_date, submitted_by, title, description) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $record_id, $submissionDateTime, $submitted_by, $title, $description);
if (!$stmt->execute()) {
    $errors[] = "Error creating record: " . $stmt->error;
} else {
    // Log successful submission
    logUserActivity($conn, 'PARAMETER_SUBMISSION', $record_id, "Title: $title");
}

// Initialize variables to track errors
$errors = [];

// Use start time as the main time field (for compatibility), otherwise use current Philippine time
$mainTime = !empty($startTime) ? $startTime : (!empty($_POST['Time']) ? formatPhilippineTime($_POST['Time']) : date('H:i:s'));
error_log("mainTime calculated as: " . $mainTime);

// Insert into productmachineinfo table
// Insert into productmachineinfo table (now including startTime & endTime)
if (isset($_POST['Date'], $_POST['Time'], $_POST['MachineName'], $_POST['RunNumber'], $_POST['Category'], $_POST['IRN'])) {
    
    // Debug logging for database insert
    error_log("=== DATABASE INSERT DEBUG ===");
    error_log("About to insert - startTime=" . ($startTime ?? 'NULL') . ", endTime=" . ($endTime ?? 'NULL') . ", mainTime=" . $mainTime);
    error_log("Date: " . $_POST['Date']);
    error_log("Time: " . $_POST['Time']);
    error_log("Machine: " . $_POST['MachineName']);
    
    $sql = "
      INSERT INTO productmachineinfo
        ( record_id
        , Date
        , Time
        , startTime
        , endTime
        , MachineName
        , RunNumber
        , Category
        , IRN
        )
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssss",
        $record_id,
        $_POST['Date'],
        $mainTime,
        $startTime,
        $endTime,
        $_POST['MachineName'],
        $_POST['RunNumber'],
        $_POST['Category'],
        $_POST['IRN']
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into productmachineinfo: " . $stmt->error;
        error_log("ERROR - productmachineinfo insert failed: " . $stmt->error);
    } else {
        error_log("SUCCESS - Database insert completed");
        error_log("SUCCESS - Values inserted: startTime=" . ($startTime ?? 'NULL') . ", endTime=" . ($endTime ?? 'NULL') . ", mainTime=" . $mainTime);
        
        // Let's verify what was actually inserted by querying back
        $verifyQuery = "SELECT startTime, endTime, Time FROM productmachineinfo WHERE record_id = ? ORDER BY id DESC LIMIT 1";
        $verifyStmt = $conn->prepare($verifyQuery);
        $verifyStmt->bind_param("s", $record_id);
        $verifyStmt->execute();
        $result = $verifyStmt->get_result();
        if ($row = $result->fetch_assoc()) {
            error_log("VERIFICATION - Database contains: startTime=" . $row['startTime'] . ", endTime=" . $row['endTime'] . ", Time=" . $row['Time']);
        }
        error_log("=== DATABASE INSERT DEBUG END ===");
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
if (isset($_POST['colorant'], $_POST['colorantColor'], $_POST['colorant-dosage'], $_POST['colorant-stabilizer'], $_POST['colorant-stabilizer-dosage'])) {
    $sql = "INSERT INTO colorantdetails (record_id, Colorant, Color, Dosage, Stabilizer, StabilizerDosage)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $record_id, $_POST['colorant'], $_POST['colorantColor'], $_POST['colorant-dosage'], $_POST['colorant-stabilizer'], $_POST['colorant-stabilizer-dosage']);
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into colorantdetails: " . $stmt->error;
    }
}

// Insert into moldoperationspecs table
if (isset($_POST['mold-code'], $_POST['clamping-force'], $_POST['operation-type'], $_POST['stationary-cooling-media'], $_POST['movable-cooling-media'], $_POST['heating-media'])) {
    $sql = "INSERT INTO moldoperationspecs (record_id, MoldCode, ClampingForce, OperationType, StationaryCoolingMedia, MovableCoolingMedia, HeatingMedia, StationaryCoolingMediaRemarks, MovableCoolingMediaRemarks)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stationaryRemarks = isset($_POST['stationary-cooling-media-remarks']) ? $_POST['stationary-cooling-media-remarks'] : '';
    $movableRemarks = isset($_POST['movable-cooling-media-remarks']) ? $_POST['movable-cooling-media-remarks'] : '';
    // Note: The form currently sends 'cooling-media-remarks' so we'll use that for both until form is updated
    $coolingRemarks = isset($_POST['cooling-media-remarks']) ? $_POST['cooling-media-remarks'] : '';
    $stmt->bind_param("sssssssss", $record_id, $_POST['mold-code'], $_POST['clamping-force'], $_POST['operation-type'], $_POST['stationary-cooling-media'], $_POST['movable-cooling-media'], $_POST['heating-media'], $coolingRemarks, $coolingRemarks);
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
if (isset($_POST['Zone1'], $_POST['Zone2'], $_POST['MTCSetting'])) {
    $sql = "INSERT INTO moldheatertemperatures (
                record_id, Zone1, Zone2, Zone3, Zone4, Zone5, Zone6, Zone7, Zone8, Zone9,
                Zone10, Zone11, Zone12, Zone13, Zone14, Zone15, Zone16, MTCSetting
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sddddddddddddddddd",
        $record_id,
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

// Insert into moldopenparameters table
if (isset($_POST['moldOpenPos1'])) {
    $sql = "INSERT INTO moldopenparameters (
                record_id, 
                MoldOpenPos1, MoldOpenPos2, MoldOpenPos3, MoldOpenPos4, MoldOpenPos5, MoldOpenPos6,
                MoldOpenSpd1, MoldOpenSpd2, MoldOpenSpd3, MoldOpenSpd4, MoldOpenSpd5, MoldOpenSpd6,
                MoldOpenPressure1, MoldOpenPressure2, MoldOpenPressure3, MoldOpenPressure4, MoldOpenPressure5, MoldOpenPressure6
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Assign variables to avoid PHP reference errors
    $moldOpenPos1 = isset($_POST['moldOpenPos1']) ? $_POST['moldOpenPos1'] : null;
    $moldOpenPos2 = isset($_POST['moldOpenPos2']) ? $_POST['moldOpenPos2'] : null;
    $moldOpenPos3 = isset($_POST['moldOpenPos3']) ? $_POST['moldOpenPos3'] : null;
    $moldOpenPos4 = isset($_POST['moldOpenPos4']) ? $_POST['moldOpenPos4'] : null;
    $moldOpenPos5 = isset($_POST['moldOpenPos5']) ? $_POST['moldOpenPos5'] : null;
    $moldOpenPos6 = isset($_POST['moldOpenPos6']) ? $_POST['moldOpenPos6'] : null;
    
    $moldOpenSpd1 = isset($_POST['moldOpenSpd1']) ? $_POST['moldOpenSpd1'] : null;
    $moldOpenSpd2 = isset($_POST['moldOpenSpd2']) ? $_POST['moldOpenSpd2'] : null;
    $moldOpenSpd3 = isset($_POST['moldOpenSpd3']) ? $_POST['moldOpenSpd3'] : null;
    $moldOpenSpd4 = isset($_POST['moldOpenSpd4']) ? $_POST['moldOpenSpd4'] : null;
    $moldOpenSpd5 = isset($_POST['moldOpenSpd5']) ? $_POST['moldOpenSpd5'] : null;
    $moldOpenSpd6 = isset($_POST['moldOpenSpd6']) ? $_POST['moldOpenSpd6'] : null;
    
    $moldOpenPressure1 = isset($_POST['moldOpenPressure1']) ? $_POST['moldOpenPressure1'] : null;
    $moldOpenPressure2 = isset($_POST['moldOpenPressure2']) ? $_POST['moldOpenPressure2'] : null;
    $moldOpenPressure3 = isset($_POST['moldOpenPressure3']) ? $_POST['moldOpenPressure3'] : null;
    $moldOpenPressure4 = isset($_POST['moldOpenPressure4']) ? $_POST['moldOpenPressure4'] : null;
    $moldOpenPressure5 = isset($_POST['moldOpenPressure5']) ? $_POST['moldOpenPressure5'] : null;
    $moldOpenPressure6 = isset($_POST['moldOpenPressure6']) ? $_POST['moldOpenPressure6'] : null;
    
    $stmt->bind_param("sdddddddddddddddddd", 
        $record_id,
        $moldOpenPos1, $moldOpenPos2, $moldOpenPos3, $moldOpenPos4, $moldOpenPos5, $moldOpenPos6,
        $moldOpenSpd1, $moldOpenSpd2, $moldOpenSpd3, $moldOpenSpd4, $moldOpenSpd5, $moldOpenSpd6,
        $moldOpenPressure1, $moldOpenPressure2, $moldOpenPressure3, $moldOpenPressure4, $moldOpenPressure5, $moldOpenPressure6
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into moldopenparameters: " . $stmt->error;
    }
}

// Insert into moldcloseparameters table
if (isset($_POST['moldClosePos1'])) {
    $sql = "INSERT INTO moldcloseparameters (
                record_id,
                MoldClosePos1, MoldClosePos2, MoldClosePos3, MoldClosePos4, MoldClosePos5, MoldClosePos6,
                MoldCloseSpd1, MoldCloseSpd2, MoldCloseSpd3, MoldCloseSpd4, MoldCloseSpd5, MoldCloseSpd6,
                MoldClosePressure1, MoldClosePressure2, MoldClosePressure3, MoldClosePressure4,
                PCLORLP, PCHORHP, LowPresTimeLimit
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Assign variables to avoid PHP reference errors
    $moldClosePos1 = isset($_POST['moldClosePos1']) ? $_POST['moldClosePos1'] : null;
    $moldClosePos2 = isset($_POST['moldClosePos2']) ? $_POST['moldClosePos2'] : null;
    $moldClosePos3 = isset($_POST['moldClosePos3']) ? $_POST['moldClosePos3'] : null;
    $moldClosePos4 = isset($_POST['moldClosePos4']) ? $_POST['moldClosePos4'] : null;
    $moldClosePos5 = isset($_POST['moldClosePos5']) ? $_POST['moldClosePos5'] : null;
    $moldClosePos6 = isset($_POST['moldClosePos6']) ? $_POST['moldClosePos6'] : null;
    
    $moldCloseSpd1 = isset($_POST['moldCloseSpd1']) ? $_POST['moldCloseSpd1'] : null;
    $moldCloseSpd2 = isset($_POST['moldCloseSpd2']) ? $_POST['moldCloseSpd2'] : null;
    $moldCloseSpd3 = isset($_POST['moldCloseSpd3']) ? $_POST['moldCloseSpd3'] : null;
    $moldCloseSpd4 = isset($_POST['moldCloseSpd4']) ? $_POST['moldCloseSpd4'] : null;
    $moldCloseSpd5 = isset($_POST['moldCloseSpd5']) ? $_POST['moldCloseSpd5'] : null;
    $moldCloseSpd6 = isset($_POST['moldCloseSpd6']) ? $_POST['moldCloseSpd6'] : null;
    
    $moldClosePressure1 = isset($_POST['moldClosePressure1']) ? $_POST['moldClosePressure1'] : null;
    $moldClosePressure2 = isset($_POST['moldClosePressure2']) ? $_POST['moldClosePressure2'] : null;
    $moldClosePressure3 = isset($_POST['moldClosePressure3']) ? $_POST['moldClosePressure3'] : null;
    $moldClosePressure4 = isset($_POST['moldClosePressure4']) ? $_POST['moldClosePressure4'] : null;
    
    $pclorlp = isset($_POST['pclorlp']) ? $_POST['pclorlp'] : null;
    $pchorhp = isset($_POST['pchorhp']) ? $_POST['pchorhp'] : null;
    $lowPresTimeLimit = isset($_POST['lowPresTimeLimit']) ? $_POST['lowPresTimeLimit'] : null;
    
    $stmt->bind_param("sdddddddddddddddsssd", 
        $record_id,
        $moldClosePos1, $moldClosePos2, $moldClosePos3, $moldClosePos4, $moldClosePos5, $moldClosePos6,
        $moldCloseSpd1, $moldCloseSpd2, $moldCloseSpd3, $moldCloseSpd4, $moldCloseSpd5, $moldCloseSpd6,
        $moldClosePressure1, $moldClosePressure2, $moldClosePressure3, $moldClosePressure4,
        $pclorlp, $pchorhp, $lowPresTimeLimit
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into moldcloseparameters: " . $stmt->error;
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
    // Prepare optional fields with null fallback
    $screwPosition4 = $_POST['ScrewPosition4'] ?? null;
    $screwPosition5 = $_POST['ScrewPosition5'] ?? null;
    $screwPosition6 = $_POST['ScrewPosition6'] ?? null;
    $injectionSpeed4 = $_POST['InjectionSpeed4'] ?? null;
    $injectionSpeed5 = $_POST['InjectionSpeed5'] ?? null;
    $injectionSpeed6 = $_POST['InjectionSpeed6'] ?? null;
    $injectionPressure4 = $_POST['InjectionPressure4'] ?? null;
    $injectionPressure5 = $_POST['InjectionPressure5'] ?? null;
    $injectionPressure6 = $_POST['InjectionPressure6'] ?? null;

    $sql = "INSERT INTO injectionparameters (
                record_id, RecoveryPosition, SecondStagePosition, Cushion,
                ScrewPosition1, ScrewPosition2, ScrewPosition3,
                InjectionSpeed1, InjectionSpeed2, InjectionSpeed3,
                InjectionPressure1, InjectionPressure2, InjectionPressure3,
                SuckBackPosition, SuckBackSpeed, SuckBackPressure,
                SprueBreak, SprueBreakTime, InjectionDelay,
                HoldingPressure1, HoldingPressure2, HoldingPressure3,
                HoldingSpeed1, HoldingSpeed2, HoldingSpeed3,
                HoldingTime1, HoldingTime2, HoldingTime3,
                ScrewPosition4, ScrewPosition5, ScrewPosition6,
                InjectionSpeed4, InjectionSpeed5, InjectionSpeed6,
                InjectionPressure4, InjectionPressure5, InjectionPressure6
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sdddddddddddddddddddddddddddddddddddd",
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
        $_POST['HoldingTime3'],
        $screwPosition4,
        $screwPosition5,
        $screwPosition6,
        $injectionSpeed4,
        $injectionSpeed5,
        $injectionSpeed6,
        $injectionPressure4,
        $injectionPressure5,
        $injectionPressure6
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into injectionparameters: " . $stmt->error;
    }
}



// Insert into ejectionparameters table
if (isset($_POST['AirBlowTimeA'], $_POST['EjectorForwardPosition1'], $_POST['EjectorForwardSpeed2'])) {
    // Prepare optional fields with null fallback
    $ejectorForwardTime = $_POST['EjectorForwardTime'] ?? null;
    $ejectorRetractTime = $_POST['EjectorRetractTime'] ?? null;
    $ejectorForwardPressure2 = $_POST['EjectorForwardPressure2'] ?? null;
    $ejectorRetractPressure2 = $_POST['EjectorRetractPressure2'] ?? null;

    $sql = "INSERT INTO ejectionparameters (
                record_id, AirBlowTimeA, AirBlowPositionA, AirBlowADelay,
                AirBlowTimeB, AirBlowPositionB, AirBlowBDelay,
                EjectorForwardPosition1, EjectorForwardPosition2, EjectorForwardSpeed1,
                EjectorRetractPosition1, EjectorRetractPosition2, EjectorRetractSpeed1,
                EjectorForwardSpeed2, EjectorForwardPressure1, EjectorRetractSpeed2,
                EjectorRetractPressure1, EjectorForwardTime, EjectorRetractTime,
                EjectorForwardPressure2, EjectorRetractPressure2
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sdddddddddddddddddddd",
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
        $_POST['EjectorRetractPressure1'],
        $ejectorForwardTime,
        $ejectorRetractTime,
        $ejectorForwardPressure2,
        $ejectorRetractPressure2
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

    // Insert Core Pull Settings for all sections
    $sqlCore = "INSERT INTO corepullsettings
  (record_id, Section, Sequence, Pressure, Speed, Position, Time, LimitSwitch)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtCore = $conn->prepare($sqlCore);

    foreach ($coreSections as $section => $types) {
        foreach ($types as $type => $fields) {
            // if there's no sequence at all, skip this row
            if (!isset($fields['sequence']) || $fields['sequence'] === '') {
                continue;
            }

            // build your typed variables
            $sectionName = "Core " . ucfirst($type) . " " . $section;
            $sequence = (int) $fields['sequence'];
            $pressure = isset($fields['pressure']) ? (float) $fields['pressure'] : 0.0;
            $speed = isset($fields['speed']) ? (float) $fields['speed'] : 0.0;
            $position = isset($fields['position']) ? (float) $fields['position'] : 0.0;
            $time = isset($fields['time']) ? (float) $fields['time'] : 0.0;
            $limitSwitch = $fields['limit'] ?? null;

            // note the type string:  ss i d d d d s
            $stmtCore->bind_param(
                "ssidddds",
                $record_id,    // s
                $sectionName,  // s
                $sequence,     // i
                $pressure,     // d
                $speed,        // d
                $position,     // d
                $time,         // d
                $limitSwitch   // s
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
    // Ensure adjuster name is properly set
    $adjuster_name = !empty(trim($_POST['adjuster'])) ? trim($_POST['adjuster']) : $_SESSION['full_name'];
    $qae_name = !empty(trim($_POST['qae'])) ? trim($_POST['qae']) : 'Not Specified';
    
    $sql = "INSERT INTO personnel (record_id, AdjusterName, QAEName) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $record_id, $adjuster_name, $qae_name);
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
        if (empty($name))
            continue; // Skip empty file slots

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