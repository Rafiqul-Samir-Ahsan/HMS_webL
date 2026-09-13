<?php
// =====================================================
// Prescription model
// Same mysqli queries used across the original pages,
// just grouped here as functions.
// =====================================================

function insert_prescription($conn, $appointmentId, $patientId, $doctorId, $date, $medicationDetails, $notes) {
    $sql = "INSERT INTO prescription (appointment_id, patient_id, doctor_id, prescription_date, medication_details, notes)
            VALUES ($appointmentId, $patientId, $doctorId, '$date', '$medicationDetails', '$notes')";
    return mysqli_query($conn, $sql);
}

function get_recent_prescriptions_for_doctor($conn, $doctorId, $limit = 10) {
    $limit = (int) $limit;
    return mysqli_query($conn, "SELECT pr.*, p.name AS patient_name FROM prescription pr
        JOIN patient p ON pr.patient_id = p.patient_id
        WHERE pr.doctor_id = $doctorId ORDER BY pr.prescription_date DESC LIMIT $limit");
}

function get_prescriptions_for_patient($conn, $patientId) {
    return mysqli_query($conn, "SELECT pr.*, d.name AS doctor_name, d.specialization FROM prescription pr
        JOIN doctor d ON pr.doctor_id = d.doctor_id
        WHERE pr.patient_id = $patientId ORDER BY pr.prescription_date DESC");
}

function count_prescriptions_this_month_for_doctor($conn, $doctorId) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM prescription WHERE doctor_id=$doctorId AND MONTH(prescription_date)=MONTH(CURDATE()) AND YEAR(prescription_date)=YEAR(CURDATE())"))['c'];
}

function count_prescriptions_for_patient($conn, $patientId) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM prescription WHERE patient_id=$patientId"))['c'];
}
