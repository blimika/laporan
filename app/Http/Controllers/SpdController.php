<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpdController extends Controller
{
    public function create(\App\Models\SuratTugas $suratTugas)
    {
        $pegawais = \App\Models\Pegawai::all();
        return view('spd.create', compact('suratTugas', 'pegawais'));
    }

    public function store(Request $request, \App\Models\SuratTugas $suratTugas)
    {
        $validated = $request->validate([
            'nomor_spd' => 'required|unique:spds,nomor_spd',
            'ppk_pegawai_id' => 'required|exists:pegawais,id',
            'kendaraan' => 'required|string',
            'tgl_dpr' => 'nullable|date',
            'nilai_dpr' => 'nullable|numeric',
        ]);

        $suratTugas->spd()->create($validated);

        return redirect()->route('surat-tugas.index')->with('success', 'SPD berhasil dibuat.');
    }

    public function exportPdf(\App\Models\Spd $spd, \App\Services\DocumentExportService $exportService)
    {
        return $exportService->exportSpd($spd);
    }

    public function exportDpr(\App\Models\Spd $spd, \App\Services\DocumentExportService $exportService)
    {
        return $exportService->exportDpr($spd);
    }

    public function exportPernyataan(\App\Models\Spd $spd, \App\Services\DocumentExportService $exportService)
    {
        return $exportService->exportPernyataan($spd);
    }
}
