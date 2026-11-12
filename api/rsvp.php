<?php
// ====================================================================
// API RSVP: Doa & Ucapan Tamu (Front Website & Dashboard)
// ====================================================================

require_once __DIR__ . '/db.inc';

if (!$pdo) {
    jsonResponse([
        'success' => false,
        'message' => 'Koneksi ke database gagal. Pastikan MySQL Laragon aktif.'
    ], 500);
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// --------------------------------------------------------------------
// 1. GET: Ambil Daftar Ucapan & Statistik Kehadiran
// --------------------------------------------------------------------
if ($method === 'GET') {
    $q = trim($_GET['q'] ?? '');
    $status = trim($_GET['status'] ?? 'all');
    $limit = (int)($_GET['limit'] ?? 50);

    try {
        $sql = "SELECT id, tamu_id, nama_pengirim, status_kehadiran, jumlah_kehadiran, pesan_ucapan, is_tampil, created_at 
                FROM `doa_ucapan` 
                WHERE is_tampil = 1";
        $params = [];

        if (!empty($q)) {
            $sql .= " AND (nama_pengirim LIKE :q OR pesan_ucapan LIKE :q)";
            $params[':q'] = "%$q%";
        }

        if (!empty($status) && $status !== 'all') {
            $sql .= " AND status_kehadiran = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY id DESC LIMIT :limit";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $wishes = $stmt->fetchAll();

        // Format tanggal ramah pengguna (contoh: "25 Okt 2026")
        foreach ($wishes as &$w) {
            $time = strtotime($w['created_at']);
            $w['formatted_date'] = date('d M Y', $time);
            $w['initials'] = strtoupper(substr($w['nama_pengirim'], 0, 2));
        }

        // Ambil statistik ringkasan ucapan & konfirmasi
        $statStmt = $pdo->query("
            SELECT 
                COUNT(*) as total_ucapan,
                SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status_kehadiran = 'Tidak Hadir' THEN 1 ELSE 0 END) as tidak_hadir,
                SUM(CASE WHEN status_kehadiran = 'Masih Ragu' THEN 1 ELSE 0 END) as masih_ragu
            FROM `doa_ucapan`
            WHERE is_tampil = 1
        ");
        $stats = $statStmt->fetch();

        $totalUcapan = (int)($stats['total_ucapan'] ?? 0);
        $hadir = (int)($stats['hadir'] ?? 0);
        $tidakHadir = (int)($stats['tidak_hadir'] ?? 0);
        $ragu = (int)($stats['masih_ragu'] ?? 0);

        $pctHadir = $totalUcapan > 0 ? round(($hadir / $totalUcapan) * 100, 1) : 0;
        $pctTidakHadir = $totalUcapan > 0 ? round(($tidakHadir / $totalUcapan) * 100, 1) : 0;
        $pctRagu = $totalUcapan > 0 ? round(($ragu / $totalUcapan) * 100, 1) : 0;

        jsonResponse([
            'success' => true,
            'total' => count($wishes),
            'data' => $wishes,
            'stats' => [
                'total_ucapan' => $totalUcapan,
                'hadir' => $hadir,
                'tidak_hadir' => $tidakHadir,
                'masih_ragu' => $ragu,
                'pct_hadir' => $pctHadir,
                'pct_tidak_hadir' => $pctTidakHadir,
                'pct_masih_ragu' => $pctRagu
            ]
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 2. POST (action=create atau tanpa action): Kirim Doa & Ucapan Baru
// --------------------------------------------------------------------
if ($method === 'POST' && ($action === 'create' || empty($action))) {
    $input = getJsonInput();
    $nama = trim($input['nama_pengirim'] ?? $input['nama'] ?? '');
    $pesan = trim($input['pesan_ucapan'] ?? $input['pesan'] ?? $input['ucapan'] ?? '');
    $status = trim($input['status_kehadiran'] ?? $input['attendance'] ?? $input['status'] ?? 'Hadir');
    $pax = (int)($input['jumlah_kehadiran'] ?? $input['pax'] ?? 1);
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';

    if (empty($nama) || empty($pesan)) {
        jsonResponse([
            'success' => false, 
            'message' => 'Nama dan ucapan/doa wajib diisi.'
        ], 400);
    }

    // Normalisasi status
    if ($status === 'on' || empty($status)) $status = 'Hadir';
    if (!in_array($status, ['Hadir', 'Tidak Hadir', 'Masih Ragu'])) {
        $status = 'Hadir';
    }

    try {
        // Cek apakah tamu sudah terdaftar berdasarkan nama
        $tamuStmt = $pdo->prepare("SELECT id FROM `tamu` WHERE `nama` LIKE :nama LIMIT 1");
        $tamuStmt->execute([':nama' => $nama]);
        $tamu = $tamuStmt->fetch();
        $tamuId = $tamu ? (int)$tamu['id'] : null;

        // Simpan ucapan ke tabel doa_ucapan
        $stmt = $pdo->prepare("
            INSERT INTO `doa_ucapan` (`tamu_id`, `nama_pengirim`, `status_kehadiran`, `jumlah_kehadiran`, `pesan_ucapan`, `is_tampil`, `ip_address`)
            VALUES (:tamu_id, :nama, :status, :pax, :pesan, 1, :ip)
        ");

        $stmt->execute([
            ':tamu_id' => $tamuId,
            ':nama' => $nama,
            ':status' => $status,
            ':pax' => $pax,
            ':pesan' => $pesan,
            ':ip' => $ip
        ]);

        $newId = (int)$pdo->lastInsertId();

        // Jika tamu terdaftar, update status kehadirannya di tabel tamu
        if ($tamuId) {
            $updateTamu = $pdo->prepare("
                UPDATE `tamu` 
                SET `status` = :status, `jumlah_hadir_aktual` = :hadir_aktual 
                WHERE `id` = :id
            ");
            $hadirAktual = ($status === 'Hadir') ? $pax : 0;
            $updateTamu->execute([
                ':status' => $status,
                ':hadir_aktual' => $hadirAktual,
                ':id' => $tamuId
            ]);
        }

        jsonResponse([
            'success' => true,
            'message' => 'Terima kasih atas doa, harapan, dan konfirmasi kehadiran Anda! 🤍',
            'data' => [
                'id' => $newId,
                'nama_pengirim' => $nama,
                'status_kehadiran' => $status,
                'jumlah_kehadiran' => $pax,
                'pesan_ucapan' => $pesan,
                'formatted_date' => 'Baru saja',
                'initials' => strtoupper(substr($nama, 0, 2))
            ]
        ], 201);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 3. POST (action=delete atau DELETE): Hapus Ucapan
// --------------------------------------------------------------------
if ($method === 'DELETE' || ($method === 'POST' && $action === 'delete')) {
    $input = getJsonInput();
    $id = (int)($input['id'] ?? $_GET['id'] ?? 0);

    if ($id <= 0) {
        jsonResponse(['success' => false, 'message' => 'ID ucapan tidak valid.'], 400);
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM `doa_ucapan` WHERE `id` = :id");
        $stmt->execute([':id' => $id]);

        jsonResponse([
            'success' => true,
            'message' => 'Ucapan berhasil dihapus.'
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Metode request tidak diizinkan.'], 405);

