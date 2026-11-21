<?php

namespace App\Http\Controllers;

use App\Models\Satker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $isSuper = auth()->user()->role === 'super';
        $currentUserSatkerId = auth()->user()->satker_id;

        $baseQuery = User::query();
        if (! $isSuper) {
            $baseQuery->where('satker_id', $currentUserSatkerId);
        }

        // Summary KPI stats
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'super' => (clone $baseQuery)->where('role', 'super')->count(),
            'admin' => (clone $baseQuery)->where('role', 'admin')->count(),
            'user' => (clone $baseQuery)->where('role', 'user')->count(),
            'active_recently' => (clone $baseQuery)->whereNotNull('last_login_at')->count(),
        ];

        $query = User::with('satker');
        if (! $isSuper) {
            $query->where('satker_id', $currentUserSatkerId);
        }

        // Search by name, username, or email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by Satker (Super Admin only)
        if ($isSuper && $request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Sorting
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['name', 'username', 'email', 'role', 'created_at', 'last_login_at'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 10;
        }

        $users = $query->paginate($perPage)->withQueryString();
        $satkers = $isSuper ? Satker::orderBy('kode')->get() : collect();

        // Compatibility alias
        $data = $users;

        return view('admin.users.index', compact('users', 'data', 'stats', 'satkers', 'isSuper', 'sort', 'direction', 'perPage'));
    }

    public function store(Request $request)
    {
        $isSuper = auth()->user()->role === 'super';

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => ['required', 'string', Rule::in($isSuper ? ['user', 'admin', 'super'] : ['user', 'admin'])],
            'password' => 'nullable|string|min:6',
        ];

        if ($isSuper) {
            $rules['satker_id'] = 'nullable|exists:satkers,id';
        }

        $validated = $request->validate($rules);

        if (! $isSuper) {
            $validated['satker_id'] = auth()->user()->satker_id;
        }

        $validated['password'] = bcrypt($validated['password'] ?: '12345678');

        User::create($validated);

        return back()->with('success', 'User '.$validated['name'].' berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $isSuper = auth()->user()->role === 'super';

        if (! $isSuper && $user->satker_id != auth()->user()->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit user ini.');
        }

        $satkers = $isSuper ? Satker::orderBy('kode')->get() : collect();

        return view('admin.users.edit', compact('user', 'satkers', 'isSuper'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isSuper = auth()->user()->role === 'super';

        if (! $isSuper && $user->satker_id != auth()->user()->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah user ini.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in($isSuper ? ['user', 'admin', 'super'] : ['user', 'admin'])],
            'password' => 'nullable|string|min:6',
        ];

        if ($isSuper) {
            $rules['satker_id'] = 'nullable|exists:satkers,id';
        }

        $validated = $request->validate($rules);

        if (! $isSuper) {
            $validated['satker_id'] = $user->satker_id;
        }

        if (! empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Data user '.$user->name.' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if (auth()->user()->role !== 'super' && $user->satker_id != auth()->user()->satker_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus user ini.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', 'User '.$name.' berhasil dihapus.');
    }
}
