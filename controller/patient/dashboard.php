<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/appointment_model.php';
require_once __DIR__ . '/../../model/prescription_model.php';
require_once __DIR__ . '/../../model/bill_model.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Dashboard';
$assetPath = '../../';
$patientId = (int) $_SESSION['user_id'];

$upcomingCount = count_upcoming_for_patient($conn, $patientId);
$activePrescriptions = count_prescriptions_for_patient($conn, $patientId);
$outstanding = get_outstanding_balance($conn, $patientId);

$nextAppt = get_next_appointment_for_patient($conn, $patientId);

$upcomingAppointments = get_upcoming_appointments_for_patient($conn, $patientId, 5);

$recentBills = get_recent_bills_for_patient($conn, $patientId, 5);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/dashboard_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
