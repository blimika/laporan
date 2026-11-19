<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\HanyaLaporan;
use App\Models\LaporanPerjalanan;
use App\Models\Pegawai;
use App\Models\Satker;
use App\Models\Spd;
use App\Models\SuratTugas;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index(Request $request): View
    {
        $currentSatker = Satker::find(session('satker_id'));
        $currentTahun = Tahun::find(session('tahun_id'));

        // Core Counts
        $totalSuratTugas = SuratTugas::count();
        $stWithSpd = SuratTugas::whereHas('spd')->count();
        $stWithoutSpd = SuratTugas::whereDoesntHave('spd')->count();
        $stWithoutLaporan = SuratTugas::whereDoesntHave('laporanPerjalanan')->count();

        $laporanAiCount = LaporanPerjalanan::count();
        $hanyaLaporanCount = HanyaLaporan::count();
        $totalLaporan = $laporanAiCount + $hanyaLaporanCount;

        // Total DPR (Pengeluaran Riil)
        $dprSpd = (float) Spd::whereHas('suratTugas')->sum('nilai_dpr');
        $dprHanyaLaporan = (float) HanyaLaporan::where('ada_dpr', true)->sum('nilai_dpr');
        $totalDpr = $dprSpd + $dprHanyaLaporan;

        // Pegawai & Anggaran
        $totalPegawai = Pegawai::count();
        $pegawaiAktif = Pegawai::where('is_active', true)->count();
        $pegawaiNonAktif = Pegawai::where('is_active', false)->count();
        $totalAnggaran = Anggaran::count();

        // Recent Surat Tugas
        $recentSuratTugas = SuratTugas::with(['pegawai', 'spd', 'anggaran', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // Recent Hanya Laporan
        $recentHanyaLaporan = HanyaLaporan::with(['user'])
            ->latest()
            ->take(6)
            ->get();

        // Pending Actions
        $pendingSpdList = SuratTugas::with(['pegawai', 'anggaran'])
            ->whereDoesntHave('spd')
            ->latest()
            ->take(5)
            ->get();

        $pendingLaporanList = SuratTugas::with(['pegawai', 'spd'])
            ->whereDoesntHave('laporanPerjalanan')
            ->latest()
            ->take(5)
            ->get();

        // Top 5 Pegawai Teraktif (Dipadukan dari Laporan AI & Hanya Laporan)
        $pegawaiMap = [];

        // 1. Dari Laporan AI (LaporanPerjalanan -> SuratTugas -> Pegawai)
        $laporanAiList = LaporanPerjalanan::with('suratTugas.pegawai')->get();
        foreach ($laporanAiList as $lp) {
            $p = $lp->suratTugas->pegawai ?? null;
            if ($p) {
                $key = 'p_'.$p->id;
                if (! isset($pegawaiMap[$key])) {
                    $pegawaiMap[$key] = [
                        'pegawai' => $p,
                        'nama' => $p->nama,
                        'nip' => $p->nip,
                        'ai_count' => 0,
                        'hl_count' => 0,
                        'total_count' => 0,
                    ];
                }
                $pegawaiMap[$key]['ai_count']++;
                $pegawaiMap[$key]['total_count']++;
            }
        }

        // 2. Dari Hanya Laporan
        $hanyaLaporanList = HanyaLaporan::get();
        $allPegawais = Pegawai::all();
        $pegawaiByNip = $allPegawais->whereNotNull('nip')->keyBy('nip');
        $pegawaiByNama = $allPegawais->keyBy('nama');

        foreach ($hanyaLaporanList as $hl) {
            $p = null;
            if (! empty($hl->nip_pegawai) && isset($pegawaiByNip[$hl->nip_pegawai])) {
                $p = $pegawaiByNip[$hl->nip_pegawai];
            } elseif (! empty($hl->nama_pegawai) && isset($pegawaiByNama[$hl->nama_pegawai])) {
                $p = $pegawaiByNama[$hl->nama_pegawai];
            }

            if ($p) {
                $key = 'p_'.$p->id;
                if (! isset($pegawaiMap[$key])) {
                    $pegawaiMap[$key] = [
                        'pegawai' => $p,
                        'nama' => $p->nama,
                        'nip' => $p->nip,
                        'ai_count' => 0,
                        'hl_count' => 0,
                        'total_count' => 0,
                    ];
                }
                $pegawaiMap[$key]['hl_count']++;
                $pegawaiMap[$key]['total_count']++;
            } else {
                $name = trim($hl->nama_pegawai ?: 'Pegawai');
                $customKey = 'c_'.md5(strtolower($name));
                if (! isset($pegawaiMap[$customKey])) {
                    $pegawaiMap[$customKey] = [
                        'pegawai' => null,
                        'nama' => $name,
                        'nip' => $hl->nip_pegawai ?: '-',
                        'ai_count' => 0,
                        'hl_count' => 0,
                        'total_count' => 0,
                    ];
                }
                $pegawaiMap[$customKey]['hl_count']++;
                $pegawaiMap[$customKey]['total_count']++;
            }
        }

        usort($pegawaiMap, function ($a, $b) {
            return $b['total_count'] <=> $a['total_count'];
        });

        $topPegawai = collect(array_map(function ($item) {
            return (object) [
                'nama' => $item['nama'],
                'nip' => $item['nip'],
                'total_count' => $item['total_count'],
                'surat_tugas_count' => $item['total_count'],
                'ai_count' => $item['ai_count'],
                'hl_count' => $item['hl_count'],
                'pegawai' => $item['pegawai'],
            ];
        }, array_slice($pegawaiMap, 0, 5)));

        // Fallback jika belum ada laporan sama sekali namun sudah ada surat tugas
        if ($topPegawai->isEmpty()) {
            $fallbackPegawai = Pegawai::withCount('suratTugas')
                ->having('surat_tugas_count', '>', 0)
                ->orderByDesc('surat_tugas_count')
                ->take(5)
                ->get();

            if ($fallbackPegawai->isNotEmpty()) {
                $topPegawai = $fallbackPegawai->map(function ($p) {
                    return (object) [
                        'nama' => $p->nama,
                        'nip' => $p->nip,
                        'total_count' => $p->surat_tugas_count,
                        'surat_tugas_count' => $p->surat_tugas_count,
                        'ai_count' => 0,
                        'hl_count' => 0,
                        'pegawai' => $p,
                    ];
                });
            }
        }

        // Top 5 Lokasi Kunjungan Terbanyak (Instansi / Desa / Tempat dari Laporan AI & Hanya Laporan)
        $locationMap = [];
        $addLocation = function ($rawLocation, string $type) use (&$locationMap) {
            if (! $rawLocation) {
                return;
            }
            $trimmed = trim($rawLocation);
            if ($trimmed === '') {
                return;
            }

            $key = strtolower(preg_replace('/\s+/', ' ', $trimmed));
            $displayName = ucwords(strtolower($trimmed));

            if (! isset($locationMap[$key])) {
                $locationMap[$key] = [
                    'name' => $displayName,
                    'ai_count' => 0,
                    'hl_count' => 0,
                    'total_count' => 0,
                ];
            }

            if ($type === 'ai') {
                $locationMap[$key]['ai_count']++;
            } else {
                $locationMap[$key]['hl_count']++;
            }
            $locationMap[$key]['total_count']++;
        };

        foreach (LaporanPerjalanan::whereNotNull('lokasi_perjalanan')->pluck('lokasi_perjalanan') as $loc) {
            $addLocation($loc, 'ai');
        }

        foreach (HanyaLaporan::whereNotNull('lokasi_perjalanan')->pluck('lokasi_perjalanan') as $loc) {
            $addLocation($loc, 'hl');
        }

        usort($locationMap, function ($a, $b) {
            return $b['total_count'] <=> $a['total_count'];
        });

        $topLocations = collect(array_map(function ($item) {
            return (object) [
                'name' => $item['name'],
                'total_count' => $item['total_count'],
                'ai_count' => $item['ai_count'],
                'hl_count' => $item['hl_count'],
            ];
        }, array_slice($locationMap, 0, 5)));

        // Fallback jika belum ada lokasi laporan, ambil dari tujuan surat tugas
        if ($topLocations->isEmpty()) {
            foreach (SuratTugas::whereNotNull('tujuan')->pluck('tujuan') as $tujuan) {
                $addLocation($tujuan, 'ai');
            }
            usort($locationMap, function ($a, $b) {
                return $b['total_count'] <=> $a['total_count'];
            });
            $topLocations = collect(array_map(function ($item) {
                return (object) [
                    'name' => $item['name'],
                    'total_count' => $item['total_count'],
                    'ai_count' => $item['ai_count'],
                    'hl_count' => $item['hl_count'],
                ];
            }, array_slice($locationMap, 0, 5)));
        }

        // Top 5 Anggaran MAK
        $topAnggaran = Anggaran::withCount('suratTugas')
            ->having('surat_tugas_count', '>', 0)
            ->orderByDesc('surat_tugas_count')
            ->take(5)
            ->get();

        // Distribusi Kategori Perjalanan
        $categoriesMap = [];
        $kategoriHL = HanyaLaporan::whereNotNull('kategori')
            ->selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        $kategoriAI = LaporanPerjalanan::whereNotNull('kategori')
            ->selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        foreach ($kategoriHL as $k => $c) {
            $categoriesMap[$k] = ($categoriesMap[$k] ?? 0) + $c;
        }
        foreach ($kategoriAI as $k => $c) {
            $categoriesMap[$k] = ($categoriesMap[$k] ?? 0) + $c;
        }
        arsort($categoriesMap);
        $totalKategoriCount = array_sum($categoriesMap);
        $topCategories = array_slice($categoriesMap, 0, 5, true);

        // Tren Perjalanan Bulanan
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $monthlyCounts = array_fill_keys(array_values($monthNames), 0);

        $stDates = SuratTugas::whereNotNull('tgl_berangkat')->get(['tgl_berangkat']);
        foreach ($stDates as $st) {
            if ($st->tgl_berangkat) {
                $m = (int) $st->tgl_berangkat->format('n');
                if (isset($monthNames[$m])) {
                    $monthlyCounts[$monthNames[$m]]++;
                }
            }
        }

        $hlDates = HanyaLaporan::whereNotNull('tgl_perjalanan')->get(['tgl_perjalanan']);
        foreach ($hlDates as $hl) {
            if ($hl->tgl_perjalanan) {
                $m = (int) $hl->tgl_perjalanan->format('n');
                if (isset($monthNames[$m])) {
                    $monthlyCounts[$monthNames[$m]]++;
                }
            }
        }

        $maxMonthlyValue = max(array_values($monthlyCounts)) ?: 1;

        $stats = [
            'surat_tugas' => $totalSuratTugas,
            'st_with_spd' => $stWithSpd,
            'st_without_spd' => $stWithoutSpd,
            'st_without_laporan' => $stWithoutLaporan,
            'laporan' => $totalLaporan,
            'laporan_perjalanan' => $laporanAiCount,
            'hanya_laporan' => $hanyaLaporanCount,
            'total_dpr' => $totalDpr,
            'pegawai' => $totalPegawai,
            'pegawai_aktif' => $pegawaiAktif,
            'pegawai_non_aktif' => $pegawaiNonAktif,
            'anggaran' => $totalAnggaran,
        ];

        return view('dashboard', compact(
            'stats',
            'currentSatker',
            'currentTahun',
            'recentSuratTugas',
            'recentHanyaLaporan',
            'pendingSpdList',
            'pendingLaporanList',
            'topPegawai',
            'topLocations',
            'topAnggaran',
            'topCategories',
            'totalKategoriCount',
            'monthlyCounts',
            'maxMonthlyValue'
        ));
    }
}
