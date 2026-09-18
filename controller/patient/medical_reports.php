<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/medical_report_model.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Medical Reports';
$assetPath = '../../';
$patientId = (int) $_SESSION['user_id'];

$reports = get_reports_for_patient($conn, $patientId);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/medical_reports_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
