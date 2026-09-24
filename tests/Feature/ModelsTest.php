<?php

namespace Tests\Feature;

use App\Models\Anggaran;
use App\Models\Pegawai;
use App\Models\SuratTugas;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    public function test_anggaran_has_surat_tugas(): void
    {
        $anggaran = new Anggaran;
        $this->assertInstanceOf(HasMany::class, $anggaran->suratTugas());
    }

    public function test_pegawai_has_relations(): void
    {
        $pegawai = new Pegawai;
        $this->assertInstanceOf(HasMany::class, $pegawai->suratTugas());
        $this->assertInstanceOf(HasMany::class, $pegawai->suratTugasKepala());
        $this->assertInstanceOf(HasMany::class, $pegawai->spds());
    }

    public function test_surat_tugas_has_relations(): void
    {
        $surat = new SuratTugas;
        $this->assertInstanceOf(BelongsTo::class, $surat->pegawai());
        $this->assertInstanceOf(HasOne::class, $surat->spd());
        $this->assertInstanceOf(HasOne::class, $surat->laporanPerjalanan());
    }
}
