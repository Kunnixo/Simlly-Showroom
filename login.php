<?php
// login.php - Hidden Admin Login
require_once __DIR__ . '/includes/auth.php';

// If already logged in, redirect to admin panel
if (is_admin_logged_in()) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Wprowadź login oraz hasło.';
    } elseif (attempt_login($username, $password)) {
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Błędny login lub hasło. Dostęp zabroniony.';
    }
}

$page_title = 'Dostęp Administracyjny – Simlly Showroom';
$active_page = 'login';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content login-page">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-card-header">
                <div class="login-badge-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h1 class="login-title">Panel Zarządzania</h1>
                <p class="login-subtitle">Wymagana autoryzacja administratora Simlly</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="login-form" autocomplete="off">
                <div class="form-group">
                    <label for="username" class="form-label">
                        <i class="fa-solid fa-user"></i> Login
                    </label>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           class="form-control" 
                           placeholder="Wprowadź login" 
                           required 
                           autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fa-solid fa-lock"></i> Hasło
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control" 
                           placeholder="••••••••••••" 
                           required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fa-solid fa-right-to-bracket"></i> Zaloguj się
                </button>
            </form>

            <div class="login-footer-hint">
                <i class="fa-solid fa-key"></i> Simlly Secure Portal
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
