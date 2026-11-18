<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use Tests\TestCase;

class PegawaiTagTest extends TestCase
{
    public function test_available_tags_contains_all_required_tags(): void
    {
        $tags = array_keys(Pegawai::AVAILABLE_TAGS);

        // Role Tags
        $this->assertContains('kepala', $tags);
        $this->assertContains('plt', $tags);
        $this->assertContains('plh', $tags);
        $this->assertContains('ppk', $tags);
        $this->assertContains('bendahara', $tags);
        $this->assertContains('kasubbag', $tags);

        // Status Tags
        $this->assertContains('aktif', $tags);
        $this->assertContains('tidak_aktif', $tags);
        $this->assertContains('mutasi', $tags);
        $this->assertContains('pensiun', $tags);
    }

    public function test_has_tag_method(): void
    {
        $pegawai = new Pegawai([
            'nama' => 'Test Pegawai',
            'nip' => '198501012010011001',
            'tags' => ['ppk', 'kasubbag', 'aktif'],
        ]);

        $this->assertTrue($pegawai->hasTag('ppk'));
        $this->assertTrue($pegawai->hasTag('kasubbag'));
        $this->assertTrue($pegawai->hasTag('aktif'));
        $this->assertFalse($pegawai->hasTag('kepala'));
        $this->assertFalse($pegawai->hasTag('bendahara'));
    }

    public function test_is_kepala_or_plt_or_plh_method(): void
    {
        $kepala = new Pegawai(['tags' => ['kepala']]);
        $plt = new Pegawai(['tags' => ['plt']]);
        $plh = new Pegawai(['tags' => ['plh']]);
        $ppk = new Pegawai(['tags' => ['ppk']]);

        $this->assertTrue($kepala->isKepalaOrPltOrPlh());
        $this->assertTrue($plt->isKepalaOrPltOrPlh());
        $this->assertTrue($plh->isKepalaOrPltOrPlh());
        $this->assertFalse($ppk->isKepalaOrPltOrPlh());
    }

    public function test_is_ppk_method(): void
    {
        $ppk = new Pegawai(['tags' => ['ppk']]);
        $kepala = new Pegawai(['tags' => ['kepala']]);

        $this->assertTrue($ppk->isPpk());
        $this->assertFalse($kepala->isPpk());
    }

    public function test_status_keaktifan_helpers(): void
    {
        $aktif = new Pegawai(['tags' => ['aktif'], 'is_active' => true]);
        $tidakAktif = new Pegawai(['tags' => ['tidak_aktif'], 'is_active' => false]);
        $mutasi = new Pegawai(['tags' => ['mutasi'], 'is_active' => false]);
        $pensiun = new Pegawai(['tags' => ['pensiun'], 'is_active' => false]);

        $this->assertTrue($aktif->isAktif());
        $this->assertFalse($aktif->isTidakAktif());
        $this->assertSame('Aktif', $aktif->status_label);

        $this->assertTrue($tidakAktif->isTidakAktif());
        $this->assertSame('Tidak Aktif', $tidakAktif->status_label);

        $this->assertTrue($mutasi->isTidakAktif());
        $this->assertSame('Mutasi', $mutasi->status_label);

        $this->assertTrue($pensiun->isTidakAktif());
        $this->assertSame('Pensiun', $pensiun->status_label);
    }

    public function test_saving_normalizes_tags_and_sets_is_active(): void
    {
        $pegawai = new Pegawai([
            'nama' => 'Purna Pegawai',
            'nip' => '195501011980011001',
            'tags' => ['pensiun', 'kepala'],
        ]);

        $this->assertArrayNotHasKey('status_keaktifan', $pegawai->getAttributes());
    }
}
