<?php

namespace Tests\Feature;

use App\Models\Anggaran;
use App\Models\LaporanPerjalanan;
use App\Models\Pegawai;
use App\Models\Satker;
use App\Models\SuratTugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LaporanPerjalananTest extends TestCase
{
    use RefreshDatabase;

    public function test_laporan_perjalanan_create_screen_can_be_rendered(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester Laporan',
            'username' => 'testerlap',
            'email' => 'testerlap@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);
        $pegawai = Pegawai::create([
            'nama' => 'Budi Santoso',
            'nip' => '198501012010121001',
            'jabatan' => 'Statistisi Ahli',
            'satker_id' => $satker->id,
        ]);
        $anggaran = Anggaran::create([
            'mak' => '054.01.GG.9999.524111',
            'satker_id' => $satker->id,
        ]);
        $st = SuratTugas::create([
            'nomor_surat' => 'ST/TEST/LAP/001',
            'tgl_surat' => now(),
            'tgl_berangkat' => '2026-11-20',
            'tgl_kembali' => '2026-11-21',
            'tujuan' => 'Desa Penujak',
            'tugas' => 'Pendataan Lapangan',
            'pembebanan_anggaran_id' => $anggaran->id,
            'kepala_pegawai_id' => $pegawai->id,
            'pegawai_id' => $pegawai->id,
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('laporan.create', ['st_id' => $st->id]));

        $response->assertStatus(200);
        $response->assertSee('Input Laporan Perjalanan (Generate AI)');
        $response->assertSee('ST/TEST/LAP/001');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Support Foto WebP');
        $response->assertSee('accept="image/jpeg,image/png,image/jpg,image/webp,image/*"', false);
    }

    public function test_laporan_perjalanan_store_accepts_webp_photo(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester Laporan 2',
            'username' => 'testerlap2',
            'email' => 'testerlap2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);
        $pegawai = Pegawai::create([
            'nama' => 'Ahmad Pelaksana',
            'nip' => '198701012011011001',
            'jabatan' => 'Pranata Komputer',
            'satker_id' => $satker->id,
        ]);
        $anggaran = Anggaran::create([
            'mak' => '054.01.GG.8888.524111',
            'satker_id' => $satker->id,
        ]);
        $st = SuratTugas::create([
            'nomor_surat' => 'ST/TEST/LAP/002',
            'tgl_surat' => now(),
            'tgl_berangkat' => '2026-11-20',
            'tgl_kembali' => '2026-11-21',
            'tujuan' => 'Kecamatan Praya',
            'tugas' => 'Supervisi Survei',
            'pembebanan_anggaran_id' => $anggaran->id,
            'kepala_pegawai_id' => $pegawai->id,
            'pegawai_id' => $pegawai->id,
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $tmp = tempnam(sys_get_temp_dir(), 'webp').'.webp';
        $im = imagecreatetruecolor(50, 50);
        imagewebp($im, $tmp);
        imagedestroy($im);
        $file = new UploadedFile($tmp, 'camera_phone.webp', 'image/webp', null, true);

        $response = $this->actingAs($user)->post(route('laporan.store'), [
            'surat_tugas_id' => $st->id,
            'tgl_laporan' => '2026-11-21',
            'tgl_perjalanan' => '2026-11-20',
            'lokasi_perjalanan' => 'Kecamatan Praya',
            'tujuan_perjalanan' => 'Supervisi Survei',
            'pegawai_ditemui' => 'Camat Praya',
            'kategori' => 'Pengawasan / Supervisi Lapangan',
            'kendala_ditemui' => 'Cuaca hujan di sore hari',
            'hasil_perjalanan' => 'Kegiatan supervisi telah dilaksanakan dengan baik.',
            'fotos' => [$file],
        ]);

        $response->assertRedirect(route('laporan.index'));

        $laporan = LaporanPerjalanan::where('surat_tugas_id', $st->id)->first();
        $this->assertNotNull($laporan);
        $this->assertEquals('Kecamatan Praya', $laporan->lokasi_perjalanan);
        $this->assertCount(1, $laporan->dokumentasi);
        $this->assertStringEndsWith('.webp', $laporan->dokumentasi->first()->file_path);

        // Cleanup saved file
        if (file_exists(public_path($laporan->dokumentasi->first()->file_path))) {
            unlink(public_path($laporan->dokumentasi->first()->file_path));
        }
        @unlink($tmp);
    }

    public function test_laporan_perjalanan_edit_screen_renders_with_rich_sections_and_existing_data(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester Laporan Edit',
            'username' => 'testeredit',
            'email' => 'testeredit@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);
        $pegawai = Pegawai::create([
            'nama' => 'Rina Wijaya',
            'nip' => '199002022014022002',
            'jabatan' => 'Statistisi Pertama',
            'satker_id' => $satker->id,
        ]);
        $anggaran = Anggaran::create([
            'mak' => '054.01.GG.7777.524111',
            'satker_id' => $satker->id,
        ]);
        $st = SuratTugas::create([
            'nomor_surat' => 'ST/TEST/LAP/EDIT-003',
            'tgl_surat' => '2026-11-18',
            'tgl_berangkat' => '2026-11-19',
            'tgl_kembali' => '2026-11-20',
            'tujuan' => 'Kantor Camat Jonggat',
            'tugas' => 'Verifikasi Data Kemiskinan',
            'pembebanan_anggaran_id' => $anggaran->id,
            'kepala_pegawai_id' => $pegawai->id,
            'pegawai_id' => $pegawai->id,
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $laporan = LaporanPerjalanan::create([
            'surat_tugas_id' => $st->id,
            'tgl_laporan' => '2026-11-20',
            'tgl_perjalanan' => '2026-11-19',
            'lokasi_perjalanan' => 'Kantor Camat Jonggat',
            'tujuan_perjalanan' => 'Verifikasi Data Kemiskinan',
            'pegawai_ditemui' => 'Sekcam Jonggat',
            'kategori' => 'Verifikasi Data',
            'kendala_ditemui' => 'Akses jaringan lambat',
            'hasil_perjalanan' => 'Telah dilakukan verifikasi data secara komprehensif.',
            'is_stamped' => true,
            'stamp_koordinat' => '-8.600000, 116.200000',
            'stamp_datetime' => '2026-11-19 10:00:00',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('laporan.edit', $laporan));

        $response->assertStatus(200);
        $response->assertSee('Edit Laporan Perjalanan (Generate AI)');
        $response->assertSee('ST/TEST/LAP/EDIT-003');
        $response->assertSee('Rina Wijaya');
        $response->assertSee('Kantor Camat Jonggat');
        $response->assertSee('Verifikasi Data Kemiskinan');
        $response->assertSee('Sekcam Jonggat');
        $response->assertSee('Support Foto WebP');
        $response->assertSee('AI Report Studio');
        $response->assertSee('Preview PDF');
        $response->assertSee('Unduh Word');
        $response->assertSee('Simpan Pembaruan Laporan');
    }

    public function test_laporan_perjalanan_update_successfully_updates_and_handles_photo_deletion(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester Laporan Update',
            'username' => 'testerupdate',
            'email' => 'testerupdate@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);
        $pegawai = Pegawai::create([
            'nama' => 'Dedi Hermanto',
            'nip' => '198905052012011003',
            'jabatan' => 'Statistisi Penyelia',
            'satker_id' => $satker->id,
        ]);
        $anggaran = Anggaran::create([
            'mak' => '054.01.GG.6666.524111',
            'satker_id' => $satker->id,
        ]);
        $st = SuratTugas::create([
            'nomor_surat' => 'ST/TEST/LAP/UPDATE-004',
            'tgl_surat' => '2026-11-15',
            'tgl_berangkat' => '2026-11-16',
            'tgl_kembali' => '2026-11-17',
            'tujuan' => 'Desa Batunyala',
            'tugas' => 'Supervisi Sensus',
            'pembebanan_anggaran_id' => $anggaran->id,
            'kepala_pegawai_id' => $pegawai->id,
            'pegawai_id' => $pegawai->id,
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $laporan = LaporanPerjalanan::create([
            'surat_tugas_id' => $st->id,
            'tgl_laporan' => '2026-11-17',
            'tgl_perjalanan' => '2026-11-16',
            'lokasi_perjalanan' => 'Desa Batunyala Lama',
            'tujuan_perjalanan' => 'Supervisi Sensus Awal',
            'pegawai_ditemui' => 'Kades Lama',
            'kategori' => 'Pengawasan / Supervisi Lapangan',
            'kendala_ditemui' => 'Tidak ada',
            'hasil_perjalanan' => 'Narasi awal sebelum update.',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        // Buat dummy foto existing
        $existingFileRel = 'uploads/dokumentasi/dummy_test_delete_'.uniqid().'.jpg';
        $existingFileFull = public_path($existingFileRel);
        if (! is_dir(dirname($existingFileFull))) {
            mkdir(dirname($existingFileFull), 0755, true);
        }
        file_put_contents($existingFileFull, 'dummy image content');

        $dok = $laporan->dokumentasi()->create([
            'file_path' => $existingFileRel,
            'keterangan_foto' => 'Dokumentasi Lama',
        ]);

        // Buat upload webp baru
        $tmp = tempnam(sys_get_temp_dir(), 'webp').'.webp';
        $im = imagecreatetruecolor(50, 50);
        imagewebp($im, $tmp);
        imagedestroy($im);
        $newWebpFile = new UploadedFile($tmp, 'foto_baru.webp', 'image/webp', null, true);

        $response = $this->actingAs($user)->put(route('laporan.update', $laporan), [
            'surat_tugas_id' => $st->id,
            'tgl_laporan' => '2026-11-18',
            'tgl_perjalanan' => '2026-11-16',
            'lokasi_perjalanan' => 'Desa Batunyala Baru',
            'tujuan_perjalanan' => 'Supervisi Sensus Final',
            'pegawai_ditemui' => 'Kades Baru & Sekdes',
            'kategori' => 'Pengawasan / Supervisi Lapangan',
            'kendala_ditemui' => 'Hujan rintik-rintik',
            'hasil_perjalanan' => 'Narasi setelah diperbarui oleh AI Studio.',
            'delete_fotos' => [$dok->id],
            'fotos' => [$newWebpFile],
        ]);

        $response->assertRedirect(route('laporan.index'));

        $laporan->refresh();
        $this->assertEquals('Desa Batunyala Baru', $laporan->lokasi_perjalanan);
        $this->assertEquals('Supervisi Sensus Final', $laporan->tujuan_perjalanan);
        $this->assertEquals('Kades Baru & Sekdes', $laporan->pegawai_ditemui);
        $this->assertEquals('Narasi setelah diperbarui oleh AI Studio.', $laporan->hasil_perjalanan);

        // Pastikan foto lama terhapus dari database dan filesystem
        $this->assertDatabaseMissing('laporan_dokumentasis', ['id' => $dok->id]);
        $this->assertFileDoesNotExist($existingFileFull);

        // Pastikan foto baru tersimpan (format webp)
        $this->assertCount(1, $laporan->dokumentasi);
        $newDok = $laporan->dokumentasi->first();
        $this->assertStringEndsWith('.webp', $newDok->file_path);

        // Cleanup foto baru
        if (file_exists(public_path($newDok->file_path))) {
            unlink(public_path($newDok->file_path));
        }
        @unlink($tmp);
    }
}
