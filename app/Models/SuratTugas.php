<?php

namespace App\Models;

use Database\Factories\SuratTugasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    /** @use HasFactory<SuratTugasFactory> */
    use HasFactory, Traits\HasContext;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tgl_surat' => 'date',
            'tgl_berangkat' => 'date',
            'tgl_kembali' => 'date',
        ];
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function kepalaPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'kepala_pegawai_id');
    }

    public function anggaran()
    {
        return $this->belongsTo(Anggaran::class, 'pembebanan_anggaran_id');
    }

    public function spd()
    {
        return $this->hasOne(Spd::class);
    }

    public function laporanPerjalanan()
    {
        return $this->hasOne(LaporanPerjalanan::class);
    }
}
