import { showAdminToast } from './toast';

function openModalById(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModalById(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

export function initAdminUi() {
    // 1. Mobile sidebar drawer
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('adminSidebarBackdrop');
    const openSidebar = () => {
        sidebar?.classList.remove('-translate-x-full');
        backdrop?.classList.remove('hidden');
    };
    const closeSidebar = () => {
        sidebar?.classList.add('-translate-x-full');
        backdrop?.classList.add('hidden');
    };

    document.querySelectorAll('[data-sidebar-open]').forEach((btn) => btn.addEventListener('click', openSidebar));
    document.querySelectorAll('[data-sidebar-close]').forEach((btn) => btn.addEventListener('click', closeSidebar));

    // 2. Password visibility toggle on login
    document.addEventListener('click', (e) => {
        const toggleBtn = e.target.closest('[data-toggle-password]');
        if (!toggleBtn) return;
        const input = document.getElementById(toggleBtn.dataset.togglePassword || '');
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
    });

    // 3. Copy invite link helper
    document.addEventListener('click', async (e) => {
        const copyBtn = e.target.closest('[data-copy-link]');
        if (!copyBtn) return;
        const link = copyBtn.dataset.copyLink || window.location.origin;
        const label = copyBtn.dataset.copyLabel || 'Tautan undangan berhasil disalin!';
        try {
            await navigator.clipboard.writeText(link);
            showAdminToast(label);
        } catch {
            window.prompt('Salin tautan berikut:', link);
        }
    });

    // 4. Confirm dialogs on destructive / reset submit buttons
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;
        const msg = btn.getAttribute('data-confirm') || 'Apakah Anda yakin?';
        if (!window.confirm(msg)) {
            e.preventDefault();
        }
    });

    // 5. Generic open/close modals
    document.addEventListener('click', (e) => {
        const opener = e.target.closest('[data-modal-open]');
        if (opener) {
            openModalById(opener.dataset.modalOpen);
            return;
        }

        const closer = e.target.closest('[data-modal-close]');
        if (closer) {
            closeModalById(closer.dataset.modalClose);
            return;
        }

        // Backdrop click closes modal
        const modal = e.target.matches('[data-modal]') ? e.target : null;
        if (modal) {
            closeModalById(modal.id);
        }
    });

    window.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('[data-modal].flex').forEach((modal) => closeModalById(modal.id));
    });

    // 6. Populate Edit Modals from JSON data-edit-payload
    document.addEventListener('click', (e) => {
        const editBtn = e.target.closest('[data-edit-modal]');
        if (!editBtn) return;

        const modalId = editBtn.dataset.editModal;
        const actionUrl = editBtn.dataset.editAction || '#';
        let payload = {};
        try {
            payload = JSON.parse(editBtn.dataset.editPayload || '{}');
        } catch {
            payload = {};
        }

        const modal = document.getElementById(modalId);
        if (!modal) return;

        const form = modal.querySelector('[data-edit-form]');
        if (form) {
            form.setAttribute('action', actionUrl);
        }

        Object.entries(payload).forEach(([key, val]) => {
            const field = modal.querySelector(`[data-field="${key}"]`);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = Boolean(val);
                } else {
                    field.value = val ?? '';
                }
            }

            const preview = modal.querySelector(`[data-field-preview="${key}"]`);
            if (preview && typeof val === 'string' && val.trim() !== '') {
                delete preview.dataset.fallbackApplied;
                preview.src = val;
            }
        });

        openModalById(modalId);
    });

    // 7. Admin Gallery Lightbox Preview
    document.addEventListener('click', (e) => {
        const previewBtn = e.target.closest('[data-preview-image]');
        if (!previewBtn) return;

        const img = document.getElementById('adminLightboxImg');
        const cap = document.getElementById('adminLightboxCaption');
        if (img) img.src = previewBtn.dataset.previewImage || '';
        if (cap) cap.textContent = previewBtn.dataset.previewTitle || '';
        openModalById('adminLightboxModal');
    });

    // 8. Admin Gallery Category Filter Tabs
    const galleryFilterBtns = document.querySelectorAll('[data-filter-tabs] [data-gallery-filter]');
    galleryFilterBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const cat = btn.dataset.galleryFilter || 'all';
            galleryFilterBtns.forEach((b) => {
                b.className = 'px-4 py-2 rounded-xl text-xs font-semibold transition bg-cardBg text-inkAdmin hover:bg-cardMuted border border-borderSoft';
            });
            btn.className = 'px-4 py-2 rounded-xl text-xs font-semibold transition bg-moss text-adminBg shadow-sm';

            document.querySelectorAll('#adminGalleryGrid [data-gallery-item]').forEach((item) => {
                const itemCat = item.dataset.category || 'prewedding';
                item.style.display = cat === 'all' || itemCat === cat ? '' : 'none';
            });
        });
    });

    // 9. Instant client-side filter on current table page (while Enter submits full server search)
    document.querySelectorAll('[data-table-search]').forEach((input) => {
        input.addEventListener('input', () => {
            const tableId = input.dataset.tableSearch;
            const table = document.getElementById(tableId);
            if (!table) return;
            const q = input.value.trim().toLowerCase();
            table.querySelectorAll('tbody tr[data-search-row]').forEach((row) => {
                const hay = row.dataset.searchRow || '';
                row.style.display = q === '' || hay.includes(q) ? '' : 'none';
            });
        });
    });

    // 10. Image fallbacks in admin
    document.querySelectorAll('img[data-fallback]').forEach((img) => {
        img.addEventListener('error', () => {
            if (img.dataset.fallbackApplied === '1') return;
            const fb = img.getAttribute('data-fallback');
            if (!fb) return;
            img.dataset.fallbackApplied = '1';
            img.src = fb;
        });
    });
}
