<?php

namespace Tests\Feature;

use App\Models\Satker;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_management_index_renders_with_kpi_and_pagination(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Lombok Tengah',
            'kode' => '5202',
        ]);

        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'adminutama',
            'email' => 'adminutama@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        // Create 24 more users to test pagination (> 20 users)
        for ($i = 1; $i <= 24; $i++) {
            User::create([
                'name' => "Pegawai User {$i}",
                'username' => "user{$i}",
                'email' => "user{$i}@bps.go.id",
                'password' => bcrypt('12345678'),
                'role' => $i % 3 === 0 ? 'admin' : 'user',
                'satker_id' => $satker->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['per_page' => 10]));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna (User Management)');
        $response->assertSee('Total User');
        $response->assertSee('Admin Satker');
        $response->assertSee('User / Operator');
        $response->assertSee('Menampilkan');
        $response->assertSee('dari <span class="font-bold text-gray-800">25</span> pengguna', false);

        // Check page 2
        $responsePage2 = $this->actingAs($admin)->get(route('admin.users.index', ['per_page' => 10, 'page' => 2]));
        $responsePage2->assertStatus(200);
    }

    public function test_user_management_search_and_role_filter(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Lombok Tengah',
            'kode' => '5202',
        ]);

        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'adminutama',
            'email' => 'adminutama@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $userSpecial = User::create([
            'name' => 'Kurnia Ramadhan',
            'username' => 'kurniaram',
            'email' => 'kurnia@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'satker_id' => $satker->id,
        ]);

        // Search test
        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Kurnia']));
        $response->assertStatus(200);
        $response->assertSee('Kurnia Ramadhan');
        $response->assertDontSee('Pegawai Tidak Ada');

        // Role filter test
        $responseRole = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'user']));
        $responseRole->assertStatus(200);
        $responseRole->assertSee('Kurnia Ramadhan');
    }

    public function test_admin_can_create_new_user_with_default_password(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Lombok Barat',
            'kode' => '5201',
        ]);

        $admin = User::create([
            'name' => 'Admin Satker',
            'username' => 'admin5201',
            'email' => 'admin5201@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Rina Wijaya',
            'username' => 'rinawijaya',
            'email' => 'rina@bps.go.id',
            'role' => 'user',
            'password' => '', // blank to test default password
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'name' => 'Rina Wijaya',
            'username' => 'rinawijaya',
            'email' => 'rina@bps.go.id',
            'satker_id' => $satker->id,
        ]);
    }

    public function test_admin_can_edit_and_update_user(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Mataram',
            'kode' => '5271',
        ]);

        $admin = User::create([
            'name' => 'Admin Mataram',
            'username' => 'adminmataram',
            'email' => 'adminmataram@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $user = User::create([
            'name' => 'Staf Lama',
            'username' => 'staflama',
            'email' => 'staflama@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'satker_id' => $satker->id,
        ]);

        // Edit screen
        $responseEdit = $this->actingAs($admin)->get(route('admin.users.edit', $user->id));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Staf Lama');

        // Update
        $responseUpdate = $this->actingAs($admin)->put(route('admin.users.update', $user->id), [
            'name' => 'Staf Baru',
            'username' => 'stafbaru',
            'email' => 'stafbaru@bps.go.id',
            'role' => 'admin',
        ]);

        $responseUpdate->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Staf Baru',
            'username' => 'stafbaru',
            'role' => 'admin',
        ]);
    }

    public function test_user_cannot_delete_themselves(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);

        $admin = User::create([
            'name' => 'Admin Sendiri',
            'username' => 'adminsendiri',
            'email' => 'adminsendiri@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_user_management_displays_last_login_date_and_ip(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Lombok Tengah',
            'kode' => '5202',
        ]);

        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'adminutama',
            'email' => 'adminutama@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
            'last_login_at' => now(),
            'last_login_ip' => '127.0.0.1',
        ]);

        $userWithLogin = User::create([
            'name' => 'User Telah Login',
            'username' => 'usertelahlogin',
            'email' => 'login@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'satker_id' => $satker->id,
            'last_login_at' => now()->subDay(),
            'last_login_ip' => '192.168.1.105',
        ]);

        $userNeverLoggedIn = User::create([
            'name' => 'User Belum Pernah Login',
            'username' => 'userbelumlogin',
            'email' => 'belum@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'satker_id' => $satker->id,
            'last_login_at' => null,
            'last_login_ip' => null,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Login Terakhir');
        $response->assertSee('192.168.1.105');
        $response->assertSee('Belum pernah login');

        // Test sorting by last_login_at
        $sortDescResponse = $this->actingAs($admin)->get(route('admin.users.index', ['sort' => 'last_login_at', 'direction' => 'desc']));
        $sortDescResponse->assertStatus(200);
        $sortDescResponse->assertSeeInOrder(['Admin Utama', 'User Telah Login']);

        // Test edit page displays last login
        $editResponse = $this->actingAs($admin)->get(route('admin.users.edit', $userWithLogin->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Login Terakhir:');
        $editResponse->assertSee('192.168.1.105');
    }

    public function test_dashboard_displays_last_login_widget_for_admin_only(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Lombok Tengah',
            'kode' => '5202',
        ]);

        $tahun = Tahun::create([
            'tahun' => '2026',
            'aktif' => true,
        ]);

        $admin = User::create([
            'name' => 'Admin Dashboard',
            'username' => 'admindash',
            'email' => 'admindash@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
            'last_login_at' => now(),
            'last_login_ip' => '172.16.0.10',
        ]);

        $normalUser = User::create([
            'name' => 'User Biasa',
            'username' => 'userbiasa',
            'email' => 'userbiasa@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'user',
            'satker_id' => $satker->id,
            'last_login_at' => now()->subHours(3),
            'last_login_ip' => '10.0.0.5',
        ]);

        // When admin visits dashboard:
        $adminDashboardResponse = $this->actingAs($admin)
            ->withSession(['satker_id' => $satker->id, 'tahun_id' => $tahun->id])
            ->get(route('dashboard'));

        $adminDashboardResponse->assertStatus(200);
        $adminDashboardResponse->assertSee('Aktivitas Login Terakhir Pengguna (Last Login)');
        $adminDashboardResponse->assertSee('Khusus Admin');
        $adminDashboardResponse->assertSee('172.16.0.10');
        $adminDashboardResponse->assertSee('10.0.0.5');

        // When regular user visits dashboard:
        $userDashboardResponse = $this->actingAs($normalUser)
            ->withSession(['satker_id' => $satker->id, 'tahun_id' => $tahun->id])
            ->get(route('dashboard'));

        $userDashboardResponse->assertStatus(200);
        $userDashboardResponse->assertDontSee('Aktivitas Login Terakhir Pengguna (Last Login)');
        $userDashboardResponse->assertDontSee('Khusus Admin');
    }
}
