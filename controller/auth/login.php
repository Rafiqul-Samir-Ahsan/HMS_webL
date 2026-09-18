<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/auth_model.php';

if (is_logged_in()) {
    redirect_to_dashboard();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $validRoles = ['patient', 'doctor', 'admin'];

    if (!in_array($role, $validRoles)) {
        $errors[] = "Please select a valid account type.";
    } elseif ($email === '' || $password === '') {
        $errors[] = "Email and password are required.";
    } else {
        $safeEmail = clean($conn, $email);
        $idCol = $role . '_id';
        $result = find_user_by_role_email($conn, $role, $safeEmail);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user[$idCol];
                $_SESSION['role'] = $role;
                $_SESSION['name'] = $user['name'];
                if (isset($_POST['remember'])) {
                    issue_remember_cookie($conn, $role, $user[$idCol]);
                }
                set_flash('success', 'Welcome back, ' . $user['name'] . '!');
                redirect_to_dashboard();
            } else {
                $errors[] = "Incorrect email or password.";
            }
        } else {
            $errors[] = "Incorrect email or password.";
        }
    }
}

include __DIR__ . '/../../view/auth/login_view.php';
