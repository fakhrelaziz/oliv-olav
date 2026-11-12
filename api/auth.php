<?php
// ====================================================================
// API AUTH: Login & Logout Admin Dashboard
// ====================================================================

session_start();
require_once __DIR__ . '/db.inc';

$action = $_GET['action'] ?? 'check';

if (!$pdo) {
    jsonResponse([
        'success' => false,
        'message' => 'Koneksi ke database gagal. Pastikan MySQL Laragon aktif.'
    ], 500);
}

// --------------------------------------------------------------------
// 1. Action: Login
// --------------------------------------------------------------------
if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(['success' => false, 'message' => 'Metode request tidak diizinkan.'], 405);
    }

    $input = getJsonInput();
    $email = trim($input['email'] ?? '');
    $password = trim($input['password'] ?? '');
    $remember = !empty($input['remember']);

    if (empty($email) || empty($password)) {
        jsonResponse([
            'success' => false,
            'message' => 'Email dan kata sandi wajib diisi.'
        ], 400);
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        // Validasi password (bisa hash atau plaintext default wedding2026)
        $passwordValid = false;
        if ($user) {
            if (password_verify($password, $user['password']) || $password === 'wedding2026' || $password === $user['password']) {
                $passwordValid = true;
            }
        }

        if (!$user || !$passwordValid) {
            jsonResponse([
                'success' => false,
                'message' => 'Email atau kata sandi tidak cocok. Silakan coba kembali.'
            ], 401);
        }

        // Simpan sesi login
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $updateStmt = $pdo->prepare("UPDATE `users` SET `remember_token` = :token WHERE `id` = :id");
            $updateStmt->execute([':token' => $token, ':id' => $user['id']]);
            setcookie('relliv_remember', $token, time() + (86400 * 30), "/");
        }

        jsonResponse([
            'success' => true,
            'message' => 'Login berhasil! Mengalihkan ke dashboard...',
            'user' => [
                'id' => (int)$user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ],
            'redirect' => 'index.html'
        ]);

    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 2. Action: Logout
// --------------------------------------------------------------------
if ($action === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    setcookie('relliv_remember', '', time() - 3600, "/");

    jsonResponse([
        'success' => true,
        'message' => 'Logout berhasil.',
        'redirect' => 'login.html'
    ]);
}

// --------------------------------------------------------------------
// 3. Action: Check Session Status
// --------------------------------------------------------------------
if ($action === 'check') {
    $isLoggedIn = !empty($_SESSION['admin_logged_in']);

    // Cek cookie remember me jika sesi PHP kosong
    if (!$isLoggedIn && !empty($_COOKIE['relliv_remember'])) {
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
                $isLoggedIn = true;
            }
        } catch (\PDOException $e) {}
    }

    jsonResponse([
        'success' => true,
        'authenticated' => $isLoggedIn,
        'user' => $isLoggedIn ? [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['user_name'] ?? 'Admin',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'admin'
        ] : null
    ]);
}

jsonResponse(['success' => false, 'message' => 'Action tidak dikenali.'], 400);

