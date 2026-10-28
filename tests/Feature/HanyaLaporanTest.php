<?php

namespace Tests\Feature;

use App\Models\HanyaLaporan;
use App\Models\Satker;
use App\Models\User;
use App\Services\AiReportGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response->assertSee('Laporan Dibuat');
        $response->assertSee('Hanya Laporan');
        $response->assertViewHas('stats', function ($stats) {
            return $stats['hanya_laporan'] === 2 && $stats['laporan'] >= 2;
        });
    }
}
