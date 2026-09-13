<?php
// galeria.php - Gallery of all posts
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/post_card.php';

$page_title = 'Galeria Prac – Simlly Showroom';
$active_page = 'galeria';

$all_posts = get_all_posts();
$total_count = count($all_posts);

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-content gallery-page">
    <div class="site-container">
        <!-- Gallery Header -->
        <header class="page-title-banner">
            <div class="page-title-content">
                <span class="section-badge"><i class="fa-solid fa-camera"></i> Showroom Portfolio</span>
                <h1 class="page-heading">Galeria Realizacji</h1>
                <p class="page-lead">
                    Odkryj pełną kolekcję naszych unikatowych kierownic, paneli i akcesoriów simracingowych.
                </p>
                <div class="gallery-meta-count">
                    <span class="count-pill"><i class="fa-solid fa-layer-group"></i> Łącznie prac: <strong><?= $total_count ?></strong></span>
                    <span class="count-hint"><i class="fa-solid fa-arrows-up-down"></i> Przewijaj w pionie, aby odkryć starsze projekty</span>
                </div>
            </div>
        </header>

        <!-- Gallery Scrollable Container (Max 3 per row, custom vertical scrollbar) -->
        <div class="gallery-scroll-wrapper">
            <div class="gallery-scroll-container custom-scrollbar" id="galleryContainer">
                <?php if (!empty($all_posts)): ?>
                    <div class="posts-grid posts-grid-3cols">
                        <?php foreach ($all_posts as $post): ?>
                            <?php render_post_card($post); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state-box">
                        <i class="fa-regular fa-image"></i>
                        <h3>Brak prac w galerii</h3>
                        <p>Zaloguj się do panelu administratora, aby dodać pierwsze realizacje.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
