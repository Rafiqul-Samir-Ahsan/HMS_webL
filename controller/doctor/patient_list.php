<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/patient_model.php';
require_once __DIR__ . '/../../model/appointment_model.php';
require_role('doctor');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Patient List';
$assetPath = '../../';
$doctorId = (int) $_SESSION['user_id'];

$viewId = isset($_GET['view']) ? (int) $_GET['view'] : 0;

$patients = get_distinct_patients_for_doctor($conn, $doctorId);

$viewPatient = null;
$history = null;
if ($viewId > 0) {
    $viewPatient = get_patient($conn, $viewId);
    $history = get_appointment_history($conn, $viewId, $doctorId);
}

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_doctor.php';
include __DIR__ . '/../../view/doctor/patient_list_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
