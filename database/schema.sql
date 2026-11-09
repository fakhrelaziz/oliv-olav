-- ====================================================================
-- DATABASE SCHEMA: db_fakhrel_olivia
-- SISTEM MANAJEMEN UNDANGAN PERNIKAHAN (LAV | LIV / OLAV & OLIV)
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
DROP TABLE IF EXISTS `konten_acara`;
DROP TABLE IF EXISTS `galeri_foto`;
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
  `foto_url` VARCHAR(255) DEFAULT NULL,
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
  `foto_url` VARCHAR(255) NOT NULL,
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
  `thumbnail_url` VARCHAR(255) DEFAULT NULL,
  `link_halaman` VARCHAR(100) DEFAULT NULL,
  `urutan` INT DEFAULT 1,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_konten_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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

-- 1. User Admin (Email: admin@olavoliv.com, Password: 'wedding2026')
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Administrator', 'admin@olavoliv.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- 2. Data Kedua Mempelai (Olav & Oliv)
INSERT INTO `pengantin` (`id`, `user_id`, `peran`, `nama_panggilan`, `nama_lengkap`, `nama_ayah`, `nama_ibu`, `urutan_anak`, `foto_url`, `instagram`, `bio_singkat`) VALUES
(1, 1, 'groom', 'Olav', 'Fakhrel Olav Al-Farabi', 'Bapak Surya', 'Ibu Ratna', 'Putra pertama dari', 'aset/section2_fotopengantin(3).webp', '@fakhrel.olav', 'Seorang pecinta kesederhanaan yang menemukan rumah di senyuman Oliv.'),
(2, 1, 'bride', 'Oliv', 'Olivia Anindita Wijaya', 'Bapak Jenderal Wijaya', 'Ibu Sari Lestari', 'Putri kedua dari', 'aset/section2_fotopengantin(2).webp', '@olivia.wijaya', 'Menemukan kedamaian dan kehangatan dalam setiap langkah bersama Olav.');

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

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
