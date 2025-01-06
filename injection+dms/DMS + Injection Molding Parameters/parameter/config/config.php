<?php
$host = "localhost";
$username = "root";
$password = ""; // default for XAMPP
$database = "injection_molding";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
