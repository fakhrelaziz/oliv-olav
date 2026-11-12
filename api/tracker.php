<?php
// ====================================================================
// API TRACKER: Pencatat Kunjungan & Statistik Trafik Undangan
// ====================================================================

require_once __DIR__ . '/db.php';

if (!$pdo) {
    jsonResponse([
        'success' => false,
        'message' => 'Koneksi ke database gagal.'
    ], 500);
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// --------------------------------------------------------------------
// 1. GET: Ambil Statistik Kunjungan Murni dari Database untuk Dashboard
// --------------------------------------------------------------------
if ($method === 'GET' || $action === 'stats') {
    try {
        $stmtTotal = $pdo->query("SELECT COUNT(*) as total FROM `kunjungan_log`");
        $total = (int)($stmtTotal->fetch()['total'] ?? 0);

        $stmtToday = $pdo->query("SELECT COUNT(*) as today FROM `kunjungan_log` WHERE DATE(visited_at) = CURDATE()");
        $today = (int)($stmtToday->fetch()['today'] ?? 0);

        $stmtWeek = $pdo->query("SELECT COUNT(*) as week FROM `kunjungan_log` WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $week = (int)($stmtWeek->fetch()['week'] ?? 0);

        $stmtPrevWeek = $pdo->query("SELECT COUNT(*) as prev FROM `kunjungan_log` WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 14 DAY) AND visited_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $prevWeek = (int)($stmtPrevWeek->fetch()['prev'] ?? 0);

        // Hitung pertumbuhan aktual atau tampilkan data hari ini
        if ($prevWeek > 0) {
            $diff = $week - $prevWeek;
            $percent = round(($diff / $prevWeek) * 100);
            $growthSymbol = $percent >= 0 ? '▲ +' : '▼ ';
            $growthLabel = "{$growthSymbol}{$percent}% dari minggu lalu";
        } elseif ($today > 0) {
            $growthLabel = "▲ +{$today} pengunjung hari ini";
        } else {
            $growthLabel = "0 kunjungan hari ini";
        }

        jsonResponse([
            'success' => true,
            'stats' => [
                'total' => $total,
                'total_raw' => $total,
                'total_display' => number_format($total, 0, ',', '.'),
                'today' => $today,
                'week' => $week,
                'growth' => $growthLabel
            ]
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 2. POST: Catat Kunjungan Baru Saat Undangan Dibuka
// --------------------------------------------------------------------
if ($method === 'POST') {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $agent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 250);
    $halaman = trim($_GET['halaman'] ?? 'undangan');

    try {
        $stmt = $pdo->prepare("
            INSERT INTO `kunjungan_log` (`ip_address`, `user_agent`, `halaman`, `visited_at`)
            VALUES (:ip, :agent, :halaman, NOW())
        ");
        $stmt->execute([
            ':ip' => $ip,
            ':agent' => $agent,
            ':halaman' => $halaman
        ]);

        jsonResponse([
            'success' => true,
            'message' => 'Kunjungan tercatat'
        ], 201);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Metode request tidak diizinkan.'], 405);

