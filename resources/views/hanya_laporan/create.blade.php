<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('hanya-laporan.index') }}" class="text-xs font-semibold text-purple-600 hover:text-purple-800 flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Hanya Laporan
                    </a>
                    <span class="text-gray-300">•</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                        ✨ AI-Powered Generator
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        📸 Support Foto WebP & GPS
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-purple-600 to-indigo-600 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <span>{{ __('Buat Hanya Laporan (Generate AI)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1 max-w-3xl">
                    Buat laporan perjalanan dinas lepas secara mandiri dan fleksibel tanpa harus terikat ke siklus Surat Tugas & SPD internal. Lengkapi parameter di bawah ini, dan biarkan AI menyusun narasi formal kedinasan yang rapi dan akuntabel.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a href="{{ route('hanya-laporan.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition">
                    Daftar Laporan
                </a>
                <a href="{{ route('laporan.create') }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-semibold shadow-xs transition" title="Buka modul laporan berbasis Surat Tugas database">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    Mode Terikat Surat Tugas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @php
                $copiedLaporan = $copiedLaporan ?? null;
                $previousReports = $previousReports ?? collect();
            @endphp

            <!-- CARD 0: FITUR SMART COPY DARI LAPORAN SEBELUMNYA -->
            <div class="bg-gradient-to-r from-purple-50 via-indigo-50/50 to-white rounded-2xl border border-purple-200/80 p-5 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-purple-600 text-white rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-purple-950 flex items-center gap-2">
                                Salin Cepat dari Laporan Sebelumnya
                                <span class="text-[10px] bg-purple-200/70 text-purple-800 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    Hemat Waktu
                                </span>
                            </h3>
                            <p class="text-xs text-purple-700/80">Pilih riwayat laporan untuk menyalin isian Nama Pegawai, ST/SPD, Lokasi, dan Kategori secara otomatis.</p>
                        </div>
                    </div>

                    @if($copiedLaporan)
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 text-xs bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-lg border border-emerald-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Data Berhasil Disalin
                            </span>
                            <a href="{{ route('hanya-laporan.create') }}" class="text-xs text-purple-700 hover:text-purple-900 font-semibold underline decoration-purple-300 hover:decoration-purple-700">
                                Reset (Form Kosong)
                            </a>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-2.5 items-center">
                    <div class="w-full">
                        <select id="copy_laporan_select" class="block w-full border-purple-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-xs select2">
                            <option value="">-- Ketik atau Pilih Laporan Sebelumnya untuk Disalin --</option>
                            @foreach($previousReports as $prev)
                                @php
                                    $prevData = [
                                        'id' => $prev->id,
                                        'nama_pegawai' => $prev->nama_pegawai,
                                        'nip_pegawai' => $prev->nip_pegawai ?? '',
                                        'nomor_st' => $prev->nomor_st,
                                        'nomor_spd' => $prev->nomor_spd ?? '',
                                        'tgl_laporan' => $prev->tgl_laporan ? ($prev->tgl_laporan instanceof \Carbon\CarbonInterface ? $prev->tgl_laporan->format('Y-m-d') : date('Y-m-d', strtotime($prev->tgl_laporan))) : date('Y-m-d'),
                                        'tgl_perjalanan' => $prev->tgl_perjalanan ? ($prev->tgl_perjalanan instanceof \Carbon\CarbonInterface ? $prev->tgl_perjalanan->format('Y-m-d') : date('Y-m-d', strtotime($prev->tgl_perjalanan))) : '',
                                        'lokasi_perjalanan' => $prev->lokasi_perjalanan,
                                        'tujuan_perjalanan' => $prev->tujuan_perjalanan,
                                        'pegawai_ditemui' => $prev->pegawai_ditemui,
                                        'kategori' => $prev->kategori,
                                        'kendala_ditemui' => $prev->kendala_ditemui ?? '',
                                    ];
                                @endphp
                                <option value="{{ $prev->id }}" 
                                        data-report='@json($prevData)'
                                        {{ (isset($copiedLaporan->id) && $copiedLaporan->id == $prev->id) ? 'selected' : '' }}>
                                    {{ $prev->tgl_perjalanan ? ($prev->tgl_perjalanan instanceof \Carbon\CarbonInterface ? $prev->tgl_perjalanan->format('d/m/Y') : date('d/m/Y', strtotime($prev->tgl_perjalanan))) : '-' }} • {{ $prev->nama_pegawai }} • {{ $prev->tujuan_perjalanan }} (ST: {{ $prev->nomor_st }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="applyCopyLaporan()" class="w-full sm:w-auto shrink-0 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-sm inline-flex items-center justify-center gap-1.5 transition active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        Terapkan Salinan
                    </button>
                </div>
                
                <div id="copy-alert" class="mt-2.5 text-xs text-purple-800 bg-purple-100/80 border border-purple-200 px-3.5 py-2 rounded-xl {{ $copiedLaporan ? '' : 'hidden' }}">
                    @if($copiedLaporan)
                        Isian dari <strong>Nama Pegawai</strong> hingga <strong>Kendala</strong> berhasil disalin dari laporan sebelumnya (<em>{{ $copiedLaporan->nama_pegawai }}</em>). Anda tetap dapat menyesuaikan data sebelum men-generate narasi AI.
                    @endif
                </div>
            </div>

            <!-- FORM UTAMA -->
            <form id="laporan-form" method="POST" action="{{ route('hanya-laporan.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- SECTION 1: IDENTITAS PELAKSANA & SURAT TUGAS -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Identitas Pelaksana & Administrasi Surat</h3>
                            <p class="text-xs text-gray-500">Tentukan pelaksana tugas serta nomor surat penugasan dinas.</p>
                        </div>
                    </div>

                    @php
                        $currentNama = old('nama_pegawai', $copiedLaporan->nama_pegawai ?? '');
                        $currentNip = old('nip_pegawai', $copiedLaporan->nip_pegawai ?? '');
                        $namaInDb = !empty($currentNama) && $pegawais->contains('nama', $currentNama);
                        $isCustomPegawai = !empty($currentNama) && !$namaInDb;
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Nama Pegawai -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="nama_pegawai_select" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Nama Pegawai Pelaksana <span class="text-rose-500">*</span>
                                </label>
                                <label class="inline-flex items-center text-xs font-semibold text-purple-700 hover:text-purple-900 cursor-pointer select-none bg-purple-50 hover:bg-purple-100 px-2.5 py-0.5 rounded-full border border-purple-200 transition">
                                    <input type="checkbox" id="check_custom_pegawai" class="rounded border-purple-300 text-purple-600 shadow-xs mr-1.5 focus:ring-purple-500" onchange="toggleCustomPegawai()" {{ $isCustomPegawai ? 'checked' : '' }}>
                                    Nama Custom (Non-Database)
                                </label>
                            </div>

                            <div id="wrapper_select_pegawai" class="{{ $isCustomPegawai ? 'hidden' : '' }}">
                                <select id="nama_pegawai_select" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs select2">
                                    <option value="">-- Pilih atau Cari Pegawai Terdaftar --</option>
                                    @foreach($pegawais as $p)
                                        <option value="{{ $p->nama }}" data-nip="{{ $p->nip }}" {{ (!$isCustomPegawai && $currentNama == $p->nama) ? 'selected' : '' }}>
                                            {{ $p->nama }} {{ $p->nip ? '('.$p->nip.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-gray-400 mt-1">Pilih pegawai internal organik BPS untuk mengaitkan NIP secara otomatis.</p>
                            </div>

                            <div id="wrapper_custom_pegawai" class="{{ $isCustomPegawai ? '' : 'hidden' }}">
                                <input type="text" id="nama_pegawai_custom" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm" placeholder="Ketik nama lengkap pegawai / mitra luar..." value="{{ $isCustomPegawai ? $currentNama : '' }}">
                                <p class="text-[11px] text-amber-600 mt-1">Mode nama custom aktif: digunakan untuk mitra statistik, pegawai luar, atau non-PNS.</p>
                            </div>

                            <input type="hidden" name="nama_pegawai" id="nama_pegawai" value="{{ $currentNama }}">
                            <x-input-error :messages="$errors->get('nama_pegawai')" class="mt-1.5" />
                        </div>

                        <!-- NIP Pegawai -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="nip_pegawai" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    NIP Pegawai <span class="text-xs font-normal text-gray-400 capitalize">(Opsional)</span>
                                </label>
                                <span class="text-[11px] text-gray-400">Kosongkan jika bukan PNS/PPPK</span>
                            </div>
                            <input type="text" id="nip_pegawai" name="nip_pegawai" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm font-mono" placeholder="Contoh: 198501012010121001" value="{{ $currentNip }}">
                            <x-input-error :messages="$errors->get('nip_pegawai')" class="mt-1.5" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Nomor Surat Tugas -->
                        <div>
                            <label for="nomor_st" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Nomor Surat Tugas (ST) <span class="text-rose-500">*</span>
                            </label>
                            <input id="nomor_st" type="text" name="nomor_st" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm font-mono" placeholder="Contoh: 012/ST/5202/2026" value="{{ old('nomor_st', $copiedLaporan->nomor_st ?? '') }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Nomor surat penugasan dinas resmi.</p>
                            <x-input-error :messages="$errors->get('nomor_st')" class="mt-1.5" />
                        </div>

                        <!-- Nomor SPD -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="nomor_spd" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Nomor SPD <span class="text-xs font-normal text-gray-400 capitalize">(Opsional)</span>
                                </label>
                                <span class="text-[11px] text-gray-400">Jika ada surat perjalanan dinas</span>
                            </div>
                            <input id="nomor_spd" type="text" name="nomor_spd" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm font-mono" placeholder="Contoh: 012/SPD/5202/2026" value="{{ old('nomor_spd', $copiedLaporan->nomor_spd ?? '') }}" />
                            <x-input-error :messages="$errors->get('nomor_spd')" class="mt-1.5" />
                        </div>
                    </div>

                    @php
                        $copiedTglLaporan = null;
                        if (isset($copiedLaporan->tgl_laporan)) {
                            $copiedTglLaporan = $copiedLaporan->tgl_laporan instanceof \Carbon\CarbonInterface 
                                ? $copiedLaporan->tgl_laporan->format('Y-m-d') 
                                : ($copiedLaporan->tgl_laporan ? date('Y-m-d', strtotime($copiedLaporan->tgl_laporan)) : null);
                        }
                        $defaultTglLaporan = $copiedTglLaporan ?: date('Y-m-d');

                        $copiedTglPerjalanan = null;
                        if (isset($copiedLaporan->tgl_perjalanan)) {
                            $copiedTglPerjalanan = $copiedLaporan->tgl_perjalanan instanceof \Carbon\CarbonInterface 
                                ? $copiedLaporan->tgl_perjalanan->format('Y-m-d') 
                                : ($copiedLaporan->tgl_perjalanan ? date('Y-m-d', strtotime($copiedLaporan->tgl_perjalanan)) : null);
                        }
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Tanggal Laporan Dibuat -->
                        <div>
                            <label for="tgl_laporan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tanggal Laporan Dibuat <span class="text-rose-500">*</span>
                            </label>
                            <input id="tgl_laporan" type="date" name="tgl_laporan" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm" value="{{ old('tgl_laporan', $defaultTglLaporan) }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Tanggal penetapan tanda tangan laporan.</p>
                            <x-input-error :messages="$errors->get('tgl_laporan')" class="mt-1.5" />
                        </div>

                        <!-- Tanggal Perjalanan Pelaksanaan -->
                        <div>
                            <label for="tgl_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tanggal Pelaksanaan Perjalanan <span class="text-rose-500">*</span>
                            </label>
                            <input id="tgl_perjalanan" type="date" name="tgl_perjalanan" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm" value="{{ old('tgl_perjalanan', $copiedTglPerjalanan ?? '') }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Tanggal riil kegiatan bertugas di lapangan.</p>
                            <x-input-error :messages="$errors->get('tgl_perjalanan')" class="mt-1.5" />
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: DETAIL KEGIATAN & LAPANGAN -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">2</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Detail Kegiatan & Lokasi Lapangan</h3>
                            <p class="text-xs text-gray-500">Data lapangan ini menjadi parameter utama AI untuk menyusun teks narasi laporan dinas.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Lokasi Perjalanan -->
                        <div>
                            <label for="lokasi_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Lokasi Tujuan / Instansi / Desa <span class="text-rose-500">*</span>
                            </label>
                            <input id="lokasi_perjalanan" type="text" name="lokasi_perjalanan" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm" placeholder="Contoh: Kantor Desa Sukamaju, Kec. Jonggat" value="{{ old('lokasi_perjalanan', $copiedLaporan->lokasi_perjalanan ?? '') }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Nama desa, kecamatan, instansi, atau tempat yang dikunjungi.</p>
                            <x-input-error :messages="$errors->get('lokasi_perjalanan')" class="mt-1.5" />
                        </div>

                        <!-- Tujuan Perjalanan Singkat -->
                        <div>
                            <label for="tujuan_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tujuan Perjalanan (Singkat & Jelas) <span class="text-rose-500">*</span>
                            </label>
                            <input id="tujuan_perjalanan" type="text" name="tujuan_perjalanan" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm" placeholder="Contoh: Koordinasi teknis pelaksanaan survei ekonomi 2026" value="{{ old('tujuan_perjalanan', $copiedLaporan->tujuan_perjalanan ?? '') }}" required oninput="reprocessImages()" />
                            <p class="text-[11px] text-gray-400 mt-1">Teks ini juga dicantumkan pada stamp watermark foto dokumentasi.</p>
                            <x-input-error :messages="$errors->get('tujuan_perjalanan')" class="mt-1.5" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Pihak / Pegawai yang Ditemui -->
                        <div>
                            <label for="pegawai_ditemui" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Pihak / Pegawai yang Ditemui <span class="text-rose-500">*</span>
                            </label>
                            <input id="pegawai_ditemui" type="text" name="pegawai_ditemui" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm" placeholder="Contoh: Kepala Desa Bpk. H. Ahmad dan Sekretaris Desa" value="{{ old('pegawai_ditemui', $copiedLaporan->pegawai_ditemui ?? '') }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Nama pejabat, staf, responden, atau tokoh yang diwawancarai/ditemui.</p>
                            <x-input-error :messages="$errors->get('pegawai_ditemui')" class="mt-1.5" />
                        </div>

                        <!-- Kategori Perjalanan -->
                        <div>
                            @php
                                $standardKategori = ['Translok Koordinasi', 'Pengawasan / Supervisi Lapangan', 'Verifikasi Data', 'Pendataan Lapangan'];
                                $rawKategori = old('kategori', $copiedLaporan->kategori ?? '');
                                $isCustomKategori = !empty($rawKategori) && !in_array($rawKategori, $standardKategori);
                                $selectedKategori = $isCustomKategori ? 'Custom' : ($rawKategori ?: 'Translok Koordinasi');
                                $customKategoriVal = $isCustomKategori ? old('kategori_custom', $rawKategori) : old('kategori_custom', '');
                            @endphp
                            <label for="kategori" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Kategori Perjalanan <span class="text-rose-500">*</span>
                            </label>
                            
                            <!-- Chips Cepat -->
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @foreach($standardKategori as $kat)
                                    <button type="button" onclick="setKategori('{{ $kat }}')" class="px-2.5 py-1 text-[11px] font-semibold rounded-lg border transition {{ $selectedKategori === $kat ? 'bg-purple-100 text-purple-800 border-purple-300' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                                        {{ $kat }}
                                    </button>
                                @endforeach
                                <button type="button" onclick="setKategori('Custom')" class="px-2.5 py-1 text-[11px] font-semibold rounded-lg border transition {{ $selectedKategori === 'Custom' ? 'bg-purple-100 text-purple-800 border-purple-300' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                                    + Custom
                                </button>
                            </div>

                            <select id="kategori" name="kategori" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-sm" required onchange="toggleCustomKategori()">
                                <option value="Translok Koordinasi" {{ $selectedKategori == 'Translok Koordinasi' ? 'selected' : '' }}>Translok Koordinasi</option>
                                <option value="Pengawasan / Supervisi Lapangan" {{ $selectedKategori == 'Pengawasan / Supervisi Lapangan' ? 'selected' : '' }}>Pengawasan / Supervisi Lapangan</option>
                                <option value="Verifikasi Data" {{ $selectedKategori == 'Verifikasi Data' ? 'selected' : '' }}>Verifikasi Data</option>
                                <option value="Pendataan Lapangan" {{ $selectedKategori == 'Pendataan Lapangan' ? 'selected' : '' }}>Pendataan Lapangan</option>
                                <option value="Custom" {{ $selectedKategori == 'Custom' ? 'selected' : '' }}>Custom (Isi Sendiri)</option>
                            </select>

                            <input type="text" id="kategori_custom" name="kategori_custom" class="mt-2.5 block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-sm {{ $isCustomKategori ? '' : 'hidden' }}" placeholder="Ketik kategori kegiatan khusus Anda..." value="{{ $customKategoriVal }}" {{ $isCustomKategori ? 'required' : '' }}>
                            <x-input-error :messages="$errors->get('kategori')" class="mt-1.5" />
                        </div>
                    </div>

                    <!-- Kendala yang Ditemui -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="kendala_ditemui" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Kendala / Catatan Lapangan <span class="text-xs font-normal text-gray-400 capitalize">(Opsional)</span>
                            </label>
                            <span class="text-[11px] text-gray-400">Kosongkan jika kegiatan lancar tanpa kendala</span>
                        </div>
                        <textarea id="kendala_ditemui" name="kendala_ditemui" rows="2" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-sm" placeholder="Contoh: Terjadi hujan deras saat menuju lokasi, responden tidak berada di tempat sehingga dijadwalkan ulang... (Biarkan kosong jika perjalanan lancar)">{{ old('kendala_ditemui', $copiedLaporan->kendala_ditemui ?? '') }}</textarea>
                        <p class="text-[11px] text-gray-400 mt-1">💡 Tips: Jika ada kendala, AI akan otomatis membuatkan bagian evaluasi dan solusi tindak lanjut pada narasi laporan.</p>
                        <x-input-error :messages="$errors->get('kendala_ditemui')" class="mt-1.5" />
                    </div>
                </div>

                <!-- SECTION 3: LAMPIRAN DOKUMEN TAMBAHAN (DPR & PERNYATAAN RIIL) -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 font-bold text-xs flex items-center justify-center">3</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Lampiran Dokumen Keuangan & Pertanggungjawaban</h3>
                            <p class="text-xs text-gray-500">Opsional untuk melampirkan Daftar Pengeluaran Riil (DPR) dan Surat Pernyataan dalam dokumen PDF gabungan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Opsi DPR -->
                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/60 hover:border-purple-200 transition">
                            <div class="flex items-start gap-3">
                                <div class="pt-0.5">
                                    <input type="checkbox" id="check_dpr" name="check_dpr" value="1" class="w-4 h-4 rounded border-gray-300 text-purple-600 shadow-xs focus:ring-purple-500" onchange="toggleDprInputs()" {{ old('check_dpr', $copiedLaporan->ada_dpr ?? false) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="check_dpr" class="text-xs font-bold text-gray-900 cursor-pointer uppercase tracking-wider block">
                                        Lampirkan Daftar Pengeluaran Riil (DPR)
                                    </label>
                                    <p class="text-xs text-gray-500 mt-0.5">Lampiran pertanggungjawaban biaya riil (uang harian/transport) sesuai standar biaya masukan.</p>
                                </div>
                            </div>

                            <div id="dpr_inputs" class="mt-4 pt-4 border-t border-gray-200 space-y-3 {{ old('check_dpr', $copiedLaporan->ada_dpr ?? false) ? '' : 'hidden' }}">
                                <div>
                                    <label for="nilai_dpr" class="block text-xs font-semibold text-gray-700 mb-1">
                                        Total Nilai Pengeluaran Riil (Rp) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative rounded-xl shadow-xs">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs font-bold font-mono">Rp</span>
                                        </div>
                                        <input id="nilai_dpr" name="nilai_dpr" type="number" class="block w-full pl-10 border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl text-sm font-mono" placeholder="Contoh: 150000" value="{{ old('nilai_dpr', $copiedLaporan->nilai_dpr ?? '') }}" />
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1">Ketik nominal angka bulat tanpa titik/koma (misal 150000).</p>
                                    <x-input-error :messages="$errors->get('nilai_dpr')" class="mt-1" />
                                </div>

                                <div>
                                    <label for="ppk_pegawai_id" class="block text-xs font-semibold text-gray-700 mb-1">
                                        Pejabat Pembuat Komitmen (PPK)
                                    </label>
                                    <select id="ppk_pegawai_id" name="ppk_pegawai_id" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-xs">
                                        <option value="">-- Pilih PPK Penandatangan (Opsional) --</option>
                                        @forelse($ppkPegawais as $p)
                                            <option value="{{ $p->id }}" {{ old('ppk_pegawai_id', $copiedLaporan->ppk_pegawai_id ?? '') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama }} (NIP. {{ $p->nip ?? '-' }})
                                            </option>
                                        @empty
                                            <option value="" disabled class="text-rose-500 font-medium">⚠️ Belum ada pegawai bertag PPK di database</option>
                                        @endforelse
                                    </select>
                                    <x-input-error :messages="$errors->get('ppk_pegawai_id')" class="mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- Opsi Surat Pernyataan -->
                        <div id="wrapper_pernyataan_card" class="p-4 rounded-xl border border-gray-200 bg-gray-50/60 hover:border-purple-200 transition {{ $isCustomPegawai ? 'hidden' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="pt-0.5">
                                    <input type="checkbox" id="check_pernyataan" name="check_pernyataan" value="1" class="w-4 h-4 rounded border-gray-300 text-purple-600 shadow-xs focus:ring-purple-500" {{ old('check_pernyataan', $copiedLaporan->ada_pernyataan ?? false) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="check_pernyataan" class="text-xs font-bold text-gray-900 cursor-pointer uppercase tracking-wider block">
                                        Lampirkan Surat Pernyataan Riil
                                    </label>
                                    <p class="text-xs text-gray-500 mt-0.5">Surat pernyataan resmi tidak menggunakan kendaraan dinas (Khusus Pegawai Organik internal yang terdaftar).</p>
                                    <div class="mt-3 flex items-center gap-1.5 text-[11px] text-gray-500 bg-white p-2.5 rounded-lg border border-gray-200">
                                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Format baku sesuai ketentuan audit BPK untuk kelengkapan administrasi SPPD.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: FOTO DOKUMENTASI (SUPPORT WEBP, JPG, PNG) & WATERMARK GPS -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center">4</span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                    Foto Dokumentasi Kegiatan
                                    <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider border border-emerald-200">
                                        Support WebP Kamera HP
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-500">Unggah foto bukti kunjungan/kegiatan. Mendukung format kamera smartphone masa kini.</p>
                            </div>
                        </div>

                        <!-- Format Support Badges -->
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">JPG</span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">PNG</span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-300 shadow-2xs">✨ WEBP</span>
                        </div>
                    </div>

                    <!-- Dropzone Area -->
                    <div id="dropzone" class="relative border-2 border-dashed border-purple-300 hover:border-purple-500 bg-purple-50/30 hover:bg-purple-50/60 rounded-2xl p-6 text-center transition cursor-pointer group" onclick="document.getElementById('fotos').click()">
                        <input id="fotos" name="fotos[]" type="file" multiple accept="image/jpeg,image/png,image/jpg,image/webp,image/*" class="sr-only" onchange="handleFotosChange(event)" />
                        
                        <div class="max-w-md mx-auto space-y-2 pointer-events-none">
                            <div class="w-12 h-12 mx-auto rounded-full bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="text-xs text-gray-700">
                                <span class="font-bold text-purple-700 hover:underline">Klik untuk Memilih Foto</span> atau seret & lepas berkas ke area ini
                            </div>
                            <p class="text-[11px] text-gray-500 leading-relaxed">
                                Mendukung <strong>WEBP (Format foto kamera HP Android/iPhone)</strong>, <strong>JPG/JPEG</strong>, dan <strong>PNG</strong>. Maksimal 5MB per file foto. Anda dapat memilih lebih dari satu foto sekaligus.
                            </p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('fotos.*')" class="mt-1.5" />

                    <!-- STAMP WATERMARK GPS & TANGGAL -->
                    <div class="mt-5 p-5 border border-gray-200 rounded-2xl bg-gray-50/70">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="check_stamp" name="check_stamp" class="w-4 h-4 rounded border-gray-300 text-purple-600 shadow-xs focus:ring-purple-500" onchange="toggleStampInputs()">
                                <div>
                                    <label for="check_stamp" class="text-xs font-bold text-gray-900 cursor-pointer uppercase tracking-wider block">
                                        Tambahkan Stamp Watermark Geotag & Waktu pada Foto
                                    </label>
                                    <p class="text-xs text-gray-500">Mencetak stamp koordinat GPS dan tanggal/jam langsung pada foto dokumentasi laporan.</p>
                                </div>
                            </div>
                            
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full self-start sm:self-auto">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Rekomendasi Akuntabilitas
                            </span>
                        </div>

                        <div id="stamp_inputs" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200">
                            <!-- Koordinat GPS -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="stamp_koordinat" class="block text-xs font-semibold text-gray-700">
                                        Koordinat Lokasi GPS (Latitude, Longitude)
                                    </label>
                                    <button type="button" onclick="getLocation()" id="btn-gps" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Deteksi GPS Saat Ini</span>
                                    </button>
                                </div>
                                <input id="stamp_koordinat" name="stamp_koordinat" type="text" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-xs font-mono" placeholder="Contoh: -8.583333, 116.116667" onchange="formatKoordinat(this)" />
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-[11px] text-gray-400">Format derajat desimal dipisah koma</span>
                                    <a id="link-gmaps" href="#" target="_blank" class="text-[11px] text-blue-600 hover:underline hidden">Lihat di Google Maps ↗</a>
                                </div>
                                <x-input-error :messages="$errors->get('stamp_koordinat')" class="mt-1" />
                            </div>

                            <!-- Tanggal & Jam Stamp -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="stamp_datetime" class="block text-xs font-semibold text-gray-700">
                                        Tanggal & Jam Foto Dokumentasi
                                    </label>
                                    <button type="button" onclick="setNowDateTime()" class="text-xs font-semibold text-purple-700 hover:text-purple-900">
                                        Set Jam Sekarang
                                    </button>
                                </div>
                                <input id="stamp_datetime" name="stamp_datetime" type="datetime-local" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-xs" onchange="reprocessImages()" />
                                <p class="text-[11px] text-gray-400 mt-1">Waktu yang akan dicetak pada watermark foto.</p>
                                <x-input-error :messages="$errors->get('stamp_datetime')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- CONTAINER PREVIEW FOTO -->
                    <div id="image-preview-wrapper" class="mt-5 hidden">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                                <span>Pratinjau Foto Dokumentasi</span>
                                <span id="preview-count" class="text-[10px] bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full font-bold">0 Foto</span>
                            </h4>
                            <span class="text-[11px] text-gray-400">Klik tanda silang (×) pada foto untuk membatalkan unggahan</span>
                        </div>
                        <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5"></div>
                    </div>
                </div>

                <!-- SECTION 5: STUDIO AI GENERATOR (GEMINI AI REPORT ENGINE) -->
                <div class="bg-gradient-to-br from-purple-50/90 via-indigo-50/50 to-white rounded-2xl border-2 border-purple-300/80 shadow-md p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-purple-200">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white font-black text-sm flex items-center justify-center shadow-xs">
                                5
                            </span>
                            <div>
                                <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                                    <span>AI Report Studio</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-200 text-purple-900">
                                        Gemini AI ✨
                                    </span>
                                </h3>
                                <p class="text-xs text-purple-800/80">Sesuaikan preferensi narasi, lalu biarkan AI menyusun laporan dinas secara otomatis.</p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center gap-2 text-xs font-semibold text-purple-700 bg-white/80 border border-purple-200 px-3 py-1.5 rounded-xl shadow-2xs self-start sm:self-auto">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            AI Generator Siap Digunakan
                        </div>
                    </div>

                    <!-- Pilihan Parameter AI -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Panjang Narasi -->
                        <div>
                            <label for="panjang_narasi" class="block text-xs font-bold text-purple-950 uppercase tracking-wider mb-2">
                                Panjang & Kedalaman Narasi AI
                            </label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5" id="narasi-card-options">
                                <label class="narasi-card relative flex flex-col p-3 rounded-xl border border-purple-200 bg-white hover:border-purple-500 cursor-pointer transition shadow-2xs">
                                    <input type="radio" name="panjang_narasi_radio" value="minimal_2" class="sr-only" checked onchange="selectNarasiRadio('minimal_2')">
                                    <span class="text-xs font-bold text-gray-900">Ringkas</span>
                                    <span class="text-[11px] text-purple-700 font-semibold mt-0.5">Min. 2 Paragraf</span>
                                    <span class="text-[10px] text-gray-400 mt-1 leading-tight">Cocok untuk monitoring singkat & tugas rutin.</span>
                                </label>

                                <label class="narasi-card relative flex flex-col p-3 rounded-xl border-2 border-purple-600 bg-purple-50/50 hover:border-purple-600 cursor-pointer transition shadow-2xs">
                                    <input type="radio" name="panjang_narasi_radio" value="3_paragraf" class="sr-only" onchange="selectNarasiRadio('3_paragraf')">
                                    <span class="text-xs font-bold text-gray-900 flex items-center justify-between">
                                        Standar
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                                    </span>
                                    <span class="text-[11px] text-purple-700 font-semibold mt-0.5">3 Paragraf</span>
                                    <span class="text-[10px] text-gray-400 mt-1 leading-tight">Format resmi: Pendahuluan, Isi, Kesimpulan.</span>
                                </label>

                                <label class="narasi-card relative flex flex-col p-3 rounded-xl border border-purple-200 bg-white hover:border-purple-500 cursor-pointer transition shadow-2xs">
                                    <input type="radio" name="panjang_narasi_radio" value="lebih" class="sr-only" onchange="selectNarasiRadio('lebih')">
                                    <span class="text-xs font-bold text-gray-900">Mendalam</span>
                                    <span class="text-[11px] text-purple-700 font-semibold mt-0.5">4+ Paragraf</span>
                                    <span class="text-[10px] text-gray-400 mt-1 leading-tight">Analisis lengkap, data komprehensif & evaluasi.</span>
                                </label>
                            </div>

                            <select id="panjang_narasi" name="panjang_narasi" class="sr-only">
                                <option value="minimal_2" selected>Minimal 2 Paragraf (Standar Ringkas)</option>
                                <option value="3_paragraf">3 Paragraf (Lengkap)</option>
                                <option value="lebih">Lebih dari 2 Paragraf (Mendalam / 4+ Paragraf)</option>
                            </select>
                        </div>

                        <!-- Mode Perjadin 8 Jam -->
                        <div>
                            <label class="block text-xs font-bold text-purple-950 uppercase tracking-wider mb-2">
                                Format Khusus Perjalanan Dinas
                            </label>

                            <div class="p-4 rounded-xl border border-purple-200 bg-white hover:border-purple-300 transition shadow-2xs">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <div class="pt-0.5">
                                        <input type="checkbox" id="is_8_jam" name="is_8_jam" value="1" class="w-4 h-4 rounded border-gray-300 text-purple-600 shadow-xs focus:ring-purple-500" onchange="toggle8JamOption()">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-gray-900">Perjadin 8 Jam (Jadwal & Matriks Waktu)</span>
                                            <span class="text-[10px] font-mono font-bold bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded">08.00 - 17.00 WITA</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            AI otomatis menyusun tabel markdown aktivitas jam-per-jam dari pagi hingga sore hari sesuai standar SOP SPPD satu hari penuh.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tip Informatif Box -->
                    <div class="mb-6 p-4 rounded-xl bg-purple-100/60 border border-purple-200/80 text-xs text-purple-900 flex items-start gap-3">
                        <svg class="w-5 h-5 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="leading-relaxed">
                            <strong>Cara Kerja AI:</strong> AI menganalisis data <em>Tujuan</em>, <em>Lokasi</em>, <em>Pihak yang Ditemui</em>, <em>Kategori</em>, dan <em>Kendala</em> yang Anda masukkan di atas untuk menyusun narasi kedinasan formal, terstruktur, dan akuntabel. Anda selalu dapat merevisi hasil narasi sebelum menyimpannya.
                        </div>
                    </div>

                    <!-- Tombol Generate AI -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <button type="button" id="btn-generate" onclick="generateAI()" class="w-full sm:w-auto bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:to-indigo-800 text-white font-bold py-3.5 px-8 rounded-xl shadow-md hover:shadow-lg hover:shadow-purple-600/30 inline-flex items-center justify-center gap-2.5 transition active:scale-95 group">
                            <svg class="w-5 h-5 text-yellow-300 group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm tracking-wide">Generate Narasi dengan AI ✨</span>
                        </button>

                        <div id="loading-indicator" class="hidden flex items-center gap-3 text-xs text-purple-800 font-semibold bg-white px-4 py-2.5 rounded-xl border border-purple-200 shadow-xs">
                            <svg class="animate-spin h-4 w-4 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="loading-text">Sedang menyusun laporan dinas dengan AI...</span>
                        </div>
                    </div>

                    <!-- HASIL DRAFT AI (REVIEW & EDIT) -->
                    <div id="hasil-container" class="mt-7 pt-6 border-t border-purple-200/80" style="{{ old('hasil_perjalanan') ? '' : 'display:none;' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                            <div>
                                <label for="hasil_perjalanan" class="block text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                    <span>Draft Narasi Laporan Hasil AI</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Siap Diedit & Ditinjau
                                    </span>
                                </label>
                                <p class="text-xs text-gray-500">Silakan tinjau dan sempurnakan teks di bawah ini bila diperlukan sebelum disimpan.</p>
                            </div>

                            <!-- Tools Bar: Copy & Stats -->
                            <div class="flex items-center gap-2">
                                <span id="word-count" class="text-[11px] font-mono text-gray-500 bg-white px-2 py-1 rounded-lg border border-gray-200">
                                    0 kata
                                </span>
                                <button type="button" onclick="copyHasilNarasi()" id="btn-copy" class="inline-flex items-center gap-1 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-lg shadow-2xs transition active:scale-95">
                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    <span id="btn-copy-text">Salin Teks</span>
                                </button>
                                <button type="button" onclick="generateAI()" class="inline-flex items-center gap-1 text-xs font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 px-3 py-1.5 rounded-lg transition active:scale-95" title="Generate ulang teks laporan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Regenerate
                                </button>
                            </div>
                        </div>

                        <div class="relative">
                            <textarea id="hasil_perjalanan" name="hasil_perjalanan" rows="12" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-2xl shadow-inner text-sm leading-relaxed p-4 font-sans bg-white" placeholder="Narasi AI akan otomatis terisi di sini setelah Anda mengklik tombol Generate di atas...">{{ old('hasil_perjalanan') }}</textarea>
                        </div>
                        <x-input-error :messages="$errors->get('hasil_perjalanan')" class="mt-1.5" />
                    </div>
                </div>

                <!-- SECTION 6: FOOTER ACTIONS -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col sm:flex-row items-center justify-between gap-4 sticky bottom-4 z-10">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pastikan isian tanggal, nomor surat, dan narasi sudah sesuai sebelum menyimpan laporan.</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('hanya-laporan.index') }}" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition text-center">
                            Batal
                        </a>
                        <button type="submit" class="px-7 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-emerald-600/30 flex items-center justify-center gap-2 transition active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Finalisasi & Simpan Laporan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Modern Select2 Styling */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db !important;
            border-radius: 0.75rem !important;
            min-height: 42px !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 0.5rem !important;
            font-size: 0.875rem !important;
            background-color: #ffffff !important;
            transition: all 0.15s ease-in-out !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #9333ea !important;
            box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2) !important;
            outline: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 8px !important;
        }
        .select2-dropdown {
            border-radius: 0.75rem !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            font-size: 0.875rem !important;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: #9333ea !important;
        }
    </style>

    <script>
        // AI Generator Calling
        async function generateAI() {
            const btn = document.getElementById('btn-generate');
            const indicator = document.getElementById('loading-indicator');
            const loadingText = document.getElementById('loading-text');
            const hasilContainer = document.getElementById('hasil-container');
            const hasilTextarea = document.getElementById('hasil_perjalanan');
            
            syncNamaPegawai();
            if (!document.getElementById('nama_pegawai').value) {
                alert('Silakan pilih atau isi Nama Pegawai terlebih dahulu.');
                return;
            }

            // Form validity check
            const form = document.getElementById('laporan-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            indicator.classList.remove('hidden');

            const loadingMessages = [
                'Menyiapkan konteks perjalanan dinas...',
                'Menghubungi AI Gemini...',
                'Menyusun narasi laporan kedinasan...',
                'Memoles struktur dan format laporan...'
            ];
            let msgIdx = 0;
            loadingText.innerText = loadingMessages[0];
            const msgTimer = setInterval(() => {
                msgIdx = (msgIdx + 1) % loadingMessages.length;
                loadingText.innerText = loadingMessages[msgIdx];
            }, 1800);

            try {
                const formData = new FormData(form);
                const response = await fetch("{{ route('hanya-laporan.generate') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                
                if (!response.ok) {
                    if (response.status === 422) {
                        alert('Validasi form gagal. Mohon periksa kembali isian wajib Anda.');
                        return;
                    }
                    throw new Error(data.message || 'Terjadi kesalahan dari server AI.');
                }
                
                if (data.hasil && data.hasil.startsWith('Error:')) {
                    alert(data.hasil);
                    return;
                }
                
                hasilTextarea.value = data.hasil || 'Terjadi kesalahan tidak diketahui.';
                hasilContainer.style.display = 'block';
                updateWordCount();
                
                // Smooth scroll to results
                hasilContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
            } catch (error) {
                alert(error.message || 'Terjadi kesalahan saat menghubungi server AI.');
            } finally {
                clearInterval(msgTimer);
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                indicator.classList.add('hidden');
            }
        }

        // Copy generated narrative to clipboard
        function copyHasilNarasi() {
            const textarea = document.getElementById('hasil_perjalanan');
            const btnText = document.getElementById('btn-copy-text');
            if (!textarea.value.trim()) {
                alert('Belum ada teks narasi untuk disalin.');
                return;
            }

            navigator.clipboard.writeText(textarea.value).then(() => {
                btnText.innerText = 'Tersalin! ✓';
                setTimeout(() => {
                    btnText.innerText = 'Salin Teks';
                }, 2000);
            }).catch(() => {
                textarea.select();
                document.execCommand('copy');
                btnText.innerText = 'Tersalin! ✓';
                setTimeout(() => {
                    btnText.innerText = 'Salin Teks';
                }, 2000);
            });
        }

        // Live Word Count
        function updateWordCount() {
            const text = document.getElementById('hasil_perjalanan').value.trim();
            const count = text ? text.split(/\s+/).filter(Boolean).length : 0;
            const counterEl = document.getElementById('word-count');
            if (counterEl) {
                counterEl.innerText = `${count} kata`;
            }
        }

        document.getElementById('hasil_perjalanan')?.addEventListener('input', updateWordCount);

        // Narasi radio cards sync
        function selectNarasiRadio(val) {
            const select = document.getElementById('panjang_narasi');
            if (select) select.value = val;

            document.querySelectorAll('.narasi-card').forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                if (radio && radio.value === val) {
                    radio.checked = true;
                    card.classList.add('border-2', 'border-purple-600', 'bg-purple-50/50');
                    card.classList.remove('border-purple-200', 'bg-white');
                } else {
                    card.classList.remove('border-2', 'border-purple-600', 'bg-purple-50/50');
                    card.classList.add('border-purple-200', 'bg-white');
                }
            });
        }

        // ==========================================
        // FOTO DOKUMENTASI & WEBP SUPPORT
        // ==========================================
        let originalFiles = [];

        function handleFotosChange(event) {
            const files = event.target.files;
            if (!files || files.length === 0) return;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                // Max size check 5MB
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File "${file.name}" melebihi ukuran batas 5MB.`);
                    continue;
                }
                originalFiles.push(file);
            }
            
            reprocessImages();
        }

        function removeSelectedFile(index) {
            if (index >= 0 && index < originalFiles.length) {
                originalFiles.splice(index, 1);
                reprocessImages();
            }
        }

        // Drag & drop support on dropzone
        const dropzone = document.getElementById('dropzone');
        if (dropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('border-purple-600', 'bg-purple-100/50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('border-purple-600', 'bg-purple-100/50');
                }, false);
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                if (dt && dt.files && dt.files.length > 0) {
                    const fakeEvent = { target: { files: dt.files } };
                    handleFotosChange(fakeEvent);
                }
            }, false);
        }

        function toggleStampInputs() {
            const inputs = document.getElementById('stamp_inputs');
            if (document.getElementById('check_stamp').checked) {
                inputs.classList.remove('hidden');
                if (!document.getElementById('stamp_datetime').value) {
                    setNowDateTime();
                }
            } else {
                inputs.classList.add('hidden');
            }
            reprocessImages();
        }

        function setNowDateTime() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('stamp_datetime').value = now.toISOString().slice(0, 16);
            reprocessImages();
        }

        function formatKoordinat(input) {
            let val = input.value.trim();
            if (val) {
                const coords = val.match(/-?\d+\.\d+/g);
                if (coords && coords.length >= 2) {
                    const lat = parseFloat(coords[0]).toFixed(6);
                    const lng = parseFloat(coords[1]).toFixed(6);
                    input.value = `${lat}, ${lng}`;
                    updateGmapsLink(lat, lng);
                }
            }
            reprocessImages();
        }

        function updateGmapsLink(lat, lng) {
            const link = document.getElementById('link-gmaps');
            if (link) {
                link.href = `https://www.google.com/maps?q=${lat},${lng}`;
                link.classList.remove('hidden');
            }
        }

        function getLocation() {
            const btn = document.getElementById('btn-gps');
            const originalContent = btn ? btn.innerHTML : '';
            if (btn) btn.innerHTML = '<span>Mendeteksi GPS...</span>';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    document.getElementById('stamp_koordinat').value = `${lat}, ${lng}`;
                    updateGmapsLink(lat, lng);
                    reprocessImages();
                    if (btn) btn.innerHTML = originalContent;
                }, function(error) {
                    alert("Gagal mendapatkan lokasi GPS: " + error.message);
                    if (btn) btn.innerHTML = originalContent;
                }, { enableHighAccuracy: true, timeout: 10000 });
            } else {
                alert("Geolocation tidak didukung oleh browser Anda.");
                if (btn) btn.innerHTML = originalContent;
            }
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function reprocessImages() {
            const container = document.getElementById('image-preview-container');
            const wrapper = document.getElementById('image-preview-wrapper');
            const countEl = document.getElementById('preview-count');
            
            container.innerHTML = '';
            
            if (originalFiles.length === 0) {
                wrapper.classList.add('hidden');
                document.getElementById('fotos').value = '';
                return;
            }

            wrapper.classList.remove('hidden');
            if (countEl) countEl.innerText = `${originalFiles.length} Foto`;

            const isStamped = document.getElementById('check_stamp').checked;
            const dt = new DataTransfer();
            
            // Stamp content
            const tujuan = document.getElementById('tujuan_perjalanan').value || 'Tujuan Belum Diisi';
            let dtVal = document.getElementById('stamp_datetime').value;
            let hariTanggal = 'Tanggal Belum Diisi';
            if (dtVal) {
                const dateObj = new Date(dtVal);
                const d = String(dateObj.getDate()).padStart(2, '0');
                const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                const y = dateObj.getFullYear();
                const h = String(dateObj.getHours()).padStart(2, '0');
                const min = String(dateObj.getMinutes()).padStart(2, '0');
                hariTanggal = `${d}/${m}/${y} ${h}:${min}`;
            }
            const koordinat = document.getElementById('stamp_koordinat').value || 'Koordinat Belum Diisi';
            
            for (let i = 0; i < originalFiles.length; i++) {
                const file = originalFiles[i];
                dt.items.add(file);
                
                const previewSrc = URL.createObjectURL(file);
                const ext = file.name.split('.').pop().toUpperCase();
                const isWebp = ext === 'WEBP';
                
                const card = document.createElement('div');
                card.className = 'relative group bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs hover:shadow-md transition';

                const imgContainer = document.createElement('div');
                imgContainer.className = 'w-full h-32 overflow-hidden relative bg-gray-100';

                const img = document.createElement('img');
                img.src = previewSrc;
                img.className = 'object-cover w-full h-full group-hover:scale-105 transition duration-300';
                imgContainer.appendChild(img);

                // Watermark simulation overlay
                if (isStamped) {
                    const stampDiv = document.createElement('div');
                    stampDiv.className = 'absolute bottom-1.5 left-1.5 right-1.5 text-white text-[8px] leading-tight font-bold border-l-2 border-yellow-400 pl-1 pointer-events-none drop-shadow-md bg-black/40 p-1 rounded-r';
                    stampDiv.style.textShadow = '1px 1px 2px black';
                    stampDiv.innerHTML = `<span class="truncate block">${escapeHtml(tujuan)}</span><span>${escapeHtml(hariTanggal)}</span><br><span class="font-mono text-[7px]">${escapeHtml(koordinat)}</span>`;
                    imgContainer.appendChild(stampDiv);
                }

                // Format badge
                const badge = document.createElement('span');
                badge.className = `absolute top-1.5 left-1.5 text-[9px] font-black px-1.5 py-0.5 rounded shadow-xs ${
                    isWebp ? 'bg-purple-600 text-white' : 'bg-gray-900/80 text-white'
                }`;
                badge.innerText = ext;
                imgContainer.appendChild(badge);

                // Delete button
                const delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.className = 'absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center hover:bg-rose-700 shadow-sm transition active:scale-95';
                delBtn.title = 'Hapus foto ini';
                delBtn.innerHTML = '&times;';
                delBtn.onclick = ((idx) => () => removeSelectedFile(idx))(i);
                imgContainer.appendChild(delBtn);

                // Card info footer
                const infoDiv = document.createElement('div');
                infoDiv.className = 'p-2 text-[10px] text-gray-500 bg-white border-t border-gray-100 flex items-center justify-between';
                infoDiv.innerHTML = `<span class="truncate max-w-[100px]" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</span><span class="font-mono">${formatBytes(file.size)}</span>`;

                card.appendChild(imgContainer);
                card.appendChild(infoDiv);
                container.appendChild(card);
            }
            
            document.getElementById('fotos').files = dt.files;
        }

        // ==========================================
        // NAMA PEGAWAI & SYNC
        // ==========================================
        function syncNamaPegawai() {
            const isCustom = document.getElementById('check_custom_pegawai').checked;
            const hiddenInput = document.getElementById('nama_pegawai');
            if (isCustom) {
                hiddenInput.value = document.getElementById('nama_pegawai_custom').value.trim();
            } else {
                const selectVal = $('#nama_pegawai_select').val();
                hiddenInput.value = selectVal ? selectVal.trim() : '';
            }
        }

        function toggleCustomPegawai() {
            const isCustom = document.getElementById('check_custom_pegawai').checked;
            const selectWrapper = document.getElementById('wrapper_select_pegawai');
            const customWrapper = document.getElementById('wrapper_custom_pegawai');
            const customInput = document.getElementById('nama_pegawai_custom');
            const nipInput = document.getElementById('nip_pegawai');
            const wrapperPernyataan = document.getElementById('wrapper_pernyataan_card');
            const checkPernyataan = document.getElementById('check_pernyataan');

            if (isCustom) {
                selectWrapper.classList.add('hidden');
                customWrapper.classList.remove('hidden');
                if (wrapperPernyataan) wrapperPernyataan.classList.add('hidden');
                if (checkPernyataan) checkPernyataan.checked = false;
                if (!customInput.value) {
                    customInput.value = $('#nama_pegawai_select').val() || '';
                }
                customInput.focus();
            } else {
                customWrapper.classList.add('hidden');
                selectWrapper.classList.remove('hidden');
                if (wrapperPernyataan) wrapperPernyataan.classList.remove('hidden');
                const selectedOpt = $('#nama_pegawai_select').find('option:selected');
                if (selectedOpt.length && selectedOpt.data('nip')) {
                    nipInput.value = selectedOpt.data('nip');
                }
            }
            syncNamaPegawai();
        }

        // ==========================================
        // DPR & 8 JAM TOGGLES
        // ==========================================
        function toggleDprInputs() {
            const checkDpr = document.getElementById('check_dpr');
            const dprInputs = document.getElementById('dpr_inputs');
            const nilaiInput = document.getElementById('nilai_dpr');
            if (checkDpr && checkDpr.checked) {
                if (dprInputs) dprInputs.classList.remove('hidden');
                if (nilaiInput && !nilaiInput.value) {
                    nilaiInput.focus();
                }
            } else {
                if (dprInputs) dprInputs.classList.add('hidden');
            }
        }

        function toggle8JamOption() {
            const is8Jam = document.getElementById('is_8_jam').checked;
            const cardOptions = document.getElementById('narasi-card-options');
            if (is8Jam) {
                cardOptions.classList.add('opacity-40', 'pointer-events-none');
            } else {
                cardOptions.classList.remove('opacity-40', 'pointer-events-none');
            }
        }

        // Kategori Chips & Custom
        function setKategori(kat) {
            const select = document.getElementById('kategori');
            select.value = kat;
            toggleCustomKategori();
        }

        function toggleCustomKategori() {
            const select = document.getElementById('kategori');
            const customInput = document.getElementById('kategori_custom');
            if (select.value === 'Custom') {
                customInput.classList.remove('hidden');
                customInput.setAttribute('required', 'required');
                customInput.focus();
            } else {
                customInput.classList.add('hidden');
                customInput.removeAttribute('required');
            }
        }

        // ==========================================
        // SALIN LAPORAN SEBELUMNYA
        // ==========================================
        function applyCopyLaporan() {
            const select = document.getElementById('copy_laporan_select');
            if (!select || !select.value) {
                alert('Silakan pilih salah satu laporan sebelumnya yang ingin disalin.');
                return;
            }

            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption) return;

            let report;
            try {
                const raw = selectedOption.getAttribute('data-report');
                report = typeof raw === 'string' ? JSON.parse(raw) : raw;
            } catch (e) {
                console.error('Gagal membaca data laporan:', e);
                return;
            }

            if (!report) return;

            // 1. Nama Pegawai & NIP
            const nama = report.nama_pegawai || '';
            const nip = report.nip_pegawai || '';
            const checkCustom = document.getElementById('check_custom_pegawai');
            const selectPegawai = $('#nama_pegawai_select');
            const customPegawai = document.getElementById('nama_pegawai_custom');
            const hiddenNama = document.getElementById('nama_pegawai');
            const inputNip = document.getElementById('nip_pegawai');

            let optionExists = false;
            selectPegawai.find('option').each(function() {
                if ($(this).val() === nama && nama !== '') {
                    optionExists = true;
                    return false;
                }
            });

            if (optionExists) {
                if (checkCustom) checkCustom.checked = false;
                toggleCustomPegawai();
                selectPegawai.val(nama).trigger('change');
            } else {
                if (checkCustom) checkCustom.checked = true;
                toggleCustomPegawai();
                if (customPegawai) customPegawai.value = nama;
            }
            if (hiddenNama) hiddenNama.value = nama;
            if (inputNip) inputNip.value = nip;

            // 2. Nomor ST & SPD
            const inputNomorSt = document.getElementById('nomor_st');
            const inputNomorSpd = document.getElementById('nomor_spd');
            if (inputNomorSt) inputNomorSt.value = report.nomor_st || '';
            if (inputNomorSpd) inputNomorSpd.value = report.nomor_spd || '';

            // 3. Tanggal Laporan & Tanggal Perjalanan
            const inputTglLaporan = document.getElementById('tgl_laporan');
            const inputTglPerjalanan = document.getElementById('tgl_perjalanan');
            if (inputTglLaporan && report.tgl_laporan) inputTglLaporan.value = report.tgl_laporan;
            if (inputTglPerjalanan && report.tgl_perjalanan) inputTglPerjalanan.value = report.tgl_perjalanan;

            // 4. Lokasi & Tujuan Perjalanan
            const inputLokasi = document.getElementById('lokasi_perjalanan');
            const inputTujuan = document.getElementById('tujuan_perjalanan');
            if (inputLokasi) inputLokasi.value = report.lokasi_perjalanan || '';
            if (inputTujuan) inputTujuan.value = report.tujuan_perjalanan || '';

            // 5. Pegawai Ditemui
            const inputPegawaiDitemui = document.getElementById('pegawai_ditemui');
            if (inputPegawaiDitemui) inputPegawaiDitemui.value = report.pegawai_ditemui || '';

            // 6. Kategori
            const selectKategori = document.getElementById('kategori');
            const inputKategoriCustom = document.getElementById('kategori_custom');
            const standardKategori = ['Translok Koordinasi', 'Pengawasan / Supervisi Lapangan', 'Verifikasi Data', 'Pendataan Lapangan'];
            if (selectKategori) {
                if (standardKategori.includes(report.kategori)) {
                    selectKategori.value = report.kategori;
                    toggleCustomKategori();
                } else if (report.kategori) {
                    selectKategori.value = 'Custom';
                    toggleCustomKategori();
                    if (inputKategoriCustom) inputKategoriCustom.value = report.kategori;
                }
            }

            // 7. Kendala
            const inputKendala = document.getElementById('kendala_ditemui');
            if (inputKendala) inputKendala.value = report.kendala_ditemui || '';

            // 8. Dokumen Tambahan: DPR & Surat Pernyataan
            const checkDpr = document.getElementById('check_dpr');
            const inputNilaiDpr = document.getElementById('nilai_dpr');
            const selectPpk = document.getElementById('ppk_pegawai_id');
            const checkPernyataan = document.getElementById('check_pernyataan');

            if (checkDpr) {
                checkDpr.checked = !!report.ada_dpr;
                toggleDprInputs();
            }
            if (inputNilaiDpr) {
                inputNilaiDpr.value = report.nilai_dpr || '';
            }
            if (selectPpk) {
                selectPpk.value = report.ppk_pegawai_id || '';
            }
            if (checkPernyataan) {
                checkPernyataan.checked = !optionExists ? false : !!report.ada_pernyataan;
            }

            // Update alert notice
            const alertBox = document.getElementById('copy-alert');
            if (alertBox) {
                alertBox.innerHTML = `Isian dari <strong>Nama Pegawai</strong> hingga <strong>Kendala</strong> berhasil disalin dari laporan sebelumnya (<em>${escapeHtml(nama)}</em>). Anda dapat menyesuaikan data sebelum men-generate narasi AI.`;
                alertBox.classList.remove('hidden');
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ==========================================
        // INITIALIZATION
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const customInput = document.getElementById('nama_pegawai_custom');
            if (customInput) {
                customInput.addEventListener('input', syncNamaPegawai);
            }

            if (typeof jQuery !== 'undefined') {
                $('#nama_pegawai_select').select2({
                    placeholder: "-- Pilih atau Cari Nama Pegawai --",
                    allowClear: true,
                    width: '100%'
                });

                $('#nama_pegawai_select').on('change', function() {
                    const selectedOption = $(this).find('option:selected');
                    const nip = selectedOption.data('nip');
                    if (nip) {
                        $('#nip_pegawai').val(nip);
                    }
                    syncNamaPegawai();
                });

                $('#copy_laporan_select').select2({
                    placeholder: "-- Ketik atau Pilih Laporan Sebelumnya untuk Disalin --",
                    allowClear: true,
                    width: '100%'
                });

                $('#copy_laporan_select').on('change', function() {
                    if ($(this).val()) {
                        applyCopyLaporan();
                    }
                });
            }

            syncNamaPegawai();
            updateWordCount();

            const form = document.getElementById('laporan-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    syncNamaPegawai();
                    const nama = document.getElementById('nama_pegawai').value;
                    if (!nama) {
                        e.preventDefault();
                        alert('Nama Pegawai wajib diisi!');
                        const isCustom = document.getElementById('check_custom_pegawai').checked;
                        if (isCustom) {
                            document.getElementById('nama_pegawai_custom').focus();
                        } else {
                            $('#nama_pegawai_select').select2('open');
                        }
                        return false;
                    }
                });
            }
        });
    </script>
</x-app-layout>