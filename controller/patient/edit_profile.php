<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/patient_model.php';
require_role('patient');

$currentUser = get_current_user_row($conn);
$pageTitle = 'Edit Profile';
$assetPath = '../../';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($conn, $_POST['name']);
    $dob = clean($conn, $_POST['date_of_birth']);
    $gender = clean($conn, $_POST['gender']);
    $phone = clean($conn, $_POST['phone']);
    $email = clean($conn, $_POST['email']);
    $address = clean($conn, $_POST['address']);
    $bloodGroup = clean($conn, $_POST['blood_group']);
    $id = (int) $_SESSION['user_id'];

    if ($name === '' || $email === '') {
        $errors[] = "Name and email are required.";
    } else {
        if (update_patient($conn, $id, $name, $dob, $gender, $phone, $email, $address, $bloodGroup)) {
            $_SESSION['name'] = $name;
            set_flash('success', 'Profile updated successfully.');
            redirect('profile.php');
        } else {
            $errors[] = "Could not update profile (email may already be in use).";
        }
    }
    $currentUser = get_current_user_row($conn);
}

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_patient.php';
include __DIR__ . '/../../view/patient/edit_profile_view.php';
include __DIR__ . '/../../view/layouts/footer.php';
