<?php

namespace Tests\Feature;

use App\Models\KunjunganLog;
use App\Models\Tamu;
use Database\Seeders\WeddingInitialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingInitialSeeder::class);
    }

    public function test_public_invitation_renders_all_sections_and_seeded_data(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Fakhrel')
            ->assertSee('Olivia')
            ->assertSee('Tamu Undangan')
            ->assertSee('Buka Undangan')
            ->assertSee('Kisah')
            ->assertSee('Akad Nikah')
            ->assertSee('Resepsi Pernikahan')
            ->assertSee('Our Photobooth Strips')
            ->assertSee('Tanda')
            ->assertSee('DANA')
            ->assertSee('08566601948')
            ->assertSee('SeaBank')
            ->assertSee('901905120917');
    }

    public function test_public_invitation_personalizes_guest_via_query_parameter(): void
    {
        $response = $this->get('/?to=Bapak+Ahmad+Santoso');

        $response->assertOk()
            ->assertSee('Bapak Ahmad Santoso');
    }

    public function test_public_invitation_personalizes_guest_via_slug_route(): void
    {
        $adminId = \App\Models\User::query()->value('id');
        $guest = Tamu::query()->create([
            'user_id' => $adminId,
            'nama' => 'Dr. Nadia Kusuma',
            'slug' => 'dr-nadia-kusuma',
            'alamat' => 'Jakarta',
            'jumlah_undangan' => 2,
            'status' => 'Belum Konfirmasi',
            'jumlah_hadir_aktual' => 0,
        ]);

        $response = $this->get("/u/{$guest->slug}");

        $response->assertOk()
            ->assertSee('Dr. Nadia Kusuma');
    }

    public function test_visit_tracker_records_kunjungan_log(): void
    {
        $initialCount = KunjunganLog::query()->count();

        $response = $this->postJson('/track-visit', ['page' => '/']);

        $response->assertCreated()
            ->assertJson(['success' => true]);

        $this->assertSame($initialCount + 1, KunjunganLog::query()->count());
    }
}
