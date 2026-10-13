<?php
// includes/header.php - Top navigation bar and HTML head
if (!isset($page_title)) {
    $page_title = 'Simlly Showroom – Built by rally fans for rally fans';
}
if (!isset($active_page)) {
    $active_page = '';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <!-- Google Fonts: Inter & Montserrat for high-performance motorsport feel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;0,900;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for modern social icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Main Style -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="site-body">
    <!-- Navigation Bar -->
    <header class="main-header">
        <div class="nav-container">
            <!-- Left: Brand Logo -->
            <a href="index.php" class="brand-logo-link" title="Simlly - Strona Główna">
                <img src="assets/images/logo.png" alt="Simlly Wheels Logo" class="brand-logo-img">
                <span class="brand-text">SIMLLY<span class="brand-accent">.</span></span>
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button class="nav-toggle" id="navToggle" aria-label="Przełącz menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Right: Navigation Links -->
            <nav class="nav-menu" id="navMenu">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="o-nas.php" class="nav-link <?= ($active_page === 'o-nas') ? 'active' : '' ?>">O nas</a>
                    </li>
                    <li class="nav-item">
                        <a href="galeria.php" class="nav-link <?= ($active_page === 'galeria') ? 'active' : '' ?>">Galeria</a>
                    </li>
                    <?php if (function_exists('is_admin_logged_in') && is_admin_logged_in()): ?>
                    <li class="nav-item admin-badge-item">
                        <a href="admin.php" class="nav-link admin-pill <?= ($active_page === 'admin') ? 'active' : '' ?>">
                            <i class="fa-solid fa-gear"></i> Panel Postów
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link logout-pill" title="Wyloguj się">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
