<?php

require_once __DIR__ . "/functions.php";
require_role("admin");
require_once __DIR__ . "/../model/AdminModel.php";

if (isset($_GET["action"]) && $_GET["action"] == "doctors") {
    $doctors = getAllDoctors();
    $partial = "doctors";

    ob_start();
    include __DIR__ . "/../view/AdminView.php";
    $html = ob_get_clean();

    header("Content-Type: application/json");

    echo json_encode([
        "success" => true,
        "html" => $html
    ]);

    exit();
}

if (isset($_GET["action"]) && $_GET["action"] == "patients") {
    $patients = getAllPatients();
    $partial = "patients";

    ob_start();
    include __DIR__ . "/../view/AdminView.php";
    $html = ob_get_clean();

    header("Content-Type: application/json");

    echo json_encode([
        "success" => true,
        "html" => $html
    ]);

    exit();
}


if (isset($_GET["action"]) && $_GET["action"] == "appointments") {
    $appointments = getAllAppointments();
    $partial = "appointments";

    ob_start();
    include __DIR__ . "/../view/AdminView.php";
    $html = ob_get_clean();

    header("Content-Type: application/json");

    echo json_encode([
        "success" => true,
        "html" => $html
    ]);

    exit();
}


if (isset($_GET["action"]) && $_GET["action"] == "wardbeds") {
    $wardBeds = getAllWardBeds();
    $partial = "wardbeds";

    ob_start();
    include __DIR__ . "/../view/AdminView.php";
    $html = ob_get_clean();

    header("Content-Type: application/json");

    echo json_encode([
        "success" => true,
        "html" => $html
    ]);

    exit();
}
if (isset($_GET["action"]) && $_GET["action"] == "changePassword") {

    $partial = "changePassword";

    ob_start();
    include __DIR__ . "/../view/AdminView.php";
    $html = ob_get_clean();

    header("Content-Type: application/json");

    echo json_encode([
        "success" => true,
        "html" => $html
    ]);

    exit();
}
if (isset($_POST["doctor_action"])) {
  
    header("Content-Type: application/json");

    $doctorAction = $_POST["doctor_action"];

    $allowedSpecializations = [
        "Cardiology",
        "Endocrinology",
        "General Medicine",
        "Neurology",
        "Orthopedics",
        "Pediatrics",
        "Dermatology"
    ];

    if ($doctorAction == "add") {

        $name = trim($_POST["name"]);
        $specialization = trim($_POST["specialization"]);
        $qualification = trim($_POST["qualification"]);
        $experience = trim($_POST["experience"]);
        $consultationFee = trim($_POST["consultation_fee"]);
        $phone = trim($_POST["phone"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        if ($name == "" || $specialization == "" || $phone == "" || $email == "" || $password == "") {
            echo json_encode([
                "success" => false,
                "message" => "Name, specialization, phone, email and password are required."
            ]);
            exit();
        }

        if (!in_array($specialization, $allowedSpecializations)) {
            echo json_encode([
                "success" => false,
                "message" => "Please select a valid specialization."
            ]);
            exit();
        }
        $allowedQualifications = [
    "MBBS",
    "MBBS, FCPS",
    "MBBS, MD",
    "MBBS, MS",
    "MBBS, MRCP"
];

    if (!in_array($qualification, $allowedQualifications)) {
    echo json_encode([
        "success" => false,
        "message" => "Please select a valid qualification."
    ]);
    exit();
}
        if (!preg_match('/^01[0-9]{9,}$/', $phone)) {
            echo json_encode([
                "success" => false,
                "message" => "Phone must start with 01 and contain at least 11 digits."
            ]);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Enter a valid email address."
            ]);
            exit();
        }

        if (strlen($password) < 6) {
            echo json_encode([
                "success" => false,
                "message" => "Password must be at least 6 characters."
            ]);
            exit();
        }

        if (doctorEmailExists($email)) {
            echo json_encode([
                "success" => false,
                "message" => "This doctor email already exists."
            ]);
            exit();
        }

        $result = addDoctor(
            $name,
            $specialization,
            $qualification,
            $experience,
            $consultationFee,
            $phone,
            $email,
            $password
        );

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result ? "Doctor added successfully." : "Doctor could not be added."
        ]);

        exit();
    }

    if ($doctorAction == "update") {

        $doctorId = $_POST["doctor_id"];
        $name = trim($_POST["name"]);
        $specialization = trim($_POST["specialization"]);
        $qualification = trim($_POST["qualification"]);
        $experience = trim($_POST["experience"]);
        $consultationFee = trim($_POST["consultation_fee"]);
        $phone = trim($_POST["phone"]);
        $email = trim($_POST["email"]);

        if ($doctorId == "" || $name == "" || $specialization == "" || $phone == "" || $email == "") {
            echo json_encode([
                "success" => false,
                "message" => "Name, specialization, phone and email are required."
            ]);
            exit();
        }

        if (!in_array($specialization, $allowedSpecializations)) {
            echo json_encode([
                "success" => false,
                "message" => "Please select a valid specialization."
            ]);
            exit();
        }
        $allowedQualifications = [
    "MBBS",
    "MBBS, FCPS",
    "MBBS, MD",
    "MBBS, MS",
    "MBBS, MRCP"
];

if (!in_array($qualification, $allowedQualifications)) {
    echo json_encode([
        "success" => false,
        "message" => "Please select a valid qualification."
    ]);
    exit();
}

        if (!preg_match('/^01[0-9]{9,}$/', $phone)) {
            echo json_encode([
                "success" => false,
                "message" => "Phone must start with 01 and contain at least 11 digits."
            ]);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Enter a valid email address."
            ]);
            exit();
        }

        if (doctorEmailExists($email, $doctorId)) {
            echo json_encode([
                "success" => false,
                "message" => "This doctor email already exists."
            ]);
            exit();
        }

        $result = updateDoctor(
            $doctorId,
            $name,
            $specialization,
            $qualification,
            $experience,
            $consultationFee,
            $phone,
            $email
        );

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result ? "Doctor updated successfully." : "Doctor could not be updated."
        ]);
        
        exit();
    }

    if ($doctorAction == "delete") {

        $doctorId = $_POST["doctor_id"];

        $result = deleteDoctor($doctorId);

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result ? "Doctor deleted successfully." : "Doctor could not be deleted."
        ]);

        exit();
    }
}

if (isset($_POST["patient_action"])) {
    header("Content-Type: application/json");

    $patientAction = $_POST["patient_action"];

    $allowedGenders = [
        "Male",
        "Female",
        "Other"
    ];

    $allowedBloodGroups = [
        "A+",
        "A-",
        "B+",
        "B-",
        "AB+",
        "AB-",
        "O+",
        "O-"
    ];

    if ($patientAction == "add") {

        $name = trim($_POST["name"]);
        $dateOfBirth = trim($_POST["date_of_birth"]);
        $gender = trim($_POST["gender"]);
        $phone = trim($_POST["phone"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $address = trim($_POST["address"]);
        $bloodGroup = trim($_POST["blood_group"]);

        if ($name == "") {
            echo json_encode([
                "success" => false,
                "message" => "Name is required."
            ]);
            exit();
        }

        if ($dateOfBirth == "") {
            echo json_encode([
                "success" => false,
                "message" => "Date of birth is required."
            ]);
            exit();
        }

        if ($dateOfBirth > date("Y-m-d")) {
            echo json_encode([
                "success" => false,
                "message" => "Date of birth cannot be in the future."
            ]);
            exit();
        }

        if ($gender == "") {
            echo json_encode([
                "success" => false,
                "message" => "Gender is required."
            ]);
            exit();
        }

        if (!in_array($gender, $allowedGenders)) {
            echo json_encode([
                "success" => false,
                "message" => "Please select a valid gender."
            ]);
            exit();
        }

        if ($phone == "") {
            echo json_encode([
                "success" => false,
                "message" => "Phone is required."
            ]);
            exit();
        }

        if (!preg_match('/^01[0-9]{9,}$/', $phone)) {
            echo json_encode([
                "success" => false,
                "message" => "Phone must start with 01 and contain at least 11 digits."
            ]);
            exit();
        }

        if ($email == "") {
            echo json_encode([
                "success" => false,
                "message" => "Email is required."
            ]);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
            !preg_match('/\.[A-Za-z]{2,}$/', $email)) {

            echo json_encode([
                "success" => false,
                "message" => "Enter a valid email address."
            ]);
            exit();
        }

        if ($password == "") {
            echo json_encode([
                "success" => false,
                "message" => "Password is required."
            ]);
            exit();
        }

        if (strlen($password) < 6) {
            echo json_encode([
                "success" => false,
                "message" => "Password must be at least 6 characters."
            ]);
            exit();
        }

        if ($address == "") {
            echo json_encode([
                "success" => false,
                "message" => "Address is required."
            ]);
            exit();
        }

        if ($bloodGroup == "") {
            echo json_encode([
                "success" => false,
                "message" => "Blood group is required."
            ]);
            exit();
        }

        if (!in_array($bloodGroup, $allowedBloodGroups)) {
            echo json_encode([
                "success" => false,
                "message" => "Please select a valid blood group."
            ]);
            exit();
        }

        if (patientEmailExists($email)) {
            echo json_encode([
                "success" => false,
                "message" => "This patient email already exists."
            ]);
            exit();
        }

        $result = addPatient(
            $name,
            $dateOfBirth,
            $gender,
            $phone,
            $email,
            $password,
            $address,
            $bloodGroup
        );

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result
                ? "Patient added successfully."
                : "Patient could not be added."
        ]);

        exit();
    }

    if ($patientAction == "update") {

        $patientId = $_POST["patient_id"];
        $name = trim($_POST["name"]);
        $dateOfBirth = trim($_POST["date_of_birth"]);
        $gender = trim($_POST["gender"]);
        $phone = trim($_POST["phone"]);
        $email = trim($_POST["email"]);
        $address = trim($_POST["address"]);
        $bloodGroup = trim($_POST["blood_group"]);

        if ($patientId == "") {
            echo json_encode([
                "success" => false,
                "message" => "Patient ID is required."
            ]);
            exit();
        }

        if ($name == "") {
            echo json_encode([
                "success" => false,
                "message" => "Name is required."
            ]);
            exit();
        }

        if ($dateOfBirth == "") {
            echo json_encode([
                "success" => false,
                "message" => "Date of birth is required."
            ]);
            exit();
        }

        if ($dateOfBirth > date("Y-m-d")) {
            echo json_encode([
                "success" => false,
                "message" => "Date of birth cannot be in the future."
            ]);
            exit();
        }

        if (!in_array($gender, $allowedGenders)) {
            echo json_encode([
                "success" => false,
                "message" => "Please select a valid gender."
            ]);
            exit();
        }

        if ($phone == "") {
            echo json_encode([
                "success" => false,
                "message" => "Phone is required."
            ]);
            exit();
        }

        if (!preg_match('/^01[0-9]{9,}$/', $phone)) {
            echo json_encode([
                "success" => false,
                "message" => "Phone must start with 01 and contain at least 11 digits."
            ]);
            exit();
        }

        if ($email == "") {
            echo json_encode([
                "success" => false,
                "message" => "Email is required."
            ]);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
            !preg_match('/\.[A-Za-z]{2,}$/', $email)) {

            echo json_encode([
                "success" => false,
                "message" => "Enter a valid email address."
            ]);
            exit();
        }

        if ($address == "") {
            echo json_encode([
                "success" => false,
                "message" => "Address is required."
            ]);
            exit();
        }

        if (!in_array($bloodGroup, $allowedBloodGroups)) {
            echo json_encode([
                "success" => false,
                "message" => "Please select a valid blood group."
            ]);
            exit();
        }

        if (patientEmailExists($email, $patientId)) {
            echo json_encode([
                "success" => false,
                "message" => "This patient email already exists."
            ]);
            exit();
        }

        $result = updatePatient(
            $patientId,
            $name,
            $dateOfBirth,
            $gender,
            $phone,
            $email,
            $address,
            $bloodGroup
        );

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result
                ? "Patient updated successfully."
                : "Patient could not be updated."
        ]);

        exit();
    }

    if ($patientAction == "delete") {

        $patientId = $_POST["patient_id"];

        $result = deletePatient($patientId);

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result
                ? "Patient deleted successfully."
                : "Patient could not be deleted."
        ]);

        exit();
    }
}


if (isset($_POST["appointment_action"])) {
    header("Content-Type: application/json");

    $appointmentAction = $_POST["appointment_action"];

    if ($appointmentAction == "updateStatus") {
        $appointmentId = $_POST["appointment_id"];
        $status = $_POST["status"];

        $allowedStatuses = ["Pending", "Approved", "Rejected", "Completed", "Cancelled"];

        if (!in_array($status, $allowedStatuses)) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid appointment status."
            ]);
            exit();
        }

        $result = updateAppointmentStatus($appointmentId, $status);

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result ? "Appointment status updated." : "Appointment could not be updated."
        ]);

        exit();
    }

    if ($appointmentAction == "delete") {
        $appointmentId = $_POST["appointment_id"];

        $result = deleteAppointment($appointmentId);

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result ? "Appointment deleted successfully." : "Appointment could not be deleted."
        ]);

        exit();
    }
}


if (isset($_POST["ward_action"])) {
    header("Content-Type: application/json");

    $wardAction = $_POST["ward_action"];

    if ($wardAction == "add") {
        $wardName = trim($_POST["ward_name"]);
        $bedNumber = trim($_POST["bed_number"]);
        $bedType = trim($_POST["bed_type"]);

        if ($wardName == "" || $bedNumber == "" || $bedType == "") {
            echo json_encode([
                "success" => false,
                "message" => "Ward name, bed number and bed type are required."
            ]);
            exit();
        }

        $result = addWardBed($wardName, $bedNumber, $bedType);

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result ? "Ward and bed added successfully." : "Ward and bed could not be added."
        ]);

        exit();
    }

    if ($wardAction == "status") {
        $wardBedId = $_POST["ward_bed_id"];
        $status = $_POST["bed_status"];

        if ($status != "Available" && $status != "Occupied" && $status != "Maintenance") {
            echo json_encode([
                "success" => false,
                "message" => "Invalid bed status."
            ]);
            exit();
        }

        $result = updateBedStatus($wardBedId, $status);

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result ? "Bed status updated." : "Bed status could not be updated."
        ]);

        exit();
    }

    if ($wardAction == "delete") {
        $wardBedId = $_POST["ward_bed_id"];

        $result = deleteWardBed($wardBedId);

        echo json_encode([
            "success" => $result ? true : false,
            "message" => $result ? "Ward and bed deleted successfully." : "Ward and bed could not be deleted."
        ]);

        exit();
    }
}
if (isset($_POST["profile_action"]) && $_POST["profile_action"] == "update") {

    header("Content-Type: application/json");

    $name = trim($_POST["name"]);
    $age = trim($_POST["age"]);
    $phone = trim($_POST["phone"]);

    if ($name == "" || $age == "" || $phone == "") {
        echo json_encode([
            "success" => false,
            "message" => "Name, age and phone are required."
        ]);
        exit();
    }

    if (!ctype_digit($age) || $age < 1 || $age > 120) {
        echo json_encode([
            "success" => false,
            "message" => "Enter a valid age."
        ]);
        exit();
    }

    if (!preg_match('/^01[0-9]{9,}$/', $phone)) {
        echo json_encode([
            "success" => false,
            "message" => "Phone must start with 01 and contain at least 11 digits."
        ]);
        exit();
    }

    $result = updateAdminProfile(
        $_SESSION["user_id"],
        $name,
        $age,
        $phone
    );

    if ($result) {
        $_SESSION["name"] = $name;
    }

    echo json_encode([
        "success" => $result,
        "message" => $result
            ? "Profile updated successfully."
            : "Profile could not be updated."
    ]);

    exit();
}
if (isset($_POST["password_action"])) {

    header("Content-Type: application/json");

    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($currentPassword == "" || $newPassword == "" || $confirmPassword == "") {
        echo json_encode([
            "success" => false,
            "message" => "All password fields are required."
        ]);
        exit();
    }

    if (strlen($newPassword) < 6) {
        echo json_encode([
            "success" => false,
            "message" => "New password must be at least 6 characters."
        ]);
        exit();
    }

    if ($newPassword != $confirmPassword) {
        echo json_encode([
            "success" => false,
            "message" => "Passwords do not match."
        ]);
        exit();
    }

    $admin = getAdminById($_SESSION["user_id"]);

    if (!$admin || !password_verify($currentPassword, $admin["password"])) {
        echo json_encode([
            "success" => false,
            "message" => "Current password is incorrect."
        ]);
        exit();
    }

    $result = changeAdminPassword($_SESSION["user_id"], $newPassword);

    echo json_encode([
        "success" => $result,
        "message" => $result
            ? "Password changed successfully."
            : "Password could not be changed."
    ]);

    exit();
}
$totalPatients = getTotalPatients();
$totalDoctors = getTotalDoctors();
$todayAppointments = getTodayAppointments();
$monthlyAppointments = getMonthlyAppointments();
$weeklyAppointments = getWeeklyAppointments();

include __DIR__ . "/../view/AdminView.php";

?>
