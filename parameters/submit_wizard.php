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

    // Insert into parameter_records table
    $sql = "INSERT INTO parameter_records (record_id, submitted_by, submission_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $record_id, $submitted_by);
    $stmt->execute();

    // Helper function to get safe value
    function getValue($key, $default = null) {
        return isset($_POST[$key]) && $_POST[$key] !== '' ? $_POST[$key] : $default;
    }

    // Helper function to convert time to Philippine timezone
    function formatPhilippineTime($timeValue) {
        if (empty($timeValue)) return null;
        if ($timeValue === '00:00' || $timeValue === '00:00:00') return null;
        if (preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $timeValue)) return $timeValue;
        if (preg_match('/^\d{1,2}:\d{2}$/', $timeValue)) return $timeValue . ':00';
        return date('H:i:s');
    }

    // 1. Product and Machine Information
    $processNo = getValue('process_no');
    $productDesc = getValue('product_description');
    $shotCount = getValue('shot_count');
    $machineNo = getValue('machine_no');
    $injectionMachine = getValue('injection_machine');
    
    $sql = "INSERT INTO productmachineinfo (record_id, ProcessNo, ProductDescription, ShotCount, MachineNo, InjectionMachine) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiss", $record_id, $processNo, $productDesc, $shotCount, $machineNo, $injectionMachine);
    $stmt->execute();

    // 2. Product Details
    $sql = "INSERT INTO productdetails (record_id, PartNumber, PartDescription, CoreCavity, Tolerance) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",
        $record_id,
        getValue('part_number'),
        getValue('part_description'),
        getValue('core_cavity'),
        getValue('tolerance')
    );
    $stmt->execute();

    // 3. Material Composition (handle up to 4 materials)
    $sql = "INSERT INTO materialcomposition (record_id, Type1, Brand1, Mix1, Type2, Brand2, Mix2, Type3, Brand3, Mix3, Type4, Brand4, Mix4) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss",
        $record_id,
        getValue('type1'),
        getValue('brand1'),
        getValue('mix1'),
        getValue('type2'),
        getValue('brand2'),
        getValue('mix2'),
        getValue('type3'),
        getValue('brand3'),
        getValue('mix3'),
        getValue('type4'),
        getValue('brand4'),
        getValue('mix4')
    );
    $stmt->execute();

    // 4. Colorant Details
    $sql = "INSERT INTO colorantdetails (record_id, Colorant, ColorantColor, ColorantDosage, ColorantStabilizer, ColorantStabilizerDosage) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss",
        $record_id,
        getValue('colorant'),
        getValue('colorantColor'),
        getValue('colorant-dosage'),
        getValue('colorant-stabilizer'),
        getValue('colorant-stabilizer-dosage')
    );
    $stmt->execute();

    // 5. Mold and Operation Specifications
    $sql = "INSERT INTO moldoperationspecs (record_id, MoldCode, ClampingForce, ShotSize, CycleTime) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",
        $record_id,
        getValue('mold-code'),
        getValue('clamping-force'),
        getValue('shot-size'),
        getValue('cycle-time')
    );
    $stmt->execute();

    // 6. Timer Parameters
    $sql = "INSERT INTO timerparameters (record_id, InjectionTime, PackingTime, CoolingTime, CorePullTimer) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",
        $record_id,
        formatPhilippineTime(getValue('injection-time')),
        formatPhilippineTime(getValue('packing-time')),
        formatPhilippineTime(getValue('cooling-time')),
        formatPhilippineTime(getValue('core-pull-timer'))
    );
    $stmt->execute();

    // 7. Barrel Heater Temperatures
    $sql = "INSERT INTO barrelheatertemperatures (record_id, NozzleTemp, BarrelTemp1, BarrelTemp2, BarrelTemp3) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",
        $record_id,
        getValue('nozzle-temp'),
        getValue('barrel-temp-1'),
        getValue('barrel-temp-2'),
        getValue('barrel-temp-3')
    );
    $stmt->execute();

    // 8. Mold Heater Temperatures
    $sql = "INSERT INTO moldheatertemperatures (record_id, MoldTemp1, MoldTemp2, MoldTemp3) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss",
        $record_id,
        getValue('mold-temp-1'),
        getValue('mold-temp-2'),
        getValue('mold-temp-3')
    );
    $stmt->execute();

    // 9. Injection Parameters
    $sql = "INSERT INTO injectionparameters (record_id, InjectionPressure, InjectionSpeed, PackingPressure, BackPressure) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",
        $record_id,
        getValue('injection-pressure'),
        getValue('injection-speed'),
        getValue('packing-pressure'),
        getValue('back-pressure')
    );
    $stmt->execute();

    // 10. Mold Close Parameters
    $sql = "INSERT INTO moldcloseparameters (record_id, MoldCloseSpeed) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $record_id, getValue('mold-close-speed'));
    $stmt->execute();

    // Mold Open Parameters
    $sql = "INSERT INTO moldopenparameters (record_id, MoldOpenSpeed) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $record_id, getValue('mold-open-speed'));
    $stmt->execute();

    // Ejection Parameters
    $sql = "INSERT INTO ejectionparameters (record_id, EjectionSpeed) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $record_id, getValue('ejection-speed'));
    $stmt->execute();

    // 11. Additional Information
    $sql = "INSERT INTO additionalinformation (record_id, Remarks, SpecialInstructions, QualityNotes) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss",
        $record_id,
        getValue('remarks'),
        getValue('special-instructions'),
        getValue('quality-notes')
    );
    $stmt->execute();

    // 12. Personnel
    $sql = "INSERT INTO personnel (record_id, OperatorName, InspectorName, Shift) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss",
        $record_id,
        getValue('operator-name'),
        getValue('inspector-name'),
        getValue('shift')
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
    $conn->rollback();
    error_log("Parameter submission error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while submitting the parameters. Please try again.',
        'details' => $e->getMessage()
    ]);
}
?>
