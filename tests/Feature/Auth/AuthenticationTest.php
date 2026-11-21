<?php

namespace Tests\Feature\Auth;

use App\Models\Tahun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $tahun = Tahun::create(['tahun' => '2026', 'aktif' => true]);
        $user = User::factory()->create(['username' => 'testuser']);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.40'])->post('/login', [
            'username' => 'testuser',
            'password' => 'password',
            'tahun_id' => $tahun->id,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user->refresh();
        $this->assertNotNull($user->last_login_at);
        $this->assertEquals('10.20.30.40', $user->last_login_ip);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $tahun = Tahun::create(['tahun' => '2026', 'aktif' => true]);
        $user = User::factory()->create(['username' => 'testuser']);

        $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrong-password',
            'tahun_id' => $tahun->id,
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
