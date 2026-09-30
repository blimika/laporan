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

    public function edit(\App\Models\Spd $spd)
    {
        $pegawais = \App\Models\Pegawai::all();
        $suratTugas = $spd->suratTugas;
        return view('spd.edit', compact('spd', 'suratTugas', 'pegawais'));
    }

    public function update(Request $request, \App\Models\Spd $spd)
    {
        $validated = $request->validate([
            'nomor_spd' => 'required|unique:spds,nomor_spd,'.$spd->id,
            'ppk_pegawai_id' => 'required|exists:pegawais,id',
            'kendaraan' => 'required|string',
            'tgl_dpr' => 'nullable|date',
            'nilai_dpr' => 'nullable|numeric',
        ]);

        $spd->update($validated);

        return redirect()->route('surat-tugas.index')->with('success', 'SPD berhasil diperbarui.');
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
