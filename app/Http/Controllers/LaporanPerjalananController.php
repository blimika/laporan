<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanPerjalananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\LaporanPerjalanan::with(['suratTugas.pegawai', 'dokumentasi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('suratTugas', function($q) use ($search) {
                      $q->where('nomor_surat', 'like', "%{$search}%")
                        ->orWhere('tugas', 'like', "%{$search}%");
                  })->orWhere('kategori', 'like', "%{$search}%");
        }

        $perPage = $request->input('per_page', 10);
        $laporans = $query->latest()->paginate($perPage)->withQueryString();
        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        // Get Surat Tugas that don't have Laporan yet
        $suratTugas = \App\Models\SuratTugas::whereDoesntHave('laporanPerjalanan')->with('pegawai')->get();
        return view('laporan.create', compact('suratTugas'));
    }

    public function generate(Request $request, \App\Services\AiReportGeneratorService $aiService)
    {
        $data = $request->validate([
            'surat_tugas_id' => 'required|exists:surat_tugas,id',
            'tgl_laporan' => 'required|date',
            'tgl_perjalanan' => 'required|date',
            'lokasi_perjalanan' => 'required|string',
            'tujuan_perjalanan' => 'required|string',
            'pegawai_ditemui' => 'required|string',
            'kategori' => 'required|string',
            'kendala_ditemui' => 'nullable|string',
        ]);

        // Call AI
        $hasil_ai = $aiService->generate($data);

        return response()->json(['hasil' => $hasil_ai]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_tugas_id' => 'required|exists:surat_tugas,id',
            'tgl_laporan' => 'required|date',
            'tgl_perjalanan' => 'required|date',
            'lokasi_perjalanan' => 'required|string',
            'tujuan_perjalanan' => 'required|string',
            'pegawai_ditemui' => 'required|string',
            'kategori' => 'required|string',
            'kendala_ditemui' => 'nullable|string',
            'hasil_perjalanan' => 'required|string',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        $laporan = \App\Models\LaporanPerjalanan::create([
            'surat_tugas_id' => $validated['surat_tugas_id'],
            'tgl_laporan' => $validated['tgl_laporan'],
            'tgl_perjalanan' => $validated['tgl_perjalanan'],
            'lokasi_perjalanan' => $validated['lokasi_perjalanan'],
            'tujuan_perjalanan' => $validated['tujuan_perjalanan'],
            'pegawai_ditemui' => $validated['pegawai_ditemui'],
            'kategori' => $validated['kategori'],
            'kendala_ditemui' => $validated['kendala_ditemui'],
            'hasil_perjalanan' => $validated['hasil_perjalanan'],
        ]);

        // Handle foto uploads
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                // Simpan langsung ke folder public/uploads
                $foto->move(public_path('uploads/dokumentasi'), $filename);
                
                $laporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/' . $filename,
                    'keterangan_foto' => 'Dokumentasi ' . $validated['kategori'],
                ]);
            }
        }

        return redirect()->route('laporan.index')->with('success', 'Laporan beserta dokumentasi berhasil disimpan.');
    }

    public function edit(\App\Models\LaporanPerjalanan $laporan)
    {
        $suratTugas = \App\Models\SuratTugas::where('id', $laporan->surat_tugas_id)->with('pegawai')->get();
        return view('laporan.edit', compact('laporan', 'suratTugas'));
    }

    public function update(Request $request, \App\Models\LaporanPerjalanan $laporan)
    {
        $validated = $request->validate([
            'surat_tugas_id' => 'required|exists:surat_tugas,id',
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

        $laporan->update([
            'tgl_laporan' => $validated['tgl_laporan'],
            'tgl_perjalanan' => $validated['tgl_perjalanan'],
            'lokasi_perjalanan' => $validated['lokasi_perjalanan'],
            'tujuan_perjalanan' => $validated['tujuan_perjalanan'],
            'pegawai_ditemui' => $validated['pegawai_ditemui'],
            'kategori' => $validated['kategori'],
            'kendala_ditemui' => $validated['kendala_ditemui'],
            'hasil_perjalanan' => $validated['hasil_perjalanan'],
        ]);

        // Handle foto uploads
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

        // Handle foto deletion
        if ($request->has('delete_fotos')) {
            foreach ($request->delete_fotos as $dok_id) {
                $dok = \App\Models\LaporanDokumentasi::find($dok_id);
                if ($dok && $dok->laporan_id == $laporan->id) {
                    if (file_exists(public_path($dok->file_path))) {
                        unlink(public_path($dok->file_path));
                    }
                    $dok->delete();
                }
            }
        }

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function exportPdf(\App\Models\LaporanPerjalanan $laporan, \App\Services\DocumentExportService $exportService)
    {
        return $exportService->exportLaporan($laporan);
    }

    public function destroy(\App\Models\LaporanPerjalanan $laporan)
    {
        // Hapus file dokumentasi
        foreach ($laporan->dokumentasi as $dok) {
            if (file_exists(public_path($dok->file_path))) {
                unlink(public_path($dok->file_path));
            }
        }
        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan perjalanan berhasil dihapus.');
    }
}
