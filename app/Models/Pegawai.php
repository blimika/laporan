<?php

namespace App\Models;

use Database\Factories\PegawaiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    /** @use HasFactory<PegawaiFactory> */
    use HasFactory, Traits\HasContext;

    protected $guarded = [];

    public const TAG_KEPALA = 'kepala';

    public const TAG_PLT = 'plt';

    public const TAG_PLH = 'plh';

    public const TAG_PPK = 'ppk';

    public const TAG_BENDAHARA = 'bendahara';

    public const TAG_KASUBBAG = 'kasubbag';

    public const TAG_AKTIF = 'aktif';

    public const TAG_TIDAK_AKTIF = 'tidak_aktif';

    public const TAG_MUTASI = 'mutasi';

    public const TAG_PENSIUN = 'pensiun';

    public const ROLE_TAGS = ['kepala', 'plt', 'plh', 'ppk', 'bendahara', 'kasubbag'];

    public const STATUS_TAGS = ['aktif', 'tidak_aktif', 'mutasi', 'pensiun'];

    public const INACTIVE_TAGS = ['tidak_aktif', 'mutasi', 'pensiun'];

    public const AVAILABLE_TAGS = [
        // Role Tags (Peran Penandatangan)
        'kepala' => [
            'label' => 'Kepala',
            'desc' => 'Kepala Satker (Penandatangan ST)',
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-800',
            'border' => 'border-purple-300',
            'dot' => 'bg-purple-500',
            'category' => 'peran',
        ],
        'plt' => [
            'label' => 'Plt.',
            'desc' => 'Plt. Kepala (Penandatangan ST)',
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-800',
            'border' => 'border-amber-300',
            'dot' => 'bg-amber-500',
            'category' => 'peran',
        ],
        'plh' => [
            'label' => 'Plh.',
            'desc' => 'Plh. Kepala (Penandatangan ST)',
            'bg' => 'bg-orange-100',
            'text' => 'text-orange-800',
            'border' => 'border-orange-300',
            'dot' => 'bg-orange-500',
            'category' => 'peran',
        ],
        'ppk' => [
            'label' => 'PPK',
            'desc' => 'Pejabat Pembuat Komitmen (Penandatangan SPD & DPR)',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'border' => 'border-blue-300',
            'dot' => 'bg-blue-500',
            'category' => 'peran',
        ],
        'bendahara' => [
            'label' => 'Bendahara',
            'desc' => 'Bendahara Pengeluaran',
            'bg' => 'bg-emerald-100',
            'text' => 'text-emerald-800',
            'border' => 'border-emerald-300',
            'dot' => 'bg-emerald-500',
            'category' => 'peran',
        ],
        'kasubbag' => [
            'label' => 'Kasubbag',
            'desc' => 'Kepala Subbagian Umum / TU',
            'bg' => 'bg-rose-100',
            'text' => 'text-rose-800',
            'border' => 'border-rose-300',
            'dot' => 'bg-rose-500',
            'category' => 'peran',
        ],

        // Status Tags (Status Keaktifan)
        'aktif' => [
            'label' => 'Aktif',
            'desc' => 'Pegawai Aktif',
            'bg' => 'bg-emerald-100',
            'text' => 'text-emerald-800',
            'border' => 'border-emerald-300',
            'dot' => 'bg-emerald-500',
            'category' => 'status',
        ],
        'tidak_aktif' => [
            'label' => 'Tidak Aktif',
            'desc' => 'Pegawai Tidak Aktif',
            'bg' => 'bg-gray-100',
            'text' => 'text-gray-800',
            'border' => 'border-gray-300',
            'dot' => 'bg-gray-500',
            'category' => 'status',
        ],
        'mutasi' => [
            'label' => 'Mutasi',
            'desc' => 'Pegawai Mutasi / Pindah Instansi',
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-800',
            'border' => 'border-amber-300',
            'dot' => 'bg-amber-500',
            'category' => 'status',
        ],
        'pensiun' => [
            'label' => 'Pensiun',
            'desc' => 'Pegawai Pensiun / Purna Tugas',
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-800',
            'border' => 'border-slate-300',
            'dot' => 'bg-slate-500',
            'category' => 'status',
        ],
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::saving(function ($pegawai) {
            $tags = $pegawai->tags ?? [];
            if (! is_array($tags)) {
                $tags = [];
            }

            $isInactive = false;
            foreach (self::INACTIVE_TAGS as $t) {
                if (in_array($t, $tags, true)) {
                    $isInactive = true;
                    break;
                }
            }

            $pegawai->is_active = ! $isInactive;

            if ($isInactive) {
                $tags = array_values(array_diff($tags, ['aktif']));
            } else {
                $tags = array_values(array_diff($tags, self::INACTIVE_TAGS));
                if (! in_array('aktif', $tags, true)) {
                    $tags[] = 'aktif';
                }
            }

            $pegawai->tags = array_values(array_unique($tags));
        });
    }

    public function hasTag(string $tag): bool
    {
        return is_array($this->tags) && in_array($tag, $this->tags, true);
    }

    public function isAktif(): bool
    {
        return (bool) $this->is_active;
    }

    public function isTidakAktif(): bool
    {
        return ! $this->isAktif();
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->hasTag('pensiun')) {
            return 'Pensiun';
        }
        if ($this->hasTag('mutasi')) {
            return 'Mutasi';
        }
        if ($this->hasTag('tidak_aktif') || ! $this->is_active) {
            return 'Tidak Aktif';
        }

        return 'Aktif';
    }

    public function getStatusInfoAttribute(): array
    {
        $key = 'aktif';
        if ($this->hasTag('pensiun')) {
            $key = 'pensiun';
        } elseif ($this->hasTag('mutasi')) {
            $key = 'mutasi';
        } elseif ($this->hasTag('tidak_aktif') || ! $this->is_active) {
            $key = 'tidak_aktif';
        }

        return self::AVAILABLE_TAGS[$key];
    }

    public function isKepalaOrPltOrPlh(): bool
    {
        return $this->hasTag('kepala') || $this->hasTag('plt') || $this->hasTag('plh');
    }

    public function isPpk(): bool
    {
        return $this->hasTag('ppk');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeWithTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function scopeWithAnyTag($query, array $tags)
    {
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhereJsonContains('tags', $tag);
            }
        });
    }

    public function scopeKepala($query)
    {
        return $this->scopeWithAnyTag($query, ['kepala', 'plt', 'plh']);
    }

    public function scopePpk($query)
    {
        return $query->whereJsonContains('tags', 'ppk');
    }

    public function scopeBendahara($query)
    {
        return $query->whereJsonContains('tags', 'bendahara');
    }

    public function scopeKasubbag($query)
    {
        return $query->whereJsonContains('tags', 'kasubbag');
    }

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
