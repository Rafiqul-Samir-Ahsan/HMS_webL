<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/patient_model.php';

if (is_logged_in()) {
    redirect_to_dashboard();
}

$errors = [];
$old = ['name'=>'','date_of_birth'=>'','gender'=>'Male','phone'=>'','email'=>'','address'=>'','blood_group'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $key => $val) {
        $old[$key] = isset($_POST[$key]) ? trim($_POST[$key]) : '';
    }
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if ($old['name'] === '' || $old['date_of_birth'] === '' || $old['phone'] === '' || $old['email'] === '') {
        $errors[] = "Please fill in all required fields.";
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $safeEmail = clean($conn, $old['email']);
        $check = find_patient_by_email($conn, $safeEmail);
        if ($check && mysqli_num_rows($check) > 0) {
            $errors[] = "An account with this email already exists.";
        }
    }

    if (empty($errors)) {
        $name = clean($conn, $old['name']);
        $dob = clean($conn, $old['date_of_birth']);
        $gender = clean($conn, $old['gender']);
        $phone = clean($conn, $old['phone']);
        $address = clean($conn, $old['address']);
        $bloodGroup = clean($conn, $old['blood_group']);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (insert_patient($conn, $name, $dob, $gender, $phone, $safeEmail, $hashedPassword, $address, $bloodGroup)) {
            set_flash('success', 'Account created successfully. Please log in.');
            redirect('login.php');
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}

include __DIR__ . '/../../view/auth/register_view.php';
