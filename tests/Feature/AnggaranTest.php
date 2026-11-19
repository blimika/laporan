<?php

namespace Tests\Feature;

use App\Models\Anggaran;
use App\Models\Satker;
use App\Models\Tahun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnggaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggaran_create_screen_renders_with_readonly_active_year_and_rich_sections(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS NTB',
            'kode' => '5200',
        ]);
        $tahun = Tahun::create([
            'tahun' => '2026',
            'aktif' => true,
        ]);
        $admin = User::create([
            'name' => 'Admin Anggaran',
            'username' => 'adminanggaran',
            'email' => 'adminanggaran@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)
            ->withSession([
                'satker_id' => $satker->id,
                'tahun_id' => $tahun->id,
            ])
            ->get(route('anggarans.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Data Anggaran (MAK)');
        $response->assertSee('2026');
        $response->assertSee('Read Only');
        $response->assertSee('readonly', false);
        $response->assertSee('Mata Anggaran Kegiatan (MAK)');
        $response->assertSee('Nomenklatur Struktur DIPA / POK');
        $response->assertSee('524111');
        $response->assertSee('Perjadin Biasa');
        $response->assertSee('Pratinjau Langsung Nomenklatur Anggaran');
        $response->assertSee('Simpan Data Anggaran');
    }

    public function test_anggaran_store_successfully_creates_record_bound_to_active_year(): void
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
            'name' => 'Admin Loteng',
            'username' => 'adminloteng',
            'email' => 'adminloteng@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($admin)
            ->withSession([
                'satker_id' => $satker->id,
                'tahun_id' => $tahun->id,
            ])
            ->post(route('anggarans.store'), [
                'mak' => '054.01.WA.2895.QMA.001.051.A.524111',
                'tahun' => '2026',
                'program_kode' => '054.01.WA',
                'program_uraian' => 'Program Penyediaan dan Pelayanan Informasi Statistik',
                'kegiatan_kode' => '2895',
                'kegiatan_uraian' => 'Penyelenggaraan Sensus dan Survei',
                'akun_kode' => '524111',
                'akun_uraian' => 'Belanja Perjalanan Dinas Biasa',
            ]);

        $response->assertRedirect(route('anggarans.index'));

        $this->assertDatabaseHas('anggarans', [
            'mak' => '054.01.WA.2895.QMA.001.051.A.524111',
            'tahun' => '2026',
            'satker_id' => $satker->id,
            'tahun_id' => $tahun->id,
            'akun_kode' => '524111',
        ]);
    }

    public function test_anggaran_edit_screen_renders_with_readonly_year_and_rich_sections(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS NTB',
            'kode' => '5200',
        ]);
        $tahun = Tahun::create([
            'tahun' => '2026',
            'aktif' => true,
        ]);
        $admin = User::create([
            'name' => 'Admin Anggaran Edit',
            'username' => 'adminanggaranedit',
            'email' => 'adminanggaranedit@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);
        $anggaran = Anggaran::create([
            'mak' => '054.01.GG.2895.524111',
            'tahun' => '2026',
            'program_kode' => '054.01.GG',
            'program_uraian' => 'Program Teknis Statistik',
            'kegiatan_kode' => '2895',
            'kegiatan_uraian' => 'Sensus & Survei',
            'akun_kode' => '524111',
            'akun_uraian' => 'Belanja Perjalanan Dinas Biasa',
            'satker_id' => $satker->id,
            'tahun_id' => $tahun->id,
        ]);

        $response = $this->actingAs($admin)
            ->withSession([
                'satker_id' => $satker->id,
                'tahun_id' => $tahun->id,
            ])
            ->get(route('anggarans.edit', $anggaran));

        $response->assertStatus(200);
        $response->assertSee('Edit Data Anggaran (MAK)');
        $response->assertSee('054.01.GG.2895.524111');
        $response->assertSee('2026');
        $response->assertSee('Read Only');
        $response->assertSee('readonly', false);
        $response->assertSee('Nomenklatur Struktur DIPA / POK');
        $response->assertSee('Pratinjau Langsung Nomenklatur Anggaran');
        $response->assertSee('Perbarui Data Anggaran');
    }

    public function test_anggaran_update_successfully_updates_record(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS NTB',
            'kode' => '5200',
        ]);
        $tahun = Tahun::create([
            'tahun' => '2026',
            'aktif' => true,
        ]);
        $admin = User::create([
            'name' => 'Admin Update Anggaran',
            'username' => 'adminupdate',
            'email' => 'adminupdate@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);
        $anggaran = Anggaran::create([
            'mak' => '054.01.WA.1000.524111',
            'tahun' => '2026',
            'program_kode' => '054.01.WA',
            'program_uraian' => 'Program Lama',
            'akun_kode' => '524111',
            'akun_uraian' => 'Belanja Perjalanan Dinas Biasa',
            'satker_id' => $satker->id,
            'tahun_id' => $tahun->id,
        ]);

        $response = $this->actingAs($admin)
            ->withSession([
                'satker_id' => $satker->id,
                'tahun_id' => $tahun->id,
            ])
            ->put(route('anggarans.update', $anggaran), [
                'mak' => '054.01.WA.2000.524113',
                'tahun' => '2026',
                'program_kode' => '054.01.WA',
                'program_uraian' => 'Program Diperbarui',
                'kegiatan_kode' => '2000',
                'kegiatan_uraian' => 'Kegiatan Baru',
                'akun_kode' => '524113',
                'akun_uraian' => 'Belanja Perjalanan Dinas Dalam Kota',
            ]);

        $response->assertRedirect(route('anggarans.index'));

        $anggaran->refresh();
        $this->assertEquals('054.01.WA.2000.524113', $anggaran->mak);
        $this->assertEquals('Program Diperbarui', $anggaran->program_uraian);
        $this->assertEquals('524113', $anggaran->akun_kode);
        $this->assertEquals('Belanja Perjalanan Dinas Dalam Kota', $anggaran->akun_uraian);
    }

    public function test_anggaran_create_denied_for_regular_user_role(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS NTB',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Regular User',
            'username' => 'reguser',
            'email' => 'reguser@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'satker_id' => $satker->id,
        ]);

        $response = $this->actingAs($user)->get(route('anggarans.create'));
        $response->assertStatus(403);
    }
}
