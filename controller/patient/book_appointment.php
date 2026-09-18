<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/appointment_model.php';
require_once __DIR__ . '/../../model/doctor_model.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Book Appointment';
$assetPath = '../../';
$patientId = (int) $_SESSION['user_id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorId = (int) $_POST['doctor_id'];
    $date = clean($conn, $_POST['appointment_date']);
    $time = clean($conn, $_POST['appointment_time']);
    $reason = clean($conn, $_POST['reason']);

    if ($doctorId <= 0 || $date === '' || $time === '' || $reason === '') {
        $errors[] = "Please fill in all fields.";
    } elseif (strtotime($date) < strtotime(date('Y-m-d'))) {
        $errors[] = "Appointment date cannot be in the past.";
    } else {
        if (insert_appointment($conn, $patientId, $doctorId, $date, $time, $reason)) {
            set_flash('success', 'Appointment request submitted. You will be notified once it is confirmed.');
            redirect('my_appointments.php');
        } else {
            $errors[] = "Could not book appointment. Please try again.";
        }
    }
}

$doctors = get_all_doctors($conn);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/book_appointment_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
