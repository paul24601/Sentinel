<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "Admin123@plvil";
$dbname = "injectionmoldingparameters";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

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
        "ddssssdsdsdssd",
        $_POST['dryingtime'],           // Drying Time
        $_POST['dryingtemp'],           // Drying Temperature
        $type1, $brand1, $mix1,         // Material 1
        $type2, $brand2, $mix2,         // Material 2
        $type3, $brand3, $mix3,         // Material 3
        $type4, $brand4, $mix4          // Material 4
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
    $stmt->bind_param("sssss", $_POST['colorant'], $_POST['color'], $_POST['colorant-dosage'], $_POST['colorant-stabilizer'], $_POST['colorant-stabilizer-dosage']);
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
if (isset($_POST['barrelHeaterZone0'], $_POST['barrelHeaterZone1'], $_POST['MTCSetting'])) {
    $sql = "INSERT INTO moldheatertemperatures (
                Zone0, Zone1, Zone2, Zone3, Zone4, Zone5, Zone6, Zone7, Zone8, Zone9,
                Zone10, Zone11, Zone12, Zone13, Zone14, Zone15, Zone16, MTCSetting
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "dddddddddddddddddd",
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
        $_POST['barrelHeaterZone16'],
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
if (isset($_POST['recoveryPOS'], $_POST['screwPosition1'], $_POST['injectionSpeed1'])) {
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
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $errors[] = "Error preparing statement: " . $conn->error;
    } else {
        // Default values for optional fields
        $secondStagePosition = $_POST['secondStagePosition'] ?? null;
        $cushion = $_POST['cushion'] ?? null;
        $screwPosition2 = $_POST['screwPosition2'] ?? null;
        $screwPosition3 = $_POST['screwPosition3'] ?? null;
        $injectionSpeed2 = $_POST['injectionSpeed2'] ?? null;
        $injectionSpeed3 = $_POST['injectionSpeed3'] ?? null;
        $injectionPressure1 = $_POST['injectionPressure1'] ?? null;
        $injectionPressure2 = $_POST['injectionPressure2'] ?? null;
        $injectionPressure3 = $_POST['injectionPressure3'] ?? null;
        $suckBackPosition = $_POST['suckBackPosition'] ?? null;
        $suckBackSpeed = $_POST['suckBackSpeed'] ?? null;
        $suckBackPressure = $_POST['suckBackPressure'] ?? null;
        $sprueBreak = $_POST['SprueBreak'] ?? null;
        $sprueBreakTime = $_POST['SprueBreakTime'] ?? null;
        $injectionDelay = $_POST['InjectionDelay'] ?? null;
        $holdingPressure1 = $_POST['HoldingPres1'] ?? null;
        $holdingPressure2 = $_POST['HoldingPres2'] ?? null;
        $holdingPressure3 = $_POST['HoldingPres3'] ?? null;
        $holdingSpeed1 = $_POST['HoldingSpeed1'] ?? null;
        $holdingSpeed2 = $_POST['HoldingSpeed2'] ?? null;
        $holdingSpeed3 = $_POST['HoldingSpeed3'] ?? null;
        $holdingTime1 = $_POST['HoldingTime1'] ?? null;
        $holdingTime2 = $_POST['HoldingTime2'] ?? null;
        $holdingTime3 = $_POST['HoldingTime3'] ?? null;

        // Bind parameters
        $stmt->bind_param(
            "fffffffffffffffffffffffffff",
            $_POST['recoveryPOS'],
            $secondStagePosition,
            $cushion,
            $_POST['screwPosition1'],
            $screwPosition2,
            $screwPosition3,
            $_POST['injectionSpeed1'],
            $injectionSpeed2,
            $injectionSpeed3,
            $injectionPressure1,
            $injectionPressure2,
            $injectionPressure3,
            $suckBackPosition,
            $suckBackSpeed,
            $suckBackPressure,
            $sprueBreak,
            $sprueBreakTime,
            $injectionDelay,
            $holdingPressure1,
            $holdingPressure2,
            $holdingPressure3,
            $holdingSpeed1,
            $holdingSpeed2,
            $holdingSpeed3,
            $holdingTime1,
            $holdingTime2,
            $holdingTime3
        );

        // Execute and check for errors
        if (!$stmt->execute()) {
            $errors[] = "Error inserting into injectionparameters: " . $stmt->error;
        }
        $stmt->close();
    }
}



// Insert into ejectionparameters table
if (isset($_POST['airBlowTimeA'], $_POST['ejectorForwardPosition1'], $_POST['ejectorForwardSpeed2'])) {
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
        $_POST['airBlowTimeA'],
        $_POST['airBlowPositionA'],
        $_POST['airBlowADelay'],
        $_POST['airBlowTimeB'],
        $_POST['airBlowPositionB'],
        $_POST['airBlowBDelay'],
        $_POST['ejectorForwardPosition1'],
        $_POST['ejectorForwardPosition2'],
        $_POST['ejectorForwardSpeed1'],
        $_POST['ejectorRetractPosition1'],
        $_POST['ejectorRetractPosition2'],
        $_POST['ejectorRetractSpeed1'],
        $_POST['ejectorForwardSpeed2'],
        $_POST['ejectorForwardPressure1'],
        $_POST['ejectorRetractSpeed2'],
        $_POST['ejectorRetractPressure1']
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into ejectionparameters: " . $stmt->error;
    }
}

// Insert into corepullsettings table
if (isset($_POST['coreSetASequence'], $_POST['coreSetAPressure'], $_POST['coreSetASpeed'])) {
    $sql = "INSERT INTO corepullsettings (Section, Sequence, Pressure, Speed, Position, Time, LimitSwitch)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sidddds",
        $_POST['coreSetASection'],
        $_POST['coreSetASequence'],
        $_POST['coreSetAPressure'],
        $_POST['coreSetASpeed'],
        $_POST['coreSetAPosition'],
        $_POST['coreSetATime'],
        $_POST['coreSetALimitSwitch']
    );
    if (!$stmt->execute()) {
        $errors[] = "Error inserting into corepullsettings: " . $stmt->error;
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

// Output success or errors
if (empty($errors)) {
    echo "Data submitted successfully!";
} else {
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}

// Close the connection
$conn->close();
?>