<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('anggarans.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Data Anggaran
                    </a>
                    <span class="text-gray-300">•</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        🏛️ DIPA / POK Satker
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                        🔒 Tahun {{ $selectedTahun }} Terkunci
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        ⚡ Validasi Otomatis
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-emerald-600 via-teal-600 to-green-600 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <span>{{ __('Tambah Data Anggaran (MAK)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1 max-w-3xl">
                    Daftarkan Mata Anggaran Kegiatan (MAK) baru beserta rincian struktur DIPA/POK secara akurat. Data anggaran ini digunakan sebagai rujukan pembebanan Surat Tugas dan perhitungan Surat Perjalanan Dinas (SPD).
                </p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a href="{{ route('anggarans.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition">
                    Daftar Anggaran
                </a>
                <a href="{{ route('anggarans.template') }}" class="inline-flex items-center px-3.5 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100 rounded-xl text-xs font-semibold shadow-xs transition" title="Unduh contoh template format CSV">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Unduh Template CSV
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $satkerName = $satkerObj ? ($satkerObj->kode . ' - ' . $satkerObj->nama) : (session('satker_id') ? 'Satker #' . session('satker_id') : 'Semua Satker (Superadmin)');
    @endphp

    <div class="py-8 bg-gray-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- NOTIFIKASI ERROR VALIDASI -->
            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl shadow-xs">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-rose-900">Periksa Kembali Isian Formulir Anggaran</h4>
                            <p class="text-xs text-rose-700 mt-0.5">Terdapat data yang belum lengkap atau kode MAK sudah terdaftar pada tahun dan satker yang sama:</p>
                            <ul class="list-disc list-inside text-xs mt-2 space-y-1 text-rose-800">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- CARD CONTEXT: INFORMASI SESI TAHUN & SATKER AKTIF -->
            <div class="bg-gradient-to-r from-slate-900 via-teal-950 to-emerald-950 text-white rounded-2xl shadow-md p-5 sm:p-6 relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/5 rounded-full pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-xs border border-white/15 flex items-center justify-center text-emerald-400 font-black text-xl shadow-xs">
                            📅
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-300">Tahun Anggaran Aktif</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Terkunci & Valid
                                </span>
                            </div>
                            <h3 class="text-2xl font-black text-white tracking-wide font-mono mt-0.5">{{ $selectedTahun }}</h3>
                        </div>
                    </div>

                    <div class="flex flex-col sm:items-end text-xs text-emerald-200/90">
                        <span class="text-[11px] text-gray-400">Satker Pemegang DIPA:</span>
                        <span class="font-bold text-white text-sm mt-0.5">{{ $satkerName }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-1 flex items-start gap-2.5 text-xs text-emerald-100/80 leading-relaxed">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        <strong>Ketetapan Sistem:</strong> Kolom Tahun Anggaran di bawah diset <strong>read-only</strong> sesuai tahun yang Anda pilih saat login awal. Bila Anda bermaksud mendaftarkan MAK untuk tahun anggaran yang berbeda, silakan lakukan pergantian tahun aktif terlebih dahulu melalui menu navigasi di kanan atas.
                    </span>
                </div>
            </div>

            <!-- FORM TAMBAH DATA ANGGARAN -->
            <form id="form-anggaran" method="POST" action="{{ route('anggarans.store') }}" class="space-y-6">
                @csrf

                <!-- ======================================================== -->
                <!-- SECTION 1: IDENTIFIKASI UTAMA MATA ANGGARAN -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Identifikasi Utama Mata Anggaran Kegiatan</h3>
                            <p class="text-xs text-gray-500">Tahun anggaran terkunci secara otomatis, masukkan kode MAK lengkap DIPA/POK.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Tahun Anggaran (READ ONLY) -->
                        <div class="md:col-span-1">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="tahun" class="block text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span>Tahun Anggaran</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-full">
                                    <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Read Only
                                </span>
                            </div>

                            <div class="relative rounded-xl shadow-xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input
                                    id="tahun"
                                    type="text"
                                    name="tahun"
                                    value="{{ old('tahun', $selectedTahun) }}"
                                    readonly
                                    class="block w-full pl-10 pr-10 py-2.5 bg-gray-100/90 text-gray-800 font-mono font-extrabold text-sm border-gray-300 rounded-xl cursor-not-allowed select-none focus:ring-0 focus:border-gray-300 shadow-inner"
                                    title="Tahun anggaran terkunci sesuai tahun aktif sesi login"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Otomatis terisi dari sesi aktif ({{ $selectedTahun }}).</p>
                            <x-input-error :messages="$errors->get('tahun')" class="mt-1" />
                        </div>

                        <!-- MAK (Mata Anggaran Kegiatan) -->
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="mak" class="block text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span>Mata Anggaran Kegiatan (MAK)</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-gray-400">Harus unik per Tahun & Satker</span>
                            </div>

                            <div class="relative rounded-xl shadow-xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                </div>
                                <input
                                    id="mak"
                                    type="text"
                                    name="mak"
                                    value="{{ old('mak') }}"
                                    placeholder="Contoh: 054.01.WA.2895.QMA.001.051.A.524111 atau 054.01.GG.2895.524111"
                                    class="block w-full pl-10 pr-3.5 py-2.5 border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl font-mono text-sm font-bold text-gray-900 placeholder:font-sans placeholder:font-normal placeholder:text-gray-400 shadow-xs"
                                    required
                                    autofocus
                                    oninput="updateLivePreview()"
                                />
                            </div>
                            
                            <!-- Quick Format Help Badges -->
                            <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                <span class="text-[11px] text-gray-400 font-medium">Sisip Cepat:</span>
                                <button type="button" onclick="insertMakPrefix('054.01.WA.')" class="px-2 py-0.5 text-[10px] font-mono font-semibold bg-gray-100 hover:bg-emerald-50 text-gray-700 hover:text-emerald-700 border border-gray-200 rounded-md transition">
                                    + 054.01.WA.
                                </button>
                                <button type="button" onclick="insertMakPrefix('054.01.GG.')" class="px-2 py-0.5 text-[10px] font-mono font-semibold bg-gray-100 hover:bg-emerald-50 text-gray-700 hover:text-emerald-700 border border-gray-200 rounded-md transition">
                                    + 054.01.GG.
                                </button>
                                <button type="button" onclick="appendMakSuffix('.524111')" class="px-2 py-0.5 text-[10px] font-mono font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-md transition">
                                    + .524111 (Perjadin Biasa)
                                </button>
                                <button type="button" onclick="appendMakSuffix('.524113')" class="px-2 py-0.5 text-[10px] font-mono font-semibold bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-md transition">
                                    + .524113 (Dalam Kota)
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('mak')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 2: RINCIAN NOMENKLATUR STRUKTUR DIPA / POK -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 font-bold text-xs flex items-center justify-center">2</span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Nomenklatur Struktur DIPA / POK</h3>
                                <p class="text-xs text-gray-500">Rincian kode & uraian resmi untuk memudahkan pencarian dan laporan akuntabilitas (Opsional).</p>
                            </div>
                        </div>

                        <button type="button" onclick="autoFillMakFromSegments()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 rounded-xl text-xs font-semibold border border-emerald-200 transition" title="Susun kode MAK secara otomatis dari rincian segmen di bawah">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Susun MAK dari Segmen
                        </button>
                    </div>

                    <!-- Level 1: Program & Kegiatan -->
                    <div class="mb-5 p-4 rounded-xl bg-gray-50/70 border border-gray-200/80 space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 uppercase tracking-wider">Level 1</span>
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Program & Kegiatan</h4>
                        </div>

                        <!-- Program -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="program_kode" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Kode Program
                                </label>
                                <input id="program_kode" name="program_kode" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs font-mono" placeholder="Contoh: 054.01.WA" value="{{ old('program_kode') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('program_kode')" class="mt-1" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="program_uraian" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Uraian Program
                                </label>
                                <input id="program_uraian" name="program_uraian" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs" placeholder="Contoh: Program Penyediaan dan Pelayanan Informasi Statistik" value="{{ old('program_uraian') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('program_uraian')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Kegiatan -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-gray-200/70">
                            <div>
                                <label for="kegiatan_kode" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Kode Kegiatan
                                </label>
                                <input id="kegiatan_kode" name="kegiatan_kode" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs font-mono" placeholder="Contoh: 2895" value="{{ old('kegiatan_kode') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('kegiatan_kode')" class="mt-1" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="kegiatan_uraian" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Uraian Kegiatan
                                </label>
                                <input id="kegiatan_uraian" name="kegiatan_uraian" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs" placeholder="Contoh: Penyelenggaraan Sensus dan Survei Statistik" value="{{ old('kegiatan_uraian') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('kegiatan_uraian')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Level 2: Output & Suboutput -->
                    <div class="mb-5 p-4 rounded-xl bg-gray-50/70 border border-gray-200/80 space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800 uppercase tracking-wider">Level 2</span>
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Output & Suboutput (KRO / RO)</h4>
                        </div>

                        <!-- Output -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="output_kode" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Kode Output (KRO)
                                </label>
                                <input id="output_kode" name="output_kode" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs font-mono" placeholder="Contoh: QMA atau 994" value="{{ old('output_kode') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('output_kode')" class="mt-1" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="output_uraian" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Uraian Output (KRO)
                                </label>
                                <input id="output_uraian" name="output_uraian" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs" placeholder="Contoh: Data Statistik Kependudukan dan Kesejahteraan Rakyat" value="{{ old('output_uraian') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('output_uraian')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Suboutput -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-gray-200/70">
                            <div>
                                <label for="suboutput_kode" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Kode Suboutput (RO)
                                </label>
                                <input id="suboutput_kode" name="suboutput_kode" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs font-mono" placeholder="Contoh: 001" value="{{ old('suboutput_kode') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('suboutput_kode')" class="mt-1" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="suboutput_uraian" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Uraian Suboutput (RO)
                                </label>
                                <input id="suboutput_uraian" name="suboutput_uraian" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs" placeholder="Contoh: Laporan Analisis Statistik Daerah" value="{{ old('suboutput_uraian') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('suboutput_uraian')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Level 3: Komponen & Subkomponen -->
                    <div class="mb-5 p-4 rounded-xl bg-gray-50/70 border border-gray-200/80 space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">Level 3</span>
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Komponen & Subkomponen</h4>
                        </div>

                        <!-- Komponen -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="komponen_kode" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Kode Komponen
                                </label>
                                <input id="komponen_kode" name="komponen_kode" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs font-mono" placeholder="Contoh: 051" value="{{ old('komponen_kode') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('komponen_kode')" class="mt-1" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="komponen_uraian" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Uraian Komponen
                                </label>
                                <input id="komponen_uraian" name="komponen_uraian" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs" placeholder="Contoh: Pelaksanaan Operasional Lapangan" value="{{ old('komponen_uraian') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('komponen_uraian')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Subkomponen -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-gray-200/70">
                            <div>
                                <label for="subkomponen_kode" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Kode Subkomponen
                                </label>
                                <input id="subkomponen_kode" name="subkomponen_kode" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs font-mono" placeholder="Contoh: A" value="{{ old('subkomponen_kode') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('subkomponen_kode')" class="mt-1" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="subkomponen_uraian" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Uraian Subkomponen
                                </label>
                                <input id="subkomponen_uraian" name="subkomponen_uraian" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs" placeholder="Contoh: Persiapan dan Pengumpulan Data" value="{{ old('subkomponen_uraian') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('subkomponen_uraian')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Level 4: Akun Belanja (Pokok 6-Digit) -->
                    <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-200 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-600 text-white uppercase tracking-wider">Level 4</span>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Akun Belanja (Mata Anggaran Pokok)</h4>
                            </div>
                            <span class="text-[11px] font-semibold text-emerald-800">Klik salah satu chip di bawah untuk isi otomatis</span>
                        </div>

                        <!-- Quick Chips Akun Perjalanan Dinas -->
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" onclick="setQuickAkun('524111', 'Belanja Perjalanan Dinas Biasa')" class="px-3 py-1.5 text-xs font-semibold bg-white hover:bg-emerald-600 hover:text-white text-emerald-800 border border-emerald-300 rounded-xl shadow-2xs transition active:scale-95 flex items-center gap-1.5">
                                <span class="font-mono font-bold">524111</span>
                                <span>Perjadin Biasa / Luar Kota</span>
                            </button>
                            <button type="button" onclick="setQuickAkun('524113', 'Belanja Perjalanan Dinas Dalam Kota')" class="px-3 py-1.5 text-xs font-semibold bg-white hover:bg-emerald-600 hover:text-white text-emerald-800 border border-emerald-300 rounded-xl shadow-2xs transition active:scale-95 flex items-center gap-1.5">
                                <span class="font-mono font-bold">524113</span>
                                <span>Perjadin Dalam Kota</span>
                            </button>
                            <button type="button" onclick="setQuickAkun('524114', 'Belanja Perjalanan Dinas Paket Meeting Dalam Kota')" class="px-3 py-1.5 text-xs font-semibold bg-white hover:bg-emerald-600 hover:text-white text-emerald-800 border border-emerald-300 rounded-xl shadow-2xs transition active:scale-95 flex items-center gap-1.5">
                                <span class="font-mono font-bold">524114</span>
                                <span>Paket Meeting</span>
                            </button>
                            <button type="button" onclick="setQuickAkun('524119', 'Belanja Perjalanan Dinas Lainnya')" class="px-3 py-1.5 text-xs font-semibold bg-white hover:bg-emerald-600 hover:text-white text-emerald-800 border border-emerald-300 rounded-xl shadow-2xs transition active:scale-95 flex items-center gap-1.5">
                                <span class="font-mono font-bold">524119</span>
                                <span>Perjadin Lainnya</span>
                            </button>
                            <button type="button" onclick="setQuickAkun('521213', 'Belanja Honor Output Kegiatan')" class="px-3 py-1.5 text-xs font-semibold bg-white hover:bg-emerald-600 hover:text-white text-emerald-800 border border-emerald-300 rounded-xl shadow-2xs transition active:scale-95 flex items-center gap-1.5">
                                <span class="font-mono font-bold">521213</span>
                                <span>Honor Output Kegiatan</span>
                            </button>
                        </div>

                        <!-- Input Akun Kode & Uraian -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                            <div>
                                <label for="akun_kode" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Kode Akun (6 Digit)
                                </label>
                                <input id="akun_kode" name="akun_kode" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs font-mono font-bold" placeholder="Contoh: 524111" value="{{ old('akun_kode') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('akun_kode')" class="mt-1" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="akun_uraian" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Uraian Akun Belanja
                                </label>
                                <input id="akun_uraian" name="akun_uraian" type="text" class="block w-full border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-xs font-medium" placeholder="Contoh: Belanja Perjalanan Dinas Biasa" value="{{ old('akun_uraian') }}" oninput="updateLivePreview()" />
                                <x-input-error :messages="$errors->get('akun_uraian')" class="mt-1" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 3: LIVE PREVIEW & VISUALISASI STRUKTUR DIPA -->
                <!-- ======================================================== -->
                <div class="bg-gradient-to-br from-gray-900 via-slate-900 to-teal-950 text-white rounded-2xl p-6 md:p-7 shadow-md border border-gray-800">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-white/10">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-300">Pratinjau Langsung Nomenklatur Anggaran (Live Preview)</h4>
                        </div>
                        <span id="preview-status" class="text-[10px] font-mono text-emerald-400 bg-emerald-950/80 px-2 py-0.5 rounded border border-emerald-800">
                            Sinkron Real-time
                        </span>
                    </div>

                    <!-- Tree Segments Breakdown -->
                    <div class="space-y-3">
                        <div>
                            <span class="text-[11px] text-gray-400 block mb-1">Mata Anggaran Kegiatan (MAK Final):</span>
                            <div class="flex items-center gap-2">
                                <span id="preview-mak" class="text-lg md:text-xl font-black font-mono text-white tracking-wider bg-white/10 px-3 py-1.5 rounded-xl border border-white/10 inline-block">
                                    {{ old('mak') ?: '- Belum Diisi -' }}
                                </span>
                            </div>
                        </div>

                        <!-- Hierarchical Tags Breadcrumb -->
                        <div class="pt-2">
                            <span class="text-[11px] text-gray-400 block mb-1.5">Alur Struktur Hierarki Belanja:</span>
                            <div id="preview-breadcrumb" class="flex flex-wrap items-center gap-1.5 text-xs font-mono">
                                <span class="px-2 py-1 bg-white/10 text-emerald-300 rounded-lg border border-white/10 font-bold">
                                    Tahun: {{ $selectedTahun }}
                                </span>
                                <span class="text-gray-500">›</span>
                                <span id="preview-p-prog" class="px-2 py-1 bg-white/5 text-gray-300 rounded-lg border border-white/5">
                                    Prog: -
                                </span>
                                <span class="text-gray-500">›</span>
                                <span id="preview-p-keg" class="px-2 py-1 bg-white/5 text-gray-300 rounded-lg border border-white/5">
                                    Keg: -
                                </span>
                                <span class="text-gray-500">›</span>
                                <span id="preview-p-out" class="px-2 py-1 bg-white/5 text-gray-300 rounded-lg border border-white/5">
                                    Out: -
                                </span>
                                <span class="text-gray-500">›</span>
                                <span id="preview-p-komp" class="px-2 py-1 bg-white/5 text-gray-300 rounded-lg border border-white/5">
                                    Komp: -
                                </span>
                                <span class="text-gray-500">›</span>
                                <span id="preview-p-akun" class="px-2 py-1 bg-emerald-500/20 text-emerald-300 rounded-lg border border-emerald-500/30 font-bold">
                                    Akun: -
                                </span>
                            </div>
                        </div>

                        <div class="pt-2 text-xs text-gray-400">
                            <span class="text-gray-300 font-semibold" id="preview-desc">Uraian Akun: -</span>
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 4: PANDUAN STANDAR AKUN PERJADIN BPS / KEMENKEU -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 sm:p-6">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Referensi Standar Kode Akun Belanja Perjalanan Dinas (PMK / BPS)</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                            <span class="font-mono font-bold text-emerald-700 block">524111 - Perjadin Biasa</span>
                            <p class="text-gray-500 mt-1 text-[11px] leading-relaxed">
                                Digunakan untuk perjalanan dinas luar kota/luar wilayah penugasan dengan komponen uang harian penuh, transport, dan penginapan.
                            </p>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                            <span class="font-mono font-bold text-emerald-700 block">524113 - Perjadin Dalam Kota</span>
                            <p class="text-gray-500 mt-1 text-[11px] leading-relaxed">
                                Digunakan untuk penugasan pengawasan/supervisi/pendataan di dalam wilayah kota/kabupaten kerja (di atas 8 jam atau riil transport).
                            </p>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200">
                            <span class="font-mono font-bold text-emerald-700 block">524114 - Paket Meeting Dalam Kota</span>
                            <p class="text-gray-500 mt-1 text-[11px] leading-relaxed">
                                Digunakan untuk kegiatan rapat, konsinyasi, sosialisasi, atau evaluasi yang diselenggarakan dalam format meeting di hotel.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 5: FOOTER ACTIONS -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col sm:flex-row items-center justify-between gap-4 sticky bottom-4 z-10">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pastikan kode MAK dan rincian akun sudah tepat sebelum menyimpan data anggaran.</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('anggarans.index') }}" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition text-center">
                            Batal
                        </a>
                        <button type="submit" class="px-7 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-emerald-600/30 flex items-center justify-center gap-2 transition active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Data Anggaran</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Set Akun Cepat dari Chips
        function setQuickAkun(kode, uraian) {
            const akunKodeInput = document.getElementById('akun_kode');
            const akunUraianInput = document.getElementById('akun_uraian');
            const makInput = document.getElementById('mak');

            if (akunKodeInput) akunKodeInput.value = kode;
            if (akunUraianInput) akunUraianInput.value = uraian;

            // Jika MAK belum berakhiran kode akun, tawarkan untuk melengkapi atau tambahkan otomatis jika kosong
            if (makInput && (!makInput.value || makInput.value.trim() === '')) {
                const prog = document.getElementById('program_kode').value || '054.01.WA';
                const keg = document.getElementById('kegiatan_kode').value || '2895';
                makInput.value = `${prog}.${keg}.${kode}`;
            } else if (makInput && !makInput.value.includes(kode)) {
                // Periksa apakah bagian akhir adalah 6 digit angka
                const parts = makInput.value.trim().split('.');
                const lastPart = parts[parts.length - 1];
                if (/^\d{6}$/.test(lastPart)) {
                    parts[parts.length - 1] = kode;
                    makInput.value = parts.join('.');
                }
            }

            updateLivePreview();
        }

        // Sisip Prefix MAK
        function insertMakPrefix(prefix) {
            const makInput = document.getElementById('mak');
            if (!makInput) return;
            if (!makInput.value.startsWith(prefix)) {
                makInput.value = prefix + makInput.value;
            }
            makInput.focus();
            updateLivePreview();
        }

        // Tambah Suffix Akun MAK
        function appendMakSuffix(suffix) {
            const makInput = document.getElementById('mak');
            if (!makInput) return;
            if (!makInput.value.endsWith(suffix.replace('.', ''))) {
                makInput.value = makInput.value.trim() + suffix;
            }
            makInput.focus();
            updateLivePreview();
        }

        // Susun MAK Lengkap dari Segmen
        function autoFillMakFromSegments() {
            const pKode = document.getElementById('program_kode').value.trim();
            const kKode = document.getElementById('kegiatan_kode').value.trim();
            const oKode = document.getElementById('output_kode').value.trim();
            const soKode = document.getElementById('suboutput_kode').value.trim();
            const kompKode = document.getElementById('komponen_kode').value.trim();
            const skompKode = document.getElementById('subkomponen_kode').value.trim();
            const aKode = document.getElementById('akun_kode').value.trim();

            const segments = [pKode, kKode, oKode, soKode, kompKode, skompKode, aKode].filter(Boolean);

            if (segments.length === 0) {
                alert('Silakan isi beberapa kode segmen DIPA (Program, Kegiatan, atau Akun) terlebih dahulu.');
                return;
            }

            document.getElementById('mak').value = segments.join('.');
            updateLivePreview();
        }

        // Perbarui Live Preview
        function updateLivePreview() {
            const makVal = document.getElementById('mak').value.trim();
            const pKode = document.getElementById('program_kode').value.trim();
            const kKode = document.getElementById('kegiatan_kode').value.trim();
            const oKode = document.getElementById('output_kode').value.trim();
            const kompKode = document.getElementById('komponen_kode').value.trim();
            const aKode = document.getElementById('akun_kode').value.trim();
            const aUraian = document.getElementById('akun_uraian').value.trim();

            // Preview MAK
            const previewMak = document.getElementById('preview-mak');
            if (previewMak) {
                previewMak.innerText = makVal || '- Belum Diisi -';
            }

            // Preview Segments
            const pProg = document.getElementById('preview-p-prog');
            if (pProg) pProg.innerText = pKode ? `Prog: ${pKode}` : 'Prog: -';

            const pKeg = document.getElementById('preview-p-keg');
            if (pKeg) pKeg.innerText = kKode ? `Keg: ${kKode}` : 'Keg: -';

            const pOut = document.getElementById('preview-p-out');
            if (pOut) pOut.innerText = oKode ? `Out: ${oKode}` : 'Out: -';

            const pKomp = document.getElementById('preview-p-komp');
            if (pKomp) pKomp.innerText = kompKode ? `Komp: ${kompKode}` : 'Komp: -';

            const pAkun = document.getElementById('preview-p-akun');
            if (pAkun) pAkun.innerText = aKode ? `Akun: ${aKode}` : 'Akun: -';

            const pDesc = document.getElementById('preview-desc');
            if (pDesc) {
                pDesc.innerText = aUraian ? `Uraian Akun: ${aUraian}` : 'Uraian Akun: -';
            }
        }

        // Jalankan saat halaman siap
        document.addEventListener('DOMContentLoaded', function() {
            updateLivePreview();
        });
    </script>
</x-app-layout>
