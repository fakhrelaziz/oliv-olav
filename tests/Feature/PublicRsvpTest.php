<?php

namespace Tests\Feature;

use App\Models\DoaUcapan;
use App\Models\Tamu;
use Database\Seeders\WeddingInitialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRsvpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingInitialSeeder::class);
    }

    public function test_guest_can_fetch_visible_wishes_as_json(): void
    {
        DoaUcapan::query()->create([
            'nama_pengirim' => 'Hidden Guest',
            'status_kehadiran' => 'Hadir',
            'pesan_ucapan' => 'Should not be shown publicly',
            'is_tampil' => false,
            'created_at' => now(),
        ]);

        $response = $this->getJson('/rsvp/wishes');

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonMissing(['nama_pengirim' => 'Hidden Guest']);
    }

    public function test_guest_can_submit_rsvp_and_sync_matching_tamu_status(): void
    {
        $adminId = \App\Models\User::query()->value('id');
        $tamu = Tamu::query()->create([
            'user_id' => $adminId,
            'nama' => 'Rama Wijaya',
            'slug' => 'rama-wijaya',
            'alamat' => 'Bandung',
            'jumlah_undangan' => 2,
            'status' => 'Belum Konfirmasi',
            'jumlah_hadir_aktual' => 0,
        ]);

        $response = $this->postJson('/rsvp', [
            'nama_pengirim' => 'rama wijaya',
            'pesan_ucapan' => 'Selamat menempuh hidup baru, semoga samawa!',
            'status_kehadiran' => 'Hadir',
        ]);

        $response->assertCreated()
            ->assertJson([
                'success' => true,
                'data' => [
                    'nama_pengirim' => 'rama wijaya',
                    'status_kehadiran' => 'Hadir',
                ],
            ]);

        $this->assertDatabaseHas('doa_ucapan', [
            'tamu_id' => $tamu->id,
            'nama_pengirim' => 'rama wijaya',
            'status_kehadiran' => 'Hadir',
            'is_tampil' => 1,
        ]);

        $savedWish = DoaUcapan::query()->where('nama_pengirim', 'rama wijaya')->firstOrFail();
        $this->assertNotEmpty($savedWish->ip_address);

        $tamu->refresh();
        $this->assertSame('Hadir', $tamu->status);
        $this->assertSame(2, $tamu->jumlah_hadir_aktual);
    }

    public function test_rsvp_rejects_invalid_payload(): void
    {
        $response = $this->postJson('/rsvp', [
            'nama_pengirim' => '',
            'pesan_ucapan' => '',
            'status_kehadiran' => 'InvalidStatus',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['nama_pengirim', 'pesan_ucapan', 'status_kehadiran']);
    }
}
