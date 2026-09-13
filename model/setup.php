<?php

$conn = mysqli_connect("localhost", "root", "");

if (!$conn) {
    die("Database connection failed");
}

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS hospital_db");
mysqli_select_db($conn, "hospital_db");

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS patient (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    blood_group VARCHAR(5),
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
");

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS doctor (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    qualification VARCHAR(150),
    experience INT DEFAULT 0,
    consultation_fee DECIMAL(10,2) DEFAULT 0,
    phone VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
");
mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
");
$result = mysqli_query($conn, "SHOW COLUMNS FROM admin LIKE 'age'");

if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "
        ALTER TABLE admin
        ADD age INT DEFAULT NULL
    ");
}

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS ward_bed (
    ward_bed_id INT AUTO_INCREMENT PRIMARY KEY,
    ward_name VARCHAR(100) NOT NULL,
    bed_number VARCHAR(20) NOT NULL,
    bed_type ENUM('General','Private','ICU','Deluxe') DEFAULT 'General',
    bed_status ENUM('Available','Occupied','Maintenance') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
");

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Pending','Approved','Rejected','Completed','Cancelled') DEFAULT 'Pending',
    reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctor(doctor_id) ON DELETE CASCADE
)
");

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS prescription (
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
)
");

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS medical_report (
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
)
");

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS bill (
    bill_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    appointment_id INT,
    amount DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) DEFAULT 0,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    payment_status ENUM('Paid','Due','Partial') DEFAULT 'Due',
    bill_date DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointment(appointment_id) ON DELETE SET NULL
)
");

$admins = [
    ["Admin One", 30, "01700000001", "admin1@medicore.com"],
    ["Admin Two", 31, "01700000002", "admin2@medicore.com"],
    ["Admin Three", 28, "01700000003", "admin3@medicore.com"],
    ["Admin Four", 32, "01700000004", "admin4@medicore.com"],
    ["Admin Five", 29, "01700000005", "admin5@medicore.com"]
];

foreach ($admins as $admin) {

    $email = $admin[2];

    $result = mysqli_query(
        $conn,
        "SELECT admin_id FROM admin WHERE email='$email'"
    );

    if (mysqli_num_rows($result) == 0) {

        mysqli_query(
            $conn,
            "INSERT INTO admin
            (name, phone, email, password)
            VALUES
            ('$admin[0]', '$admin[1]', '$admin[2]', '$adminPassword')"
        );
    }
}

$userPassword = password_hash("password123", PASSWORD_DEFAULT);

$doctors = [
    [
        "Dr. Anwar Hossain",
        "Cardiology",
        "MBBS, MD (Cardiology)",
        12,
        1200,
        "01710000001",
        "anwar@medicore.com"
    ],
    [
        "Dr. Sadia Karim",
        "Endocrinology",
        "MBBS, FCPS",
        8,
        1000,
        "01710000002",
        "sadia@medicore.com"
    ],
    [
        "Dr. Rashedul Alam",
        "General Medicine",
        "MBBS",
        6,
        800,
        "01710000003",
        "rashedul@medicore.com"
    ]
];

foreach ($doctors as $doctor) {

    $email = $doctor[6];

    $result = mysqli_query(
        $conn,
        "SELECT doctor_id FROM doctor WHERE email='$email'"
    );

    if (mysqli_num_rows($result) == 0) {

        mysqli_query(
            $conn,
            "INSERT INTO doctor
            (name, specialization, qualification, experience,
            consultation_fee, phone, email, password)
            VALUES
            ('$doctor[0]', '$doctor[1]', '$doctor[2]',
            '$doctor[3]', '$doctor[4]', '$doctor[5]',
            '$doctor[6]', '$userPassword')"
        );
    }
}

$patientEmail = "patient@medicore.com";

$result = mysqli_query(
    $conn,
    "SELECT patient_id FROM patient WHERE email='$patientEmail'"
);

if (mysqli_num_rows($result) == 0) {

    mysqli_query(
        $conn,
        "INSERT INTO patient
        (name, date_of_birth, gender, phone, email,
        password, address, blood_group)
        VALUES
        ('Tasnim Rahman', '1992-05-14', 'Female',
        '01800000001', '$patientEmail', '$userPassword',
        'Dhaka', 'B+')"
    );
}

$beds = [
    ["General Ward A", "A-101", "General", "Available"],
    ["General Ward A", "A-102", "General", "Occupied"],
    ["ICU", "ICU-01", "ICU", "Available"],
    ["Private Wing", "P-201", "Private", "Available"]
];

foreach ($beds as $bed) {

    $result = mysqli_query(
        $conn,
        "SELECT ward_bed_id
        FROM ward_bed
        WHERE ward_name='$bed[0]'
        AND bed_number='$bed[1]'"
    );

    if (mysqli_num_rows($result) == 0) {

        mysqli_query(
            $conn,
            "INSERT INTO ward_bed
            (ward_name, bed_number, bed_type, bed_status)
            VALUES
            ('$bed[0]', '$bed[1]', '$bed[2]', '$bed[3]')"
        );
    }$result = mysqli_query($conn, "SELECT appointment_id FROM appointment LIMIT 1");

if (mysqli_num_rows($result) == 0) {

    mysqli_query($conn, "
        INSERT INTO appointment
        (patient_id, doctor_id, appointment_date, appointment_time, reason, status)
        VALUES
        (1, 1, CURDATE(), '10:30:00', 'General Checkup', 'Pending'),
        (1, 2, CURDATE(), '12:00:00', 'Regular Checkup', 'Approved')
    ");
}$result = mysqli_query($conn, "SHOW COLUMNS FROM admin LIKE 'remember_token'");

if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "
        ALTER TABLE admin
        ADD remember_token VARCHAR(255) DEFAULT NULL
    ");
}

$result = mysqli_query($conn, "SHOW COLUMNS FROM doctor LIKE 'remember_token'");

if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "
        ALTER TABLE doctor
        ADD remember_token VARCHAR(255) DEFAULT NULL
    ");
}

$result = mysqli_query($conn, "SHOW COLUMNS FROM patient LIKE 'remember_token'");

if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "
        ALTER TABLE patient
        ADD remember_token VARCHAR(255) DEFAULT NULL
    ");
}
}