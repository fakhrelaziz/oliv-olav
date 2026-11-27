<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\WeddingInitialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WeddingInitialSeeder::class);
    }

    public function test_unauthenticated_guest_is_redirected_from_admin_routes_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/konten')->assertRedirect('/login');
        $this->get('/admin/tamu')->assertRedirect('/login');
        $this->get('/admin/rsvp')->assertRedirect('/login');
    }

    public function test_login_page_does_not_expose_hardcoded_password(): void
    {
        $response = $this->get('/login');

        $response->assertOk()
            ->assertSee('Selamat Datang Kembali')
            ->assertDontSee('value="wedding2026"', false)
            ->assertDontSee('Masuk dengan Google');
    }

    public function test_admin_can_login_with_valid_bcrypt_credentials_and_logout(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@relliv.com',
            'password' => 'wedding2026',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Kelengkapan Undangan');

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_admin_login_fails_with_wrong_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@relliv.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_password_is_bcrypt_hashed_in_database(): void
    {
        $admin = User::query()->where('email', 'admin@relliv.com')->firstOrFail();

        $this->assertNotSame('wedding2026', $admin->password);
        $this->assertStringStartsWith('$2y$', $admin->password);
    }
}
