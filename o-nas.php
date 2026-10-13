<?php
// o-nas.php - About Us page
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$page_title = 'O Nas – Simlly Showroom';
$active_page = 'o-nas';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content about-page">
    <div class="site-container">
        <!-- Breadcrumb / Badge -->
        <div class="page-title-banner text-center">
            <span class="section-badge"><i class="fa-solid fa-flag-checkered"></i> Poznaj Naszą Pasję</span>
            <h1 class="page-heading">O Nas</h1>
        </div>

        <!-- About Hero Quote Card -->
        <div class="about-card-hero">
            <div class="about-quote-mark"><i class="fa-solid fa-quote-left"></i></div>
            
            <blockquote class="about-main-quote">
                "Celem założenia Simlly było motto <strong>"Built by rally fans for rally fans"</strong>.
                Każdy z naszych produktów stara się być jak najwierniejszym i jednocześnie przystępnym dla klienta odwzorowaniem namiastki prawdziwej rajdówki. Jesteśmy otwarci również na projekty indywidualne więc zapraszamy w razie czego do kontaktu. Razem możemy stworzyć kawał unikatowej części do twojego RIG'a"
            </blockquote>

            <div class="about-quote-author">
                <div class="author-avatar">
                    <img src="assets/images/logo.png" alt="Simlly Wheels Logo">
                </div>
                <div class="author-info">
                    <span class="author-name">Zespół SIMLLY</span>
                    <span class="author-role">Motorsport &amp; SimRacing Accesories</span>
                </div>
            </div>
        </div>

        <!-- Feature Pillars -->
        <div class="about-pillars-grid">
            <div class="pillar-card">
                <div class="pillar-icon">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <h3 class="pillar-title">Dla Fanów Rajdów</h3>
                <p class="pillar-desc">
                    Każdy projekt rodzi się z miłości do Rajdówek, zapachu rajdowego paliwa i bezkompromisowej jazdy na szutrze i asfalcie.
                </p>
            </div>

            <div class="pillar-card">
                <div class="pillar-icon">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <h3 class="pillar-title">Odwzorowanie Rajdówki</h3>
                <p class="pillar-desc">
                    Opieramy się głównie na Druku 3D co pozwala nam być przystępnymi cenowo nie rezygnując z wiernego odwzorowania i jakości wykonania.
                </p>
            </div>

            <div class="pillar-card">
                <div class="pillar-icon">
                    <i class="fa-solid fa-gears"></i>
                </div>
                <h3 class="pillar-title">Projekty Indywidualne</h3>
                <p class="pillar-desc">
                    Marzy Ci się unikatowy plate, button box lub replika konkretnego koła? Stworzymy dedykowane akcesorium skrojone pod Twój RIG!
                </p>
            </div>
        </div>

        <!-- Contact Callout Box -->
        <div class="about-contact-callout">
            <div class="callout-inner">
                <div class="callout-text">
                    <h3 class="callout-title">Masz pomysł na swój projekt?</h3>
                    <p class="callout-desc">
                        Skontaktuj się z nami przez nasze kanały społecznościowe. Porozmawiajmy o specyfikacji i stwórzmy coś niepowtarzalnego!
                    </p>
                </div>
                <div class="callout-actions">
                    <a href="https://www.instagram.com/simlly.official/" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                        <i class="fa-brands fa-instagram"></i> Napisz na Instagramie
                    </a>
                    <a href="galeria.php" class="btn btn-secondary">
                        <i class="fa-solid fa-images"></i> Zobacz dotychczasowe prace
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
