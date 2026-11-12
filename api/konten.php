<?php
// ====================================================================
// API KONTEN: Manajemen Konten 6 Section Undangan & Halaman Terpadu
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

// Pastikan tabel section_konten ada
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `section_konten` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `section_key` VARCHAR(50) NOT NULL UNIQUE,
          `nama_section` VARCHAR(100) NOT NULL,
          `konten_json` LONGTEXT NOT NULL,
          `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          INDEX `idx_section_key` (`section_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
} catch (\PDOException $e) {}

// Default template data 6 section
function getDefaultSectionData($key = null) {
    $defaults = [
        'section_1_hero' => [
            'section_number' => 1,
            'nama_section' => 'Section 1: Hero & Pembuka',
            'subpage' => 'section-hero.html',
            'thumbnail' => '../asetV2/background_herosection(desktop).webp',
            'data' => [
                'tagline' => 'THE WEDDING OF',
                'couple_title' => 'Fakhrel and Olivia',
                'groom_nickname' => 'Fakhrel',
                'bride_nickname' => 'Olivia',
                'wedding_date' => '25 OCTOBER 2026',
                'salam_text' => 'Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir dalam hari bahagia kami.',
                'side_text' => "SAME\nPEOPLE\nBRIGHTER\nDAYS",
                'script_text' => "A new\nchapter\ntogether",
                'city' => 'JAKARTA, INDONESIA',
                'bg_desktop' => '../asetV2/background_herosection(desktop).webp',
                'bg_mobile' => '../asetV2/background_herosection.webp'
            ]
        ],
        'section_2_about' => [
            'section_number' => 2,
            'nama_section' => 'Section 2: Mempelai (About)',
            'subpage' => 'pengantin.html',
            'thumbnail' => '../aset/section2_fotopengantin.webp',
            'data' => [
                'headline' => "Sebuah\nAwal dari\nSelamanya",
                'intro_1' => 'Dengan penuh rasa syukur dan bahagia, kami mengundang Bapak/Ibu/Saudara/i untuk menjadi bagian dari hari istimewa dalam perjalanan hidup kami.',
                'intro_2' => 'Pernikahan ini bukan hanya tentang dua insan yang saling mencintai, tetapi juga tentang dua keluarga yang dipersatukan dalam doa, restu, dan kebahagiaan bersama.',
                'photo_couple' => '../aset/section2_fotopengantin.webp',
                'groom_nickname' => 'Fakhrel',
                'groom_fullname' => 'Pratama',
                'groom_parents' => 'Putra pertama dari Bapak Surya Pratama dan Ibu Ratna Dewi',
                'groom_instagram' => 'https://instagram.com',
                'groom_photo' => '../aset/section2_fotopengantin(2).webp',
                'bride_nickname' => 'Olivia',
                'bride_fullname' => 'Putri',
                'bride_parents' => 'Putri kedua dari Bapak Hendra Wijaya dan Ibu Sari Lestari',
                'bride_instagram' => 'https://instagram.com',
                'bride_photo' => '../aset/section2_fotopengantin(3).webp',
                'bottom_quote' => 'Dua hati, satu tujuan, melangkah bersama menuju selamanya.'
            ]
        ],
        'section_3_story' => [
            'section_number' => 3,
            'nama_section' => 'Section 3: Our Story (Kisah Cinta)',
            'subpage' => 'our-story.html',
            'thumbnail' => '../aset/section3_fotopengantin.webp',
            'data' => [
                'headline' => "Kisah\nCinta Kami",
                'intro' => 'Setiap perjalanan memiliki cerita, dan ini adalah cerita tentang dua hati yang dipertemukan, tumbuh bersama, dan memilih untuk melangkah ke masa depan yang sama.',
                'photo_hero' => '../aset/section3_fotopengantin.webp',
                'bottom_quote' => 'Bukan tentang siapa yang sempurna, tapi tentang dua orang yang memilih untuk selalu berjuang bersama.'
            ]
        ],
        'section_4_event' => [
            'section_number' => 4,
            'nama_section' => 'Section 4: Waktu & Lokasi Acara',
            'subpage' => 'acara.html',
            'thumbnail' => '../asetV2/dekorasi_akad_hd.webp',
            'data' => [
                'headline' => "Waktu\nPernikahan",
                'intro' => 'Dengan penuh rasa syukur, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dalam rangkaian acara pernikahan kami.',
                'header_photo' => '../aset/section4_header_cincin.webp',
                'akad_name' => 'Akad Nikah',
                'akad_date' => 'Minggu, 25 Oktober 2026',
                'akad_time' => '08.00 - 10.00 WIB',
                'akad_place' => 'The Glass House, Jakarta',
                'akad_address' => 'Jl. Bunga Melati No. 12, Jakarta Selatan',
                'akad_photo' => '../asetV2/dekorasi_akad_hd.webp',
                'resepsi_name' => 'Resepsi Pernikahan',
                'resepsi_date' => 'Minggu, 25 Oktober 2026',
                'resepsi_time' => '11.00 - 14.00 WIB',
                'resepsi_place' => 'The Glass House, Jakarta',
                'resepsi_address' => 'Jl. Bunga Melati No. 12, Jakarta Selatan',
                'resepsi_photo' => '../aset/section4_fotopengantin(2).webp',
                'venue_headline' => 'Temui Kami Di Sini',
                'venue_intro' => 'Kami sangat berharap kehadiran Bapak/Ibu/Saudara/i untuk menjadi bagian dari hari istimewa ini.',
                'venue_name' => 'The Backyard Cafe',
                'venue_address' => 'Jl. Melawai No. 123, Jakarta Selatan',
                'venue_photo' => '../aset/section4_bagianbawah.webp',
                'maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.1!2d106.79!3d-6.24!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z!5e0!3m2!1sen!2sid!4v1',
                'maps_link' => 'https://maps.google.com',
                'features_parking' => 'Tersedia area parkir',
                'features_transit' => 'Dapat diakses dengan transportasi umum',
                'bottom_quote' => 'Tempat di mana cerita baru kami dimulai bersama orang-orang tercinta.'
            ]
        ],
        'section_5_gallery' => [
            'section_number' => 5,
            'nama_section' => 'Section 5: Galeri Foto',
            'subpage' => 'galeri.html',
            'thumbnail' => '../aset/section5_fotopengantin.webp',
            'data' => [
                'headline' => "Momen\nBerharga Bersama",
                'intro' => 'Setiap foto menyimpan cerita, tentang tawa, perjalanan, dan cinta yang tumbuh.',
                'hero_photo' => '../aset/section5_fotopengantin.webp',
                'hero_script' => "Same\nPeople\nBrighter\nDays",
                'bottom_quote' => 'Cinta bukan tentang mencari seseorang yang sempurna, tetapi tentang melihat seseorang yang tidak sempurna dengan cara yang sempurna.'
            ]
        ],
        'section_6_rsvp' => [
            'section_number' => 6,
            'nama_section' => 'Section 6: RSVP, Ucapan & Penutup',
            'subpage' => 'section-penutup.html',
            'thumbnail' => '../aset/section6(01).webp',
            'data' => [
                'headline' => "Doa dan\nUcapan",
                'intro' => 'Terima kasih atas kehadiran, doa dan ucapan yang tulus untuk kami. Semoga segala kebaikan kembali kepada Anda.',
                'banner_photo' => '../aset/section6(01).webp',
                'form_title' => 'Kirim Ucapan',
                'form_intro' => 'Tuliskan doa, harapan, dan ucapan terbaik untuk kami.',
                'confirm_box_title' => "Konfirmasi\nKehadiran",
                'confirm_box_intro' => 'Mohon konfirmasi kehadiran Anda agar kami dapat mempersiapkan segala sesuatunya dengan lebih baik.',
                'thanks_script' => "Terima\nKasih",
                'closing_quote' => "Doa Anda adalah hadiah\nterindah bagi kami.",
                'closing_subtext' => 'Terima kasih telah menjadi bagian dari hari istimewa kami.',
                'footer_couple' => 'FAKHREL & OLIVIA',
                'footer_date' => '25 OKTOBER 2026'
            ]
        ]
    ];

    if ($key) return $defaults[$key] ?? null;
    return $defaults;
}

// --------------------------------------------------------------------
// 1. GET: Ambil Seluruh Data Konten 6 Section (untuk Halaman Publik)
// --------------------------------------------------------------------
if ($method === 'GET' && ($action === 'sections' || $action === 'get_all_sections')) {
    try {
        $stmt = $pdo->query("SELECT `section_key`, `konten_json`, `updated_at` FROM `section_konten`");
        $rows = $stmt->fetchAll();
        $dbData = [];
        foreach ($rows as $r) {
            $decoded = json_decode($r['konten_json'], true);
            if ($decoded) {
                $dbData[$r['section_key']] = $decoded;
            }
        }

        $allDefaults = getDefaultSectionData();
        $finalSections = [];

        foreach ($allDefaults as $k => $def) {
            if (isset($dbData[$k]) && is_array($dbData[$k])) {
                $finalSections[$k] = array_merge($def['data'], $dbData[$k]);
            } else {
                $finalSections[$k] = $def['data'];
            }
        }

        jsonResponse([
            'success' => true,
            'sections' => $finalSections
        ]);
    } catch (\PDOException $e) {
        // Fallback ke default
        $allDefaults = getDefaultSectionData();
        $final = [];
        foreach ($allDefaults as $k => $def) $final[$k] = $def['data'];
        jsonResponse(['success' => true, 'sections' => $final, 'warning' => $e->getMessage()]);
    }
}

// --------------------------------------------------------------------
// 2. GET: Ambil Ringkasan Daftar 6 Section (untuk Parent Page kelola-konten.html)
// --------------------------------------------------------------------
if ($method === 'GET' && ($action === 'list_sections' || $action === 'summary')) {
    try {
        $stmt = $pdo->query("SELECT `section_key`, `nama_section`, `konten_json`, `updated_at` FROM `section_konten`");
        $rows = $stmt->fetchAll();
        $dbMap = [];
        foreach ($rows as $r) {
            $dbMap[$r['section_key']] = [
                'updated_at' => $r['updated_at'],
                'data' => json_decode($r['konten_json'], true) ?: []
            ];
        }

        $allDefaults = getDefaultSectionData();
        $list = [];

        $descriptions = [
            'section_1_hero' => 'Tagline acara, nama pasangan, tanggal pernikahan, salam pembuka, dan background hero.',
            'section_2_about' => 'Profil mempelai pria & wanita, nama orang tua, akun media sosial, foto, dan quote pengantar.',
            'section_3_story' => 'Judul kisah cinta, paragraf perjalanan kasih, foto hero, quote penutup, dan milestone timeline.',
            'section_4_event' => 'Detail waktu Akad Nikah, Resepsi Pernikahan, nama venue, alamat, dan Google Maps interaktif.',
            'section_5_gallery' => 'Headline galeri, caption editorial, foto utama, quote penutup, dan 10 foto bento grid.',
            'section_6_rsvp' => 'Form doa & ucapan, status kehadiran tamu, kalimat penutup, kuotasi rasa syukur, dan footer.'
        ];

        foreach ($allDefaults as $k => $def) {
            $db = $dbMap[$k] ?? null;
            $updatedAt = $db ? $db['updated_at'] : date('Y-m-d H:i:s');
            $mergedData = $db ? array_merge($def['data'], $db['data']) : $def['data'];

            $thumb = $def['thumbnail'];
            if (!empty($mergedData['bg_desktop'])) $thumb = $mergedData['bg_desktop'];
            elseif (!empty($mergedData['photo_couple'])) $thumb = $mergedData['photo_couple'];
            elseif (!empty($mergedData['photo_hero'])) $thumb = $mergedData['photo_hero'];
            elseif (!empty($mergedData['header_photo'])) $thumb = $mergedData['header_photo'];
            elseif (!empty($mergedData['hero_photo'])) $thumb = $mergedData['hero_photo'];
            elseif (!empty($mergedData['banner_photo'])) $thumb = $mergedData['banner_photo'];

            $list[] = [
                'key' => $k,
                'number' => $def['section_number'],
                'name' => $def['nama_section'],
                'description' => $descriptions[$k] ?? '',
                'subpage' => $def['subpage'],
                'thumbnail' => $thumb,
                'updated_at' => $updatedAt,
                'item_count' => count($mergedData),
                'status' => 'Aktif & Tersinkron'
            ];
        }

        jsonResponse([
            'success' => true,
            'total' => count($list),
            'data' => $list
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 3. GET: Ambil Detail Konten 1 Section Tertentu (untuk Subhalaman Editor)
// --------------------------------------------------------------------
if ($method === 'GET' && ($action === 'get_section' || !empty($_GET['key']))) {
    $key = trim($_GET['key'] ?? '');
    $default = getDefaultSectionData($key);

    if (!$default) {
        jsonResponse(['success' => false, 'message' => 'Section key tidak valid.'], 404);
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM `section_konten` WHERE `section_key` = :key LIMIT 1");
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch();

        $data = $default['data'];
        $updatedAt = date('Y-m-d H:i:s');

        if ($row) {
            $decoded = json_decode($row['konten_json'], true);
            if ($decoded && is_array($decoded)) {
                $data = array_merge($default['data'], $decoded);
            }
            $updatedAt = $row['updated_at'];
        }

        jsonResponse([
            'success' => true,
            'key' => $key,
            'name' => $default['nama_section'],
            'subpage' => $default['subpage'],
            'updated_at' => $updatedAt,
            'data' => $data
        ]);
    } catch (\PDOException $e) {
        jsonResponse([
            'success' => true,
            'key' => $key,
            'name' => $default['nama_section'],
            'data' => $default['data'],
            'warning' => $e->getMessage()
        ]);
    }
}

// --------------------------------------------------------------------
// 4. POST: Simpan Perubahan Konten Section (Persisten ke MySQL)
// --------------------------------------------------------------------
if ($method === 'POST' && ($action === 'save_section' || $action === 'save')) {
    $input = getJsonInput();
    $key = trim($input['section_key'] ?? '');
    $nama = trim($input['nama_section'] ?? '');
    $data = $input['data'] ?? [];

    if (empty($key)) {
        jsonResponse(['success' => false, 'message' => 'Parameter section_key wajib diisi.'], 400);
    }

    $default = getDefaultSectionData($key);
    if (!$default) {
        jsonResponse(['success' => false, 'message' => 'Section key tidak dikenali.'], 404);
    }

    if (empty($nama)) {
        $nama = $default['nama_section'];
    }

    // Ambil data yang tersimpan sebelumnya agar field yang tidak diinput tidak hilang
    try {
        $stmtExisting = $pdo->prepare("SELECT `konten_json` FROM `section_konten` WHERE `section_key` = :key LIMIT 1");
        $stmtExisting->execute([':key' => $key]);
        $existingRow = $stmtExisting->fetch();

        $merged = $default['data'];
        if ($existingRow) {
            $prev = json_decode($existingRow['konten_json'], true);
            if ($prev && is_array($prev)) {
                $merged = array_merge($merged, $prev);
            }
        }

        if (is_array($data)) {
            $merged = array_merge($merged, $data);
        }

        $jsonPayload = json_encode($merged, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $stmtSave = $pdo->prepare("
            INSERT INTO `section_konten` (`section_key`, `nama_section`, `konten_json`)
            VALUES (:key, :nama, :json)
            ON DUPLICATE KEY UPDATE 
                `nama_section` = :nama_up,
                `konten_json` = :json_up,
                `updated_at` = NOW()
        ");

        $stmtSave->execute([
            ':key' => $key,
            ':nama' => $nama,
            ':json' => $jsonPayload,
            ':nama_up' => $nama,
            ':json_up' => $jsonPayload
        ]);

        // Sinkronisasi khusus ke tabel lain bila relevan
        // Section 2: Sinkron ke tabel pengantin
        if ($key === 'section_2_about') {
            try {
                if (!empty($merged['groom_nickname'])) {
                    $pdo->prepare("UPDATE `pengantin` SET `nama_panggilan` = :nick, `nama_lengkap` = :full, `foto_url` = :foto, `instagram` = :ig WHERE `peran` = 'groom'")
                        ->execute([
                            ':nick' => $merged['groom_nickname'],
                            ':full' => trim(($merged['groom_nickname'] ?? '') . ' ' . ($merged['groom_fullname'] ?? '')),
                            ':foto' => $merged['groom_photo'] ?? '',
                            ':ig' => $merged['groom_instagram'] ?? ''
                        ]);
                }
                if (!empty($merged['bride_nickname'])) {
                    $pdo->prepare("UPDATE `pengantin` SET `nama_panggilan` = :nick, `nama_lengkap` = :full, `foto_url` = :foto, `instagram` = :ig WHERE `peran` = 'bride'")
                        ->execute([
                            ':nick' => $merged['bride_nickname'],
                            ':full' => trim(($merged['bride_nickname'] ?? '') . ' ' . ($merged['bride_fullname'] ?? '')),
                            ':foto' => $merged['bride_photo'] ?? '',
                            ':ig' => $merged['bride_instagram'] ?? ''
                        ]);
                }
            } catch (\Exception $ex) {}
        }

        // Section 4: Sinkron ke tabel acara
        if ($key === 'section_4_event') {
            try {
                if (!empty($merged['akad_name'])) {
                    $pdo->prepare("UPDATE `acara` SET `tempat` = :t, `alamat` = :a WHERE `nama_acara` LIKE '%Akad%'")
                        ->execute([':t' => $merged['akad_place'] ?? '', ':a' => $merged['akad_address'] ?? '']);
                }
                if (!empty($merged['resepsi_name'])) {
                    $pdo->prepare("UPDATE `acara` SET `tempat` = :t, `alamat` = :a, `google_maps_url` = :m WHERE `nama_acara` LIKE '%Resepsi%'")
                        ->execute([':t' => $merged['resepsi_place'] ?? '', ':a' => $merged['resepsi_address'] ?? '', ':m' => $merged['maps_link'] ?? '']);
                }
            } catch (\Exception $ex) {}
        }

        jsonResponse([
            'success' => true,
            'message' => 'Konten ' . $nama . ' berhasil disimpan ke database!',
            'key' => $key,
            'updated_at' => date('Y-m-d H:i:s'),
            'data' => $merged
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
    }
}

// --------------------------------------------------------------------
// 5. GET Default: Fallback ke konten_acara (Backward Compatibility)
// --------------------------------------------------------------------
if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM `konten_acara` ORDER BY urutan ASC, id ASC");
        $items = $stmt->fetchAll();

        jsonResponse([
            'success' => true,
            'total' => count($items),
            'data' => $items
        ]);
    } catch (\PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Metode request tidak diizinkan.'], 405);
