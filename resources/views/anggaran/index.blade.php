<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ __('Data Anggaran (DIPA / MAK)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Kelola alokasi mata anggaran kegiatan, struktur DIPA, serta pantau realisasi beban pengeluaran riil (DPR).
                </p>
            </div>

            @if(auth()->user()->role !== 'user')
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('anggarans.template') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Template CSV
                </a>
                <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')" class="inline-flex items-center px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Import CSV
                </button>
                <a href="{{ route('anggarans.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm shadow-emerald-600/20 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    + Tambah Anggaran
                </a>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 4 Summary KPI Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Card 1: Total MAK -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total MAK Terdaftar</p>
                            <h4 class="text-2xl font-black text-gray-900 mt-1 font-mono">{{ number_format($stats['total_mak']) }}</h4>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Mata Anggaran DIPA</span>
                        <span class="font-semibold text-indigo-600">Terstruktur</span>
                    </div>
                </div>

                <!-- Card 2: MAK Aktif Terpakai -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">MAK Aktif Digunakan</p>
                            <h4 class="text-2xl font-black text-emerald-600 mt-1 font-mono">{{ number_format($stats['used_mak']) }}</h4>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-emerald-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                            {{ $stats['total_mak'] > 0 ? round(($stats['used_mak'] / $stats['total_mak']) * 100) : 0 }}% Utilisasi
                        </span>
                        <span class="text-gray-500">
                            {{ $stats['total_mak'] - $stats['used_mak'] }} Belum Dipakai
                        </span>
                    </div>
                </div>

                <!-- Card 3: Beban Kegiatan Surat Tugas -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Beban Surat Tugas</p>
                            <h4 class="text-2xl font-black text-amber-600 mt-1 font-mono">{{ number_format($stats['total_st']) }} <span class="text-xs font-normal text-gray-500">Kegiatan</span></h4>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Penugasan Lapangan</span>
                        <span class="font-semibold text-amber-700">Terbebankan</span>
                    </div>
                </div>

                <!-- Card 4: Total Realisasi DPR -->
                <div class="bg-white rounded-xl shadow-xs border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Realisasi DPR</p>
                            <h4 class="text-2xl font-black text-indigo-700 mt-1 font-mono">
                                Rp {{ number_format($stats['total_dpr'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Pengeluaran Riil</span>
                        <span class="font-semibold text-indigo-600">Sesuai SPD</span>
                    </div>
                </div>

            </div>

            <!-- Flash Notifications -->
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Content Card -->
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                
                <!-- Search & Filters Bar -->
                <div class="p-5 border-b border-gray-100 bg-slate-50/50">
                    <form action="{{ route('anggarans.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                            
                            <!-- Search Input (lg:col-span-4) -->
                            <div class="lg:col-span-4 relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Cari MAK, uraian kegiatan, output, akun..." 
                                    class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs sm:text-sm bg-white transition"
                                >
                            </div>

                            <!-- Filter Tahun (lg:col-span-2) -->
                            <div class="lg:col-span-2">
                                <select name="tahun" onchange="this.form.submit()" class="w-full py-2 px-3 rounded-lg border border-gray-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs sm:text-sm bg-white cursor-pointer">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahunList as $th)
                                        <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                            Tahun {{ $th }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Status Penggunaan (lg:col-span-3) -->
                            <div class="lg:col-span-3">
                                <select name="status" onchange="this.form.submit()" class="w-full py-2 px-3 rounded-lg border border-gray-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs sm:text-sm bg-white cursor-pointer">
                                    <option value="">Semua Status Penggunaan</option>
                                    <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>
                                        Sudah Digunakan (Ada ST)
                                    </option>
                                    <option value="unused" {{ request('status') == 'unused' ? 'selected' : '' }}>
                                        Belum Digunakan (0 ST)
                                    </option>
                                </select>
                            </div>

                            <!-- Filter Sort (lg:col-span-2) -->
                            <div class="lg:col-span-2">
                                <select name="sort" onchange="this.form.submit()" class="w-full py-2 px-3 rounded-lg border border-gray-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 text-xs sm:text-sm bg-white cursor-pointer">
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="mak" {{ request('sort') == 'mak' ? 'selected' : '' }}>Kode MAK (A-Z)</option>
                                    <option value="surat_tugas_count" {{ request('sort') == 'surat_tugas_count' ? 'selected' : '' }}>Beban ST Terbanyak</option>
                                    <option value="dpr" {{ request('sort') == 'dpr' ? 'selected' : '' }}>Nilai DPR Tertinggi</option>
                                </select>
                            </div>

                            <!-- Buttons (lg:col-span-1) -->
                            <div class="lg:col-span-1 flex items-center gap-1.5">
                                <button type="submit" class="w-full py-2 px-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition text-center">
                                    Filter
                                </button>
                                @if(request()->anyFilled(['search', 'tahun', 'status', 'sort', 'per_page']))
                                    <a href="{{ route('anggarans.index') }}" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition" title="Reset Filter">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </a>
                                @endif
                            </div>

                        </div>

                        <!-- Baris Tambahan: Per Page & Hasil -->
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-2 border-t border-gray-200/60">
                            <div>
                                Menampilkan <span class="font-bold text-gray-800">{{ $anggarans->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-gray-800">{{ $anggarans->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-800">{{ $anggarans->total() }}</span> data anggaran
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Tampilkan:</span>
                                <select name="per_page" onchange="this.form.submit()" class="py-1 px-2.5 rounded-md border border-gray-300 text-xs bg-white cursor-pointer">
                                    <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-slate-50 text-gray-600">
                            <tr>
                                <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">
                                    Mata Anggaran (MAK) &amp; Akun
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">
                                    Tahun &amp; Status
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">
                                    Program &amp; Kegiatan
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">
                                    Output &amp; Komponen
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">
                                    Beban &amp; Realisasi DPR
                                </th>
                                <th class="px-5 py-3.5 text-center text-xs font-bold uppercase tracking-wider whitespace-nowrap">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($anggarans as $anggaran)
                                <tr class="hover:bg-slate-50/80 transition group">
                                    
                                    <!-- Kolom 1: MAK & Akun -->
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono text-xs font-bold text-indigo-900 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-md">
                                                {{ $anggaran->mak }}
                                            </span>
                                            <!-- Copy Button -->
                                            <button 
                                                type="button" 
                                                onclick="copyMak('{{ $anggaran->mak }}', this)" 
                                                class="text-gray-400 hover:text-indigo-600 p-1 rounded transition" 
                                                title="Salin Kode MAK"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            </button>
                                        </div>

                                        @if($anggaran->akun_kode || $anggaran->akun_uraian)
                                            <div class="mt-1.5 flex items-center gap-1.5 text-xs text-gray-600">
                                                <span class="font-mono font-semibold text-gray-500 bg-gray-100 px-1 rounded text-[11px]">
                                                    {{ $anggaran->akun_kode ?? 'Akun' }}
                                                </span>
                                                <span class="truncate max-w-[260px] text-gray-700" title="{{ $anggaran->akun_uraian }}">
                                                    {{ $anggaran->akun_uraian ?? '-' }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Kolom 2: Tahun & Status -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="font-bold text-xs text-gray-800">
                                            Tahun {{ $anggaran->tahun }}
                                        </div>
                                        <div class="mt-1">
                                            @if(($anggaran->surat_tugas_count ?? 0) > 0)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Digunakan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                    Belum Dipakai
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Kolom 3: Program & Kegiatan -->
                                    <td class="px-5 py-4">
                                        <div class="text-xs font-semibold text-gray-900 leading-snug">
                                            {{ $anggaran->kegiatan_uraian ?: '-' }}
                                        </div>
                                        @if($anggaran->program_uraian)
                                            <div class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1">
                                                <span class="text-gray-400 font-mono">Prog:</span>
                                                <span class="truncate max-w-[240px]" title="{{ $anggaran->program_uraian }}">{{ $anggaran->program_uraian }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Kolom 4: Output & Komponen -->
                                    <td class="px-5 py-4">
                                        <div class="text-xs text-gray-800 font-medium leading-snug">
                                            {{ $anggaran->output_uraian ?: '-' }}
                                        </div>
                                        @if($anggaran->komponen_uraian)
                                            <div class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1">
                                                <span class="text-gray-400 font-mono">Komp:</span>
                                                <span class="truncate max-w-[240px]" title="{{ $anggaran->komponen_uraian }}">{{ $anggaran->komponen_uraian }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Kolom 5: Beban ST & Realisasi DPR -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <span class="px-2 py-0.5 rounded text-xs font-bold font-mono {{ ($anggaran->surat_tugas_count ?? 0) > 0 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $anggaran->surat_tugas_count ?? 0 }} ST
                                            </span>
                                        </div>
                                        <div class="mt-1 text-xs font-mono font-bold text-emerald-700">
                                            Rp {{ number_format($anggaran->spds_sum_nilai_dpr ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    <!-- Kolom 6: Aksi -->
                                    <td class="px-5 py-4 whitespace-nowrap text-center">
                                        <div class="inline-flex items-center gap-1">
                                            
                                            <!-- Tombol View Detail -->
                                            <button 
                                                type="button" 
                                                onclick="loadAnggaranDetail({{ $anggaran->id }})" 
                                                class="p-1.5 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 transition" 
                                                title="Lihat Struktur Lengkap & Riwayat ST"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>

                                            @if(auth()->user()->role !== 'user')
                                                <!-- Tombol Edit -->
                                                <a 
                                                    href="{{ route('anggarans.edit', $anggaran->id) }}" 
                                                    class="p-1.5 rounded-lg text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 transition" 
                                                    title="Edit Data Anggaran"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>

                                                <!-- Tombol Delete -->
                                                <form action="{{ route('anggarans.destroy', $anggaran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggaran MAK ini? Pastikan tidak ada surat tugas yang terkait.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" 
                                                        title="Hapus Data Anggaran"
                                                    >
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                            <p class="text-sm font-medium text-gray-500">Tidak ada data anggaran yang sesuai filter.</p>
                                            @if(request()->anyFilled(['search', 'tahun', 'status']))
                                                <a href="{{ route('anggarans.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                                    Bersihkan Semua Filter
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
                @if($anggarans->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 bg-slate-50/50">
                        {{ $anggarans->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

    <!-- Modal View Detail Anggaran & Riwayat ST -->
    <div id="detail-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeDetailModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-100 relative z-10">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 px-6 py-5 text-white flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 px-2 py-0.5 rounded-full">
                                Detail Struktur DIPA
                            </span>
                            <span id="modal-tahun-badge" class="text-xs font-semibold bg-white/10 text-slate-300 px-2 py-0.5 rounded-full">
                                Tahun -
                            </span>
                        </div>
                        <h3 class="text-lg font-bold font-mono tracking-tight mt-1 text-white flex items-center gap-2">
                            <span id="modal-mak-title">-</span>
                        </h3>
                    </div>
                    <button type="button" onclick="closeDetailModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 max-h-[75vh] overflow-y-auto space-y-6">

                    <!-- Quick Metrics Banner -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase">Surat Tugas Terbit</p>
                                <p id="modal-st-count" class="text-xl font-black text-gray-900 font-mono mt-0.5">0 ST</p>
                            </div>
                            <div class="p-2.5 bg-amber-100 rounded-lg text-amber-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold uppercase">Realisasi Pengeluaran Riil (DPR)</p>
                                <p id="modal-dpr-total" class="text-xl font-black text-emerald-600 font-mono mt-0.5">Rp 0</p>
                            </div>
                            <div class="p-2.5 bg-emerald-100 rounded-lg text-emerald-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Hierarki Struktur DIPA / MAK -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                            Struktur Hierarki DIPA
                        </h4>
                        <div class="bg-slate-50 rounded-xl border border-gray-200/80 p-4 space-y-3.5 text-xs">
                            
                            <!-- Program -->
                            <div class="flex items-start gap-3">
                                <span class="px-2 py-0.5 rounded font-mono font-bold bg-indigo-100 text-indigo-800 text-[11px] shrink-0">Program</span>
                                <div>
                                    <span id="modal-program-kode" class="font-mono font-bold text-gray-900">-</span>
                                    <p id="modal-program-uraian" class="text-gray-600 mt-0.5">-</p>
                                </div>
                            </div>

                            <!-- Kegiatan -->
                            <div class="flex items-start gap-3 pt-3 border-t border-gray-200/60">
                                <span class="px-2 py-0.5 rounded font-mono font-bold bg-blue-100 text-blue-800 text-[11px] shrink-0">Kegiatan</span>
                                <div>
                                    <span id="modal-kegiatan-kode" class="font-mono font-bold text-gray-900">-</span>
                                    <p id="modal-kegiatan-uraian" class="text-gray-800 font-semibold mt-0.5">-</p>
                                </div>
                            </div>

                            <!-- Output & Suboutput -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-3 border-t border-gray-200/60">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-1.5 py-0.5 rounded font-mono font-bold bg-gray-200 text-gray-700 text-[10px]">KRO / Output</span>
                                        <span id="modal-output-kode" class="font-mono font-bold text-gray-800">-</span>
                                    </div>
                                    <p id="modal-output-uraian" class="text-gray-600 mt-0.5">-</p>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-1.5 py-0.5 rounded font-mono font-bold bg-gray-200 text-gray-700 text-[10px]">RO / Suboutput</span>
                                        <span id="modal-suboutput-kode" class="font-mono font-bold text-gray-800">-</span>
                                    </div>
                                    <p id="modal-suboutput-uraian" class="text-gray-600 mt-0.5">-</p>
                                </div>
                            </div>

                            <!-- Komponen & Subkomponen -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-3 border-t border-gray-200/60">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-1.5 py-0.5 rounded font-mono font-bold bg-gray-200 text-gray-700 text-[10px]">Komponen</span>
                                        <span id="modal-komponen-kode" class="font-mono font-bold text-gray-800">-</span>
                                    </div>
                                    <p id="modal-komponen-uraian" class="text-gray-600 mt-0.5">-</p>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-1.5 py-0.5 rounded font-mono font-bold bg-gray-200 text-gray-700 text-[10px]">Subkomponen</span>
                                        <span id="modal-subkomponen-kode" class="font-mono font-bold text-gray-800">-</span>
                                    </div>
                                    <p id="modal-subkomponen-uraian" class="text-gray-600 mt-0.5">-</p>
                                </div>
                            </div>

                            <!-- Akun Belanja -->
                            <div class="flex items-start gap-3 pt-3 border-t border-gray-200/60">
                                <span class="px-2 py-0.5 rounded font-mono font-bold bg-purple-100 text-purple-800 text-[11px] shrink-0">Akun Belanja</span>
                                <div>
                                    <span id="modal-akun-kode" class="font-mono font-bold text-purple-900 text-sm">-</span>
                                    <p id="modal-akun-uraian" class="text-gray-700 font-medium mt-0.5">-</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Riwayat Surat Tugas Terbaru yang Menggunakan MAK Ini -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Riwayat Surat Tugas Terkait (Maks. 10 Terakhir)
                            </h4>
                        </div>
                        <div id="modal-st-list" class="border border-gray-200 rounded-xl overflow-hidden text-xs">
                            <div class="p-4 text-center text-gray-400">Memuat data...</div>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-100">
                    <span class="text-xs text-gray-400">Translok.ai &bull; Sistem Monitoring Anggaran</span>
                    <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 shadow-xs transition">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Import CSV -->
    <div id="import-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 relative z-10">
                <form action="{{ route('anggarans.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span>Import Data Anggaran (CSV)</span>
                            </h3>
                            <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="bg-indigo-50/60 border border-indigo-100 rounded-xl p-4 text-xs text-indigo-900 mb-4 space-y-2">
                            <p class="font-bold flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Petunjuk Import:</span>
                            </p>
                            <p class="leading-relaxed text-indigo-800">
                                Unduh terlebih dahulu berkas template resmi, lalu isikan data MAK sesuai kolom yang tersedia tanpa mengubah urutan header.
                            </p>
                            <div class="pt-1">
                                <a href="{{ route('anggarans.template') }}" class="inline-flex items-center text-xs font-bold text-indigo-700 hover:text-indigo-900 hover:underline">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh Template CSV
                                </a>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Pilih File CSV (.csv)</label>
                            <input 
                                type="file" 
                                name="file_csv" 
                                accept=".csv" 
                                required 
                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-xl p-1 bg-white cursor-pointer"
                            />
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-2 border-t border-gray-100">
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                            Mulai Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification for Copy -->
    <div id="copy-toast" class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white text-xs font-semibold py-2 px-4 rounded-xl shadow-xl transition-all duration-300 transform translate-y-12 opacity-0 pointer-events-none flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span id="copy-toast-msg">Kode MAK berhasil disalin!</span>
    </div>

    <script>
        // Copy to clipboard helper
        function copyMak(text, btnElement) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast('Kode MAK disalin: ' + text);
                });
            } else {
                const temp = document.createElement('textarea');
                temp.value = text;
                document.body.appendChild(temp);
                temp.select();
                document.execCommand('copy');
                document.body.removeChild(temp);
                showToast('Kode MAK disalin: ' + text);
            }
        }

        function showToast(message) {
            const toast = document.getElementById('copy-toast');
            document.getElementById('copy-toast-msg').innerText = message;
            toast.classList.remove('translate-y-12', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-12', 'opacity-0');
            }, 2500);
        }

        // Detail Modal Loader
        function loadAnggaranDetail(id) {
            const modal = document.getElementById('detail-modal');
            const stContainer = document.getElementById('modal-st-list');
            
            // Set loading state
            stContainer.innerHTML = '<div class="p-4 text-center text-gray-400 text-xs">Memuat rincian penugasan...</div>';
            modal.classList.remove('hidden');

            fetch(`/anggarans/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                // Populate Header & Metrics
                document.getElementById('modal-mak-title').innerText = data.mak || '-';
                document.getElementById('modal-tahun-badge').innerText = 'Tahun ' + (data.tahun || '-');
                document.getElementById('modal-st-count').innerText = (data.surat_tugas_count || 0) + ' ST';
                
                const dprSum = data.spds_sum_nilai_dpr ? Number(data.spds_sum_nilai_dpr) : 0;
                document.getElementById('modal-dpr-total').innerText = 'Rp ' + dprSum.toLocaleString('id-ID');

                // Populate Structure
                document.getElementById('modal-program-kode').innerText = data.program_kode || '-';
                document.getElementById('modal-program-uraian').innerText = data.program_uraian || '-';

                document.getElementById('modal-kegiatan-kode').innerText = data.kegiatan_kode || '-';
                document.getElementById('modal-kegiatan-uraian').innerText = data.kegiatan_uraian || '-';

                document.getElementById('modal-output-kode').innerText = data.output_kode || '-';
                document.getElementById('modal-output-uraian').innerText = data.output_uraian || '-';

                document.getElementById('modal-suboutput-kode').innerText = data.suboutput_kode || '-';
                document.getElementById('modal-suboutput-uraian').innerText = data.suboutput_uraian || '-';

                document.getElementById('modal-komponen-kode').innerText = data.komponen_kode || '-';
                document.getElementById('modal-komponen-uraian').innerText = data.komponen_uraian || '-';

                document.getElementById('modal-subkomponen-kode').innerText = data.subkomponen_kode || '-';
                document.getElementById('modal-subkomponen-uraian').innerText = data.subkomponen_uraian || '-';

                document.getElementById('modal-akun-kode').innerText = data.akun_kode || '-';
                document.getElementById('modal-akun-uraian').innerText = data.akun_uraian || '-';

                // Populate Surat Tugas List
                if (data.surat_tugas && data.surat_tugas.length > 0) {
                    let rows = `
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-gray-500 text-[11px]">
                                <tr>
                                    <th class="py-2 px-3 text-left">No. ST</th>
                                    <th class="py-2 px-3 text-left">Pelaksana</th>
                                    <th class="py-2 px-3 text-left">Tanggal</th>
                                    <th class="py-2 px-3 text-right">Nilai DPR</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                    `;

                    data.surat_tugas.forEach(st => {
                        const namaPegawai = st.pegawai ? st.pegawai.nama : '-';
                        const tglBerangkat = st.tgl_berangkat ? st.tgl_berangkat.substring(0, 10) : '-';
                        const dprVal = (st.spd && st.spd.nilai_dpr) ? Number(st.spd.nilai_dpr).toLocaleString('id-ID') : '0';

                        rows += `
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-2 px-3 font-mono font-medium text-gray-900">${st.nomor_surat || '-'}</td>
                                <td class="py-2 px-3 text-gray-800">${namaPegawai}</td>
                                <td class="py-2 px-3 text-gray-500">${tglBerangkat}</td>
                                <td class="py-2 px-3 text-right font-mono font-semibold text-emerald-700">Rp ${dprVal}</td>
                            </tr>
                        `;
                    });

                    rows += `</tbody></table>`;
                    stContainer.innerHTML = rows;
                } else {
                    stContainer.innerHTML = '<div class="p-6 text-center text-gray-400">Belum ada Surat Tugas yang menggunakan MAK ini.</div>';
                }
            })
            .catch(err => {
                console.error(err);
                stContainer.innerHTML = '<div class="p-4 text-center text-rose-500">Gagal memuat rincian data anggaran.</div>';
            });
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
