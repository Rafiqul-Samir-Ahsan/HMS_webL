<?php
session_start();
require_once __DIR__ . '/../model/db.php';

// ---------------------------------------------------
// Auto-detect the app's base URL (e.g. "/hms") so redirects
// work no matter what folder name the project sits in on your server.
// ---------------------------------------------------
if (!defined('BASE_URL')) {
    $appRoot = str_replace('\\', '/', dirname(__DIR__));         // e.g. C:/xampp/htdocs/hms
    $docRoot = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')); // e.g. C:/xampp/htdocs
    $base = substr($appRoot, strlen($docRoot));                  // e.g. /hms
    define('BASE_URL', $base === false ? '' : $base);
}

// ---------------------------------------------------
// Basic helpers
// ---------------------------------------------------
function clean($conn, $value) {
    return mysqli_real_escape_string($conn, trim($value));
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

define('REMEMBER_COOKIE', 'medicore_remember');

// Login lookups, "remember me", and get_current_user_row() live in the
// model layer since they talk to the database.
require_once __DIR__ . '/../model/auth_model.php';
try_remember_me_login($conn);

// ---------------------------------------------------
// JSON response helper for AJAX endpoints
// ---------------------------------------------------
function json_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Restrict a page to one role only ('patient', 'doctor', 'admin')
function require_role($role) {
    if (!is_logged_in() || $_SESSION['role'] !== $role) {
        redirect(BASE_URL . '/controller/auth/login.php');
    }
}

// Send the user to their own dashboard (used after login / on wrong-page access)
function redirect_to_dashboard() {
    if (!is_logged_in()) {
        redirect(BASE_URL . '/controller/auth/login.php');
    }
    switch ($_SESSION['role']) {
        case 'admin':
            redirect(BASE_URL . '/view/AdminView.php');
            break;
        case 'doctor':
            redirect(BASE_URL . '/controller/doctor/dashboard.php');
            break;
        case 'patient':
            redirect(BASE_URL . '/controller/patient/dashboard.php');
            break;
        default:
            redirect(BASE_URL . '/controller/auth/login.php');
    }
}

// ---------------------------------------------------
// Flash messages (one-time session messages)
// ---------------------------------------------------
function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function render_flash() {
    $flash = get_flash();
    if ($flash) {
        $cls = $flash['type'] === 'error' ? 'alert-error' : 'alert-success';
        echo '<div class="alert ' . $cls . '">' . htmlspecialchars($flash['message']) . '</div>';
    }
}

function status_badge($status) {
    $status = htmlspecialchars($status);
    $map = [
        'Approved' => 'badge-green',
        'Pending'  => 'badge-yellow',
        'Rejected' => 'badge-red',
        'Completed'=> 'badge-blue',
        'Cancelled'=> 'badge-red',
        'Paid'     => 'badge-green',
        'Due'      => 'badge-yellow',
        'Partial'  => 'badge-blue',
        'Available'=> 'badge-green',
        'Occupied' => 'badge-red',
        'Maintenance' => 'badge-yellow',
    ];
    $cls = isset($map[$status]) ? $map[$status] : 'badge-blue';
    return '<span class="badge ' . $cls . '">' . $status . '</span>';
}
