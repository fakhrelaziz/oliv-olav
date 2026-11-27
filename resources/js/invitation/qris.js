export function initQrisAndGift() {
    const modal = document.getElementById('qris-zoom-modal');
    const openTriggers = document.querySelectorAll('[data-qris-open]');
    const closeTriggers = document.querySelectorAll('[data-qris-close]');

    const openModal = () => {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    const closeModal = () => {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    openTriggers.forEach((btn) => btn.addEventListener('click', openModal));
    closeTriggers.forEach((btn) => btn.addEventListener('click', closeModal));

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    }

    const copyBtn = document.getElementById('btn-copy-rekening');
    const copyLabel = document.getElementById('btn-copy-rekening-label');
    const numberEl = document.getElementById('rsvp-bank-number');

    if (copyBtn && numberEl) {
        copyBtn.addEventListener('click', async () => {
            const rawNumber = numberEl.textContent.replace(/[^0-9A-Za-z]/g, '').trim();
            if (!rawNumber) return;

            try {
                await navigator.clipboard.writeText(rawNumber);
                if (copyLabel) {
                    const orig = copyLabel.textContent;
                    copyLabel.textContent = 'Tersalin!';
                    window.setTimeout(() => {
                        copyLabel.textContent = orig;
                    }, 2000);
                }
            } catch {
                // Fallback selection
                const range = document.createRange();
                range.selectNodeContents(numberEl);
                const sel = window.getSelection();
                sel?.removeAllRanges();
                sel?.addRange(range);
            }
        });
    }
}
