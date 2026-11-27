export function initQrisAndGift() {
    document.querySelectorAll('[data-copy-target]').forEach((copyBtn) => {
        copyBtn.addEventListener('click', async () => {
            const targetId = copyBtn.getAttribute('data-copy-target');
            const numberEl = targetId ? document.getElementById(targetId) : null;
            if (!numberEl) return;

            const rawNumber = numberEl.textContent.replace(/[^0-9A-Za-z]/g, '').trim();
            if (!rawNumber) return;

            const copyLabel = copyBtn.querySelector('[data-copy-label]');

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
                const range = document.createRange();
                range.selectNodeContents(numberEl);
                const sel = window.getSelection();
                sel?.removeAllRanges();
                sel?.addRange(range);
            }
        });
    });
}
