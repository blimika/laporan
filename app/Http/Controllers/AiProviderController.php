<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satker;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AiProviderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->check() && in_array(auth()->user()->role, ['super', 'admin'])) {
                    return $next($request);
                }
                abort(403);
            }),
        ];
    }

    public function edit(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'super') {
            $satkerId = $request->satker_id ?? \App\Models\Satker::first()?->id;
            $allSatkers = \App\Models\Satker::all();
        } else {
            $satkerId = $user->satker_id;
            $allSatkers = collect();
        }

        if (!$satkerId) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada Satker yang ditemukan.');
        }

        $satker = Satker::findOrFail($satkerId);
        return view('admin.ai_provider.edit', compact('satker', 'allSatkers'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'super') {
            $satkerId = $request->satker_id;
        } else {
            $satkerId = $user->satker_id;
        }

        if (!$satkerId) {
            return redirect()->route('dashboard')->with('error', 'Pilih Satker terlebih dahulu.');
        }

        $satker = Satker::findOrFail($satkerId);

        $validated = $request->validate([
            'ai_provider' => 'required|in:gemini,deepseek',
            'gemini_api_key' => 'nullable|string',
            'deepseek_api_key' => 'nullable|string',
        ]);

        $validated['is_centralized_api'] = $request->has('is_centralized_api') ? 1 : 0;

        $satker->update($validated);

        return redirect()->back()->with('success', 'Pengaturan Provider AI berhasil disimpan.');
    }
}
