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

$result = mysqli_query($conn, "SHOW COLUMNS FROM admin LIKE 'remember_token'");

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
$result = mysqli_query($conn, "SHOW COLUMNS FROM bill LIKE 'discount'");

if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "
        ALTER TABLE bill
        ADD discount DECIMAL(10,2) DEFAULT 0
    ");
}

$result = mysqli_query($conn, "SHOW COLUMNS FROM bill LIKE 'paid_amount'");

if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "
        ALTER TABLE bill
        ADD paid_amount DECIMAL(10,2) DEFAULT 0
    ");
}

$result = mysqli_query($conn, "SHOW COLUMNS FROM bill LIKE 'payment_status'");

if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn, "
        ALTER TABLE bill
        ADD payment_status ENUM('Paid','Due','Partial') DEFAULT 'Due'
    ");
}
$adminPassword = password_hash("admin123", PASSWORD_DEFAULT);
$userPassword = password_hash("password123", PASSWORD_DEFAULT);

$admins = [
    ["Admin One", 30, "01700000001", "admin1@medicore.com"],
    ["Admin Two", 31, "01700000002", "admin2@medicore.com"],
    ["Admin Three", 28, "01700000003", "admin3@medicore.com"],
    ["Admin Four", 32, "01700000004", "admin4@medicore.com"],
    ["Admin Five", 29, "01700000005", "admin5@medicore.com"]
];

foreach ($admins as $admin) {

    $name = $admin[0];
    $age = $admin[1];
    $phone = $admin[2];
    $email = $admin[3];

    $result = mysqli_query(
        $conn,
        "SELECT admin_id FROM admin WHERE email='$email'"
    );

    if (mysqli_num_rows($result) == 0) {

        mysqli_query(
            $conn,
            "INSERT INTO admin
            (name, age, phone, email, password)
            VALUES
            ('$name', '$age', '$phone', '$email', '$adminPassword')"
        );
    }
    else {

        mysqli_query(
            $conn,
            "UPDATE admin
            SET name='$name',
                age='$age',
                phone='$phone'
            WHERE email='$email'"
        );
    }
}

$doctors = [
    ["Dr. Anwar Hossain", "Cardiology", "MBBS, MD", 12, 1200, "01710000001", "anwar@medicore.com"],
    ["Dr. Sadia Karim", "Endocrinology", "MBBS, FCPS", 8, 1000, "01710000002", "sadia@medicore.com"],
    ["Dr. Rashedul Alam", "General Medicine", "MBBS", 6, 800, "01710000003", "rashedul@medicore.com"],
    ["Dr. Nusrat Jahan", "Neurology", "MBBS, MD", 10, 1100, "01710000004", "nusrat@medicore.com"],
    ["Dr. Fahim Ahmed", "Orthopedics", "MBBS, MS", 9, 950, "01710000005", "fahim@medicore.com"],
    ["Dr. Tanvir Hasan", "Pediatrics", "MBBS, FCPS", 7, 900, "01710000006", "tanvir@medicore.com"],
    ["Dr. Farzana Islam", "Dermatology", "MBBS, MD", 11, 1000, "01710000007", "farzana@medicore.com"],
    ["Dr. Mahmud Rahman", "Cardiology", "MBBS, FCPS", 14, 1300, "01710000008", "mahmud@medicore.com"],
    ["Dr. Sharmin Akter", "General Medicine", "MBBS", 5, 750, "01710000009", "sharmin@medicore.com"],
    ["Dr. Imran Kabir", "Neurology", "MBBS, MD", 13, 1250, "01710000010", "imran@medicore.com"],
    ["Dr. Sumaiya Noor", "Pediatrics", "MBBS, FCPS", 6, 850, "01710000011", "sumaiya@medicore.com"],
    ["Dr. Arif Chowdhury", "Orthopedics", "MBBS, MS", 15, 1400, "01710000012", "arif@medicore.com"],
    ["Dr. Mim Akter", "Dermatology", "MBBS, MD", 8, 950, "01710000013", "mim@medicore.com"],
    ["Dr. Saiful Islam", "Endocrinology", "MBBS, FCPS", 10, 1050, "01710000014", "saiful@medicore.com"],
    ["Dr. Jannat Ara", "General Medicine", "MBBS", 4, 700, "01710000015", "jannat@medicore.com"],
    ["Dr. Kamrul Hasan", "Cardiology", "MBBS, MD", 16, 1500, "01710000016", "kamrul@medicore.com"],
    ["Dr. Rafia Sultana", "Pediatrics", "MBBS, FCPS", 9, 1000, "01710000017", "rafia@medicore.com"],
    ["Dr. Hasan Mahmud", "Orthopedics", "MBBS, MS", 12, 1150, "01710000018", "hasan@medicore.com"],
    ["Dr. Tania Rahman", "Dermatology", "MBBS, MD", 7, 900, "01710000019", "tania@medicore.com"]
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

$patients = [
    ["Tasnim Rahman", "1992-05-14", "Female", "01800000001", "patient@medicore.com", "Dhaka", "B+"],
    ["Rakib Hasan", "1998-08-20", "Male", "01800000002", "patient2@medicore.com", "Uttara, Dhaka", "A+"],
    ["Nusrat Akter", "2000-03-12", "Female", "01800000003", "patient3@medicore.com", "Mirpur, Dhaka", "O+"],
    ["Siam Ahmed", "1995-11-05", "Male", "01800000004", "patient4@medicore.com", "Dhanmondi, Dhaka", "AB+"],
    ["Jannatul Ferdous", "2001-07-18", "Female", "01800000005", "patient5@medicore.com", "Mohammadpur, Dhaka", "A-"],
    ["Arif Hossain", "1990-02-15", "Male", "01800000006", "patient6@medicore.com", "Banani, Dhaka", "B-"],
    ["Mim Akter", "1997-09-11", "Female", "01800000007", "patient7@medicore.com", "Badda, Dhaka", "O-"],
    ["Tanvir Islam", "1994-06-23", "Male", "01800000008", "patient8@medicore.com", "Gulshan, Dhaka", "A+"],
    ["Farzana Yasmin", "1999-12-04", "Female", "01800000009", "patient9@medicore.com", "Rampura, Dhaka", "AB-"],
    ["Hasan Mahmud", "1988-01-28", "Male", "01800000010", "patient10@medicore.com", "Motijheel, Dhaka", "B+"],
    ["Rafia Sultana", "2002-04-17", "Female", "01800000011", "patient11@medicore.com", "Khilgaon, Dhaka", "O+"],
    ["Imran Kabir", "1993-10-09", "Male", "01800000012", "patient12@medicore.com", "Farmgate, Dhaka", "A-"],
    ["Sumaiya Noor", "1996-02-25", "Female", "01800000013", "patient13@medicore.com", "Lalmatia, Dhaka", "B+"],
    ["Saiful Islam", "1985-07-30", "Male", "01800000014", "patient14@medicore.com", "Jatrabari, Dhaka", "O+"],
    ["Tania Rahman", "2000-08-14", "Female", "01800000015", "patient15@medicore.com", "Bashundhara, Dhaka", "AB+"],
    ["Mahmudul Hasan", "1991-05-21", "Male", "01800000016", "patient16@medicore.com", "Mohakhali, Dhaka", "A+"],
    ["Sharmin Akter", "1998-11-16", "Female", "01800000017", "patient17@medicore.com", "Shyamoli, Dhaka", "B-"],
    ["Fahim Ahmed", "1989-03-08", "Male", "01800000018", "patient18@medicore.com", "Wari, Dhaka", "O+"],
    ["Nabila Islam", "2003-01-19", "Female", "01800000019", "patient19@medicore.com", "Mirpur, Dhaka", "A+"]
];

foreach ($patients as $patient) {

    $email = $patient[4];

    $result = mysqli_query(
        $conn,
        "SELECT patient_id FROM patient WHERE email='$email'"
    );

    if (mysqli_num_rows($result) == 0) {

        mysqli_query(
            $conn,
            "INSERT INTO patient
            (name, date_of_birth, gender, phone, email,
            password, address, blood_group)
            VALUES
            ('$patient[0]', '$patient[1]', '$patient[2]',
            '$patient[3]', '$patient[4]', '$userPassword',
            '$patient[5]', '$patient[6]')"
        );
    }
}

$beds = [
    ["General Ward A", "A-101", "General", "Available"],
    ["General Ward A", "A-102", "General", "Occupied"],
    ["General Ward A", "A-103", "General", "Available"],
    ["General Ward B", "B-101", "General", "Available"],
    ["General Ward B", "B-102", "General", "Maintenance"],
    ["General Ward B", "B-103", "General", "Occupied"],
    ["ICU", "ICU-01", "ICU", "Available"],
    ["ICU", "ICU-02", "ICU", "Occupied"],
    ["ICU", "ICU-03", "ICU", "Available"],
    ["Private Wing", "P-201", "Private", "Available"],
    ["Private Wing", "P-202", "Private", "Occupied"],
    ["Private Wing", "P-203", "Private", "Available"],
    ["Deluxe Wing", "D-301", "Deluxe", "Available"],
    ["Deluxe Wing", "D-302", "Deluxe", "Occupied"],
    ["Deluxe Wing", "D-303", "Deluxe", "Maintenance"]
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
    }
}

$appointments = [
    ["patient@medicore.com", "anwar@medicore.com", 0, "09:00:00", "Chest pain", "Pending"],
    ["patient2@medicore.com", "sadia@medicore.com", 0, "09:30:00", "Diabetes checkup", "Approved"],
    ["patient3@medicore.com", "rashedul@medicore.com", 0, "10:00:00", "Fever and weakness", "Completed"],
    ["patient4@medicore.com", "nusrat@medicore.com", 0, "10:30:00", "Headache", "Pending"],
    ["patient5@medicore.com", "fahim@medicore.com", 0, "11:00:00", "Knee pain", "Approved"],
    ["patient6@medicore.com", "tanvir@medicore.com", 0, "11:30:00", "Fever", "Completed"],
    ["patient7@medicore.com", "farzana@medicore.com", 0, "12:00:00", "Skin allergy", "Pending"],
    ["patient8@medicore.com", "mahmud@medicore.com", 0, "12:30:00", "Heart checkup", "Approved"],
    ["patient9@medicore.com", "sharmin@medicore.com", 1, "09:00:00", "General weakness", "Completed"],
    ["patient10@medicore.com", "imran@medicore.com", 1, "09:30:00", "Migraine", "Pending"],
    ["patient11@medicore.com", "sumaiya@medicore.com", 1, "10:00:00", "Routine checkup", "Approved"],
    ["patient12@medicore.com", "arif@medicore.com", 1, "10:30:00", "Back pain", "Pending"],
    ["patient13@medicore.com", "mim@medicore.com", 2, "11:00:00", "Skin problem", "Approved"],
    ["patient14@medicore.com", "saiful@medicore.com", 2, "12:00:00", "Hormone checkup", "Pending"],
    ["patient15@medicore.com", "jannat@medicore.com", 2, "14:00:00", "Regular checkup", "Approved"]
];

$appointmentIds = [];

foreach ($appointments as $appointment) {

    $patientEmail = $appointment[0];
    $doctorEmail = $appointment[1];
    $days = $appointment[2];
    $time = $appointment[3];
    $reason = $appointment[4];
    $status = $appointment[5];

    $date = date("Y-m-d", strtotime("+$days day"));

    $patientResult = mysqli_query(
        $conn,
        "SELECT patient_id FROM patient WHERE email='$patientEmail'"
    );

    $doctorResult = mysqli_query(
        $conn,
        "SELECT doctor_id FROM doctor WHERE email='$doctorEmail'"
    );

    $patient = mysqli_fetch_assoc($patientResult);
    $doctor = mysqli_fetch_assoc($doctorResult);

    if ($patient && $doctor) {

        $patientId = $patient["patient_id"];
        $doctorId = $doctor["doctor_id"];

        $check = mysqli_query(
            $conn,
            "SELECT appointment_id
            FROM appointment
            WHERE patient_id='$patientId'
            AND doctor_id='$doctorId'
            AND appointment_date='$date'
            AND appointment_time='$time'"
        );

        if (mysqli_num_rows($check) == 0) {

            mysqli_query(
                $conn,
                "INSERT INTO appointment
                (patient_id, doctor_id, appointment_date,
                appointment_time, reason, status)
                VALUES
                ('$patientId', '$doctorId', '$date',
                '$time', '$reason', '$status')"
            );

            $appointmentId = mysqli_insert_id($conn);
        }
        else {

            $row = mysqli_fetch_assoc($check);
            $appointmentId = $row["appointment_id"];
        }

        $appointmentIds[$patientEmail] = [
            "appointment_id" => $appointmentId,
            "patient_id" => $patientId,
            "doctor_id" => $doctorId
        ];
    }
}

$prescriptions = [
    ["patient@medicore.com", "Aspirin 75mg once daily", "Take after meal"],
    ["patient2@medicore.com", "Metformin 500mg twice daily", "Reduce sugar intake"],
    ["patient3@medicore.com", "Paracetamol 500mg twice daily", "Drink plenty of water"],
    ["patient4@medicore.com", "Napa 500mg when needed", "Take proper rest"],
    ["patient5@medicore.com", "Calcium tablet once daily", "Avoid heavy exercise"],
    ["patient6@medicore.com", "Paracetamol 500mg twice daily", "Maintain hydration"],
    ["patient7@medicore.com", "Antihistamine once daily", "Avoid allergy triggers"],
    ["patient8@medicore.com", "Aspirin 75mg once daily", "Check blood pressure regularly"],
    ["patient9@medicore.com", "Vitamin B Complex once daily", "Take after breakfast"],
    ["patient10@medicore.com", "Naproxen 250mg when needed", "Avoid excessive screen time"],
    ["patient11@medicore.com", "Multivitamin once daily", "Maintain healthy diet"],
    ["patient12@medicore.com", "Ibuprofen 400mg twice daily", "Avoid heavy lifting"],
    ["patient13@medicore.com", "Skin cream twice daily", "Keep affected area clean"],
    ["patient14@medicore.com", "Metformin 500mg once daily", "Maintain diabetic diet"],
    ["patient15@medicore.com", "Multivitamin once daily", "Regular follow-up recommended"]
];

foreach ($prescriptions as $prescription) {

    $patientEmail = $prescription[0];

    if (isset($appointmentIds[$patientEmail])) {

        $appointmentId = $appointmentIds[$patientEmail]["appointment_id"];
        $patientId = $appointmentIds[$patientEmail]["patient_id"];
        $doctorId = $appointmentIds[$patientEmail]["doctor_id"];

        $check = mysqli_query(
            $conn,
            "SELECT prescription_id
            FROM prescription
            WHERE appointment_id='$appointmentId'"
        );

        if (mysqli_num_rows($check) == 0) {

            mysqli_query(
                $conn,
                "INSERT INTO prescription
                (appointment_id, patient_id, doctor_id,
                prescription_date, medication_details, notes)
                VALUES
                ('$appointmentId', '$patientId', '$doctorId',
                CURDATE(), '$prescription[1]', '$prescription[2]')"
            );
        }
    }
}

$reports = [
    ["patient@medicore.com", "ECG", "reports/ecg1.pdf", "Normal ECG result"],
    ["patient2@medicore.com", "Blood Sugar", "reports/sugar2.pdf", "Blood sugar slightly high"],
    ["patient3@medicore.com", "CBC", "reports/cbc3.pdf", "Normal blood count"],
    ["patient4@medicore.com", "MRI", "reports/mri4.pdf", "No major abnormality"],
    ["patient5@medicore.com", "X-Ray", "reports/xray5.pdf", "Minor knee inflammation"],
    ["patient6@medicore.com", "CBC", "reports/cbc6.pdf", "Mild infection detected"],
    ["patient7@medicore.com", "Allergy Test", "reports/allergy7.pdf", "Mild allergic reaction"],
    ["patient8@medicore.com", "ECG", "reports/ecg8.pdf", "Heart rhythm normal"],
    ["patient9@medicore.com", "CBC", "reports/cbc9.pdf", "Normal result"],
    ["patient10@medicore.com", "MRI", "reports/mri10.pdf", "No significant issue"],
    ["patient11@medicore.com", "Blood Test", "reports/blood11.pdf", "Normal result"],
    ["patient12@medicore.com", "X-Ray", "reports/xray12.pdf", "Minor back strain"],
    ["patient13@medicore.com", "Skin Test", "reports/skin13.pdf", "Skin irritation found"],
    ["patient14@medicore.com", "Hormone Test", "reports/hormone14.pdf", "Hormone level slightly high"],
    ["patient15@medicore.com", "CBC", "reports/cbc15.pdf", "Normal result"]
];

foreach ($reports as $report) {

    $patientEmail = $report[0];

    if (isset($appointmentIds[$patientEmail])) {

        $appointmentId = $appointmentIds[$patientEmail]["appointment_id"];
        $patientId = $appointmentIds[$patientEmail]["patient_id"];
        $doctorId = $appointmentIds[$patientEmail]["doctor_id"];

        $check = mysqli_query(
            $conn,
            "SELECT report_id
            FROM medical_report
            WHERE appointment_id='$appointmentId'"
        );

        if (mysqli_num_rows($check) == 0) {

            mysqli_query(
                $conn,
                "INSERT INTO medical_report
                (appointment_id, patient_id, doctor_id,
                report_type, file_path, report_date, notes)
                VALUES
                ('$appointmentId', '$patientId', '$doctorId',
                '$report[1]', '$report[2]', CURDATE(), '$report[3]')"
            );
        }
    }
}

$bills = [
    ["patient@medicore.com", 1200, 100, 1100, "Paid"],
    ["patient2@medicore.com", 1000, 0, 500, "Partial"],
    ["patient3@medicore.com", 800, 50, 750, "Paid"],
    ["patient4@medicore.com", 1100, 0, 0, "Due"],
    ["patient5@medicore.com", 950, 100, 400, "Partial"],
    ["patient6@medicore.com", 900, 0, 900, "Paid"],
    ["patient7@medicore.com", 1000, 100, 0, "Due"],
    ["patient8@medicore.com", 1300, 100, 1200, "Paid"],
    ["patient9@medicore.com", 750, 50, 350, "Partial"],
    ["patient10@medicore.com", 1250, 0, 0, "Due"],
    ["patient11@medicore.com", 850, 50, 800, "Paid"],
    ["patient12@medicore.com", 1400, 100, 600, "Partial"],
    ["patient13@medicore.com", 950, 0, 950, "Paid"],
    ["patient14@medicore.com", 1050, 50, 0, "Due"],
    ["patient15@medicore.com", 700, 0, 700, "Paid"]
];

foreach ($bills as $bill) {

    $patientEmail = $bill[0];

    if (isset($appointmentIds[$patientEmail])) {

        $appointmentId = $appointmentIds[$patientEmail]["appointment_id"];
        $patientId = $appointmentIds[$patientEmail]["patient_id"];

        $check = mysqli_query(
            $conn,
            "SELECT bill_id
            FROM bill
            WHERE appointment_id='$appointmentId'"
        );

        if (mysqli_num_rows($check) == 0) {

            mysqli_query(
                $conn,
                "INSERT INTO bill
                (patient_id, appointment_id, amount,
                discount, paid_amount, payment_status, bill_date)
                VALUES
                ('$patientId', '$appointmentId', '$bill[1]',
                '$bill[2]', '$bill[3]', '$bill[4]', CURDATE())"
            );
        }
    }
}

?>