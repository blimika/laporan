<?php

namespace App\Models;

use Database\Factories\LaporanPerjalananFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPerjalanan extends Model
{
    /** @use HasFactory<LaporanPerjalananFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tgl_laporan' => 'date',
            'tgl_perjalanan' => 'date',
        ];
    }

    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'surat_tugas_id');
    }

    public function dokumentasi()
    {
        return $this->hasMany(LaporanDokumentasi::class, 'laporan_id');
    }
}
