<?php

require_once __DIR__ . "/db.php";

function getTotalPatients()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM patient";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row["total"];
}

function getTotalDoctors()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM doctor";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row["total"];
}

function getTodayAppointments()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM appointment
            WHERE appointment_date = CURDATE()";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row["total"];
}

function getMonthlyAppointments()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total
            FROM appointment
            WHERE MONTH(appointment_date) = MONTH(CURDATE())
            AND YEAR(appointment_date) = YEAR(CURDATE())";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row["total"];
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

    $result = mysqli_query($conn, $sql);

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

    return $days;
}

function getAllDoctors()
{
    global $conn;

    $sql = "SELECT * FROM doctor ORDER BY doctor_id DESC";
    return mysqli_query($conn, $sql);
}

function doctorEmailExists($email, $ignoreDoctorId = 0)
{
    global $conn;

    $email = mysqli_real_escape_string($conn, $email);
    $ignoreDoctorId = (int)$ignoreDoctorId;

    if ($ignoreDoctorId > 0) {
        $sql = "SELECT doctor_id
                FROM doctor
                WHERE email='$email'
                AND doctor_id != '$ignoreDoctorId'";
    }
    else {
        $sql = "SELECT doctor_id
                FROM doctor
                WHERE email='$email'";
    }

    $result = mysqli_query($conn, $sql);

    return mysqli_num_rows($result) > 0;
}

function addDoctor($name, $specialization, $qualification, $experience, $consultationFee, $phone, $email, $password)
{
    global $conn;

    $name = mysqli_real_escape_string($conn, $name);
    $specialization = mysqli_real_escape_string($conn, $specialization);
    $qualification = mysqli_real_escape_string($conn, $qualification);
    $experience = (int)$experience;
    $consultationFee = (float)$consultationFee;
    $phone = mysqli_real_escape_string($conn, $phone);
    $email = mysqli_real_escape_string($conn, $email);
    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO doctor
            (name, specialization, qualification, experience, consultation_fee, phone, email, password)
            VALUES
            ('$name', '$specialization', '$qualification', '$experience',
             '$consultationFee', '$phone', '$email', '$password')";

    return mysqli_query($conn, $sql);
}

function updateDoctor($doctorId, $name, $specialization, $qualification, $experience, $consultationFee, $phone, $email)
{
    global $conn;

    $doctorId = (int)$doctorId;
    $name = mysqli_real_escape_string($conn, $name);
    $specialization = mysqli_real_escape_string($conn, $specialization);
    $qualification = mysqli_real_escape_string($conn, $qualification);
    $experience = (int)$experience;
    $consultationFee = (float)$consultationFee;
    $phone = mysqli_real_escape_string($conn, $phone);
    $email = mysqli_real_escape_string($conn, $email);

    $sql = "UPDATE doctor
            SET name='$name',
                specialization='$specialization',
                qualification='$qualification',
                experience='$experience',
                consultation_fee='$consultationFee',
                phone='$phone',
                email='$email'
            WHERE doctor_id='$doctorId'";

    return mysqli_query($conn, $sql);
}

function deleteDoctor($doctorId)
{
    global $conn;

    $doctorId = (int)$doctorId;

    $sql = "DELETE FROM doctor
            WHERE doctor_id='$doctorId'";

    return mysqli_query($conn, $sql);
}

function getAllPatients()
{
    global $conn;

    $sql = "SELECT * FROM patient ORDER BY patient_id DESC";
    return mysqli_query($conn, $sql);
}

function patientEmailExists($email, $ignorePatientId = 0)
{
    global $conn;

    $email = mysqli_real_escape_string($conn, $email);
    $ignorePatientId = (int)$ignorePatientId;

    if ($ignorePatientId > 0) {
        $sql = "SELECT patient_id
                FROM patient
                WHERE email='$email'
                AND patient_id != '$ignorePatientId'";
    }
    else {
        $sql = "SELECT patient_id
                FROM patient
                WHERE email='$email'";
    }

    $result = mysqli_query($conn, $sql);

    return mysqli_num_rows($result) > 0;
}

function addPatient($name, $dateOfBirth, $gender, $phone, $email, $password, $address, $bloodGroup)
{
    global $conn;

    $name = mysqli_real_escape_string($conn, $name);
    $dateOfBirth = mysqli_real_escape_string($conn, $dateOfBirth);
    $gender = mysqli_real_escape_string($conn, $gender);
    $phone = mysqli_real_escape_string($conn, $phone);
    $email = mysqli_real_escape_string($conn, $email);
    $address = mysqli_real_escape_string($conn, $address);
    $bloodGroup = mysqli_real_escape_string($conn, $bloodGroup);
    $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO patient
            (name, date_of_birth, gender, phone, email, password, address, blood_group)
            VALUES
            ('$name', '$dateOfBirth', '$gender', '$phone', '$email', '$password', '$address', '$bloodGroup')";

    return mysqli_query($conn, $sql);
}

function updatePatient($patientId, $name, $dateOfBirth, $gender, $phone, $email, $address, $bloodGroup)
{
    global $conn;

    $patientId = (int)$patientId;
    $name = mysqli_real_escape_string($conn, $name);
    $dateOfBirth = mysqli_real_escape_string($conn, $dateOfBirth);
    $gender = mysqli_real_escape_string($conn, $gender);
    $phone = mysqli_real_escape_string($conn, $phone);
    $email = mysqli_real_escape_string($conn, $email);
    $address = mysqli_real_escape_string($conn, $address);
    $bloodGroup = mysqli_real_escape_string($conn, $bloodGroup);

    $sql = "UPDATE patient
            SET name='$name',
                date_of_birth='$dateOfBirth',
                gender='$gender',
                phone='$phone',
                email='$email',
                address='$address',
                blood_group='$bloodGroup'
            WHERE patient_id='$patientId'";

    return mysqli_query($conn, $sql);
}

function deletePatient($patientId)
{
    global $conn;

    $patientId = (int)$patientId;

    $sql = "DELETE FROM patient
            WHERE patient_id='$patientId'";

    return mysqli_query($conn, $sql);
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

    return mysqli_query($conn, $sql);
}

function updateAppointmentStatus($appointmentId, $status)
{
    global $conn;

    $appointmentId = (int)$appointmentId;
    $status = mysqli_real_escape_string($conn, $status);

    $sql = "UPDATE appointment
            SET status='$status'
            WHERE appointment_id='$appointmentId'";

    return mysqli_query($conn, $sql);
}

function deleteAppointment($appointmentId)
{
    global $conn;

    $appointmentId = (int)$appointmentId;

    $sql = "DELETE FROM appointment
            WHERE appointment_id='$appointmentId'";

    return mysqli_query($conn, $sql);
}


function getAllWardBeds()
{
    global $conn;

    $sql = "SELECT * FROM ward_bed ORDER BY ward_bed_id DESC";

    return mysqli_query($conn, $sql);
}

function addWardBed($wardName, $bedNumber, $bedType)
{
    global $conn;

    $wardName = mysqli_real_escape_string($conn, $wardName);
    $bedNumber = mysqli_real_escape_string($conn, $bedNumber);
    $bedType = mysqli_real_escape_string($conn, $bedType);

    $sql = "INSERT INTO ward_bed
            (ward_name, bed_number, bed_type, bed_status)
            VALUES
            ('$wardName', '$bedNumber', '$bedType', 'Available')";

    return mysqli_query($conn, $sql);
}

function updateBedStatus($wardBedId, $status)
{
    global $conn;

    $wardBedId = (int)$wardBedId;
    $status = mysqli_real_escape_string($conn, $status);

    $sql = "UPDATE ward_bed
            SET bed_status='$status'
            WHERE ward_bed_id='$wardBedId'";

    return mysqli_query($conn, $sql);
}

function deleteWardBed($wardBedId)
{
    global $conn;

    $wardBedId = (int)$wardBedId;

    $sql = "DELETE FROM ward_bed
            WHERE ward_bed_id='$wardBedId'";

    return mysqli_query($conn, $sql);
}

?>
