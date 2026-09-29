<?php
namespace App\Http\Controllers;

use App\Models\Tahun;
use Illuminate\Http\Request;

class TahunController extends Controller
{
    public function index() {
        $data = Tahun::latest()->get();
        return view('admin.tahuns.index', compact('data'));
    }
    
    public function store(Request $request) {
        $data = $request->all();
        $data['aktif'] = $request->has('aktif') ? 1 : 0;
        if ($data['aktif']) {
            Tahun::query()->update(['aktif' => 0]);
        }
        Tahun::create($data);
        return back()->with('success', 'Berhasil ditambahkan');
    }
    
    public function edit($id) {
        $tahun = Tahun::findOrFail($id);
        return view('admin.tahuns.edit', compact('tahun'));
    }

    public function update(Request $request, $id) {
        $record = Tahun::findOrFail($id);
        $data = $request->all();
        $data['aktif'] = $request->has('aktif') ? 1 : 0;
        
        if ($data['aktif']) {
            Tahun::where('id', '!=', $id)->update(['aktif' => 0]);
        }
        
        $record->update($data);
        return redirect()->route('admin.tahuns.index')->with('success', 'Berhasil diupdate');
    }
    
    public function destroy($id) {
        Tahun::findOrFail($id)->delete();
        return back()->with('success', 'Berhasil dihapus');
    }
}
