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
// 1. GET: Ambil Foto Galeri Berdasarkan Kategori
// --------------------------------------------------------------------
if ($method === 'GET') {
    $category = trim($_GET['category'] ?? 'all');

    try {
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

