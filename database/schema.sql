-- ====================================================================
-- DATABASE SCHEMA: db_fakhrel_olivia
-- SISTEM MANAJEMEN UNDANGAN PERNIKAHAN (REL | LIV / FAKHREL & OLIVIA)
-- Kompatibel dengan MySQL 5.7+ / MariaDB 10.3+ / phpMyAdmin
-- Skema Relasional Penuh dengan Foreign Key Lengkap
-- ====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------------------
-- 1. Inisialisasi Database
-- --------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `db_fakhrel_olivia` 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `db_fakhrel_olivia`;

-- --------------------------------------------------------------------
-- 2. Drop Tables (Clean Install)
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `doa_ucapan`;
DROP TABLE IF EXISTS `tamu`;
DROP TABLE IF EXISTS `section_konten`;
DROP TABLE IF EXISTS `konten_acara`;
DROP TABLE IF EXISTS `galeri_foto`;
DROP TABLE IF EXISTS `cerita_cinta`;
DROP TABLE IF EXISTS `kunjungan_log`;
DROP TABLE IF EXISTS `acara`;
DROP TABLE IF EXISTS `pengantin`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------------------
-- 3. Tabel: users (Admin & Pengguna Dashboard)
-- --------------------------------------------------------------------
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'editor', 'couple') DEFAULT 'admin',
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4. Tabel: pengantin (Data Kedua Mempelai Olav & Oliv)
-- --------------------------------------------------------------------
CREATE TABLE `pengantin` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `peran` ENUM('groom', 'bride') NOT NULL,
  `nama_panggilan` VARCHAR(50) NOT NULL,
  `nama_lengkap` VARCHAR(150) NOT NULL,
  `nama_ayah` VARCHAR(100) DEFAULT NULL,
  `nama_ibu` VARCHAR(100) DEFAULT NULL,
  `urutan_anak` VARCHAR(100) DEFAULT NULL,
  `foto_url` LONGTEXT DEFAULT NULL,
  `instagram` VARCHAR(100) DEFAULT NULL,
  `bio_singkat` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pengantin_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 5. Tabel: acara (Rangkaian Acara Pernikahan)
-- --------------------------------------------------------------------
CREATE TABLE `acara` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `nama_acara` VARCHAR(100) NOT NULL,
  `tanggal` DATE NOT NULL,
  `waktu_mulai` TIME NOT NULL,
  `waktu_selesai` TIME DEFAULT NULL,
  `zona_waktu` VARCHAR(10) DEFAULT 'WIB',
  `tempat` VARCHAR(150) NOT NULL,
  `alamat` TEXT NOT NULL,
  `google_maps_url` TEXT DEFAULT NULL,
  `catatan` VARCHAR(255) DEFAULT NULL,
  `urutan` INT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_acara_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 6. Tabel: galeri_foto (Album Foto Pernikahan)
-- --------------------------------------------------------------------
CREATE TABLE `galeri_foto` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `judul` VARCHAR(150) DEFAULT NULL,
  `kategori` ENUM('prewedding', 'acara', 'keseharian', 'lainnya') NOT NULL DEFAULT 'prewedding',
  `foto_url` LONGTEXT NOT NULL,
  `keterangan` VARCHAR(255) DEFAULT NULL,
  `urutan` INT DEFAULT 1,
  `is_featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_galeri_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 7. Tabel: konten_acara (Daftar Manajemen Konten Dashboard)
-- --------------------------------------------------------------------
CREATE TABLE `konten_acara` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `judul` VARCHAR(150) NOT NULL,
  `deskripsi` VARCHAR(255) DEFAULT NULL,
  `kategori` ENUM('Lokasi', 'Acara', 'Galeri', 'Tentang Kami', 'Lainnya') NOT NULL,
  `thumbnail_url` LONGTEXT DEFAULT NULL,
  `link_halaman` VARCHAR(100) DEFAULT NULL,
  `urutan` INT DEFAULT 1,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_konten_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 8. Tabel: cerita_cinta (Our Story / Milestone Perjalanan Cinta)
-- --------------------------------------------------------------------
CREATE TABLE `cerita_cinta` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `judul` VARCHAR(150) NOT NULL,
  `tanggal_momen` VARCHAR(50) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `foto_url` LONGTEXT DEFAULT NULL,
  `urutan` INT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cerita_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 9. Tabel: kunjungan_log (Statistik Lalu Lintas & Pengunjung Riil)
-- --------------------------------------------------------------------
CREATE TABLE `kunjungan_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `halaman` VARCHAR(100) DEFAULT 'undangan',
  `visited_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_kunjungan_time` (`visited_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 10. Tabel: section_konten (Penyimpanan Konten Dinamis 6 Section)
-- --------------------------------------------------------------------
CREATE TABLE `section_konten` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_key` VARCHAR(50) NOT NULL UNIQUE,
  `nama_section` VARCHAR(100) NOT NULL,
  `konten_json` LONGTEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_section_key` (`section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 8. Tabel: tamu (Master Data Tamu Undangan & Personalisasi URL)
-- --------------------------------------------------------------------
CREATE TABLE `tamu` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Kode unik/slug URL untuk tamu (?to=slug)',
  `nama` VARCHAR(150) NOT NULL,
  `no_telepon` VARCHAR(20) DEFAULT NULL,
  `alamat` VARCHAR(255) DEFAULT NULL,
  `jumlah_undangan` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Pax jatah undangan',
  `status` ENUM('Hadir', 'Tidak Hadir', 'Belum Konfirmasi', 'Masih Ragu') NOT NULL DEFAULT 'Belum Konfirmasi',
  `jumlah_hadir_aktual` INT UNSIGNED DEFAULT 0,
  `is_terkirim` TINYINT(1) DEFAULT 0,
  `waktu_kirim` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_tamu_status` (`status`),
  INDEX `idx_tamu_slug` (`slug`),
  CONSTRAINT `fk_tamu_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 9. Tabel: doa_ucapan (Input Form RSVP & Doa dari Tamu)
-- --------------------------------------------------------------------
CREATE TABLE `doa_ucapan` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tamu_id` INT UNSIGNED DEFAULT NULL,
  `nama_pengirim` VARCHAR(150) NOT NULL,
  `status_kehadiran` ENUM('Hadir', 'Tidak Hadir', 'Masih Ragu') NOT NULL DEFAULT 'Hadir',
  `jumlah_kehadiran` INT UNSIGNED DEFAULT 1,
  `pesan_ucapan` TEXT NOT NULL,
  `is_tampil` TINYINT(1) DEFAULT 1,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_ucapan_status` (`status_kehadiran`),
  CONSTRAINT `fk_doa_tamu` FOREIGN KEY (`tamu_id`) REFERENCES `tamu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ====================================================================
-- SEEDER DATA (DATA AWAL SESUAI DASHBOARD & UNDANGAN)
-- ====================================================================

-- 1. User Admin (Email: admin@relliv.com, Password: 'wedding2026')
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Administrator', 'admin@relliv.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- 2. Data Kedua Mempelai (Fakhrel & Olivia)
INSERT INTO `pengantin` (`id`, `user_id`, `peran`, `nama_panggilan`, `nama_lengkap`, `nama_ayah`, `nama_ibu`, `urutan_anak`, `foto_url`, `instagram`, `bio_singkat`) VALUES
(1, 1, 'groom', 'Fakhrel', 'Fakhrel Pratama', 'Bapak Surya Pratama', 'Ibu Ratna Dewi', 'Putra pertama dari', 'aset/section2_fotopengantin(2).webp', '@fakhrel.pratama', 'Seorang pecinta kesederhanaan yang menemukan rumah di senyuman Olivia.'),
(2, 1, 'bride', 'Olivia', 'Olivia Putri', 'Bapak Hendra Wijaya', 'Ibu Sari Lestari', 'Putri kedua dari', 'aset/section2_fotopengantin(3).webp', '@olivia.putri', 'Menemukan kedamaian dan kehangatan dalam setiap langkah bersama Fakhrel.');

-- 3. Rangkaian Acara
INSERT INTO `acara` (`id`, `user_id`, `nama_acara`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `zona_waktu`, `tempat`, `alamat`, `google_maps_url`, `urutan`) VALUES
(1, 1, 'Akad Nikah', '2026-10-25', '08:00:00', '10:00:00', 'WIB', 'Masjid Al-Falah', 'Jl. Melawai No. 123, Kebayoran Baru, Jakarta Selatan', 'https://maps.google.com', 1),
(2, 1, 'Resepsi Pernikahan', '2026-10-25', '19:00:00', '22:00:00', 'WIB', 'The Backyard Cafe', 'Jl. Melawai No. 123, Kebayoran Baru, Jakarta Selatan', 'https://maps.google.com', 2);

-- 4. Galeri Foto
INSERT INTO `galeri_foto` (`id`, `user_id`, `judul`, `kategori`, `foto_url`, `urutan`, `is_featured`) VALUES
(1, 1, 'Momen Bersama 1', 'prewedding', 'aset/section5_galerifoto.webp', 1, 1),
(2, 1, 'Momen Bersama 2', 'prewedding', 'aset/section5_galerifoto(2).webp', 2, 0),
(3, 1, 'Momen Bersama 3', 'prewedding', 'aset/section5_galerifoto(3).webp', 3, 0),
(4, 1, 'Momen Bersama 4', 'prewedding', 'aset/section5_galerifoto(4).webp', 4, 0),
(5, 1, 'Momen Bersama 5', 'acara', 'aset/section5_galerifoto(5).webp', 5, 0),
(6, 1, 'Momen Bersama 6', 'acara', 'aset/section5_galerifoto(6).webp', 6, 0),
(7, 1, 'Momen Bersama 7', 'keseharian', 'aset/section5_galerifoto(7)webp.webp', 7, 0),
(8, 1, 'Momen Bersama 8', 'keseharian', 'aset/section5_galerifoto(8).webp', 8, 0),
(9, 1, 'Momen Bersama 9', 'keseharian', 'aset/section5_galerifoto(9).webp', 9, 0),
(10, 1, 'Highlight Utama', 'prewedding', 'aset/section5_fotopengantin.webp', 10, 1);

-- 5. Daftar Manajemen Konten Acara
INSERT INTO `konten_acara` (`id`, `user_id`, `judul`, `deskripsi`, `kategori`, `thumbnail_url`, `link_halaman`, `urutan`) VALUES
(1, 1, 'Tempat & Lokasi', 'Informasi lokasi dan alamat venue.', 'Lokasi', 'aset/section4_fotoresepsipernikahan.webp', 'pengantin.html', 1),
(2, 1, 'Susunan Acara', 'Rangkaian acara pernikahan.', 'Acara', 'aset/section4_fotoakadnikah.webp', 'pengantin.html', 2),
(3, 1, 'Galeri Foto', 'Kumpulan momen spesial kami.', 'Galeri', 'aset/section5_galerifoto.webp', 'galeri.html', 3),
(4, 1, 'Kisah Kami', 'Cerita perjalanan cinta kami.', 'Tentang Kami', 'aset/section3_fotopengantin.webp', 'our-story.html', 4),
(5, 1, 'Ucapan Terima Kasih', 'Pesan dan doa untuk kami.', 'Lainnya', 'aset/section2_fotopengantin.webp', 'rsvp.html', 5);

-- 6. Data Tamu Undangan
INSERT INTO `tamu` (`id`, `user_id`, `slug`, `nama`, `no_telepon`, `jumlah_undangan`, `status`, `jumlah_hadir_aktual`, `is_terkirim`) VALUES
(1, 1, 'ahmad-fauzi', 'Ahmad Fauzi', '081234567890', 2, 'Hadir', 2, 1),
(2, 1, 'siti-nurhaliza', 'Siti Nurhaliza', '081234567891', 1, 'Belum Konfirmasi', 0, 1),
(3, 1, 'rizky-maulana', 'Rizky Maulana', '081234567892', 3, 'Hadir', 3, 1),
(4, 1, 'dewi-lestari', 'Dewi Lestari', '081234567893', 2, 'Tidak Hadir', 0, 1),
(5, 1, 'fajar-ramadhan', 'Fajar Ramadhan', '081234567894', 1, 'Hadir', 1, 1),
(6, 1, 'putri-ananda', 'Putri Ananda', '081234567895', 2, 'Hadir', 2, 1),
(7, 1, 'budi-santoso', 'Budi Santoso', '081234567896', 1, 'Belum Konfirmasi', 0, 1),
(8, 1, 'nadia-rahma', 'Nadia Rahma', '081234567897', 2, 'Hadir', 2, 1);

-- 7. Doa & Ucapan RSVP Tamu
INSERT INTO `doa_ucapan` (`id`, `tamu_id`, `nama_pengirim`, `status_kehadiran`, `jumlah_kehadiran`, `pesan_ucapan`, `is_tampil`, `created_at`) VALUES
(1, 1, 'Ahmad Fauzi', 'Hadir', 2, 'Turut berbahagia, semoga lancar sampai hari H. Amin. 🙏', 1, '2026-10-25 10:00:00'),
(2, NULL, 'Siti Rahma', 'Hadir', 2, 'Selamat yaa, semoga menjadi keluarga sakinah, mawadah, warahmah. 🤍', 1, '2026-10-25 09:30:00'),
(3, 4, 'Dewi Lestari', 'Tidak Hadir', 0, 'Maaf tidak bisa hadir, semoga selalu diberi keberkahan. 🙏', 1, '2026-10-24 19:20:00'),
(4, 3, 'Rizky Maulana', 'Hadir', 3, 'Happy wedding! Semoga selalu bahagia dan diberi rezeki yang melimpah. 🤍', 1, '2026-10-24 16:45:00'),
(5, NULL, 'Nadia Putri', 'Masih Ragu', 1, 'InsyaAllah berusaha hadir, mohon dikonfirmasi kembali. 🙏', 1, '2026-10-23 14:10:00');

-- 8. Data Perjalanan Cinta (Our Story)
INSERT INTO `cerita_cinta` (`id`, `user_id`, `judul`, `tanggal_momen`, `deskripsi`, `foto_url`, `urutan`) VALUES
(1, 1, 'Pertemuan Pertama', '14 Februari 2020', 'Pertemuan tak sengaja di perpustakaan kampus yang menjadi awal mula dari percakapan tak berujung.', 'aset/section3_fotopengantin.webp', 1),
(2, 1, 'Rasa yang Tumbuh', '20 Oktober 2021', 'Berawal dari teman diskusi, perlahan tumbuh rasa nyaman yang membuat setiap hari terasa lebih berwarna.', 'aset/section3_fotopengantin(2).webp', 2),
(3, 1, 'Komitmen Bersama', '10 Agustus 2023', 'Kami menyadari bahwa ini bukan hanya sekadar rasa suka, tapi cinta yang ingin terus dijaga dalam suka maupun duka.', 'aset/section3_fotopengantin(3).webp', 3),
(4, 1, 'Hari Lamaran', '15 Desember 2025', 'Di hadapan kedua keluarga besar, kami mengikrarkan niat tulus untuk melangkah ke jenjang pernikahan yang suci.', 'aset/section3_fotopengantin(4).webp', 4),
(5, 1, 'Menuju Hari Bahagia', '25 Oktober 2026', 'Kini, kami siap memulai babak baru sebagai suami dan istri. Bukan akhir dari cerita, tapi awal dari selamanya.', 'aset/section3_fotopengantin(5).webp', 5);

-- 9. Log Kunjungan Awal
INSERT INTO `kunjungan_log` (`id`, `ip_address`, `user_agent`, `halaman`, `visited_at`) VALUES
(1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'undangan', NOW() - INTERVAL 2 DAY),
(2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'undangan', NOW() - INTERVAL 1 DAY),
(3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'undangan', NOW());

-- 10. Data Konten 6 Section Website Undangan
INSERT INTO `section_konten` (`id`, `section_key`, `nama_section`, `konten_json`) VALUES
(1, 'section_1_hero', 'Section 1: Hero & Pembuka', '{"tagline":"THE WEDDING OF","couple_title":"Fakhrel and Olivia","groom_nickname":"Fakhrel","bride_nickname":"Olivia","wedding_date":"25 OCTOBER 2026","salam_text":"Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir dalam hari bahagia kami.","side_text":"SAME\\nPEOPLE\\nBRIGHTER\\nDAYS","script_text":"A new\\nchapter\\ntogether","city":"JAKARTA, INDONESIA","bg_desktop":"../asetV2/background_herosection(desktop).webp","bg_mobile":"../asetV2/background_herosection.webp"}'),
(2, 'section_2_about', 'Section 2: Mempelai (About)', '{"headline":"Sebuah\\nAwal dari\\nSelamanya","intro_1":"Dengan penuh rasa syukur dan bahagia, kami mengundang Bapak/Ibu/Saudara/i untuk menjadi bagian dari hari istimewa dalam perjalanan hidup kami.","intro_2":"Pernikahan ini bukan hanya tentang dua insan yang saling mencintai, tetapi juga tentang dua keluarga yang dipersatukan dalam doa, restu, dan kebahagiaan bersama.","photo_couple":"../aset/section2_fotopengantin.webp","groom_nickname":"Fakhrel","groom_fullname":"Pratama","groom_parents":"Putra pertama dari Bapak Surya Pratama dan Ibu Ratna Dewi","groom_instagram":"https://instagram.com","groom_photo":"../aset/section2_fotopengantin(2).webp","bride_nickname":"Olivia","bride_fullname":"Putri","bride_parents":"Putri kedua dari Bapak Hendra Wijaya dan Ibu Sari Lestari","bride_instagram":"https://instagram.com","bride_photo":"../aset/section2_fotopengantin(3).webp","bottom_quote":"Dua hati, satu tujuan, melangkah bersama menuju selamanya."}'),
(3, 'section_3_story', 'Section 3: Our Story', '{"headline":"Kisah\\nCinta Kami","intro":"Setiap perjalanan memiliki cerita, dan ini adalah cerita tentang dua hati yang dipertemukan, tumbuh bersama, dan memilih untuk melangkah ke masa depan yang sama.","photo_hero":"../aset/section3_fotopengantin.webp","bottom_quote":"Bukan tentang siapa yang sempurna, tapi tentang dua orang yang memilih untuk selalu berjuang bersama."}'),
(4, 'section_4_event', 'Section 4: Waktu & Lokasi Acara', '{"headline":"Waktu\\nPernikahan","intro":"Dengan penuh rasa syukur, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dalam rangkaian acara pernikahan kami.","header_photo":"../aset/section4_header_cincin.webp","akad_name":"Akad Nikah","akad_date":"Minggu, 25 Oktober 2026","akad_time":"08.00 - 10.00 WIB","akad_place":"The Glass House, Jakarta","akad_address":"Jl. Bunga Melati No. 12, Jakarta Selatan","akad_photo":"../asetV2/dekorasi_akad_hd.webp","resepsi_name":"Resepsi Pernikahan","resepsi_date":"Minggu, 25 Oktober 2026","resepsi_time":"11.00 - 14.00 WIB","resepsi_place":"The Glass House, Jakarta","resepsi_address":"Jl. Bunga Melati No. 12, Jakarta Selatan","resepsi_photo":"../asetV2/tata_meja_taman_hd.webp","venue_headline":"Temui Kami Di Sini","venue_intro":"Kami sangat berharap kehadiran Bapak/Ibu/Saudara/i untuk menjadi bagian dari hari istimewa ini.","venue_name":"The Backyard Cafe","venue_address":"Jl. Melawai No. 123, Jakarta Selatan","venue_photo":"../aset/section4_bagianbawah.webp","maps_embed":"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.1!2d106.79!3d-6.24!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z!5e0!3m2!1sen!2sid!4v1","maps_link":"https://maps.google.com","features_parking":"Tersedia area parkir","features_transit":"Dapat diakses dengan transportasi umum","bottom_quote":"Tempat di mana cerita baru kami dimulai bersama orang-orang tercinta."}'),
(5, 'section_5_gallery', 'Section 5: Galeri Foto', '{"headline":"Momen\\nBerharga Bersama","intro":"Setiap foto menyimpan cerita, tentang tawa, perjalanan, dan cinta yang tumbuh.","hero_photo":"../aset/section5_fotopengantin.webp","hero_script":"Same\\nPeople\\nBrighter\\nDays","bottom_quote":"Cinta bukan tentang mencari seseorang yang sempurna, tetapi tentang melihat seseorang yang tidak sempurna dengan cara yang sempurna."}'),
(6, 'section_6_rsvp', 'Section 6: RSVP & Penutup', '{"headline":"Doa dan\\nUcapan","intro":"Terima kasih atas kehadiran, doa dan ucapan yang tulus untuk kami. Semoga segala kebaikan kembali kepada Anda.","banner_photo":"../aset/section6(01).webp","form_title":"Kirim Ucapan","form_intro":"Tuliskan doa, harapan, dan ucapan terbaik untuk kami.","confirm_box_title":"Konfirmasi\\nKehadiran","confirm_box_intro":"Mohon konfirmasi kehadiran Anda agar kami dapat mempersiapkan segala sesuatunya dengan lebih baik.","thanks_script":"Terima\\nKasih","closing_quote":"Doa Anda adalah hadiah\\nterindah bagi kami.","closing_subtext":"Terima kasih telah menjadi bagian dari hari istimewa kami.","footer_couple":"FAKHREL & OLIVIA","footer_date":"25 OKTOBER 2026"}');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
