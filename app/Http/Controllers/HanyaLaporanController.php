<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HanyaLaporan;
use App\Models\HanyaLaporanDokumentasi;
use Barryvdh\DomPDF\Facade\Pdf;

class HanyaLaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = HanyaLaporan::with('dokumentasi');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nomor_st', 'like', "%{$search}%")
                  ->orWhere('tujuan_perjalanan', 'like', "%{$search}%")
                  ->orWhere('nama_pegawai', 'like', "%{$search}%");
        }

        $perPage = $request->input('per_page', 10);
        $laporans = $query->latest()->paginate($perPage)->withQueryString();
        return view('hanya_laporan.index', compact('laporans'));
    }

    public function create()
    {
        return view('hanya_laporan.create');
    }

    public function generate(Request $request, \App\Services\AiReportGeneratorService $aiService)
    {
        $data = $request->validate([
            'tujuan_perjalanan' => 'required|string',
            'lokasi_perjalanan' => 'required|string',
            'pegawai_ditemui' => 'required|string',
            'kategori' => 'required|string',
            'kendala_ditemui' => 'nullable|string',
            'is_8_jam' => 'nullable|boolean',
        ]);

        $hasil_ai = $aiService->generate($data);

        return response()->json(['hasil' => $hasil_ai]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string',
            'nip_pegawai' => 'required|string',
            'nomor_st' => 'required|string',
            'nomor_spd' => 'nullable|string',
            'tgl_laporan' => 'required|date',
            'tgl_perjalanan' => 'required|date',
            'lokasi_perjalanan' => 'required|string',
            'tujuan_perjalanan' => 'required|string',
            'pegawai_ditemui' => 'required|string',
            'kategori' => 'required|string',
            'kendala_ditemui' => 'nullable|string',
            'hasil_perjalanan' => 'required|string',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $laporan = HanyaLaporan::create($request->except(['_token', 'fotos']));

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $filename);
                
                $laporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/' . $filename,
                    'keterangan_foto' => 'Dokumentasi ' . $validated['kategori'],
                ]);
            }
        }

        return redirect()->route('hanya-laporan.index')->with('success', 'Laporan berhasil disimpan.');
    }

    public function edit(HanyaLaporan $hanyaLaporan)
    {
        return view('hanya_laporan.edit', compact('hanyaLaporan'));
    }

    public function update(Request $request, HanyaLaporan $hanyaLaporan)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string',
            'nip_pegawai' => 'required|string',
            'nomor_st' => 'required|string',
            'nomor_spd' => 'nullable|string',
            'tgl_laporan' => 'required|date',
            'tgl_perjalanan' => 'required|date',
            'lokasi_perjalanan' => 'required|string',
            'tujuan_perjalanan' => 'required|string',
            'pegawai_ditemui' => 'required|string',
            'kategori' => 'required|string',
            'kendala_ditemui' => 'nullable|string',
            'hasil_perjalanan' => 'required|string',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $hanyaLaporan->update($request->except(['_token', '_method', 'fotos', 'delete_fotos']));

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $filename);
                
                $hanyaLaporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/' . $filename,
                    'keterangan_foto' => 'Dokumentasi ' . $validated['kategori'],
                ]);
            }
        }

        if ($request->has('delete_fotos')) {
            foreach ($request->delete_fotos as $dok_id) {
                $dok = HanyaLaporanDokumentasi::find($dok_id);
                if ($dok && $dok->hanya_laporan_id == $hanyaLaporan->id) {
                    if (file_exists(public_path($dok->file_path))) {
                        unlink(public_path($dok->file_path));
                    }
                    $dok->delete();
                }
            }
        }

        return redirect()->route('hanya-laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function exportPdf(HanyaLaporan $hanyaLaporan)
    {
        $pdf = Pdf::loadView('exports.pdf.hanya_laporan', ['laporan' => $hanyaLaporan]);
        return $pdf->stream('Hanya_Laporan_'.$hanyaLaporan->id.'.pdf');
    }

    public function destroy(HanyaLaporan $hanyaLaporan)
    {
        foreach ($hanyaLaporan->dokumentasi as $dok) {
            if (file_exists(public_path($dok->file_path))) {
                unlink(public_path($dok->file_path));
            }
        }
        $hanyaLaporan->delete();
        return redirect()->route('hanya-laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
