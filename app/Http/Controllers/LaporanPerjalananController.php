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
        $query = \App\Models\LaporanPerjalanan::with(['suratTugas.pegawai', 'dokumentasi', 'user'])
                    ->select('laporan_perjalanans.*')
                    ->leftJoin('surat_tugas', 'laporan_perjalanans.surat_tugas_id', '=', 'surat_tugas.id')
                    ->leftJoin('pegawais', 'surat_tugas.pegawai_id', '=', 'pegawais.id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('surat_tugas.nomor_surat', 'like', "%{$search}%")
                  ->orWhere('surat_tugas.tugas', 'like', "%{$search}%")
                  ->orWhere('surat_tugas.tujuan', 'like', "%{$search}%")
                  ->orWhere('laporan_perjalanans.kategori', 'like', "%{$search}%")
                  ->orWhere('pegawais.nama', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'laporan_perjalanans.created_at');
        $direction = $request->input('direction', 'desc');
        
        $allowedSorts = [
            'tgl_laporan' => 'laporan_perjalanans.tgl_laporan',
            'lokasi_perjalanan' => 'laporan_perjalanans.lokasi_perjalanan',
            'kategori' => 'laporan_perjalanans.kategori',
            'nomor_surat' => 'surat_tugas.nomor_surat',
            'nama_pegawai' => 'pegawais.nama'
        ];

        if (array_key_exists($sort, $allowedSorts)) {
            $query->orderBy($allowedSorts[$sort], $direction);
        } else {
            $query->latest('laporan_perjalanans.created_at');
        }

        $perPage = $request->input('per_page', 10);
        $laporans = $query->paginate($perPage)->withQueryString();
        return view('laporan.index', compact('laporans', 'sort', 'direction'));
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
            'kategori_custom' => 'nullable|string',
            'kendala_ditemui' => 'nullable|string',
            'is_8_jam' => 'nullable|boolean',
        ]);

        if ($data['kategori'] === 'Custom' && !empty($data['kategori_custom'])) {
            $data['kategori'] = $data['kategori_custom'];
        }

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
            'check_stamp' => 'nullable',
            'stamp_koordinat' => 'nullable|required_with:check_stamp|string',
            'stamp_datetime' => 'nullable|required_with:check_stamp|string',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        $kategori = $validated['kategori'];
        if ($kategori === 'Custom' && $request->filled('kategori_custom')) {
            $kategori = $request->kategori_custom;
        }

        $laporan = \App\Models\LaporanPerjalanan::create([
            'surat_tugas_id' => $validated['surat_tugas_id'],
            'tgl_laporan' => $validated['tgl_laporan'],
            'tgl_perjalanan' => $validated['tgl_perjalanan'],
            'lokasi_perjalanan' => $validated['lokasi_perjalanan'],
            'tujuan_perjalanan' => $validated['tujuan_perjalanan'],
            'pegawai_ditemui' => $validated['pegawai_ditemui'],
            'kategori' => $kategori,
            'kendala_ditemui' => $validated['kendala_ditemui'],
            'hasil_perjalanan' => $validated['hasil_perjalanan'],
            'is_stamped' => $request->has('check_stamp'),
            'stamp_koordinat' => $validated['stamp_koordinat'] ?? null,
            'stamp_datetime' => $validated['stamp_datetime'] ?? null,
        ]);

        // Handle foto uploads
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                // Simpan langsung ke folder public/uploads
                $foto->move(public_path('uploads/dokumentasi'), $filename);
                
                $laporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/' . $filename,
                    'keterangan_foto' => 'Dokumentasi ' . $kategori,
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
            'check_stamp' => 'nullable',
            'stamp_koordinat' => 'nullable|required_with:check_stamp|string',
            'stamp_datetime' => 'nullable|required_with:check_stamp|string',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $kategori = $validated['kategori'];
        if ($kategori === 'Custom' && $request->filled('kategori_custom')) {
            $kategori = $request->kategori_custom;
        }

        $laporan->update([
            'tgl_laporan' => $validated['tgl_laporan'],
            'tgl_perjalanan' => $validated['tgl_perjalanan'],
            'lokasi_perjalanan' => $validated['lokasi_perjalanan'],
            'tujuan_perjalanan' => $validated['tujuan_perjalanan'],
            'pegawai_ditemui' => $validated['pegawai_ditemui'],
            'kategori' => $kategori,
            'kendala_ditemui' => $validated['kendala_ditemui'],
            'hasil_perjalanan' => $validated['hasil_perjalanan'],
            'is_stamped' => $request->has('check_stamp'),
            'stamp_koordinat' => $validated['stamp_koordinat'] ?? null,
            'stamp_datetime' => $validated['stamp_datetime'] ?? null,
        ]);

        // Handle foto uploads
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $filename);
                
                $laporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/' . $filename,
                    'keterangan_foto' => 'Dokumentasi ' . $kategori,
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

    public function exportWord(\App\Models\LaporanPerjalanan $laporan, \App\Services\DocumentExportService $exportService)
    {
        return $exportService->exportLaporanWord($laporan);
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
