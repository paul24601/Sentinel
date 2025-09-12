<?php
// Set timezone to Philippine Time (UTC+8)
date_default_timezone_set('Asia/Manila');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session and check authentication
session_start();

// Check if the user is logged in
if (!isset($_SESSION['full_name']) || empty($_SESSION['full_name'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Session expired. Please log in again.']);
    exit();
}

// Check session validity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    http_response_code(401);
    echo json_encode(['error' => 'Session expired. Please log in again.']);
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Load centralized database configuration
require_once __DIR__ . '/../includes/database.php';
require_once 'user_activity_logger.php';

try {
    $conn = DatabaseManager::getConnection('sentinel_main');
    $conn->query("SET time_zone = '+08:00'");
    $conn->begin_transaction();

    // Generate a unique record_id
    $record_id = 'PARAM_' . date('Ymd') . '_' . substr(uniqid(), -5);
    $submitted_by = $_SESSION['full_name'];

    // Create title and description
    $title = isset($_POST['MachineName']) ? $_POST['MachineName'] . ' - ' . (isset($_POST['product']) ? $_POST['product'] : 'Unknown Product') : 'Unknown Machine';
    $description = isset($_POST['additionalInfo']) ? $_POST['additionalInfo'] : '';

    // Handle timing
    function formatPhilippineTime($timeValue) {
        if (empty($timeValue) || $timeValue === '00:00' || $timeValue === '00:00:00') {
            return null;
        }
        if (preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $timeValue)) {
            return $timeValue;
        }
        if (preg_match('/^\d{1,2}:\d{2}$/', $timeValue)) {
            return $timeValue . ':00';
        }
        return date('H:i:s');
    }
    
    $startTime = !empty($_POST['startTime']) ? formatPhilippineTime($_POST['startTime']) : date('H:i:s', strtotime('-5 minutes'));
    $endTime = date('H:i:s');
    $mainTime = !empty($startTime) ? $startTime : date('H:i:s');
    $submissionDateTime = (isset($_POST['Date']) ? $_POST['Date'] : date('Y-m-d')) . ' ' . $endTime;

    // Insert into parameter_records table
    $sql = "INSERT INTO parameter_records (record_id, submission_date, submitted_by, title, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $record_id, $submissionDateTime, $submitted_by, $title, $description);
    $stmt->execute();

    // 1. Product and Machine Information
    $sql = "INSERT INTO productmachineinfo (record_id, Date, Time, startTime, endTime, MachineName, RunNumber, Category, IRN) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", 
        $record_id,
        isset($_POST['Date']) ? $_POST['Date'] : date('Y-m-d'),
        $mainTime,
        $startTime,
        $endTime,
        isset($_POST['MachineName']) ? $_POST['MachineName'] : null,
        isset($_POST['RunNumber']) ? $_POST['RunNumber'] : null,
        isset($_POST['Category']) ? $_POST['Category'] : null,
        isset($_POST['IRN']) ? $_POST['IRN'] : null
    );
    $stmt->execute();

    // 2. Product Details
    $sql = "INSERT INTO productdetails (record_id, ProductName, Color, MoldName, ProductNumber, CavityActive, GrossWeight, NetWeight) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss",
        $record_id,
        isset($_POST['product']) ? $_POST['product'] : null,
        isset($_POST['color']) ? $_POST['color'] : null,
        isset($_POST['mold-name']) ? $_POST['mold-name'] : null,
        isset($_POST['prodNo']) ? $_POST['prodNo'] : null,
        isset($_POST['cavity']) ? $_POST['cavity'] : null,
        isset($_POST['grossWeight']) ? $_POST['grossWeight'] : null,
        isset($_POST['netWeight']) ? $_POST['netWeight'] : null
    );
    $stmt->execute();

    // 3. Material Composition
    $sql = "INSERT INTO materialcomposition (record_id, DryingTime, DryingTemperature, Type1, Brand1, Mix1, Type2, Brand2, Mix2, Type3, Brand3, Mix3, Type4, Brand4, Mix4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssss",
        $record_id,
        isset($_POST['dryingtime']) ? $_POST['dryingtime'] : null,
        isset($_POST['dryingtemp']) ? $_POST['dryingtemp'] : null,
        isset($_POST['type1']) ? $_POST['type1'] : null,
        isset($_POST['brand1']) ? $_POST['brand1'] : null,
        isset($_POST['mix1']) ? $_POST['mix1'] : null,
        isset($_POST['type2']) ? $_POST['type2'] : null,
        isset($_POST['brand2']) ? $_POST['brand2'] : null,
        isset($_POST['mix2']) ? $_POST['mix2'] : null,
        isset($_POST['type3']) ? $_POST['type3'] : null,
        isset($_POST['brand3']) ? $_POST['brand3'] : null,
        isset($_POST['mix3']) ? $_POST['mix3'] : null,
        isset($_POST['type4']) ? $_POST['type4'] : null,
        isset($_POST['brand4']) ? $_POST['brand4'] : null,
        isset($_POST['mix4']) ? $_POST['mix4'] : null
    );
    $stmt->execute();

    // 4. Colorant Details
    $sql = "INSERT INTO colorantdetails (record_id, Colorant, Color, Dosage, Stabilizer, StabilizerDosage) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss",
        $record_id,
        isset($_POST['colorant']) ? $_POST['colorant'] : null,
        isset($_POST['colorantColor']) ? $_POST['colorantColor'] : null,
        isset($_POST['colorant-dosage']) ? $_POST['colorant-dosage'] : null,
        isset($_POST['colorant-stabilizer']) ? $_POST['colorant-stabilizer'] : null,
        isset($_POST['colorant-stabilizer-dosage']) ? $_POST['colorant-stabilizer-dosage'] : null
    );
    $stmt->execute();

    // 5. Mold and Operation Specifications
    $sql = "INSERT INTO moldoperationspecs (record_id, MoldCode, ClampingForce, OperationType, StationaryCoolingMedia, MovableCoolingMedia, HeatingMedia, StationaryCoolingMediaRemarks, MovableCoolingMediaRemarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss",
        $record_id,
        isset($_POST['mold-code']) ? $_POST['mold-code'] : null,
        isset($_POST['clamping-force']) ? $_POST['clamping-force'] : null,
        isset($_POST['operation-type']) ? $_POST['operation-type'] : null,
        isset($_POST['stationary-cooling-media']) ? $_POST['stationary-cooling-media'] : null,
        isset($_POST['movable-cooling-media']) ? $_POST['movable-cooling-media'] : null,
        isset($_POST['heating-media-type']) ? $_POST['heating-media-type'] : null,
        isset($_POST['cooling-media-remarks']) ? $_POST['cooling-media-remarks'] : null,
        isset($_POST['cooling-media-remarks']) ? $_POST['cooling-media-remarks'] : null
    );
    $stmt->execute();

    // 6. Timer Parameters
    $sql = "INSERT INTO timerparameters (record_id, FillingTime, HoldingTime, MoldOpenCloseTime, ChargingTime, CoolingTime, CycleTime) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss",
        $record_id,
        isset($_POST['fillingTime']) ? $_POST['fillingTime'] : null,
        isset($_POST['holdingTime']) ? $_POST['holdingTime'] : null,
        isset($_POST['moldOpenCloseTime']) ? $_POST['moldOpenCloseTime'] : null,
        isset($_POST['chargingTime']) ? $_POST['chargingTime'] : null,
        isset($_POST['coolingTime']) ? $_POST['coolingTime'] : null,
        isset($_POST['cycleTime']) ? $_POST['cycleTime'] : null
    );
    $stmt->execute();

    // 7. Barrel Heater Temperatures
    $sql = "INSERT INTO barrelheatertemperatures (
        record_id, BarrelHeaterZone0, BarrelHeaterZone1, BarrelHeaterZone2, BarrelHeaterZone3, 
        BarrelHeaterZone4, BarrelHeaterZone5, BarrelHeaterZone6, BarrelHeaterZone7, 
        BarrelHeaterZone8, BarrelHeaterZone9, BarrelHeaterZone10, BarrelHeaterZone11, 
        BarrelHeaterZone12, BarrelHeaterZone13, BarrelHeaterZone14, BarrelHeaterZone15, BarrelHeaterZone16
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssss",
        $record_id,
        isset($_POST['barrelHeaterZone0']) ? $_POST['barrelHeaterZone0'] : null,
        isset($_POST['barrelHeaterZone1']) ? $_POST['barrelHeaterZone1'] : null,
        isset($_POST['barrelHeaterZone2']) ? $_POST['barrelHeaterZone2'] : null,
        isset($_POST['barrelHeaterZone3']) ? $_POST['barrelHeaterZone3'] : null,
        isset($_POST['barrelHeaterZone4']) ? $_POST['barrelHeaterZone4'] : null,
        isset($_POST['barrelHeaterZone5']) ? $_POST['barrelHeaterZone5'] : null,
        isset($_POST['barrelHeaterZone6']) ? $_POST['barrelHeaterZone6'] : null,
        isset($_POST['barrelHeaterZone7']) ? $_POST['barrelHeaterZone7'] : null,
        isset($_POST['barrelHeaterZone8']) ? $_POST['barrelHeaterZone8'] : null,
        isset($_POST['barrelHeaterZone9']) ? $_POST['barrelHeaterZone9'] : null,
        isset($_POST['barrelHeaterZone10']) ? $_POST['barrelHeaterZone10'] : null,
        isset($_POST['barrelHeaterZone11']) ? $_POST['barrelHeaterZone11'] : null,
        isset($_POST['barrelHeaterZone12']) ? $_POST['barrelHeaterZone12'] : null,
        isset($_POST['barrelHeaterZone13']) ? $_POST['barrelHeaterZone13'] : null,
        isset($_POST['barrelHeaterZone14']) ? $_POST['barrelHeaterZone14'] : null,
        isset($_POST['barrelHeaterZone15']) ? $_POST['barrelHeaterZone15'] : null,
        isset($_POST['barrelHeaterZone16']) ? $_POST['barrelHeaterZone16'] : null
    );
    $stmt->execute();

    // 8. Mold Heater Temperatures
    $sql = "INSERT INTO moldheatertemperatures (
        record_id, Zone1, Zone2, Zone3, Zone4, Zone5, Zone6, Zone7, Zone8, 
        Zone9, Zone10, Zone11, Zone12, Zone13, Zone14, Zone15, Zone16, MTCSetting
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssss",
        $record_id,
        isset($_POST['Zone1']) ? $_POST['Zone1'] : null,
        isset($_POST['Zone2']) ? $_POST['Zone2'] : null,
        isset($_POST['Zone3']) ? $_POST['Zone3'] : null,
        isset($_POST['Zone4']) ? $_POST['Zone4'] : null,
        isset($_POST['Zone5']) ? $_POST['Zone5'] : null,
        isset($_POST['Zone6']) ? $_POST['Zone6'] : null,
        isset($_POST['Zone7']) ? $_POST['Zone7'] : null,
        isset($_POST['Zone8']) ? $_POST['Zone8'] : null,
        isset($_POST['Zone9']) ? $_POST['Zone9'] : null,
        isset($_POST['Zone10']) ? $_POST['Zone10'] : null,
        isset($_POST['Zone11']) ? $_POST['Zone11'] : null,
        isset($_POST['Zone12']) ? $_POST['Zone12'] : null,
        isset($_POST['Zone13']) ? $_POST['Zone13'] : null,
        isset($_POST['Zone14']) ? $_POST['Zone14'] : null,
        isset($_POST['Zone15']) ? $_POST['Zone15'] : null,
        isset($_POST['Zone16']) ? $_POST['Zone16'] : null,
        isset($_POST['MTCSetting']) ? $_POST['MTCSetting'] : null
    );
    $stmt->execute();

    // 9. Mold Open Parameters
    $sql = "INSERT INTO moldopenparameters (
        record_id, MoldOpenPos1, MoldOpenPos2, MoldOpenPos3, MoldOpenPos4, MoldOpenPos5, MoldOpenPos6,
        MoldOpenSpd1, MoldOpenSpd2, MoldOpenSpd3, MoldOpenSpd4, MoldOpenSpd5, MoldOpenSpd6,
        MoldOpenPressure1, MoldOpenPressure2, MoldOpenPressure3, MoldOpenPressure4, MoldOpenPressure5, MoldOpenPressure6
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssss",
        $record_id,
        isset($_POST['moldOpenPos1']) ? $_POST['moldOpenPos1'] : null,
        isset($_POST['moldOpenPos2']) ? $_POST['moldOpenPos2'] : null,
        isset($_POST['moldOpenPos3']) ? $_POST['moldOpenPos3'] : null,
        isset($_POST['moldOpenPos4']) ? $_POST['moldOpenPos4'] : null,
        isset($_POST['moldOpenPos5']) ? $_POST['moldOpenPos5'] : null,
        isset($_POST['moldOpenPos6']) ? $_POST['moldOpenPos6'] : null,
        isset($_POST['moldOpenSpd1']) ? $_POST['moldOpenSpd1'] : null,
        isset($_POST['moldOpenSpd2']) ? $_POST['moldOpenSpd2'] : null,
        isset($_POST['moldOpenSpd3']) ? $_POST['moldOpenSpd3'] : null,
        isset($_POST['moldOpenSpd4']) ? $_POST['moldOpenSpd4'] : null,
        isset($_POST['moldOpenSpd5']) ? $_POST['moldOpenSpd5'] : null,
        isset($_POST['moldOpenSpd6']) ? $_POST['moldOpenSpd6'] : null,
        isset($_POST['moldOpenPressure1']) ? $_POST['moldOpenPressure1'] : null,
        isset($_POST['moldOpenPressure2']) ? $_POST['moldOpenPressure2'] : null,
        isset($_POST['moldOpenPressure3']) ? $_POST['moldOpenPressure3'] : null,
        isset($_POST['moldOpenPressure4']) ? $_POST['moldOpenPressure4'] : null,
        isset($_POST['moldOpenPressure5']) ? $_POST['moldOpenPressure5'] : null,
        isset($_POST['moldOpenPressure6']) ? $_POST['moldOpenPressure6'] : null
    );
    $stmt->execute();

    // 10. Mold Close Parameters
    $sql = "INSERT INTO moldcloseparameters (
        record_id, MoldClosePos1, MoldClosePos2, MoldClosePos3, MoldClosePos4, MoldClosePos5, MoldClosePos6,
        MoldCloseSpd1, MoldCloseSpd2, MoldCloseSpd3, MoldCloseSpd4, MoldCloseSpd5, MoldCloseSpd6,
        MoldClosePressure1, MoldClosePressure2, MoldClosePressure3, MoldClosePressure4, PCLowOrLP, PCHighOrHP, LowPresTimeLimit
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssssss",
        $record_id,
        isset($_POST['moldClosePos1']) ? $_POST['moldClosePos1'] : null,
        isset($_POST['moldClosePos2']) ? $_POST['moldClosePos2'] : null,
        isset($_POST['moldClosePos3']) ? $_POST['moldClosePos3'] : null,
        isset($_POST['moldClosePos4']) ? $_POST['moldClosePos4'] : null,
        isset($_POST['moldClosePos5']) ? $_POST['moldClosePos5'] : null,
        isset($_POST['moldClosePos6']) ? $_POST['moldClosePos6'] : null,
        isset($_POST['moldCloseSpd1']) ? $_POST['moldCloseSpd1'] : null,
        isset($_POST['moldCloseSpd2']) ? $_POST['moldCloseSpd2'] : null,
        isset($_POST['moldCloseSpd3']) ? $_POST['moldCloseSpd3'] : null,
        isset($_POST['moldCloseSpd4']) ? $_POST['moldCloseSpd4'] : null,
        isset($_POST['moldCloseSpd5']) ? $_POST['moldCloseSpd5'] : null,
        isset($_POST['moldCloseSpd6']) ? $_POST['moldCloseSpd6'] : null,
        isset($_POST['moldClosePressure1']) ? $_POST['moldClosePressure1'] : null,
        isset($_POST['moldClosePressure2']) ? $_POST['moldClosePressure2'] : null,
        isset($_POST['moldClosePressure3']) ? $_POST['moldClosePressure3'] : null,
        isset($_POST['moldClosePressure4']) ? $_POST['moldClosePressure4'] : null,
        isset($_POST['pclorlp']) ? $_POST['pclorlp'] : null,
        isset($_POST['pchorhp']) ? $_POST['pchorhp'] : null,
        isset($_POST['lowPresTimeLimit']) ? $_POST['lowPresTimeLimit'] : null
    );
    $stmt->execute();

    // 11. Plasticizing Parameters
    $sql = "INSERT INTO plasticizingparameters (
        record_id, ScrewRPM1, ScrewRPM2, ScrewRPM3, ScrewSpeed1, ScrewSpeed2, ScrewSpeed3,
        PlastPressure1, PlastPressure2, PlastPressure3, PlastPosition1, PlastPosition2, PlastPosition3,
        BackPressure1, BackPressure2, BackPressure3, BackPressureStartPosition
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssss",
        $record_id,
        isset($_POST['screwRPM1']) ? $_POST['screwRPM1'] : null,
        isset($_POST['screwRPM2']) ? $_POST['screwRPM2'] : null,
        isset($_POST['screwRPM3']) ? $_POST['screwRPM3'] : null,
        isset($_POST['screwSpeed1']) ? $_POST['screwSpeed1'] : null,
        isset($_POST['screwSpeed2']) ? $_POST['screwSpeed2'] : null,
        isset($_POST['screwSpeed3']) ? $_POST['screwSpeed3'] : null,
        isset($_POST['plastPressure1']) ? $_POST['plastPressure1'] : null,
        isset($_POST['plastPressure2']) ? $_POST['plastPressure2'] : null,
        isset($_POST['plastPressure3']) ? $_POST['plastPressure3'] : null,
        isset($_POST['plastPosition1']) ? $_POST['plastPosition1'] : null,
        isset($_POST['plastPosition2']) ? $_POST['plastPosition2'] : null,
        isset($_POST['plastPosition3']) ? $_POST['plastPosition3'] : null,
        isset($_POST['backPressure1']) ? $_POST['backPressure1'] : null,
        isset($_POST['backPressure2']) ? $_POST['backPressure2'] : null,
        isset($_POST['backPressure3']) ? $_POST['backPressure3'] : null,
        isset($_POST['backPressureStartPosition']) ? $_POST['backPressureStartPosition'] : null
    );
    $stmt->execute();

    // 12. Injection Parameters
    $sql = "INSERT INTO injectionparameters (
        record_id, RecoveryPosition, SecondStagePosition, Cushion,
        ScrewPosition1, ScrewPosition2, ScrewPosition3, ScrewPosition4, ScrewPosition5, ScrewPosition6,
        InjectionSpeed1, InjectionSpeed2, InjectionSpeed3, InjectionSpeed4, InjectionSpeed5, InjectionSpeed6,
        InjectionPressure1, InjectionPressure2, InjectionPressure3, InjectionPressure4, InjectionPressure5, InjectionPressure6,
        SuckBackPosition, SuckBackSpeed, SuckBackPressure, SprueBreak, SprueBreakTime, InjectionDelay,
        HoldingPressure1, HoldingPressure2, HoldingPressure3, HoldingSpeed1, HoldingSpeed2, HoldingSpeed3,
        HoldingTime1, HoldingTime2, HoldingTime3
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssssssssssssssssssssss",
        $record_id,
        isset($_POST['RecoveryPosition']) ? $_POST['RecoveryPosition'] : null,
        isset($_POST['SecondStagePosition']) ? $_POST['SecondStagePosition'] : null,
        isset($_POST['Cushion']) ? $_POST['Cushion'] : null,
        isset($_POST['ScrewPosition1']) ? $_POST['ScrewPosition1'] : null,
        isset($_POST['ScrewPosition2']) ? $_POST['ScrewPosition2'] : null,
        isset($_POST['ScrewPosition3']) ? $_POST['ScrewPosition3'] : null,
        isset($_POST['ScrewPosition4']) ? $_POST['ScrewPosition4'] : null,
        isset($_POST['ScrewPosition5']) ? $_POST['ScrewPosition5'] : null,
        isset($_POST['ScrewPosition6']) ? $_POST['ScrewPosition6'] : null,
        isset($_POST['InjectionSpeed1']) ? $_POST['InjectionSpeed1'] : null,
        isset($_POST['InjectionSpeed2']) ? $_POST['InjectionSpeed2'] : null,
        isset($_POST['InjectionSpeed3']) ? $_POST['InjectionSpeed3'] : null,
        isset($_POST['InjectionSpeed4']) ? $_POST['InjectionSpeed4'] : null,
        isset($_POST['InjectionSpeed5']) ? $_POST['InjectionSpeed5'] : null,
        isset($_POST['InjectionSpeed6']) ? $_POST['InjectionSpeed6'] : null,
        isset($_POST['InjectionPressure1']) ? $_POST['InjectionPressure1'] : null,
        isset($_POST['InjectionPressure2']) ? $_POST['InjectionPressure2'] : null,
        isset($_POST['InjectionPressure3']) ? $_POST['InjectionPressure3'] : null,
        isset($_POST['InjectionPressure4']) ? $_POST['InjectionPressure4'] : null,
        isset($_POST['InjectionPressure5']) ? $_POST['InjectionPressure5'] : null,
        isset($_POST['InjectionPressure6']) ? $_POST['InjectionPressure6'] : null,
        isset($_POST['SuckBackPosition']) ? $_POST['SuckBackPosition'] : null,
        isset($_POST['SuckBackSpeed']) ? $_POST['SuckBackSpeed'] : null,
        isset($_POST['SuckBackPressure']) ? $_POST['SuckBackPressure'] : null,
        isset($_POST['SprueBreak']) ? $_POST['SprueBreak'] : null,
        isset($_POST['SprueBreakTime']) ? $_POST['SprueBreakTime'] : null,
        isset($_POST['InjectionDelay']) ? $_POST['InjectionDelay'] : null,
        isset($_POST['HoldingPressure1']) ? $_POST['HoldingPressure1'] : null,
        isset($_POST['HoldingPressure2']) ? $_POST['HoldingPressure2'] : null,
        isset($_POST['HoldingPressure3']) ? $_POST['HoldingPressure3'] : null,
        isset($_POST['HoldingSpeed1']) ? $_POST['HoldingSpeed1'] : null,
        isset($_POST['HoldingSpeed2']) ? $_POST['HoldingSpeed2'] : null,
        isset($_POST['HoldingSpeed3']) ? $_POST['HoldingSpeed3'] : null,
        isset($_POST['HoldingTime1']) ? $_POST['HoldingTime1'] : null,
        isset($_POST['HoldingTime2']) ? $_POST['HoldingTime2'] : null,
        isset($_POST['HoldingTime3']) ? $_POST['HoldingTime3'] : null
    );
    $stmt->execute();

    // 13. Ejection Parameters
    $sql = "INSERT INTO ejectionparameters (
        record_id, AirBlowTimeA, AirBlowPositionA, AirBlowADelay, AirBlowTimeB, AirBlowPositionB, AirBlowBDelay,
        EjectorForwardPosition1, EjectorForwardPosition2, EjectorForwardSpeed1, EjectorRetractPosition1, 
        EjectorRetractPosition2, EjectorRetractSpeed1, EjectorForwardPosition, EjectorForwardTime,
        EjectorRetractPosition, EjectorRetractTime, EjectorForwardSpeed2, EjectorForwardPressure1
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssss",
        $record_id,
        isset($_POST['AirBlowTimeA']) ? $_POST['AirBlowTimeA'] : null,
        isset($_POST['AirBlowPositionA']) ? $_POST['AirBlowPositionA'] : null,
        isset($_POST['AirBlowADelay']) ? $_POST['AirBlowADelay'] : null,
        isset($_POST['AirBlowTimeB']) ? $_POST['AirBlowTimeB'] : null,
        isset($_POST['AirBlowPositionB']) ? $_POST['AirBlowPositionB'] : null,
        isset($_POST['AirBlowBDelay']) ? $_POST['AirBlowBDelay'] : null,
        isset($_POST['EjectorForwardPosition1']) ? $_POST['EjectorForwardPosition1'] : null,
        isset($_POST['EjectorForwardPosition2']) ? $_POST['EjectorForwardPosition2'] : null,
        isset($_POST['EjectorForwardSpeed1']) ? $_POST['EjectorForwardSpeed1'] : null,
        isset($_POST['EjectorRetractPosition1']) ? $_POST['EjectorRetractPosition1'] : null,
        isset($_POST['EjectorRetractPosition2']) ? $_POST['EjectorRetractPosition2'] : null,
        isset($_POST['EjectorRetractSpeed1']) ? $_POST['EjectorRetractSpeed1'] : null,
        isset($_POST['EjectorForwardPosition']) ? $_POST['EjectorForwardPosition'] : null,
        isset($_POST['EjectorForwardTime']) ? $_POST['EjectorForwardTime'] : null,
        isset($_POST['EjectorRetractPosition']) ? $_POST['EjectorRetractPosition'] : null,
        isset($_POST['EjectorRetractTime']) ? $_POST['EjectorRetractTime'] : null,
        isset($_POST['EjectorForwardSpeed2']) ? $_POST['EjectorForwardSpeed2'] : null,
        isset($_POST['EjectorForwardPressure1']) ? $_POST['EjectorForwardPressure1'] : null
    );
    $stmt->execute();

    // 14. Additional Information
    $sql = "INSERT INTO additionalinformation (record_id, Info) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $record_id, isset($_POST['additionalInfo']) ? $_POST['additionalInfo'] : null);
    $stmt->execute();

    // 15. Personnel
    $sql = "INSERT INTO personnel (record_id, AdjusterName, QAEName) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $record_id, 
        isset($_POST['adjuster']) ? $_POST['adjuster'] : null,
        isset($_POST['qae']) ? $_POST['qae'] : null
    );
    $stmt->execute();

    // Log user activity
    logUserActivity($conn, $_SESSION['full_name'], $_SESSION['id_number'], 'PARAMETER_SUBMISSION', 
                   "Submitted parameters record: {$record_id}", $_SERVER['REMOTE_ADDR']);

    $conn->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'record_id' => $record_id,
        'message' => 'Parameters submitted successfully!'
    ]);

} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    error_log("Parameter submission error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while submitting the parameters. Please try again.',
        'details' => $e->getMessage()
    ]);
}
?>
