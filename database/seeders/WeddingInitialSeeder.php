<?php

namespace Database\Seeders;

use App\Models\Acara;
use App\Models\CeritaCinta;
use App\Models\DoaUcapan;
use App\Models\GaleriFoto;
use App\Models\KontenAcara;
use App\Models\KunjunganLog;
use App\Models\Pengantin;
use App\Models\SectionKonten;
use App\Models\Tamu;
use App\Models\User;
use App\Services\SectionContentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WeddingInitialSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@relliv.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('wedding2026'),
                'role' => 'admin',
            ]
        );

        // 1. Pengantin
        Pengantin::query()->updateOrCreate(
            ['user_id' => $admin->id, 'peran' => 'groom'],
            [
                'nama_panggilan' => 'Fakhrel',
                'nama_lengkap' => 'Fakhrel Pratama',
                'nama_ayah' => 'Bapak Surya Pratama',
                'nama_ibu' => 'Ibu Ratna Dewi',
                'urutan_anak' => 'Putra pertama dari',
                'foto_url' => '/aset/section2_fotopengantin(2).webp',
                'instagram' => '@fakhrel.pratama',
                'bio_singkat' => 'Seorang pecinta kesederhanaan yang menemukan rumah di senyuman Olivia.',
            ]
        );

        Pengantin::query()->updateOrCreate(
            ['user_id' => $admin->id, 'peran' => 'bride'],
            [
                'nama_panggilan' => 'Olivia',
                'nama_lengkap' => 'Olivia Putri',
                'nama_ayah' => 'Bapak Hendra Wijaya',
                'nama_ibu' => 'Ibu Sari Lestari',
                'urutan_anak' => 'Putri kedua dari',
                'foto_url' => '/aset/section2_fotopengantin(3).webp',
                'instagram' => '@olivia.putri',
                'bio_singkat' => 'Menemukan kedamaian dan kehangatan dalam setiap langkah bersama Fakhrel.',
            ]
        );

        // 2. Rangkaian Acara
        if (Acara::query()->count() === 0) {
            Acara::query()->insert([
                [
                    'user_id' => $admin->id,
                    'nama_acara' => 'Akad Nikah',
                    'tanggal' => '2026-12-25',
                    'waktu_mulai' => '08:00:00',
                    'waktu_selesai' => '10:00:00',
                    'zona_waktu' => 'WIB',
                    'tempat' => 'The Glass House, Jakarta',
                    'alamat' => 'Jl. Bunga Melati No. 12, Jakarta Selatan',
                    'google_maps_url' => 'https://maps.google.com',
                    'urutan' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'user_id' => $admin->id,
                    'nama_acara' => 'Resepsi Pernikahan',
                    'tanggal' => '2026-12-25',
                    'waktu_mulai' => '11:00:00',
                    'waktu_selesai' => '14:00:00',
                    'zona_waktu' => 'WIB',
                    'tempat' => 'The Glass House, Jakarta',
                    'alamat' => 'Jl. Bunga Melati No. 12, Jakarta Selatan',
                    'google_maps_url' => 'https://maps.google.com',
                    'urutan' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 3. Galeri Foto (10 HD Bento + 4 Picapica Photobooth Strips)
        if (GaleriFoto::query()->count() === 0) {
            $photos = [
                ['Melangkah Bersama di Bawah Rindang Pepohonan', 'prewedding', '/aset/section5_galerifoto(5).webp', 'Melangkah Bersama di Bawah Rindang Pepohonan', 1, true],
                ['Simbol Ikatan Suci & Janji Setia', 'acara', '/aset/cincin_tunangan_hd.webp', 'Simbol Ikatan Suci & Janji Setia', 2, false],
                ['Genggaman Hangat Menuju Hari Bahagia', 'prewedding', '/aset/foto_hd_soft.webp', 'Genggaman Hangat Menuju Hari Bahagia', 3, true],
                ['Menatap Langit & Merajut Masa Depan', 'prewedding', '/aset/section5_galerifoto(9).webp', 'Menatap Langit & Merajut Masa Depan', 4, false],
                ['Saling Menjaga dalam Setiap Langkah', 'prewedding', '/aset/langit_gandengan_hd.webp', 'Saling Menjaga dalam Setiap Langkah', 5, false],
                ['Bersandar Tenang Menatap Hari Esok', 'keseharian', '/aset/cover_hd.webp', 'Bersandar Tenang Menatap Hari Esok', 6, false],
                ['Genggaman Tangan yang Tak Pernah Lepas', 'acara', '/aset/section2_fotopengantin.webp', 'Genggaman Tangan yang Tak Pernah Lepas', 7, false],
                ['Tawa Hangat di Hamparan Padang Rumput', 'keseharian', '/aset/hd_3.webp', 'Tawa Hangat di Hamparan Padang Rumput', 8, true],
                ['A Chapter of Us — Lembaran Kisah Kami', 'acara', '/aset/section5_galerifoto(3).webp', 'A Chapter of Us — Lembaran Kisah Kami', 9, false],
                ['Potret Kasih di Bawah Langit Teduh', 'prewedding', '/aset/section5_galerifoto(7)webp.webp', 'Potret Kasih di Bawah Langit Teduh', 10, true],
                ['Picapica Photobooth — Sweet Smiles', 'keseharian', '/aset/photobooth_strip_1.webp', 'Picapica Studio · 09/23/2026 02:59 PM', 11, false],
                ['Picapica Photobooth — Peace & Dimples', 'keseharian', '/aset/photobooth_strip_2.webp', 'Picapica Studio · 09/23/2026 03:02 PM', 12, false],
                ['Picapica Photobooth — Classic Noir Frame', 'keseharian', '/aset/photobooth_strip_3.webp', 'Picapica Studio · Noir Edition 03:02 PM', 13, false],
                ['Picapica Photobooth — Playful & Waving', 'keseharian', '/aset/photobooth_strip_4.webp', 'Picapica Studio · 09/23/2026 03:06 PM', 14, false],
            ];

            foreach ($photos as [$judul, $kategori, $url, $ket, $urutan, $featured]) {
                GaleriFoto::query()->create([
                    'user_id' => $admin->id,
                    'judul' => $judul,
                    'kategori' => $kategori,
                    'foto_url' => $url,
                    'keterangan' => $ket,
                    'urutan' => $urutan,
                    'is_featured' => $featured,
                ]);
            }
        }

        // 4. Cerita Cinta (5 Milestones)
        if (CeritaCinta::query()->count() === 0) {
            $stories = [
                ['Pertemuan Pertama', '14 Februari 2020', 'Pertemuan tak sengaja di perpustakaan kampus yang menjadi awal mula dari percakapan tak berujung.', '/aset/section3_fotopengantin(2).webp', 1],
                ['Rasa yang Tumbuh', '20 Oktober 2021', 'Berawal dari teman diskusi, perlahan tumbuh rasa nyaman yang membuat setiap hari terasa lebih berwarna.', '/aset/section3_fotopengantin(3).webp', 2],
                ['Komitmen Bersama', '10 Agustus 2023', 'Kami menyadari bahwa ini bukan hanya sekadar rasa suka, tapi cinta yang ingin terus dijaga dalam suka maupun duka.', '/aset/section3_fotopengantin(5).webp', 3],
                ['Hari Lamaran', '15 Desember 2025', 'Di hadapan kedua keluarga besar, kami mengikrarkan niat tulus untuk melangkah ke jenjang pernikahan yang suci.', '/aset/section3_fotopengantin(6).webp', 4],
                ['Menuju Hari Bahagia', '25 Desember 2026', 'Kini, kami siap memulai babak baru sebagai suami dan istri. Bukan akhir dari cerita, tapi awal dari selamanya.', '/aset/section3_fotopengantin(4).webp', 5],
            ];

            foreach ($stories as [$judul, $tgl, $desc, $url, $urutan]) {
                CeritaCinta::query()->create([
                    'user_id' => $admin->id,
                    'judul' => $judul,
                    'tanggal_momen' => $tgl,
                    'deskripsi' => $desc,
                    'foto_url' => $url,
                    'urutan' => $urutan,
                ]);
            }
        }

        // 5. Konten Acara Ringkas
        if (KontenAcara::query()->count() === 0) {
            $items = [
                ['Tempat & Lokasi', 'Informasi lokasi dan alamat venue.', 'Lokasi', '/aset/section4_fotoresepsipernikahan.webp', '/admin/konten/acara', 1],
                ['Susunan Acara', 'Rangkaian acara pernikahan.', 'Acara', '/aset/section4_fotoakadnikah.webp', '/admin/konten/acara', 2],
                ['Galeri Foto', 'Kumpulan momen spesial kami.', 'Galeri', '/aset/section5_galerifoto.webp', '/admin/galeri', 3],
                ['Kisah Kami', 'Cerita perjalanan cinta kami.', 'Tentang Kami', '/aset/section3_fotopengantin.webp', '/admin/story', 4],
                ['Ucapan Terima Kasih', 'Pesan dan doa untuk kami.', 'Lainnya', '/aset/section2_fotopengantin.webp', '/admin/rsvp', 5],
            ];
            foreach ($items as [$judul, $desc, $kat, $thumb, $link, $urutan]) {
                KontenAcara::query()->create([
                    'user_id' => $admin->id,
                    'judul' => $judul,
                    'deskripsi' => $desc,
                    'kategori' => $kat,
                    'thumbnail_url' => $thumb,
                    'link_halaman' => $link,
                    'urutan' => $urutan,
                ]);
            }
        }

        // 6. Data Tamu Undangan
        $tamuMap = [];
        if (Tamu::query()->count() === 0) {
            $guests = [
                ['ahmad-fauzi', 'Ahmad Fauzi', '081234567890', 2, 'Hadir', 2, true],
                ['siti-nurhaliza', 'Siti Nurhaliza', '081234567891', 1, 'Belum Konfirmasi', 0, true],
                ['rizky-maulana', 'Rizky Maulana', '081234567892', 3, 'Hadir', 3, true],
                ['dewi-lestari', 'Dewi Lestari', '081234567893', 2, 'Tidak Hadir', 0, true],
                ['fajar-ramadhan', 'Fajar Ramadhan', '081234567894', 1, 'Hadir', 1, true],
                ['putri-ananda', 'Putri Ananda', '081234567895', 2, 'Hadir', 2, true],
                ['budi-santoso', 'Budi Santoso', '081234567896', 1, 'Belum Konfirmasi', 0, true],
                ['nadia-rahma', 'Nadia Rahma', '081234567897', 2, 'Hadir', 2, true],
            ];
            foreach ($guests as [$slug, $nama, $telp, $pax, $status, $aktual, $sent]) {
                $tamuMap[$slug] = Tamu::query()->create([
                    'user_id' => $admin->id,
                    'slug' => $slug,
                    'nama' => $nama,
                    'no_telepon' => $telp,
                    'jumlah_undangan' => $pax,
                    'status' => $status,
                    'jumlah_hadir_aktual' => $aktual,
                    'is_terkirim' => $sent,
                ]);
            }
        }

        // 7. Doa & Ucapan RSVP
        if (DoaUcapan::query()->count() === 0) {
            $wishes = [
                [$tamuMap['ahmad-fauzi']->id ?? null, 'Ahmad Fauzi', 'Hadir', 2, 'Turut berbahagia, semoga lancar sampai hari H. Amin. 🙏', now()->subDays(1)],
                [null, 'Siti Rahma', 'Hadir', 2, 'Selamat yaa, semoga menjadi keluarga sakinah, mawadah, warahmah. 🤍', now()->subDays(2)],
                [$tamuMap['dewi-lestari']->id ?? null, 'Dewi Lestari', 'Tidak Hadir', 0, 'Maaf tidak bisa hadir, semoga selalu diberi keberkahan. 🙏', now()->subDays(3)],
                [$tamuMap['rizky-maulana']->id ?? null, 'Rizky Maulana', 'Hadir', 3, 'Happy wedding! Semoga selalu bahagia dan diberi rezeki yang melimpah. 🤍', now()->subDays(4)],
                [null, 'Nadia Putri', 'Masih Ragu', 1, 'InsyaAllah berusaha hadir, mohon dikonfirmasi kembali. 🙏', now()->subDays(5)],
            ];
            foreach ($wishes as [$tamuId, $nama, $status, $pax, $pesan, $created]) {
                DoaUcapan::query()->create([
                    'tamu_id' => $tamuId,
                    'nama_pengirim' => $nama,
                    'status_kehadiran' => $status,
                    'jumlah_kehadiran' => $pax,
                    'pesan_ucapan' => $pesan,
                    'is_tampil' => true,
                    'created_at' => $created,
                ]);
            }
        }

        // 8. Kunjungan Log Awal
        if (KunjunganLog::query()->count() === 0) {
            foreach ([2, 1, 0] as $daysAgo) {
                KunjunganLog::query()->create([
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'halaman' => 'undangan',
                    'visited_at' => now()->subDays($daysAgo),
                ]);
            }
        }

        // 9. Konten 6 Section Dinamis (Single Source of Truth)
        foreach (SectionContentService::defaults() as $sectionKey => $def) {
            SectionKonten::query()->updateOrCreate(
                ['section_key' => $sectionKey],
                [
                    'nama_section' => $def['nama_section'],
                    'konten_json' => $def['data'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}
