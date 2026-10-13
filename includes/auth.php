<?php
// includes/auth.php - Session and authentication management

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'readytorace');

function is_admin_logged_in(): bool {
    return !empty($_SESSION['simlly_admin_logged']) && $_SESSION['simlly_admin_logged'] === true;
}

function require_admin(): void {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function attempt_login(string $username, string $password): bool {
    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['simlly_admin_logged'] = true;
        $_SESSION['simlly_admin_user'] = $username;
        $_SESSION['simlly_login_time'] = time();
        return true;
    }
    return false;
}

function logout_admin(): void {
    $_SESSION = [];
    if (!headers_sent() && ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
