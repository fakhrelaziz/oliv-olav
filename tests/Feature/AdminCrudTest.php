<?php

namespace Tests\Feature;

use App\Models\Acara;
use App\Models\CeritaCinta;
use App\Models\DoaUcapan;
use App\Models\GaleriFoto;
use App\Models\Pengantin;
use App\Models\SectionKonten;
use App\Models\Tamu;
use App\Models\User;
use Database\Seeders\WeddingInitialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingInitialSeeder::class);
        $this->admin = User::query()->where('email', 'admin@relliv.com')->firstOrFail();
    }

    public function test_admin_can_view_all_cms_pages(): void
    {
        $this->actingAs($this->admin)->get('/admin')->assertOk();
        $this->actingAs($this->admin)
            ->get('/admin/konten')
            ->assertOk()
            ->assertDontSee('Pusat Pengelolaan 6 Section Website Undangan');
        $this->actingAs($this->admin)->get('/admin/konten/hero')->assertOk()->assertDontSee('side_text');
        $this->actingAs($this->admin)->get('/admin/konten/pengantin')->assertOk();
        $this->actingAs($this->admin)->get('/admin/konten/acara')->assertOk()->assertSee('adminMapsPreview');
        $this->actingAs($this->admin)->get('/admin/konten/penutup')->assertOk()->assertSee('DANA')->assertSee('SeaBank');
        $this->actingAs($this->admin)->get('/admin/konten/story')->assertRedirect('/admin/story');
        $this->actingAs($this->admin)->get('/admin/konten/galeri')->assertRedirect('/admin/galeri');
        $this->actingAs($this->admin)->get('/admin/story')->assertOk();
        $this->actingAs($this->admin)->get('/admin/galeri')->assertOk()->assertSee('hero_script');
        $this->actingAs($this->admin)->get('/admin/tamu')->assertOk();
        $this->actingAs($this->admin)->get('/admin/rsvp')->assertOk();
    }

    public function test_admin_can_update_hero_sync_calendar_date_and_reset_single_field(): void
    {
        $this->actingAs($this->admin)
            ->put('/admin/konten/hero', [
                'tagline' => 'THE WEDDING OF',
                'groom_nickname' => 'Arka',
                'bride_nickname' => 'Nadia',
                'wedding_date' => '14 FEBRUARI 2027',
                'salam_text' => 'Salam hangat untuk seluruh tamu.',
                'script_text' => "A new\njourney",
                'city' => 'BANDUNG, INDONESIA',
                'bg_desktop' => '/aset/background_herosection.webp',
                'bg_mobile' => '/aset/background_herosection.webp',
            ])
            ->assertRedirect();

        $this->get('/')
            ->assertOk()
            ->assertSee('Arka')
            ->assertSee('Nadia')
            ->assertSee('14 FEBRUARI 2027')
            ->assertSee('dates=20270214T010000Z/20270214T070000Z', false);

        // Single field photo reset via JSON
        $this->actingAs($this->admin)
            ->postJson('/admin/konten/hero/reset', ['field' => 'bg_desktop'])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'groom_nickname' => 'Arka',
                    'bg_desktop' => '/aset/background_herosection(desktop).webp',
                ],
            ]);

        // Full section reset
        $this->actingAs($this->admin)
            ->post('/admin/konten/hero/reset')
            ->assertRedirect();

        $this->get('/')
            ->assertSee('Fakhrel')
            ->assertSee('dates=20261225T010000Z/20261225T070000Z', false);
    }

    public function test_admin_can_update_and_reset_section_content_with_pengantin_sync(): void
    {
        $this->actingAs($this->admin)
            ->put('/admin/konten/pengantin', [
                'headline' => "Kisah\nBaru",
                'intro_1' => 'Pengantar baru satu',
                'intro_2' => 'Pengantar baru dua',
                'groom_nickname' => 'Fakhrel Updated',
                'groom_fullname' => 'Pratama',
                'groom_parents' => 'Bapak Surya & Ibu Ratna',
                'groom_instagram' => '',
                'bride_nickname' => 'Olivia Updated',
                'bride_fullname' => 'Putri',
                'bride_parents' => 'Bapak Hendra & Ibu Sari',
                'bride_instagram' => 'https://instagram.com/olivia',
                'bottom_quote' => 'Quote cinta baru',
            ])
            ->assertRedirect();

        $groom = Pengantin::query()->where('peran', 'groom')->firstOrFail();
        $this->assertSame('Fakhrel Updated', $groom->nama_panggilan);
        $this->assertSame('', $groom->instagram);

        $this->get('/')
            ->assertSee('Fakhrel Updated')
            ->assertSee('Quote cinta baru')
            ->assertDontSee('id="front-groom-instagram"', false)
            ->assertSee('id="front-bride-instagram"', false);

        // Submitting empty required nickname falls back safely to default instead of null
        $this->actingAs($this->admin)
            ->put('/admin/konten/pengantin', [
                'groom_nickname' => '',
                'bride_nickname' => '',
            ])
            ->assertRedirect();

        $groom->refresh();
        $this->assertSame('Fakhrel', $groom->nama_panggilan);

        // Reset section back to defaults
        $this->actingAs($this->admin)
            ->post('/admin/konten/pengantin/reset')
            ->assertRedirect();

        $groom->refresh();
        $this->assertSame('Fakhrel', $groom->nama_panggilan);
    }

    public function test_admin_can_update_acara_extract_maps_iframe_and_sync_acara_table(): void
    {
        $iframeSnippet = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!TEST_EXTRACT_123" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';

        $this->actingAs($this->admin)
            ->put('/admin/konten/acara', [
                'akad_name' => 'Akad Nikah',
                'akad_place' => 'Masjid Istiqlal, Jakarta',
                'akad_address' => 'Jl. Taman Wijaya Kusuma, Jakarta Pusat',
                'resepsi_name' => 'Resepsi Pernikahan',
                'resepsi_place' => 'Hotel Mulia Senayan',
                'resepsi_address' => 'Jl. Asia Afrika, Jakarta Pusat',
                'maps_embed' => $iframeSnippet,
                'maps_link' => 'https://maps.google.com/?q=Hotel+Mulia',
            ])
            ->assertRedirect();

        $saved = SectionKonten::query()->where('section_key', 'section_4_event')->firstOrFail();
        $this->assertSame(
            'https://www.google.com/maps/embed?pb=!1m18!TEST_EXTRACT_123',
            $saved->konten_json['maps_embed']
        );

        $this->assertDatabaseHas('acara', ['tempat' => 'Masjid Istiqlal, Jakarta']);
        $this->assertDatabaseHas('acara', ['tempat' => 'Hotel Mulia Senayan']);

        $this->get('/')
            ->assertOk()
            ->assertSee('https://www.google.com/maps/embed?pb=!1m18!TEST_EXTRACT_123', false)
            ->assertSee('Hotel Mulia Senayan');

        // Empty maps_embed falls back safely to default URL
        $this->actingAs($this->admin)
            ->put('/admin/konten/acara', ['maps_embed' => ''])
            ->assertRedirect();

        $saved->refresh();
        $this->assertStringStartsWith('https://www.google.com/maps/embed?', $saved->konten_json['maps_embed']);

        $this->actingAs($this->admin)
            ->post('/admin/konten/acara/reset')
            ->assertRedirect();

        $this->assertDatabaseHas('acara', ['tempat' => 'The Glass House, Jakarta']);
    }

    public function test_admin_can_update_and_reset_story_gallery_and_penutup_sections(): void
    {
        $this->actingAs($this->admin)
            ->put('/admin/konten/story', [
                'headline' => "Perjalanan\nCinta",
                'intro' => 'Pengantar kisah baru.',
                'bottom_quote' => 'Quote kisah baru.',
            ])
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->put('/admin/konten/galeri', [
                'headline' => "Bingkai\nKenangan",
                'intro' => 'Pengantar galeri baru.',
                'hero_script' => "Forever\nStarts\nHere",
                'bottom_quote' => 'Quote galeri baru.',
            ])
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->put('/admin/konten/penutup', [
                'ewallet_name' => 'DANA',
                'ewallet_number' => '08566601948',
                'ewallet_holder' => 'Fakhrel & Olivia',
                'bank_name' => 'SeaBank',
                'bank_number' => '901905120917',
                'bank_holder' => 'Fakhrel & Olivia',
                'closing_subtext' => 'Sampai jumpa di hari bahagia kami.',
            ])
            ->assertRedirect();

        $this->get('/')
            ->assertOk()
            ->assertSee('Quote kisah baru.')
            ->assertSee('Forever')
            ->assertSee('Quote galeri baru.')
            ->assertSee('Sampai jumpa di hari bahagia kami.');

        $this->actingAs($this->admin)->post('/admin/konten/story/reset')->assertRedirect();
        $this->actingAs($this->admin)->post('/admin/konten/galeri/reset')->assertRedirect();
        $this->actingAs($this->admin)->post('/admin/konten/penutup/reset')->assertRedirect();
    }

    public function test_admin_can_crud_story_milestones(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/story', [
                'judul' => 'Momen Lamaran Spesial',
                'tanggal_momen' => '10 Januari 2026',
                'deskripsi' => 'Hari penuh kebahagiaan bersama kedua keluarga.',
                'foto_url' => '/aset/section3_fotopengantin(3).webp',
                'urutan' => 9,
            ])
            ->assertRedirect('/admin/story');

        $story = CeritaCinta::query()->where('judul', 'Momen Lamaran Spesial')->firstOrFail();
        $this->assertSame($this->admin->id, $story->user_id);

        $this->actingAs($this->admin)
            ->put("/admin/story/{$story->id}", [
                'judul' => 'Momen Lamaran Diubah',
                'tanggal_momen' => '12 Januari 2026',
                'deskripsi' => 'Deskripsi terbaru.',
                'foto_url' => '/aset/section3_fotopengantin(3).webp',
                'urutan' => 9,
            ])
            ->assertRedirect('/admin/story');

        $this->assertDatabaseHas('cerita_cinta', ['id' => $story->id, 'judul' => 'Momen Lamaran Diubah']);

        $this->actingAs($this->admin)
            ->delete("/admin/story/{$story->id}")
            ->assertRedirect('/admin/story');

        $this->assertDatabaseMissing('cerita_cinta', ['id' => $story->id]);
    }

    public function test_admin_can_upload_media_and_crud_gallery(): void
    {
        Storage::fake('public');

        $png1x1 = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $file = UploadedFile::fake()->createWithContent('test_photo.png', $png1x1);

        $uploadRes = $this->actingAs($this->admin)
            ->postJson('/admin/media/upload', [
                'image' => $file,
                'section' => 'gallery',
            ]);

        $uploadRes->assertCreated()
            ->assertJson(['success' => true]);

        $uploadedUrl = $uploadRes->json('url');
        $uploadedPath = $uploadRes->json('path');
        Storage::disk('public')->assertExists($uploadedPath);

        // Save photo into gallery with caption (keterangan)
        $this->actingAs($this->admin)
            ->post('/admin/galeri', [
                'judul' => 'Foto Prewedding Test',
                'keterangan' => 'Sesi sore hari di taman',
                'kategori' => 'prewedding',
                'foto_url' => $uploadedUrl,
                'urutan' => 99,
            ])
            ->assertRedirect('/admin/galeri');

        $photo = GaleriFoto::query()->where('judul', 'Foto Prewedding Test')->firstOrFail();
        $this->assertSame('Sesi sore hari di taman', $photo->keterangan);

        // Update gallery photo and caption
        $this->actingAs($this->admin)
            ->put("/admin/galeri/{$photo->id}", [
                'judul' => 'Foto Prewedding Updated',
                'keterangan' => 'Caption lightbox terbaru',
                'kategori' => 'acara',
                'foto_url' => $uploadedUrl,
                'urutan' => 100,
            ])
            ->assertRedirect('/admin/galeri');

        $this->assertDatabaseHas('galeri_foto', [
            'id' => $photo->id,
            'judul' => 'Foto Prewedding Updated',
            'keterangan' => 'Caption lightbox terbaru',
            'kategori' => 'acara',
        ]);

        // Clearing caption sets keterangan to null
        $this->actingAs($this->admin)
            ->put("/admin/galeri/{$photo->id}", [
                'judul' => 'Foto Prewedding Updated',
                'keterangan' => '',
                'kategori' => 'acara',
                'foto_url' => $uploadedUrl,
                'urutan' => 100,
            ])
            ->assertRedirect('/admin/galeri');

        $photo->refresh();
        $this->assertNull($photo->keterangan);

        // Delete photo & verify unreferenced storage file is cleaned up
        $this->actingAs($this->admin)
            ->delete("/admin/galeri/{$photo->id}")
            ->assertRedirect('/admin/galeri');

        $this->assertDatabaseMissing('galeri_foto', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing($uploadedPath);
    }

    public function test_admin_can_crud_guests_with_unique_slug(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/tamu', [
                'nama' => 'Raden Mas Danu',
                'no_telepon' => '08123456789',
                'alamat' => 'Jakarta',
                'jumlah_undangan' => 2,
                'status' => 'Belum Konfirmasi',
            ])
            ->assertRedirect('/admin/tamu');

        $this->actingAs($this->admin)
            ->post('/admin/tamu', [
                'nama' => 'Raden Mas Danu',
                'no_telepon' => '08129876543',
                'alamat' => 'Bandung',
                'jumlah_undangan' => 3,
                'status' => 'Hadir',
            ])
            ->assertRedirect('/admin/tamu');

        $guests = Tamu::query()->where('nama', 'Raden Mas Danu')->orderBy('id')->get();
        $this->assertCount(2, $guests);
        $this->assertSame('raden-mas-danu', $guests[0]->slug);
        $this->assertSame('raden-mas-danu-2', $guests[1]->slug);

        // Update guest name & status -> slug regenerates cleanly
        $this->actingAs($this->admin)
            ->put("/admin/tamu/{$guests[0]->id}", [
                'nama' => 'Raden Mas Danu Wijaya',
                'no_telepon' => '08123456780',
                'alamat' => 'Surabaya',
                'jumlah_undangan' => 4,
                'status' => 'Hadir',
            ])
            ->assertRedirect('/admin/tamu');

        $guests[0]->refresh();
        $this->assertSame('raden-mas-danu-wijaya', $guests[0]->slug);
        $this->assertSame('Hadir', $guests[0]->status);

        // Filter & search verify clean /u/{slug} copy link and Reset button
        $this->actingAs($this->admin)
            ->get('/admin/tamu?q=Wijaya&status=Hadir')
            ->assertOk()
            ->assertSee('Raden Mas Danu Wijaya')
            ->assertSee('/u/raden-mas-danu-wijaya')
            ->assertSee('Reset');

        $this->actingAs($this->admin)
            ->delete("/admin/tamu/{$guests[1]->id}")
            ->assertRedirect('/admin/tamu');

        $this->assertDatabaseMissing('tamu', ['id' => $guests[1]->id]);
    }

    public function test_admin_can_moderate_wishes_toggle_visibility_and_export_csv(): void
    {
        // Admin creates a wish manually
        $this->actingAs($this->admin)
            ->post('/admin/rsvp', [
                'nama_pengirim' => 'Keluarga Besar Jogja',
                'status_kehadiran' => 'Hadir',
                'pesan_ucapan' => 'Barakallahu lakuma untuk Fakhrel dan Olivia!',
            ])
            ->assertRedirect('/admin/rsvp');

        $wish = DoaUcapan::query()->where('nama_pengirim', 'Keluarga Besar Jogja')->firstOrFail();

        // Filter & search
        $this->actingAs($this->admin)
            ->get('/admin/rsvp?q=Jogja&status=Hadir')
            ->assertOk()
            ->assertSee('Keluarga Besar Jogja')
            ->assertSee('Reset');

        // Edit wish and hide via is_tampil = 0
        $this->actingAs($this->admin)
            ->put("/admin/rsvp/{$wish->id}", [
                'nama_pengirim' => 'Keluarga Besar Yogyakarta',
                'status_kehadiran' => 'Masih Ragu',
                'pesan_ucapan' => 'Doa terbaik dari Jogja!',
                'is_tampil' => '0',
            ])
            ->assertRedirect('/admin/rsvp');

        $wish->refresh();
        $this->assertSame('Keluarga Besar Yogyakarta', $wish->nama_pengirim);
        $this->assertFalse((bool) $wish->is_tampil);

        // Toggle visibility back to public
        $this->actingAs($this->admin)
            ->patch("/admin/rsvp/{$wish->id}/toggle-visibility")
            ->assertRedirect('/admin/rsvp');

        $wish->refresh();
        $this->assertTrue((bool) $wish->is_tampil);

        // Export CSV
        $exportRes = $this->actingAs($this->admin)->get('/admin/rsvp/export');
        $exportRes->assertOk();
        $this->assertStringContainsString('text/csv', (string) $exportRes->headers->get('Content-Type'));

        // Delete wish
        $this->actingAs($this->admin)
            ->delete("/admin/rsvp/{$wish->id}")
            ->assertRedirect('/admin/rsvp');

        $this->assertDatabaseMissing('doa_ucapan', ['id' => $wish->id]);
    }
}
