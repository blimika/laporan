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
        Tahun::create($request->all());
        return back()->with('success', 'Berhasil ditambahkan');
    }
    
    public function update(Request $request, $id) {
        $record = Tahun::findOrFail($id);
        if ($request->has('password') && $request->password != '') {
            $data = $request->except('password');
            $data['password'] = bcrypt($request->password);
            $record->update($data);
        } else {
            $record->update($request->except('password'));
        }
        return back()->with('success', 'Berhasil diupdate');
    }
    
    public function destroy($id) {
        Tahun::findOrFail($id)->delete();
        return back()->with('success', 'Berhasil dihapus');
    }
}
