<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/prescription_model.php';
require_once __DIR__ . '/../../model/appointment_model.php';
require_role('doctor');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Add Prescription';
$assetPath = '../../';
$doctorId = (int) $_SESSION['user_id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientId = (int) $_POST['patient_id'];
    $appointmentId = !empty($_POST['appointment_id']) ? (int) $_POST['appointment_id'] : 'NULL';
    $medicationDetails = clean($conn, $_POST['medication_details']);
    $notes = clean($conn, $_POST['notes']);
    $date = date('Y-m-d');

    if ($patientId <= 0 || $medicationDetails === '') {
        $errors[] = "Please select a patient and enter medication details.";
    } else {
        if (insert_prescription($conn, $appointmentId, $patientId, $doctorId, $date, $medicationDetails, $notes)) {
            set_flash('success', 'Prescription created.');
            redirect('add_prescription.php');
        } else {
            $errors[] = "Could not save prescription.";
        }
    }
}

$myPatients = get_patient_options_for_doctor($conn, $doctorId);

$preselect = isset($_GET['patient_id']) ? (int) $_GET['patient_id'] : 0;

$recentPrescriptions = get_recent_prescriptions_for_doctor($conn, $doctorId, 10);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_doctor.php';
include __DIR__ . '/../../view/doctor/add_prescription_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
