<?php
require_once __DIR__ . '/../functions.php';
clear_remember_cookie($conn);
$_SESSION = [];
session_destroy();
session_start();
set_flash('success', 'You have been logged out.');
redirect(BASE_URL . '/controller/auth/login.php');
