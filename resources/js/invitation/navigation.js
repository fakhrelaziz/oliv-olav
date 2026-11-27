export function initNavigation() {
    const weddingHeader = document.querySelector('.wedding-header');
    const weddingNav = document.querySelector('.wedding-floating-nav');
    const weddingSections = document.querySelectorAll('main section[id]');
    const weddingNavLinks = document.querySelectorAll('[data-nav-target]');

    const updateWeddingNav = () => {
        if (!weddingHeader || !weddingNav) return;
        const shouldShowNav = window.scrollY > 90;
        weddingHeader.classList.toggle('wedding-header-scrolled', window.scrollY > window.innerHeight * 0.65);
        weddingNav.classList.toggle('is-nav-visible', shouldShowNav);
        document.body.classList.toggle('nav-is-visible', shouldShowNav);
    };

    window.addEventListener('scroll', updateWeddingNav, { passive: true });
    updateWeddingNav();

    if (weddingSections.length > 0 && weddingNavLinks.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    const targetId = entry.target.id === 'countdown' ? 'home' : entry.target.id;
                    const hasMatch = Array.from(weddingNavLinks).some((link) => link.dataset.navTarget === targetId);
                    if (!hasMatch) return;

                    weddingNavLinks.forEach((link) => {
                        link.classList.toggle('wedding-nav-link-active', link.dataset.navTarget === targetId);
                    });
                });
            },
            { rootMargin: '-35% 0px -55% 0px' },
        );

        weddingSections.forEach((section) => observer.observe(section));
    }
}
