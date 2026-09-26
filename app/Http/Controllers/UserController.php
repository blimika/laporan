<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        if (auth()->user()->role === 'super') {
            $data = User::latest()->get();
        } else {
            $data = User::where('satker_id', auth()->user()->satker_id)->latest()->get();
        }
        return view('admin.users.index', compact('data'));
    }
    
    public function store(Request $request) {
        $data = $request->all();
        if (auth()->user()->role !== 'super') {
            $data['satker_id'] = auth()->user()->satker_id;
        }
        $data['password'] = bcrypt($data['password'] ?? '12345678');
        User::create($data);
        return back()->with('success', 'Berhasil ditambahkan');
    }
    
    public function update(Request $request, $id) {
        $record = User::findOrFail($id);
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
        User::findOrFail($id)->delete();
        return back()->with('success', 'Berhasil dihapus');
    }
}
