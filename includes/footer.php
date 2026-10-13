<?php
// includes/footer.php - Footer with social media links and JavaScript inclusion
?>
    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-brand-col">
                <div class="footer-brand-logo">
                    <img src="assets/images/logo.png" alt="Simlly" class="footer-mini-logo">
                    <span class="footer-brand-name">SIMLLY</span>
                </div>
                <p class="footer-motto">"Built by rally fans for rally fans"</p>
            </div>

            <div class="footer-social-col">
                <h4 class="footer-heading">Social Media</h4>
                <div class="social-links-grid">
                    <a href="https://www.instagram.com/simlly.official/" target="_blank" rel="noopener noreferrer" class="social-card social-ig" title="Instagram @simlly.official">
                        <i class="fa-brands fa-instagram"></i>
                        <div class="social-meta">
                            <span class="social-platform">Instagram</span>
                            <span class="social-handle">@simlly.official</span>
                        </div>
                    </a>
                    <a href="https://www.tiktok.com/@simlly.official" target="_blank" rel="noopener noreferrer" class="social-card social-tt" title="TikTok @simlly.official">
                        <i class="fa-brands fa-tiktok"></i>
                        <div class="social-meta">
                            <span class="social-platform">TikTok</span>
                            <span class="social-handle">@simlly.official</span>
                        </div>
                    </a>
                    <a href="https://www.youtube.com/@Simlly.wheels" target="_blank" rel="noopener noreferrer" class="social-card social-yt" title="YouTube @Simlly.wheels">
                        <i class="fa-brands fa-youtube"></i>
                        <div class="social-meta">
                            <span class="social-platform">YouTube</span>
                            <span class="social-handle">@Simlly.wheels</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-container">
                <p>&copy; <?= date('Y') ?> Simlly. Wszelkie prawa zastrzeżone.</p>
                <p class="footer-subtext">Designed for SimRacing &amp; Motorsport Enthusiasts.</p>
            </div>
        </div>
    </footer>

    <!-- Lightbox Modal for Full View Images -->
    <div class="image-lightbox-modal" id="imageLightboxModal">
        <button class="lightbox-close" id="lightboxClose" aria-label="Zamknij">&times;</button>
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Poprzednie">&#10094;</button>
        <button class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Następne">&#10095;</button>
        <div class="lightbox-content">
            <img src="" alt="Powiększone zdjęcie" id="lightboxImg">
            <div class="lightbox-counter" id="lightboxCounter"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    <?php if (isset($extra_js)): ?>
        <script src="<?= htmlspecialchars($extra_js) ?>"></script>
    <?php endif; ?>
</body>
</html>
