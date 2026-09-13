<?php
// =====================================================
// Auth model
// Database access for login, "remember me", and fetching
// the currently logged-in user's own row.
// =====================================================

function find_user_by_role_email($conn, $role, $email) {
    $sql = "SELECT * FROM $role WHERE email = '$email' LIMIT 1";
    return mysqli_query($conn, $sql);
}

// Fetch the full DB row for whoever is currently logged in.
function get_current_user_row($conn) {
    if (!is_logged_in()) return null;
    $table = $_SESSION['role']; // 'patient', 'doctor', or 'admin'
    $idCol = $table . '_id';
    $id = (int) $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT * FROM $table WHERE $idCol = $id");
    return $result ? mysqli_fetch_assoc($result) : null;
}

// ---------------------------------------------------
// "Remember Me" - cookie-based auto login
// Cookie stores: base64("role:id:token"). The token is a random
// string also saved in that user's remember_token column, so the
// cookie alone is useless without a matching DB row.
// ---------------------------------------------------

function issue_remember_cookie($conn, $role, $id) {
    $token = bin2hex(random_bytes(32));
    $idCol = $role . '_id';
    $safeToken = mysqli_real_escape_string($conn, $token);
    mysqli_query($conn, "UPDATE $role SET remember_token = '$safeToken' WHERE $idCol = " . (int) $id);
    $cookieValue = base64_encode($role . ':' . $id . ':' . $token);
    $path = BASE_URL !== '' ? BASE_URL . '/' : '/';
    setcookie(REMEMBER_COOKIE, $cookieValue, time() + (30 * 24 * 60 * 60), $path);
}

function clear_remember_cookie($conn) {
    if (isset($_SESSION['role'], $_SESSION['user_id'])) {
        $role = $_SESSION['role'];
        $idCol = $role . '_id';
        mysqli_query($conn, "UPDATE $role SET remember_token = NULL WHERE $idCol = " . (int) $_SESSION['user_id']);
    }
    $path = BASE_URL !== '' ? BASE_URL . '/' : '/';
    setcookie(REMEMBER_COOKIE, '', time() - 3600, $path);
}

function try_remember_me_login($conn) {
    if (is_logged_in() || !isset($_COOKIE[REMEMBER_COOKIE])) {
        return;
    }
    $decoded = base64_decode($_COOKIE[REMEMBER_COOKIE], true);
    $parts = $decoded !== false ? explode(':', $decoded) : [];
    if (count($parts) !== 3) return;

    list($role, $id, $token) = $parts;
    if (!in_array($role, ['patient', 'doctor', 'admin'], true)) return;

    $idCol = $role . '_id';
    $id = (int) $id;
    $safeToken = mysqli_real_escape_string($conn, $token);
    $result = mysqli_query($conn, "SELECT * FROM $role WHERE $idCol = $id AND remember_token = '$safeToken' LIMIT 1");

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user[$idCol];
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $user['name'];
    }
}
