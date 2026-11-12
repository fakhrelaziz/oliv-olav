<?php
// ====================================================================
// API UPLOAD: Layanan Unggah Gambar / Foto untuk Seluruh Section Admin
// ====================================================================

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse([
        'success' => false,
        'message' => 'Metode request tidak diizinkan. Gunakan POST.'
    ], 405);
}

// Ambil file dari $_FILES['file'] atau $_FILES['photo']
$file = $_FILES['file'] ?? $_FILES['photo'] ?? null;

if (!$file || !isset($file['tmp_name']) || empty($file['tmp_name'])) {
    jsonResponse([
        'success' => false,
        'message' => 'Tidak ada file foto yang dipilih untuk diunggah.'
    ], 400);
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE   => 'Ukuran file melebihi batas maksimum server.',
        UPLOAD_ERR_FORM_SIZE  => 'Ukuran file melebihi batas form.',
        UPLOAD_ERR_PARTIAL    => 'File hanya terunggah sebagian.',
        UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang diunggah.',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary server tidak ditemukan.',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menyimpan file ke disk server.',
        UPLOAD_ERR_EXTENSION  => 'Unggahan dihentikan oleh ekstensi PHP.'
    ];
    $msg = $errorMessages[$file['error']] ?? 'Terjadi kesalahan saat mengunggah file.';
    jsonResponse([
        'success' => false,
        'message' => $msg
    ], 400);
}

// Validasi ukuran maksimal (10 MB)
$maxSize = 10 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    jsonResponse([
        'success' => false,
        'message' => 'Ukuran file foto terlalu besar. Maksimal 10 MB.'
    ], 400);
}

// Validasi MIME Type & Ekstensi
$allowedMimeTypes = [
    'image/jpeg' => 'jpg',
    'image/jpg'  => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$originalExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

if (!isset($allowedMimeTypes[$mimeType]) && !in_array($originalExt, $allowedExtensions, true)) {
    jsonResponse([
        'success' => false,
        'message' => 'Format file tidak didukung. Harap unggah foto berformat JPG, PNG, WEBP, atau GIF.'
    ], 400);
}

$ext = $allowedMimeTypes[$mimeType] ?? ($originalExt === 'jpeg' ? 'jpg' : $originalExt);

// Pastikan folder uploads/ tersedia di root project
$uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        jsonResponse([
            'success' => false,
            'message' => 'Gagal membuat direktori penyimpanan uploads/.'
        ], 500);
    }
}

// Buat nama file unik & bersih
$sectionPrefix = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['section'] ?? $_GET['section'] ?? 'img');
if (empty($sectionPrefix)) {
    $sectionPrefix = 'img';
}

$uniqueName = $sectionPrefix . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
$targetPath = $uploadDir . DIRECTORY_SEPARATOR . $uniqueName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    jsonResponse([
        'success' => false,
        'message' => 'Gagal memindahkan file foto ke folder penyimpanan.'
    ], 500);
}

// Path relatif ../uploads/... bisa digunakan langsung oleh dashboard/ maupun integrated/
$relativeUrl = '../uploads/' . $uniqueName;

jsonResponse([
    'success'    => true,
    'message'    => 'Foto berhasil diunggah!',
    'url'        => $relativeUrl,
    'public_url' => 'uploads/' . $uniqueName,
    'filename'   => $uniqueName
], 201);

