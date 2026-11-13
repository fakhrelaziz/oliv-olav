<?php
// ====================================================================
// API STORY: Manajemen Perjalanan Cinta (Our Story)
// ====================================================================

require_once __DIR__ . '/lib/db.php';

if (!$pdo) {
    jsonResponse([
        'success' => false,
        'message' => 'Koneksi ke database gagal. Pastikan MySQL Laragon aktif.'
    ], 500);
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// --------------------------------------------------------------------
// 1. GET: Ambil Daftar Milestone Cerita
// --------------------------------------------------------------------
if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT id, user_id, judul, tanggal_momen, deskripsi, foto_url, urutan, created_at, updated_at FROM `cerita_cinta` ORDER BY urutan ASC, id ASC");
        $stories = $stmt->fetchAll();

        jsonResponse([
            'success' => true,
            'total' => count($stories),
            'data' => $stories
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 2. POST (action=create atau tanpa action): Tambah Milestone Baru
// --------------------------------------------------------------------
if ($method === 'POST' && ($action === 'create' || empty($action))) {
    $input = getJsonInput();
    $judul = trim($input['judul'] ?? $input['title'] ?? '');
    $tanggal = trim($input['tanggal_momen'] ?? $input['date'] ?? '');
    $deskripsi = trim($input['deskripsi'] ?? $input['description'] ?? '');
    $fotoUrl = trim($input['foto_url'] ?? $input['photo_url'] ?? 'aset/section3_fotopengantin.webp');
    $urutan = (int)($input['urutan'] ?? 1);

    if (empty($judul) || empty($deskripsi)) {
        jsonResponse(['success' => false, 'message' => 'Judul dan deskripsi cerita wajib diisi.'], 400);
    }

    ensureFotoUrlLongText($pdo);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO `cerita_cinta` (`user_id`, `judul`, `tanggal_momen`, `deskripsi`, `foto_url`, `urutan`)
            VALUES (1, :judul, :tanggal, :deskripsi, :foto_url, :urutan)
        ");
        $stmt->execute([
            ':judul' => $judul,
            ':tanggal' => $tanggal,
            ':deskripsi' => $deskripsi,
            ':foto_url' => $fotoUrl,
            ':urutan' => $urutan
        ]);

        $newId = (int)$pdo->lastInsertId();

        jsonResponse([
            'success' => true,
            'message' => "Cerita \"$judul\" berhasil ditambahkan!",
            'data' => [
                'id' => $newId,
                'judul' => $judul,
                'tanggal_momen' => $tanggal,
                'deskripsi' => $deskripsi,
                'foto_url' => $fotoUrl,
                'urutan' => $urutan
            ]
        ], 201);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 3. POST (action=update atau PUT): Ubah Milestone
// --------------------------------------------------------------------
if ($method === 'PUT' || ($method === 'POST' && $action === 'update')) {
    $input = getJsonInput();
    $id = (int)($input['id'] ?? 0);
    $judul = trim($input['judul'] ?? '');
    $tanggal = trim($input['tanggal_momen'] ?? '');
    $deskripsi = trim($input['deskripsi'] ?? '');
    $fotoUrl = trim($input['foto_url'] ?? '');

    if ($id <= 0 || empty($judul)) {
        jsonResponse(['success' => false, 'message' => 'ID dan judul cerita wajib diisi.'], 400);
    }

    ensureFotoUrlLongText($pdo);

    try {
        $stmtOld = $pdo->prepare("SELECT `foto_url` FROM `cerita_cinta` WHERE `id` = :id LIMIT 1");
        $stmtOld->execute([':id' => $id]);
        $oldUrl = (string)$stmtOld->fetchColumn();
        $finalFoto = $fotoUrl !== '' ? $fotoUrl : $oldUrl;

        $stmt = $pdo->prepare("
            UPDATE `cerita_cinta` 
            SET `judul` = :judul, `tanggal_momen` = :tanggal, `deskripsi` = :deskripsi, `foto_url` = :foto 
            WHERE `id` = :id
        ");
        $stmt->execute([
            ':judul' => $judul,
            ':tanggal' => $tanggal,
            ':deskripsi' => $deskripsi,
            ':foto' => $finalFoto,
            ':id' => $id
        ]);

        if ($oldUrl !== '' && $oldUrl !== $finalFoto) {
            cleanupLocalUploadIfUnused($oldUrl, $pdo);
        }

        jsonResponse([
            'success' => true,
            'message' => 'Cerita berhasil diperbarui!'
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 4. POST (action=delete atau DELETE): Hapus Milestone
// --------------------------------------------------------------------
if ($method === 'DELETE' || ($method === 'POST' && $action === 'delete')) {
    $input = getJsonInput();
    $id = (int)($input['id'] ?? $_GET['id'] ?? 0);

    if ($id <= 0) {
        jsonResponse(['success' => false, 'message' => 'ID cerita tidak valid.'], 400);
    }

    try {
        $stmtOld = $pdo->prepare("SELECT `foto_url` FROM `cerita_cinta` WHERE `id` = :id LIMIT 1");
        $stmtOld->execute([':id' => $id]);
        $oldUrl = (string)$stmtOld->fetchColumn();

        $stmt = $pdo->prepare("DELETE FROM `cerita_cinta` WHERE `id` = :id");
        $stmt->execute([':id' => $id]);

        if ($oldUrl !== '') {
            cleanupLocalUploadIfUnused($oldUrl, $pdo);
        }

        jsonResponse([
            'success' => true,
            'message' => 'Cerita berhasil dihapus.'
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Metode request tidak diizinkan.'], 405);

