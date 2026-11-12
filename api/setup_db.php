<?php
// ====================================================================
// SCRIPT INISIALISASI DATABASE OTOMATIS (MIGRATION & SEEDER)
// Menjalankan database/schema.sql ke MySQL Laragon
// ====================================================================

require_once __DIR__ . '/db.php';

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

header('Content-Type: application/json; charset=utf-8');

try {
    $conn = new PDO("mysql:host=$host;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $sqlFile = dirname(__DIR__) . '/database/schema.sql';
    if (!file_exists($sqlFile)) {
        jsonResponse([
            'success' => false,
            'message' => 'File schema.sql tidak ditemukan di: ' . $sqlFile
        ], 404);
    }

    $sqlContent = file_get_contents($sqlFile);

    // Eksekusi query schema
    $conn->exec($sqlContent);

    // Ambil daftar tabel yang baru dibuat
    $stmt = $conn->query("SHOW TABLES FROM `db_fakhrel_olivia`");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Hitung jumlah data awal
    $conn->exec("USE `db_fakhrel_olivia`");
    $userCount   = $conn->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
    $tamuCount   = $conn->query("SELECT COUNT(*) FROM `tamu`")->fetchColumn();
    $doaCount    = $conn->query("SELECT COUNT(*) FROM `doa_ucapan`")->fetchColumn();
    $galeriCount = $conn->query("SELECT COUNT(*) FROM `galeri_foto`")->fetchColumn();
    $storyCount  = $conn->query("SELECT COUNT(*) FROM `cerita_cinta`")->fetchColumn();
    $kontenCount  = $conn->query("SELECT COUNT(*) FROM `konten_acara`")->fetchColumn();
    $sectionCount = $conn->query("SELECT COUNT(*) FROM `section_konten`")->fetchColumn();

    jsonResponse([
        'success' => true,
        'message' => 'Database `db_fakhrel_olivia` dan seluruh tabel berhasil diinisialisasi!',
        'tables' => $tables,
        'seeder_summary' => [
            'users' => (int)$userCount,
            'tamu' => (int)$tamuCount,
            'doa_ucapan' => (int)$doaCount,
            'galeri_foto' => (int)$galeriCount,
            'cerita_cinta' => (int)$storyCount,
            'konten_acara' => (int)$kontenCount,
            'section_konten' => (int)$sectionCount
        ]
    ]);

} catch (\PDOException $e) {
    jsonResponse([
        'success' => false,
        'message' => 'Gagal menginisialisasi database: ' . $e->getMessage(),
        'tip' => 'Pastikan service MySQL di Laragon sudah dalam keadaan START / Running.'
    ], 500);
}

