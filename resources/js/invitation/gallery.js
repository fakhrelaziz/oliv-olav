export function initGallery() {
    const gallerySection = document.getElementById('gallery');
    const bentoGrid = document.getElementById('gallery-bento-grid');
    const photoboothSection = document.getElementById('gallery-photobooth-section');
    const filterPills = document.querySelectorAll('#gallery-filter-bar [data-gallery-filter]');

    const lightbox = document.getElementById('gallery-lightbox');
    const lightboxImg = document.getElementById('lightbox-image');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const lightboxCounter = document.getElementById('lightbox-counter');
    const closeBtn = document.getElementById('lightbox-close-btn');
    const prevBtn = document.getElementById('lightbox-prev-btn');
    const nextBtn = document.getElementById('lightbox-next-btn');

    let galleryItems = Array.from(document.querySelectorAll('.gallery-item'));
    let currentIndex = 0;

    const refreshItems = () => {
        galleryItems = Array.from(document.querySelectorAll('.gallery-item'));
    };

    const updateLightbox = () => {
        const activeItem = galleryItems[currentIndex];
        if (!activeItem) return;

        const imgEl = activeItem.querySelector('img');
        const src = activeItem.dataset.lightboxSrc || imgEl?.src || '';
        const caption = activeItem.dataset.caption || imgEl?.alt || '';

        if (lightboxImg) {
            lightboxImg.style.opacity = '0';
            window.setTimeout(() => {
                lightboxImg.src = src;
                lightboxImg.alt = caption;
                lightboxImg.style.opacity = '1';
            }, 120);
        }
        if (lightboxCaption) lightboxCaption.textContent = caption;
        if (lightboxCounter) lightboxCounter.textContent = `${currentIndex + 1} / ${galleryItems.length}`;
    };

    const openLightbox = (index) => {
        refreshItems();
        if (!lightbox || !galleryItems[index]) return;
        currentIndex = index;
        updateLightbox();

        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            lightbox.classList.remove('opacity-0');
            if (lightboxImg) {
                lightboxImg.classList.remove('scale-95');
                lightboxImg.classList.add('scale-100');
            }
        });
    };

    const closeLightbox = () => {
        if (!lightbox) return;
        lightbox.classList.add('opacity-0');
        if (lightboxImg) {
            lightboxImg.classList.remove('scale-100');
            lightboxImg.classList.add('scale-95');
        }
        window.setTimeout(() => {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        }, 280);
    };

    const showPrev = () => {
        if (!galleryItems.length) return;
        currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
        updateLightbox();
    };

    const showNext = () => {
        if (!galleryItems.length) return;
        currentIndex = (currentIndex + 1) % galleryItems.length;
        updateLightbox();
    };

    if (gallerySection) {
        gallerySection.addEventListener('click', (e) => {
            const clicked = e.target.closest('.gallery-item');
            if (!clicked) return;
            refreshItems();
            const idx = galleryItems.indexOf(clicked);
            if (idx !== -1) openLightbox(idx);
        });
    }

    // Category filter pills
    filterPills.forEach((pill) => {
        pill.addEventListener('click', () => {
            const category = pill.dataset.galleryFilter || 'all';

            filterPills.forEach((btn) => {
                btn.className = 'gallery-filter-pill px-4 py-2 rounded-full text-xs font-medium bg-warmwhite/80 text-moss hover:bg-moss/10 border border-moss/20 transition';
            });
            pill.className = 'gallery-filter-pill px-4 py-2 rounded-full text-xs font-medium bg-moss text-warmwhite shadow-sm transition';

            if (category === 'photobooth') {
                bentoGrid?.classList.add('hidden');
                if (photoboothSection) {
                    photoboothSection.classList.remove('hidden', 'mt-16', 'pt-12', 'border-t');
                    photoboothSection.classList.add('mt-2');
                }
                return;
            }

            bentoGrid?.classList.remove('hidden');
            if (photoboothSection) {
                photoboothSection.classList.remove('mt-2');
                photoboothSection.classList.add('mt-16', 'pt-12', 'border-t');
                if (category === 'all' || category === 'keseharian') {
                    photoboothSection.classList.remove('hidden');
                } else {
                    photoboothSection.classList.add('hidden');
                }
            }

            if (bentoGrid) {
                bentoGrid.querySelectorAll('.gallery-item').forEach((item) => {
                    const itemCat = (item.getAttribute('data-category') || '').toLowerCase();
                    if (category === 'all' || itemCat.includes(category)) {
                        item.classList.remove('opacity-20', 'scale-[0.97]', 'pointer-events-none');
                    } else {
                        item.classList.add('opacity-20', 'scale-[0.97]', 'pointer-events-none');
                    }
                });
            }
        });
    });

    closeBtn?.addEventListener('click', closeLightbox);
    prevBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        showPrev();
    });
    nextBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        showNext();
    });

    lightbox?.addEventListener('click', (e) => {
        if (e.target === lightbox || e.target.classList.contains('flex-1')) {
            closeLightbox();
        }
    });

    window.addEventListener('keydown', (e) => {
        if (!lightbox || lightbox.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') showPrev();
        if (e.key === 'ArrowRight') showNext();
    });
}
