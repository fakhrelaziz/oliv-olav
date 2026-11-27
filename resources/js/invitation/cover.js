export function initOpeningCover() {
    const coverEl = document.getElementById('opening-cover');
    const openBtn = document.getElementById('btn-open-invitation');
    if (!coverEl || !openBtn) return;

    const slides = Array.from(coverEl.querySelectorAll('.opening-cover-slide'));
    const dots = Array.from(coverEl.querySelectorAll('.opening-cover-dot'));
    const coverVideo = document.getElementById('opening-cover-video');

    let activeSlideIdx = 0;
    let slideTimer = null;
    let isCoverOpened = false;

    const stopSlideTimer = () => {
        if (slideTimer !== null) {
            clearInterval(slideTimer);
            slideTimer = null;
        }
    };

    const goToCoverSlide = (nextIdx) => {
        if (isCoverOpened || slides.length === 0) return;
        activeSlideIdx = (nextIdx + slides.length) % slides.length;

        slides.forEach((slide, idx) => {
            slide.classList.toggle('is-active', idx === activeSlideIdx);
        });
        dots.forEach((dot, idx) => {
            dot.classList.toggle('is-active', idx === activeSlideIdx);
        });

        if (coverVideo) {
            const isVideoSlide = Boolean(slides[activeSlideIdx]?.contains(coverVideo));
            if (isVideoSlide) {
                coverVideo.currentTime = 0;
                coverVideo.play().catch(() => {});
            } else {
                coverVideo.pause();
            }
        }
    };

    const startSlideTimer = () => {
        stopSlideTimer();
        slideTimer = window.setInterval(() => {
            goToCoverSlide(activeSlideIdx + 1);
        }, 4500);
    };

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            goToCoverSlide(idx);
            startSlideTimer();
        });
    });

    const openInvitationGate = () => {
        if (isCoverOpened) return;
        isCoverOpened = true;
        stopSlideTimer();

        window.scrollTo({ top: 0, behavior: 'instant' });
        document.body.classList.remove('cover-locked');
        coverEl.classList.add('is-opened');
        coverEl.setAttribute('aria-hidden', 'true');

        window.dispatchEvent(new CustomEvent('invitation:opened'));

        window.setTimeout(() => {
            if (coverVideo) coverVideo.pause();
            coverEl.style.display = 'none';
        }, 950);
    };

    openBtn.addEventListener('click', openInvitationGate);
    startSlideTimer();
}
