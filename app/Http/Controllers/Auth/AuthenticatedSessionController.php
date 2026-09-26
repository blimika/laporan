<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $satkers = \App\Models\Satker::all();
        $tahuns = \App\Models\Tahun::where('aktif', true)->get();
        
        return view('auth.login', compact('satkers', 'tahuns'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        $user = auth()->user();
        if ($user->role === 'super') {
            // Superadmin views all or we let them have no satker_id initially
            // For now, set it to null so they see everything, or they can switch later
            $request->session()->put('satker_id', null);
        } else {
            // Admin and User use their bound satker
            $request->session()->put('satker_id', $user->satker_id);
        }
        $request->session()->put('tahun_id', $request->tahun_id);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
