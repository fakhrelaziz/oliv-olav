import { showAdminToast } from './toast';

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function getUploadUrl() {
    return document.querySelector('meta[name="upload-url"]')?.getAttribute('content') || '/admin/media/upload';
}

async function compressImageToWebp(file) {
    if (!file || file.type === 'image/gif' || file.size < 250 * 1024) {
        return file;
    }

    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (ev) => {
            const img = new Image();
            img.onload = () => {
                const maxDim = 1600;
                let width = img.naturalWidth || img.width;
                let height = img.naturalHeight || img.height;

                if (width > maxDim || height > maxDim) {
                    const ratio = Math.min(maxDim / width, maxDim / height);
                    width = Math.round(width * ratio);
                    height = Math.round(height * ratio);
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    resolve(file);
                    return;
                }
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    (blob) => {
                        if (!blob || blob.size >= file.size) {
                            resolve(file);
                            return;
                        }
                        const baseName = file.name.replace(/\.[^.]+$/, '') || 'upload';
                        resolve(new File([blob], `${baseName}.webp`, { type: 'image/webp' }));
                    },
                    'image/webp',
                    0.84,
                );
            };
            img.onerror = () => resolve(file);
            img.src = ev.target?.result;
        };
        reader.onerror = () => resolve(file);
        reader.readAsDataURL(file);
    });
}

export function initMediaUploads() {
    // Trigger hidden file inputs cleanly
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-upload-trigger]');
        if (!trigger) return;
        const idBase = trigger.getAttribute('data-upload-trigger');
        const fileInput = document.getElementById(`file_${idBase}`);
        fileInput?.click();
    });

    // Handle file upload with optional WebP compression
    document.addEventListener('change', async (e) => {
        const fileInput = e.target.closest('[data-upload-input]');
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) return;

        const file = fileInput.files[0];
        const targetInput = document.getElementById(fileInput.dataset.targetInput || '');
        const targetPreview = document.getElementById(fileInput.dataset.targetPreview || '');
        const targetStatus = document.getElementById(fileInput.dataset.targetStatus || '');
        const prefix = fileInput.dataset.uploadPrefix || 'img';

        if (targetStatus) {
            targetStatus.textContent = 'Mengompresi & mengunggah foto...';
            targetStatus.className = 'text-[10px] text-moss font-medium block mt-1';
        }

        try {
            const preparedFile = await compressImageToWebp(file);
            const formData = new FormData();
            formData.append('image', preparedFile);
            formData.append('section', prefix);

            const response = await fetch(getUploadUrl(), {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: formData,
            });

            const json = await response.json().catch(() => ({}));
            if (!response.ok || !json.success || !json.url) {
                const firstError = json.errors ? Object.values(json.errors).flat()[0] : null;
                throw new Error(firstError || json.message || 'Gagal mengunggah gambar.');
            }

            if (targetInput) {
                targetInput.value = json.url;
            }
            if (targetPreview) {
                delete targetPreview.dataset.fallbackApplied;
                targetPreview.src = json.url;
                targetPreview.classList.remove('hidden');
                const placeholder = targetPreview.parentElement?.querySelector('[data-upload-placeholder]');
                placeholder?.classList.add('hidden');
            }
            if (targetStatus) {
                targetStatus.textContent = `Berhasil diunggah (${json.filename || 'tersimpan'})`;
                targetStatus.className = 'text-[10px] text-emerald-700 font-medium block mt-1';
            }

            showAdminToast('Foto berhasil diunggah! Klik Simpan untuk menyimpan perubahan form.');
        } catch (err) {
            if (targetStatus) {
                targetStatus.textContent = err.message || 'Upload gagal.';
                targetStatus.className = 'text-[10px] text-rose-600 font-medium block mt-1';
            }
            showAdminToast(err.message || 'Gagal mengunggah gambar.');
        } finally {
            fileInput.value = '';
        }
    });

    // Individual photo reset button
    document.addEventListener('click', async (e) => {
        const resetBtn = e.target.closest('[data-reset-photo]');
        if (!resetBtn) return;

        const defaultUrl = resetBtn.dataset.defaultUrl || '';
        const targetInput = document.getElementById(resetBtn.dataset.targetInput || '');
        const targetPreview = document.getElementById(resetBtn.dataset.targetPreview || '');
        const targetStatus = document.getElementById(resetBtn.dataset.targetStatus || '');
        const endpoint = resetBtn.dataset.resetEndpoint || '';
        const field = resetBtn.dataset.resetField || '';

        if (!window.confirm('Kembalikan foto ini ke gambar bawaan (default)?')) {
            return;
        }

        if (endpoint && field) {
            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                    body: JSON.stringify({ field }),
                });
                const json = await res.json().catch(() => ({}));
                if (!res.ok || !json.success) {
                    throw new Error(json.message || 'Gagal mereset foto.');
                }
            } catch (err) {
                showAdminToast(err.message || 'Gagal mereset foto.');
                return;
            }
        }

        if (targetInput) targetInput.value = defaultUrl;
        if (targetPreview) {
            delete targetPreview.dataset.fallbackApplied;
            targetPreview.src = defaultUrl;
        }
        if (targetStatus) {
            targetStatus.textContent = 'Dikembalikan ke foto default.';
            targetStatus.className = 'text-[10px] text-inkMuted block mt-1';
        }
        showAdminToast('Foto berhasil dikembalikan ke default.');
    });
}
