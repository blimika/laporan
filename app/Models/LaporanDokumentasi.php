<?php

namespace App\Models;

use Database\Factories\LaporanDokumentasiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanDokumentasi extends Model
{
    /** @use HasFactory<LaporanDokumentasiFactory> */
    use HasFactory;

    protected $guarded = [];

    public function laporan()
    {
        return $this->belongsTo(LaporanPerjalanan::class, 'laporan_id');
    }
}
