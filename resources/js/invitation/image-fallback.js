export function initImageFallbacks() {
    document.querySelectorAll('img[data-fallback]').forEach((img) => {
        img.addEventListener('error', () => {
            if (img.dataset.fallbackApplied === '1') return;
            const fallback = img.getAttribute('data-fallback');
            if (!fallback) return;
            img.dataset.fallbackApplied = '1';
            img.src = fallback;
        });
    });
}
