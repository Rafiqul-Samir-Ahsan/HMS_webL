<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/appointment_model.php';
require_once __DIR__ . '/../../model/prescription_model.php';
require_role('doctor');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Dashboard';
$assetPath = '../../';
$doctorId = (int) $_SESSION['user_id'];

$todayCount = count_today_for_doctor($conn, $doctorId);
$totalPatients = count_distinct_patients_for_doctor($conn, $doctorId);
$prescriptionsThisMonth = count_prescriptions_this_month_for_doctor($conn, $doctorId);
$pendingCount = count_pending_for_doctor($conn, $doctorId);

$todayAppointments = get_today_appointments_for_doctor($conn, $doctorId);
$recentPatients = get_recent_patients_for_doctor($conn, $doctorId, 5);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_doctor.php';
include __DIR__ . '/../../view/doctor/dashboard_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
