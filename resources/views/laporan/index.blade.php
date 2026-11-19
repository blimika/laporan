<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                    <div class="p-1.5 bg-blue-100 text-blue-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span>{{ __('Laporan AI (Perjalanan Dinas)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Kelola laporan hasil perjalanan dinas resmi berbasis Surat Tugas & SPD dengan asisten narasi cerdas AI (Gemini).
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('hanya-laporan.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition" title="Buka modul Hanya Laporan (Mandiri)">
                    <svg class="w-4 h-4 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Ke Hanya Laporan
                </a>
                <a href="{{ route('laporan.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm shadow-blue-600/30 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Input Laporan AI Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            <!-- 4 Summary KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Card 1: Total Laporan AI -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Laporan AI</p>
                            <h4 class="text-2xl font-black text-gray-900 mt-1 font-mono">{{ number_format($stats['total']) }}</h4>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Berbasis Surat Tugas</span>
                        <a href="{{ route('laporan.index') }}" class="font-semibold text-blue-600 hover:text-blue-800">Semua Data &rarr;</a>
                    </div>
                </div>

                <!-- Card 2: Bukti Foto Dokumentasi -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Bukti Dokumentasi</p>
                            <h4 class="text-2xl font-black text-emerald-600 mt-1 font-mono">
                                {{ number_format($stats['with_foto']) }}
                                <span class="text-xs font-normal text-gray-500">Laporan</span>
                            </h4>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-emerald-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                            {{ $stats['total'] > 0 ? round(($stats['with_foto'] / $stats['total']) * 100) : 0 }}% Berfoto
                        </span>
                        @if($stats['without_foto'] > 0)
                            <a href="{{ route('laporan.index', ['has_foto' => 'without_foto']) }}" class="text-amber-600 hover:text-amber-800 font-semibold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                {{ $stats['without_foto'] }} Tanpa Foto
                            </a>
                        @else
                            <span class="text-gray-400">Semua terdokumentasi</span>
                        @endif
                    </div>
                </div>

                <!-- Card 3: Validasi Geotag & GPS -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Validasi Geotag (GPS)</p>
                            <h4 class="text-2xl font-black text-rose-600 mt-1 font-mono">
                                {{ number_format($stats['with_geotag']) }}
                                <span class="text-xs font-normal text-gray-500">Tervalidasi</span>
                            </h4>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-xl text-rose-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-rose-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                            {{ $stats['total'] > 0 ? round(($stats['with_geotag'] / $stats['total']) * 100) : 0 }}% Ada Koordinat
                        </span>
                        <a href="{{ route('laporan.index', ['has_geotag' => 'with_geotag']) }}" class="font-semibold text-rose-600 hover:text-rose-800">
                            Lihat di Peta &rarr;
                        </a>
                    </div>
                </div>

                <!-- Card 4: Kategori Dominan -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div class="max-w-[70%]">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori Terbanyak</p>
                            <h4 class="text-base font-black text-purple-700 mt-1 truncate" title="{{ $stats['top_kategori']->kategori ?? '-' }}">
                                {{ $stats['top_kategori']->kategori ?? '-' }}
                            </h4>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Aktivitas Dominan</span>
                        <span class="font-semibold text-purple-600">{{ $stats['top_kategori'] ? $stats['top_kategori']->count . ' Dokumen' : '-' }}</span>
                    </div>
                </div>

            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-4">
                <form method="GET" action="{{ route('laporan.index') }}" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                        <!-- Search Box -->
                        <div class="lg:col-span-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari No ST, SPD, Pegawai, Lokasi, Narasumber..."
                                class="w-full pl-9 pr-8 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                            />
                            @if(request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>

                        <!-- Filter Kategori -->
                        <div class="lg:col-span-3">
                            <select name="kategori" class="w-full py-2 px-3 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                        {{ $kat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Foto Dokumentasi -->
                        <div class="lg:col-span-2">
                            <select name="has_foto" class="w-full py-2 px-3 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                                <option value="">Semua Foto</option>
                                <option value="with_foto" {{ request('has_foto') == 'with_foto' ? 'selected' : '' }}>📷 Ada Foto ({{ $stats['with_foto'] }})</option>
                                <option value="without_foto" {{ request('has_foto') == 'without_foto' ? 'selected' : '' }}>Tanpa Foto ({{ $stats['without_foto'] }})</option>
                            </select>
                        </div>

                        <!-- Filter Geotag GPS -->
                        <div class="lg:col-span-1">
                            <select name="has_geotag" class="w-full py-2 px-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                                <option value="">Geotag</option>
                                <option value="with_geotag" {{ request('has_geotag') == 'with_geotag' ? 'selected' : '' }}>Ada GPS</option>
                                <option value="without_geotag" {{ request('has_geotag') == 'without_geotag' ? 'selected' : '' }}>Tanpa GPS</option>
                            </select>
                        </div>

                        <!-- Per Page & Actions -->
                        <div class="lg:col-span-2 flex items-center gap-2">
                            <select name="per_page" class="w-20 py-2 px-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 baris</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris</option>
                            </select>

                            <button type="submit" class="flex-1 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                Filter
                            </button>

                            @if(request('search') || request('kategori') || request('has_foto') || request('has_geotag') || request('per_page') != 10)
                                <a href="{{ route('laporan.index') }}" class="px-2 py-2 text-xs text-gray-500 hover:text-rose-600 border border-gray-200 rounded-lg hover:border-rose-200 hover:bg-rose-50 transition" title="Reset Semua Filter">
                                    Reset
                                </a>
                            @endif
                        </div>

                    </div>

                    <!-- Active Filter Badges -->
                    @if(request('search') || request('kategori') || request('has_foto') || request('has_geotag'))
                        <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-gray-100 text-xs">
                            <span class="text-gray-400 font-medium mr-1">Filter aktif:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                    Keyword: "{{ request('search') }}"
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-blue-400 hover:text-blue-700">&times;</a>
                                </span>
                            @endif
                            @if(request('kategori'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                                    Kategori: {{ request('kategori') }}
                                    <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" class="text-purple-400 hover:text-purple-700">&times;</a>
                                </span>
                            @endif
                            @if(request('has_foto'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Foto: {{ request('has_foto') == 'with_foto' ? 'Ada Foto' : 'Tanpa Foto' }}
                                    <a href="{{ request()->fullUrlWithQuery(['has_foto' => null]) }}" class="text-emerald-400 hover:text-emerald-700">&times;</a>
                                </span>
                            @endif
                            @if(request('has_geotag'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                    Geotag: {{ request('has_geotag') == 'with_geotag' ? 'Ada Koordinat' : 'Tanpa GPS' }}
                                    <a href="{{ request()->fullUrlWithQuery(['has_geotag' => null]) }}" class="text-rose-400 hover:text-rose-700">&times;</a>
                                </span>
                            @endif
                        </div>
                    @endif
                </form>
            </div>

            <!-- Main Table Card -->
            <div class="bg-white rounded-xl shadow-xs border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50/75">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'tgl_perjalanan', 'direction' => ($sort == 'tgl_perjalanan' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center gap-1 hover:text-gray-900">
                                        <span>Waktu & Tanggal</span>
                                        <span class="text-gray-400 group-hover:text-gray-700">
                                            @if($sort == 'tgl_perjalanan')
                                                <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                                </svg>
                                            @else
                                                <svg class="h-3.5 w-3.5 opacity-40 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'nomor_surat', 'direction' => ($sort == 'nomor_surat' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center gap-1 hover:text-gray-900">
                                        <span>Nomor ST & SPD</span>
                                        <span class="text-gray-400 group-hover:text-gray-700">
                                            @if($sort == 'nomor_surat' || $sort == 'nomor_st')
                                                <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                                </svg>
                                            @else
                                                <svg class="h-3.5 w-3.5 opacity-40 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_pegawai', 'direction' => ($sort == 'nama_pegawai' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center gap-1 hover:text-gray-900">
                                        <span>Pegawai Pelaksana</span>
                                        <span class="text-gray-400 group-hover:text-gray-700">
                                            @if($sort == 'nama_pegawai')
                                                <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                                </svg>
                                            @else
                                                <svg class="h-3.5 w-3.5 opacity-40 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'lokasi_perjalanan', 'direction' => ($sort == 'lokasi_perjalanan' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center gap-1 hover:text-gray-900">
                                        <span>Lokasi & Maksud Perjalanan</span>
                                        <span class="text-gray-400 group-hover:text-gray-700">
                                            @if($sort == 'lokasi_perjalanan' || $sort == 'tujuan_perjalanan')
                                                <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                                </svg>
                                            @else
                                                <svg class="h-3.5 w-3.5 opacity-40 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Kategori & Bukti Foto
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Pembuat
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($laporans as $laporan)
                                @php
                                    $appUrl = config('app.url');
                                    $baseUrl = rtrim($appUrl, '/');
                                    $dokCollection = $laporan->dokumentasi->map(fn($d) => [
                                        'file_path' => asset($d->file_path),
                                        'keterangan' => $d->keterangan_foto ?? 'Dokumentasi ' . $laporan->kategori,
                                    ]);

                                    $modalData = [
                                        'id' => $laporan->id,
                                        'nomor_st' => $laporan->suratTugas->nomor_surat ?? '-',
                                        'nomor_spd' => $laporan->suratTugas->spd?->nomor_spd ?: '-',
                                        'pegawai' => [
                                            'nama' => $laporan->suratTugas->pegawai->nama ?? '-',
                                            'nip' => $laporan->suratTugas->pegawai->nip ?? '-',
                                            'jabatan' => $laporan->suratTugas->pegawai->jabatan ?? '-',
                                            'golongan' => $laporan->suratTugas->pegawai->golongan ?? '-',
                                        ],
                                        'tgl_laporan' => $laporan->tgl_laporan ? $laporan->tgl_laporan->format('d/m/Y') : '-',
                                        'tgl_perjalanan' => $laporan->tgl_perjalanan ? $laporan->tgl_perjalanan->format('d/m/Y') : ($laporan->suratTugas->tgl_berangkat ? $laporan->suratTugas->tgl_berangkat->format('d/m/Y') : '-'),
                                        'lokasi_perjalanan' => $laporan->lokasi_perjalanan,
                                        'tujuan_perjalanan' => $laporan->tujuan_perjalanan,
                                        'pegawai_ditemui' => $laporan->pegawai_ditemui,
                                        'kategori' => $laporan->kategori,
                                        'kendala_ditemui' => $laporan->kendala_ditemui,
                                        'hasil_perjalanan' => $laporan->hasil_perjalanan,
                                        'is_stamped' => $laporan->is_stamped,
                                        'stamp_koordinat' => $laporan->stamp_koordinat,
                                        'stamp_datetime' => $laporan->stamp_datetime ? \Carbon\Carbon::parse($laporan->stamp_datetime)->format('d/m/Y H:i') : null,
                                        'dokumentasi' => $dokCollection->toArray(),
                                        'export_pdf_url' => route('export.laporan', $laporan->id),
                                        'export_word_url' => route('export.laporan.word', $laporan->id),
                                        'copy_url' => route('hanya-laporan.create', ['copy_from_laporan' => $laporan->id]),
                                        'edit_url' => route('laporan.edit', $laporan->id),
                                        'pembuat' => ($laporan->user ?? $laporan->suratTugas?->user)?->username ?? (($laporan->user ?? $laporan->suratTugas?->user)?->name ?? '-'),
                                    ];
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition-colors">

                                    <!-- Column 1: Waktu & Tanggal -->
                                    <td class="px-5 py-4 whitespace-nowrap text-xs">
                                        <div class="font-semibold text-gray-900 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>{{ $laporan->tgl_perjalanan ? $laporan->tgl_perjalanan->format('d/m/Y') : '-' }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-400 mt-1">
                                            Laporan: {{ $laporan->tgl_laporan ? $laporan->tgl_laporan->format('d/m/Y') : '-' }}
                                        </div>
                                    </td>

                                    <!-- Column 2: Nomor ST & SPD -->
                                    <td class="px-5 py-4 text-xs">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-gray-900 font-mono tracking-tight">{{ $laporan->suratTugas->nomor_surat ?? '-' }}</span>
                                            @if($laporan->suratTugas)
                                                <button type="button" onclick="navigator.clipboard.writeText('{{ $laporan->suratTugas->nomor_surat }}'); alert('Nomor ST disalin!');" class="text-gray-300 hover:text-blue-600 transition" title="Salin Nomor ST">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                </button>
                                            @endif
                                        </div>

                                        <div class="mt-1">
                                            @if($laporan->suratTugas?->spd)
                                                <span class="inline-flex items-center gap-1 text-[11px] font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                    SPD: {{ $laporan->suratTugas->spd->nomor_spd }}
                                                </span>
                                            @else
                                                <span class="text-[11px] text-gray-400 italic">SPD: -</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Column 3: Pegawai Pelaksana -->
                                    <td class="px-5 py-4 text-xs">
                                        <div class="flex items-center gap-2.5">
                                            @php
                                                $nama = $laporan->suratTugas->pegawai->nama ?? 'Pegawai';
                                                $initials = collect(explode(' ', $nama))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                                            @endphp
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                                {{ strtoupper($initials) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 leading-snug">{{ $laporan->suratTugas->pegawai->nama ?? '-' }}</div>
                                                <div class="text-[11px] text-gray-500 font-mono mt-0.5">NIP: {{ $laporan->suratTugas->pegawai->nip ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Column 4: Lokasi & Maksud Perjalanan -->
                                    <td class="px-5 py-4 text-xs max-w-xs">
                                        <div class="flex items-start gap-1 font-semibold text-gray-900">
                                            <svg class="w-3.5 h-3.5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="truncate" title="{{ $laporan->lokasi_perjalanan }}">{{ $laporan->lokasi_perjalanan }}</span>

                                            @if($laporan->stamp_koordinat)
                                                <a href="https://maps.google.com/?q={{ $laporan->stamp_koordinat }}" target="_blank" class="text-rose-500 hover:text-rose-700 ml-1 inline-flex shrink-0" title="Buka Koordinat di Google Maps ({{ $laporan->stamp_koordinat }})">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            @endif
                                        </div>

                                        <div class="text-gray-500 text-[11px] mt-1 line-clamp-2 leading-relaxed" title="{{ $laporan->tujuan_perjalanan }}">
                                            {{ $laporan->tujuan_perjalanan }}
                                        </div>

                                        @if($laporan->pegawai_ditemui)
                                            <div class="text-[10px] text-gray-400 mt-1">
                                                Narasumber: <span class="font-medium text-gray-600">{{ $laporan->pegawai_ditemui }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Column 5: Kategori & Bukti Foto -->
                                    <td class="px-5 py-4 text-xs whitespace-nowrap">
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                                {{ $laporan->kategori }}
                                            </span>
                                        </div>

                                        <div class="mt-2 flex items-center gap-2">
                                            @if($laporan->dokumentasi->count() > 0)
                                                <button
                                                    type="button"
                                                    onclick="openPhotoLightbox({{ json_encode($modalData) }})"
                                                    class="inline-flex items-center gap-1.5 group cursor-pointer"
                                                    title="Klik untuk membuka Galeri Foto ({{ $laporan->dokumentasi->count() }} Foto)"
                                                >
                                                    <div class="flex -space-x-2 overflow-hidden">
                                                        @foreach($laporan->dokumentasi->take(3) as $dok)
                                                            <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white object-cover group-hover:scale-105 transition-transform" src="{{ asset($dok->file_path) }}" alt="Foto">
                                                        @endforeach
                                                    </div>
                                                    <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200 group-hover:bg-emerald-100 transition">
                                                        {{ $laporan->dokumentasi->count() }} Foto
                                                    </span>
                                                </button>
                                            @else
                                                <span class="text-[11px] text-gray-400 italic">Tanpa Foto</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Column 6: Pembuat -->
                                    <td class="px-5 py-4 whitespace-nowrap text-center text-xs text-gray-500">
                                        @php
                                            $pembuatUser = $laporan->user ?? $laporan->suratTugas?->user;
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-700 font-mono">
                                            {{ $pembuatUser ? ($pembuatUser->username ?? $pembuatUser->name) : '-' }}
                                        </span>
                                    </td>

                                    <!-- Column 7: Aksi -->
                                    <td class="px-5 py-4 whitespace-nowrap text-center text-xs">
                                        <div class="flex items-center justify-center gap-1" x-data="{ open: false }">

                                            <!-- Action 1: Lihat Detail Modal -->
                                            <button
                                                type="button"
                                                onclick="openDetailModal({{ json_encode($modalData) }})"
                                                class="p-1.5 rounded-lg text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition"
                                                title="Lihat Detail Lengkap Laporan"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            <!-- Action 2: Cetak PDF -->
                                            <a
                                                href="{{ route('export.laporan', $laporan->id) }}"
                                                target="_blank"
                                                class="p-1.5 rounded-lg text-rose-600 hover:text-rose-900 hover:bg-rose-50 transition"
                                                title="Cetak Laporan Resmi (PDF)"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                            </a>

                                            <!-- Action 3: Download Word -->
                                            <a
                                                href="{{ route('export.laporan.word', $laporan->id) }}"
                                                class="p-1.5 rounded-lg text-blue-700 hover:text-blue-900 hover:bg-blue-50 transition"
                                                title="Download Laporan Format Word (.docx)"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                            </a>

                                            <!-- Action 4: Salin ke Hanya Laporan -->
                                            <a
                                                href="{{ route('hanya-laporan.create', ['copy_from_laporan' => $laporan->id]) }}"
                                                class="p-1.5 rounded-lg text-amber-600 hover:text-amber-900 hover:bg-amber-50 transition"
                                                title="Salin Data ke Modul Hanya Laporan"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </a>

                                            <!-- Dropdown Options -->
                                            <div class="relative">
                                                <button
                                                    @click="open = !open"
                                                    type="button"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition"
                                                    title="Menu Lainnya"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                    </svg>
                                                </button>

                                                <div
                                                    x-show="open"
                                                    @click.outside="open = false"
                                                    x-transition
                                                    class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-20 text-left"
                                                    style="display: none;"
                                                >
                                                    <div class="py-1">
                                                        <a href="{{ route('laporan.edit', $laporan->id) }}" class="group flex items-center px-4 py-2 text-xs text-blue-700 hover:bg-blue-50">
                                                            <svg class="mr-3 h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                            Edit Laporan
                                                        </a>
                                                        @if($laporan->suratTugas)
                                                            <a href="{{ route('surat-tugas.index', ['search' => $laporan->suratTugas->nomor_surat]) }}" class="group flex items-center px-4 py-2 text-xs text-purple-700 hover:bg-purple-50">
                                                                <svg class="mr-3 h-4 w-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                Buka Surat Tugas
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <div class="py-1">
                                                        <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('Peringatan: Yakin ingin menghapus laporan ini beserta seluruh file dokumentasinya?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full group flex items-center px-4 py-2 text-xs text-rose-600 hover:bg-rose-50">
                                                                <svg class="mr-3 h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                Hapus Laporan
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="max-w-sm mx-auto flex flex-col items-center">
                                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                            </div>
                                            <h5 class="text-sm font-bold text-gray-900 mb-1">Tidak Ada Data Laporan AI</h5>
                                            <p class="text-xs text-gray-500 mb-4 text-center">
                                                @if(request('search') || request('kategori') || request('has_foto') || request('has_geotag'))
                                                    Tidak ada laporan yang sesuai dengan filter pencarian. Silakan reset filter.
                                                @else
                                                    Belum ada Laporan AI yang diterbitkan pada konteks Satker & Tahun ini.
                                                @endif
                                            </p>
                                            @if(request('search') || request('kategori') || request('has_foto') || request('has_geotag'))
                                                <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                                                    Reset Filter
                                                </a>
                                            @else
                                                <a href="{{ route('laporan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                                    + Buat Laporan AI Pertama
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if($laporans->hasPages() || $laporans->total() > 0)
                    <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
                        <div>
                            Menampilkan <span class="font-semibold text-gray-800">{{ $laporans->firstItem() ?? 0 }}</span> s/d <span class="font-semibold text-gray-800">{{ $laporans->lastItem() ?? 0 }}</span> dari total <span class="font-semibold text-gray-800">{{ $laporans->total() }}</span> Laporan AI
                        </div>
                        <div>
                            {{ $laporans->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Enhanced Detail Modal -->
    <div id="detail-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeDetailModal()"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-3xl sm:w-full border border-gray-100">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-5 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-white/20 text-white">
                                    Detail Laporan Perjalanan (AI)
                                </span>
                                <span id="modal-badge-kategori" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-purple-500/30 text-purple-100 border border-purple-400/30"></span>
                                <span id="modal-badge-foto" class="text-xs font-semibold px-2 py-0.5 rounded-full"></span>
                            </div>
                            <h3 class="text-lg font-bold mt-2 text-white flex items-center gap-2">
                                <span id="modal-nomor-st">-</span>
                                <button type="button" id="modal-copy-st-btn" class="text-white/70 hover:text-white transition" title="Salin Nomor ST">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </h3>
                            <p class="text-xs text-white/80 mt-1" id="modal-header-sub">Pelaksana: -</p>
                        </div>
                        <button type="button" onclick="closeDetailModal()" class="text-white/70 hover:text-white rounded-lg p-1 hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-5 text-sm">

                    <!-- Section 1: Informasi Penugasan & Lokasi -->
                    <div class="bg-gray-50/70 rounded-xl p-4 border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-gray-400">Tempat / Lokasi Perjalanan:</span>
                                <p class="text-gray-900 font-bold text-sm mt-0.5 flex items-center gap-1.5" id="modal-lokasi">-</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Jadwal Perjalanan & Laporan:</span>
                                <p class="text-gray-900 font-semibold mt-0.5" id="modal-jadwal">-</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Nomor SPD:</span>
                                <p class="text-gray-900 font-mono font-medium mt-0.5" id="modal-spd">-</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Narasumber / Pegawai yang Ditemui:</span>
                                <p class="text-gray-900 font-medium mt-0.5" id="modal-narasumber">-</p>
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <span class="text-gray-400">Maksud & Tujuan Perjalanan:</span>
                                <p class="text-gray-800 font-medium leading-relaxed mt-0.5" id="modal-tujuan">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Kendala (if any) -->
                    <div id="modal-kendala-card" class="bg-amber-50/60 rounded-xl p-4 border border-amber-200/60 hidden">
                        <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Kendala / Masalah yang Dihadapi
                        </h4>
                        <p class="text-xs text-amber-900 leading-relaxed font-sans" id="modal-kendala">-</p>
                    </div>

                    <!-- Section 3: Narasi Hasil Perjalanan Dinas (AI) -->
                    <div class="bg-blue-50/30 rounded-xl p-4 border border-blue-100/60">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Hasil Perjalanan Dinas (Narasi AI)
                            </h4>
                            <button type="button" id="modal-copy-hasil-btn" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Salin Narasi
                            </button>
                        </div>
                        <div class="p-3.5 bg-white rounded-lg border border-gray-200 text-xs text-gray-800 font-sans leading-relaxed whitespace-pre-wrap max-h-60 overflow-y-auto" id="modal-hasil">
                            -
                        </div>
                    </div>

                    <!-- Section 4: Galeri Foto Dokumentasi -->
                    <div class="bg-gray-50/70 rounded-xl p-4 border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Foto Dokumentasi Lapangan
                        </h4>
                        <div id="modal-foto-gallery" class="flex flex-wrap gap-2.5">
                            <!-- Populated via Javascript -->
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2" id="modal-footer-left">
                        <!-- Populated via Javascript -->
                    </div>
                    <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-xs">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Photo Lightbox -->
    <div id="foto-lightbox-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-foto-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/85 backdrop-blur-xs transition-opacity" onclick="closePhotoLightbox()"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
            <div class="relative bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all max-w-4xl w-full border border-slate-800 text-white">

                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
                    <div>
                        <h4 class="text-sm font-bold text-white flex items-center gap-2" id="lightbox-title">
                            Galeri Foto Dokumentasi
                        </h4>
                        <p class="text-xs text-slate-400 mt-0.5" id="lightbox-sub">-</p>
                    </div>
                    <button type="button" onclick="closePhotoLightbox()" class="text-slate-400 hover:text-white p-1 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 max-h-[80vh] overflow-y-auto">
                    <div id="lightbox-gallery-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Populated via JS -->
                    </div>
                </div>

                <div class="bg-slate-950 px-6 py-3 border-t border-slate-800 flex justify-end">
                    <button type="button" onclick="closePhotoLightbox()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-semibold transition">
                        Tutup Viewer
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Javascript Controller -->
    <script>
        function openDetailModal(data) {
            // Header
            document.getElementById('modal-nomor-st').textContent = data.nomor_st || '-';
            document.getElementById('modal-header-sub').textContent = 'Pelaksana: ' + (data.pegawai?.nama || '-') + ' (NIP: ' + (data.pegawai?.nip || '-') + ') • Pembuat: ' + (data.pembuat || '-');

            // Copy ST button
            document.getElementById('modal-copy-st-btn').onclick = function() {
                navigator.clipboard.writeText(data.nomor_st);
                alert('Nomor Surat Tugas berhasil disalin!');
            };

            // Badges
            document.getElementById('modal-badge-kategori').textContent = data.kategori || 'Umum';

            const badgeFoto = document.getElementById('modal-badge-foto');
            if (data.dokumentasi && data.dokumentasi.length > 0) {
                badgeFoto.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/30 text-emerald-100 border border-emerald-400/30';
                badgeFoto.textContent = data.dokumentasi.length + ' Foto';
            } else {
                badgeFoto.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-500/30 text-slate-200 border border-slate-400/30';
                badgeFoto.textContent = 'Tanpa Foto';
            }

            // Section 1: Lokasi & Jadwal
            let mapLink = '';
            if (data.stamp_koordinat) {
                mapLink = `<a href="https://maps.google.com/?q=${data.stamp_koordinat}" target="_blank" class="text-rose-500 hover:text-rose-700 inline-flex items-center gap-0.5 text-xs font-semibold" title="Buka Google Maps">
                    (Maps &rarr;)
                </a>`;
            }
            document.getElementById('modal-lokasi').innerHTML = `${data.lokasi_perjalanan || '-'} ${mapLink}`;
            document.getElementById('modal-jadwal').textContent = `Perjalanan: ${data.tgl_perjalanan || '-'} • Laporan: ${data.tgl_laporan || '-'}`;
            document.getElementById('modal-spd').textContent = data.nomor_spd || '-';
            document.getElementById('modal-narasumber').textContent = data.pegawai_ditemui || '-';
            document.getElementById('modal-tujuan').textContent = data.tujuan_perjalanan || '-';

            // Section 2: Kendala
            const kendalaCard = document.getElementById('modal-kendala-card');
            if (data.kendala_ditemui && data.kendala_ditemui.trim() !== '') {
                document.getElementById('modal-kendala').textContent = data.kendala_ditemui;
                kendalaCard.classList.remove('hidden');
            } else {
                kendalaCard.classList.add('hidden');
            }

            // Section 3: Hasil AI
            document.getElementById('modal-hasil').textContent = data.hasil_perjalanan || '-';
            document.getElementById('modal-copy-hasil-btn').onclick = function() {
                navigator.clipboard.writeText(data.hasil_perjalanan || '');
                alert('Narasi hasil laporan perjalanan dinas berhasil disalin!');
            };

            // Section 4: Foto Gallery
            const galleryEl = document.getElementById('modal-foto-gallery');
            if (data.dokumentasi && data.dokumentasi.length > 0) {
                let fotosHtml = '';
                data.dokumentasi.forEach(dok => {
                    fotosHtml += `
                        <div class="relative group w-28 h-28 rounded-xl overflow-hidden border border-gray-200 shadow-xs bg-gray-100">
                            <a href="${dok.file_path}" target="_blank" class="block w-full h-full">
                                <img src="${dok.file_path}" alt="Dokumentasi" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            </a>
                        </div>
                    `;
                });
                galleryEl.innerHTML = fotosHtml;
            } else {
                galleryEl.innerHTML = '<p class="text-xs text-gray-400 italic">Tidak ada foto dokumentasi yang diunggah.</p>';
            }

            // Footer Left Action Buttons
            const footerLeft = document.getElementById('modal-footer-left');
            footerLeft.innerHTML = `
                <a href="${data.export_pdf_url}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak PDF
                </a>
                <a href="${data.export_word_url}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Word
                </a>
                <a href="${data.copy_url}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-semibold transition" title="Salin ke modul Hanya Laporan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Salin ke Hanya Laporan
                </a>
            `;

            document.getElementById('detail-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openPhotoLightbox(data) {
            document.getElementById('lightbox-title').textContent = 'Dokumentasi: ' + (data.nomor_st || 'Laporan');
            document.getElementById('lightbox-sub').textContent = (data.lokasi_perjalanan || '-') + ' • ' + (data.tgl_perjalanan || '-');

            const container = document.getElementById('lightbox-gallery-container');
            container.innerHTML = '';

            let stampHtml = '';
            if (data.is_stamped) {
                const tujuan = data.tujuan_perjalanan || '-';
                const dt = data.stamp_datetime || '-';
                const koord = data.stamp_koordinat || '-';
                stampHtml = `
                    <div class="absolute bottom-3 left-3 text-white text-[10px] sm:text-xs leading-tight font-bold border-l-4 border-yellow-400 pl-2 pointer-events-none drop-shadow-md" style="text-shadow: 1px 1px 3px black, 0 0 2px black;">
                        ${tujuan}<br>${dt}<br>${koord}
                    </div>
                `;
            }

            if (data.dokumentasi && data.dokumentasi.length > 0) {
                data.dokumentasi.forEach(dok => {
                    container.innerHTML += `
                        <div class="relative bg-black rounded-xl overflow-hidden border border-slate-800 flex flex-col">
                            <div class="relative aspect-video flex items-center justify-center bg-slate-950">
                                <img src="${dok.file_path}" alt="Foto" class="max-h-[60vh] w-auto object-contain">
                                ${stampHtml}
                            </div>
                            <div class="p-3 bg-slate-900 border-t border-slate-800 flex items-center justify-between text-xs text-slate-300">
                                <span class="truncate">${dok.keterangan || 'Foto Dokumentasi'}</span>
                                <a href="${dok.file_path}" target="_blank" class="text-blue-400 hover:text-blue-300 font-medium shrink-0 ml-2">
                                    Buka Penuh &rarr;
                                </a>
                            </div>
                        </div>
                    `;
                });
            }

            document.getElementById('foto-lightbox-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closePhotoLightbox() {
            document.getElementById('foto-lightbox-modal').classList.add('hidden');
            if (document.getElementById('detail-modal').classList.contains('hidden')) {
                document.body.classList.remove('overflow-hidden');
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetailModal();
                closePhotoLightbox();
            }
        });
    </script>
</x-app-layout>
