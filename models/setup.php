<?php
include "../config/database.php";

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hospital_db";


$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("MySQL connection failed: " . mysqli_connect_error());
}


$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if (!mysqli_query($conn, $sql)) {
    die("Database creation failed: " . mysqli_error($conn));
}


mysqli_select_db($conn, $dbname);


$sql = "CREATE TABLE IF NOT EXISTS patient (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    blood_group VARCHAR(10)
)";

if (!mysqli_query($conn, $sql)) {
    die("Patient table error: " . mysqli_error($conn));
}


$sql = "CREATE TABLE IF NOT EXISTS doctor (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    qualification VARCHAR(150),
    experience INT DEFAULT 0,
    consultation_fee DECIMAL(10,2) DEFAULT 0,
    phone VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if (!mysqli_query($conn, $sql)) {
    die("Doctor table error: " . mysqli_error($conn));
}


$sql = "CREATE TABLE IF NOT EXISTS admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if (!mysqli_query($conn, $sql)) {
    die("Admin table error: " . mysqli_error($conn));
}


$sql = "CREATE TABLE IF NOT EXISTS ward_bed (
    ward_bed_id INT AUTO_INCREMENT PRIMARY KEY,
    ward_name VARCHAR(100) NOT NULL,
    bed_number VARCHAR(20) NOT NULL,
    bed_type VARCHAR(50) NOT NULL,
    bed_status VARCHAR(30) DEFAULT 'Available'
)";

if (!mysqli_query($conn, $sql)) {
    die("Ward/Bed table error: " . mysqli_error($conn));
}


$sql = "CREATE TABLE IF NOT EXISTS appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason VARCHAR(255),
    status VARCHAR(30) DEFAULT 'Pending',
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id) ON DELETE CASCADE
)";

if (!mysqli_query($conn, $sql)) {
    die("Appointment table error: " . mysqli_error($conn));
}


$sql = "CREATE TABLE IF NOT EXISTS prescription (
    prescription_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    prescription_date DATE NOT NULL,
    medication_details TEXT NOT NULL,
    notes TEXT,
    FOREIGN KEY (appointment_id) REFERENCES appointment(appointment_id) ON DELETE SET NULL,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id) ON DELETE CASCADE
)";

if (!mysqli_query($conn, $sql)) {
    die("Prescription table error: " . mysqli_error($conn));
}

$sql = "CREATE TABLE IF NOT EXISTS medical_report (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    report_type VARCHAR(100) NOT NULL,
    file_path VARCHAR(255),
    report_date DATE NOT NULL,
    notes TEXT,
    FOREIGN KEY (appointment_id) REFERENCES appointment(appointment_id) ON DELETE SET NULL,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id) ON DELETE CASCADE
)";

if (!mysqli_query($conn, $sql)) {
    die("Medical report table error: " . mysqli_error($conn));
}


$sql = "CREATE TABLE IF NOT EXISTS bill (
    bill_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    appointment_id INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_status VARCHAR(30) DEFAULT 'Due',
    bill_date DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointment(appointment_id) ON DELETE SET NULL
)";

if (!mysqli_query($conn, $sql)) {
    die("Bill table error: " . mysqli_error($conn));
}


$admin_email = "admin@medicore.com";
$check = mysqli_query($conn, "SELECT admin_id FROM admin WHERE email='$admin_email'");

if (mysqli_num_rows($check) == 0) {
    $admin_name = "MediCore Admin";
    $admin_phone = "01700000000";
    $admin_password = password_hash("admin123", PASSWORD_DEFAULT);

    $insert_admin = "INSERT INTO admin (name, phone, email, password)
                     VALUES ('$admin_name', '$admin_phone', '$admin_email', '$admin_password')";

    mysqli_query($conn, $insert_admin);
}

echo "<h2>MediCore database created successfully.</h2>";
echo "<p>Database name: <b>$dbname</b></p>";
echo "<p>Tables created: patient, doctor, admin, ward_bed, appointment, prescription, medical_report, bill.</p>";
echo "<p>Default Admin: <b>admin@medicore.com</b> / <b>admin123</b></p>";
echo "<p>You can now open phpMyAdmin and check the database.</p>";

mysqli_close($conn);
?>
