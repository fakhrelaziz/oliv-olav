<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengantin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('peran', ['groom', 'bride']);
            $table->string('nama_panggilan', 50);
            $table->string('nama_lengkap', 150);
            $table->string('nama_ayah', 100)->nullable();
            $table->string('nama_ibu', 100)->nullable();
            $table->string('urutan_anak', 100)->nullable();
            $table->longText('foto_url')->nullable();
            $table->string('instagram', 100)->nullable();
            $table->text('bio_singkat')->nullable();
            $table->timestamps();
        });

        Schema::create('acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama_acara', 100);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->string('zona_waktu', 10)->default('WIB');
            $table->string('tempat', 150);
            $table->text('alamat');
            $table->text('google_maps_url')->nullable();
            $table->string('catatan', 255)->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        Schema::create('galeri_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul', 150)->nullable();
            $table->enum('kategori', ['prewedding', 'acara', 'keseharian', 'lainnya'])->default('prewedding');
            $table->longText('foto_url');
            $table->string('keterangan', 255)->nullable();
            $table->integer('urutan')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('konten_acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul', 150);
            $table->string('deskripsi', 255)->nullable();
            $table->enum('kategori', ['Lokasi', 'Acara', 'Galeri', 'Tentang Kami', 'Lainnya']);
            $table->longText('thumbnail_url')->nullable();
            $table->string('link_halaman', 100)->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('cerita_cinta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul', 150);
            $table->string('tanggal_momen', 50);
            $table->text('deskripsi');
            $table->longText('foto_url')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        Schema::create('kunjungan_log', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('user_agent', 255)->nullable();
            $table->string('halaman', 100)->default('undangan');
            $table->timestamp('visited_at')->useCurrent()->index();
        });

        Schema::create('section_konten', function (Blueprint $table) {
            $table->id();
            $table->string('section_key', 50)->unique();
            $table->string('nama_section', 100);
            $table->longText('konten_json');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('slug', 100)->unique();
            $table->string('nama', 150);
            $table->string('no_telepon', 20)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->unsignedInteger('jumlah_undangan')->default(1);
            $table->enum('status', ['Hadir', 'Tidak Hadir', 'Belum Konfirmasi', 'Masih Ragu'])
                ->default('Belum Konfirmasi')
                ->index();
            $table->unsignedInteger('jumlah_hadir_aktual')->default(0);
            $table->boolean('is_terkirim')->default(false);
            $table->dateTime('waktu_kirim')->nullable();
            $table->timestamps();
        });

        Schema::create('doa_ucapan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tamu_id')->nullable()->constrained('tamu')->nullOnDelete()->cascadeOnUpdate();
            $table->string('nama_pengirim', 150);
            $table->enum('status_kehadiran', ['Hadir', 'Tidak Hadir', 'Masih Ragu'])
                ->default('Hadir')
                ->index();
            $table->unsignedInteger('jumlah_kehadiran')->default(1);
            $table->text('pesan_ucapan');
            $table->boolean('is_tampil')->default(true);
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doa_ucapan');
        Schema::dropIfExists('tamu');
        Schema::dropIfExists('section_konten');
        Schema::dropIfExists('kunjungan_log');
        Schema::dropIfExists('cerita_cinta');
        Schema::dropIfExists('konten_acara');
        Schema::dropIfExists('galeri_foto');
        Schema::dropIfExists('acara');
        Schema::dropIfExists('pengantin');
    }
};
