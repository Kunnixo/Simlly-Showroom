<?php
// index.php - Simlly Showroom Home Page
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/post_card.php';

$page_title = 'Simlly Showroom – Built by rally fans for rally fans';
$active_page = 'home';

// Scan carousel images dynamically from Foto karuzelka
$carousel_dir = __DIR__ . '/Foto karuzelka';
$carousel_files = glob($carousel_dir . '/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [];
$carousel_images = [];
foreach ($carousel_files as $file) {
    $carousel_images[] = 'Foto karuzelka/' . rawurlencode(basename($file));
}

// Fetch 3 latest posts
$latest_posts = get_latest_posts(3);

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content">
    <!-- Hero 21:9 Carousel Section with Centered Logo -->
    <section class="hero-carousel-section">
        <div class="carousel-21-9-container" id="heroCarousel" data-autoplay-speed="4500">
            <!-- Background Carousel Slides -->
            <div class="carousel-track">
                <?php if (!empty($carousel_images)): ?>
                    <?php foreach ($carousel_images as $idx => $img_url): ?>
                        <div class="carousel-slide <?= $idx === 0 ? 'active' : '' ?>" style="background-image: url('<?= $img_url ?>');">
                            <div class="slide-overlay"></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-slide active fallback-bg">
                        <div class="slide-overlay"></div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Overlaid Big Simlly Logo in the Center -->
            <div class="hero-center-branding">
                <div class="hero-logo-wrapper">
                    <img src="assets/images/logo.png" alt="Simlly Wheel Logo" class="hero-big-logo">
                    <div class="hero-logo-glow"></div>
                </div>
                <div class="hero-text-badge">
                    <h1 class="hero-brand-title">SIMLLY SHOWROOM</h1>
                    <p class="hero-brand-subtitle">"Built by rally fans for rally fans"</p>
                    <div class="hero-cta-group">
                        <a href="galeria.php" class="btn btn-primary"><i class="fa-solid fa-images"></i> Zobacz Galerię Prac</a>
                        <a href="o-nas.php" class="btn btn-secondary"><i class="fa-solid fa-flag"></i> O nas</a>
                    </div>
                </div>
            </div>

            <!-- Carousel Navigation Controls -->
            <?php if (count($carousel_images) > 1): ?>
                <button type="button" class="carousel-arrow prev" id="heroPrev" aria-label="Poprzedni slajd">&#10094;</button>
                <button type="button" class="carousel-arrow next" id="heroNext" aria-label="Następny slajd">&#10095;</button>
                <div class="carousel-dots" id="heroDots">
                    <?php for ($i = 0; $i < count($carousel_images); $i++): ?>
                        <button type="button" class="carousel-dot <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>" aria-label="Slajd <?= $i + 1 ?>"></button>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Latest 3 Posts Section -->
    <section class="section latest-posts-section">
        <div class="site-container">
            <div class="section-header">
                <div class="section-header-left">
                    <span class="section-badge"><i class="fa-solid fa-bolt"></i> Ostatnie Realizacje</span>
                    <h2 class="section-title">Najnowsze Posty</h2>
                    <p class="section-subtitle">Sprawdź nasze najświeższe projekty customowych kierownic i akcesoriów simracingowych.</p>
                </div>
                <div class="section-header-right">
                    <a href="galeria.php" class="link-all-posts">Wszystkie prace <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="posts-grid posts-grid-home">
                <?php if (!empty($latest_posts)): ?>
                    <?php foreach ($latest_posts as $post): ?>
                        <?php render_post_card($post); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state-box">
                        <i class="fa-regular fa-newspaper"></i>
                        <h3>Brak postów do wyświetlenia</h3>
                        <p>Nowe realizacje pojawią się wkrótce.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Social Media Section -->
    <section class="section social-banner-section">
        <div class="site-container">
            <div class="social-banner-card">
                <div class="social-banner-header">
                    <span class="section-badge"><i class="fa-solid fa-hashtag"></i> Dołącz do nas</span>
                    <h2 class="social-banner-title">Śledź Simlly w mediach społecznościowych</h2>
                    <p class="social-banner-desc">Bądź na bieżąco z relacjami z warsztatu, procesem tworzenia nowych akcesorii i testami na RIG'u!</p>
                </div>

                <div class="social-cards-wrapper">
                    <a href="https://www.instagram.com/simlly.official/" target="_blank" rel="noopener noreferrer" class="social-big-card ig-card">
                        <div class="social-card-icon">
                            <i class="fa-brands fa-instagram"></i>
                        </div>
                        <div class="social-card-info">
                            <span class="social-name">Instagram</span>
                            <span class="social-nick">@simlly.official</span>
                            <span class="social-action">Obserwuj profil &rarr;</span>
                        </div>
                    </a>

                    <a href="https://www.tiktok.com/@simlly.official" target="_blank" rel="noopener noreferrer" class="social-big-card tt-card">
                        <div class="social-card-icon">
                            <i class="fa-brands fa-tiktok"></i>
                        </div>
                        <div class="social-card-info">
                            <span class="social-name">TikTok</span>
                            <span class="social-nick">@simlly.official</span>
                            <span class="social-action">Zobacz wideo &rarr;</span>
                        </div>
                    </a>

                    <a href="https://www.youtube.com/@Simlly.wheels" target="_blank" rel="noopener noreferrer" class="social-big-card yt-card">
                        <div class="social-card-icon">
                            <i class="fa-brands fa-youtube"></i>
                        </div>
                        <div class="social-card-info">
                            <span class="social-name">YouTube</span>
                            <span class="social-nick">@Simlly.wheels</span>
                            <span class="social-action">Subskrybuj kanał &rarr;</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
