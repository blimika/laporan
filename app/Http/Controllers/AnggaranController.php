<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Spd;
use App\Models\SuratTugas;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class AnggaranController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->check() && auth()->user()->role === 'user') {
                    abort(403, 'Anda hanya dapat melihat data.');
                }

                return $next($request);
            }, except: ['index', 'downloadTemplate', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Anggaran::query()
            ->withCount('suratTugas')
            ->withSum('spds', 'nilai_dpr');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('mak', 'like', "%{$search}%")
                    ->orWhere('kegiatan_uraian', 'like', "%{$search}%")
                    ->orWhere('output_uraian', 'like', "%{$search}%")
                    ->orWhere('program_uraian', 'like', "%{$search}%")
                    ->orWhere('komponen_uraian', 'like', "%{$search}%")
                    ->orWhere('akun_kode', 'like', "%{$search}%")
                    ->orWhere('akun_uraian', 'like', "%{$search}%")
                    ->orWhere('tahun', 'like', "%{$search}%");
            });
        }

        // Tahun Filter
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Status Penggunaan Filter
        if ($request->status === 'used') {
            $query->has('suratTugas');
        } elseif ($request->status === 'unused') {
            $query->doesntHave('suratTugas');
        }

        // Sorting
        $sort = $request->input('sort', 'created_at');
        $direction = strtolower($request->input('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        if ($sort === 'surat_tugas_count') {
            $query->orderBy('surat_tugas_count', $direction);
        } elseif ($sort === 'dpr') {
            $query->orderBy('spds_sum_nilai_dpr', $direction);
        } elseif ($sort === 'mak') {
            $query->orderBy('mak', $direction);
        } elseif ($sort === 'tahun') {
            $query->orderBy('tahun', $direction);
        } else {
            $query->latest();
        }

        $perPage = (int) $request->input('per_page', 10);
        $anggarans = $query->paginate($perPage)->withQueryString();

        // Summary Stats
        $stats = [
            'total_mak' => Anggaran::count(),
            'used_mak' => Anggaran::has('suratTugas')->count(),
            'total_st' => SuratTugas::whereNotNull('pembebanan_anggaran_id')->count(),
            'total_dpr' => (float) Spd::whereHas('suratTugas', function ($q) {
                $q->whereNotNull('pembebanan_anggaran_id');
            })->sum('nilai_dpr'),
        ];

        // Available Tahun list for filter
        $tahunList = Anggaran::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('anggaran.index', compact('anggarans', 'stats', 'tahunList', 'sort', 'direction'));
    }

    public function create()
    {
        return view('anggaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mak' => [
                'required',
                'string',
                Rule::unique('anggarans')->where(function ($query) use ($request) {
                    return $query->where('tahun', $request->tahun)
                        ->where('satker_id', session('satker_id'));
                }),
            ],
            'tahun' => 'required|digits:4|integer',
            'program_kode' => 'nullable|string',
            'program_uraian' => 'nullable|string',
            'kegiatan_kode' => 'nullable|string',
            'kegiatan_uraian' => 'nullable|string',
            'output_kode' => 'nullable|string',
            'output_uraian' => 'nullable|string',
            'suboutput_kode' => 'nullable|string',
            'suboutput_uraian' => 'nullable|string',
            'komponen_kode' => 'nullable|string',
            'komponen_uraian' => 'nullable|string',
            'subkomponen_kode' => 'nullable|string',
            'subkomponen_uraian' => 'nullable|string',
            'akun_kode' => 'nullable|string',
            'akun_uraian' => 'nullable|string',
        ]);

        Anggaran::create($validated);

        return redirect()->route('anggarans.index')->with('success', 'Data anggaran berhasil ditambahkan');
    }

    public function show(Request $request, string $id)
    {
        $anggaran = Anggaran::withCount('suratTugas')
            ->withSum('spds', 'nilai_dpr')
            ->with(['suratTugas' => function ($q) {
                $q->with(['pegawai', 'spd'])->latest()->take(10);
            }])
            ->findOrFail($id);

        if ($request->wantsJson() || $request->ajax() || $request->has('json')) {
            return response()->json($anggaran);
        }

        return view('anggaran.edit', compact('anggaran'));
    }

    public function edit(string $id)
    {
        $anggaran = Anggaran::findOrFail($id);

        return view('anggaran.edit', compact('anggaran'));
    }

    public function update(Request $request, string $id)
    {
        $anggaran = Anggaran::findOrFail($id);

        $validated = $request->validate([
            'mak' => [
                'required',
                'string',
                Rule::unique('anggarans')->where(function ($query) use ($request) {
                    return $query->where('tahun', $request->tahun)
                        ->where('satker_id', session('satker_id'));
                })->ignore($id),
            ],
            'tahun' => 'required|digits:4|integer',
            'program_kode' => 'nullable|string',
            'program_uraian' => 'nullable|string',
            'kegiatan_kode' => 'nullable|string',
            'kegiatan_uraian' => 'nullable|string',
            'output_kode' => 'nullable|string',
            'output_uraian' => 'nullable|string',
            'suboutput_kode' => 'nullable|string',
            'suboutput_uraian' => 'nullable|string',
            'komponen_kode' => 'nullable|string',
            'komponen_uraian' => 'nullable|string',
            'subkomponen_kode' => 'nullable|string',
            'subkomponen_uraian' => 'nullable|string',
            'akun_kode' => 'nullable|string',
            'akun_uraian' => 'nullable|string',
        ]);

        $anggaran->update($validated);

        return redirect()->route('anggarans.index')->with('success', 'Data anggaran berhasil diperbarui');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=template_import_anggaran.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['mak', 'tahun', 'program_kode', 'program_uraian', 'kegiatan_kode', 'kegiatan_uraian', 'output_kode', 'output_uraian', 'suboutput_kode', 'suboutput_uraian', 'komponen_kode', 'komponen_uraian', 'subkomponen_kode', 'subkomponen_uraian', 'akun_kode', 'akun_uraian'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');
            fputcsv($file, ['054.01.WA.1234', '2026', '054', 'Program A', 'WA', 'Kegiatan B', '1234', 'Output C', '', '', '', '', '', '', '', ''], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');

        $header = fgetcsv($handle, 1000, ';'); // We use semicolon in template
        if (! $header || ! in_array('mak', $header) || ! in_array('tahun', $header)) {
            // fallback to comma if semicolon fails
            fclose($handle);
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle, 1000, ',');
            if (! $header || ! in_array('mak', $header) || ! in_array('tahun', $header)) {
                return back()->with('error', 'Format kolom CSV tidak valid. Pastikan memakai template.');
            }
        }

        $imported = 0;
        $delimiter = in_array('mak', fgetcsv(fopen($file->getRealPath(), 'r'), 1000, ';') ?: []) ? ';' : ',';

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if (count($header) == count($row)) {
                $data = array_combine($header, $row);

                if (empty($data['mak']) || empty($data['tahun'])) {
                    continue;
                }

                Anggaran::updateOrCreate(
                    [
                        'mak' => $data['mak'],
                        'tahun' => $data['tahun'],
                    ],
                    [
                        'program_kode' => $data['program_kode'] ?? null,
                        'program_uraian' => $data['program_uraian'] ?? null,
                        'kegiatan_kode' => $data['kegiatan_kode'] ?? null,
                        'kegiatan_uraian' => $data['kegiatan_uraian'] ?? null,
                        'output_kode' => $data['output_kode'] ?? null,
                        'output_uraian' => $data['output_uraian'] ?? null,
                        'suboutput_kode' => $data['suboutput_kode'] ?? null,
                        'suboutput_uraian' => $data['suboutput_uraian'] ?? null,
                        'komponen_kode' => $data['komponen_kode'] ?? null,
                        'komponen_uraian' => $data['komponen_uraian'] ?? null,
                        'subkomponen_kode' => $data['subkomponen_kode'] ?? null,
                        'subkomponen_uraian' => $data['subkomponen_uraian'] ?? null,
                        'akun_kode' => $data['akun_kode'] ?? null,
                        'akun_uraian' => $data['akun_uraian'] ?? null,
                    ]
                );
                $imported++;
            }
        }
        fclose($handle);

        return back()->with('success', $imported.' data anggaran berhasil diimpor.');
    }

    public function destroy(string $id)
    {
        $anggaran = Anggaran::findOrFail($id);
        $anggaran->delete();

        return redirect()->route('anggarans.index')->with('success', 'Data anggaran berhasil dihapus');
    }
}
