<?php
// ====================================================================
// API TAMU: CRUD & Statistik Data Tamu Undangan
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
// 1. GET: Ambil Daftar Tamu & Statistik Kehadiran
// --------------------------------------------------------------------
if ($method === 'GET') {
    $q = trim($_GET['q'] ?? '');
    $status = trim($_GET['status'] ?? 'all');

    try {
        // Query data tamu
        $sql = "SELECT id, user_id, slug, nama, no_telepon, alamat, jumlah_undangan, status, jumlah_hadir_aktual, is_terkirim, created_at FROM `tamu` WHERE 1=1";
        $params = [];

        if (!empty($q)) {
            $sql .= " AND (nama LIKE :q OR no_telepon LIKE :q)";
            $params[':q'] = "%$q%";
        }

        if (!empty($status) && $status !== 'all') {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $tamuList = $stmt->fetchAll();

        // Hitung statistik keseluruhan tamu (tanpa terpengaruh filter pencarian)
        $statStmt = $pdo->query("
            SELECT 
                COUNT(*) as total_tamu,
                SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'Tidak Hadir' THEN 1 ELSE 0 END) as tidak_hadir,
                SUM(CASE WHEN status = 'Belum Konfirmasi' THEN 1 ELSE 0 END) as belum_konfirmasi,
                SUM(jumlah_undangan) as total_pax,
                SUM(CASE WHEN status = 'Hadir' THEN jumlah_hadir_aktual ELSE 0 END) as hadir_pax
            FROM `tamu`
        ");
        $stats = $statStmt->fetch();

        $totalTamu = (int)($stats['total_tamu'] ?? 0);
        $hadir = (int)($stats['hadir'] ?? 0);
        $tidakHadir = (int)($stats['tidak_hadir'] ?? 0);
        $belum = (int)($stats['belum_konfirmasi'] ?? 0);

        $pctHadir = $totalTamu > 0 ? round(($hadir / $totalTamu) * 100, 1) : 0;
        $pctTidakHadir = $totalTamu > 0 ? round(($tidakHadir / $totalTamu) * 100, 1) : 0;
        $pctBelum = $totalTamu > 0 ? round(($belum / $totalTamu) * 100, 1) : 0;

        jsonResponse([
            'success' => true,
            'total' => count($tamuList),
            'data' => $tamuList,
            'stats' => [
                'total_tamu' => $totalTamu,
                'hadir' => $hadir,
                'tidak_hadir' => $tidakHadir,
                'belum_konfirmasi' => $belum,
                'pct_hadir' => $pctHadir,
                'pct_tidak_hadir' => $pctTidakHadir,
                'pct_belum_konfirmasi' => $pctBelum,
                'total_pax' => (int)($stats['total_pax'] ?? 0),
                'hadir_pax' => (int)($stats['hadir_pax'] ?? 0)
            ]
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 2. POST (action=create atau tanpa action): Tambah Tamu Baru
// --------------------------------------------------------------------
if ($method === 'POST' && ($action === 'create' || empty($action))) {
    $input = getJsonInput();
    $nama = trim($input['nama'] ?? '');
    $pax = (int)($input['jumlah_undangan'] ?? $input['pax'] ?? 2);
    $status = trim($input['status'] ?? 'Belum Konfirmasi');
    $telepon = trim($input['no_telepon'] ?? '');
    $alamat = trim($input['alamat'] ?? '');

    if (empty($nama)) {
        jsonResponse(['success' => false, 'message' => 'Nama tamu tidak boleh kosong.'], 400);
    }

    if ($pax < 1) $pax = 1;

    try {
        // Buat slug unik
        $baseSlug = slugify($nama);
        $slug = $baseSlug;
        $counter = 1;
        while (true) {
            $check = $pdo->prepare("SELECT id FROM `tamu` WHERE `slug` = :slug LIMIT 1");
            $check->execute([':slug' => $slug]);
            if (!$check->fetch()) break;
            $slug = $baseSlug . '-' . (++$counter);
        }

        $stmt = $pdo->prepare("
            INSERT INTO `tamu` (`user_id`, `slug`, `nama`, `no_telepon`, `alamat`, `jumlah_undangan`, `status`, `jumlah_hadir_aktual`)
            VALUES (1, :slug, :nama, :no_telepon, :alamat, :jumlah_undangan, :status, :hadir_aktual)
        ");

        $hadirAktual = ($status === 'Hadir') ? $pax : 0;
        $stmt->execute([
            ':slug' => $slug,
            ':nama' => $nama,
            ':no_telepon' => $telepon,
            ':alamat' => $alamat,
            ':jumlah_undangan' => $pax,
            ':status' => $status,
            ':hadir_aktual' => $hadirAktual
        ]);

        $newId = (int)$pdo->lastInsertId();

        jsonResponse([
            'success' => true,
            'message' => "Tamu \"$nama\" berhasil ditambahkan!",
            'data' => [
                'id' => $newId,
                'slug' => $slug,
                'nama' => $nama,
                'jumlah_undangan' => $pax,
                'status' => $status,
                'link_undangan' => "/integrated/index.html?to=" . urlencode($nama)
            ]
        ], 201);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 3. POST (action=update atau PUT): Update Data Tamu
// --------------------------------------------------------------------
if ($method === 'PUT' || ($method === 'POST' && $action === 'update')) {
    $input = getJsonInput();
    $id = (int)($input['id'] ?? 0);
    $nama = trim($input['nama'] ?? '');
    $pax = (int)($input['jumlah_undangan'] ?? $input['pax'] ?? 2);
    $status = trim($input['status'] ?? 'Belum Konfirmasi');
    $telepon = trim($input['no_telepon'] ?? '');

    if ($id <= 0 || empty($nama)) {
        jsonResponse(['success' => false, 'message' => 'ID dan nama tamu wajib disertakan.'], 400);
    }

    try {
        $hadirAktual = ($status === 'Hadir') ? $pax : 0;
        $stmt = $pdo->prepare("
            UPDATE `tamu` 
            SET `nama` = :nama, 
                `jumlah_undangan` = :pax, 
                `status` = :status,
                `jumlah_hadir_aktual` = :hadir_aktual,
                `no_telepon` = :telepon
            WHERE `id` = :id
        ");

        $stmt->execute([
            ':nama' => $nama,
            ':pax' => $pax,
            ':status' => $status,
            ':hadir_aktual' => $hadirAktual,
            ':telepon' => $telepon,
            ':id' => $id
        ]);

        jsonResponse([
            'success' => true,
            'message' => "Data tamu \"$nama\" berhasil diperbarui!",
            'data' => [
                'id' => $id,
                'nama' => $nama,
                'jumlah_undangan' => $pax,
                'status' => $status
            ]
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 4. POST (action=delete atau DELETE): Hapus Tamu
// --------------------------------------------------------------------
if ($method === 'DELETE' || ($method === 'POST' && $action === 'delete')) {
    $input = getJsonInput();
    $id = (int)($input['id'] ?? $_GET['id'] ?? 0);

    if ($id <= 0) {
        jsonResponse(['success' => false, 'message' => 'ID tamu tidak valid.'], 400);
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM `tamu` WHERE `id` = :id");
        $stmt->execute([':id' => $id]);

        jsonResponse([
            'success' => true,
            'message' => 'Data tamu berhasil dihapus.'
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Metode request atau action tidak didukung.'], 405);

