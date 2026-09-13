<?php
require_once __DIR__ . '/../functions.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'My Profile';
$assetPath = '../../';

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/profile_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
