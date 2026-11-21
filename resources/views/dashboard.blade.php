<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} &bull; Tahun Anggaran: <span class="font-bold text-gray-700">{{ $currentTahun->tahun ?? date('Y') }}</span>
                    @if($currentSatker)
                        &bull; Satker: <span class="font-bold text-gray-700">{{ $currentSatker->nama }} ({{ $currentSatker->kode }})</span>
                    @endif
                </p>
            </div>
            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('surat-tugas.create') }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded shadow-sm transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Surat Tugas
                </a>
                <a href="{{ route('hanya-laporan.create') }}" class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded shadow-sm transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Hanya Laporan
                </a>
                <a href="{{ route('laporan.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded shadow-sm transition">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Laporan AI
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-900 rounded-xl shadow-lg overflow-hidden text-white">
                <div class="p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai" class="h-20 w-20 md:h-24 md:w-24 object-contain rounded-full border-2 border-purple-400/50 shadow-md flex-shrink-0 bg-white p-1">
                        <div>
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-500/20 text-purple-200 border border-purple-400/30 mb-2">
                                Sistem Pertanggungjawaban Perjalanan Dinas
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-purple-100/80 text-sm mt-1 max-w-2xl leading-relaxed">
                                Kelola administrasi perjalanan dinas secara otomatis, terintegrasi, dan cepat. Dari penerbitan Surat Tugas, SPD, DPR, hingga Laporan Narasi AI lengkap dalam hitungan detik.
                            </p>
                            @if(in_array(auth()->user()->role, ['admin', 'super']) && auth()->user()->last_login_at)
                                <div class="mt-3 inline-flex items-center gap-2 text-xs text-purple-200 bg-white/10 px-3 py-1.5 rounded-lg border border-purple-400/20 backdrop-blur-xs">
                                    <svg class="w-3.5 h-3.5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Login Terakhir Anda: <strong>{{ auth()->user()->last_login_at->translatedFormat('d M Y, H:i') }}</strong> ({{ auth()->user()->last_login_at->diffForHumans() }})</span>
                                    <span class="text-purple-300/60">•</span>
                                    <span class="font-mono">IP: <strong>{{ auth()->user()->last_login_ip ?: '-' }}</strong></span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex md:flex-col items-center md:items-end justify-between w-full md:w-auto gap-2 border-t md:border-t-0 border-white/10 pt-4 md:pt-0">
                        <div class="text-left md:text-right">
                            <span class="text-xs text-purple-200">Total Pengeluaran Riil (DPR)</span>
                            <div class="text-xl md:text-2xl font-black text-amber-300 font-mono">
                                Rp {{ number_format($stats['total_dpr'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4 Primary KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Card Surat Tugas & SPD -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Surat Tugas Terbit</p>
                            <h4 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($stats['surat_tugas']) }}</h4>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-green-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                            {{ $stats['st_with_spd'] }} SPD Terbit
                        </span>
                        <span class="text-amber-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                            {{ $stats['st_without_spd'] }} Belum Ada SPD
                        </span>
                    </div>
                </div>

                <!-- Card Laporan Selesai -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan Selesai (Dibuat)</p>
                            <h4 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($stats['laporan']) }}</h4>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-indigo-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                            {{ $stats['laporan_perjalanan'] }} Laporan AI
                        </span>
                        <span class="text-purple-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mr-1.5"></span>
                            {{ $stats['hanya_laporan'] }} Hanya Laporan
                        </span>
                    </div>
                </div>

                <!-- Card Total DPR -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Nilai DPR</p>
                            <h4 class="text-2xl font-black text-emerald-600 mt-1 font-mono">
                                Rp {{ number_format($stats['total_dpr'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Daftar Pengeluaran Riil</span>
                        <span class="font-semibold text-gray-700">Tahun {{ $currentTahun->tahun ?? date('Y') }}</span>
                    </div>
                </div>

                <!-- Card Pegawai -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Master Pegawai & Anggaran</p>
                            <h4 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($stats['pegawai']) }} <span class="text-xs font-normal text-gray-500">Pegawai</span></h4>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-blue-700 font-medium inline-flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                            {{ $stats['pegawai_aktif'] }} Aktif
                        </span>
                        <span class="text-gray-600 font-medium inline-flex items-center">
                            {{ $stats['anggaran'] }} Kode MAK
                        </span>
                    </div>
                </div>

            </div>

            <!-- Perlu Tindak Lanjut (Pending Attention) Alerts -->
            @if($stats['st_without_spd'] > 0 || $stats['st_without_laporan'] > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($stats['st_without_spd'] > 0)
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-amber-100 rounded-lg text-amber-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-amber-900">{{ $stats['st_without_spd'] }} Surat Tugas Belum Ada SPD</h5>
                                    <p class="text-xs text-amber-700">Perlu penerbitan SPD & penetapan anggaran untuk kelengkapan administrasi.</p>
                                </div>
                            </div>
                            <a href="{{ route('surat-tugas.index') }}" class="text-xs font-semibold text-amber-800 bg-amber-200/70 hover:bg-amber-200 px-3 py-1.5 rounded-lg whitespace-nowrap transition">
                                Tindak Lanjut &rarr;
                            </a>
                        </div>
                    @endif

                    @if($stats['st_without_laporan'] > 0)
                        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-xl flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-purple-100 rounded-lg text-purple-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-purple-900">{{ $stats['st_without_laporan'] }} Surat Tugas Belum Dilaporkan</h5>
                                    <p class="text-xs text-purple-700">Perjalanan dinas yang belum diisi Laporan Pertanggungjawaban (AI).</p>
                                </div>
                            </div>
                            <a href="{{ route('laporan.create') }}" class="text-xs font-semibold text-purple-800 bg-purple-200/70 hover:bg-purple-200 px-3 py-1.5 rounded-lg whitespace-nowrap transition">
                                Buat Laporan &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tren Aktivitas & Kategori -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Grafik Bulanan (2 Kolom) -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h4 class="text-base font-bold text-gray-900">Aktivitas Perjalanan Bulanan</h4>
                            <p class="text-xs text-gray-500">Volume pelaksanaan perjalanan dinas per bulan di Tahun {{ $currentTahun->tahun ?? date('Y') }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full font-mono">
                            Total: {{ array_sum($monthlyCounts) }} Kegiatan
                        </span>
                    </div>

                    <div class="h-48 flex items-end justify-between gap-2 pt-6 pb-2 px-2 border-b border-gray-100">
                        @foreach($monthlyCounts as $month => $count)
                            @php
                                $percent = $maxMonthlyValue > 0 ? max(8, round(($count / $maxMonthlyValue) * 100)) : 8;
                            @endphp
                            <div class="flex-1 flex flex-col items-center h-full justify-end group">
                                <span class="text-[10px] font-bold text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity mb-1 font-mono">
                                    {{ $count }}
                                </span>
                                <div class="w-full max-w-[28px] rounded-t-md transition-all duration-300 {{ $count > 0 ? 'bg-gradient-to-t from-indigo-600 to-purple-500 group-hover:from-indigo-700 group-hover:to-purple-600 shadow-sm' : 'bg-gray-100' }}" style="height: {{ $percent }}%;"></div>
                                <span class="text-[11px] font-semibold text-gray-500 mt-2">{{ $month }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-400 mt-3 pt-1">
                        <span>Januari</span>
                        <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-sm bg-indigo-600 inline-block mr-1"></span> Volume Kegiatan Terlaksana</span>
                        <span>Desember</span>
                    </div>
                </div>

                <!-- Distribusi Kategori (1 Kolom) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-base font-bold text-gray-900">Kategori Kegiatan</h4>
                        <span class="text-xs text-gray-500">Top 5</span>
                    </div>
                    
                    @if(count($topCategories) > 0)
                        <div class="space-y-4">
                            @foreach($topCategories as $kat => $count)
                                @php
                                    $pct = $totalKategoriCount > 0 ? round(($count / $totalKategoriCount) * 100) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center text-xs mb-1">
                                        <span class="font-medium text-gray-700 truncate max-w-[180px]">{{ $kat }}</span>
                                        <span class="font-bold text-gray-900 font-mono">{{ $count }} ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-400 text-sm">
                            Belum ada data kategori perjalanan.
                        </div>
                    @endif
                </div>

            </div>

            <!-- Leaderboard & Peringkat: Pegawai Teraktif, 5 Lokasi Terbanyak, dan Pembebanan Anggaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- 1. Pegawai Teraktif (Dipadukan Laporan AI & Hanya Laporan) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <h4 class="text-base font-bold text-gray-900">Pegawai Teraktif</h4>
                            </div>
                            <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                AI + Hanya Laporan
                            </span>
                        </div>

                        @if($topPegawai->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($topPegawai as $idx => $p)
                                    <div class="py-2.5 flex items-center justify-between gap-3">
                                        <div class="flex items-center space-x-3 min-w-0">
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $idx === 0 ? 'bg-amber-100 text-amber-800' : ($idx === 1 ? 'bg-slate-200 text-slate-800' : ($idx === 2 ? 'bg-amber-50 text-amber-700' : 'bg-gray-100 text-gray-700')) }}">
                                                {{ $idx + 1 }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-gray-900 truncate" title="{{ $p->nama }}">{{ $p->nama }}</p>
                                                <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-gray-500 font-mono">
                                                    <span>{{ $p->nip ?? '-' }}</span>
                                                    @if($p->ai_count > 0 && $p->hl_count > 0)
                                                        <span class="text-[10px] font-sans text-gray-400 font-normal">({{ $p->ai_count }} AI &bull; {{ $p->hl_count }} HL)</span>
                                                    @elseif($p->ai_count > 0)
                                                        <span class="text-[10px] font-sans text-indigo-600 font-normal">({{ $p->ai_count }} Lap AI)</span>
                                                    @elseif($p->hl_count > 0)
                                                        <span class="text-[10px] font-sans text-purple-600 font-normal">({{ $p->hl_count }} Hanya Lap)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-50 text-indigo-700 font-mono shrink-0">
                                            {{ $p->total_count }}x
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 text-center py-6">Belum ada data perjalanan pegawai.</p>
                        @endif
                    </div>
                </div>

                <!-- 2. 5 Lokasi Terbanyak (Instansi / Desa / Tempat dari Laporan AI & Hanya Laporan) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <h4 class="text-base font-bold text-gray-900">5 Lokasi Terbanyak</h4>
                            </div>
                            <span class="text-[11px] font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">
                                Instansi / Desa / Tempat
                            </span>
                        </div>

                        @if($topLocations->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($topLocations as $idx => $loc)
                                    <div class="py-2.5 flex items-center justify-between gap-3">
                                        <div class="flex items-center space-x-3 min-w-0">
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $idx === 0 ? 'bg-rose-100 text-rose-800' : ($idx === 1 ? 'bg-slate-200 text-slate-800' : ($idx === 2 ? 'bg-rose-50 text-rose-700' : 'bg-gray-100 text-gray-700')) }}">
                                                {{ $idx + 1 }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-gray-900 truncate" title="{{ $loc->name }}">{{ $loc->name }}</p>
                                                <div class="flex items-center gap-1.5 mt-0.5 text-[10px] text-gray-500">
                                                    @if($loc->ai_count > 0 && $loc->hl_count > 0)
                                                        <span>{{ $loc->ai_count }} Lap AI &bull; {{ $loc->hl_count }} Hanya Lap</span>
                                                    @elseif($loc->ai_count > 0)
                                                        <span class="text-indigo-600">{{ $loc->ai_count }} Laporan AI</span>
                                                    @else
                                                        <span class="text-purple-600">{{ $loc->hl_count }} Hanya Laporan</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-50 text-rose-700 font-mono shrink-0">
                                            {{ $loc->total_count }}x
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 text-center py-6">Belum ada data lokasi perjalanan.</p>
                        @endif
                    </div>
                </div>

                <!-- 3. Top MAK Anggaran -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <h4 class="text-base font-bold text-gray-900">Pembebanan Anggaran</h4>
                            </div>
                            <span class="text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                Top 5 MAK
                            </span>
                        </div>

                        @if($topAnggaran->count() > 0)
                            <div class="space-y-3">
                                @foreach($topAnggaran as $ang)
                                    <div class="p-2.5 rounded-lg bg-gray-50 border border-gray-100 text-xs">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold font-mono text-indigo-700">{{ $ang->mak }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800 font-mono">
                                                {{ $ang->surat_tugas_count }} Kegiatan
                                            </span>
                                        </div>
                                        @if($ang->kegiatan_uraian)
                                            <p class="text-[11px] text-gray-600 mt-1 truncate" title="{{ $ang->kegiatan_uraian }}">{{ $ang->kegiatan_uraian }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 text-center py-6">Belum ada pembebanan anggaran.</p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- WIDGET LAST LOGIN (KHUSUS ADMINISTRATOR) -->
            @if($isAdmin)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-xs shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-base font-bold text-gray-900">Aktivitas Login Terakhir Pengguna (Last Login)</h4>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Khusus Admin
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Rekam jejak waktu login dan alamat IP akses pengguna{{ auth()->user()->role === 'super' ? ' seluruh satuan kerja' : ' satuan kerja Anda' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold transition">
                                <span>Buka Manajemen Pengguna</span>
                                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    @if($recentLogins->isNotEmpty())
                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full divide-y divide-gray-100 text-sm">
                                <thead>
                                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                        <th class="py-2.5">Pengguna</th>
                                        <th class="py-2.5">Peran (Role)</th>
                                        @if(auth()->user()->role === 'super')
                                            <th class="py-2.5">Satuan Kerja</th>
                                        @endif
                                        <th class="py-2.5">Waktu Login Terakhir</th>
                                        <th class="py-2.5">Alamat IP (IP Address)</th>
                                        <th class="py-2.5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($recentLogins as $userLogin)
                                        @php
                                            $initials = collect(explode(' ', $userLogin->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                                            $avatarColor = match($userLogin->role) {
                                                'super' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                'admin' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                default => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            };
                                            $isToday = $userLogin->last_login_at && $userLogin->last_login_at->isToday();
                                        @endphp
                                        <tr class="hover:bg-gray-50/80 transition">
                                            <!-- Pengguna -->
                                            <td class="py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-lg {{ $avatarColor }} border font-extrabold text-[11px] flex items-center justify-center shrink-0 uppercase">
                                                        {{ $initials ?: 'U' }}
                                                    </div>
                                                    <div>
                                                        <div class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                                            <span>{{ $userLogin->name }}</span>
                                                            @if($userLogin->id === auth()->id())
                                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Anda</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-[11px] text-gray-400 font-mono">
                                                            <span>@</span>{{ $userLogin->username }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Role -->
                                            <td class="py-3 whitespace-nowrap text-xs">
                                                @if($userLogin->role === 'super')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                        Super Admin
                                                    </span>
                                                @elseif($userLogin->role === 'admin')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                        Admin Satker
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        User (Operator)
                                                    </span>
                                                @endif
                                            </td>

                                            @if(auth()->user()->role === 'super')
                                                <!-- Satker -->
                                                <td class="py-3 whitespace-nowrap text-xs text-gray-600">
                                                    {{ $userLogin->satker ? $userLogin->satker->nama : 'Semua Satker' }}
                                                </td>
                                            @endif

                                            <!-- Waktu Login -->
                                            <td class="py-3 whitespace-nowrap text-xs">
                                                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ $userLogin->last_login_at->translatedFormat('d M Y, H:i') }}</span>
                                                </div>
                                                <div class="text-[10px] text-gray-400 mt-0.5">
                                                    {{ $userLogin->last_login_at->diffForHumans() }}
                                                </div>
                                            </td>

                                            <!-- Alamat IP -->
                                            <td class="py-3 whitespace-nowrap text-xs">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-100 text-gray-800 font-mono text-xs border border-gray-200">
                                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $userLogin->last_login_ip ?: '-' }}
                                                </span>
                                            </td>

                                            <!-- Status -->
                                            <td class="py-3 text-center whitespace-nowrap">
                                                @if($isToday)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                                        Hari Ini
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600">
                                                        Tercatat
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center text-gray-400">
                            <div class="w-10 h-10 mx-auto rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-600">Belum ada riwayat aktivitas login</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Riwayat tanggal dan IP login akan otomatis tercatat saat pengguna masuk ke sistem.</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tabel Surat Tugas & SPD Terbaru (Full Width) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-base font-bold text-gray-900">Surat Tugas &amp; SPD Terbaru</h4>
                        <p class="text-xs text-gray-500">Daftar penerbitan surat tugas paling mutakhir</p>
                    </div>
                    <a href="{{ route('surat-tugas.index') }}" class="text-xs font-semibold text-purple-600 hover:text-purple-800">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="py-2.5">Waktu</th>
                                <th class="py-2.5">No. ST &amp; SPD</th>
                                <th class="py-2.5">Pelaksana</th>
                                <th class="py-2.5">Tujuan &amp; Tugas</th>
                                <th class="py-2.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentSuratTugas as $st)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="py-3 whitespace-nowrap text-xs text-gray-700">
                                        {{ $st->tgl_berangkat->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 text-xs">
                                        <div class="font-bold text-gray-900">{{ $st->nomor_surat }}</div>
                                        <div class="text-[11px] text-gray-500 font-mono">
                                            SPD: {{ $st->spd ? $st->spd->nomor_spd : '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3 text-xs text-gray-800 font-medium">
                                        {{ $st->pegawai->nama ?? '-' }}
                                    </td>
                                    <td class="py-3 text-xs text-gray-600">
                                        <div class="font-medium text-gray-800">{{ $st->tujuan }}</div>
                                        <div class="text-[11px] text-gray-500">{{ $st->tugas }}</div>
                                    </td>
                                    <td class="py-3 text-center whitespace-nowrap">
                                        @if($st->spd)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-800">
                                                SPD Terbit
                                            </span>
                                        @else
                                            <a href="{{ route('spd.create', $st->id) }}" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 hover:bg-amber-200">
                                                + Terbitkan SPD
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-400 text-xs">Belum ada data surat tugas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bagian: Hanya Laporan Terbaru -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-base font-bold text-gray-900">Hanya Laporan Terbaru</h4>
                        <p class="text-xs text-gray-500">Laporan yang dibuat secara mandiri tanpa siklus surat tugas</p>
                    </div>
                    <a href="{{ route('hanya-laporan.index') }}" class="text-xs font-semibold text-purple-600 hover:text-purple-800">
                        Lihat Semua Hanya Laporan &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="py-2.5">Waktu</th>
                                <th class="py-2.5">Nomor ST & SPD</th>
                                <th class="py-2.5">Nama Pegawai</th>
                                <th class="py-2.5">Tujuan & Lokasi</th>
                                <th class="py-2.5">DPR & Pernyataan</th>
                                <th class="py-2.5 text-center">Pembuat</th>
                                <th class="py-2.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentHanyaLaporan as $hl)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="py-3 whitespace-nowrap text-xs text-gray-700">
                                        {{ $hl->tgl_perjalanan->format('d/m/Y') }}
                                        <div class="text-[10px] text-gray-400">Lap: {{ $hl->tgl_laporan->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="py-3 text-xs">
                                        <div class="font-bold text-gray-900">{{ $hl->nomor_st }}</div>
                                        <div class="text-[11px] text-gray-500 font-mono">
                                            SPD: {{ $hl->nomor_spd ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3 text-xs text-gray-800 font-medium">
                                        {{ $hl->nama_pegawai }}
                                    </td>
                                    <td class="py-3 text-xs text-gray-600">
                                        <div class="font-medium text-gray-800">{{ $hl->lokasi_perjalanan }}</div>
                                        <div class="text-[11px] text-gray-500">{{ $hl->tujuan_perjalanan }}</div>
                                    </td>
                                    <td class="py-3 text-xs">
                                        @if($hl->ada_dpr)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800 font-mono">
                                                DPR Rp {{ number_format($hl->nilai_dpr, 0, ',', '.') }}
                                            </span>
                                        @endif
                                        @if($hl->ada_pernyataan)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">
                                                Pernyataan
                                            </span>
                                        @endif
                                        @if(!$hl->ada_dpr && !$hl->ada_pernyataan)
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 font-mono">
                                            {{ $hl->user ? ($hl->user->username ?? $hl->user->name) : '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center whitespace-nowrap">
                                        <a href="{{ route('export.hanya-laporan', $hl->id) }}" target="_blank" class="text-green-600 hover:text-green-800 text-xs font-semibold inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            PDF
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-gray-400 text-xs">Belum ada data Hanya Laporan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
