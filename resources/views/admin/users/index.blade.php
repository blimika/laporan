<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                        🛡️ Pengaturan Hak Akses
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="text-xs text-gray-500 font-medium">
                        {{ $isSuper ? 'Lingkup Seluruh Satuan Kerja (Super Admin)' : 'Satker: ' . (auth()->user()->satker->nama ?? 'Default') }}
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-blue-600 to-indigo-600 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </span>
                    <span>{{ __('Manajemen Pengguna (User Management)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1 max-w-3xl">
                    Kelola akun operator, administrator, dan hak akses pengguna sistem. Pengaturan role menentukan otorisasi akses modul dan manajemen data perjalanan dinas.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <button type="button" onclick="openUserModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-blue-600/30 transition active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Tambah User Baru
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts Notifikasi -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl shadow-xs flex items-center justify-between transition">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl shadow-xs flex items-center justify-between transition">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            <!-- 4 SUMMARY KPI STAT CARDS -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Pengguna -->
                <a href="{{ route('admin.users.index') }}" class="p-5 rounded-2xl border transition shadow-xs {{ !request('role') ? 'bg-gradient-to-br from-blue-50 to-indigo-50/60 border-blue-300 ring-2 ring-blue-500/20' : 'bg-white border-gray-100 hover:border-gray-200 hover:shadow-sm' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total User</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-2xl font-black text-gray-900 font-mono">{{ number_format($stats['total']) }}</span>
                        <span class="text-[11px] font-semibold text-blue-600">Semua Akun</span>
                    </div>
                </a>

                <!-- Card 2: Admin Satker -->
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="p-5 rounded-2xl border transition shadow-xs {{ request('role') === 'admin' ? 'bg-gradient-to-br from-indigo-50 to-blue-50/60 border-indigo-300 ring-2 ring-indigo-500/20' : 'bg-white border-gray-100 hover:border-gray-200 hover:shadow-sm' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Admin Satker</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-2xl font-black text-indigo-950 font-mono">{{ number_format($stats['admin']) }}</span>
                        <span class="text-[11px] font-semibold text-indigo-600">Pengelola Satker</span>
                    </div>
                </a>

                <!-- Card 3: User Biasa / Operator -->
                <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="p-5 rounded-2xl border transition shadow-xs {{ request('role') === 'user' ? 'bg-gradient-to-br from-emerald-50 to-green-50/60 border-emerald-300 ring-2 ring-emerald-500/20' : 'bg-white border-gray-100 hover:border-gray-200 hover:shadow-sm' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">User / Operator</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-2xl font-black text-emerald-950 font-mono">{{ number_format($stats['user']) }}</span>
                        <span class="text-[11px] font-semibold text-emerald-600">Operator Pelaporan</span>
                    </div>
                </a>

                <!-- Card 4: Super Admin -->
                <a href="{{ route('admin.users.index', ['role' => 'super']) }}" class="p-5 rounded-2xl border transition shadow-xs {{ request('role') === 'super' ? 'bg-gradient-to-br from-purple-50 to-pink-50/60 border-purple-300 ring-2 ring-purple-500/20' : 'bg-white border-gray-100 hover:border-gray-200 hover:shadow-sm' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Super Admin</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-2xl font-black text-purple-950 font-mono">{{ number_format($stats['super']) }}</span>
                        <span class="text-[11px] font-semibold text-purple-600">Akses Penuh Sistem</span>
                    </div>
                </a>
            </div>

            <!-- MAIN CONTAINER: FILTER BAR + TABLE -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
                
                <!-- FILTER & SEARCH BAR -->
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            
                            <!-- Search Input (col-span-5) -->
                            <div class="lg:col-span-5 relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Cari berdasarkan nama, username, atau email..." 
                                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs sm:text-sm bg-white transition"
                                >
                            </div>

                            <!-- Filter Role (col-span-3) -->
                            <div class="lg:col-span-3">
                                <select name="role" class="w-full py-2 px-3 rounded-xl border border-gray-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs sm:text-sm bg-white transition">
                                    <option value="">-- Semua Role --</option>
                                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User (Operator)</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin (Satker)</option>
                                    <option value="super" {{ request('role') === 'super' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                            </div>

                            <!-- Filter Satker (col-span-2) jika Super Admin -->
                            @if($isSuper)
                            <div class="lg:col-span-2">
                                <select name="satker_id" class="w-full py-2 px-3 rounded-xl border border-gray-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs sm:text-sm bg-white transition">
                                    <option value="">-- Semua Satker --</option>
                                    @foreach($satkers as $s)
                                        <option value="{{ $s->id }}" {{ request('satker_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->kode }} - {{ $s->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Per Page (col-span-2) -->
                            <div class="{{ $isSuper ? 'lg:col-span-2' : 'lg:col-span-4' }} flex items-center gap-2">
                                <select name="per_page" class="w-full py-2 px-3 rounded-xl border border-gray-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs sm:text-sm bg-white transition" onchange="this.form.submit()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 baris</option>
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 baris</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 baris</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 baris</option>
                                </select>

                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-xs shrink-0">
                                    Filter
                                </button>
                                
                                @if(request('search') || request('role') || request('satker_id') || request('per_page'))
                                    <a href="{{ route('admin.users.index') }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition shrink-0" title="Reset Filter">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TABLE DAFTAR PENGGUNA -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50/80">
                            <tr class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th scope="col" class="py-3.5 px-6">Pengguna</th>
                                <th scope="col" class="py-3.5 px-6">Kontak & Email</th>
                                <th scope="col" class="py-3.5 px-6">Peran (Role)</th>
                                <th scope="col" class="py-3.5 px-6">Satuan Kerja</th>
                                <th scope="col" class="py-3.5 px-6">Terdaftar Sejak</th>
                                <th scope="col" class="py-3.5 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($users as $row)
                                <tr class="hover:bg-gray-50/80 transition duration-150">
                                    <!-- Pengguna (Avatar + Nama + Username) -->
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $initials = collect(explode(' ', $row->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                                                $avatarColor = match($row->role) {
                                                    'super' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                    'admin' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    default => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                };
                                            @endphp
                                            <div class="w-10 h-10 rounded-xl {{ $avatarColor }} border font-extrabold text-xs flex items-center justify-center shrink-0 uppercase shadow-2xs">
                                                {{ $initials ?: 'U' }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                                    <span>{{ $row->name }}</span>
                                                    @if($row->id === auth()->id())
                                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Anda</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500 font-mono mt-0.5 flex items-center gap-1">
                                                    <span class="text-gray-400">@</span>{{ $row->username }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Kontak & Email -->
                                    <td class="py-4 px-6 whitespace-nowrap text-xs text-gray-600">
                                        <a href="mailto:{{ $row->email }}" class="text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-1.5 font-medium">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $row->email }}
                                        </a>
                                        @if($row->email_verified_at)
                                            <span class="text-[10px] text-emerald-600 font-semibold mt-0.5 block">Terverifikasi</span>
                                        @endif
                                    </td>

                                    <!-- Role Badge -->
                                    <td class="py-4 px-6 whitespace-nowrap text-xs">
                                        @if($row->role === 'super')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200 shadow-2xs">
                                                <svg class="w-3 h-3 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                                </svg>
                                                Super Admin
                                            </span>
                                        @elseif($row->role === 'admin')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200 shadow-2xs">
                                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                Admin Satker
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                User (Operator)
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Satker -->
                                    <td class="py-4 px-6 whitespace-nowrap text-xs text-gray-700">
                                        @if($row->satker)
                                            <div class="font-semibold text-gray-900">{{ $row->satker->nama }}</div>
                                            <div class="text-[10px] text-gray-400 font-mono">Kode: {{ $row->satker->kode }}</div>
                                        @else
                                            <span class="text-xs text-purple-700 font-medium bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                                Semua Satker (Nasional)
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Terdaftar Sejak -->
                                    <td class="py-4 px-6 whitespace-nowrap text-xs text-gray-500">
                                        <div class="font-medium text-gray-800">{{ $row->created_at ? $row->created_at->format('d M Y') : '-' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $row->created_at ? $row->created_at->diffForHumans() : '' }}</div>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-4 px-6 whitespace-nowrap text-center text-xs font-semibold">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('admin.users.edit', $row->id) }}" class="p-1.5 rounded-lg text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition" title="Edit Data Pengguna">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <!-- Tombol Hapus -->
                                            @if($row->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user &quot;{{ $row->name }}&quot;? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:text-rose-900 hover:bg-rose-50 transition" title="Hapus User">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="p-1.5 text-gray-300 cursor-not-allowed" title="Anda tidak dapat menghapus akun Anda sendiri">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <div class="max-w-sm mx-auto space-y-2">
                                            <div class="w-12 h-12 mx-auto rounded-full bg-gray-100 text-gray-400 flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-bold text-gray-800">Tidak Ada Pengguna Ditemukan</h4>
                                            <p class="text-xs text-gray-400">
                                                @if(request('search') || request('role') || request('satker_id'))
                                                    Tidak ada user yang sesuai dengan filter pencarian Anda. Coba atur ulang filter pencarian.
                                                @else
                                                    Belum ada data user tambahan yang terdaftar.
                                                @endif
                                            </p>
                                            @if(request('search') || request('role') || request('satker_id'))
                                                <a href="{{ route('admin.users.index') }}" class="inline-block mt-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 underline">
                                                    Reset Filter Pencarian
                                                </a>
                                            @else
                                                <div class="pt-2">
                                                    <button type="button" onclick="openUserModal()" class="inline-flex items-center px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs transition active:scale-95 cursor-pointer">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Tambah Pengguna Sekarang
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION FOOTER -->
                <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500 bg-gray-50/50">
                    <div>
                        Menampilkan <span class="font-bold text-gray-800">{{ $users->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-gray-800">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-800">{{ $users->total() }}</span> pengguna
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH USER BARU -->
        <div 
            id="user-modal"
            class="fixed inset-0 z-50 overflow-y-auto {{ $errors->any() ? '' : 'hidden' }}"
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            <!-- Backdrop -->
            <div 
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"
                onclick="closeUserModal()"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gray-100 z-10"
                >
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 bg-white/10 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold leading-tight" id="modal-title">Tambah Pengguna Baru</h3>
                                <p class="text-[11px] text-blue-100">Lengkapi data untuk mendaftarkan akun pengguna ke sistem</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeUserModal()" class="text-white/80 hover:text-white rounded-lg p-1 hover:bg-white/10 transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Modal Body Form -->
                    <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                            <x-text-input id="name" class="block mt-1 w-full rounded-xl text-sm" type="text" name="name" :value="old('name')" placeholder="Contoh: Budi Santoso, S.Stat" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Username -->
                            <div>
                                <x-input-label for="username" :value="__('Username')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <x-text-input id="username" class="block mt-1 w-full rounded-xl text-sm font-mono" type="text" name="username" :value="old('username')" placeholder="Contoh: budi_s" required />
                                <p class="text-[11px] text-gray-400 mt-1">Digunakan saat login aplikasi.</p>
                                <x-input-error :messages="$errors->get('username')" class="mt-1" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <x-text-input id="email" class="block mt-1 w-full rounded-xl text-sm" type="email" name="email" :value="old('email')" placeholder="budi@bps.go.id" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Role -->
                            <div>
                                <x-input-label for="role" :value="__('Role / Peran Akses')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-sm" required>
                                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Operator)</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Satker)</option>
                                    @if($isSuper)
                                        <option value="super" {{ old('role') === 'super' ? 'selected' : '' }}>Super Admin</option>
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-1" />
                            </div>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <x-text-input id="password" class="block mt-1 w-full rounded-xl text-sm" type="password" name="password" placeholder="Default: 12345678" />
                                <p class="text-[11px] text-gray-400 mt-1">Kosongkan jika memakai password default (12345678).</p>
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Satker (Super Admin only) -->
                        @if($isSuper)
                            <div>
                                <x-input-label for="satker_id" :value="__('Satker (Satuan Kerja)')" class="text-xs font-bold uppercase tracking-wider text-gray-700" />
                                <select id="satker_id" name="satker_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-sm">
                                    <option value="">-- Semua Satker / Nasional (Super Admin) --</option>
                                    @foreach($satkers as $s)
                                        <option value="{{ $s->id }}" {{ old('satker_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->kode }} - {{ $s->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-gray-400 mt-1">Pilih Satker tempat pengguna bertugas.</p>
                                <x-input-error :messages="$errors->get('satker_id')" class="mt-1" />
                            </div>
                        @endif

                        <!-- Modal Actions Footer -->
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                            <button type="button" onclick="closeUserModal()" class="px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-blue-600/30 transition active:scale-95 cursor-pointer">
                                Simpan Pengguna Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                document.body.classList.add('overflow-hidden');
            });
        @endif

        function openUserModal() {
            const modal = document.getElementById('user-modal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                setTimeout(() => {
                    const nameInput = document.getElementById('name');
                    if (nameInput) {
                        nameInput.focus();
                    }
                }, 50);
            }
        }

        function closeUserModal() {
            const modal = document.getElementById('user-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeUserModal();
            }
        });
    </script>
</x-app-layout>
