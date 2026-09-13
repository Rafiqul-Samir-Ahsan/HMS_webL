<?php
// =====================================================
// Appointment model
// Same mysqli queries used across the original pages,
// just grouped here as functions.
// =====================================================

function count_appointments_today($conn) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM appointment WHERE appointment_date = CURDATE()"))['c'];
}

function count_appointments_this_month($conn) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM appointment WHERE MONTH(appointment_date) = MONTH(CURDATE()) AND YEAR(appointment_date) = YEAR(CURDATE())"))['c'];
}

function count_appointments_on_date($conn, $date) {
    return (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM appointment WHERE appointment_date = '$date'"))['c'];
}

function insert_appointment($conn, $patientId, $doctorId, $date, $time, $reason) {
    $sql = "INSERT INTO appointment (patient_id, doctor_id, appointment_date, appointment_time, status, reason)
            VALUES ($patientId, $doctorId, '$date', '$time', 'Pending', '$reason')";
    return mysqli_query($conn, $sql);
}

// ---------------- Doctor-side ----------------

function get_appointments_for_doctor($conn, $doctorId, $statusFilter = 'All') {
    $where = "WHERE a.doctor_id = $doctorId";
    if ($statusFilter !== 'All') {
        $where .= " AND a.status = '$statusFilter'";
    }
    return mysqli_query($conn, "SELECT a.*, p.name AS patient_name, p.phone AS patient_phone
        FROM appointment a JOIN patient p ON a.patient_id = p.patient_id
        $where ORDER BY a.appointment_date DESC, a.appointment_time DESC");
}

function get_today_appointments_for_doctor($conn, $doctorId) {
    return mysqli_query($conn, "SELECT a.*, p.name AS patient_name FROM appointment a
        JOIN patient p ON a.patient_id = p.patient_id
        WHERE a.doctor_id=$doctorId AND a.appointment_date = CURDATE()
        ORDER BY a.appointment_time");
}

function get_recent_patients_for_doctor($conn, $doctorId, $limit = 5) {
    $limit = (int) $limit;
    return mysqli_query($conn, "SELECT DISTINCT p.patient_id, p.name, a.reason, a.appointment_date
        FROM appointment a JOIN patient p ON a.patient_id = p.patient_id
        WHERE a.doctor_id=$doctorId ORDER BY a.appointment_date DESC LIMIT $limit");
}

function get_distinct_patients_for_doctor($conn, $doctorId) {
    return mysqli_query($conn, "SELECT DISTINCT p.* FROM patient p
        JOIN appointment a ON a.patient_id = p.patient_id
        WHERE a.doctor_id = $doctorId ORDER BY p.name");
}

// Narrower version (just id + name) used by the patient dropdowns
// on the add-prescription and upload-report pages, exactly as in the original.
function get_patient_options_for_doctor($conn, $doctorId) {
    return mysqli_query($conn, "SELECT DISTINCT p.patient_id, p.name FROM patient p
        JOIN appointment a ON a.patient_id = p.patient_id
        WHERE a.doctor_id = $doctorId ORDER BY p.name");
}

function get_appointment_history($conn, $patientId, $doctorId) {
    return mysqli_query($conn, "SELECT * FROM appointment WHERE patient_id = $patientId AND doctor_id = $doctorId ORDER BY appointment_date DESC");
}

function update_appointment_status_by_doctor($conn, $appointmentId, $doctorId, $status) {
    $sql = "UPDATE appointment SET status = '$status' WHERE appointment_id = $appointmentId AND doctor_id = $doctorId";
    mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn) > 0;
}

function count_today_for_doctor($conn, $doctorId) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM appointment WHERE doctor_id=$doctorId AND appointment_date=CURDATE()"))['c'];
}

function count_distinct_patients_for_doctor($conn, $doctorId) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT patient_id) c FROM appointment WHERE doctor_id=$doctorId"))['c'];
}

function count_pending_for_doctor($conn, $doctorId) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM appointment WHERE doctor_id=$doctorId AND status='Pending'"))['c'];
}

// ---------------- Patient-side ----------------

function get_appointments_for_patient($conn, $patientId) {
    return mysqli_query($conn, "SELECT a.*, d.name AS doctor_name, d.specialization FROM appointment a
        JOIN doctor d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id=$patientId ORDER BY a.appointment_date DESC, a.appointment_time DESC");
}

function get_upcoming_appointments_for_patient($conn, $patientId, $limit = 5) {
    $limit = (int) $limit;
    return mysqli_query($conn, "SELECT a.*, d.name AS doctor_name, d.specialization FROM appointment a
        JOIN doctor d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id=$patientId AND a.appointment_date >= CURDATE()
        ORDER BY a.appointment_date LIMIT $limit");
}

function get_next_appointment_for_patient($conn, $patientId) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT a.*, d.name AS doctor_name FROM appointment a
        JOIN doctor d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id=$patientId AND a.appointment_date >= CURDATE() AND a.status IN ('Pending','Approved')
        ORDER BY a.appointment_date, a.appointment_time LIMIT 1"));
}

function count_upcoming_for_patient($conn, $patientId) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM appointment WHERE patient_id=$patientId AND appointment_date >= CURDATE() AND status IN ('Pending','Approved')"))['c'];
}

function cancel_appointment_by_patient($conn, $appointmentId, $patientId) {
    $sql = "UPDATE appointment SET status = 'Cancelled'
            WHERE appointment_id = $appointmentId AND patient_id = $patientId AND status = 'Pending'";
    mysqli_query($conn, $sql);
    return mysqli_affected_rows($conn) > 0;
}
