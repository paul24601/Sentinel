<?php 
$servername = "localhost";
$username = 'root';
$password = '';
$dbname = 'dms_db';

$db = new mysqli('localhost', $username, $password, $dbname)or die
("Unable to connect");

echo"Great Work!";
?>

