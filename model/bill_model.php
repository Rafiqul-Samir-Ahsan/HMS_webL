<?php
// =====================================================
// Bill model
// Same mysqli queries used across the original pages,
// just grouped here as functions.
// =====================================================

function get_bills_for_patient($conn, $patientId) {
    return mysqli_query($conn, "SELECT * FROM bill WHERE patient_id = $patientId ORDER BY bill_date DESC");
}

function get_recent_bills_for_patient($conn, $patientId, $limit = 5) {
    $limit = (int) $limit;
    return mysqli_query($conn, "SELECT * FROM bill WHERE patient_id=$patientId ORDER BY bill_date DESC LIMIT $limit");
}

function get_outstanding_balance($conn, $patientId) {
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(amount - discount - paid_amount),0) c FROM bill WHERE patient_id=$patientId AND payment_status != 'Paid'"))['c'];
}
