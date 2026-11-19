<?php

namespace App\Http\Controllers;

use App\Models\LaporanDokumentasi;
use App\Models\LaporanPerjalanan;
use App\Models\SuratTugas;
use App\Services\AiReportGeneratorService;
use App\Services\DocumentExportService;
use Illuminate\Http\Request;

class LaporanPerjalananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $baseQuery = LaporanPerjalanan::query();

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'with_foto' => (clone $baseQuery)->has('dokumentasi')->count(),
            'without_foto' => (clone $baseQuery)->doesntHave('dokumentasi')->count(),
            'with_geotag' => (clone $baseQuery)->whereNotNull('stamp_koordinat')->where('stamp_koordinat', '!=', '')->count(),
            'top_kategori' => (clone $baseQuery)->selectRaw('kategori, count(*) as count')
                ->groupBy('kategori')
                ->orderByDesc('count')
                ->first(),
        ];

        $kategoriList = LaporanPerjalanan::selectRaw('kategori, count(*) as count')
            ->groupBy('kategori')
            ->orderByDesc('count')
            ->pluck('kategori');

        $query = LaporanPerjalanan::with(['suratTugas.pegawai', 'suratTugas.spd', 'dokumentasi', 'user'])
            ->select('laporan_perjalanans.*')
            ->leftJoin('surat_tugas', 'laporan_perjalanans.surat_tugas_id', '=', 'surat_tugas.id')
            ->leftJoin('pegawais', 'surat_tugas.pegawai_id', '=', 'pegawais.id')
            ->leftJoin('spds', 'surat_tugas.id', '=', 'spds.surat_tugas_id');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('surat_tugas.nomor_surat', 'like', "%{$search}%")
                    ->orWhere('spds.nomor_spd', 'like', "%{$search}%")
                    ->orWhere('surat_tugas.tugas', 'like', "%{$search}%")
                    ->orWhere('surat_tugas.tujuan', 'like', "%{$search}%")
                    ->orWhere('laporan_perjalanans.lokasi_perjalanan', 'like', "%{$search}%")
                    ->orWhere('laporan_perjalanans.tujuan_perjalanan', 'like', "%{$search}%")
                    ->orWhere('laporan_perjalanans.kategori', 'like', "%{$search}%")
                    ->orWhere('laporan_perjalanans.pegawai_ditemui', 'like', "%{$search}%")
                    ->orWhere('laporan_perjalanans.kendala_ditemui', 'like', "%{$search}%")
                    ->orWhere('pegawais.nama', 'like', "%{$search}%")
                    ->orWhere('pegawais.nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('laporan_perjalanans.kategori', $request->kategori);
        }

        if ($request->filled('has_foto')) {
            if ($request->has_foto === 'with_foto') {
                $query->has('dokumentasi');
            } elseif ($request->has_foto === 'without_foto') {
                $query->doesntHave('dokumentasi');
            }
        }

        if ($request->filled('has_geotag')) {
            if ($request->has_geotag === 'with_geotag') {
                $query->whereNotNull('laporan_perjalanans.stamp_koordinat')
                    ->where('laporan_perjalanans.stamp_koordinat', '!=', '');
            } elseif ($request->has_geotag === 'without_geotag') {
                $query->where(function ($q) {
                    $q->whereNull('laporan_perjalanans.stamp_koordinat')
                        ->orWhere('laporan_perjalanans.stamp_koordinat', '');
                });
            }
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('laporan_perjalanans.tgl_perjalanan', $request->bulan);
        }

        $sort = $request->input('sort', 'laporan_perjalanans.created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = [
            'tgl_laporan' => 'laporan_perjalanans.tgl_laporan',
            'tgl_perjalanan' => 'laporan_perjalanans.tgl_perjalanan',
            'lokasi_perjalanan' => 'laporan_perjalanans.lokasi_perjalanan',
            'tujuan_perjalanan' => 'laporan_perjalanans.tujuan_perjalanan',
            'kategori' => 'laporan_perjalanans.kategori',
            'nomor_surat' => 'surat_tugas.nomor_surat',
            'nomor_st' => 'surat_tugas.nomor_surat',
            'nomor_spd' => 'spds.nomor_spd',
            'nama_pegawai' => 'pegawais.nama',
            'created_at' => 'laporan_perjalanans.created_at',
        ];

        if (array_key_exists($sort, $allowedSorts)) {
            $query->orderBy($allowedSorts[$sort], $direction);
        } else {
            $query->latest('laporan_perjalanans.created_at');
        }

        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [5, 10, 20, 50, 100])) {
            $perPage = 10;
        }

        $laporans = $query->paginate($perPage)->withQueryString();

        return view('laporan.index', compact('laporans', 'sort', 'direction', 'stats', 'kategoriList'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, LaporanPerjalanan $laporan)
    {
        $laporan->load(['suratTugas.pegawai', 'suratTugas.spd', 'dokumentasi', 'user']);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $laporan,
            ]);
        }

        return redirect()->route('laporan.index');
    }

    public function create()
    {
        // Get Surat Tugas that don't have Laporan yet
        $suratTugas = SuratTugas::whereDoesntHave('laporanPerjalanan')->with(['pegawai', 'anggaran'])->get();

        return view('laporan.create', compact('suratTugas'));
    }

    public function generate(Request $request, AiReportGeneratorService $aiService)
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
            'panjang_narasi' => 'nullable|string',
        ]);

        if ($data['kategori'] === 'Custom' && ! empty($data['kategori_custom'])) {
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
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        ]);

        $kategori = $validated['kategori'];
        if ($kategori === 'Custom' && $request->filled('kategori_custom')) {
            $kategori = $request->kategori_custom;
        }

        $laporan = LaporanPerjalanan::create([
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
                $filename = time().'_'.uniqid().'.'.$foto->getClientOriginalExtension();
                // Simpan langsung ke folder public/uploads
                $foto->move(public_path('uploads/dokumentasi'), $filename);

                $laporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/'.$filename,
                    'keterangan_foto' => 'Dokumentasi '.$kategori,
                ]);
            }
        }

        return redirect()->route('laporan.index')->with('success', 'Laporan beserta dokumentasi berhasil disimpan.');
    }

    public function edit(LaporanPerjalanan $laporan)
    {
        $suratTugas = SuratTugas::where('id', $laporan->surat_tugas_id)->with('pegawai')->get();

        return view('laporan.edit', compact('laporan', 'suratTugas'));
    }

    public function update(Request $request, LaporanPerjalanan $laporan)
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
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
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
                $filename = time().'_'.uniqid().'.'.$foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $filename);

                $laporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/'.$filename,
                    'keterangan_foto' => 'Dokumentasi '.$kategori,
                ]);
            }
        }

        // Handle foto deletion
        if ($request->has('delete_fotos')) {
            foreach ($request->delete_fotos as $dok_id) {
                $dok = LaporanDokumentasi::find($dok_id);
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

    public function exportPdf(LaporanPerjalanan $laporan, DocumentExportService $exportService)
    {
        return $exportService->exportLaporan($laporan);
    }

    public function exportWord(LaporanPerjalanan $laporan, DocumentExportService $exportService)
    {
        return $exportService->exportLaporanWord($laporan);
    }

    public function destroy(LaporanPerjalanan $laporan)
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
