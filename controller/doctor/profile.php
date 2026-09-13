<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/doctor_model.php';
require_role('doctor');

$currentUser = get_current_user_row($conn);
$pageTitle = 'My Profile';
$assetPath = '../../';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($conn, $_POST['name']);
    $specialization = clean($conn, $_POST['specialization']);
    $qualification = clean($conn, $_POST['qualification']);
    $experience = (int) $_POST['experience'];
    $fee = (float) $_POST['consultation_fee'];
    $phone = clean($conn, $_POST['phone']);
    $email = clean($conn, $_POST['email']);
    $id = (int) $_SESSION['user_id'];

    if ($name === '' || $email === '') {
        $errors[] = "Name and email are required.";
    } else {
        if (update_doctor($conn, $id, $name, $specialization, $qualification, $experience, $fee, $phone, $email)) {
            $_SESSION['name'] = $name;
            set_flash('success', 'Profile updated.');
            redirect('profile.php');
        } else {
            $errors[] = "Could not update profile (email may already be in use).";
        }
    }
    $currentUser = get_current_user_row($conn);
}

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_doctor.php';
include __DIR__ . '/../../view/doctor/profile_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
