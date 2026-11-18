<?php

namespace App\Http\Controllers;

use App\Models\HanyaLaporan;
use App\Models\HanyaLaporanDokumentasi;
use App\Models\LaporanPerjalanan;
use App\Models\Pegawai;
use App\Models\User;
use App\Services\AiReportGeneratorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HanyaLaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = HanyaLaporan::with(['dokumentasi', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_st', 'like', "%{$search}%")
                    ->orWhere('nomor_spd', 'like', "%{$search}%")
                    ->orWhere('tujuan_perjalanan', 'like', "%{$search}%")
                    ->orWhere('nama_pegawai', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = ['nomor_st', 'nomor_spd', 'nama_pegawai', 'tujuan_perjalanan', 'tgl_perjalanan'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $perPage = $request->input('per_page', 10);
        $laporans = $query->paginate($perPage)->withQueryString();

        return view('hanya_laporan.index', compact('laporans', 'sort', 'direction'));
    }

    public function create(Request $request)
    {
        $pegawais = Pegawai::active()->get();
        $ppkPegawais = Pegawai::active()->ppk()->get();

        $copiedLaporan = null;
        if ($request->filled('copy_from')) {
            $copiedLaporan = HanyaLaporan::find($request->copy_from);
        } elseif ($request->filled('copy_from_laporan')) {
            $lapPerjalanan = LaporanPerjalanan::with(['suratTugas.pegawai', 'suratTugas.spd'])->find($request->copy_from_laporan);
            if ($lapPerjalanan) {
                $copiedLaporan = (object) [
                    'id' => null,
                    'nama_pegawai' => $lapPerjalanan->suratTugas->pegawai->nama ?? '',
                    'nip_pegawai' => $lapPerjalanan->suratTugas->pegawai->nip ?? '',
                    'nomor_st' => $lapPerjalanan->suratTugas->nomor_surat ?? '',
                    'nomor_spd' => $lapPerjalanan->suratTugas->spd->nomor_spd ?? '',
                    'tgl_laporan' => $lapPerjalanan->tgl_laporan,
                    'tgl_perjalanan' => $lapPerjalanan->tgl_perjalanan,
                    'lokasi_perjalanan' => $lapPerjalanan->lokasi_perjalanan,
                    'tujuan_perjalanan' => $lapPerjalanan->tujuan_perjalanan,
                    'pegawai_ditemui' => $lapPerjalanan->pegawai_ditemui,
                    'kategori' => $lapPerjalanan->kategori,
                    'kendala_ditemui' => $lapPerjalanan->kendala_ditemui,
                    'ada_dpr' => $lapPerjalanan->suratTugas->spd ? true : false,
                    'nilai_dpr' => $lapPerjalanan->suratTugas->spd->nilai_dpr ?? null,
                    'ppk_pegawai_id' => $lapPerjalanan->suratTugas->spd->ppk_pegawai_id ?? null,
                    'ada_pernyataan' => false,
                ];
            }
        }

        $previousReports = HanyaLaporan::select([
            'id', 'nama_pegawai', 'nip_pegawai', 'nomor_st', 'nomor_spd',
            'tgl_laporan', 'tgl_perjalanan', 'lokasi_perjalanan', 'tujuan_perjalanan',
            'pegawai_ditemui', 'kategori', 'kendala_ditemui',
            'ada_dpr', 'nilai_dpr', 'ppk_pegawai_id', 'ada_pernyataan',
        ])->latest()->take(100)->get();

        if ($copiedLaporan && isset($copiedLaporan->id) && ! $previousReports->contains('id', $copiedLaporan->id)) {
            $previousReports->prepend($copiedLaporan);
        }

        return view('hanya_laporan.create', compact('pegawais', 'ppkPegawais', 'copiedLaporan', 'previousReports'));
    }

    public function generate(Request $request, AiReportGeneratorService $aiService)
    {
        $data = $request->validate([
            'tujuan_perjalanan' => 'required|string',
            'lokasi_perjalanan' => 'required|string',
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

        $hasil_ai = $aiService->generate($data);

        return response()->json(['hasil' => $hasil_ai]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string',
            'nip_pegawai' => 'nullable|string',
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
            'check_dpr' => 'nullable|boolean',
            'nilai_dpr' => 'nullable|required_if:check_dpr,1|numeric',
            'ppk_pegawai_id' => 'nullable|exists:pegawais,id',
            'check_pernyataan' => 'nullable|boolean',
        ]);

        // Allowed database columns
        $dataToSave = $request->only([
            'nama_pegawai', 'nip_pegawai', 'nomor_st', 'nomor_spd',
            'tgl_laporan', 'tgl_perjalanan', 'lokasi_perjalanan', 'tujuan_perjalanan',
            'pegawai_ditemui', 'kategori', 'kendala_ditemui', 'hasil_perjalanan',
            'stamp_koordinat', 'stamp_datetime',
        ]);

        if ($request->kategori === 'Custom' && $request->filled('kategori_custom')) {
            $dataToSave['kategori'] = $request->kategori_custom;
        }

        $dataToSave['is_stamped'] = $request->has('check_stamp');
        if (! $request->has('check_stamp')) {
            $dataToSave['stamp_koordinat'] = null;
            $dataToSave['stamp_datetime'] = null;
        }

        $hasDpr = $request->boolean('check_dpr');
        $dataToSave['ada_dpr'] = $hasDpr;
        $dataToSave['nilai_dpr'] = $hasDpr ? $request->input('nilai_dpr') : null;
        $dataToSave['ppk_pegawai_id'] = $hasDpr ? $request->input('ppk_pegawai_id') : null;

        $isOrganic = Pegawai::where('nama', $dataToSave['nama_pegawai'])->exists();
        $dataToSave['ada_pernyataan'] = ($isOrganic && ! $request->boolean('check_custom_pegawai'))
            ? $request->boolean('check_pernyataan')
            : false;

        $laporan = HanyaLaporan::create($dataToSave);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time().'_'.uniqid().'.'.$foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $filename);

                $laporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/'.$filename,
                    'keterangan_foto' => 'Dokumentasi '.$dataToSave['kategori'],
                ]);
            }
        }

        return redirect()->route('hanya-laporan.index')->with('success', 'Laporan berhasil disimpan.');
    }

    public function edit(HanyaLaporan $hanyaLaporan)
    {
        $pegawais = Pegawai::active()->get();
        if ($hanyaLaporan->pegawai && ! $pegawais->contains('id', $hanyaLaporan->pegawai->id)) {
            $pegawais->push($hanyaLaporan->pegawai);
        }
        $ppkPegawais = Pegawai::active()->ppk()->get();
        if ($hanyaLaporan->ppkPegawai && ! $ppkPegawais->contains('id', $hanyaLaporan->ppk_pegawai_id)) {
            $ppkPegawais->push($hanyaLaporan->ppkPegawai);
        }

        $users = collect();
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')) {
            $userQuery = User::query();
            if (auth()->user()->role === 'admin') {
                $userQuery->where('satker_id', auth()->user()->satker_id);
            }
            $users = $userQuery->get();
        }

        return view('hanya_laporan.edit', compact('hanyaLaporan', 'pegawais', 'ppkPegawais', 'users'));
    }

    public function update(Request $request, HanyaLaporan $hanyaLaporan)
    {
        $rules = [
            'nama_pegawai' => 'required|string',
            'nip_pegawai' => 'nullable|string',
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
            'check_dpr' => 'nullable|boolean',
            'nilai_dpr' => 'nullable|required_if:check_dpr,1|numeric',
            'ppk_pegawai_id' => 'nullable|exists:pegawais,id',
            'check_pernyataan' => 'nullable|boolean',
        ];

        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')) {
            $rules['user_id'] = 'nullable|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Allowed database columns
        $allowedFields = [
            'nama_pegawai', 'nip_pegawai', 'nomor_st', 'nomor_spd',
            'tgl_laporan', 'tgl_perjalanan', 'lokasi_perjalanan', 'tujuan_perjalanan',
            'pegawai_ditemui', 'kategori', 'kendala_ditemui', 'hasil_perjalanan',
            'stamp_koordinat', 'stamp_datetime',
        ];

        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') && $request->filled('user_id')) {
            $allowedFields[] = 'user_id';
        }

        $dataToUpdate = $request->only($allowedFields);

        if ($request->kategori === 'Custom' && $request->filled('kategori_custom')) {
            $dataToUpdate['kategori'] = $request->kategori_custom;
        }

        $dataToUpdate['is_stamped'] = $request->has('check_stamp');
        if (! $request->has('check_stamp')) {
            $dataToUpdate['stamp_koordinat'] = null;
            $dataToUpdate['stamp_datetime'] = null;
        }

        $hasDpr = $request->boolean('check_dpr');
        $dataToUpdate['ada_dpr'] = $hasDpr;
        $dataToUpdate['nilai_dpr'] = $hasDpr ? $request->input('nilai_dpr') : null;
        $dataToUpdate['ppk_pegawai_id'] = $hasDpr ? $request->input('ppk_pegawai_id') : null;

        $isOrganic = Pegawai::where('nama', $dataToUpdate['nama_pegawai'])->exists();
        $dataToUpdate['ada_pernyataan'] = ($isOrganic && ! $request->boolean('check_custom_pegawai'))
            ? $request->boolean('check_pernyataan')
            : false;

        $hanyaLaporan->update($dataToUpdate);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $filename = time().'_'.uniqid().'.'.$foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/dokumentasi'), $filename);

                $hanyaLaporan->dokumentasi()->create([
                    'file_path' => 'uploads/dokumentasi/'.$filename,
                    'keterangan_foto' => 'Dokumentasi '.$dataToUpdate['kategori'],
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
        $hanyaLaporan->load(['dokumentasi', 'ppkPegawai', 'satker', 'pegawai']);
        $pdf = Pdf::loadView('exports.pdf.hanya_laporan', ['laporan' => $hanyaLaporan]);

        return $pdf->stream('Hanya_Laporan_'.$hanyaLaporan->id.'.pdf');
    }

    public function exportWord(HanyaLaporan $hanyaLaporan)
    {
        $hanyaLaporan->load(['dokumentasi', 'ppkPegawai', 'satker', 'pegawai']);
        $isWord = true;
        $html = view('exports.pdf.hanya_laporan', ['laporan' => $hanyaLaporan, 'isWord' => $isWord])->render();

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="Hanya_Laporan_'.$hanyaLaporan->id.'.doc"');
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
