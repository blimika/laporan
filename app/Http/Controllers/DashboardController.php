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

        // Top 5 Pegawai Paling Sering Melaksanakan Tugas
        $topPegawai = Pegawai::withCount('suratTugas')
            ->having('surat_tugas_count', '>', 0)
            ->orderByDesc('surat_tugas_count')
            ->take(5)
            ->get();

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
            'topAnggaran',
            'topCategories',
            'totalKategoriCount',
            'monthlyCounts',
            'maxMonthlyValue'
        ));
    }
}
