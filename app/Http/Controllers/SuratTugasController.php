<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuratTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\SuratTugas::with(['pegawai', 'anggaran', 'spd', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('tugas', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('pegawai', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        
        $allowedSorts = ['nomor_surat', 'tujuan', 'tgl_berangkat'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $perPage = $request->input('per_page', 10);
        $suratTugasList = $query->paginate($perPage)->withQueryString();
        return view('surat_tugas.index', compact('suratTugasList', 'sort', 'direction'));
    }

    public function create()
    {
        $pegawais = \App\Models\Pegawai::all();
        $anggarans = \App\Models\Anggaran::all();
        return view('surat_tugas.create', compact('pegawais', 'anggarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|unique:surat_tugas,nomor_surat',
            'pegawai_id' => 'required|exists:pegawais,id',
            'kepala_pegawai_id' => 'required|exists:pegawais,id',
            'pembebanan_anggaran_id' => 'required|exists:anggarans,id',
            'uraian_pembebanan' => 'required|string',
            'tujuan' => 'required|string',
            'tugas' => 'required|string',
            'tgl_surat' => 'required|date',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
        ]);

        \App\Models\SuratTugas::create($validated);

        return redirect()->route('surat-tugas.index')->with('success', 'Surat Tugas berhasil dibuat.');
    }

    public function edit(\App\Models\SuratTugas $suratTuga)
    {
        $pegawais = \App\Models\Pegawai::all();
        $anggarans = \App\Models\Anggaran::all();
        $suratTugas = $suratTuga; // Alias the variable to match our standard naming
        
        $users = collect();
        if (auth()->user()->role === 'super') {
            $users = \App\Models\User::all();
        } elseif (auth()->user()->role === 'admin') {
            $users = \App\Models\User::where('satker_id', auth()->user()->satker_id)->get();
        }
        
        return view('surat_tugas.edit', compact('suratTugas', 'pegawais', 'anggarans', 'users'));
    }

    public function update(Request $request, \App\Models\SuratTugas $suratTuga)
    {
        $suratTugas = $suratTuga;
        
        $validated = $request->validate([
            'nomor_surat' => 'required|unique:surat_tugas,nomor_surat,' . $suratTugas->id,
            'pegawai_id' => 'required|exists:pegawais,id',
            'kepala_pegawai_id' => 'required|exists:pegawais,id',
            'pembebanan_anggaran_id' => 'required|exists:anggarans,id',
            'uraian_pembebanan' => 'required|string',
            'tujuan' => 'required|string',
            'tugas' => 'required|string',
            'tgl_surat' => 'required|date',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $suratTugas->update($validated);

        return redirect()->route('surat-tugas.index')->with('success', 'Surat Tugas berhasil diperbarui.');
    }

    public function exportPdf(\App\Models\SuratTugas $suratTugas, \App\Services\DocumentExportService $exportService)
    {
        return $exportService->exportSuratTugas($suratTugas);
    }

    public function destroy(\App\Models\SuratTugas $suratTuga)
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
