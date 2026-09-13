<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/patient_model.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Change Password';
$assetPath = '../../';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $id = (int) $_SESSION['user_id'];

    if (!password_verify($current, $currentUser['password'])) {
        $errors[] = "Current password is incorrect.";
    } elseif (strlen($new) < 6) {
        $errors[] = "New password must be at least 6 characters.";
    } elseif ($new !== $confirm) {
        $errors[] = "New passwords do not match.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        update_patient_password($conn, $id, $hashed);
        set_flash('success', 'Password changed successfully.');
        redirect('change_password.php');
    }
}

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/change_password_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
