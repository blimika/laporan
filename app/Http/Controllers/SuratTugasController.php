<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Pegawai;
use App\Models\Spd;
use App\Models\SuratTugas;
use App\Models\User;
use App\Services\DocumentExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SuratTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $baseQuery = SuratTugas::query();

        $stats = [
            'total_st' => (clone $baseQuery)->count(),
            'st_with_spd' => (clone $baseQuery)->has('spd')->count(),
            'st_without_spd' => (clone $baseQuery)->doesntHave('spd')->count(),
            'st_with_laporan' => (clone $baseQuery)->has('laporanPerjalanan')->count(),
            'st_without_laporan' => (clone $baseQuery)->doesntHave('laporanPerjalanan')->count(),
            'total_dpr' => (float) Spd::whereHas('suratTugas')->sum('nilai_dpr'),
        ];

        $query = SuratTugas::with(['pegawai', 'kepalaPegawai', 'anggaran', 'spd.ppkPegawai', 'laporanPerjalanan', 'user']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('tugas', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('uraian_pembebanan', 'like', "%{$search}%")
                    ->orWhereHas('pegawai', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%")
                            ->orWhere('nip', 'like', "%{$search}%");
                    })
                    ->orWhereHas('spd', function ($qSpd) use ($search) {
                        $qSpd->where('nomor_spd', 'like', "%{$search}%");
                    })
                    ->orWhereHas('anggaran', function ($qAng) use ($search) {
                        $qAng->where('mak', 'like', "%{$search}%")
                            ->orWhere('akun_uraian', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status_spd')) {
            if ($request->status_spd === 'with_spd') {
                $query->has('spd');
            } elseif ($request->status_spd === 'without_spd') {
                $query->doesntHave('spd');
            }
        }

        if ($request->filled('status_laporan')) {
            if ($request->status_laporan === 'with_laporan') {
                $query->has('laporanPerjalanan');
            } elseif ($request->status_laporan === 'without_laporan') {
                $query->doesntHave('laporanPerjalanan');
            }
        }

        if ($request->filled('anggaran_id')) {
            $query->where('pembebanan_anggaran_id', $request->anggaran_id);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tgl_berangkat', $request->bulan);
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = ['nomor_surat', 'tujuan', 'tgl_berangkat', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [5, 10, 20, 50, 100])) {
            $perPage = 10;
        }

        $suratTugasList = $query->paginate($perPage)->withQueryString();
        $anggaranList = Anggaran::orderBy('mak')->get();

        return view('surat_tugas.index', compact('suratTugasList', 'sort', 'direction', 'stats', 'anggaranList'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, SuratTugas $suratTuga)
    {
        $suratTugas = $suratTuga->load(['pegawai', 'kepalaPegawai', 'anggaran', 'spd.ppkPegawai', 'laporanPerjalanan', 'user']);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $suratTugas,
            ]);
        }

        return redirect()->route('surat-tugas.index');
    }

    public function create()
    {
        $pegawais = Pegawai::active()->get();
        $kepalaPegawais = Pegawai::active()->kepala()->get();
        $ppkPegawais = Pegawai::active()->ppk()->get();
        $anggarans = Anggaran::all();

        return view('surat_tugas.create', compact('pegawais', 'kepalaPegawais', 'ppkPegawais', 'anggarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|unique:surat_tugas,nomor_surat',
            'pegawai_id' => 'required|exists:pegawais,id',
            'kepala_pegawai_id' => 'required|exists:pegawais,id',
            'pembebanan_anggaran_id' => 'required|exists:anggarans,id',
            'uraian_pembebanan' => 'required|string',
            'spd_template' => 'nullable|in:template_1,template_2',
            'tujuan' => 'required|string',
            'tugas' => 'required|string',
            'tgl_surat' => 'required|date',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
            'nilai_dpr' => 'nullable',
            'ppk_pegawai_id' => 'nullable|exists:pegawais,id',
            'kendaraan' => 'nullable|string',
        ]);

        if (empty($validated['spd_template'])) {
            $validated['spd_template'] = 'template_2';
        }

        $stData = Arr::except($validated, ['nilai_dpr', 'ppk_pegawai_id', 'kendaraan']);
        $suratTugas = SuratTugas::create($stData);

        if ($suratTugas->spd_template === 'template_2') {
            $ppkId = $request->ppk_pegawai_id
                ?: (Pegawai::active()->ppk()->where('satker_id', $suratTugas->satker_id)->value('id')
                    ?? Pegawai::active()->ppk()->value('id')
                    ?? $suratTugas->kepala_pegawai_id);

            $safeNomor = 'T2/'.str_replace(['/', '\\', ' '], '-', $suratTugas->nomor_surat);
            if (Spd::where('nomor_spd', $safeNomor)->exists()) {
                $safeNomor .= '-'.$suratTugas->id;
            }

            $nilaiDpr = null;
            if ($request->filled('nilai_dpr')) {
                $cleanVal = str_replace(['.', ','], ['', '.'], (string) $request->nilai_dpr);
                $nilaiDpr = is_numeric($cleanVal) ? (float) $cleanVal : null;
            }

            $suratTugas->spd()->create([
                'nomor_spd' => $safeNomor,
                'ppk_pegawai_id' => $ppkId,
                'kendaraan' => $request->kendaraan ?: 'Kendaraan Umum',
                'tgl_dpr' => $suratTugas->tgl_kembali,
                'nilai_dpr' => $nilaiDpr,
            ]);
        }

        return redirect()->route('surat-tugas.index')->with('success', 'Surat Tugas berhasil dibuat.');
    }

    public function edit(SuratTugas $suratTuga)
    {
        $pegawais = Pegawai::active()->get();
        if ($suratTuga->pegawai && ! $pegawais->contains('id', $suratTuga->pegawai_id)) {
            $pegawais->push($suratTuga->pegawai);
        }
        $kepalaPegawais = Pegawai::active()->kepala()->get();
        if ($suratTuga->kepalaPegawai && ! $kepalaPegawais->contains('id', $suratTuga->kepala_pegawai_id)) {
            $kepalaPegawais->push($suratTuga->kepalaPegawai);
        }
        $ppkPegawais = Pegawai::active()->ppk()->get();
        if ($suratTuga->spd && $suratTuga->spd->ppkPegawai && ! $ppkPegawais->contains('id', $suratTuga->spd->ppk_pegawai_id)) {
            $ppkPegawais->push($suratTuga->spd->ppkPegawai);
        }
        $anggarans = Anggaran::all();
        $suratTugas = $suratTuga; // Alias the variable to match our standard naming

        $users = collect();
        if (auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->role === 'super')) {
            $users = User::all();
        } elseif (auth()->check() && auth()->user()->role === 'admin') {
            $users = User::where('satker_id', auth()->user()->satker_id)->get();
        }

        return view('surat_tugas.edit', compact('suratTugas', 'pegawais', 'kepalaPegawais', 'ppkPegawais', 'anggarans', 'users'));
    }

    public function update(Request $request, SuratTugas $suratTuga)
    {
        $suratTugas = $suratTuga;

        $validated = $request->validate([
            'nomor_surat' => 'required|unique:surat_tugas,nomor_surat,'.$suratTugas->id,
            'pegawai_id' => 'required|exists:pegawais,id',
            'kepala_pegawai_id' => 'required|exists:pegawais,id',
            'pembebanan_anggaran_id' => 'required|exists:anggarans,id',
            'uraian_pembebanan' => 'required|string',
            'spd_template' => 'nullable|in:template_1,template_2',
            'tujuan' => 'required|string',
            'tugas' => 'required|string',
            'tgl_surat' => 'required|date',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
            'user_id' => 'nullable|exists:users,id',
            'nilai_dpr' => 'nullable',
            'ppk_pegawai_id' => 'nullable|exists:pegawais,id',
            'kendaraan' => 'nullable|string',
        ]);

        if (empty($validated['spd_template'])) {
            $validated['spd_template'] = $suratTugas->spd_template ?? 'template_2';
        }

        $stData = Arr::except($validated, ['nilai_dpr', 'ppk_pegawai_id', 'kendaraan']);
        $suratTugas->update($stData);

        if ($suratTugas->spd_template === 'template_2') {
            $nilaiDpr = null;
            if ($request->filled('nilai_dpr')) {
                $cleanVal = str_replace(['.', ','], ['', '.'], (string) $request->nilai_dpr);
                $nilaiDpr = is_numeric($cleanVal) ? (float) $cleanVal : null;
            }

            $ppkId = $request->ppk_pegawai_id
                ?: ($suratTugas->spd ? $suratTugas->spd->ppk_pegawai_id : null)
                ?: (Pegawai::active()->ppk()->where('satker_id', $suratTugas->satker_id)->value('id')
                    ?? Pegawai::active()->ppk()->value('id')
                    ?? $suratTugas->kepala_pegawai_id);

            if ($suratTugas->spd) {
                $suratTugas->spd->update([
                    'ppk_pegawai_id' => $ppkId,
                    'tgl_dpr' => $suratTugas->tgl_kembali,
                    'nilai_dpr' => $nilaiDpr,
                    'kendaraan' => $request->kendaraan ?: ($suratTugas->spd->kendaraan ?: 'Kendaraan Umum'),
                ]);
            } else {
                $safeNomor = 'T2/'.str_replace(['/', '\\', ' '], '-', $suratTugas->nomor_surat);
                if (Spd::where('nomor_spd', $safeNomor)->exists()) {
                    $safeNomor .= '-'.$suratTugas->id;
                }

                $suratTugas->spd()->create([
                    'nomor_spd' => $safeNomor,
                    'ppk_pegawai_id' => $ppkId,
                    'kendaraan' => $request->kendaraan ?: 'Kendaraan Umum',
                    'tgl_dpr' => $suratTugas->tgl_kembali,
                    'nilai_dpr' => $nilaiDpr,
                ]);
            }
        }

        return redirect()->route('surat-tugas.index')->with('success', 'Surat Tugas berhasil diperbarui.');
    }

    public function exportPdf(Request $request, SuratTugas $suratTugas, DocumentExportService $exportService)
    {
        $template = $request->query('template', $suratTugas->spd_template ?? 'template_2');

        return $exportService->exportSuratTugasGabungan($suratTugas, $template);
    }

    public function destroy(SuratTugas $suratTuga)
    {
        // Because of foreign keys, we might need to delete SPD and Laporan explicitly if cascade is not set,
        // but Laravel eloquent delete() will just delete it, and DB cascade will handle the rest if set up.
        // Let's explicitly delete related to be safe
        if ($suratTuga->laporanPerjalanan) {
            foreach ($suratTuga->laporanPerjalanan->dokumentasi as $dok) {
                if (file_exists(public_path($dok->file_path))) {
                    unlink(public_path($dok->file_path));
                }
            }
            $suratTuga->laporanPerjalanan->delete();
        }
        if ($suratTuga->spd) {
            $suratTuga->spd->delete();
        }

        $suratTuga->delete();

        return redirect()->route('surat-tugas.index')->with('success', 'Surat Tugas dan semua data terkait berhasil dihapus.');
    }
}
