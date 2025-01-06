<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "sentinel";
$dbname = "injection_molding";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $date = $_POST['date'];
    $time = $_POST['time'];
    $machine = $_POST['machine'];
    $productionRunNumber = $_POST['productionRunNumber'];
    $category = $_POST['category'];
    $IRN = $_POST['IRN'];
    $productName = $_POST['productName'];
    $color = $_POST['color'];
    $moldName = $_POST['moldName'];
    $productNumber = $_POST['productNumber'];
    $cavity = $_POST['cavity'];
    $grossWeight = $_POST['grossWeight'];
    $netWeight = $_POST['netWeight'];
    $dryingTime = $_POST['dryingTime'];
    $dryingTemp = $_POST['dryingTemp'];
    $materialType1 = $_POST['materialType1'];
    $materialBrand1 = $_POST['materialBrand1'];
    $materialMix1 = $_POST['materialMix1'];
    $materialType2 = $_POST['materialType2'];
    $materialBrand2 = $_POST['materialBrand2'];
    $materialMix2 = $_POST['materialMix2'];
    $materialType3 = $_POST['materialType3'];
    $materialBrand3 = $_POST['materialBrand3'];
    $materialMix3 = $_POST['materialMix3'];
    $materialType4 = $_POST['materialType4'];
    $materialBrand4 = $_POST['materialBrand4'];
    $materialMix4 = $_POST['materialMix4'];
    $colorant = $_POST['colorant'];
    $colorantColor = $_POST['colorantColor'];
    $colorantDosage = $_POST['colorantDosage'];
    $colorantStabilizer = $_POST['colorantStabilizer'];
    $colorantStabilizerDosage = $_POST['colorantStabilizerDosage'];
    $moldCode = $_POST['moldCode'];
    $clampingForce = $_POST['clampingForce'];
    $operationType = $_POST['operationType'];
    $coolingMedia = $_POST['coolingMedia'];
    $heatingMedia = $_POST['heatingMedia'];
    $fillingTime = $_POST['fillingTime'];
    $holdingTime = $_POST['holdingTime'];
    $moldOpenCloseTime = $_POST['moldOpenCloseTime'];
    $chargingTime = $_POST['chargingTime'];
    $coolingTime = $_POST['coolingTime'];
    $cycleTime = $_POST['cycleTime'];
    $additionalInfo = $_POST['additionalInfo'];
    $operatorName = $_POST['operator'];
    $inspectorName = $_POST['inspector'];

    // Handling file uploads
    $imagePath = "";
    $videoPath = "";
    if (isset($_FILES['uploadImage']) && $_FILES['uploadImage']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['uploadImage']['tmp_name'];
        $imageName = basename($_FILES['uploadImage']['name']);
        $imagePath = 'uploads/images/' . $imageName;
        move_uploaded_file($imageTmpPath, $imagePath);
    }
    if (isset($_FILES['uploadVideo']) && $_FILES['uploadVideo']['error'] == UPLOAD_ERR_OK) {
        $videoTmpPath = $_FILES['uploadVideo']['tmp_name'];
        $videoName = basename($_FILES['uploadVideo']['name']);
        $videoPath = 'uploads/videos/' . $videoName;
        move_uploaded_file($videoTmpPath, $videoPath);
    }

    // SQL Insert Query
    $sql = "INSERT INTO molding_parameters (date, time, machine, productionRunNumber, category, IRN, productName, color, moldName, productNumber, cavity, grossWeight, netWeight, dryingTime, dryingTemp, materialType1, materialBrand1, materialMix1, materialType2, materialBrand2, materialMix2, materialType3, materialBrand3, materialMix3, materialType4, materialBrand4, materialMix4, colorant, colorantColor, colorantDosage, colorantStabilizer, colorantStabilizerDosage, moldCode, clampingForce, operationType, coolingMedia, heatingMedia, fillingTime, holdingTime, moldOpenCloseTime, chargingTime, coolingTime, cycleTime, additionalInfo, operatorName, inspectorName, imagePath, videoPath)
    VALUES ('$date', '$time', '$machine', '$productionRunNumber', '$category', '$IRN', '$productName', '$color', '$moldName', '$productNumber', '$cavity', '$grossWeight', '$netWeight', '$dryingTime', '$dryingTemp', '$materialType1', '$materialBrand1', '$materialMix1', '$materialType2', '$materialBrand2', '$materialMix2', '$materialType3', '$materialBrand3', '$materialMix3', '$materialType4', '$materialBrand4', '$materialMix4', '$colorant', '$colorantColor', '$colorantDosage', '$colorantStabilizer', '$colorantStabilizerDosage', '$moldCode', '$clampingForce', '$operationType', '$coolingMedia', '$heatingMedia', '$fillingTime', '$holdingTime', '$moldOpenCloseTime', '$chargingTime', '$coolingTime', '$cycleTime', '$additionalInfo', '$operatorName', '$inspectorName', '$imagePath', '$videoPath')";

   // Execute the query
if ($conn->query($sql) === TRUE) {
    echo '<div class="alert alert-success" role="alert">';
    echo "Form data successfully saved.";
    echo '</div>';
} else {
    echo '<div class="alert alert-danger" role="alert">';
    echo "Error: " . htmlspecialchars($sql) . "<br>" . htmlspecialchars($conn->error);
    echo '</div>';
}
} else {
    echo '<div class="alert alert-warning" role="alert">';
    echo "Invalid request method.";
    echo '</div>';
}

$conn->close();
?>
