/**
 * assets/js/main.js - Interactive features for Simlly Showroom
 */

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
    initHeroCarousel();
    initPostSliders();
    initTextExpanders();
    initLightbox();
});

/* ============================================================
   1. Mobile Navigation Toggle
============================================================ */
function initMobileNav() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('open');
            navToggle.classList.toggle('active');
        });
    }
}

/* ============================================================
   2. Hero 21:9 Carousel
============================================================ */
function initHeroCarousel() {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) return;

    const slides = carousel.querySelectorAll('.carousel-slide');
    const dots = carousel.querySelectorAll('.carousel-dot');
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    const intervalTime = parseInt(carousel.dataset.autoplaySpeed || '4500', 10);

    if (slides.length <= 1) return;

    let currentIndex = 0;
    let timer = null;

    function goToSlide(index) {
        slides[currentIndex].classList.remove('active');
        if (dots[currentIndex]) dots[currentIndex].classList.remove('active');

        currentIndex = (index + slides.length) % slides.length;

        slides[currentIndex].classList.add('active');
        if (dots[currentIndex]) dots[currentIndex].classList.add('active');
    }

    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    function startAutoplay() {
        stopAutoplay();
        timer = setInterval(nextSlide, intervalTime);
    }

    function stopAutoplay() {
        if (timer) clearInterval(timer);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            startAutoplay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            startAutoplay();
        });
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            goToSlide(idx);
            startAutoplay();
        });
    });

    carousel.addEventListener('mouseenter', stopAutoplay);
    carousel.addEventListener('mouseleave', startAutoplay);

    startAutoplay();
}

/* ============================================================
   3. Post 1:1 Image Sliders
============================================================ */
function initPostSliders() {
    const sliders = document.querySelectorAll('.post-slider');

    sliders.forEach(slider => {
        const slides = slider.querySelectorAll('.post-slide');
        const prevBtn = slider.querySelector('.prev-btn');
        const nextBtn = slider.querySelector('.next-btn');
        const dots = slider.querySelectorAll('.dot-indicator');
        const counter = slider.querySelector('.curr-photo-num');

        if (slides.length <= 1) return;

        let currentIndex = 0;

        function updateSlide(newIndex) {
            slides[currentIndex].classList.remove('active');
            if (dots[currentIndex]) dots[currentIndex].classList.remove('active');

            currentIndex = (newIndex + slides.length) % slides.length;

            slides[currentIndex].classList.add('active');
            if (dots[currentIndex]) dots[currentIndex].classList.add('active');
            if (counter) counter.textContent = currentIndex + 1;
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                updateSlide(currentIndex - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                updateSlide(currentIndex + 1);
            });
        }

        dots.forEach((dot, idx) => {
            dot.addEventListener('click', (e) => {
                e.stopPropagation();
                updateSlide(idx);
            });
        });
    });
}

/* ============================================================
   4. Post Description 3-line Truncate & "..." Toggle
============================================================ */
function initTextExpanders() {
    const wrappers = document.querySelectorAll('.post-description-wrapper');

    wrappers.forEach(wrapper => {
        const textElem = wrapper.querySelector('.post-description');
        const btn = wrapper.querySelector('.expand-dots-btn');

        if (!textElem || !btn) return;

        // Determine if content exceeds 3 lines
        // A standard 3-line box is ~75px. If scrollHeight > clientHeight by at least 4px, show button
        const checkOverflow = () => {
            const isOverflowing = textElem.scrollHeight > (textElem.clientHeight + 4);
            if (!isOverflowing && !textElem.classList.contains('expanded')) {
                // If text is short, hide the "..." button
                btn.style.display = 'none';
            } else {
                btn.style.display = 'inline-flex';
            }
        };

        // Run check after render
        setTimeout(checkOverflow, 50);
        window.addEventListener('resize', checkOverflow);

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const isExpanded = textElem.classList.contains('expanded');

            if (isExpanded) {
                textElem.classList.remove('expanded');
                textElem.classList.add('clamped');
                btn.classList.remove('is-expanded');
            } else {
                textElem.classList.remove('clamped');
                textElem.classList.add('expanded');
                btn.classList.add('is-expanded');
            }
        });
    });
}

/* ============================================================
   5. Lightbox Modal
============================================================ */
function initLightbox() {
    const modal = document.getElementById('imageLightboxModal');
    if (!modal) return;

    const imgElem = document.getElementById('lightboxImg');
    const closeBtn = document.getElementById('lightboxClose');
    const prevBtn = document.getElementById('lightboxPrev');
    const nextBtn = document.getElementById('lightboxNext');
    const counterElem = document.getElementById('lightboxCounter');

    let currentImages = [];
    let currentIndex = 0;

    function openLightbox(imagesList, startIndex) {
        currentImages = imagesList;
        currentIndex = startIndex;
        showImage();
        modal.classList.add('is-active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        modal.classList.remove('is-active');
        document.body.style.overflow = '';
    }

    function showImage() {
        if (!currentImages.length) return;
        imgElem.src = currentImages[currentIndex];
        if (counterElem) {
            counterElem.textContent = `${currentIndex + 1} / ${currentImages.length}`;
        }
        if (prevBtn && nextBtn) {
            prevBtn.style.display = currentImages.length > 1 ? 'flex' : 'none';
            nextBtn.style.display = currentImages.length > 1 ? 'flex' : 'none';
        }
    }

    function nextImg() {
        if (!currentImages.length) return;
        currentIndex = (currentIndex + 1) % currentImages.length;
        showImage();
    }

    function prevImg() {
        if (!currentImages.length) return;
        currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
        showImage();
    }

    // Attach click to all post images
    document.addEventListener('click', (e) => {
        const target = e.target.closest('[data-gallery-item]');
        if (!target) return;

        const card = target.closest('.post-card');
        if (!card) return;

        const allImgsInCard = Array.from(card.querySelectorAll('[data-gallery-item]')).map(img => img.dataset.fullSrc || img.src);
        const clickedSrc = target.dataset.fullSrc || target.src;
        const clickedIdx = allImgsInCard.indexOf(clickedSrc);

        openLightbox(allImgsInCard, clickedIdx >= 0 ? clickedIdx : 0);
    });

    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (nextBtn) nextBtn.addEventListener('click', nextImg);
    if (prevBtn) prevBtn.addEventListener('click', prevImg);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeLightbox();
    });

    document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('is-active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextImg();
        if (e.key === 'ArrowLeft') prevImg();
    });
}
