<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/patient_model.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Delete Account';
$assetPath = '../../';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $id = (int) $_SESSION['user_id'];

    if (!password_verify($password, $currentUser['password'])) {
        $errors[] = "Incorrect password. Account was not deleted.";
    } else {
        delete_patient($conn, $id);
        $_SESSION = [];
        session_destroy();
        session_start();
        set_flash('success', 'Your account has been deleted.');
        redirect('../auth/login.php');
    }
}

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/delete_account_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
