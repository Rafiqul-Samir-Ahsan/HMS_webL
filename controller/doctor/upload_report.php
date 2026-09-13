<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/medical_report_model.php';
require_once __DIR__ . '/../../model/appointment_model.php';
require_role('doctor');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Upload Medical Report';
$assetPath = '../../';
$doctorId = (int) $_SESSION['user_id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientId = (int) $_POST['patient_id'];
    $appointmentId = !empty($_POST['appointment_id']) ? (int) $_POST['appointment_id'] : 'NULL';
    $reportType = clean($conn, $_POST['report_type']);
    $notes = clean($conn, $_POST['notes']);
    $date = date('Y-m-d');
    $filePath = '';

    if ($patientId <= 0 || $reportType === '') {
        $errors[] = "Please select a patient and enter a report type.";
    }

    if (empty($errors) && isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../view/uploads/reports/';
        $ext = pathinfo($_FILES['report_file']['name'], PATHINFO_EXTENSION);
        $safeName = 'report_' . time() . '_' . $patientId . '.' . $ext;
        if (move_uploaded_file($_FILES['report_file']['tmp_name'], $uploadDir . $safeName)) {
            $filePath = 'view/uploads/reports/' . $safeName;
        } else {
            $errors[] = "File upload failed.";
        }
    }

    if (empty($errors)) {
        $safeFilePath = clean($conn, $filePath);
        if (insert_medical_report($conn, $appointmentId, $patientId, $doctorId, $reportType, $safeFilePath, $date, $notes)) {
            set_flash('success', 'Medical report uploaded.');
            redirect('upload_report.php');
        } else {
            $errors[] = "Could not save report.";
        }
    }
}

$myPatients = get_patient_options_for_doctor($conn, $doctorId);

$recentReports = get_recent_reports_for_doctor($conn, $doctorId, 10);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_doctor.php';
include __DIR__ . '/../../view/doctor/upload_report_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
