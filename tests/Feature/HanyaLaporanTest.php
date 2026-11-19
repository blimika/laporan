<?php

namespace Tests\Feature;

use App\Models\Anggaran;
use App\Models\HanyaLaporan;
use App\Models\LaporanPerjalanan;
use App\Models\Pegawai;
use App\Models\Satker;
use App\Models\SuratTugas;
use App\Models\User;
use App\Services\AiReportGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\ViewErrorBag;
use ReflectionClass;
use Tests\TestCase;

class HanyaLaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_prompt_supports_various_paragraph_lengths(): void
    {
        $service = new AiReportGeneratorService;
        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('buildPrompt');
        $method->setAccessible(true);

        $baseData = [
            'tujuan_perjalanan' => 'Ubinan Padi',
            'lokasi_perjalanan' => 'Batunyala',
            'pegawai_ditemui' => 'Petani',
            'kategori' => 'Pendataan Lapangan',
            'kendala_ditemui' => 'Hujan lebat',
        ];

        // 1. minimal_2
        $p1 = $method->invoke($service, array_merge($baseData, ['panjang_narasi' => 'minimal_2']));
        $this->assertStringContainsString('MINIMAL DUA (2) PARAGRAF', $p1);

        // 2. 3_paragraf
        $p2 = $method->invoke($service, array_merge($baseData, ['panjang_narasi' => '3_paragraf']));
        $this->assertStringContainsString('TEPAT TIGA (3) PARAGRAF', $p2);

        // 3. lebih
        $p3 = $method->invoke($service, array_merge($baseData, ['panjang_narasi' => 'lebih']));
        $this->assertStringContainsString('Minimal 3-4 Paragraf atau Lebih', $p3);

        // 4. is_8_jam
        $p4 = $method->invoke($service, array_merge($baseData, ['is_8_jam' => true]));
        $this->assertStringContainsString('TABEL MARKDOWN', $p4);
    }

    public function test_hanya_laporan_pdf_omits_nip_when_empty(): void
    {
        $laporan = new HanyaLaporan([
            'nama_pegawai' => 'Drs. Subroto (Custom Non-Database)',
            'nip_pegawai' => null,
            'nomor_st' => 'ST/TEST/001',
            'nomor_spd' => null,
            'tgl_laporan' => now(),
            'tgl_perjalanan' => now(),
            'lokasi_perjalanan' => 'Desa Batunyala',
            'tujuan_perjalanan' => 'Survei Ubinan Padi',
            'pegawai_ditemui' => 'Kelompok Tani',
            'kategori' => 'Pendataan Lapangan',
            'kendala_ditemui' => null,
            'hasil_perjalanan' => "Petugas melakukan kunjungan kerja di lokasi.\n\nHasil koordinasi berjalan dengan baik.",
        ]);
        $laporan->setRelation('dokumentasi', collect());

        $html = view('exports.pdf.hanya_laporan', ['laporan' => $laporan])->render();

        $this->assertStringContainsString('Drs. Subroto (Custom Non-Database)', $html);
        $this->assertStringNotContainsString('NIP.', $html);
    }

    public function test_hanya_laporan_pdf_includes_nip_when_provided(): void
    {
        $laporan = new HanyaLaporan([
            'nama_pegawai' => 'I Putu Dyatmika, SST',
            'nip_pegawai' => '198203192004121002',
            'nomor_st' => 'ST/TEST/002',
            'nomor_spd' => null,
            'tgl_laporan' => now(),
            'tgl_perjalanan' => now(),
            'lokasi_perjalanan' => 'Desa Batunyala',
            'tujuan_perjalanan' => 'Survei Ubinan Padi',
            'pegawai_ditemui' => 'Kelompok Tani',
            'kategori' => 'Pendataan Lapangan',
            'kendala_ditemui' => null,
            'hasil_perjalanan' => "Petugas melakukan kunjungan kerja di lokasi.\n\nHasil koordinasi berjalan dengan baik.",
        ]);
        $laporan->setRelation('dokumentasi', collect());

        $html = view('exports.pdf.hanya_laporan', ['laporan' => $laporan])->render();

        $this->assertStringContainsString('I Putu Dyatmika, SST', $html);
        $this->assertStringContainsString('NIP. 198203192004121002', $html);
    }

    public function test_hanya_laporan_pdf_signature_renders_in_keep_together_block(): void
    {
        $laporan = new HanyaLaporan([
            'nama_pegawai' => 'Pegawai Uji',
            'nip_pegawai' => '123456',
            'nomor_st' => 'ST/TEST/003',
            'tgl_laporan' => now(),
            'tgl_perjalanan' => now(),
            'lokasi_perjalanan' => 'Kantor',
            'tujuan_perjalanan' => 'Rapat',
            'pegawai_ditemui' => 'Pimpinan',
            'kategori' => 'Translok Koordinasi',
            'hasil_perjalanan' => "Paragraf pertama laporan dinas.\n\nParagraf kedua laporan dinas.",
        ]);
        $laporan->setRelation('dokumentasi', collect());

        $html = view('exports.pdf.hanya_laporan', ['laporan' => $laporan])->render();

        $this->assertStringContainsString('class="keep-together"', $html);
        $this->assertStringContainsString('class="signature-table"', $html);
    }

    public function test_hanya_laporan_edit_view_prefills_database_and_custom_pegawai(): void
    {
        view()->share('errors', new ViewErrorBag);
        $this->actingAs(new User(['name' => 'Admin', 'role' => 'user']));

        $pegawais = collect([
            (object) ['nama' => 'Pegawai DB', 'nip' => '19900101'],
        ]);
        $users = collect();

        // 1. Database employee
        $laporanDb = new HanyaLaporan([
            'nama_pegawai' => 'Pegawai DB',
            'nip_pegawai' => '19900101',
            'nomor_st' => 'ST/01',
            'tgl_laporan' => now(),
            'tgl_perjalanan' => now(),
            'lokasi_perjalanan' => 'Kantor',
            'tujuan_perjalanan' => 'Dinas',
            'pegawai_ditemui' => 'Pimpinan',
            'kategori' => 'Translok Koordinasi',
        ]);
        $laporanDb->id = 101;
        $laporanDb->exists = true;
        $laporanDb->setRelation('dokumentasi', collect());

        $htmlDb = view('hanya_laporan.edit', [
            'hanyaLaporan' => $laporanDb,
            'pegawais' => $pegawais,
            'users' => $users,
        ])->render();

        $this->assertStringContainsString('value="Pegawai DB"', $htmlDb);
        $this->assertStringNotContainsString('<x-text-input', $htmlDb);

        // 2. Custom employee
        $laporanCustom = new HanyaLaporan([
            'nama_pegawai' => 'Pegawai Luar Custom',
            'nip_pegawai' => null,
            'nomor_st' => 'ST/02',
            'tgl_laporan' => now(),
            'tgl_perjalanan' => now(),
            'lokasi_perjalanan' => 'Kantor',
            'tujuan_perjalanan' => 'Dinas',
            'pegawai_ditemui' => 'Pimpinan',
            'kategori' => 'Translok Koordinasi',
        ]);
        $laporanCustom->id = 102;
        $laporanCustom->exists = true;
        $laporanCustom->setRelation('dokumentasi', collect());

        $htmlCustom = view('hanya_laporan.edit', [
            'hanyaLaporan' => $laporanCustom,
            'pegawais' => $pegawais,
            'users' => $users,
        ])->render();

        $this->assertStringContainsString('value="Pegawai Luar Custom"', $htmlCustom);
        $this->assertStringContainsString('id="check_custom_pegawai"', $htmlCustom);
        $this->assertStringContainsString('checked', $htmlCustom);
        $this->assertStringNotContainsString('<x-text-input', $htmlCustom);
    }

    public function test_dashboard_widget_includes_hanya_laporan_count(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester',
            'username' => 'tester',
            'email' => 'tester@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        HanyaLaporan::create([
            'nama_pegawai' => 'Pegawai Hanya Laporan 1',
            'nomor_st' => 'ST/001',
            'tgl_laporan' => now(),
            'tgl_perjalanan' => now(),
            'lokasi_perjalanan' => 'Mataram',
            'tujuan_perjalanan' => 'Tugas',
            'pegawai_ditemui' => 'Kades',
            'kategori' => 'Pendataan',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        HanyaLaporan::create([
            'nama_pegawai' => 'Pegawai Hanya Laporan 2',
            'nomor_st' => 'ST/002',
            'tgl_laporan' => now(),
            'tgl_perjalanan' => now(),
            'lokasi_perjalanan' => 'Mataram',
            'tujuan_perjalanan' => 'Tugas',
            'pegawai_ditemui' => 'Kades',
            'kategori' => 'Pendataan',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Laporan Selesai (Dibuat)');
        $response->assertSee('Hanya Laporan');
        $response->assertViewHas('stats', function ($stats) {
            return $stats['hanya_laporan'] === 2 && $stats['laporan'] >= 2;
        });
    }

    public function test_hanya_laporan_index_contains_copy_button(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester',
            'username' => 'tester2',
            'email' => 'tester2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $laporan = HanyaLaporan::create([
            'nama_pegawai' => 'Budi Santoso',
            'nip_pegawai' => '198801012010121001',
            'nomor_st' => 'ST/TEST/099',
            'tgl_laporan' => now(),
            'tgl_perjalanan' => now(),
            'lokasi_perjalanan' => 'Kecamatan Praya',
            'tujuan_perjalanan' => 'Koordinasi Survei',
            'pegawai_ditemui' => 'Camat',
            'kategori' => 'Translok Koordinasi',
            'kendala_ditemui' => 'Sinyal terbatas',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('hanya-laporan.index'));

        $response->assertStatus(200);
        $response->assertSee(route('hanya-laporan.create', ['copy_from' => $laporan->id]));
    }

    public function test_hanya_laporan_create_prefills_inputs_from_copy_from_parameter(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester',
            'username' => 'tester3',
            'email' => 'tester3@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $laporan = HanyaLaporan::create([
            'nama_pegawai' => 'Budi Santoso',
            'nip_pegawai' => '198801012010121001',
            'nomor_st' => 'ST/TEST/099',
            'nomor_spd' => 'SPD/TEST/099',
            'tgl_laporan' => '2026-10-15',
            'tgl_perjalanan' => '2026-10-14',
            'lokasi_perjalanan' => 'Kecamatan Praya',
            'tujuan_perjalanan' => 'Koordinasi Survei',
            'pegawai_ditemui' => 'Camat Praya',
            'kategori' => 'Translok Koordinasi',
            'kendala_ditemui' => 'Cuaca hujan dan sinyal terbatas',
            'hasil_perjalanan' => 'Hasil teks lama yang tidak perlu dicopy',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('hanya-laporan.create', ['copy_from' => $laporan->id]));

        $response->assertStatus(200);
        $response->assertSee('value="Budi Santoso"', false);
        $response->assertSee('value="198801012010121001"', false);
        $response->assertSee('value="ST/TEST/099"', false);
        $response->assertSee('value="SPD/TEST/099"', false);
        $response->assertSee('value="2026-10-15"', false);
        $response->assertSee('value="2026-10-14"', false);
        $response->assertSee('value="Kecamatan Praya"', false);
        $response->assertSee('value="Koordinasi Survei"', false);
        $response->assertSee('value="Camat Praya"', false);
        $response->assertSee('Cuaca hujan dan sinyal terbatas');
        $response->assertDontSee('Hasil teks lama yang tidak perlu dicopy');
    }

    public function test_hanya_laporan_create_prefills_inputs_from_copy_from_laporan_parameter(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester',
            'username' => 'tester4',
            'email' => 'tester4@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);
        $pegawai = Pegawai::create([
            'nama' => 'Ahmad Pegawai',
            'nip' => '198701012011011001',
            'jabatan' => 'Pranata Komputer',
            'satker_id' => $satker->id,
        ]);
        $anggaran = Anggaran::create([
            'mak' => '054.01.GG.1234.524111',
            'satker_id' => $satker->id,
        ]);
        $st = SuratTugas::create([
            'nomor_surat' => 'ST/LP/2026/01',
            'tgl_surat' => now(),
            'tgl_berangkat' => now(),
            'tgl_kembali' => now(),
            'tujuan' => 'Desa Penujak',
            'tugas' => 'Pendataan Lapangan',
            'pembebanan_anggaran_id' => $anggaran->id,
            'kepala_pegawai_id' => $pegawai->id,
            'pegawai_id' => $pegawai->id,
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);
        $lap = LaporanPerjalanan::create([
            'surat_tugas_id' => $st->id,
            'tgl_laporan' => '2026-10-20',
            'tgl_perjalanan' => '2026-10-19',
            'lokasi_perjalanan' => 'Desa Penujak',
            'tujuan_perjalanan' => 'Survei Ubinan',
            'pegawai_ditemui' => 'Kepala Dusun',
            'kategori' => 'Pendataan Lapangan',
            'kendala_ditemui' => 'Jalan becek',
            'hasil_perjalanan' => 'Hasil narasi lama yang tidak dicopy',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('hanya-laporan.create', ['copy_from_laporan' => $lap->id]));

        $response->assertStatus(200);
        $response->assertSee('value="Ahmad Pegawai"', false);
        $response->assertSee('value="198701012011011001"', false);
        $response->assertSee('value="ST/LP/2026/01"', false);
        $response->assertSee('value="2026-10-20"', false);
        $response->assertSee('value="2026-10-19"', false);
        $response->assertSee('value="Desa Penujak"', false);
        $response->assertSee('value="Survei Ubinan"', false);
        $response->assertSee('value="Kepala Dusun"', false);
        $response->assertSee('Jalan becek');
        $response->assertDontSee('Hasil narasi lama yang tidak dicopy');
    }

    public function test_hanya_laporan_store_accepts_webp_photo(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Test',
            'kode' => '5200',
        ]);
        $user = User::create([
            'name' => 'Tester Webp',
            'username' => 'testerwebp',
            'email' => 'testerwebp@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $tmp = tempnam(sys_get_temp_dir(), 'webp').'.webp';
        $im = imagecreatetruecolor(50, 50);
        imagewebp($im, $tmp);
        imagedestroy($im);
        $file = new UploadedFile($tmp, 'kamera_hp.webp', 'image/webp', null, true);

        $response = $this->actingAs($user)->post(route('hanya-laporan.store'), [
            'nama_pegawai' => 'Dwi Handoko',
            'nip_pegawai' => '199101012015011001',
            'nomor_st' => 'ST/WEBP/001',
            'tgl_laporan' => '2026-11-19',
            'tgl_perjalanan' => '2026-11-19',
            'lokasi_perjalanan' => 'Dusun Montong',
            'tujuan_perjalanan' => 'Supervisi WebP',
            'pegawai_ditemui' => 'Kaling Montong',
            'kategori' => 'Pengawasan / Supervisi Lapangan',
            'hasil_perjalanan' => 'Narasi laporan pengawasan telah selesai.',
            'fotos' => [$file],
        ]);

        $response->assertRedirect(route('hanya-laporan.index'));
        $this->assertDatabaseHas('hanya_laporans', [
            'nomor_st' => 'ST/WEBP/001',
            'nama_pegawai' => 'Dwi Handoko',
        ]);

        $laporan = HanyaLaporan::where('nomor_st', 'ST/WEBP/001')->first();
        $this->assertNotNull($laporan);
        $this->assertCount(1, $laporan->dokumentasi);
        $this->assertStringEndsWith('.webp', $laporan->dokumentasi->first()->file_path);

        // Cleanup saved file
        if (file_exists(public_path($laporan->dokumentasi->first()->file_path))) {
            unlink(public_path($laporan->dokumentasi->first()->file_path));
        }
        @unlink($tmp);
    }

    public function test_hanya_laporan_edit_renders_with_rich_sections_and_existing_data(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Lombok Tengah',
            'kode' => '5202',
        ]);
        $user = User::create([
            'name' => 'Operator AI',
            'username' => 'operatorai',
            'email' => 'operatorai@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);
        $pegawai = Pegawai::create([
            'nama' => 'Lalu Irfan, S.Stat',
            'nip' => '199201012015011002',
            'satker_id' => $satker->id,
            'tags' => ['aktif'],
        ]);

        $laporan = HanyaLaporan::create([
            'nama_pegawai' => $pegawai->nama,
            'nip_pegawai' => $pegawai->nip,
            'nomor_st' => '055/ST/5202/2026',
            'nomor_spd' => '055/SPD/5202/2026',
            'tgl_laporan' => '2026-11-18',
            'tgl_perjalanan' => '2026-11-18',
            'lokasi_perjalanan' => 'Kecamatan Praya Barat',
            'tujuan_perjalanan' => 'Supervisi Survei Angkatan Kerja',
            'pegawai_ditemui' => 'Koordinator Lapangan',
            'kategori' => 'Pengawasan / Supervisi Lapangan',
            'kendala_ditemui' => 'Sinyal seluler terbatas di perbukitan',
            'hasil_perjalanan' => 'Pelaksanaan survei berjalan sesuai SOP yang ditetapkan.',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('hanya-laporan.edit', $laporan));

        $response->assertStatus(200);
        $response->assertSee('Edit Hanya Laporan (Generate AI)');
        $response->assertSee('Identitas Dokumen Laporan');
        $response->assertSee('055/ST/5202/2026');
        $response->assertSee('Lalu Irfan, S.Stat');
        $response->assertSee('Kecamatan Praya Barat');
        $response->assertSee('Supervisi Survei Angkatan Kerja');
        $response->assertSee('Pelaksanaan survei berjalan sesuai SOP yang ditetapkan.');
    }

    public function test_hanya_laporan_update_successfully_updates_and_handles_photo_deletion(): void
    {
        $satker = Satker::create([
            'nama' => 'BPS Lombok Tengah',
            'kode' => '5202',
        ]);
        $user = User::create([
            'name' => 'Operator AI 2',
            'username' => 'operatorai2',
            'email' => 'operatorai2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $satker->id,
        ]);

        $laporan = HanyaLaporan::create([
            'nama_pegawai' => 'Baiq Rohani, A.Md.',
            'nip_pegawai' => '199501012019012001',
            'nomor_st' => '077/ST/5202/2026',
            'tgl_laporan' => '2026-11-10',
            'tgl_perjalanan' => '2026-11-10',
            'lokasi_perjalanan' => 'Desa Rembitan',
            'tujuan_perjalanan' => 'Pendataan Desa Wisata',
            'pegawai_ditemui' => 'Kepala Desa',
            'kategori' => 'Pendataan Lapangan',
            'hasil_perjalanan' => 'Hasil teks awal sebelum diedit.',
            'satker_id' => $satker->id,
            'user_id' => $user->id,
        ]);

        // Create a dummy dokumentasi
        $dok = $laporan->dokumentasi()->create([
            'file_path' => 'uploads/dokumentasi/dummy_test_photo.jpg',
            'keterangan_foto' => 'Foto Lama',
        ]);

        $response = $this->actingAs($user)->put(route('hanya-laporan.update', $laporan), [
            'nama_pegawai' => 'Baiq Rohani, A.Md.',
            'nip_pegawai' => '199501012019012001',
            'nomor_st' => '077/ST/5202/2026/REV',
            'tgl_laporan' => '2026-11-11',
            'tgl_perjalanan' => '2026-11-10',
            'lokasi_perjalanan' => 'Desa Sade & Rembitan',
            'tujuan_perjalanan' => 'Pendataan Desa Wisata Terpadu',
            'pegawai_ditemui' => 'Kepala Desa dan Tokoh Adat',
            'kategori' => 'Pendataan Lapangan',
            'hasil_perjalanan' => 'Teks narasi hasil editan baru yang lebih komprehensif.',
            'delete_fotos' => [$dok->id],
        ]);

        $response->assertRedirect(route('hanya-laporan.index'));
        $response->assertSessionHas('success', 'Laporan berhasil diperbarui.');

        $this->assertDatabaseHas('hanya_laporans', [
            'id' => $laporan->id,
            'nomor_st' => '077/ST/5202/2026/REV',
            'lokasi_perjalanan' => 'Desa Sade & Rembitan',
            'hasil_perjalanan' => 'Teks narasi hasil editan baru yang lebih komprehensif.',
        ]);

        $this->assertDatabaseMissing('hanya_laporan_dokumentasis', [
            'id' => $dok->id,
        ]);
    }
}
