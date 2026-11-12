<?php
// ====================================================================
// API PENGANTIN: Data Mempelai & Rangkaian Acara
// ====================================================================

require_once __DIR__ . '/db.php';

if (!$pdo) {
    jsonResponse([
        'success' => false,
        'message' => 'Koneksi ke database gagal. Pastikan MySQL Laragon aktif.'
    ], 500);
}

$method = $_SERVER['REQUEST_METHOD'];

// --------------------------------------------------------------------
// 1. GET: Ambil Profil Kedua Mempelai & Info Acara
// --------------------------------------------------------------------
if ($method === 'GET') {
    try {
        $pengantinStmt = $pdo->query("SELECT * FROM `pengantin` ORDER BY peran DESC");
        $pengantin = $pengantinStmt->fetchAll();

        $acaraStmt = $pdo->query("SELECT * FROM `acara` ORDER BY urutan ASC");
        $acara = $acaraStmt->fetchAll();

        $groom = null;
        $bride = null;
        foreach ($pengantin as $p) {
            if ($p['peran'] === 'groom') $groom = $p;
            if ($p['peran'] === 'bride') $bride = $p;
        }

        jsonResponse([
            'success' => true,
            'groom' => $groom,
            'bride' => $bride,
            'acara' => $acara
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 2. POST: Perbarui Profil Kedua Mempelai
// --------------------------------------------------------------------
if ($method === 'POST') {
    $input = getJsonInput();
    
    $groomName = trim($input['groom'] ?? $input['groom_name'] ?? '');
    $groomP = trim($input['groomP'] ?? $input['groom_parents'] ?? '');
    $brideName = trim($input['bride'] ?? $input['bride_name'] ?? '');
    $brideP = trim($input['brideP'] ?? $input['bride_parents'] ?? '');

    try {
        if (!empty($groomName)) {
            $stmt = $pdo->prepare("
                UPDATE `pengantin` 
                SET `nama_panggilan` = :name, `nama_lengkap` = :name, `nama_ayah` = :parents 
                WHERE `peran` = 'groom'
            ");
            $stmt->execute([':name' => $groomName, ':parents' => $groomP]);
        }

        if (!empty($brideName)) {
            $stmt = $pdo->prepare("
                UPDATE `pengantin` 
                SET `nama_panggilan` = :name, `nama_lengkap` = :name, `nama_ayah` = :parents 
                WHERE `peran` = 'bride'
            ");
            $stmt->execute([':name' => $brideName, ':parents' => $brideP]);
        }

        jsonResponse([
            'success' => true,
            'message' => 'Profil pengantin berhasil diperbarui di basis data!'
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Metode request tidak diizinkan.'], 405);

