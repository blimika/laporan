<?php

namespace App\Http\Controllers;

use App\Models\Tahun;
use Illuminate\Http\Request;

class TahunController extends Controller
{
    public function index()
    {
        $data = Tahun::orderBy('tahun', 'desc')->get();

        return view('admin.tahuns.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|digits:4|unique:tahuns,tahun',
        ]);

        Tahun::create([
            'tahun' => $validated['tahun'],
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        return back()->with('success', 'Tahun anggaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tahun = Tahun::findOrFail($id);

        return view('admin.tahuns.edit', compact('tahun'));
    }

    public function update(Request $request, $id)
    {
        $record = Tahun::findOrFail($id);

        $validated = $request->validate([
            'tahun' => 'required|digits:4|unique:tahuns,tahun,'.$id,
        ]);

        $record->update([
            'tahun' => $validated['tahun'],
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        return redirect()->route('admin.tahuns.index')->with('success', 'Tahun anggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Tahun::findOrFail($id)->delete();

        return back()->with('success', 'Tahun anggaran berhasil dihapus.');
    }
}
