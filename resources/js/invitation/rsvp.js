function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = String(text ?? '');
    return div.innerHTML;
}

export function initRsvpForm() {
    const form = document.getElementById('form-kirim-ucapan');
    const wishesContainer = document.getElementById('wishes-feed-container');
    const feedbackEl = document.getElementById('rsvp-feedback');
    if (!form) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const showFeedback = (message, isError = false) => {
        if (!feedbackEl) return;
        feedbackEl.textContent = message;
        feedbackEl.classList.remove('hidden', 'bg-moss/10', 'text-moss', 'bg-rose-100', 'text-rose-800');
        feedbackEl.classList.add(
            isError ? 'bg-rose-100' : 'bg-moss/10',
            isError ? 'text-rose-800' : 'text-moss',
        );
    };

    const prependWishCard = (wish) => {
        if (!wishesContainer) return;
        const emptyNotice = wishesContainer.querySelector('[data-empty-wishes]');
        if (emptyNotice) emptyNotice.remove();

        const card = document.createElement('div');
        card.className = 'bg-warmwhite/75 p-5 rounded-xl shadow-sm border border-moss/10';
        card.innerHTML = `
            <p class="font-serif font-semibold text-sm mb-0.5 text-moss">${escapeHtml(wish.nama_pengirim)}</p>
            <p class="text-[11px] text-moss/60 mb-2.5">${escapeHtml(wish.formatted_date || 'Baru saja')}</p>
            <p class="text-xs sm:text-sm text-moss/85 leading-relaxed">${escapeHtml(wish.pesan_ucapan)}</p>
        `;
        wishesContainer.prepend(card);
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const namaInput = document.getElementById('input-nama-tamu');
        const pesanInput = document.getElementById('input-pesan-tamu');
        const attendanceRadio = form.querySelector('input[name="attendance"]:checked');
        const submitBtn = form.querySelector('button[type="submit"]');

        const nama = namaInput?.value.trim() || '';
        const pesan = pesanInput?.value.trim() || '';
        const status = attendanceRadio?.value || 'Hadir';

        if (!nama || !pesan) {
            showFeedback('Mohon lengkapi nama dan doa ucapan Anda.', true);
            return;
        }

        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim ucapan...';
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    nama_pengirim: nama,
                    pesan_ucapan: pesan,
                    status_kehadiran: status,
                }),
            });

            const json = await response.json().catch(() => ({}));
            if (!response.ok || !json.success) {
                const firstError = json.errors ? Object.values(json.errors).flat()[0] : null;
                showFeedback(firstError || json.message || 'Gagal mengirim ucapan. Silakan coba lagi.', true);
                return;
            }

            if (json.data) {
                prependWishCard(json.data);
            }
            if (pesanInput) pesanInput.value = '';
            showFeedback(json.message || 'Terima kasih! Ucapan Anda telah tersimpan.', false);
        } catch {
            showFeedback('Terjadi gangguan jaringan. Silakan coba beberapa saat lagi.', true);
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        }
    });
}
