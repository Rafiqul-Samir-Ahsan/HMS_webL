<?php
// =====================================================
// Patient model
// Same mysqli queries used across the original pages,
// just grouped here as functions.
// =====================================================

function get_all_patients($conn, $search = '') {
    $where = $search !== '' ? "WHERE name LIKE '%$search%' OR email LIKE '%$search%'" : '';
    return mysqli_query($conn, "SELECT * FROM patient $where ORDER BY name");
}

function get_patient($conn, $id) {
    $id = (int) $id;
    $result = mysqli_query($conn, "SELECT * FROM patient WHERE patient_id = $id");
    return mysqli_fetch_assoc($result);
}

function find_patient_by_email($conn, $email) {
    return mysqli_query($conn, "SELECT patient_id FROM patient WHERE email = '$email'");
}

function insert_patient($conn, $name, $dob, $gender, $phone, $email, $hashedPassword, $address, $bloodGroup) {
    $sql = "INSERT INTO patient (name, date_of_birth, gender, phone, email, password, address, blood_group)
            VALUES ('$name', '$dob', '$gender', '$phone', '$email', '$hashedPassword', '$address', '$bloodGroup')";
    return mysqli_query($conn, $sql);
}

function update_patient($conn, $patientId, $name, $dob, $gender, $phone, $email, $address, $bloodGroup) {
    $patientId = (int) $patientId;
    $sql = "UPDATE patient SET name='$name', date_of_birth='$dob', gender='$gender', phone='$phone',
            email='$email', address='$address', blood_group='$bloodGroup' WHERE patient_id=$patientId";
    return mysqli_query($conn, $sql);
}

function update_patient_password($conn, $id, $hashed) {
    $id = (int) $id;
    return mysqli_query($conn, "UPDATE patient SET password='$hashed' WHERE patient_id=$id");
}

function delete_patient($conn, $id) {
    $id = (int) $id;
    return mysqli_query($conn, "DELETE FROM patient WHERE patient_id = $id");
}

function count_patients($conn) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM patient"))['c'];
}
