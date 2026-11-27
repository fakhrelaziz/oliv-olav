let hideTimer = null;

export function showAdminToast(message) {
    const toast = document.getElementById('adminToast');
    const msgEl = document.getElementById('adminToastMsg');
    if (!toast || !msgEl) return;

    msgEl.textContent = message;
    toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');

    if (hideTimer !== null) {
        clearTimeout(hideTimer);
    }

    hideTimer = window.setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
    }, 3400);
}
