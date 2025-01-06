<?php
// upload.php

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables for form fields
    $date = $_POST['date'];
    $time = $_POST['time'];
    $machine = $_POST['machine'];
    $productionRunNumber = $_POST['productionRunNumber'];
    $category = $_POST['category'];
    $irn = $_POST['IRN'];
    $product = $_POST['product'];
    $color = $_POST['color'];
    $moldName = $_POST['mold-name'];
    
    // Handle file uploads
    $imagePath = "";
    $videoPath = "";

    // Upload image if exists
    if (isset($_FILES['uploadImage']) && $_FILES['uploadImage']['error'] == 0) {
        $imagePath = 'uploads/' . basename($_FILES['uploadImage']['name']);
        move_uploaded_file($_FILES['uploadImage']['tmp_name'], $imagePath);
    }

    // Upload video if exists
    if (isset($_FILES['uploadVideo']) && $_FILES['uploadVideo']['error'] == 0) {
        $videoPath = 'uploads/' . basename($_FILES['uploadVideo']['name']);
        move_uploaded_file($_FILES['uploadVideo']['tmp_name'], $videoPath);
    }

    // Prepare SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO parameters (date, time, machine, production_run_number, category, irn, product, color, mold_name, image, video) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $date, $time, $machine, $productionRunNumber, $category, $irn, $product, $color, $moldName, $imagePath, $videoPath);

    if ($stmt->execute()) {
        echo "Form submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
