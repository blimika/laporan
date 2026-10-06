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
        $query = HanyaLaporan::with(['dokumentasi', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_st', 'like', "%{$search}%")
                  ->orWhere('tujuan_perjalanan', 'like', "%{$search}%")
                  ->orWhere('nama_pegawai', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        
        $allowedSorts = ['nomor_st', 'nama_pegawai', 'tujuan_perjalanan', 'tgl_perjalanan'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $perPage = $request->input('per_page', 10);
        $laporans = $query->paginate($perPage)->withQueryString();
        return view('hanya_laporan.index', compact('laporans', 'sort', 'direction'));
    }

    public function create()
    {
        $pegawais = \App\Models\Pegawai::all();
        return view('hanya_laporan.create', compact('pegawais'));
    }

    public function generate(Request $request, \App\Services\AiReportGeneratorService $aiService)
    {
        $data = $request->validate([
            'tujuan_perjalanan' => 'required|string',
            'lokasi_perjalanan' => 'required|string',
            'pegawai_ditemui' => 'required|string',
            'kategori' => 'required|string',
            'kategori_custom' => 'nullable|string',
            'kendala_ditemui' => 'nullable|string',
            'is_8_jam' => 'nullable|boolean',
        ]);

        if ($data['kategori'] === 'Custom' && !empty($data['kategori_custom'])) {
            $data['kategori'] = $data['kategori_custom'];
        }

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
            'stamp_koordinat' => 'nullable|required_with:check_stamp|string',
            'stamp_datetime' => 'nullable|required_with:check_stamp|date',
        ]);

        // Merge custom kategori
        $dataToSave = $request->except(['_token', 'fotos', 'kategori_custom', 'check_stamp']);
        if ($request->kategori === 'Custom' && $request->filled('kategori_custom')) {
            $dataToSave['kategori'] = $request->kategori_custom;
        }
        
        $dataToSave['is_stamped'] = $request->has('check_stamp');

        $laporan = HanyaLaporan::create($dataToSave);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $filename);
                
                $laporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/' . $filename,
                    'keterangan_foto' => 'Dokumentasi ' . $dataToSave['kategori'],
                ]);
            }
        }

        return redirect()->route('hanya-laporan.index')->with('success', 'Laporan berhasil disimpan.');
    }

    public function edit(HanyaLaporan $hanyaLaporan)
    {
        $pegawais = \App\Models\Pegawai::all();
        $users = collect();
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') {
            $userQuery = \App\Models\User::query();
            if (auth()->user()->role === 'admin') {
                $userQuery->where('satker_id', auth()->user()->satker_id);
            }
            $users = $userQuery->get();
        }
        return view('hanya_laporan.edit', compact('hanyaLaporan', 'pegawais', 'users'));
    }

    public function update(Request $request, HanyaLaporan $hanyaLaporan)
    {
        $rules = [
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
            'stamp_koordinat' => 'nullable|required_with:check_stamp|string',
            'stamp_datetime' => 'nullable|required_with:check_stamp|date',
        ];

        if (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') {
            $rules['user_id'] = 'nullable|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Merge custom kategori
        $dataToUpdate = $request->except(['_token', '_method', 'fotos', 'delete_fotos', 'kategori_custom', 'check_stamp']);
        if ($request->kategori === 'Custom' && $request->filled('kategori_custom')) {
            $dataToUpdate['kategori'] = $request->kategori_custom;
        }

        // Handle user_id update only if filled (to prevent accidental orphaning)
        if (array_key_exists('user_id', $dataToUpdate) && empty($dataToUpdate['user_id'])) {
            unset($dataToUpdate['user_id']);
        }
        
        $dataToUpdate['is_stamped'] = $request->has('check_stamp');
        if (!$request->has('check_stamp')) {
            $dataToUpdate['stamp_koordinat'] = null;
            $dataToUpdate['stamp_datetime'] = null;
        }

        $hanyaLaporan->update($dataToUpdate);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $filename);
                
                $hanyaLaporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/' . $filename,
                    'keterangan_foto' => 'Dokumentasi ' . $dataToUpdate['kategori'],
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
        $hanyaLaporan->load('dokumentasi');
        $pdf = Pdf::loadView('exports.pdf.hanya_laporan', ['laporan' => $hanyaLaporan]);
        return $pdf->stream('Hanya_Laporan_'.$hanyaLaporan->id.'.pdf');
    }

    public function exportWord(HanyaLaporan $hanyaLaporan)
    {
        $hanyaLaporan->load('dokumentasi');
        $isWord = true;
        $html = view('exports.pdf.hanya_laporan', ['laporan' => $hanyaLaporan, 'isWord' => $isWord])->render();
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="Hanya_Laporan_' . $hanyaLaporan->id . '.doc"');
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
