<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/appointment_model.php';
require_role('doctor');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Appointments';
$assetPath = '../../';
$doctorId = (int) $_SESSION['user_id'];

$filter = isset($_GET['status']) ? $_GET['status'] : 'All';
$safeFilter = $filter !== 'All' ? clean($conn, $filter) : 'All';

$appointments = get_appointments_for_doctor($conn, $doctorId, $safeFilter);

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_doctor.php';
include __DIR__ . '/../../view/doctor/appointments_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
