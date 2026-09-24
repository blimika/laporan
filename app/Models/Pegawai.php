<?php

namespace App\Models;

use Database\Factories\PegawaiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    /** @use HasFactory<PegawaiFactory> */
    use HasFactory;

    protected $guarded = [];

    public function suratTugas()
    {
        return $this->hasMany(SuratTugas::class, 'pegawai_id');
    }

    public function suratTugasKepala()
    {
        return $this->hasMany(SuratTugas::class, 'kepala_pegawai_id');
    }

    public function spds()
    {
        return $this->hasMany(Spd::class, 'ppk_pegawai_id');
    }
}
