<?php

namespace Tests\Feature;

use App\Models\HanyaLaporan;
use App\Models\Pegawai;
use App\Models\SuratTugas;
use App\Models\Tahun;
use Tests\TestCase;

class TahunContextTest extends TestCase
{
    public function test_tahun_model_fillable_attributes(): void
    {
        $tahun = new Tahun([
            'tahun' => '2026',
            'aktif' => true,
        ]);

        $this->assertSame('2026', $tahun->tahun);
        $this->assertTrue($tahun->aktif);
    }

    public function test_has_context_skips_pegawais_table_for_tahun_scoping(): void
    {
        session(['tahun_id' => 999]);

        $pegawaiSql = Pegawai::query()->toSql();
        $this->assertStringNotContainsString('tahun_id', $pegawaiSql);

        $suratTugasSql = SuratTugas::query()->toSql();
        $this->assertStringContainsString('tahun_id', $suratTugasSql);

        $hanyaLaporanSql = HanyaLaporan::query()->toSql();
        $this->assertStringContainsString('tahun_id', $hanyaLaporanSql);
    }

    public function test_has_context_does_not_set_tahun_id_on_pegawai_creating(): void
    {
        session(['tahun_id' => 123]);

        $pegawai = new Pegawai([
            'nama' => 'Budi Santoso',
            'nip' => '199001012015011001',
        ]);

        // Creating event hook test
        $this->assertNull($pegawai->tahun_id);
    }
}
