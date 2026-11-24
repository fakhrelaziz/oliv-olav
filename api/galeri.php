<?php
// ====================================================================
// API GALERI: Manajemen Foto Galeri Pernikahan
// ====================================================================

require_once __DIR__ . '/lib/db.php';

if (!$pdo) {
    jsonResponse([
        'success' => false,
        'message' => 'Koneksi ke database gagal. Pastikan MySQL Laragon aktif.'
    ], 500);
}

$method = $_SERVER['REQUEST_METHOD'];

// --------------------------------------------------------------------
// 1. GET: Ambil Foto Galeri Berdasarkan Kategori (dengan Auto-Upgrade Seeder Lama)
// --------------------------------------------------------------------
if ($method === 'GET') {
    $category = trim($_GET['category'] ?? 'all');

    try {
        // Jika database masih menyimpan seeder lama atau path asetV2/, upgrade otomatis ke path aset/ terbaru
        $legacyCheck = $pdo->query("SELECT COUNT(*) FROM `galeri_foto` WHERE (`judul` = 'Momen Bersama 1' AND `foto_url` = 'aset/section5_galerifoto.webp') OR (`foto_url` LIKE '%asetV2/%')")->fetchColumn();
        if ((int)$legacyCheck > 0) {
            $curatedDefaults = [
                [1, 'Melangkah Bersama di Bawah Rindang Pepohonan', 'prewedding', 'aset/section5_galerifoto(5).webp', 'Melangkah Bersama di Bawah Rindang Pepohonan', 1, 1],
                [2, 'Simbol Ikatan Suci & Janji Setia', 'acara', 'aset/cincin_tunangan_hd.webp', 'Simbol Ikatan Suci & Janji Setia', 2, 0],
                [3, 'Genggaman Hangat Menuju Hari Bahagia', 'prewedding', 'aset/foto_hd_soft.webp', 'Genggaman Hangat Menuju Hari Bahagia', 3, 1],
                [4, 'Menatap Langit & Merajut Masa Depan', 'prewedding', 'aset/section5_galerifoto(9).webp', 'Menatap Langit & Merajut Masa Depan', 4, 0],
                [5, 'Saling Menjaga dalam Setiap Langkah', 'prewedding', 'aset/langit_gandengan_hd.webp', 'Saling Menjaga dalam Setiap Langkah', 5, 0],
                [6, 'Bersandar Tenang Menatap Hari Esok', 'keseharian', 'aset/cover_hd.webp', 'Bersandar Tenang Menatap Hari Esok', 6, 0],
                [7, 'Genggaman Tangan yang Tak Pernah Lepas', 'acara', 'aset/section2_fotopengantin.webp', 'Genggaman Tangan yang Tak Pernah Lepas', 7, 0],
                [8, 'Tawa Hangat di Hamparan Padang Rumput', 'keseharian', 'aset/hd_3.webp', 'Tawa Hangat di Hamparan Padang Rumput', 8, 1],
                [9, 'A Chapter of Us — Lembaran Kisah Kami', 'acara', 'aset/section5_galerifoto(3).webp', 'A Chapter of Us — Lembaran Kisah Kami', 9, 0],
                [10, 'Potret Kasih di Bawah Langit Teduh', 'prewedding', 'aset/section5_galerifoto(7)webp.webp', 'Potret Kasih di Bawah Langit Teduh', 10, 1],
                [11, 'Picapica Photobooth — Sweet Smiles', 'keseharian', 'aset/photobooth_strip_1.webp', 'Picapica Studio · 09/23/2026 02:59 PM', 11, 0],
                [12, 'Picapica Photobooth — Peace & Dimples', 'keseharian', 'aset/photobooth_strip_2.webp', 'Picapica Studio · 09/23/2026 03:02 PM', 12, 0],
                [13, 'Picapica Photobooth — Classic Noir Frame', 'keseharian', 'aset/photobooth_strip_3.webp', 'Picapica Studio · Noir Edition 03:02 PM', 13, 0],
                [14, 'Picapica Photobooth — Playful & Waving', 'keseharian', 'aset/photobooth_strip_4.webp', 'Picapica Studio · 09/23/2026 03:06 PM', 14, 0],
            ];
            $upStmt = $pdo->prepare("
                INSERT INTO `galeri_foto` (`id`, `user_id`, `judul`, `kategori`, `foto_url`, `keterangan`, `urutan`, `is_featured`)
                VALUES (:id, 1, :judul, :kategori, :url, :ket, :urutan, :feat)
                ON DUPLICATE KEY UPDATE
                  `judul` = VALUES(`judul`),
                  `kategori` = VALUES(`kategori`),
                  `foto_url` = VALUES(`foto_url`),
                  `keterangan` = VALUES(`keterangan`),
                  `urutan` = VALUES(`urutan`),
                  `is_featured` = VALUES(`is_featured`)
            ");
            foreach ($curatedDefaults as $row) {
                $upStmt->execute([
                    ':id' => $row[0],
                    ':judul' => $row[1],
                    ':kategori' => $row[2],
                    ':url' => $row[3],
                    ':ket' => $row[4],
                    ':urutan' => $row[5],
                    ':feat' => $row[6]
                ]);
            }
        }

        $sql = "SELECT id, user_id, judul, kategori, foto_url, keterangan, urutan, is_featured, created_at 
                FROM `galeri_foto` WHERE 1=1";
        $params = [];

        if (!empty($category) && $category !== 'all') {
            $sql .= " AND kategori = :cat";
            $params[':cat'] = $category;
        }

        $sql .= " ORDER BY urutan ASC, id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $photos = $stmt->fetchAll();

        jsonResponse([
            'success' => true,
            'total' => count($photos),
            'data' => $photos
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 2. POST (action=delete atau DELETE): Hapus Foto dari Galeri
// --------------------------------------------------------------------
$action = $_GET['action'] ?? '';

if ($method === 'DELETE' || ($method === 'POST' && $action === 'delete')) {
    $input = getJsonInput();
    $id = (int)($input['id'] ?? $_GET['id'] ?? 0);

    if ($id <= 0) {
        jsonResponse(['success' => false, 'message' => 'ID foto tidak valid.'], 400);
    }

    try {
        $stmtOld = $pdo->prepare("SELECT `foto_url` FROM `galeri_foto` WHERE `id` = :id LIMIT 1");
        $stmtOld->execute([':id' => $id]);
        $oldUrl = (string)$stmtOld->fetchColumn();

        $stmt = $pdo->prepare("DELETE FROM `galeri_foto` WHERE `id` = :id");
        $stmt->execute([':id' => $id]);

        if ($oldUrl !== '') {
            cleanupLocalUploadIfUnused($oldUrl, $pdo);
        }

        jsonResponse([
            'success' => true,
            'message' => 'Foto berhasil dihapus dari basis data!'
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 2B. POST (action=update) atau PUT: Edit / Ganti Foto di Galeri
// --------------------------------------------------------------------
if ($method === 'PUT' || ($method === 'POST' && $action === 'update')) {
    $input = getJsonInput();
    $id = (int)($input['id'] ?? $_GET['id'] ?? 0);
    $judul = trim($input['judul'] ?? $input['title'] ?? '');
    $kategori = trim($input['kategori'] ?? $input['category'] ?? 'prewedding');
    $fotoUrl = trim($input['foto_url'] ?? $input['url'] ?? '');
    $keterangan = trim($input['keterangan'] ?? $input['caption'] ?? '');
    $urutan = isset($input['urutan']) ? (int)$input['urutan'] : null;

    $allowedCats = ['prewedding', 'acara', 'keseharian', 'lainnya'];
    if (!in_array($kategori, $allowedCats, true)) {
        $kategori = 'prewedding';
    }

    if ($id <= 0 || empty($judul)) {
        jsonResponse(['success' => false, 'message' => 'ID dan judul foto wajib diisi.'], 400);
    }

    ensureFotoUrlLongText($pdo);

    try {
        $stmtOld = $pdo->prepare("SELECT `foto_url`, `urutan` FROM `galeri_foto` WHERE `id` = :id LIMIT 1");
        $stmtOld->execute([':id' => $id]);
        $existing = $stmtOld->fetch();
        if (!$existing) {
            jsonResponse(['success' => false, 'message' => 'Foto tidak ditemukan.'], 404);
        }

        $oldUrl = (string)$existing['foto_url'];
        $finalUrl = !empty($fotoUrl) ? $fotoUrl : $oldUrl;
        $finalUrutan = ($urutan !== null) ? $urutan : (int)$existing['urutan'];

        $stmt = $pdo->prepare("
            UPDATE `galeri_foto`
            SET `judul` = :judul,
                `kategori` = :kategori,
                `foto_url` = :url,
                `keterangan` = :keterangan,
                `urutan` = :urutan
            WHERE `id` = :id
        ");
        $stmt->execute([
            ':judul' => $judul,
            ':kategori' => $kategori,
            ':url' => $finalUrl,
            ':keterangan' => $keterangan !== '' ? $keterangan : null,
            ':urutan' => $finalUrutan,
            ':id' => $id
        ]);

        if ($oldUrl !== '' && $oldUrl !== $finalUrl) {
            cleanupLocalUploadIfUnused($oldUrl, $pdo);
        }

        jsonResponse([
            'success' => true,
            'message' => "Foto \"$judul\" berhasil diperbarui!",
            'data' => [
                'id' => $id,
                'judul' => $judul,
                'kategori' => $kategori,
                'foto_url' => $finalUrl,
                'keterangan' => $keterangan,
                'urutan' => $finalUrutan
            ]
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 3. POST: Tambah Foto Baru ke Galeri
// --------------------------------------------------------------------
if ($method === 'POST') {
    $input = getJsonInput();
    $judul = trim($input['judul'] ?? $input['title'] ?? 'Momen Spesial');
    $kategori = trim($input['kategori'] ?? $input['category'] ?? 'prewedding');
    $fotoUrl = trim($input['foto_url'] ?? $input['url'] ?? '');
    $keterangan = trim($input['keterangan'] ?? $input['caption'] ?? '');
    $urutan = isset($input['urutan']) ? (int)$input['urutan'] : 1;

    $allowedCats = ['prewedding', 'acara', 'keseharian', 'lainnya'];
    if (!in_array($kategori, $allowedCats, true)) {
        $kategori = 'prewedding';
    }

    if (empty($fotoUrl)) {
        jsonResponse(['success' => false, 'message' => 'Silakan unggah foto terlebih dahulu sebelum menyimpan.'], 400);
    }

    ensureFotoUrlLongText($pdo);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO `galeri_foto` (`user_id`, `judul`, `kategori`, `foto_url`, `keterangan`, `urutan`)
            VALUES (1, :judul, :kategori, :url, :keterangan, :urutan)
        ");
        $stmt->execute([
            ':judul' => $judul,
            ':kategori' => $kategori,
            ':url' => $fotoUrl,
            ':keterangan' => $keterangan !== '' ? $keterangan : null,
            ':urutan' => $urutan
        ]);

        jsonResponse([
            'success' => true,
            'message' => "Foto \"$judul\" berhasil ditambahkan ke basis data!",
            'data' => [
                'id' => (int)$pdo->lastInsertId(),
                'judul' => $judul,
                'kategori' => $kategori,
                'foto_url' => $fotoUrl,
                'keterangan' => $keterangan,
                'urutan' => $urutan
            ]
        ], 201);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Metode request tidak diizinkan.'], 405);

