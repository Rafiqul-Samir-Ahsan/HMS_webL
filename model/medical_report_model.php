<?php
// =====================================================
// Medical report model
// Same mysqli queries used across the original pages,
// just grouped here as functions.
// =====================================================

function insert_medical_report($conn, $appointmentId, $patientId, $doctorId, $reportType, $filePath, $date, $notes) {
    $sql = "INSERT INTO medical_report (appointment_id, patient_id, doctor_id, report_type, file_path, report_date, notes)
            VALUES ($appointmentId, $patientId, $doctorId, '$reportType', '$filePath', '$date', '$notes')";
    return mysqli_query($conn, $sql);
}

function get_recent_reports_for_doctor($conn, $doctorId, $limit = 10) {
    $limit = (int) $limit;
    return mysqli_query($conn, "SELECT mr.*, p.name AS patient_name FROM medical_report mr
        JOIN patient p ON mr.patient_id = p.patient_id
        WHERE mr.doctor_id = $doctorId ORDER BY mr.report_date DESC LIMIT $limit");
}

function get_reports_for_patient($conn, $patientId) {
    return mysqli_query($conn, "SELECT mr.*, d.name AS doctor_name FROM medical_report mr
        JOIN doctor d ON mr.doctor_id = d.doctor_id
        WHERE mr.patient_id = $patientId ORDER BY mr.report_date DESC");
}
