<?php
// Start session if needed
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "Admin123@plvil";
$dbname = "injectionmoldingparameters";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Transaction for consistency
    $conn->begin_transaction();
    try {
        // Insert Product and Machine Information
        $stmt = $conn->prepare("
            INSERT INTO productmachineinfo (Date, Time, MachineName, RunNumber, Category, IRN) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssss",
            $_POST['Date'],
            $_POST['Time'],
            $_POST['MachineName'],
            $_POST['RunNumber'],
            $_POST['Category'],
            $_POST['IRN']
        );
        $stmt->execute();
        $stmt->close();

        // Insert Product Details
        $stmt = $conn->prepare("
            INSERT INTO productdetails (ProductName, Color, MoldName, ProductNumber, CavityActive, GrossWeight, NetWeight)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssidd",
            $_POST['product'],
            $_POST['color'],
            $_POST['mold-name'],
            $_POST['prodNo'],
            $_POST['cavity'],
            $_POST['grossWeight'],
            $_POST['netWeight']
        );
        $stmt->execute();
        $stmt->close();

        // Insert Material Composition
        $stmt = $conn->prepare("
            INSERT INTO materialcomposition 
            (DryingTime, DryingTemperature, Material1_Type, Material1_Brand, Material1_MixturePercentage,
             Material2_Type, Material2_Brand, Material2_MixturePercentage, 
             Material3_Type, Material3_Brand, Material3_MixturePercentage, 
             Material4_Type, Material4_Brand, Material4_MixturePercentage)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ddssdssdssdssd",
            $_POST['dryingtime'],
            $_POST['dryingtemp'],
            $_POST['type1'],
            $_POST['brand1'],
            $_POST['mix1'],
            $_POST['type2'],
            $_POST['brand2'],
            $_POST['mix2'],
            $_POST['type3'],
            $_POST['brand3'],
            $_POST['mix3'],
            $_POST['type4'],
            $_POST['brand4'],
            $_POST['mix4']
        );
        $stmt->execute();
        $stmt->close();

        // Insert Colorant Details
        $stmt = $conn->prepare("
            INSERT INTO colorantdetails (Colorant, Color, Dosage, Stabilizer, StabilizerDosage)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss",
            $_POST['colorant'],
            $_POST['color'],
            $_POST['colorant-dosage'],
            $_POST['colorant-stabilizer'],
            $_POST['colorant-stabilizer-dosage']
        );
        $stmt->execute();
        $stmt->close();

        // Insert Mold and Operation Specifications
        $stmt = $conn->prepare("
            INSERT INTO moldoperationspecs (MoldCode, ClampingForce, OperationType, CoolingMedia, HeatingMedia)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss",
            $_POST['mold-code'],
            $_POST['clamping-force'],
            $_POST['operation-type'],
            $_POST['cooling-media'],
            $_POST['heating-media']
        );
        $stmt->execute();
        $stmt->close();

        // Insert Timer Parameters
        $stmt = $conn->prepare("
            INSERT INTO timerparameters (FillingTime, HoldingTime, MoldOpenCloseTime, ChargingTime, CoolingTime, CycleTime)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("dddddd",
            $_POST['fillingTime'],
            $_POST['holdingTime'],
            $_POST['moldOpenCloseTime'],
            $_POST['chargingTime'],
            $_POST['coolingTime'],
            $_POST['cycleTime']
        );
        $stmt->execute();
        $stmt->close();

        // Insert Personnel
        $stmt = $conn->prepare("
            INSERT INTO personnel (AdjusterName, QAEName)
            VALUES (?, ?)
        ");
        $stmt->bind_param("ss",
            $_POST['adjuster'],
            $_POST['qae']
        );
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Redirect to success page
        header("Location: success.html");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}

// Close database connection
$conn->close();
?>
