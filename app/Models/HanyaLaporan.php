<?php

namespace App\Models;

use App\Models\Traits\HasContext;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HanyaLaporan extends Model
{
    use HasContext, HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tgl_laporan' => 'date',
            'tgl_perjalanan' => 'date',
            'ada_dpr' => 'boolean',
            'nilai_dpr' => 'decimal:2',
            'ada_pernyataan' => 'boolean',
        ];
    }

    public function ppkPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'ppk_pegawai_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nama_pegawai', 'nama');
    }

    public function dokumentasi()
    {
        return $this->hasMany(HanyaLaporanDokumentasi::class, 'hanya_laporan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
