<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasContext
{
    protected static function bootHasContext()
    {
        static::addGlobalScope('context', function (Builder $builder) {
            if (session()->get('satker_id')) {
                $builder->where($builder->getModel()->getTable() . '.satker_id', session('satker_id'));
            }
            if (session()->get('tahun_id')) {
                $builder->where($builder->getModel()->getTable() . '.tahun_id', session('tahun_id'));
            }
            
            // Check if user role is 'user' and table is surat_tugas or laporan_perjalanans
            if (auth()->check() && auth()->user()->role === 'user') {
                $table = $builder->getModel()->getTable();
                if (in_array($table, ['surat_tugas', 'laporan_perjalanans'])) {
                    $builder->where($table . '.user_id', auth()->id());
                }
            }
        });

        static::creating(function ($model) {
            if (session()->get('satker_id')) {
                $model->satker_id = session('satker_id');
            }
            if (session()->get('tahun_id')) {
                $model->tahun_id = session('tahun_id');
            }
            if (auth()->check() && in_array($model->getTable(), ['surat_tugas', 'laporan_perjalanans'])) {
                if (empty($model->user_id)) {
                    $model->user_id = auth()->id();
                }
            }
        });
    }

    public function satker()
    {
        return $this->belongsTo(\App\Models\Satker::class, 'satker_id');
    }
}
