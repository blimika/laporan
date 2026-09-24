<?php

namespace App\Models;

use Database\Factories\SpdFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spd extends Model
{
    /** @use HasFactory<SpdFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tgl_dpr' => 'date',
            'nilai_dpr' => 'decimal:2',
        ];
    }

    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'surat_tugas_id');
    }

    public function ppkPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ppk_pegawai_id');
    }
}
