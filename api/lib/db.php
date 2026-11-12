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

$host    = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: '127.0.0.1';
$port    = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306';
$db      = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'db_fakhrel_olivia';
$user    = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$pass    = getenv('DB_PASS') !== false ? getenv('DB_PASS') : (getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : '');
$charset = 'utf8mb4';

// Dukungan otomatis URL Railway: prioritaskan MYSQL_PUBLIC_URL agar bisa diakses dari Vercel,
// dan fallback ke MYSQL_URL / DATABASE_URL / RAILWAY_TCP_PROXY_DOMAIN jika host internal.
$candidateUrls = [
    getenv('MYSQL_PUBLIC_URL'),
    getenv('MYSQL_URL'),
    getenv('DATABASE_URL'),
];
foreach ($candidateUrls as $candidateUrl) {
    if (!$candidateUrl || strpos($candidateUrl, 'mysql://') !== 0) {
        continue;
    }
    $parsed = parse_url($candidateUrl);
    if (!$parsed || empty($parsed['host'])) {
        continue;
    }
    $isInternal = substr($parsed['host'], -17) === '.railway.internal';
    if ($isInternal && (getenv('MYSQL_PUBLIC_URL') || getenv('RAILWAY_TCP_PROXY_DOMAIN'))) {
        continue;
    }
    $host = $parsed['host'];
    $port = isset($parsed['port']) ? (string)$parsed['port'] : $port;
    $user = $parsed['user'] ?? $user;
    $pass = $parsed['pass'] ?? $pass;
    if (!empty($parsed['path'])) {
        $db = ltrim($parsed['path'], '/');
    }
    break;
}

if (substr($host, -17) === '.railway.internal') {
    $proxyHost = getenv('RAILWAY_TCP_PROXY_DOMAIN');
    $proxyPort = getenv('RAILWAY_TCP_PROXY_PORT');
    if ($proxyHost) {
        $host = $proxyHost;
        if ($proxyPort) {
            $port = (string)$proxyPort;
        }
    }
}

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_TIMEOUT            => 5,
];

// Aktifkan dukungan SSL/TLS otomatis jika terhubung ke Cloud MySQL (Vercel / TiDB / Aiven / Railway)
if ($host !== '127.0.0.1' && $host !== 'localhost') {
    if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
    if (defined('PDO::MYSQL_ATTR_SSL_CA') && getenv('DB_SSL_CA')) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = getenv('DB_SSL_CA');
    }
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Jika database belum dibuat, coba koneksi ke host saja
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;charset=$charset", $user, $pass, $options);
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
            'authenticated' => false,
            'message' => 'Akses ditolak. Silakan login sebagai Admin terlebih dahulu.'
        ], 401);
    }
    return true;
}

