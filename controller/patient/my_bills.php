<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/bill_model.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'My Bills';
$assetPath = '../../';
$patientId = (int) $_SESSION['user_id'];

$bills = get_bills_for_patient($conn, $patientId);

$totalDue = get_outstanding_balance($conn, $patientId);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/my_bills_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
