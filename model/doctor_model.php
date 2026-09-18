<?php
// =====================================================
// Doctor model
// Same mysqli queries used across the original pages,
// just grouped here as functions.
// =====================================================

function get_all_doctors($conn) {
    return mysqli_query($conn, "SELECT * FROM doctor ORDER BY name");
}

function get_doctor($conn, $id) {
    $id = (int) $id;
    $result = mysqli_query($conn, "SELECT * FROM doctor WHERE doctor_id = $id");
    return mysqli_fetch_assoc($result);
}

function insert_doctor($conn, $name, $specialization, $qualification, $experience, $fee, $phone, $email, $hashedPassword) {
    $sql = "INSERT INTO doctor (name, specialization, qualification, experience, consultation_fee, phone, email, password)
            VALUES ('$name', '$specialization', '$qualification', $experience, $fee, '$phone', '$email', '$hashedPassword')";
    return mysqli_query($conn, $sql);
}

function update_doctor($conn, $doctorId, $name, $specialization, $qualification, $experience, $fee, $phone, $email, $hashedPassword = null) {
    $doctorId = (int) $doctorId;
    $sql = "UPDATE doctor SET name='$name', specialization='$specialization', qualification='$qualification',
            experience=$experience, consultation_fee=$fee, phone='$phone', email='$email' WHERE doctor_id=$doctorId";
    if ($hashedPassword !== null) {
        $sql = "UPDATE doctor SET name='$name', specialization='$specialization', qualification='$qualification',
                experience=$experience, consultation_fee=$fee, phone='$phone', email='$email', password='$hashedPassword' WHERE doctor_id=$doctorId";
    }
    return mysqli_query($conn, $sql);
}

function update_doctor_password($conn, $id, $hashed) {
    $id = (int) $id;
    return mysqli_query($conn, "UPDATE doctor SET password='$hashed' WHERE doctor_id=$id");
}

function delete_doctor($conn, $id) {
    $id = (int) $id;
    return mysqli_query($conn, "DELETE FROM doctor WHERE doctor_id = $id");
}

function count_doctors($conn) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM doctor"))['c'];
}
