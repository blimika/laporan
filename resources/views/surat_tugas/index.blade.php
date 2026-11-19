<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                    <div class="p-1.5 bg-purple-100 text-purple-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span>{{ __('Daftar Surat Tugas & SPD') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Kelola administrasi Surat Tugas, penerbitan Surat Perjalanan Dinas (SPD), rincian pengeluaran riil (DPR), dan laporan AI.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('surat-tugas.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm shadow-purple-600/30 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Surat Tugas Baru
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

                <!-- Card 1: Total Surat Tugas -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Surat Tugas</p>
                            <h4 class="text-2xl font-black text-gray-900 mt-1 font-mono">{{ number_format($stats['total_st']) }}</h4>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Penugasan Aktif</span>
                        <a href="{{ route('surat-tugas.index') }}" class="font-semibold text-purple-600 hover:text-purple-800">Semua Data &rarr;</a>
                    </div>
                </div>

                <!-- Card 2: Status Penerbitan SPD -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Penerbitan SPD</p>
                            <h4 class="text-2xl font-black text-emerald-600 mt-1 font-mono">
                                {{ number_format($stats['st_with_spd']) }}
                                <span class="text-xs font-normal text-gray-500">Terbit</span>
                            </h4>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-emerald-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                            {{ $stats['total_st'] > 0 ? round(($stats['st_with_spd'] / $stats['total_st']) * 100) : 0 }}% Lengkap
                        </span>
                        @if($stats['st_without_spd'] > 0)
                            <a href="{{ route('surat-tugas.index', ['status_spd' => 'without_spd']) }}" class="text-amber-600 hover:text-amber-800 font-semibold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                {{ $stats['st_without_spd'] }} Belum Ada SPD
                            </a>
                        @else
                            <span class="text-gray-400">Semua ber-SPD</span>
                        @endif
                    </div>
                </div>

                <!-- Card 3: Status Laporan Perjalanan (AI) -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan AI Selesai</p>
                            <h4 class="text-2xl font-black text-blue-600 mt-1 font-mono">
                                {{ number_format($stats['st_with_laporan']) }}
                                <span class="text-xs font-normal text-gray-500">Laporan</span>
                            </h4>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-blue-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                            {{ $stats['total_st'] > 0 ? round(($stats['st_with_laporan'] / $stats['total_st']) * 100) : 0 }}% Terlapor
                        </span>
                        @if($stats['st_without_laporan'] > 0)
                            <a href="{{ route('surat-tugas.index', ['status_laporan' => 'without_laporan']) }}" class="text-rose-600 hover:text-rose-800 font-semibold bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                {{ $stats['st_without_laporan'] }} Belum Lapor
                            </a>
                        @else
                            <span class="text-emerald-600 font-medium">100% Selesai</span>
                        @endif
                    </div>
                </div>

                <!-- Card 4: Total Nilai DPR (Biaya Riil) -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengeluaran Riil (DPR)</p>
                            <h4 class="text-xl font-black text-amber-600 mt-1 font-mono truncate" title="Rp {{ number_format($stats['total_dpr'], 0, ',', '.') }}">
                                Rp {{ number_format($stats['total_dpr'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Realisasi SPD</span>
                        <span class="font-semibold text-gray-700">{{ $stats['st_with_spd'] }} Dokumen</span>
                    </div>
                </div>

            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-4">
                <form method="GET" action="{{ route('surat-tugas.index') }}" class="space-y-3">
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
                                placeholder="Cari No ST, No SPD, Pegawai, NIP, Tujuan, MAK..."
                                class="w-full pl-9 pr-8 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400"
                            />
                            @if(request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>

                        <!-- Filter Status SPD -->
                        <div class="lg:col-span-2">
                            <select name="status_spd" class="w-full py-2 px-3 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" onchange="this.form.submit()">
                                <option value="">Semua Status SPD</option>
                                <option value="with_spd" {{ request('status_spd') == 'with_spd' ? 'selected' : '' }}>✓ SPD Terbit ({{ $stats['st_with_spd'] }})</option>
                                <option value="without_spd" {{ request('status_spd') == 'without_spd' ? 'selected' : '' }}>⏳ Belum Ada SPD ({{ $stats['st_without_spd'] }})</option>
                            </select>
                        </div>

                        <!-- Filter Status Laporan -->
                        <div class="lg:col-span-2">
                            <select name="status_laporan" class="w-full py-2 px-3 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" onchange="this.form.submit()">
                                <option value="">Semua Status Laporan</option>
                                <option value="with_laporan" {{ request('status_laporan') == 'with_laporan' ? 'selected' : '' }}>✓ Laporan Selesai ({{ $stats['st_with_laporan'] }})</option>
                                <option value="without_laporan" {{ request('status_laporan') == 'without_laporan' ? 'selected' : '' }}>⏳ Belum Dilaporkan ({{ $stats['st_without_laporan'] }})</option>
                            </select>
                        </div>

                        <!-- Filter MAK Anggaran -->
                        <div class="lg:col-span-2">
                            <select name="anggaran_id" class="w-full py-2 px-3 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" onchange="this.form.submit()">
                                <option value="">Semua MAK Anggaran</option>
                                @foreach($anggaranList as $ang)
                                    <option value="{{ $ang->id }}" {{ request('anggaran_id') == $ang->id ? 'selected' : '' }}>
                                        {{ $ang->mak }} - {{ Str::limit($ang->akun_uraian ?? $ang->suboutput_uraian, 35) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Per Page & Submit -->
                        <div class="lg:col-span-2 flex items-center gap-2">
                            <select name="per_page" class="w-24 py-2 px-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 baris</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris</option>
                            </select>

                            <button type="submit" class="flex-1 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                Filter
                            </button>

                            @if(request('search') || request('status_spd') || request('status_laporan') || request('anggaran_id') || request('per_page') != 10)
                                <a href="{{ route('surat-tugas.index') }}" class="px-2.5 py-2 text-xs text-gray-500 hover:text-rose-600 border border-gray-200 rounded-lg hover:border-rose-200 hover:bg-rose-50 transition" title="Reset Semua Filter">
                                    Reset
                                </a>
                            @endif
                        </div>

                    </div>

                    <!-- Active Filter Pills (if any) -->
                    @if(request('search') || request('status_spd') || request('status_laporan') || request('anggaran_id'))
                        <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-gray-100 text-xs">
                            <span class="text-gray-400 font-medium mr-1">Filter aktif:</span>
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                                    Keyword: "{{ request('search') }}"
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-purple-400 hover:text-purple-700">&times;</a>
                                </span>
                            @endif
                            @if(request('status_spd'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    SPD: {{ request('status_spd') == 'with_spd' ? 'Sudah Terbit' : 'Belum Ada' }}
                                    <a href="{{ request()->fullUrlWithQuery(['status_spd' => null]) }}" class="text-emerald-400 hover:text-emerald-700">&times;</a>
                                </span>
                            @endif
                            @if(request('status_laporan'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                    Laporan: {{ request('status_laporan') == 'with_laporan' ? 'Selesai' : 'Belum Lapor' }}
                                    <a href="{{ request()->fullUrlWithQuery(['status_laporan' => null]) }}" class="text-blue-400 hover:text-blue-700">&times;</a>
                                </span>
                            @endif
                            @if(request('anggaran_id'))
                                @php $selectedAng = $anggaranList->firstWhere('id', request('anggaran_id')); @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    MAK: {{ $selectedAng ? $selectedAng->mak : request('anggaran_id') }}
                                    <a href="{{ request()->fullUrlWithQuery(['anggaran_id' => null]) }}" class="text-indigo-400 hover:text-indigo-700">&times;</a>
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
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'tgl_berangkat', 'direction' => ($sort == 'tgl_berangkat' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center gap-1 hover:text-gray-900">
                                        <span>Waktu & Jadwal</span>
                                        <span class="text-gray-400 group-hover:text-gray-700">
                                            @if($sort == 'tgl_berangkat')
                                                <svg class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                            @if($sort == 'nomor_surat')
                                                <svg class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                    Pegawai Pelaksana
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'tujuan', 'direction' => ($sort == 'tujuan' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="group inline-flex items-center gap-1 hover:text-gray-900">
                                        <span>Tujuan & Maksud Tugas</span>
                                        <span class="text-gray-400 group-hover:text-gray-700">
                                            @if($sort == 'tujuan')
                                                <svg class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                    Pembebanan Anggaran (MAK)
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Laporan AI
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($suratTugasList as $st)
                                @php
                                    $durasi = ($st->tgl_berangkat && $st->tgl_kembali)
                                        ? $st->tgl_berangkat->diffInDays($st->tgl_kembali) + 1
                                        : 1;

                                    $modalData = [
                                        'id' => $st->id,
                                        'nomor_surat' => $st->nomor_surat,
                                        'tgl_surat' => $st->tgl_surat ? $st->tgl_surat->format('d/m/Y') : '-',
                                        'tgl_berangkat' => $st->tgl_berangkat ? $st->tgl_berangkat->format('d/m/Y') : '-',
                                        'tgl_kembali' => $st->tgl_kembali ? $st->tgl_kembali->format('d/m/Y') : '-',
                                        'durasi' => $durasi,
                                        'tujuan' => $st->tujuan,
                                        'tugas' => $st->tugas,
                                        'uraian_pembebanan' => $st->uraian_pembebanan,
                                        'pembuat' => $st->user ? ($st->user->username ?? $st->user->name) : '-',
                                        'pegawai' => [
                                            'nama' => $st->pegawai->nama ?? '-',
                                            'nip' => $st->pegawai->nip ?? '-',
                                            'golongan' => $st->pegawai->golongan ?? '-',
                                            'pangkat' => $st->pegawai->pangkat ?? '-',
                                            'jabatan' => $st->pegawai->jabatan ?? '-',
                                        ],
                                        'kepala' => [
                                            'nama' => $st->kepalaPegawai->nama ?? '-',
                                            'nip' => $st->kepalaPegawai->nip ?? '-',
                                            'jabatan' => $st->kepalaPegawai->jabatan ?? '-',
                                        ],
                                        'anggaran' => [
                                            'mak' => $st->anggaran->mak ?? '-',
                                            'uraian' => $st->uraian_pembebanan ?: ($st->anggaran ? ($st->anggaran->akun_uraian . ($st->anggaran->suboutput_uraian ? ' (' . $st->anggaran->suboutput_uraian . ')' : '')) : '-'),
                                        ],
                                        'spd' => $st->spd ? [
                                            'id' => $st->spd->id,
                                            'nomor_spd' => $st->spd->nomor_spd,
                                            'kendaraan' => $st->spd->kendaraan ?? '-',
                                            'tgl_dpr' => $st->spd->tgl_dpr ? $st->spd->tgl_dpr->format('d/m/Y') : '-',
                                            'nilai_dpr' => $st->spd->nilai_dpr,
                                            'nilai_dpr_formatted' => 'Rp ' . number_format($st->spd->nilai_dpr, 0, ',', '.'),
                                            'ppk_nama' => $st->spd->ppkPegawai->nama ?? '-',
                                            'ppk_nip' => $st->spd->ppkPegawai->nip ?? '-',
                                            'export_spd_url' => route('export.spd', $st->spd->id),
                                            'export_dpr_url' => route('export.dpr', $st->spd->id),
                                            'export_pernyataan_url' => route('export.pernyataan', $st->spd->id),
                                            'edit_spd_url' => route('spd.edit', $st->spd->id),
                                        ] : null,
                                        'create_spd_url' => route('spd.create', $st->id),
                                        'laporan' => $st->laporanPerjalanan ? [
                                            'id' => $st->laporanPerjalanan->id,
                                            'tgl_laporan' => $st->laporanPerjalanan->tgl_laporan ? \Carbon\Carbon::parse($st->laporanPerjalanan->tgl_laporan)->format('d/m/Y') : '-',
                                            'kategori' => $st->laporanPerjalanan->kategori ?? '-',
                                            'lokasi_perjalanan' => $st->laporanPerjalanan->lokasi_perjalanan ?? '-',
                                            'pegawai_ditemui' => $st->laporanPerjalanan->pegawai_ditemui ?? '-',
                                            'hasil_perjalanan' => Str::limit($st->laporanPerjalanan->hasil_perjalanan ?? '', 280),
                                            'show_url' => route('laporan.show', $st->laporanPerjalanan->id),
                                            'export_pdf_url' => route('export.laporan', $st->laporanPerjalanan->id),
                                            'export_word_url' => route('export.laporan.word', $st->laporanPerjalanan->id),
                                        ] : null,
                                        'create_laporan_url' => route('laporan.create', ['surat_tugas_id' => $st->id]),
                                        'export_pdf_url' => route('export.surat-tugas', $st->id),
                                        'edit_st_url' => route('surat-tugas.edit', $st->id),
                                    ];
                                @endphp
                                <tr class="hover:bg-purple-50/30 transition-colors">

                                    <!-- Column 1: Waktu & Jadwal -->
                                    <td class="px-5 py-4 whitespace-nowrap text-xs">
                                        <div class="font-semibold text-gray-900 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>{{ $st->tgl_berangkat ? $st->tgl_berangkat->format('d/m/Y') : '-' }}</span>
                                            @if($st->tgl_kembali && $st->tgl_kembali != $st->tgl_berangkat)
                                                <span class="text-gray-400 font-normal">s/d</span>
                                                <span>{{ $st->tgl_kembali->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $durasi }} Hari
                                            </span>
                                            @if($st->tgl_surat)
                                                <span class="text-[11px] text-gray-400" title="Tanggal Surat Terbit">
                                                    ST: {{ $st->tgl_surat->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Column 2: Nomor ST & SPD -->
                                    <td class="px-5 py-4 text-xs">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-gray-900 font-mono tracking-tight">{{ $st->nomor_surat }}</span>
                                            <button type="button" onclick="navigator.clipboard.writeText('{{ $st->nomor_surat }}'); alert('Nomor ST disalin!');" class="text-gray-300 hover:text-purple-600 transition" title="Salin Nomor ST">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            </button>
                                        </div>

                                        <div class="mt-1.5">
                                            @if($st->spd)
                                                <a href="{{ route('export.spd', $st->spd->id) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-mono font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 transition" title="Klik untuk cetak / preview SPD">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    SPD: {{ $st->spd->nomor_spd }}
                                                </a>
                                            @else
                                                <a href="{{ route('spd.create', $st->id) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 px-2 py-0.5 rounded border border-amber-200 transition" title="Belum ada SPD, klik untuk terbitkan sekarang">
                                                    <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    + Terbitkan SPD
                                                </a>
                                            @endif
                                        </div>

                                        <div class="text-[10px] text-gray-400 mt-1">
                                            Oleh: <span class="font-medium text-gray-600">{{ $st->user ? ($st->user->username ?? $st->user->name) : '-' }}</span>
                                        </div>
                                    </td>

                                    <!-- Column 3: Pegawai Pelaksana -->
                                    <td class="px-5 py-4 text-xs">
                                        <div class="flex items-center gap-2.5">
                                            @php
                                                $nama = $st->pegawai->nama ?? 'Pegawai';
                                                $initials = collect(explode(' ', $nama))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                                            @endphp
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                                {{ strtoupper($initials) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 leading-snug">{{ $st->pegawai->nama ?? '-' }}</div>
                                                <div class="text-[11px] text-gray-500 font-mono mt-0.5">NIP: {{ $st->pegawai->nip ?? '-' }}</div>
                                                @if($st->pegawai && ($st->pegawai->golongan || $st->pegawai->jabatan))
                                                    <div class="text-[10px] text-gray-400 mt-0.5 truncate max-w-[180px]">
                                                        {{ $st->pegawai->golongan ? 'Gol. ' . $st->pegawai->golongan : '' }}
                                                        {{ $st->pegawai->jabatan ? ($st->pegawai->golongan ? '• ' : '') . $st->pegawai->jabatan : '' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Column 4: Tujuan & Maksud Tugas -->
                                    <td class="px-5 py-4 text-xs max-w-xs">
                                        <div class="flex items-start gap-1 font-semibold text-gray-900">
                                            <svg class="w-3.5 h-3.5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="truncate" title="{{ $st->tujuan }}">{{ $st->tujuan }}</span>
                                        </div>
                                        <div class="text-gray-500 text-[11px] mt-1 line-clamp-2 leading-relaxed" title="{{ $st->tugas }}">
                                            {{ $st->tugas }}
                                        </div>
                                    </td>

                                    <!-- Column 5: Pembebanan Anggaran (MAK) -->
                                    <td class="px-5 py-4 text-xs whitespace-nowrap">
                                        @if($st->anggaran)
                                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-indigo-50 border border-indigo-200/60 text-indigo-700 font-mono text-[11px] max-w-[200px] truncate" title="{{ $st->anggaran->mak }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                                <span class="truncate">{{ $st->anggaran->mak }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-[11px]">Tidak terbeban MAK</span>
                                        @endif

                                        <div class="mt-1 flex items-center gap-1.5">
                                            @if($st->spd && $st->spd->nilai_dpr > 0)
                                                <span class="text-[11px] font-semibold text-emerald-600 font-mono">
                                                    DPR: Rp {{ number_format($st->spd->nilai_dpr, 0, ',', '.') }}
                                                </span>
                                            @elseif($st->spd)
                                                <span class="text-[10px] text-gray-400">DPR: Rp 0</span>
                                            @else
                                                <span class="text-[10px] text-amber-600">SPD Belum Terbit</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Column 6: Status Laporan AI -->
                                    <td class="px-5 py-4 whitespace-nowrap text-center text-xs">
                                        @if($st->laporanPerjalanan)
                                            <div class="inline-flex flex-col items-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Laporan Selesai
                                                </span>
                                                <a href="{{ route('laporan.show', $st->laporanPerjalanan->id) }}" class="text-[11px] text-indigo-600 hover:text-indigo-800 font-medium mt-1 inline-flex items-center gap-0.5 transition">
                                                    <span>Buka Laporan</span>
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            </div>
                                        @else
                                            <div class="inline-flex flex-col items-center">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                                    <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Belum Ada
                                                </span>
                                                <a href="{{ route('laporan.create', ['surat_tugas_id' => $st->id]) }}" class="inline-flex items-center gap-1 text-[11px] font-semibold text-purple-700 hover:text-purple-900 mt-1 bg-purple-50 hover:bg-purple-100 px-2 py-0.5 rounded border border-purple-200 transition">
                                                    <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                    Buat AI
                                                </a>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Column 7: Aksi -->
                                    <td class="px-5 py-4 whitespace-nowrap text-center text-xs">
                                        <div class="flex items-center justify-center gap-1.5" x-data="{ open: false }">

                                            <!-- Quick 1: Lihat Detail Modal -->
                                            <button
                                                type="button"
                                                onclick="openDetailModal({{ json_encode($modalData) }})"
                                                class="p-1.5 rounded-lg text-purple-600 hover:text-purple-900 hover:bg-purple-50 transition"
                                                title="Lihat Detail Lengkap Surat Tugas & SPD"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            <!-- Quick 2: Cetak PDF Gabungan 1 Tombol -->
                                            <a
                                                href="{{ route('export.surat-tugas', $st->id) }}"
                                                target="_blank"
                                                class="p-1.5 rounded-lg text-emerald-600 hover:text-emerald-900 hover:bg-emerald-50 transition"
                                                title="{{ $st->spd ? 'Cetak / Preview PDF Lengkap (ST, SPD, DPR, Pernyataan)' : 'Cetak / Preview PDF Surat Tugas' }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                            </a>

                                            <!-- Quick 3: Edit Surat Tugas -->
                                            <a
                                                href="{{ route('surat-tugas.edit', $st->id) }}"
                                                class="p-1.5 rounded-lg text-blue-600 hover:text-blue-900 hover:bg-blue-50 transition"
                                                title="Edit Data Surat Tugas"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            <!-- Dropdown Menu for More Options -->
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
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="absolute right-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-20 text-left"
                                                    style="display: none;"
                                                >
                                                    <!-- SPD Actions -->
                                                    <div class="py-1">
                                                        @if($st->spd)
                                                            <a href="{{ route('spd.edit', $st->spd->id) }}" class="group flex items-center px-4 py-2 text-xs text-amber-700 hover:bg-amber-50">
                                                                <svg class="mr-3 h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                Edit SPD & DPR
                                                            </a>
                                                            <a href="{{ route('export.spd', $st->spd->id) }}" target="_blank" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                                                <svg class="mr-3 h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                                Cetak Lembar SPD (PDF)
                                                            </a>
                                                            <a href="{{ route('export.dpr', $st->spd->id) }}" target="_blank" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                                                <svg class="mr-3 h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                Cetak Rincian DPR (PDF)
                                                            </a>
                                                            <a href="{{ route('export.pernyataan', $st->spd->id) }}" target="_blank" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                                                <svg class="mr-3 h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                                Cetak Surat Pernyataan
                                                            </a>
                                                        @else
                                                            <a href="{{ route('spd.create', $st->id) }}" class="group flex items-center px-4 py-2 text-xs text-amber-700 hover:bg-amber-50">
                                                                <svg class="mr-3 h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                Terbitkan SPD & Rincian DPR
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <!-- Laporan Actions -->
                                                    <div class="py-1">
                                                        @if($st->laporanPerjalanan)
                                                            <a href="{{ route('laporan.show', $st->laporanPerjalanan->id) }}" class="group flex items-center px-4 py-2 text-xs text-blue-700 hover:bg-blue-50">
                                                                <svg class="mr-3 h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                Lihat Laporan AI
                                                            </a>
                                                            <a href="{{ route('export.laporan', $st->laporanPerjalanan->id) }}" target="_blank" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                                                <svg class="mr-3 h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                                Cetak Laporan (PDF)
                                                            </a>
                                                            <a href="{{ route('export.laporan.word', $st->laporanPerjalanan->id) }}" class="group flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                                                <svg class="mr-3 h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                                Export Laporan (Word .docx)
                                                            </a>
                                                        @else
                                                            <a href="{{ route('laporan.create', ['surat_tugas_id' => $st->id]) }}" class="group flex items-center px-4 py-2 text-xs text-purple-700 hover:bg-purple-50">
                                                                <svg class="mr-3 h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                                Buat Laporan AI Baru
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <!-- Delete ST -->
                                                    <div class="py-1">
                                                        <form action="{{ route('surat-tugas.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Peringatan: Menghapus Surat Tugas ini juga akan menghapus data SPD dan Laporan terkait. Lanjutkan penghapusan?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full group flex items-center px-4 py-2 text-xs text-rose-600 hover:bg-rose-50">
                                                                <svg class="mr-3 h-4 w-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                Hapus Surat Tugas
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
                                            <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <h5 class="text-sm font-bold text-gray-900 mb-1">Tidak Ada Data Surat Tugas</h5>
                                            <p class="text-xs text-gray-500 mb-4 text-center">
                                                @if(request('search') || request('status_spd') || request('status_laporan') || request('anggaran_id'))
                                                    Tidak ada data yang cocok dengan kriteria filter saat ini. Coba reset filter.
                                                @else
                                                    Belum ada Surat Tugas yang diterbitkan pada konteks Satker & Tahun Anggaran ini.
                                                @endif
                                            </p>
                                            @if(request('search') || request('status_spd') || request('status_laporan') || request('anggaran_id'))
                                                <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                                                    Reset Filter
                                                </a>
                                            @else
                                                <a href="{{ route('surat-tugas.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                                    + Buat Surat Tugas Pertama
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
                @if($suratTugasList->hasPages() || $suratTugasList->total() > 0)
                    <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
                        <div>
                            Menampilkan <span class="font-semibold text-gray-800">{{ $suratTugasList->firstItem() ?? 0 }}</span> s/d <span class="font-semibold text-gray-800">{{ $suratTugasList->lastItem() ?? 0 }}</span> dari total <span class="font-semibold text-gray-800">{{ $suratTugasList->total() }}</span> Surat Tugas
                        </div>
                        <div>
                            {{ $suratTugasList->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Enhanced Detail Modal -->
    <div id="detail-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeDetailModal()"></div>

        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-3xl sm:w-full border border-gray-100">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-purple-700 to-indigo-800 px-6 py-5 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-white/20 text-white">
                                    Detail Administrasi Perjalanan
                                </span>
                                <span id="modal-status-spd" class="text-xs font-semibold px-2 py-0.5 rounded-full"></span>
                                <span id="modal-status-laporan" class="text-xs font-semibold px-2 py-0.5 rounded-full"></span>
                            </div>
                            <h3 class="text-lg font-bold mt-2 text-white flex items-center gap-2">
                                <span id="modal-nomor-st">-</span>
                                <button type="button" id="modal-copy-st-btn" class="text-white/70 hover:text-white transition" title="Salin Nomor ST">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </h3>
                            <p class="text-xs text-white/80 mt-1" id="modal-header-sub">Tanggal Terbit: -</p>
                        </div>
                        <button type="button" onclick="closeDetailModal()" class="text-white/70 hover:text-white rounded-lg p-1 hover:bg-white/10 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6 text-sm">

                    <!-- Section 1: Informasi Penugasan & Waktu -->
                    <div class="bg-gray-50/70 rounded-xl p-4 border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Penugasan & Jadwal Pelaksanaan
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-gray-400">Tempat / Lokasi Tujuan:</span>
                                <p class="text-gray-900 font-bold text-sm mt-0.5" id="modal-tujuan">-</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Jadwal Perjalanan Dinas:</span>
                                <p class="text-gray-900 font-semibold mt-0.5" id="modal-jadwal">-</p>
                            </div>
                            <div class="col-span-1 md:col-span-2 mt-1">
                                <span class="text-gray-400">Maksud / Uraian Tugas:</span>
                                <p class="text-gray-800 font-normal leading-relaxed mt-1 p-2.5 bg-white rounded-lg border border-gray-200" id="modal-tugas">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Personel Pelaksana & Penandatangan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Pelaksana -->
                        <div class="bg-purple-50/40 rounded-xl p-4 border border-purple-100/60">
                            <h4 class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Pegawai Pelaksana
                            </h4>
                            <div class="space-y-1.5 text-xs">
                                <div>
                                    <span class="text-gray-400">Nama:</span>
                                    <p class="text-gray-900 font-bold" id="modal-pelaksana-nama">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-400">NIP:</span>
                                    <p class="text-gray-700 font-mono" id="modal-pelaksana-nip">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-400">Golongan / Jabatan:</span>
                                    <p class="text-gray-700" id="modal-pelaksana-jabatan">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pejabat Penandatangan -->
                        <div class="bg-indigo-50/40 rounded-xl p-4 border border-indigo-100/60">
                            <h4 class="text-xs font-bold text-indigo-900 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Pemberi Tugas / Kepala
                            </h4>
                            <div class="space-y-1.5 text-xs">
                                <div>
                                    <span class="text-gray-400">Nama Pejabat:</span>
                                    <p class="text-gray-900 font-bold" id="modal-kepala-nama">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-400">NIP:</span>
                                    <p class="text-gray-700 font-mono" id="modal-kepala-nip">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-400">Jabatan:</span>
                                    <p class="text-gray-700" id="modal-kepala-jabatan">-</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Section 3: Administrasi SPD & Pembebanan Anggaran -->
                    <div class="bg-gray-50/70 rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Administrasi SPD & Anggaran
                            </h4>
                            <div id="modal-spd-actions"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-gray-400">Nomor SPD:</span>
                                <p class="text-gray-900 font-bold font-mono text-sm mt-0.5" id="modal-spd-nomor">-</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Pengeluaran Riil (DPR):</span>
                                <p class="text-emerald-600 font-black font-mono text-sm mt-0.5" id="modal-spd-dpr">-</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Mata Anggaran Kegiatan (MAK):</span>
                                <p class="text-indigo-700 font-mono font-medium mt-0.5" id="modal-anggaran-mak">-</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Pejabat Pembuat Komitmen (PPK):</span>
                                <p class="text-gray-800 font-semibold mt-0.5" id="modal-ppk-nama">-</p>
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <span class="text-gray-400">Uraian Beban Anggaran:</span>
                                <p class="text-gray-600 mt-0.5" id="modal-anggaran-uraian">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Laporan Perjalanan (AI) -->
                    <div class="bg-blue-50/40 rounded-xl p-4 border border-blue-100/60">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Laporan Perjalanan (AI)
                            </h4>
                            <div id="modal-laporan-actions"></div>
                        </div>

                        <div id="modal-laporan-body" class="text-xs space-y-2">
                            <!-- Populated via Javascript -->
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2" id="modal-footer-left">
                        <!-- Primary Action Buttons -->
                    </div>
                    <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-xs">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Javascript Modal Controller -->
    <script>
        function openDetailModal(data) {
            // Header
            document.getElementById('modal-nomor-st').textContent = data.nomor_surat || '-';
            document.getElementById('modal-header-sub').textContent = 'Tanggal Surat: ' + (data.tgl_surat || '-') + ' • Pembuat: ' + (data.pembuat || '-');

            // Copy button
            document.getElementById('modal-copy-st-btn').onclick = function() {
                navigator.clipboard.writeText(data.nomor_surat);
                alert('Nomor Surat Tugas berhasil disalin!');
            };

            // Status Badges
            const statusSpdEl = document.getElementById('modal-status-spd');
            if (data.spd) {
                statusSpdEl.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/30 text-emerald-100 border border-emerald-400/30';
                statusSpdEl.textContent = 'SPD Terbit';
            } else {
                statusSpdEl.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-500/30 text-amber-100 border border-amber-400/30';
                statusSpdEl.textContent = 'Belum Ada SPD';
            }

            const statusLapEl = document.getElementById('modal-status-laporan');
            if (data.laporan) {
                statusLapEl.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-500/30 text-blue-100 border border-blue-400/30';
                statusLapEl.textContent = 'Laporan Selesai';
            } else {
                statusLapEl.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-rose-500/30 text-rose-100 border border-rose-400/30';
                statusLapEl.textContent = 'Belum Ada Laporan';
            }

            // Section 1: Penugasan
            document.getElementById('modal-tujuan').textContent = data.tujuan || '-';
            document.getElementById('modal-jadwal').textContent = (data.tgl_berangkat || '-') + ' s/d ' + (data.tgl_kembali || '-') + ' (' + (data.durasi || 1) + ' Hari)';
            document.getElementById('modal-tugas').textContent = data.tugas || '-';

            // Section 2: Personel
            document.getElementById('modal-pelaksana-nama').textContent = data.pegawai?.nama || '-';
            document.getElementById('modal-pelaksana-nip').textContent = data.pegawai?.nip || '-';
            document.getElementById('modal-pelaksana-jabatan').textContent = (data.pegawai?.golongan ? 'Gol. ' + data.pegawai.golongan : '') + (data.pegawai?.jabatan ? ' • ' + data.pegawai.jabatan : '-');

            document.getElementById('modal-kepala-nama').textContent = data.kepala?.nama || '-';
            document.getElementById('modal-kepala-nip').textContent = data.kepala?.nip || '-';
            document.getElementById('modal-kepala-jabatan').textContent = data.kepala?.jabatan || '-';

            // Section 3: SPD & Anggaran
            document.getElementById('modal-anggaran-mak').textContent = data.anggaran?.mak || '-';
            document.getElementById('modal-anggaran-uraian').textContent = data.anggaran?.uraian || data.uraian_pembebanan || '-';

            const spdActions = document.getElementById('modal-spd-actions');
            if (data.spd) {
                document.getElementById('modal-spd-nomor').textContent = data.spd.nomor_spd || '-';
                document.getElementById('modal-spd-dpr').textContent = data.spd.nilai_dpr_formatted || 'Rp 0';
                document.getElementById('modal-ppk-nama').textContent = (data.spd.ppk_nama || '-') + (data.spd.ppk_nip ? ' (NIP: ' + data.spd.ppk_nip + ')' : '');

                spdActions.innerHTML = `
                    <div class="flex items-center gap-1.5">
                        <a href="${data.spd.export_spd_url}" target="_blank" class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded border border-emerald-200 text-[11px] font-semibold transition">
                            PDF SPD
                        </a>
                        <a href="${data.spd.export_dpr_url}" target="_blank" class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded border border-emerald-200 text-[11px] font-semibold transition">
                            PDF DPR
                        </a>
                        <a href="${data.spd.edit_spd_url}" class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded border border-amber-200 text-[11px] font-semibold transition">
                            Edit SPD
                        </a>
                    </div>
                `;
            } else {
                document.getElementById('modal-spd-nomor').innerHTML = '<span class="text-amber-600 font-normal">Belum Diterbitkan</span>';
                document.getElementById('modal-spd-dpr').textContent = '-';
                document.getElementById('modal-ppk-nama').textContent = '-';

                spdActions.innerHTML = `
                    <a href="${data.create_spd_url}" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded text-[11px] font-semibold transition shadow-xs">
                        + Terbitkan SPD Sekarang
                    </a>
                `;
            }

            // Section 4: Laporan AI
            const laporanBody = document.getElementById('modal-laporan-body');
            const laporanActions = document.getElementById('modal-laporan-actions');

            if (data.laporan) {
                laporanActions.innerHTML = `
                    <div class="flex items-center gap-1.5">
                        <a href="${data.laporan.show_url}" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px] font-semibold transition shadow-xs">
                            Buka Detail Laporan &rarr;
                        </a>
                        <a href="${data.laporan.export_pdf_url}" target="_blank" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded border border-rose-200 text-[11px] font-semibold transition">
                            PDF
                        </a>
                        <a href="${data.laporan.export_word_url}" class="px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded border border-blue-200 text-[11px] font-semibold transition">
                            Word
                        </a>
                    </div>
                `;

                laporanBody.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <span class="text-gray-400">Tanggal Laporan:</span>
                            <p class="font-semibold text-gray-800">${data.laporan.tgl_laporan || '-'}</p>
                        </div>
                        <div>
                            <span class="text-gray-400">Kategori / Narasumber:</span>
                            <p class="font-semibold text-gray-800">${data.laporan.kategori || '-'} • ${data.laporan.pegawai_ditemui || '-'}</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-gray-400">Ringkasan Hasil Perjalanan:</span>
                        <p class="text-gray-700 mt-1 p-2.5 bg-white rounded-lg border border-gray-200 leading-relaxed font-sans">${data.laporan.hasil_perjalanan || '-'}</p>
                    </div>
                `;
            } else {
                laporanActions.innerHTML = `
                    <a href="${data.create_laporan_url}" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded text-[11px] font-semibold transition shadow-xs flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Buat Laporan AI
                    </a>
                `;

                laporanBody.innerHTML = `
                    <div class="p-4 bg-white/80 rounded-lg border border-dashed border-blue-200 text-center">
                        <p class="text-gray-500">Laporan perjalanan untuk penugasan ini belum dibuat.</p>
                        <a href="${data.create_laporan_url}" class="inline-flex items-center gap-1.5 mt-2 text-xs font-semibold text-purple-700 hover:text-purple-900">
                            <span>Klik di sini untuk langsung generate draft laporan dengan AI</span> &rarr;
                        </a>
                    </div>
                `;
            }

            // Footer Left Action Buttons
            const footerLeft = document.getElementById('modal-footer-left');
            footerLeft.innerHTML = `
                <a href="${data.export_pdf_url}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Dokumen Lengkap (PDF)
                </a>
                <a href="${data.edit_st_url}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Surat Tugas
                </a>
            `;

            document.getElementById('detail-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetailModal();
            }
        });
    </script>
</x-app-layout>
