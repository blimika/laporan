<?php

namespace Tests\Feature;

use App\Models\Anggaran;
use App\Models\Pegawai;
use App\Models\Satker;
use App\Models\Spd;
use App\Models\SuratTugas;
use App\Models\Tahun;
use App\Models\User;
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
    }
}
