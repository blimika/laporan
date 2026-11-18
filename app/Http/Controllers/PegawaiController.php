<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->check() && auth()->user()->role === 'user') {
                    abort(403, 'Anda hanya dapat melihat data.');
                }

                return $next($request);
            }, except: ['index', 'downloadTemplate']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pegawai::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $currentTag = $request->input('tag');
        if ($request->filled('tag')) {
            if ($currentTag === 'aktif') {
                $query->where('is_active', true);
            } elseif ($currentTag === 'tidak_aktif') {
                $query->where('is_active', false);
            } elseif (array_key_exists($currentTag, Pegawai::AVAILABLE_TAGS)) {
                $query->whereJsonContains('tags', $currentTag);
            }
        }

        $baseCountQuery = Pegawai::query();
        $counts = [
            'total' => (clone $baseCountQuery)->count(),
            'aktif' => (clone $baseCountQuery)->where('is_active', true)->count(),
            'tidak_aktif' => (clone $baseCountQuery)->where('is_active', false)->count(),
            'kepala_group' => (clone $baseCountQuery)->where('is_active', true)->where(function ($q) {
                $q->whereJsonContains('tags', 'kepala')
                    ->orWhereJsonContains('tags', 'plt')
                    ->orWhereJsonContains('tags', 'plh');
            })->count(),
            'ppk' => (clone $baseCountQuery)->where('is_active', true)->whereJsonContains('tags', 'ppk')->count(),
            'bendahara' => (clone $baseCountQuery)->where('is_active', true)->whereJsonContains('tags', 'bendahara')->count(),
            'kasubbag' => (clone $baseCountQuery)->where('is_active', true)->whereJsonContains('tags', 'kasubbag')->count(),
        ];

        $perPage = $request->input('per_page', 10);
        $pegawais = $query->latest()->paginate($perPage)->withQueryString();

        return view('pegawai.index', compact('pegawais', 'counts', 'currentTag'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => [
                'required',
                'string',
                Rule::unique('pegawais')->where(function ($query) {
                    return $query->where('satker_id', session('satker_id'));
                }),
            ],
            'nama' => 'required|string|max:255',
            'golongan' => 'nullable|string',
            'pangkat' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'status_keaktifan' => 'nullable|string|in:'.implode(',', Pegawai::STATUS_TAGS),
            'tags' => 'nullable|array',
            'tags.*' => 'string|in:'.implode(',', array_keys(Pegawai::AVAILABLE_TAGS)),
        ]);

        $tags = $request->input('tags', []);
        if ($request->filled('status_keaktifan')) {
            $tags = array_values(array_diff($tags, Pegawai::STATUS_TAGS));
            $tags[] = $request->status_keaktifan;
        }

        $validated['tags'] = array_values(array_unique($tags));
        unset($validated['status_keaktifan']);

        Pegawai::create($validated);

        return redirect()->route('pegawais.index')->with('success', 'Data pegawai berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nip' => [
                'required',
                'string',
                Rule::unique('pegawais')->where(function ($query) {
                    return $query->where('satker_id', session('satker_id'));
                })->ignore($id),
            ],
            'nama' => 'required|string|max:255',
            'golongan' => 'nullable|string',
            'pangkat' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'status_keaktifan' => 'nullable|string|in:'.implode(',', Pegawai::STATUS_TAGS),
            'tags' => 'nullable|array',
            'tags.*' => 'string|in:'.implode(',', array_keys(Pegawai::AVAILABLE_TAGS)),
        ]);

        $tags = $request->input('tags', []);
        if ($request->filled('status_keaktifan')) {
            $tags = array_values(array_diff($tags, Pegawai::STATUS_TAGS));
            $tags[] = $request->status_keaktifan;
        }

        $validated['tags'] = array_values(array_unique($tags));
        unset($validated['status_keaktifan']);

        $pegawai->update($validated);

        return redirect()->route('pegawais.index')->with('success', 'Data pegawai berhasil diperbarui');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=template_import_pegawai.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['nip', 'nama', 'golongan', 'pangkat', 'jabatan', 'tags'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');
            fputcsv($file, ['198001012005011001', 'John Doe', 'III/c', 'Penata', 'Kepala Subbagian Tata Usaha', 'aktif, kasubbag, ppk'], ';');
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
        if (! $header || ! in_array('nip', $header) || ! in_array('nama', $header)) {
            fclose($handle);
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle, 1000, ',');
            if (! $header || ! in_array('nip', $header) || ! in_array('nama', $header)) {
                return back()->with('error', 'Format kolom CSV tidak valid. Pastikan memakai template.');
            }
        }

        $imported = 0;
        $delimiter = in_array('nip', fgetcsv(fopen($file->getRealPath(), 'r'), 1000, ';') ?: []) ? ';' : ',';

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if (count($header) == count($row)) {
                $data = array_combine($header, $row);

                if (empty($data['nip']) || empty($data['nama'])) {
                    continue;
                }

                $tags = null;
                $rawTags = $data['tags'] ?? $data['tag'] ?? null;
                if (! empty($rawTags)) {
                    $parsedTags = array_map('trim', explode(',', strtolower($rawTags)));
                    $validTags = array_keys(Pegawai::AVAILABLE_TAGS);
                    $filteredTags = array_values(array_intersect($parsedTags, $validTags));
                    if (! empty($filteredTags)) {
                        $tags = $filteredTags;
                    }
                }

                $updateData = [
                    'nama' => $data['nama'],
                    'golongan' => $data['golongan'] ?? null,
                    'pangkat' => $data['pangkat'] ?? null,
                    'jabatan' => $data['jabatan'] ?? null,
                ];

                if ($tags !== null) {
                    $updateData['tags'] = $tags;
                }

                Pegawai::updateOrCreate(
                    ['nip' => $data['nip']],
                    $updateData
                );
                $imported++;
            }
        }
        fclose($handle);

        return back()->with('success', $imported.' data pegawai berhasil diimpor.');
    }

    public function destroy(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawais.index')->with('success', 'Data pegawai berhasil dihapus');
    }
}
