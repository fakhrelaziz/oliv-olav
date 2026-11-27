export function trackInvitationVisit() {
    const mainEl = document.getElementById('integrated-content');
    const trackUrl = mainEl?.dataset.trackVisitUrl;
    if (!trackUrl) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(trackUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ page: window.location.pathname || '/' }),
    }).catch(() => {});
}
