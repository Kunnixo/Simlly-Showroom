<?php
// includes/post_card.php - Helper to render a single post card

function render_post_card(array $post, bool $show_admin_actions = false): void {
    $images = is_array($post['images']) ? $post['images'] : (json_decode($post['images'] ?? '[]', true) ?: []);
    $image_count = count($images);
    $date_formatted = !empty($post['created_at']) ? date('d.m.Y', strtotime($post['created_at'])) : '';
    ?>
    <article class="post-card" id="post-<?= (int)$post['id'] ?>" data-post-id="<?= (int)$post['id'] ?>">
        <!-- Card Header: Title and Date -->
        <header class="post-card-header">
            <div class="post-meta-top">
                <span class="post-badge"><i class="fa-solid fa-flag-checkered"></i> Projekt</span>
                <?php if ($date_formatted): ?>
                    <span class="post-date"><i class="fa-regular fa-calendar"></i> <?= $date_formatted ?></span>
                <?php endif; ?>
            </div>
            <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
        </header>

        <!-- Card Media: 1:1 Aspect Ratio Gallery -->
        <div class="post-media-container aspect-square">
            <?php if ($image_count > 0): ?>
                <div class="post-slider" data-current-slide="0">
                    <div class="post-slides-wrapper">
                        <?php foreach ($images as $idx => $img): ?>
                            <div class="post-slide <?= $idx === 0 ? 'active' : '' ?>">
                                <img src="<?= htmlspecialchars($img) ?>" 
                                     alt="<?= htmlspecialchars($post['title']) ?> - zdjęcie <?= $idx + 1 ?>" 
                                     class="post-slide-img" 
                                     loading="lazy"
                                     data-gallery-item
                                     data-full-src="<?= htmlspecialchars($img) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($image_count > 1): ?>
                        <!-- Slider Controls -->
                        <button type="button" class="post-slider-btn prev-btn" aria-label="Poprzednie zdjęcie">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button type="button" class="post-slider-btn next-btn" aria-label="Następne zdjęcie">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>

                        <!-- Thumbnail / Dot Indicators -->
                        <div class="post-slider-dots">
                            <?php for ($i = 0; $i < $image_count; $i++): ?>
                                <button type="button" class="dot-indicator <?= $i === 0 ? 'active' : '' ?>" data-slide-to="<?= $i ?>" aria-label="Zdjęcie <?= $i + 1 ?>"></button>
                            <?php endfor; ?>
                        </div>
                        <div class="post-photo-counter">
                            <span class="curr-photo-num">1</span>/<?= $image_count ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="post-no-image">
                    <i class="fa-solid fa-image"></i>
                    <span>Brak zdjęć</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Card Body: Small print description with 3-line clamp and ... toggle -->
        <div class="post-card-body">
            <div class="post-description-wrapper">
                <p class="post-description small-print clamped" data-full-text="<?= htmlspecialchars($post['content']) ?>">
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </p>
                <button type="button" class="expand-dots-btn" aria-label="Rozwiń treść" title="Kliknij, aby przeczytać całość">
                    <span class="dots-symbol">...</span>
                    <span class="expand-label"></span>
                </button>
            </div>
        </div>

        <?php if ($show_admin_actions): ?>
            <!-- Admin Actions -->
            <footer class="post-admin-actions">
                <form method="POST" action="admin.php" onsubmit="return confirm('Czy na pewno chcesz usunąć ten post?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                    <button type="submit" class="btn-delete-post">
                        <i class="fa-solid fa-trash"></i> Usuń post
                    </button>
                </form>
            </footer>
        <?php endif; ?>
    </article>
    <?php
}
