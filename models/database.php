<?php
// Simple XAMPP MySQL connection for MediCore

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hospital_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
