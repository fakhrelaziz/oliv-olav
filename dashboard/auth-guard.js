/**
 * Auth Guard: Proteksi Sesi Terpadu Admin Dashboard
 * Memeriksa status login sebelum merender halaman dan menangani proses logout bersih.
 */

(function () {
  // Normalisasi trailing slash agar link relatif sidebar (index.html, rsvp.html, dll) selalu mengarah ke /dashboard/...
  const currentPath = window.location.pathname;
  if (currentPath === '/dashboard') {
    window.location.replace('/dashboard/' + window.location.search + window.location.hash);
    return;
  }
  if (currentPath === '/kelola-konten' || currentPath === '/kelola-konten.html') {
    window.location.replace('/dashboard/kelola-konten.html' + window.location.search + window.location.hash);
    return;
  }

  // Hanya jalankan pengecekan jika bukan di halaman login
  const isLoginPage = currentPath.endsWith('login.html');

  async function checkAuthentication() {
    try {
      const res = await fetch('../api/auth.php?action=check', {
        headers: { 'Cache-Control': 'no-cache' }
      });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const data = await res.json();

      if (isLoginPage) {
        // Jika sudah login dan membuka login.html, arahkan langsung ke dashboard
        if (data.authenticated) {
          window.location.replace('index.html');
        }
      } else {
        // Jika di halaman dashboard tetapi belum terotentikasi, alihkan ke login
        if (!data.authenticated) {
          sessionStorage.setItem('redirect_after_login', window.location.href);
          window.location.replace('login.html');
          return;
        }

        // Tampilkan nama admin pada profile badge jika elemen tersedia
        if (data.user && data.user.name) {
          document.querySelectorAll('.admin-profile-name').forEach(el => {
            el.innerText = data.user.name;
          });
        }
      }
    } catch (e) {
      // Jika server offline / request gagal saat di halaman login, biarkan tetap di login
      if (!isLoginPage) {
        console.warn('Auth guard check failed, ensuring safety:', e);
      }
    }
  }

  // Jalankan segera
  checkAuthentication();
})();

/**
 * Global Logout Handler: Menghancurkan sesi di PHP server dan menghapus cookie
 */
async function handleLogout(e) {
  if (e) e.preventDefault();
  if (!confirm('Apakah Anda yakin ingin keluar dari Admin Panel?')) {
    return;
  }

  try {
    const res = await fetch('../api/auth.php?action=logout', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
    });
    const data = await res.json();
    if (data.redirect) {
      window.location.replace(data.redirect);
      return;
    }
  } catch (err) {
    console.warn('Logout API failed, forcing client redirect:', err);
  }
  window.location.replace('login.html');
}

/**
 * Normalisasi path aset agar mendukung Data URI, Blob, HTTP(S), absolute path (/...), dan relative path (../...).
 */
function normalizeAssetUrl(url, fallback = '') {
  const raw = String(url || '').trim();
  if (!raw) return fallback;
  if (
    raw.startsWith('data:') ||
    raw.startsWith('blob:') ||
    raw.startsWith('http://') ||
    raw.startsWith('https://') ||
    raw.startsWith('/') ||
    raw.startsWith('../')
  ) {
    return raw;
  }
  return '../' + raw.replace(/^\.?\//, '');
}

/**
 * Pasang handler onerror agar elemen <img> tidak pernah tampil rusak (broken image).
 */
function attachImageFallback(imgEl, fallbackUrl) {
  if (!imgEl || !fallbackUrl) return;
  const resolvedFallback = normalizeAssetUrl(fallbackUrl);
  imgEl.dataset.fallbackSrc = resolvedFallback;
  imgEl.onerror = function () {
    if (this.dataset.fallbackApplied === '1') return;
    this.dataset.fallbackApplied = '1';
    this.src = this.dataset.fallbackSrc || resolvedFallback;
  };
}

/**
 * Kompres gambar sisi client sebelum unggah agar aman di batas payload Vercel (4.5 MB) & hemat penyimpanan.
 */
async function compressImageForUpload(file, maxDimension = 1600, quality = 0.82) {
  if (!file || !file.type || !file.type.startsWith('image/') || file.type === 'image/gif') {
    return file;
  }
  // File sangat kecil (< 350 KB) tidak perlu dikompres kecuali dimensinya terlalu besar
  if (file.size <= 350 * 1024) {
    return file;
  }

  return new Promise((resolve) => {
    const img = new Image();
    const objUrl = URL.createObjectURL(file);
    img.onload = () => {
      URL.revokeObjectURL(objUrl);
      let { width, height } = img;
      if (width > maxDimension || height > maxDimension) {
        if (width >= height) {
          height = Math.round((height * maxDimension) / width);
          width = maxDimension;
        } else {
          width = Math.round((width * maxDimension) / height);
          height = maxDimension;
        }
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
          if (blob && (blob.size < file.size || file.size > 2.5 * 1024 * 1024)) {
            const baseName = (file.name || 'image').replace(/\.[^.]+$/, '');
            resolve(new File([blob], baseName + '.webp', { type: 'image/webp' }));
          } else {
            resolve(file);
          }
        },
        'image/webp',
        quality
      );
    };
    img.onerror = () => {
      URL.revokeObjectURL(objUrl);
      resolve(file);
    };
    img.src = objUrl;
  });
}

/**
 * Global Photo Upload Helper:
 * Mengunggah file foto langsung ke ../api/konten.php?action=upload, memperbarui preview gambar secara instan,
 * dan menyimpan path/Data URI hasil upload ke dalam input hidden.
 */
async function uploadPhotoFile(fileInput, hiddenInputId, previewImgId, statusId, sectionName = 'img') {
  const file = fileInput.files && fileInput.files[0];
  if (!file) return;

  const previewEl = previewImgId ? document.getElementById(previewImgId) : null;
  const hiddenEl = hiddenInputId ? document.getElementById(hiddenInputId) : null;
  const statusEl = statusId ? document.getElementById(statusId) : null;

  const prevHiddenVal = hiddenEl ? hiddenEl.value : '';
  const prevPreviewSrc = previewEl && previewEl.tagName === 'IMG' ? previewEl.getAttribute('src') || '' : '';
  const prevWasHidden = previewEl ? previewEl.classList.contains('hidden') : false;

  // Pratinjau lokal instan
  const localUrl = URL.createObjectURL(file);
  if (previewEl) {
    delete previewEl.dataset.fallbackApplied;
    if (previewEl.tagName === 'IMG') {
      previewEl.src = localUrl;
      previewEl.classList.remove('hidden');
    } else {
      previewEl.style.backgroundImage = `url('${localUrl}')`;
    }
  }

  if (statusEl) {
    statusEl.innerText = '⏳ Mengoptimalkan & mengunggah foto...';
    statusEl.className = 'text-[10px] font-medium text-amber-700 block mt-1';
  }

  try {
    const uploadFileObj = await compressImageForUpload(file);
    if (uploadFileObj.size > 3.8 * 1024 * 1024) {
      throw new Error('Ukuran gambar setelah kompresi masih di atas 3.8 MB. Gunakan gambar dengan ukuran lebih kecil.');
    }

    const formData = new FormData();
    formData.append('file', uploadFileObj, uploadFileObj.name || file.name);
    formData.append('section', sectionName);

    const res = await fetch('../api/konten.php?action=upload', {
      method: 'POST',
      body: formData
    });
    const json = await res.json();

    if (!res.ok || !json.success) {
      throw new Error(json.message || 'Gagal mengunggah foto.');
    }

    if (hiddenEl) {
      hiddenEl.value = json.url;
    }
    if (previewEl) {
      delete previewEl.dataset.fallbackApplied;
      if (previewEl.tagName === 'IMG') {
        previewEl.src = normalizeAssetUrl(json.url);
        previewEl.classList.remove('hidden');
      } else {
        previewEl.style.backgroundImage = `url('${normalizeAssetUrl(json.url)}')`;
      }
    }
    if (statusEl) {
      statusEl.innerText = '✓ Foto siap disimpan (' + file.name + ')';
      statusEl.className = 'text-[10px] font-semibold text-emerald-700 block mt-1';
    }
  } catch (err) {
    // Pulihkan state preview sebelumnya bila gagal
    if (hiddenEl) hiddenEl.value = prevHiddenVal;
    if (previewEl && previewEl.tagName === 'IMG') {
      if (prevPreviewSrc) {
        previewEl.src = prevPreviewSrc;
      } else {
        previewEl.removeAttribute('src');
      }
      if (prevWasHidden) previewEl.classList.add('hidden');
    }
    if (statusEl) {
      statusEl.innerText = '✕ Gagal unggah: ' + (err.message || 'Cek server');
      statusEl.className = 'text-[10px] font-semibold text-rose-600 block mt-1';
    }
    alert('Gagal mengunggah foto: ' + (err.message || 'Pastikan server PHP berjalan.'));
  } finally {
    URL.revokeObjectURL(localUrl);
    fileInput.value = '';
  }
}

/**
 * Reset 1 field foto pada section tertentu kembali ke asset default bawaan.
 */
async function resetPhotoFieldToDefault(sectionKey, fieldKey, defaultUrl, hiddenInputId, previewImgId, statusId) {
  if (!confirm('Kembalikan foto ini ke gambar default bawaan?')) return;
  try {
    const res = await fetch('../api/konten.php?action=reset_section', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      cache: 'no-store',
      body: JSON.stringify({ section_key: sectionKey, field: fieldKey })
    });
    const json = await res.json();
    if (!res.ok || !json.success) {
      throw new Error(json.message || 'Gagal mereset foto.');
    }
    const nextUrl = (json.data && json.data[fieldKey]) ? json.data[fieldKey] : defaultUrl;
    const hiddenEl = hiddenInputId ? document.getElementById(hiddenInputId) : null;
    const previewEl = previewImgId ? document.getElementById(previewImgId) : null;
    const statusEl = statusId ? document.getElementById(statusId) : null;
    if (hiddenEl) hiddenEl.value = nextUrl;
    if (previewEl) {
      delete previewEl.dataset.fallbackApplied;
      previewEl.src = normalizeAssetUrl(nextUrl, defaultUrl);
      previewEl.classList.remove('hidden');
    }
    if (statusEl) {
      statusEl.innerText = '✓ Foto dikembalikan ke default bawaan';
      statusEl.className = 'text-[10px] font-semibold text-moss block mt-1';
    }
  } catch (err) {
    alert(err.message || 'Gagal mereset foto ke default.');
  }
}

/**
 * Reset seluruh kustomisasi 1 section ke bawaan default.
 */
async function resetSectionToDefault(sectionKey, reloadFn, label = 'section ini') {
  if (!confirm(`Apakah Anda yakin ingin menghapus kustomisasi & mengembalikan ${label} ke konten default?`)) return;
  try {
    const res = await fetch('../api/konten.php?action=reset_section', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      cache: 'no-store',
      body: JSON.stringify({ section_key: sectionKey })
    });
    const json = await res.json();
    if (!res.ok || !json.success) {
      throw new Error(json.message || 'Gagal mereset section.');
    }
    if (typeof reloadFn === 'function') {
      await reloadFn();
    }
    alert(json.message || 'Konten berhasil dikembalikan ke default!');
  } catch (err) {
    alert(err.message || 'Terjadi kesalahan saat mereset section.');
  }
}


