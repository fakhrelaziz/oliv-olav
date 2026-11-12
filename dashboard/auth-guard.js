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
 * Global Photo Upload Helper:
 * Mengunggah file foto langsung ke ../api/upload.php, memperbarui preview gambar secara instan,
 * dan menyimpan path hasil upload ke dalam input hidden.
 */
async function uploadPhotoFile(fileInput, hiddenInputId, previewImgId, statusId, sectionName = 'img') {
  const file = fileInput.files && fileInput.files[0];
  if (!file) return;

  const previewEl = previewImgId ? document.getElementById(previewImgId) : null;
  const hiddenEl = hiddenInputId ? document.getElementById(hiddenInputId) : null;
  const statusEl = statusId ? document.getElementById(statusId) : null;

  // Pratinjau lokal instan
  const localUrl = URL.createObjectURL(file);
  if (previewEl) {
    if (previewEl.tagName === 'IMG') {
      previewEl.src = localUrl;
      previewEl.classList.remove('hidden');
    } else {
      previewEl.style.backgroundImage = `url('${localUrl}')`;
    }
  }

  if (statusEl) {
    statusEl.innerText = '⏳ Mengunggah foto ke server...';
    statusEl.className = 'text-[10px] font-medium text-amber-700 block mt-1';
  }

  const formData = new FormData();
  formData.append('file', file);
  formData.append('section', sectionName);

  try {
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
      if (previewEl.tagName === 'IMG') {
        previewEl.src = json.url;
      } else {
        previewEl.style.backgroundImage = `url('${json.url}')`;
      }
    }
    if (statusEl) {
      statusEl.innerText = '✓ Foto siap disimpan (' + file.name + ')';
      statusEl.className = 'text-[10px] font-semibold text-emerald-700 block mt-1';
    }
  } catch (err) {
    if (statusEl) {
      statusEl.innerText = '✕ Gagal unggah: ' + (err.message || 'Cek server');
      statusEl.className = 'text-[10px] font-semibold text-rose-600 block mt-1';
    }
    alert('Gagal mengunggah foto: ' + (err.message || 'Pastikan server PHP berjalan.'));
  }
}


