<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Spd;
use App\Models\SuratTugas;
use App\Services\DocumentExportService;
use Illuminate\Http\Request;

class SpdController extends Controller
{
    public function create(SuratTugas $suratTugas)
    {
        $pegawais = Pegawai::active()->get();
        $ppkPegawais = Pegawai::active()->ppk()->get();

        return view('spd.create', compact('suratTugas', 'pegawais', 'ppkPegawais'));
    }

    public function store(Request $request, SuratTugas $suratTugas)
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

    public function edit(Spd $spd)
    {
        $pegawais = Pegawai::active()->get();
        $ppkPegawais = Pegawai::active()->ppk()->get();
        if ($spd->ppkPegawai && ! $ppkPegawais->contains('id', $spd->ppk_pegawai_id)) {
            $ppkPegawais->push($spd->ppkPegawai);
        }
        $suratTugas = $spd->suratTugas;

        return view('spd.edit', compact('spd', 'suratTugas', 'pegawais', 'ppkPegawais'));
    }

    public function update(Request $request, Spd $spd)
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

    public function exportPdf(Spd $spd, DocumentExportService $exportService)
    {
        return $exportService->exportSpd($spd);
    }

    public function exportDpr(Spd $spd, DocumentExportService $exportService)
    {
        return $exportService->exportDpr($spd);
    }

    public function exportPernyataan(Spd $spd, DocumentExportService $exportService)
    {
        return $exportService->exportPernyataan($spd);
    }
}
