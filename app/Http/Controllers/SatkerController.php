<?php
namespace App\Http\Controllers;

use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SatkerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->check() && auth()->user()->role !== 'super') {
                    abort(403);
                }
                return $next($request);
            }),
        ];
    }

    public function index() {
        $data = Satker::latest()->get();
        return view('admin.satkers.index', compact('data'));
    }
    
    public function store(Request $request) {
        Satker::create($request->all());
        return back()->with('success', 'Berhasil ditambahkan');
    }
    
    public function edit($id) {
        $satker = Satker::findOrFail($id);
        return view('admin.satkers.edit', compact('satker'));
    }

    public function update(Request $request, $id) {
        $record = Satker::findOrFail($id);
        $record->update($request->all());
        return redirect()->route('admin.satkers.index')->with('success', 'Berhasil diupdate');
    }
    
    public function destroy($id) {
        Satker::findOrFail($id)->delete();
        return back()->with('success', 'Berhasil dihapus');
    }
}
