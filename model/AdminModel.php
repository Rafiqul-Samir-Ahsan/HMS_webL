<?php

require_once __DIR__ . "/db.php";



function getTotalPatients()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM patient";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        throw new RuntimeException("Unable to prepare the dashboard query.");
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            throw new RuntimeException("Unable to execute the dashboard query.");
        }

        $result = mysqli_stmt_get_result($stmt);

        if ($result === false) {
            throw new RuntimeException("Unable to read the dashboard query result.");
        }
        $row = mysqli_fetch_assoc($result);

        mysqli_free_result($result);

        return $row["total"];
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function getTotalDoctors()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM doctor";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        throw new RuntimeException("Unable to prepare the dashboard query.");
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            throw new RuntimeException("Unable to execute the dashboard query.");
        }

        $result = mysqli_stmt_get_result($stmt);

        if ($result === false) {
            throw new RuntimeException("Unable to read the dashboard query result.");
        }
        $row = mysqli_fetch_assoc($result);

        mysqli_free_result($result);

        return $row["total"];
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function getTodayAppointments()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM appointment
            WHERE appointment_date = CURDATE()";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        throw new RuntimeException("Unable to prepare the dashboard query.");
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            throw new RuntimeException("Unable to execute the dashboard query.");
        }

        $result = mysqli_stmt_get_result($stmt);

        if ($result === false) {
            throw new RuntimeException("Unable to read the dashboard query result.");
        }
        $row = mysqli_fetch_assoc($result);

        mysqli_free_result($result);

        return $row["total"];
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function getMonthlyAppointments()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM appointment
            WHERE MONTH(appointment_date) = MONTH(CURDATE())
            AND YEAR(appointment_date) = YEAR(CURDATE())";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        throw new RuntimeException("Unable to prepare the dashboard query.");
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            throw new RuntimeException("Unable to execute the dashboard query.");
        }

        $result = mysqli_stmt_get_result($stmt);

        if ($result === false) {
            throw new RuntimeException("Unable to read the dashboard query result.");
        }
        $row = mysqli_fetch_assoc($result);

        mysqli_free_result($result);

        return $row["total"];
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function getWeeklyAppointments()
{
    global $conn;

    $days = [
        "Mon" => 0,
        "Tue" => 0,
        "Wed" => 0,
        "Thu" => 0,
        "Fri" => 0,
        "Sat" => 0,
        "Sun" => 0
    ];

    $sql = "SELECT DAYOFWEEK(appointment_date) AS day_number,
                   COUNT(*) AS total
            FROM appointment
            WHERE YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)
            GROUP BY DAYOFWEEK(appointment_date)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        throw new RuntimeException("Unable to prepare the dashboard query.");
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            throw new RuntimeException("Unable to execute the dashboard query.");
        }

        $result = mysqli_stmt_get_result($stmt);

        if ($result === false) {
            throw new RuntimeException("Unable to read the dashboard query result.");
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $dayNumber = $row["day_number"];

            if ($dayNumber == 2) {
                $days["Mon"] = $row["total"];
            }
            elseif ($dayNumber == 3) {
                $days["Tue"] = $row["total"];
            }
            elseif ($dayNumber == 4) {
                $days["Wed"] = $row["total"];
            }
            elseif ($dayNumber == 5) {
                $days["Thu"] = $row["total"];
            }
            elseif ($dayNumber == 6) {
                $days["Fri"] = $row["total"];
            }
            elseif ($dayNumber == 7) {
                $days["Sat"] = $row["total"];
            }
            elseif ($dayNumber == 1) {
                $days["Sun"] = $row["total"];
            }
        }

        mysqli_free_result($result);

        return $days;
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function getAllDoctors()
{
    global $conn;

    $sql = "SELECT * FROM doctor ORDER BY doctor_id DESC";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            return false;
        }

        return mysqli_stmt_get_result($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function doctorEmailExists($email, $ignoreDoctorId = 0)
{
    global $conn;

    $ignoreDoctorId = (int)$ignoreDoctorId;

    if ($ignoreDoctorId > 0) {
        $sql = "SELECT doctor_id
                FROM doctor
                WHERE email=?
                AND doctor_id != ?";
    }
    else {
        $sql = "SELECT doctor_id
                FROM doctor
                WHERE email=?";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        throw new RuntimeException("Unable to prepare the email lookup.");
    }

    try {
        if ($ignoreDoctorId > 0) {
            $bound = mysqli_stmt_bind_param($stmt, "si", $email, $ignoreDoctorId);
        }
        else {
            $bound = mysqli_stmt_bind_param($stmt, "s", $email);
        }

        if (!$bound || !mysqli_stmt_execute($stmt)) {
            throw new RuntimeException("Unable to execute the email lookup.");
        }

        $result = mysqli_stmt_get_result($stmt);

        if ($result === false) {
            throw new RuntimeException("Unable to read the email lookup result.");
        }

        $exists = mysqli_num_rows($result) > 0;
        mysqli_free_result($result);

        return $exists;
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function addDoctor($name, $specialization, $qualification, $experience, $consultationFee, $phone, $email, $password)
{
    global $conn;

    $experience = (int)$experience;
    $consultationFee = (float)$consultationFee;
    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO doctor
            (name, specialization, qualification, experience, consultation_fee, phone, email, password)
            VALUES
            (?, ?, ?, ?,
             ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "sssidsss", $name, $specialization, $qualification, $experience, $consultationFee, $phone, $email, $password)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function updateDoctor($doctorId, $name, $specialization, $qualification, $experience, $consultationFee, $phone, $email)
{
    global $conn;

    $doctorId = (int)$doctorId;
    $experience = (int)$experience;
    $consultationFee = (float)$consultationFee;

    $sql = "UPDATE doctor
            SET name=?,
                specialization=?,
                qualification=?,
                experience=?,
                consultation_fee=?,
                phone=?,
                email=?
            WHERE doctor_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "sssidssi", $name, $specialization, $qualification, $experience, $consultationFee, $phone, $email, $doctorId)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function deleteDoctor($doctorId)
{
    global $conn;

    $doctorId = (int)$doctorId;

    $sql = "DELETE FROM doctor
            WHERE doctor_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "i", $doctorId)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function getAllPatients()
{
    global $conn;

    $sql = "SELECT * FROM patient ORDER BY patient_id DESC";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            return false;
        }

        return mysqli_stmt_get_result($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function patientEmailExists($email, $ignorePatientId = 0)
{
    global $conn;

    $ignorePatientId = (int)$ignorePatientId;

    if ($ignorePatientId > 0) {
        $sql = "SELECT patient_id
                FROM patient
                WHERE email=?
                AND patient_id != ?";
    }
    else {
        $sql = "SELECT patient_id
                FROM patient
                WHERE email=?";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        throw new RuntimeException("Unable to prepare the email lookup.");
    }

    try {
        if ($ignorePatientId > 0) {
            $bound = mysqli_stmt_bind_param($stmt, "si", $email, $ignorePatientId);
        }
        else {
            $bound = mysqli_stmt_bind_param($stmt, "s", $email);
        }

        if (!$bound || !mysqli_stmt_execute($stmt)) {
            throw new RuntimeException("Unable to execute the email lookup.");
        }

        $result = mysqli_stmt_get_result($stmt);

        if ($result === false) {
            throw new RuntimeException("Unable to read the email lookup result.");
        }

        $exists = mysqli_num_rows($result) > 0;
        mysqli_free_result($result);

        return $exists;
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function addPatient($name, $dateOfBirth, $gender, $phone, $email, $password, $address, $bloodGroup)
{
    global $conn;

    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO patient
            (name, date_of_birth, gender, phone, email, password, address, blood_group)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "ssssssss", $name, $dateOfBirth, $gender, $phone, $email, $password, $address, $bloodGroup)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function updatePatient($patientId, $name, $dateOfBirth, $gender, $phone, $email, $address, $bloodGroup)
{
    global $conn;

    $patientId = (int)$patientId;

    $sql = "UPDATE patient
            SET name=?,
                date_of_birth=?,
                gender=?,
                phone=?,
                email=?,
                address=?,
                blood_group=?
            WHERE patient_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "sssssssi", $name, $dateOfBirth, $gender, $phone, $email, $address, $bloodGroup, $patientId)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function deletePatient($patientId)
{
    global $conn;

    $patientId = (int)$patientId;

    $sql = "DELETE FROM patient
            WHERE patient_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "i", $patientId)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}


function getAllAppointments()
{
    global $conn;

    $sql = "SELECT appointment.appointment_id,
                   appointment.appointment_date,
                   appointment.appointment_time,
                   appointment.reason,
                   appointment.status,
                   patient.name AS patient_name,
                   doctor.name AS doctor_name
            FROM appointment
            JOIN patient ON appointment.patient_id = patient.patient_id
            JOIN doctor ON appointment.doctor_id = doctor.doctor_id
            ORDER BY appointment.appointment_date DESC,
                     appointment.appointment_time DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            return false;
        }

        return mysqli_stmt_get_result($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function updateAppointmentStatus($appointmentId, $status)
{
    global $conn;

    $appointmentId = (int)$appointmentId;

    $sql = "UPDATE appointment
            SET status=?
            WHERE appointment_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "si", $status, $appointmentId)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function deleteAppointment($appointmentId)
{
    global $conn;

    $appointmentId = (int)$appointmentId;

    $sql = "DELETE FROM appointment
            WHERE appointment_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "i", $appointmentId)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}


function getAllWardBeds()
{
    global $conn;

    $sql = "SELECT * FROM ward_bed ORDER BY ward_bed_id DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_execute($stmt)) {
            return false;
        }

        return mysqli_stmt_get_result($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function addWardBed($wardName, $bedNumber, $bedType)
{
    global $conn;


    $sql = "INSERT INTO ward_bed
            (ward_name, bed_number, bed_type, bed_status)
            VALUES
            (?, ?, ?, 'Available')";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "sss", $wardName, $bedNumber, $bedType)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function updateBedStatus($wardBedId, $status)
{
    global $conn;

    $wardBedId = (int)$wardBedId;

    $sql = "UPDATE ward_bed
            SET bed_status=?
            WHERE ward_bed_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "si", $status, $wardBedId)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}

function deleteWardBed($wardBedId)
{
    global $conn;

    $wardBedId = (int)$wardBedId;

    $sql = "DELETE FROM ward_bed
            WHERE ward_bed_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        return false;
    }

    try {
        if (!mysqli_stmt_bind_param($stmt, "i", $wardBedId)) {
            return false;
        }

        return mysqli_stmt_execute($stmt);
    }
    finally {
        mysqli_stmt_close($stmt);
    }
}