<?php

namespace App\Models;

use Database\Factories\AnggaranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    /** @use HasFactory<AnggaranFactory> */
    use HasFactory, Traits\HasContext;

    protected $guarded = [];

    public function suratTugas()
    {
        return $this->hasMany(SuratTugas::class, 'pembebanan_anggaran_id');
    }
}
