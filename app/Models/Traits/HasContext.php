<?php

namespace App\Models\Traits;

use App\Models\Satker;
use Illuminate\Database\Eloquent\Builder;

trait HasContext
{
    protected static function bootHasContext()
    {
        static::addGlobalScope('context', function (Builder $builder) {
            $table = $builder->getModel()->getTable();

            if (session()->get('satker_id')) {
                $builder->where($table.'.satker_id', session('satker_id'));
            }
            if ($table !== 'pegawais' && session()->get('tahun_id')) {
                $builder->where($table.'.tahun_id', session('tahun_id'));
            }

            // Check if user role is 'user' and table is surat_tugas, laporan_perjalanans, or hanya_laporans
            if (auth()->check() && auth()->user()->role === 'user') {
                if (in_array($table, ['surat_tugas', 'laporan_perjalanans', 'hanya_laporans'])) {
                    $builder->where($table.'.user_id', auth()->id());
                }
            }
        });

        static::creating(function ($model) {
            $table = $model->getTable();

            if (session()->get('satker_id')) {
                $model->satker_id = session('satker_id');
            }
            if ($table !== 'pegawais' && session()->get('tahun_id')) {
                $model->tahun_id = session('tahun_id');
            }
            if (auth()->check() && in_array($table, ['surat_tugas', 'laporan_perjalanans', 'hanya_laporans'])) {
                if (empty($model->user_id)) {
                    $model->user_id = auth()->id();
                }
            }
        });
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class, 'satker_id');
    }
}
