<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasContext;

class HanyaLaporan extends Model
{
    use HasFactory, HasContext;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tgl_laporan' => 'date',
            'tgl_perjalanan' => 'date',
        ];
    }

    public function dokumentasi()
    {
        return $this->hasMany(HanyaLaporanDokumentasi::class, 'hanya_laporan_id');
    }
}
