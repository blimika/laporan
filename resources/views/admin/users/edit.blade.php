<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Manajemen User
                    </a>
                    <span class="text-gray-300">•</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                        Edit Akun Pengguna
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-blue-600 to-indigo-600 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    <span>Edit Pengguna: {{ $user->name }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Perbarui profil, hak akses role, penugasan satuan kerja, atau atur ulang kata sandi pengguna.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition">
                    Batal & Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- USER IDENTITY BANNER -->
            <div class="bg-gradient-to-r from-blue-50/80 via-indigo-50/50 to-white rounded-2xl border border-blue-200/80 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    @php
                        $initials = collect(explode(' ', $user->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                        $avatarColor = match($user->role) {
                            'super' => 'bg-purple-100 text-purple-700 border-purple-300',
                            'admin' => 'bg-blue-100 text-blue-700 border-blue-300',
                            default => 'bg-emerald-100 text-emerald-700 border-emerald-300',
                        };
                    @endphp
                    <div class="w-12 h-12 rounded-2xl {{ $avatarColor }} border-2 font-black text-sm flex items-center justify-center uppercase shadow-xs">
                        {{ $initials ?: 'U' }}
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <span>{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Akun Anda</span>
                            @endif
                        </h3>
                        <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-gray-500">
                            <span class="font-mono bg-white px-2 py-0.5 rounded border border-gray-200">@ {{ $user->username }}</span>
                            <span>•</span>
                            <span>{{ $user->email }}</span>
                            <span>•</span>
                            <span class="text-gray-400">Bergabung {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                        </div>
                        @if($user->last_login_at)
                            <div class="flex items-center gap-2 mt-2 text-xs text-indigo-700 bg-indigo-50/80 border border-indigo-100 rounded-lg px-2.5 py-1 w-fit">
                                <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Login Terakhir: <strong>{{ $user->last_login_at->translatedFormat('d M Y, H:i') }}</strong> ({{ $user->last_login_at->diffForHumans() }})</span>
                                <span class="text-indigo-300">•</span>
                                <span class="font-mono">IP: <strong>{{ $user->last_login_ip ?: '-' }}</strong></span>
                            </div>
                        @else
                            <div class="mt-2 text-[11px] text-gray-400 italic">
                                Belum pernah login ke sistem
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    @if($user->role === 'super')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200 shadow-2xs">
                            👑 Super Admin
                        </span>
                    @elseif($user->role === 'admin')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200 shadow-2xs">
                            🛡️ Admin Satker
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                            👤 User (Operator)
                        </span>
                    @endif
                </div>
            </div>

            <!-- FORM EDIT USER -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900">Formulir Pembaruan Data Pengguna</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Sesuaikan informasi pengguna di bawah ini, lalu klik Simpan Perubahan.</p>
                </div>

                <div class="p-6 md:p-8">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                            <x-text-input id="name" class="block mt-1 w-full rounded-xl text-sm" type="text" name="name" :value="old('name', $user->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Username -->
                            <div>
                                <x-input-label for="username" :value="__('Username')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <x-text-input id="username" class="block mt-1 w-full rounded-xl text-sm font-mono" type="text" name="username" :value="old('username', $user->username)" required />
                                <p class="text-[11px] text-gray-400 mt-1">Username unik untuk login.</p>
                                <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <x-text-input id="email" class="block mt-1 w-full rounded-xl text-sm" type="email" name="email" :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Role -->
                            <div>
                                <x-input-label for="role" :value="__('Role / Peran Akses')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-sm" required>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User (Operator Pelaporan)</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Pengelola Satker)</option>
                                    @if($isSuper)
                                        <option value="super" {{ old('role', $user->role) === 'super' ? 'selected' : '' }}>Super Admin (Semua Hak Akses)</option>
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
                            </div>

                            <!-- Password Baru (Opsional) -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <x-input-label for="password" :value="__('Password Baru')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                    <span class="text-[11px] text-gray-400 capitalize">Opsional</span>
                                </div>
                                <x-text-input id="password" class="block mt-1 w-full rounded-xl text-sm" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" />
                                <p class="text-[11px] text-gray-400 mt-1">Biarkan kosong jika tetap menggunakan kata sandi lama.</p>
                                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                            </div>
                        </div>

                        <!-- Satker (Super Admin only) -->
                        @if($isSuper)
                            <div>
                                <x-input-label for="satker_id" :value="__('Satuan Kerja (Satker)')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <select id="satker_id" name="satker_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-sm">
                                    <option value="">-- Semua Satker / Nasional (Super Admin) --</option>
                                    @foreach($satkers as $s)
                                        <option value="{{ $s->id }}" {{ old('satker_id', $user->satker_id) == $s->id ? 'selected' : '' }}>
                                            {{ $s->kode }} - {{ $s->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-gray-400 mt-1">Satker menentukan lingkup data yang dapat dilihat dan dikelola user.</p>
                                <x-input-error :messages="$errors->get('satker_id')" class="mt-1.5" />
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-blue-600/30 transition active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
