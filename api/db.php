<?php
// ====================================================================
// KONFIGURASI KONEKSI DATABASE (PHP PDO)
// Proyek: Fakhrel & Olivia Wedding Invitation & Admin Dashboard
// ====================================================================

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight OPTIONS request
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = '127.0.0.1';
$db   = 'db_fakhrel_olivia';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Jika database belum dibuat, coba koneksi ke host saja
    try {
        $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
    } catch (\PDOException $e2) {
        $pdo = null;
    }
}

/**
 * Helper untuk response JSON
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}

/**
 * Helper untuk mendapatkan input JSON dari request body
 */
function getJsonInput() {
    $input = file_get_contents('php://input');
    if (empty($input)) {
        return $_POST;
    }
    $decoded = json_decode($input, true);
    return is_array($decoded) ? array_merge($_POST, $decoded) : $_POST;
}

/**
 * Helper untuk membuat slug unik dari string
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'tamu-' . rand(100, 999) : $text;
}

/**
 * Helper untuk memvalidasi sesi admin pada endpoint write / delete
 */
function requireAdminAuth() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_logged_in'])) {
        global $pdo;
        if (!empty($_COOKIE['relliv_remember']) && $pdo) {
            try {
                $stmtRem = $pdo->prepare("SELECT id, name, email, role FROM `users` WHERE `remember_token` = :tok LIMIT 1");
                $stmtRem->execute([':tok' => $_COOKIE['relliv_remember']]);
                $uRem = $stmtRem->fetch();
                if ($uRem) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['user_id'] = $uRem['id'];
                    $_SESSION['user_name'] = $uRem['name'];
                    $_SESSION['user_email'] = $uRem['email'];
                    $_SESSION['user_role'] = $uRem['role'];
                    return true;
                }
            } catch (\PDOException $e) {}
        }

        jsonResponse([
            'success' => false,
            'message' => 'Akses ditolak. Sesi login admin telah berakhir atau tidak valid.'
        ], 401);
    }
    return true;
}


