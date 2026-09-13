<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/appointment_model.php';

header('Content-Type: application/json');

if (!is_logged_in() || $_SESSION['role'] !== 'doctor') {
    json_response(['success' => false, 'message' => 'Not authorized.']);
}

$doctorId = (int) $_SESSION['user_id'];
$appointmentId = isset($_POST['appointment_id']) ? (int) $_POST['appointment_id'] : 0;
$newStatus = isset($_POST['status']) ? $_POST['status'] : '';

$allowed = ['Approved', 'Rejected', 'Completed', 'Cancelled'];
if ($appointmentId <= 0 || !in_array($newStatus, $allowed, true)) {
    json_response(['success' => false, 'message' => 'Invalid request.']);
}

$safeStatus = clean($conn, $newStatus);

if (update_appointment_status_by_doctor($conn, $appointmentId, $doctorId, $safeStatus)) {
    json_response([
        'success' => true,
        'status' => $newStatus,
        'message' => "Appointment marked as $newStatus.",
    ]);
} else {
    json_response(['success' => false, 'message' => 'Could not update the appointment.']);
}
