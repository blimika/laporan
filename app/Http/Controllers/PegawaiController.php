<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Pegawai::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
        }

        $pegawais = $query->latest()->paginate(10)->withQueryString();
        return view('pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|unique:pegawais,nip',
            'nama' => 'required|string|max:255',
            'golongan' => 'nullable|string',
            'pangkat' => 'nullable|string',
            'jabatan' => 'nullable|string',
        ]);

        \App\Models\Pegawai::create($validated);

        return redirect()->route('pegawais.index')->with('success', 'Data pegawai berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $pegawai = \App\Models\Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, string $id)
    {
        $pegawai = \App\Models\Pegawai::findOrFail($id);
        
        $validated = $request->validate([
            'nip' => 'required|string|unique:pegawais,nip,' . $id,
            'nama' => 'required|string|max:255',
            'golongan' => 'nullable|string',
            'pangkat' => 'nullable|string',
            'jabatan' => 'nullable|string',
        ]);

        $pegawai->update($validated);

        return redirect()->route('pegawais.index')->with('success', 'Data pegawai berhasil diperbarui');
    }

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_import_pegawai.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['nip', 'nama', 'golongan', 'pangkat', 'jabatan'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');
            fputcsv($file, ['198001012005011001', 'John Doe', 'III/c', 'Penata', 'Analis Kebijakan Ahli Muda'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        
        $header = fgetcsv($handle, 1000, ';'); // We use semicolon in template
        if (!$header || !in_array('nip', $header) || !in_array('nama', $header)) {
            fclose($handle);
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle, 1000, ',');
            if (!$header || !in_array('nip', $header) || !in_array('nama', $header)) {
                return back()->with('error', 'Format kolom CSV tidak valid. Pastikan memakai template.');
            }
        }

        $imported = 0;
        $delimiter = in_array('nip', fgetcsv(fopen($file->getRealPath(), 'r'), 1000, ';')?:[]) ? ';' : ',';

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if(count($header) == count($row)) {
                $data = array_combine($header, $row);
                
                if (empty($data['nip']) || empty($data['nama'])) continue;

                \App\Models\Pegawai::updateOrCreate(
                    ['nip' => $data['nip']],
                    [
                        'nama' => $data['nama'],
                        'golongan' => $data['golongan'] ?? null,
                        'pangkat' => $data['pangkat'] ?? null,
                        'jabatan' => $data['jabatan'] ?? null,
                    ]
                );
                $imported++;
            }
        }
        fclose($handle);

        return back()->with('success', $imported . ' data pegawai berhasil diimpor.');
    }

    public function destroy(string $id)
    {
        $pegawai = \App\Models\Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawais.index')->with('success', 'Data pegawai berhasil dihapus');
    }
}
