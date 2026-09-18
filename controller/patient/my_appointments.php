<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/appointment_model.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'My Appointments';
$assetPath = '../../';
$patientId = (int) $_SESSION['user_id'];

$appointments = get_appointments_for_patient($conn, $patientId);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/my_appointments_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
