<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/appointment_model.php';

header('Content-Type: application/json');

if (!is_logged_in() || $_SESSION['role'] !== 'patient') {
    json_response(['success' => false, 'message' => 'Not authorized.']);
}

$patientId = (int) $_SESSION['user_id'];
$appointmentId = isset($_POST['appointment_id']) ? (int) $_POST['appointment_id'] : 0;

if ($appointmentId <= 0) {
    json_response(['success' => false, 'message' => 'Invalid appointment.']);
}

if (cancel_appointment_by_patient($conn, $appointmentId, $patientId)) {
    json_response(['success' => true, 'message' => 'Appointment cancelled.']);
} else {
    json_response(['success' => false, 'message' => 'Could not cancel — it may no longer be pending.']);
}
