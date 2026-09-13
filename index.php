<?php
require_once __DIR__ . '/controller/functions.php';
if (is_logged_in()) {
    redirect_to_dashboard();
} else {
    redirect('controller/auth/login.php');
}
