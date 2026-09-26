<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HanyaLaporanDokumentasi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function laporan()
    {
        return $this->belongsTo(HanyaLaporan::class, 'hanya_laporan_id');
    }
}
