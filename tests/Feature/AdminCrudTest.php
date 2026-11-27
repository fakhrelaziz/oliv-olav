<?php

namespace Tests\Feature;

use App\Models\CeritaCinta;
use App\Models\DoaUcapan;
use App\Models\GaleriFoto;
use App\Models\Pengantin;
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
        $this->actingAs($this->admin)->get('/admin/konten')->assertOk();
        $this->actingAs($this->admin)->get('/admin/konten/hero')->assertOk();
        $this->actingAs($this->admin)->get('/admin/konten/pengantin')->assertOk();
        $this->actingAs($this->admin)->get('/admin/konten/acara')->assertOk();
        $this->actingAs($this->admin)->get('/admin/konten/penutup')->assertOk();
        $this->actingAs($this->admin)->get('/admin/story')->assertOk();
        $this->actingAs($this->admin)->get('/admin/galeri')->assertOk();
        $this->actingAs($this->admin)->get('/admin/tamu')->assertOk();
        $this->actingAs($this->admin)->get('/admin/rsvp')->assertOk();
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
                'bride_nickname' => 'Olivia Updated',
                'bride_fullname' => 'Putri',
                'bride_parents' => 'Bapak Hendra & Ibu Sari',
                'bottom_quote' => 'Quote cinta baru',
            ])
            ->assertRedirect();

        $groom = Pengantin::query()->where('peran', 'groom')->firstOrFail();
        $this->assertSame('Fakhrel Updated', $groom->nama_panggilan);

        $this->get('/')->assertSee('Fakhrel Updated')->assertSee('Quote cinta baru');

        // Reset section back to defaults
        $this->actingAs($this->admin)
            ->post('/admin/konten/pengantin/reset')
            ->assertRedirect();

        $groom->refresh();
        $this->assertSame('Fakhrel', $groom->nama_panggilan);
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

        // Save photo into gallery
        $this->actingAs($this->admin)
            ->post('/admin/galeri', [
                'judul' => 'Foto Prewedding Test',
                'kategori' => 'prewedding',
                'foto_url' => $uploadedUrl,
                'urutan' => 99,
            ])
            ->assertRedirect('/admin/galeri');

        $photo = GaleriFoto::query()->where('judul', 'Foto Prewedding Test')->firstOrFail();

        // Update gallery photo
        $this->actingAs($this->admin)
            ->put("/admin/galeri/{$photo->id}", [
                'judul' => 'Foto Prewedding Updated',
                'kategori' => 'acara',
                'foto_url' => $uploadedUrl,
                'urutan' => 100,
            ])
            ->assertRedirect('/admin/galeri');

        $this->assertDatabaseHas('galeri_foto', ['id' => $photo->id, 'judul' => 'Foto Prewedding Updated', 'kategori' => 'acara']);

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

        $this->actingAs($this->admin)
            ->delete("/admin/tamu/{$guests[1]->id}")
            ->assertRedirect('/admin/tamu');

        $this->assertDatabaseMissing('tamu', ['id' => $guests[1]->id]);
    }

    public function test_admin_can_moderate_wishes_toggle_visibility_and_export_csv(): void
    {
        $wish = DoaUcapan::query()->firstOrFail();
        $initialVisible = (bool) $wish->is_tampil;

        $this->actingAs($this->admin)
            ->patch("/admin/rsvp/{$wish->id}/toggle-visibility")
            ->assertRedirect('/admin/rsvp');

        $wish->refresh();
        $this->assertSame(! $initialVisible, (bool) $wish->is_tampil);

        $exportRes = $this->actingAs($this->admin)->get('/admin/rsvp/export');
        $exportRes->assertOk();
        $this->assertStringContainsString('text/csv', (string) $exportRes->headers->get('Content-Type'));
    }
}
