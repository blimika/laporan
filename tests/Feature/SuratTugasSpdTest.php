<?php

namespace Tests\Feature;

use App\Models\Anggaran;
use App\Models\Pegawai;
use App\Models\Satker;
use App\Models\Spd;
use App\Models\SuratTugas;
use App\Models\Tahun;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratTugasSpdTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Satker $satker;

    private Tahun $tahun;

    private Pegawai $pegawai;

    private Pegawai $kepala;

    private Pegawai $ppk;

    private Anggaran $anggaran;

    protected function setUp(): void
    {
        parent::setUp();

        $this->satker = Satker::create([
            'nama' => 'BPS Lombok Tengah',
            'kode' => '5202',
        ]);

        $this->tahun = Tahun::create([
            'tahun' => (int) date('Y'),
            'is_active' => true,
        ]);

        $this->user = User::create([
            'name' => 'Admin Satker',
            'username' => 'adminsatker',
            'email' => 'adminsatker@bps.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'satker_id' => $this->satker->id,
        ]);

        $this->pegawai = Pegawai::create([
            'satker_id' => $this->satker->id,
            'nama' => 'Ahmad Pelaksana, S.Tr.Stat',
            'nip' => '199501012018011001',
            'jabatan' => 'Statistisi Ahli Pertama',
            'golongan' => 'III/a',
            'pangkat' => 'Penata Muda',
            'tags' => ['aktif'],
        ]);

        $this->kepala = Pegawai::create([
            'satker_id' => $this->satker->id,
            'nama' => 'Dr. H. Kepala Satker, M.Si',
            'nip' => '197501011998031001',
            'jabatan' => 'Kepala BPS Kabupaten Lombok Tengah',
            'golongan' => 'IV/b',
            'pangkat' => 'Pembina Tk. I',
            'tags' => ['aktif', 'kepala'],
        ]);

        $this->ppk = Pegawai::create([
            'satker_id' => $this->satker->id,
            'nama' => 'Siti Aminah, S.E.',
            'nip' => '198202022005012002',
            'jabatan' => 'Pranata Keuangan APBN',
            'golongan' => 'III/c',
            'pangkat' => 'Penata',
            'tags' => ['aktif', 'ppk'],
        ]);

        $this->anggaran = Anggaran::create([
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
            'mak' => '2905.BMA.006.051.A.524111',
            'tahun' => (int) date('Y'),
            'program_kode' => '054.01.GG',
            'program_uraian' => 'Program Penyediaan dan Pelayanan Informasi Statistik',
            'kegiatan_kode' => '2905',
            'kegiatan_uraian' => 'Survei Sosial Ekonomi Nasional (SUSENAS)',
            'akun_kode' => '524111',
            'akun_uraian' => 'Belanja Perjalanan Dinas Biasa',
        ]);
    }

    public function test_surat_tugas_create_page_renders_with_rich_sections(): void
    {
        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->get(route('surat-tugas.create'));

        $response->assertStatus(200);
        $response->assertSee('Buat Surat Tugas Baru');
        $response->assertSee('Identitas Surat');
        $response->assertSee('Penandatangan Dokumen');
        $response->assertSee('Rincian Penugasan');
        $response->assertSee('Pembebanan Anggaran');
        $response->assertSee($this->pegawai->nama);
        $response->assertSee($this->kepala->nama);
        $response->assertSee($this->anggaran->mak);
    }

    public function test_surat_tugas_store_creates_record_successfully(): void
    {
        $payload = [
            'nomor_surat' => 'B-001/5202/KP.120/2026',
            'tgl_surat' => '2026-03-01',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA BPS Lombok Tengah Tahun 2026',
            'tujuan' => 'Kantor Desa Kuta, Kec. Pujut',
            'tugas' => 'Melaksanakan pengawasan lapangan survei ekonomi',
            'tgl_berangkat' => '2026-03-05',
            'tgl_kembali' => '2026-03-07',
            'lama_hari' => 3,
        ];

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->post(route('surat-tugas.store'), $payload);

        $response->assertRedirect(route('surat-tugas.index'));
        $response->assertSessionHas('success', 'Surat Tugas berhasil dibuat.');

        $this->assertDatabaseHas('surat_tugas', [
            'nomor_surat' => 'B-001/5202/KP.120/2026',
            'tujuan' => 'Kantor Desa Kuta, Kec. Pujut',
            'pegawai_id' => $this->pegawai->id,
        ]);
    }

    public function test_surat_tugas_edit_page_renders_with_existing_data(): void
    {
        $st = SuratTugas::create([
            'nomor_surat' => 'B-002/5202/KP.120/2026',
            'tgl_surat' => '2026-03-02',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA BPS Lombok Tengah Tahun 2026',
            'tujuan' => 'Kecamatan Praya Timur',
            'tugas' => 'Melakukan verifikasi data',
            'tgl_berangkat' => '2026-03-10',
            'tgl_kembali' => '2026-03-11',
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
        ]);

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->get(route('surat-tugas.edit', $st));

        $response->assertStatus(200);
        $response->assertSee('Edit Surat Tugas');
        $response->assertSee('B-002/5202/KP.120/2026');
        $response->assertSee('Kecamatan Praya Timur');
    }

    public function test_spd_create_page_renders_with_surat_tugas_overview(): void
    {
        $st = SuratTugas::create([
            'nomor_surat' => 'B-003/5202/KP.120/2026',
            'tgl_surat' => '2026-03-02',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA BPS Lombok Tengah Tahun 2026',
            'tujuan' => 'Kecamatan Kopang',
            'tugas' => 'Sosialisasi Sensus Pertanian',
            'tgl_berangkat' => '2026-03-15',
            'tgl_kembali' => '2026-03-16',
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
        ]);

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->get(route('spd.create', $st));

        $response->assertStatus(200);
        $response->assertSee('Penerbitan Surat Perjalanan Dinas (SPD)');
        $response->assertSee('Surat Tugas Referensi');
        $response->assertSee('B-003/5202/KP.120/2026');
        $response->assertSee($this->pegawai->nama);
        $response->assertSee('Kecamatan Kopang');
        $response->assertSee('Pejabat Pembuat Komitmen (PPK)');
        $response->assertSee('Daftar Pengeluaran Riil (DPR)');
    }

    public function test_spd_store_creates_spd_and_dpr(): void
    {
        $st = SuratTugas::create([
            'nomor_surat' => 'B-004/5202/KP.120/2026',
            'tgl_surat' => '2026-03-02',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA BPS Lombok Tengah Tahun 2026',
            'tujuan' => 'Kecamatan Janapria',
            'tugas' => 'Pendataan lapangan',
            'tgl_berangkat' => '2026-03-20',
            'tgl_kembali' => '2026-03-21',
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
        ]);

        $payload = [
            'nomor_spd' => '004/SPD/5202/2026',
            'ppk_pegawai_id' => $this->ppk->id,
            'kendaraan' => 'Kendaraan Dinas',
            'tgl_dpr' => '2026-03-21',
            'nilai_dpr' => 350000,
        ];

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->post(route('spd.store', $st), $payload);

        $response->assertRedirect(route('surat-tugas.index'));
        $response->assertSessionHas('success', 'SPD berhasil dibuat.');

        $this->assertDatabaseHas('spds', [
            'surat_tugas_id' => $st->id,
            'nomor_spd' => '004/SPD/5202/2026',
            'ppk_pegawai_id' => $this->ppk->id,
            'kendaraan' => 'Kendaraan Dinas',
            'nilai_dpr' => 350000.00,
        ]);
    }

    public function test_spd_edit_page_renders_with_existing_spd(): void
    {
        $st = SuratTugas::create([
            'nomor_surat' => 'B-005/5202/KP.120/2026',
            'tgl_surat' => '2026-03-02',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA BPS Lombok Tengah Tahun 2026',
            'tujuan' => 'Kecamatan Batukliang',
            'tugas' => 'Uji petik kualitas data',
            'tgl_berangkat' => '2026-03-25',
            'tgl_kembali' => '2026-03-26',
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
        ]);

        $spd = Spd::create([
            'surat_tugas_id' => $st->id,
            'nomor_spd' => '005/SPD/5202/2026',
            'ppk_pegawai_id' => $this->ppk->id,
            'kendaraan' => 'Kendaraan Umum',
            'tgl_dpr' => '2026-03-26',
            'nilai_dpr' => 450000,
        ]);

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->get(route('spd.edit', $spd));

        $response->assertStatus(200);
        $response->assertSee('Edit Surat Perjalanan Dinas (SPD)');
        $response->assertSee('005/SPD/5202/2026');
        $response->assertSee('450.000');
        $response->assertSee('Template 1 (Standar)');
        $response->assertSee('Template 2 (Lampiran ST)');
    }

    public function test_spd_export_template_1_and_template_2(): void
    {
        $st = SuratTugas::create([
            'nomor_surat' => 'B-006/5202/KP.120/2026',
            'tgl_surat' => '2026-03-02',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA BPS Lombok Tengah Tahun 2026',
            'tujuan' => 'Kecamatan Batukliang',
            'tugas' => 'Uji petik kualitas data',
            'tgl_berangkat' => '2026-03-25',
            'tgl_kembali' => '2026-03-26',
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
        ]);

        $spd = Spd::create([
            'surat_tugas_id' => $st->id,
            'nomor_spd' => '006/SPD/5202/2026',
            'ppk_pegawai_id' => $this->ppk->id,
            'kendaraan' => 'Kendaraan Umum',
            'tgl_dpr' => '2026-03-26',
            'nilai_dpr' => 450000,
        ]);

        // Default export (template_1)
        $response1 = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->get(route('export.spd', $spd));

        $response1->assertStatus(200);
        $this->assertEquals('application/pdf', $response1->headers->get('Content-Type'));

        // Explicit template_2 export
        $response2 = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->get(route('export.spd', ['spd' => $spd, 'template' => 'template_2']));

        $response2->assertStatus(200);
        $this->assertEquals('application/pdf', $response2->headers->get('Content-Type'));

        // Pastikan hasil cetak template_2 pas 1 halaman
        $pdfDom = Pdf::loadView('exports.pdf.spd', ['spd' => $spd, 'template' => 'template_2']);
        $pdfDom->render();
        $this->assertEquals(1, $pdfDom->getCanvas()->get_page_count());

        // Test template_2 blade view content directly
        $viewHtml = view('exports.pdf.spd_template_2', [
            'spd' => $spd->load(['suratTugas.pegawai', 'suratTugas.satker', 'suratTugas.kepalaPegawai', 'ppkPegawai']),
        ])->render();

        // Must have Lampiran Surat Tugas header with ST number & date
        $this->assertStringContainsString('Lampiran Surat Tugas', $viewHtml);
        $this->assertStringContainsString('B-006/5202/KP.120/2026', $viewHtml);
        $this->assertStringContainsString('02 Maret 2026', $viewHtml);

        // Must NOT contain SPD number in template_2 (explicit user instruction)
        $this->assertStringNotContainsString('006/SPD/5202/2026', $viewHtml);

        // Kolom 1 Baris 1 harus kosong (sel kosong)
        $this->assertMatchesRegularExpression('/<tr>\s*<td style="height: 200px;">\s*<\/td>/', $viewHtml);

        // Kolom 2 Baris 1: Berangkat dari, tanpa Romawi I, dan penandatangan dinamis TitleCase
        $this->assertStringContainsString('Berangkat dari', $viewHtml);
        $this->assertStringContainsString('(tempat kedudukan)', $viewHtml);
        $this->assertStringContainsString('Kecamatan Batukliang', $viewHtml);
        $this->assertStringContainsString('25 Maret 2026', $viewHtml);
        $this->assertStringContainsString('Kepala Badan Pusat Statistik', $viewHtml);
        $this->assertStringNotContainsString('Kepala BADAN PUSAT STATISTIK', $viewHtml);
        $this->assertStringContainsString($this->kepala->nama, $viewHtml);
        $this->assertStringContainsString($this->kepala->nip, $viewHtml);

        // Tidak boleh ada Romawi I maupun Romawi II
        $this->assertStringNotContainsString('I.', $viewHtml);
        $this->assertStringNotContainsString('II.', $viewHtml);
        $this->assertStringNotContainsString('Direktur/Kepala Biro', $viewHtml);

        // Baris 2: Tiba di & Berangkat dari serta garis titik-titik tipis ttd
        $this->assertStringContainsString('Tiba di', $viewHtml);
        $this->assertStringContainsString('Berangkat dari', $viewHtml);
        $this->assertStringContainsString('26 Maret 2026', $viewHtml);
        $this->assertStringContainsString('............................................................', $viewHtml);

        // Test penandatangan Plh / Plt
        $plhPegawai = Pegawai::create([
            'satker_id' => $this->satker->id,
            'nama' => 'Budi Plh, M.M',
            'nip' => '198001012005011003',
            'jabatan' => 'Plh. Kepala BPS',
            'tags' => ['aktif', 'plh'],
        ]);
        $st->update(['kepala_pegawai_id' => $plhPegawai->id]);
        $viewHtmlPlh = view('exports.pdf.spd_template_2', [
            'spd' => $spd->fresh()->load(['suratTugas.pegawai', 'suratTugas.satker', 'suratTugas.kepalaPegawai', 'ppkPegawai']),
        ])->render();
        $this->assertStringContainsString('Plh. Kepala Badan Pusat Statistik', $viewHtmlPlh);
        $this->assertStringContainsString('Budi Plh, M.M', $viewHtmlPlh);

        // Cek juga template_1 menggunakan TitleCase
        $viewHtmlT1 = view('exports.pdf.spd_template_1', [
            'spd' => $spd->fresh()->load(['suratTugas.pegawai', 'suratTugas.satker', 'suratTugas.kepalaPegawai', 'ppkPegawai']),
        ])->render();
        $this->assertStringContainsString('Plh. Kepala Badan Pusat Statistik', $viewHtmlT1);

        // Cek juga surat_tugas menggunakan TitleCase
        $viewHtmlSt = view('exports.pdf.surat_tugas', [
            'suratTugas' => $st->fresh()->load(['pegawai', 'satker', 'kepalaPegawai', 'anggaran']),
        ])->render();
        $this->assertStringContainsString('Plh. Kepala Badan Pusat Statistik', $viewHtmlSt);

        // Surat Tugas Gabungan with template_2
        $responseGabungan = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->get(route('export.surat-tugas', ['suratTugas' => $st, 'template' => 'template_2']));

        $responseGabungan->assertStatus(200);
        $this->assertEquals('application/pdf', $responseGabungan->headers->get('Content-Type'));
    }

    public function test_surat_tugas_auto_creates_spd_with_template_2_and_sets_tgl_dpr_equal_to_tgl_kembali(): void
    {
        $payload = [
            'nomor_surat' => 'B-100/5202/KP.120/2026',
            'tgl_surat' => '2026-03-01',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA 2026',
            'tujuan' => 'Kecamatan Praya Timur',
            'tugas' => 'Monitoring Sakernas',
            'tgl_berangkat' => '2026-03-10',
            'tgl_kembali' => '2026-03-12',
            'lama_hari' => 3,
            'spd_template' => 'template_2',
            'nilai_dpr' => '250.000',
            'ppk_pegawai_id' => $this->ppk->id,
        ];

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->post(route('surat-tugas.store'), $payload);

        $response->assertRedirect(route('surat-tugas.index'));

        $st = SuratTugas::where('nomor_surat', 'B-100/5202/KP.120/2026')->first();
        $this->assertNotNull($st);
        $this->assertEquals('template_2', $st->spd_template);

        // SPD harus otomatis dibuat
        $spd = Spd::where('surat_tugas_id', $st->id)->first();
        $this->assertNotNull($spd);
        $this->assertEquals('2026-03-12', $spd->tgl_dpr->format('Y-m-d'));
        $this->assertEquals($st->tgl_kembali->format('Y-m-d'), $spd->tgl_dpr->format('Y-m-d'));
        $this->assertEquals(250000.0, (float) $spd->nilai_dpr);
        $this->assertEquals($this->ppk->id, $spd->ppk_pegawai_id);
    }

    public function test_surat_tugas_does_not_auto_create_spd_when_template_1_selected(): void
    {
        $payload = [
            'nomor_surat' => 'B-101/5202/KP.120/2026',
            'tgl_surat' => '2026-03-01',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA 2026',
            'tujuan' => 'Kecamatan Praya Barat',
            'tugas' => 'Survei Harga Produsen',
            'tgl_berangkat' => '2026-03-15',
            'tgl_kembali' => '2026-03-16',
            'lama_hari' => 2,
            'spd_template' => 'template_1',
        ];

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->post(route('surat-tugas.store'), $payload);

        $response->assertRedirect(route('surat-tugas.index'));

        $st = SuratTugas::where('nomor_surat', 'B-101/5202/KP.120/2026')->first();
        $this->assertNotNull($st);
        $this->assertEquals('template_1', $st->spd_template);

        // SPD TIDAK boleh dibuat otomatis jika template_1
        $spd = Spd::where('surat_tugas_id', $st->id)->first();
        $this->assertNull($spd);
    }

    public function test_surat_tugas_edit_updates_spd_when_template_2(): void
    {
        $st = SuratTugas::create([
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
            'nomor_surat' => 'B-102/5202/KP.120/2026',
            'tgl_surat' => '2026-03-01',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA 2026',
            'tujuan' => 'Kecamatan Kopang',
            'tugas' => 'Survei Konstruksi',
            'tgl_berangkat' => '2026-03-20',
            'tgl_kembali' => '2026-03-21',
            'spd_template' => 'template_2',
            'user_id' => $this->user->id,
        ]);

        $updatePayload = [
            'nomor_surat' => 'B-102/5202/KP.120/2026',
            'tgl_surat' => '2026-03-01',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'uraian_pembebanan' => 'Beban DIPA 2026',
            'tujuan' => 'Kecamatan Kopang Diperbarui',
            'tugas' => 'Survei Konstruksi',
            'tgl_berangkat' => '2026-03-20',
            'tgl_kembali' => '2026-03-22',
            'lama_hari' => 3,
            'spd_template' => 'template_2',
            'nilai_dpr' => '450.000',
            'ppk_pegawai_id' => $this->ppk->id,
        ];

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->put(route('surat-tugas.update', $st->id), $updatePayload);

        $response->assertRedirect(route('surat-tugas.index'));

        $spd = Spd::where('surat_tugas_id', $st->id)->first();
        $this->assertNotNull($spd);
        $this->assertEquals('2026-03-22', $spd->tgl_dpr->format('Y-m-d'));
        $this->assertEquals(450000.0, (float) $spd->nilai_dpr);
    }

    public function test_surat_tugas_index_shows_t1_and_t2_badges_and_actions(): void
    {
        $st1 = SuratTugas::create([
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
            'nomor_surat' => 'B-T1/5202/2026',
            'tgl_surat' => '2026-03-01',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'tujuan' => 'Tujuan T1',
            'tugas' => 'Tugas T1',
            'tgl_berangkat' => '2026-03-10',
            'tgl_kembali' => '2026-03-11',
            'spd_template' => 'template_1',
            'user_id' => $this->user->id,
        ]);

        $st2 = SuratTugas::create([
            'satker_id' => $this->satker->id,
            'tahun_id' => $this->tahun->id,
            'nomor_surat' => 'B-T2/5202/2026',
            'tgl_surat' => '2026-03-01',
            'pegawai_id' => $this->pegawai->id,
            'kepala_pegawai_id' => $this->kepala->id,
            'pembebanan_anggaran_id' => $this->anggaran->id,
            'tujuan' => 'Tujuan T2',
            'tugas' => 'Tugas T2',
            'tgl_berangkat' => '2026-03-12',
            'tgl_kembali' => '2026-03-13',
            'spd_template' => 'template_2',
            'user_id' => $this->user->id,
        ]);

        Spd::create([
            'surat_tugas_id' => $st2->id,
            'nomor_spd' => 'T2/B-T2-5202-2026',
            'ppk_pegawai_id' => $this->ppk->id,
            'kendaraan' => 'Kendaraan Umum',
            'tgl_dpr' => $st2->tgl_kembali,
            'nilai_dpr' => 300000,
        ]);

        $response = $this->actingAs($this->user)
            ->withSession([
                'satker_id' => $this->satker->id,
                'tahun_id' => $this->tahun->id,
            ])
            ->get(route('surat-tugas.index'));

        $response->assertStatus(200);
        $response->assertSee('T1');
        $response->assertSee('T2');
        $response->assertSee('SPD Terbit (T2)');
        $response->assertSee('+ Terbitkan SPD (T1)');
    }
}
