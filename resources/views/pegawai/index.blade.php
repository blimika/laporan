<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Data Pegawai') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data master pegawai, status keaktifan (aktif, mutasi, pensiun), dan tag peran penandatangan.
                </p>
            </div>
            @if(auth()->user()->role !== 'user')
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('pegawais.template') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Template CSV
                </a>
                <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')" class="inline-flex items-center px-3.5 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Import CSV
                </button>
                <a href="{{ route('pegawais.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pegawai
                </a>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Banner Statistics & Quick Status/Tag Filter -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- Total Pegawai -->
                <a href="{{ route('pegawais.index') }}" class="block p-4 rounded-xl border transition shadow-sm {{ empty($currentTag) ? 'bg-blue-50 border-blue-300 ring-2 ring-blue-500/20' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Pegawai</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="text-2xl font-bold text-gray-900">{{ $counts['total'] }}</span>
                        <span class="text-xs text-gray-500">Semua Data</span>
                    </div>
                </a>

                <!-- Pegawai Aktif -->
                <a href="{{ route('pegawais.index', ['tag' => 'aktif']) }}" class="block p-4 rounded-xl border transition shadow-sm {{ $currentTag === 'aktif' ? 'bg-emerald-50 border-emerald-300 ring-2 ring-emerald-500/20' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Pegawai Aktif</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                            <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="text-2xl font-bold text-emerald-900">{{ $counts['aktif'] }}</span>
                        <span class="text-xs text-emerald-600">Bisa Ditugaskan</span>
                    </div>
                </a>

                <!-- Pegawai Tidak Aktif (Mutasi / Pensiun) -->
                <a href="{{ route('pegawais.index', ['tag' => 'tidak_aktif']) }}" class="block p-4 rounded-xl border transition shadow-sm {{ in_array($currentTag, ['tidak_aktif', 'mutasi', 'pensiun']) ? 'bg-gray-100 border-gray-400 ring-2 ring-gray-500/20' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-700">Tidak Aktif</span>
                        <div class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="text-2xl font-bold text-gray-900">{{ $counts['tidak_aktif'] }}</span>
                        <span class="text-xs text-gray-500">Mutasi / Pensiun</span>
                    </div>
                </a>

                <!-- TTD Kepala (ST) -->
                <a href="{{ route('pegawais.index', ['tag' => 'kepala']) }}" class="block p-4 rounded-xl border transition shadow-sm {{ in_array($currentTag, ['kepala', 'plt', 'plh']) ? 'bg-purple-50 border-purple-300 ring-2 ring-purple-500/20' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-purple-700">TTD Kepala / Plt / Plh</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700">
                            <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="text-2xl font-bold text-purple-900">{{ $counts['kepala_group'] }}</span>
                        <span class="text-xs text-purple-600">TTD Surat Tugas</span>
                    </div>
                </a>

                <!-- PPK -->
                <a href="{{ route('pegawais.index', ['tag' => 'ppk']) }}" class="block p-4 rounded-xl border transition shadow-sm {{ $currentTag === 'ppk' ? 'bg-blue-50 border-blue-300 ring-2 ring-blue-500/20' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-blue-700">PPK</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                            <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="text-2xl font-bold text-blue-900">{{ $counts['ppk'] }}</span>
                        <span class="text-xs text-blue-600">TTD SPD & DPR</span>
                    </div>
                </a>
            </div>

            <!-- Main Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 text-gray-900 space-y-4">

                    <!-- Search and Tag Filter Bar -->
                    <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-200 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                        <!-- Quick Tag Filter Pills -->
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="text-xs font-semibold text-gray-500 mr-1">Status:</span>
                            <a href="{{ route('pegawais.index', array_merge(request()->except(['tag', 'page']))) }}"
                               class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition {{ empty($currentTag) ? 'bg-gray-900 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300' }}">
                                Semua
                            </a>
                            <a href="{{ route('pegawais.index', array_merge(request()->except(['tag', 'page']), ['tag' => 'aktif'])) }}"
                               class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition {{ $currentTag === 'aktif' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-white text-emerald-800 hover:bg-emerald-50 border border-emerald-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-emerald-500"></span>
                                Aktif
                            </a>
                            <a href="{{ route('pegawais.index', array_merge(request()->except(['tag', 'page']), ['tag' => 'tidak_aktif'])) }}"
                               class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition {{ $currentTag === 'tidak_aktif' ? 'bg-gray-700 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-gray-500"></span>
                                Tidak Aktif
                            </a>
                            <a href="{{ route('pegawais.index', array_merge(request()->except(['tag', 'page']), ['tag' => 'mutasi'])) }}"
                               class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition {{ $currentTag === 'mutasi' ? 'bg-amber-700 text-white shadow-sm' : 'bg-white text-amber-800 hover:bg-amber-50 border border-amber-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-amber-500"></span>
                                Mutasi
                            </a>
                            <a href="{{ route('pegawais.index', array_merge(request()->except(['tag', 'page']), ['tag' => 'pensiun'])) }}"
                               class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition {{ $currentTag === 'pensiun' ? 'bg-slate-700 text-white shadow-sm' : 'bg-white text-slate-800 hover:bg-slate-50 border border-slate-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-slate-500"></span>
                                Pensiun
                            </a>

                            <span class="text-xs font-semibold text-gray-400 mx-1">|</span>
                            <span class="text-xs font-semibold text-gray-500 mr-1">Peran:</span>

                            @foreach(\App\Models\Pegawai::AVAILABLE_TAGS as $tagKey => $tagInfo)
                                @if(($tagInfo['category'] ?? '') === 'peran')
                                    <a href="{{ route('pegawais.index', array_merge(request()->except(['tag', 'page']), ['tag' => $tagKey])) }}"
                                       class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium transition {{ $currentTag === $tagKey ? 'bg-gray-900 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $tagInfo['dot'] }}"></span>
                                        {{ $tagInfo['label'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <!-- Search Form -->
                        <form action="{{ route('pegawais.index') }}" method="GET" class="flex items-center gap-2">
                            @if(request('tag'))
                                <input type="hidden" name="tag" value="{{ request('tag') }}">
                            @endif
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP, nama, jabatan..." class="pl-9 pr-3 py-1.5 w-64 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm">
                            </div>
                            <button type="submit" class="px-3.5 py-1.5 bg-gray-900 hover:bg-black text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                Cari
                            </button>
                            @if(request('search') || request('tag'))
                                <a href="{{ route('pegawais.index') }}" class="px-3 py-1.5 text-xs text-gray-600 hover:text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition" title="Reset Semua Filter">
                                    Reset
                                </a>
                            @endif

                            <div class="flex items-center gap-1.5 pl-2 border-l border-gray-300">
                                <select name="per_page" id="per_page" onchange="this.form.submit()" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-xs py-1.5">
                                    <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10 / hal</option>
                                    <option value="20" {{ request('per_page', '20') == '20' ? 'selected' : '' }}>20 / hal</option>
                                    <option value="50" {{ request('per_page', '50') == '50' ? 'selected' : '' }}>50 / hal</option>
                                    <option value="100" {{ request('per_page', '100') == '100' ? 'selected' : '' }}>100 / hal</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Tabel Pegawai -->
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/75">
                                <tr>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pegawai</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gol. / Pangkat</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran Penandatangan</th>
                                    @if(auth()->user()->role !== 'user')
                                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    @else
                                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Detail</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($pegawais as $pegawai)
                                    @php
                                        $statusInfo = $pegawai->status_info;
                                    @endphp
                                    <tr class="hover:bg-blue-50/30 transition {{ ! $pegawai->is_active ? 'bg-gray-50/40 text-gray-500' : '' }}">
                                        <!-- Pegawai (Nama + NIP + Avatar Initials) -->
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full {{ $pegawai->is_active ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white' : 'bg-gray-300 text-gray-600' }} font-semibold text-sm flex items-center justify-center shrink-0 shadow-sm">
                                                    {{ strtoupper(substr($pegawai->nama, 0, 2)) }}
                                                </div>
                                                <div class="ml-3.5">
                                                    <div class="text-sm font-bold {{ $pegawai->is_active ? 'text-gray-900' : 'text-gray-600 line-through-none' }}">
                                                        {{ $pegawai->nama }}
                                                    </div>
                                                    <div class="text-xs font-mono text-gray-500 mt-0.5">NIP: {{ $pegawai->nip }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Status Keaktifan -->
                                        <td class="px-5 py-4 whitespace-nowrap text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusInfo['bg'] }} {{ $statusInfo['text'] }} border {{ $statusInfo['border'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $statusInfo['dot'] }}"></span>
                                                {{ $pegawai->status_label }}
                                            </span>
                                        </td>

                                        <!-- Jabatan -->
                                        <td class="px-5 py-4 text-sm text-gray-700 max-w-xs">
                                            <span class="line-clamp-2">{{ $pegawai->jabatan ?? '-' }}</span>
                                        </td>

                                        <!-- Golongan / Pangkat -->
                                        <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700">
                                            @if($pegawai->golongan)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                    {{ $pegawai->golongan }}
                                                </span>
                                            @endif
                                            @if($pegawai->pangkat)
                                                <span class="text-xs text-gray-600 ml-1">{{ $pegawai->pangkat }}</span>
                                            @endif
                                            @if(!$pegawai->golongan && !$pegawai->pangkat)
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <!-- Peran Penandatangan -->
                                        <td class="px-5 py-4 text-sm">
                                            @php
                                                $roleTags = array_filter($pegawai->tags ?? [], function($t) {
                                                    return in_array($t, \App\Models\Pegawai::ROLE_TAGS);
                                                });
                                            @endphp

                                            @if(count($roleTags) > 0)
                                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                                    @foreach($roleTags as $tag)
                                                        @if(isset(\App\Models\Pegawai::AVAILABLE_TAGS[$tag]))
                                                            @php $info = \App\Models\Pegawai::AVAILABLE_TAGS[$tag]; @endphp
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $info['bg'] }} {{ $info['text'] }} border {{ $info['border'] }}">
                                                                <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $info['dot'] }}"></span>
                                                                {{ $info['label'] }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Tidak ada peran</span>
                                            @endif
                                        </td>

                                        <!-- Aksi -->
                                        <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <!-- Tombol View Detail -->
                                                <button type="button" data-pegawai="{{ $pegawai->toJson() }}" onclick="showDetail(this)" class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail Pegawai">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                @if(auth()->user()->role !== 'user')
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('pegawais.edit', $pegawai->id) }}" class="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition" title="Edit Data & Tag">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('pegawais.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Peringatan: Menghapus data pegawai dapat merusak riwayat laporan terkait. Jika pegawai mutasi/pensiun, disarankan untuk mengubah status tag menjadi Tidak Aktif / Mutasi / Pensiun daripada menghapusnya.\n\nTetap hapus pegawai {{ $pegawai->nama }}?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition" title="Hapus Data">
                                                        <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-medium text-gray-900">Belum ada data pegawai yang sesuai</p>
                                                <p class="text-xs text-gray-500 mt-1">Coba ubah kata kunci pencarian atau reset filter di atas.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pt-2">
                        {{ $pegawais->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import CSV -->
    <div id="import-modal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form action="{{ route('pegawais.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white p-6 sm:p-8">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Import Data Pegawai (CSV)</h3>
                            <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 rounded-lg p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-3">
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Anda dapat mengimpor data pegawai secara massal menggunakan file CSV. Kolom yang didukung:
                                <code class="text-xs bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded font-mono">nip;nama;golongan;pangkat;jabatan;tags</code>
                            </p>
                            <p class="text-xs text-gray-500">
                                Contoh isi kolom tags: <span class="font-mono text-emerald-700 bg-emerald-50 px-1 py-0.5 rounded">aktif, ppk</span>, atau untuk yang pindah: <span class="font-mono text-amber-700 bg-amber-50 px-1 py-0.5 rounded">mutasi</span>, atau pensiun: <span class="font-mono text-slate-700 bg-slate-100 px-1 py-0.5 rounded">pensiun</span>.
                            </p>

                            <div class="pt-2">
                                <a href="{{ route('pegawais.template') }}" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800 underline">
                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Unduh Template CSV Disini
                                </a>
                            </div>

                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Pilih File CSV:</label>
                                <input type="file" name="file_csv" accept=".csv,.txt" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg p-2" />
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-2 border-t border-gray-100">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm transition">
                            Proses Import
                        </button>
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium border border-gray-300 rounded-lg transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal View Detail Pegawai -->
    <div id="detail-modal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="document.getElementById('detail-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                <div class="bg-white p-6 sm:p-7">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Detail Pegawai</h3>
                        <button type="button" onclick="document.getElementById('detail-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 rounded-lg p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Header Profil -->
                    <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                        <div id="detail-avatar" class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-base flex items-center justify-center shrink-0 shadow">
                            DP
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 id="detail-nama" class="font-bold text-base text-gray-900 leading-snug">Nama Pegawai</h4>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <p id="detail-nip" class="text-xs font-mono text-gray-500">NIP: -</p>
                                <span id="detail-status" class="inline-flex items-center px-2 py-0.2 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3.5 text-sm" id="detail-content">
                        <!-- Konten diisi JavaScript -->
                    </div>

                    <!-- Bagian Peran Penandatangan -->
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Peran Penandatangan Dokumen</p>
                        <div id="detail-roles" class="flex flex-wrap gap-1.5">
                            <!-- Role badges diisi JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3.5 flex justify-end border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('detail-modal').classList.add('hidden')" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium border border-gray-300 rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $tagDefinitionsJson = json_encode(\App\Models\Pegawai::AVAILABLE_TAGS);
    @endphp

    <script>
        const tagDefinitions = {!! $tagDefinitionsJson !!};

        function showDetail(buttonElement) {
            const data = JSON.parse(buttonElement.getAttribute('data-pegawai'));
            const content = document.getElementById('detail-content');
            const rolesContainer = document.getElementById('detail-roles');
            const statusEl = document.getElementById('detail-status');

            document.getElementById('detail-avatar').innerText = (data.nama || 'PG').substring(0, 2).toUpperCase();
            document.getElementById('detail-nama').innerText = data.nama || '-';
            document.getElementById('detail-nip').innerText = 'NIP: ' + (data.nip || '-');

            // Hitung status
            let statusKey = 'aktif';
            if (data.tags && Array.isArray(data.tags)) {
                if (data.tags.includes('pensiun')) {
                    statusKey = 'pensiun';
                } else if (data.tags.includes('mutasi')) {
                    statusKey = 'mutasi';
                } else if (data.tags.includes('tidak_aktif') || data.is_active === false) {
                    statusKey = 'tidak_aktif';
                }
            } else if (data.is_active === false) {
                statusKey = 'tidak_aktif';
            }

            const statusInfo = tagDefinitions[statusKey] || { label: 'Aktif', bg: 'bg-emerald-100', text: 'text-emerald-800' };
            statusEl.className = `inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ${statusInfo.bg} ${statusInfo.text}`;
            statusEl.innerText = statusInfo.label;

            const renderRow = (label, value) => {
                return `<div class="flex justify-between items-baseline py-1 border-b border-gray-50">
                            <span class="text-xs text-gray-500 uppercase font-semibold">${label}</span>
                            <span class="text-gray-900 font-medium text-right text-sm">${value || '-'}</span>
                        </div>`;
            };

            content.innerHTML = `
                ${renderRow('Jabatan', data.jabatan)}
                ${renderRow('Golongan', data.golongan)}
                ${renderRow('Pangkat', data.pangkat)}
                ${renderRow('Status Keaktifan', statusInfo.label + ' (' + statusInfo.desc + ')')}
            `;

            rolesContainer.innerHTML = '';
            const roleTags = (data.tags && Array.isArray(data.tags))
                ? data.tags.filter(t => ['kepala', 'plt', 'plh', 'ppk', 'bendahara', 'kasubbag'].includes(t))
                : [];

            if (roleTags.length > 0) {
                roleTags.forEach(t => {
                    const info = tagDefinitions[t];
                    if (info) {
                        rolesContainer.innerHTML += `
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${info.bg} ${info.text} border ${info.border}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 ${info.dot}"></span>
                                ${info.label} (${info.desc})
                            </span>`;
                    }
                });
            } else {
                rolesContainer.innerHTML = '<span class="text-xs text-gray-400 italic">Pegawai ini belum ditugaskan sebagai penandatangan dokumen.</span>';
            }

            document.getElementById('detail-modal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
