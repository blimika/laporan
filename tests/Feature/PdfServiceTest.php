<?php

namespace Tests\Feature;

use App\Models\Anggaran;
use App\Models\HanyaLaporan;
use App\Models\Pegawai;
use App\Models\Satker;
use App\Models\Spd;
use App\Models\SuratTugas;
use Barryvdh\DomPDF\Facade\Pdf;
use Tests\TestCase;

class PdfServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_gabungan_pdf_view_renders_successfully(): void
    {
        $satker = new Satker(['nama' => 'BPS Kota Mataram']);
        $pegawai = new Pegawai([
            'nama' => 'John Doe',
            'nip' => '198501012010011001',
            'jabatan' => 'Pranata Komputer',
            'golongan' => 'III/a',
            'pangkat' => 'Penata Muda',
        ]);
        $kepala = new Pegawai([
            'nama' => 'Jane Boss',
            'nip' => '197501011998011002',
        ]);
        $anggaran = new Anggaran([
            'mak' => '054.01.GG.2890.QEA.001.051.A.524111',
            'program_kode' => '054.01.GG',
            'program_uraian' => 'Program Penyediaan dan Pelayanan Informasi Statistik',
            'kegiatan_kode' => '2890',
            'kegiatan_uraian' => 'Pengembangan Sistem Informasi Statistik',
            'output_kode' => 'QEA',
            'output_uraian' => 'Layanan TI',
            'suboutput_kode' => '001',
            'suboutput_uraian' => 'Pengelolaan Infrastruktur TI',
            'komponen_kode' => '051',
            'komponen_uraian' => 'Operasional TI',
            'subkomponen_kode' => 'A',
            'subkomponen_uraian' => 'Dukungan Teknis',
            'akun_kode' => '524111',
            'akun_uraian' => 'Belanja Perjalanan Dinas Biasa',
        ]);

        $st = new SuratTugas([
            'nomor_surat' => 'B-001/52710/2026',
            'tgl_surat' => '2026-06-01',
            'tgl_berangkat' => '2026-06-02',
            'tgl_kembali' => '2026-06-02',
            'tujuan' => 'Mataram',
            'tugas' => 'Melakukan Pendataan Lapangan',
            'uraian_pembebanan' => 'Dibiayai DIPA BPS Kota Mataram',
        ]);
        $st->setRelation('satker', $satker);
        $st->setRelation('pegawai', $pegawai);
        $st->setRelation('kepalaPegawai', $kepala);
        $st->setRelation('anggaran', $anggaran);

        $spd = new Spd([
            'nomor_spd' => 'SPD-001/2026',
            'kendaraan' => 'Kendaraan Umum',
            'nilai_dpr' => 150000,
        ]);
        $spd->setRelation('suratTugas', $st);
        $spd->setRelation('ppkPegawai', $kepala);
        $st->setRelation('spd', $spd);

        $pdf = Pdf::loadView('exports.pdf.surat_tugas_spd_gabungan', [
            'suratTugas' => $st,
            'spd' => $spd,
        ]);
        $pdf->render();

        $this->assertEquals(5, $pdf->getCanvas()->get_page_count());
    }

    public function test_gabungan_pdf_view_renders_single_page_without_spd(): void
    {
        $satker = new Satker(['nama' => 'BPS Kota Mataram']);
        $pegawai = new Pegawai([
            'nama' => 'John Doe',
            'nip' => '198501012010011001',
            'jabatan' => 'Pranata Komputer',
            'golongan' => 'III/a',
            'pangkat' => 'Penata Muda',
        ]);
        $kepala = new Pegawai([
            'nama' => 'Jane Boss',
            'nip' => '197501011998011002',
        ]);
        $anggaran = new Anggaran([
            'mak' => '054.01.GG.2890',
        ]);

        $st = new SuratTugas([
            'nomor_surat' => 'B-001/52710/2026',
            'tgl_surat' => '2026-06-01',
            'tgl_berangkat' => '2026-06-02',
            'tgl_kembali' => '2026-06-02',
            'tujuan' => 'Mataram',
            'tugas' => 'Melakukan Pendataan Lapangan',
            'uraian_pembebanan' => 'Dibiayai DIPA',
        ]);
        $st->setRelation('satker', $satker);
        $st->setRelation('pegawai', $pegawai);
        $st->setRelation('kepalaPegawai', $kepala);
        $st->setRelation('anggaran', $anggaran);
        $st->setRelation('spd', null);

        $pdf = Pdf::loadView('exports.pdf.surat_tugas_spd_gabungan', [
            'suratTugas' => $st,
            'spd' => null,
        ]);
        $pdf->render();

        $this->assertEquals(1, $pdf->getCanvas()->get_page_count());
    }

    public function test_hanya_laporan_pdf_includes_dpr_when_enabled(): void
    {
        $satker = new Satker(['nama' => 'BPS Kota Mataram']);
        $ppk = new Pegawai([
            'nama' => 'Jane PPK',
            'nip' => '197501011998011002',
        ]);

        $laporan = new HanyaLaporan([
            'nama_pegawai' => 'John Doe',
            'nip_pegawai' => '198501012010011001',
            'nomor_st' => 'ST/001/2026',
            'nomor_spd' => 'SPD/001/2026',
            'tgl_laporan' => '2026-06-01',
            'tgl_perjalanan' => '2026-06-01',
            'lokasi_perjalanan' => 'Mataram',
            'tujuan_perjalanan' => 'Pendataan Lapangan',
            'pegawai_ditemui' => 'Responden',
            'kategori' => 'Pendataan Lapangan',
            'hasil_perjalanan' => 'Kegiatan berjalan lancar.',
            'ada_dpr' => true,
            'nilai_dpr' => 150000,
            'ada_pernyataan' => false,
        ]);
        $laporan->setRelation('satker', $satker);
        $laporan->setRelation('ppkPegawai', $ppk);
        $laporan->setRelation('dokumentasi', collect());

        $pdf = Pdf::loadView('exports.pdf.hanya_laporan', ['laporan' => $laporan]);
        $pdf->render();

        // 1 page laporan + 1 page DPR = 2 pages
        $this->assertEquals(2, $pdf->getCanvas()->get_page_count());
    }

    public function test_hanya_laporan_pdf_includes_both_dpr_and_pernyataan_when_enabled(): void
    {
        $satker = new Satker(['nama' => 'BPS Kota Mataram']);
        $pegawai = new Pegawai([
            'nama' => 'John Doe',
            'nip' => '198501012010011001',
            'jabatan' => 'Pranata Komputer',
            'golongan' => 'III/a',
            'pangkat' => 'Penata Muda',
        ]);
        $ppk = new Pegawai([
            'nama' => 'Jane PPK',
            'nip' => '197501011998011002',
        ]);

        $laporan = new HanyaLaporan([
            'nama_pegawai' => 'John Doe',
            'nip_pegawai' => '198501012010011001',
            'nomor_st' => 'ST/001/2026',
            'nomor_spd' => 'SPD/001/2026',
            'tgl_laporan' => '2026-06-01',
            'tgl_perjalanan' => '2026-06-01',
            'lokasi_perjalanan' => 'Mataram',
            'tujuan_perjalanan' => 'Pendataan Lapangan',
            'pegawai_ditemui' => 'Responden',
            'kategori' => 'Pendataan Lapangan',
            'hasil_perjalanan' => 'Kegiatan berjalan lancar.',
            'ada_dpr' => true,
            'nilai_dpr' => 150000,
            'ada_pernyataan' => true,
        ]);
        $laporan->setRelation('satker', $satker);
        $laporan->setRelation('pegawai', $pegawai);
        $laporan->setRelation('ppkPegawai', $ppk);
        $laporan->setRelation('dokumentasi', collect());

        $pdf = Pdf::loadView('exports.pdf.hanya_laporan', ['laporan' => $laporan]);
        $pdf->render();

        // 1 page laporan + 1 page DPR + 1 page pernyataan = 3 pages
        $this->assertEquals(3, $pdf->getCanvas()->get_page_count());
    }
}
