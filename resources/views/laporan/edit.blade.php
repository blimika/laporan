<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('laporan.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Laporan Perjalanan
                    </a>
                    <span class="text-gray-300">•</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        ✏️ Mode Edit Laporan Penugasan
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                        ✨ Gemini AI Studio
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        📸 Support Foto WebP & Geotag
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-indigo-600 via-purple-600 to-indigo-700 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    <span>{{ __('Edit Laporan Perjalanan (Generate AI)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1 max-w-3xl">
                    Perbarui parameter laporan penugasan resmi berbasis Surat Tugas, kelola foto dokumentasi kegiatan (WebP/JPG/PNG), serta manfaatkan AI Studio untuk menyusun ulang draft narasi yang formal, komprehensif, dan akuntabel.
                </p>
            </div>

            <div class="flex items-center flex-wrap gap-2 self-start md:self-auto">
                <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition">
                    Daftar Laporan
                </a>
                <a href="{{ route('export.laporan', $laporan) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-semibold shadow-xs transition" title="Tinjau dan unduh format PDF laporan perjalanan">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Preview PDF
                </a>
                <a href="{{ route('export.laporan.word', $laporan) }}" class="inline-flex items-center px-3.5 py-2 bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-semibold shadow-xs transition" title="Unduh naskah laporan dalam format Microsoft Word (.docx)">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Unduh Word
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $st = $laporan->suratTugas ?? ($suratTugas->first() ?? null);
        $stPegawai = $st ? $st->pegawai : null;
        $stAnggaran = $st ? $st->anggaran : null;
        $stSpd = $st ? $st->spd : null;

        $stampDatetimeVal = '';
        if (!empty($laporan->stamp_datetime)) {
            try {
                $stampDatetimeVal = \Carbon\Carbon::parse($laporan->stamp_datetime)->format('Y-m-d\TH:i');
            } catch (\Exception $e) {
                $stampDatetimeVal = $laporan->stamp_datetime;
            }
        }
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
                            <h4 class="text-xs font-bold uppercase tracking-wider text-rose-900">Periksa Kembali Isian Formulir</h4>
                            <p class="text-xs text-rose-700 mt-0.5">Terdapat data yang belum lengkap atau formatnya belum sesuai:</p>
                            <ul class="list-disc list-inside text-xs mt-2 space-y-1 text-rose-800">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- CARD CONTEXT: METADATA SURAT TUGAS & PENUGASAN INDUK -->
            @if($st)
                <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 text-white rounded-2xl shadow-md p-5 sm:p-6 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full pointer-events-none"></div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-white/10">
                        <div class="flex items-center gap-2.5">
                            <span class="p-2 bg-white/10 rounded-xl text-white">
                                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">Surat Tugas Terdaftar</span>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        Induk Penugasan #{{ $st->id }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-white tracking-wide font-mono mt-0.5">{{ $st->nomor_surat }}</h3>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('surat-tugas.edit', $st->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-xl border border-white/15 transition">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Buka Surat Tugas ↗
                            </a>
                        </div>
                    </div>

                    <!-- Grid Detail Surat Tugas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4 text-xs">
                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span class="text-indigo-200/70 block text-[11px] font-medium">Pegawai Pelaksana:</span>
                            <span class="font-bold text-white text-sm block mt-0.5">{{ $stPegawai->nama ?? 'Tidak terdefinisi' }}</span>
                            <span class="text-indigo-200/80 font-mono text-[11px] block mt-0.5">NIP: {{ $stPegawai->nip ?? '-' }}</span>
                            @if(!empty($stPegawai->jabatan))
                                <span class="text-indigo-300/60 text-[10px] block mt-0.5 truncate">{{ $stPegawai->jabatan }}</span>
                            @endif
                        </div>

                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span class="text-indigo-200/70 block text-[11px] font-medium">Waktu & Jadwal Pelaksanaan:</span>
                            <span class="font-bold text-white block mt-0.5">
                                {{ $st->tgl_berangkat ? $st->tgl_berangkat->format('d/m/Y') : '-' }} s.d. {{ $st->tgl_kembali ? $st->tgl_kembali->format('d/m/Y') : '-' }}
                            </span>
                            @php
                                $lamaHari = $st->lama_hari;
                                if(!$lamaHari && $st->tgl_berangkat && $st->tgl_kembali) {
                                    $lamaHari = $st->tgl_berangkat->diffInDays($st->tgl_kembali) + 1;
                                }
                            @endphp
                            <span class="text-emerald-300 font-semibold text-[11px] block mt-0.5">
                                Durasi: {{ $lamaHari ?? 1 }} Hari Kalender
                            </span>
                        </div>

                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span class="text-indigo-200/70 block text-[11px] font-medium">Lokasi Tujuan ST:</span>
                            <span class="font-bold text-white block mt-0.5 truncate" title="{{ $st->tujuan }}">{{ $st->tujuan ?? '-' }}</span>
                            <span class="text-indigo-200/70 block text-[11px] font-medium mt-2">Pembebanan Anggaran (MAK):</span>
                            <span class="font-mono text-indigo-300 block text-[11px] truncate" title="{{ $stAnggaran->mak ?? '-' }}">
                                {{ $stAnggaran->mak ?? 'DIPA Satker' }}
                            </span>
                        </div>

                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span class="text-indigo-200/70 block text-[11px] font-medium">Nomor Surat Perjalanan Dinas (SPD):</span>
                            <span class="font-bold text-white font-mono block mt-0.5 truncate">
                                {{ $stSpd->nomor_spd ?? 'Belum diterbitkan' }}
                            </span>
                            <span class="text-indigo-200/70 block text-[11px] font-medium mt-2">Maksud Penugasan ST:</span>
                            <span class="text-indigo-100/90 line-clamp-2 text-[11px] leading-tight" title="{{ $st->tugas }}">
                                {{ $st->tugas ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- FORM UTAMA EDIT LAPORAN PERJALANAN -->
            <form id="laporan-form" method="POST" action="{{ route('laporan.update', $laporan->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Hidden Input Surat Tugas ID -->
                <input type="hidden" name="surat_tugas_id" id="surat_tugas_id" value="{{ $laporan->surat_tugas_id }}">

                <!-- ======================================================== -->
                <!-- SECTION 1: REFERENSI ADMINISTRASI & PENJADWALAN LAPORAN -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center justify-between pb-3 mb-5 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">1</span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Referensi Administrasi & Penjadwalan Laporan</h3>
                                <p class="text-xs text-gray-500">Korelasi dokumen penugasan resmi dengan tanggal pembuatan dan riil pelaksanaan.</p>
                            </div>
                        </div>

                        @if($st)
                            <button type="button" onclick="syncDatesWithST()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg border border-indigo-200 transition" title="Sesuaikan tanggal laporan dan tanggal perjalanan dengan tanggal ST">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Sinkronkan Tanggal dengan ST
                            </button>
                        @endif
                    </div>

                    <!-- Info Box ST Terpilih -->
                    <div class="mb-5 p-4 rounded-xl bg-gray-50 border border-gray-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-base shadow-xs">
                                📋
                            </div>
                            <div>
                                <span class="text-[11px] font-bold text-indigo-700 uppercase tracking-wider block">Surat Tugas Terikat (Permanen)</span>
                                <h4 class="text-sm font-extrabold text-gray-900 font-mono">{{ $st->nomor_surat ?? 'ST #' . $laporan->surat_tugas_id }}</h4>
                                <p class="text-xs text-gray-500">Pelaksana: <strong>{{ $stPegawai->nama ?? '-' }}</strong> (NIP. {{ $stPegawai->nip ?? '-' }})</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Terhubung Aktif
                            </span>
                        </div>
                    </div>

                    <!-- Input Tanggal Laporan & Tanggal Perjalanan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Tanggal Laporan Dibuat -->
                        <div>
                            <label for="tgl_laporan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tanggal Laporan Dibuat <span class="text-rose-500">*</span>
                            </label>
                            <input id="tgl_laporan" type="date" name="tgl_laporan" class="border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs block w-full text-sm font-medium" value="{{ old('tgl_laporan', $laporan->tgl_laporan ? $laporan->tgl_laporan->format('Y-m-d') : '') }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Tanggal pembuatan naskah pertanggungjawaban dinas (biasanya tanggal selesai tugas).</p>
                            <x-input-error :messages="$errors->get('tgl_laporan')" class="mt-1.5" />
                        </div>

                        <!-- Tanggal Perjalanan -->
                        <div>
                            <label for="tgl_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tanggal Pelaksanaan Perjalanan <span class="text-rose-500">*</span>
                            </label>
                            <input id="tgl_perjalanan" type="date" name="tgl_perjalanan" class="border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs block w-full text-sm font-medium" value="{{ old('tgl_perjalanan', $laporan->tgl_perjalanan ? $laporan->tgl_perjalanan->format('Y-m-d') : '') }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Tanggal riil pelaksanaan tugas di lapangan (tanggal awal keberangkatan).</p>
                            <x-input-error :messages="$errors->get('tgl_perjalanan')" class="mt-1.5" />
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 2: DETAIL KEGIATAN & LOKASI LAPANGAN -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center">2</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Detail Kegiatan & Lokasi Lapangan</h3>
                            <p class="text-xs text-gray-500">Parameter utama kegiatan yang dijadikan rujukan oleh AI dalam menyusun teks narasi laporan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Lokasi Perjalanan -->
                        <div>
                            <label for="lokasi_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Lokasi Tujuan / Instansi / Desa <span class="text-rose-500">*</span>
                            </label>
                            <input id="lokasi_perjalanan" type="text" name="lokasi_perjalanan" class="border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs block w-full text-sm font-medium" placeholder="Contoh: Kantor Desa Sukamaju, Kec. Jonggat" value="{{ old('lokasi_perjalanan', $laporan->lokasi_perjalanan) }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Nama desa, kecamatan, instansi sasaran, atau tempat yang dikunjungi.</p>
                            <x-input-error :messages="$errors->get('lokasi_perjalanan')" class="mt-1.5" />
                        </div>

                        <!-- Tujuan Perjalanan Singkat -->
                        <div>
                            <label for="tujuan_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tujuan Perjalanan (Singkat & Jelas) <span class="text-rose-500">*</span>
                            </label>
                            <input id="tujuan_perjalanan" type="text" name="tujuan_perjalanan" class="border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs block w-full text-sm font-medium" placeholder="Contoh: Koordinasi teknis pelaksanaan survei ekonomi 2026" value="{{ old('tujuan_perjalanan', $laporan->tujuan_perjalanan) }}" required oninput="reprocessImages()" />
                            <p class="text-[11px] text-gray-400 mt-1">Teks ini juga dicantumkan pada watermark stamp foto dokumentasi.</p>
                            <x-input-error :messages="$errors->get('tujuan_perjalanan')" class="mt-1.5" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Pihak / Pegawai yang Ditemui -->
                        <div>
                            <label for="pegawai_ditemui" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Pihak / Pegawai yang Ditemui <span class="text-rose-500">*</span>
                            </label>
                            <input id="pegawai_ditemui" type="text" name="pegawai_ditemui" class="border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs block w-full text-sm font-medium" placeholder="Contoh: Kepala Desa Bpk. H. Ahmad dan Sekretaris Desa" value="{{ old('pegawai_ditemui', $laporan->pegawai_ditemui) }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Nama pejabat, aparatur desa, staf, atau responden yang ditemui saat tugas.</p>
                            <x-input-error :messages="$errors->get('pegawai_ditemui')" class="mt-1.5" />
                        </div>

                        <!-- Kategori Perjalanan -->
                        <div>
                            @php
                                $standardKategori = ['Translok Koordinasi', 'Pengawasan / Supervisi Lapangan', 'Verifikasi Data', 'Pendataan Lapangan'];
                                $rawKategori = old('kategori', $laporan->kategori ?? 'Translok Koordinasi');
                                $isCustomKategori = !empty($rawKategori) && !in_array($rawKategori, $standardKategori);
                                $selectedKategori = $isCustomKategori ? 'Custom' : $rawKategori;
                                $customKategoriVal = $isCustomKategori ? old('kategori_custom', $rawKategori) : old('kategori_custom', '');
                            @endphp
                            <label for="kategori" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Kategori Perjalanan <span class="text-rose-500">*</span>
                            </label>

                            <!-- Chips Cepat Kategori -->
                            <div class="flex flex-wrap gap-1.5 mb-2" id="kategori-chips-container">
                                @foreach($standardKategori as $kat)
                                    <button type="button" onclick="setKategori('{{ $kat }}')" class="kategori-chip px-2.5 py-1 text-[11px] font-semibold rounded-lg border transition {{ $selectedKategori === $kat ? 'bg-indigo-100 text-indigo-800 border-indigo-300 shadow-2xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}" data-kategori="{{ $kat }}">
                                        {{ $kat }}
                                    </button>
                                @endforeach
                                <button type="button" onclick="setKategori('Custom')" class="kategori-chip px-2.5 py-1 text-[11px] font-semibold rounded-lg border transition {{ $selectedKategori === 'Custom' ? 'bg-indigo-100 text-indigo-800 border-indigo-300 shadow-2xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}" data-kategori="Custom">
                                    + Custom
                                </button>
                            </div>

                            <select id="kategori" name="kategori" class="block w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-sm" required onchange="toggleCustomKategori()">
                                <option value="Translok Koordinasi" {{ $selectedKategori == 'Translok Koordinasi' ? 'selected' : '' }}>Translok Koordinasi</option>
                                <option value="Pengawasan / Supervisi Lapangan" {{ $selectedKategori == 'Pengawasan / Supervisi Lapangan' ? 'selected' : '' }}>Pengawasan / Supervisi Lapangan</option>
                                <option value="Verifikasi Data" {{ $selectedKategori == 'Verifikasi Data' ? 'selected' : '' }}>Verifikasi Data</option>
                                <option value="Pendataan Lapangan" {{ $selectedKategori == 'Pendataan Lapangan' ? 'selected' : '' }}>Pendataan Lapangan</option>
                                <option value="Custom" {{ $selectedKategori == 'Custom' ? 'selected' : '' }}>Custom (Isi Sendiri)</option>
                            </select>

                            <input type="text" id="kategori_custom" name="kategori_custom" class="mt-2.5 block w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-sm {{ $isCustomKategori ? '' : 'hidden' }}" placeholder="Ketik kategori kegiatan khusus Anda..." value="{{ $customKategoriVal }}" {{ $isCustomKategori ? 'required' : '' }}>
                            <x-input-error :messages="$errors->get('kategori')" class="mt-1.5" />
                        </div>
                    </div>

                    <!-- Kendala / Catatan Lapangan -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="kendala_ditemui" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Kendala / Masalah yang Ditemukan <span class="text-xs font-normal text-gray-400 capitalize">(Opsional)</span>
                            </label>
                            <span class="text-[11px] text-gray-400">Kosongkan jika kegiatan berlangsung lancar tanpa hambatan</span>
                        </div>
                        <textarea id="kendala_ditemui" name="kendala_ditemui" rows="2" class="block w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-sm placeholder:text-gray-400" placeholder="Contoh: Kondisi cuaca hujan lebat di perjalanan, responden sempat berhalangan sehingga wawancara dijadwalkan ulang siang hari...">{{ old('kendala_ditemui', $laporan->kendala_ditemui) }}</textarea>
                        <p class="text-[11px] text-gray-400 mt-1">💡 Tips AI: Isian kendala akan dianalisis secara cerdas oleh AI untuk memformulasikan poin evaluasi dan tindak lanjut rekomendasi dinas.</p>
                        <x-input-error :messages="$errors->get('kendala_ditemui')" class="mt-1.5" />
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 3: MANAJEMEN FOTO DOKUMENTASI KEGIATAN -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center">3</span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                    Foto Dokumentasi Kegiatan
                                    <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider border border-emerald-200">
                                        Support WebP Kamera HP
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-500">Kelola foto yang sudah tersimpan atau unggah berkas dokumentasi baru dalam format WebP, JPG, dan PNG.</p>
                            </div>
                        </div>

                        <!-- Format Badges -->
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">JPG</span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">PNG</span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-300 shadow-2xs">✨ WEBP</span>
                        </div>
                    </div>

                    <!-- GALERI FOTO DOKUMENTASI SAAT INI (EXISTING) -->
                    @if($laporan->dokumentasi && $laporan->dokumentasi->count() > 0)
                        <div class="mb-6 p-4 rounded-xl border border-gray-200 bg-gray-50/70">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Foto Dokumentasi Tersimpan ({{ $laporan->dokumentasi->count() }})</span>
                                    <span class="text-[11px] text-gray-400">• Centang/Klik tombol merah untuk menandai foto yang ingin dihapus</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
                                @foreach($laporan->dokumentasi as $dok)
                                    <div id="photo_card_{{ $dok->id }}" class="relative rounded-xl border border-gray-200 bg-white overflow-hidden shadow-2xs transition group hover:shadow-md">
                                        <div class="aspect-square relative overflow-hidden bg-gray-100">
                                            <a href="{{ asset($dok->file_path) }}" target="_blank" class="block w-full h-full" title="Klik untuk melihat foto ukuran penuh">
                                                <img src="{{ asset($dok->file_path) }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300" alt="Dokumentasi">

                                                @if($laporan->is_stamped)
                                                    @php
                                                        $dtVal = $laporan->stamp_datetime ? \Carbon\Carbon::parse($laporan->stamp_datetime)->format('d/m/Y H:i') : '-';
                                                    @endphp
                                                    <div class="absolute bottom-1 left-1 right-1 text-white text-[8px] leading-tight font-bold border-l-2 border-yellow-400 pl-1 pointer-events-none drop-shadow-md truncate" style="text-shadow: 1px 1px 2px black, 0 0 1px black;">
                                                        {{ $laporan->tujuan_perjalanan }}<br>{{ $dtVal }}<br>{{ $laporan->stamp_koordinat }}
                                                    </div>
                                                @endif
                                            </a>

                                            <!-- Tombol Checkbox Hapus -->
                                            <label class="absolute top-1.5 right-1.5 p-1 bg-white/90 backdrop-blur-xs rounded-lg shadow-sm border border-gray-200 cursor-pointer flex items-center justify-center hover:bg-rose-50 transition" title="Centang untuk menghapus foto ini saat disimpan">
                                                <input type="checkbox" name="delete_fotos[]" id="del_photo_{{ $dok->id }}" value="{{ $dok->id }}" onchange="handleDeleteCheck({{ $dok->id }})" class="w-4 h-4 rounded border-gray-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                                            </label>

                                            <!-- Overlay Tag Ditandai Dihapus -->
                                            <div id="photo_badge_{{ $dok->id }}" class="hidden absolute inset-0 bg-rose-900/60 backdrop-blur-2xs flex flex-col items-center justify-center text-white text-center p-2 pointer-events-none">
                                                <svg class="w-6 h-6 text-rose-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-100">Akan Dihapus</span>
                                            </div>
                                        </div>

                                        <div class="p-1.5 text-[10px] text-gray-500 border-t border-gray-100 flex items-center justify-between bg-white">
                                            <span class="truncate max-w-[80px]" title="{{ basename($dok->file_path) }}">{{ basename($dok->file_path) }}</span>
                                            <button type="button" onclick="toggleDeletePhoto({{ $dok->id }})" class="text-rose-600 hover:text-rose-800 font-semibold text-[10px] hover:underline">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- DROPZONE UNGGAH FOTO TAMBAHAN -->
                    <div id="dropzone" class="relative border-2 border-dashed border-indigo-300 hover:border-indigo-500 bg-indigo-50/30 hover:bg-indigo-50/60 rounded-2xl p-6 text-center transition cursor-pointer group" onclick="document.getElementById('fotos').click()">
                        <input id="fotos" name="fotos[]" type="file" multiple accept="image/jpeg,image/png,image/jpg,image/webp,image/*" class="sr-only" onchange="handleFotosChange(event)" />

                        <div class="max-w-md mx-auto space-y-2 pointer-events-none">
                            <div class="w-12 h-12 mx-auto rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="text-xs text-gray-700">
                                <span class="font-bold text-indigo-700 hover:underline">Klik untuk Menambah Foto Baru</span> atau seret & lepas berkas ke sini
                            </div>
                            <p class="text-[11px] text-gray-500 leading-relaxed">
                                Mendukung format <strong>WEBP (Format foto kamera HP Android/iPhone)</strong>, <strong>JPG/JPEG</strong>, dan <strong>PNG</strong>. Maksimal 5MB per berkas foto.
                            </p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('fotos.*')" class="mt-1.5" />

                    <!-- STAMP WATERMARK GPS & WAKTU -->
                    <div class="mt-5 p-5 border border-gray-200 rounded-2xl bg-gray-50/70">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="check_stamp" name="check_stamp" class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-xs focus:ring-indigo-500 cursor-pointer" onchange="toggleStampInputs()" {{ $laporan->is_stamped ? 'checked' : '' }}>
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

                        <div id="stamp_inputs" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 {{ $laporan->is_stamped ? '' : 'hidden' }}">
                            <!-- Koordinat Manual / GPS -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="stamp_koordinat" class="block text-xs font-semibold text-gray-700">
                                        Koordinat Lokasi Geotag
                                    </label>
                                    <button type="button" onclick="getLocation()" id="btn-gps" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Ambil Lokasi Saat Ini (GPS)
                                    </button>
                                </div>
                                <input id="stamp_koordinat" name="stamp_koordinat" type="text" class="block w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-xs font-mono" placeholder="Contoh: -8.650000, 116.320000" value="{{ old('stamp_koordinat', $laporan->stamp_koordinat) }}" onchange="formatKoordinat(this)" oninput="reprocessImages()" />
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-[11px] text-gray-400">Format: Latitude, Longitude</span>
                                    <a id="link-gmaps" href="#" target="_blank" class="text-[11px] text-indigo-600 hover:underline hidden">Buka di Google Maps ↗</a>
                                </div>
                                <x-input-error :messages="$errors->get('stamp_koordinat')" class="mt-1" />
                            </div>

                            <!-- Tanggal & Jam Stamp -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="stamp_datetime" class="block text-xs font-semibold text-gray-700">
                                        Waktu / Jam Pengambilan Foto
                                    </label>
                                    <button type="button" onclick="setNowDateTime()" class="text-[11px] font-semibold text-indigo-700 hover:text-indigo-900">
                                        Set Jam Sekarang
                                    </button>
                                </div>
                                <input id="stamp_datetime" name="stamp_datetime" type="datetime-local" class="block w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-xl shadow-xs text-xs" value="{{ old('stamp_datetime', $stampDatetimeVal) }}" onchange="reprocessImages()" />
                                <p class="text-[11px] text-gray-400 mt-1">Waktu yang akan dicetak di sudut foto dokumentasi.</p>
                                <x-input-error :messages="$errors->get('stamp_datetime')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- CONTAINER PREVIEW FOTO TAMBAHAN BARU -->
                    <div id="image-preview-wrapper" class="mt-5 hidden">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                                <span>Pratinjau Foto Tambahan Baru</span>
                                <span id="preview-count" class="text-[10px] bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full font-bold">0 Foto</span>
                            </h4>
                            <span class="text-[11px] text-gray-400">Foto baru akan diunggah dan disimpan bersama laporan</span>
                        </div>
                        <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5"></div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 4: AI STUDIO & HASIL NARASI LAPORAN -->
                <!-- ======================================================== -->
                <div class="bg-gradient-to-br from-indigo-50/90 via-purple-50/50 to-white rounded-2xl border-2 border-indigo-300/80 shadow-md p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-indigo-200">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-black text-sm flex items-center justify-center shadow-xs">
                                4
                            </span>
                            <div>
                                <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                                    <span>AI Report Studio</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-200 text-indigo-900">
                                        Gemini AI ✨
                                    </span>
                                </h3>
                                <p class="text-xs text-indigo-800/80">Sesuaikan preferensi narasi, lalu biarkan AI menyusun ulang naskah laporan dinas Anda secara otomatis.</p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center gap-2 text-xs font-semibold text-indigo-700 bg-white/80 border border-indigo-200 px-3 py-1.5 rounded-xl shadow-2xs self-start sm:self-auto">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            AI Studio Siap Digunakan
                        </div>
                    </div>

                    <!-- Pilihan Parameter AI Studio -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Panjang Narasi -->
                        <div>
                            <label class="block text-xs font-bold text-indigo-950 uppercase tracking-wider mb-2">
                                Target Kedalaman & Struktur Narasi
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5" id="narasi-card-options">
                                <label class="narasi-card relative flex flex-col p-3 rounded-xl border-2 border-indigo-600 bg-indigo-50/50 hover:border-indigo-600 cursor-pointer transition shadow-2xs">
                                    <input type="radio" name="panjang_narasi_radio" value="minimal_2" class="sr-only" checked onchange="selectNarasiRadio('minimal_2')">
                                    <span class="text-xs font-bold text-gray-900 flex items-center justify-between">
                                        Ringkas
                                        <span class="narasi-dot w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                    </span>
                                    <span class="text-[11px] text-indigo-700 font-semibold mt-0.5">Min. 2 Paragraf</span>
                                    <span class="text-[10px] text-gray-400 mt-1 leading-tight">Cocok untuk monitoring singkat & tugas rutin.</span>
                                </label>

                                <label class="narasi-card relative flex flex-col p-3 rounded-xl border border-indigo-200 bg-white hover:border-indigo-500 cursor-pointer transition shadow-2xs">
                                    <input type="radio" name="panjang_narasi_radio" value="3_paragraf" class="sr-only" onchange="selectNarasiRadio('3_paragraf')">
                                    <span class="text-xs font-bold text-gray-900 flex items-center justify-between">
                                        Standar
                                        <span class="narasi-dot w-1.5 h-1.5 rounded-full bg-indigo-600 hidden"></span>
                                    </span>
                                    <span class="text-[11px] text-indigo-700 font-semibold mt-0.5">3 Paragraf</span>
                                    <span class="text-[10px] text-gray-400 mt-1 leading-tight">Format resmi: Pendahuluan, Isi, Kesimpulan.</span>
                                </label>

                                <label class="narasi-card relative flex flex-col p-3 rounded-xl border border-indigo-200 bg-white hover:border-indigo-500 cursor-pointer transition shadow-2xs">
                                    <input type="radio" name="panjang_narasi_radio" value="lebih" class="sr-only" onchange="selectNarasiRadio('lebih')">
                                    <span class="text-xs font-bold text-gray-900 flex items-center justify-between">
                                        Mendalam
                                        <span class="narasi-dot w-1.5 h-1.5 rounded-full bg-indigo-600 hidden"></span>
                                    </span>
                                    <span class="text-[11px] text-indigo-700 font-semibold mt-0.5">4+ Paragraf</span>
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
                            <label class="block text-xs font-bold text-indigo-950 uppercase tracking-wider mb-2">
                                Format Khusus Perjalanan Dinas
                            </label>

                            <div class="p-4 rounded-xl border border-indigo-200 bg-white hover:border-indigo-300 transition shadow-2xs">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <div class="pt-0.5">
                                        <input type="checkbox" id="is_8_jam" name="is_8_jam" value="1" class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-xs focus:ring-indigo-500" onchange="toggle8JamOption()">
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

                    <!-- Tip Box Sintesis AI -->
                    <div class="mb-6 p-4 rounded-xl bg-indigo-100/60 border border-indigo-200/80 text-xs text-indigo-900 flex items-start gap-3">
                        <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="leading-relaxed">
                            <strong>Sintesis Otomatis AI:</strong> Mengklik "Generate Ulang dengan AI" akan membaca data Surat Tugas (nomor, pelaksana, maksud penugasan) bersama parameter lapangan saat ini untuk menyusun draft narasi pertanggungjawaban yang komprehensif. Anda tetap dapat mengedit teks sebelum menyimpannya.
                        </div>
                    </div>

                    <!-- Tombol Generate AI -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <button type="button" id="btn-generate" onclick="generateAI()" class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 hover:from-indigo-700 hover:to-purple-800 text-white font-bold py-3.5 px-8 rounded-xl shadow-md hover:shadow-lg hover:shadow-indigo-600/30 inline-flex items-center justify-center gap-2.5 transition active:scale-95 group">
                            <svg class="w-5 h-5 text-yellow-300 group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm tracking-wide">Generate Ulang dengan AI ✨</span>
                        </button>

                        <div id="loading-indicator" class="hidden flex items-center gap-3 text-xs text-indigo-800 font-semibold bg-white px-4 py-2.5 rounded-xl border border-indigo-200 shadow-xs">
                            <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="loading-text">Sedang menyusun narasi laporan dengan AI...</span>
                        </div>
                    </div>

                    <!-- HASIL DRAFT AI (REVIEW & EDIT) -->
                    <div id="hasil-container" class="mt-7 pt-6 border-t border-indigo-200/80" style="{{ old('hasil_perjalanan', $laporan->hasil_perjalanan) ? '' : 'display:none;' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                            <div>
                                <label for="hasil_perjalanan" class="block text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                    <span>Naskah Narasi Laporan Hasil AI</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Siap Ditinjau & Diedit
                                    </span>
                                </label>
                                <p class="text-xs text-gray-500">Anda dapat langsung menyunting narasi di bawah ini agar sesuai dengan format pertanggungjawaban yang dikehendaki.</p>
                            </div>

                            <!-- Tools Bar: Stats & Copy -->
                            <div class="flex items-center gap-2">
                                <span id="word-count" class="text-[11px] font-mono text-gray-500 bg-white px-2.5 py-1 rounded-lg border border-gray-200">
                                    0 kata
                                </span>
                                <button type="button" onclick="copyHasilNarasi()" id="btn-copy" class="inline-flex items-center gap-1 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-lg shadow-2xs transition active:scale-95">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    <span id="btn-copy-text">Salin Teks</span>
                                </button>
                                <button type="button" onclick="generateAI()" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-3 py-1.5 rounded-lg transition active:scale-95" title="Generate ulang teks laporan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Regenerate
                                </button>
                            </div>
                        </div>

                        <div class="relative">
                            <textarea id="hasil_perjalanan" name="hasil_perjalanan" rows="12" class="block w-full border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-2xl shadow-inner text-sm leading-relaxed p-4 font-sans bg-white" placeholder="Naskah narasi laporan akan terisi di sini..." oninput="updateWordCount()">{{ old('hasil_perjalanan', $laporan->hasil_perjalanan) }}</textarea>
                        </div>
                        <x-input-error :messages="$errors->get('hasil_perjalanan')" class="mt-1.5" />
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
                        <span>Pastikan isian kegiatan, dokumentasi, dan narasi laporan sudah sesuai sebelum menyimpan pembaruan.</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('laporan.index') }}" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition text-center">
                            Batal
                        </a>
                        <button type="submit" class="px-7 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-emerald-600/30 flex items-center justify-center gap-2 transition active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Pembaruan Laporan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Tanggal ST Default untuk Sinkronisasi Cepat
        @if($st)
            const stBerangkat = "{{ $st->tgl_berangkat ? $st->tgl_berangkat->format('Y-m-d') : '' }}";
            const stKembali = "{{ $st->tgl_kembali ? $st->tgl_kembali->format('Y-m-d') : '' }}";
            const stTujuan = "{{ addslashes($st->tujuan ?? '') }}";
            const stTugas = "{{ addslashes($st->tugas ?? '') }}";

            function syncDatesWithST() {
                if (stKembali) document.getElementById('tgl_laporan').value = stKembali;
                if (stBerangkat) document.getElementById('tgl_perjalanan').value = stBerangkat;
                if (stTujuan && !document.getElementById('lokasi_perjalanan').value) {
                    document.getElementById('lokasi_perjalanan').value = stTujuan;
                }
                if (stTugas && !document.getElementById('tujuan_perjalanan').value) {
                    document.getElementById('tujuan_perjalanan').value = stTugas;
                }
                reprocessImages();
            }
        @endif

        // Kategori Logic & Chips
        function setKategori(kat) {
            const select = document.getElementById('kategori');
            select.value = kat;
            toggleCustomKategori();

            // Update chip styles
            document.querySelectorAll('.kategori-chip').forEach(btn => {
                if (btn.getAttribute('data-kategori') === kat) {
                    btn.className = 'kategori-chip px-2.5 py-1 text-[11px] font-semibold rounded-lg border transition bg-indigo-100 text-indigo-800 border-indigo-300 shadow-2xs';
                } else {
                    btn.className = 'kategori-chip px-2.5 py-1 text-[11px] font-semibold rounded-lg border transition bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100';
                }
            });
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

        // Hapus Foto Existing
        function handleDeleteCheck(id) {
            const checkbox = document.getElementById('del_photo_' + id);
            const badge = document.getElementById('photo_badge_' + id);
            const card = document.getElementById('photo_card_' + id);

            if (checkbox.checked) {
                badge.classList.remove('hidden');
                card.classList.add('border-rose-400', 'ring-2', 'ring-rose-300');
            } else {
                badge.classList.add('hidden');
                card.classList.remove('border-rose-400', 'ring-2', 'ring-rose-300');
            }
        }

        function toggleDeletePhoto(id) {
            const checkbox = document.getElementById('del_photo_' + id);
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                handleDeleteCheck(id);
            }
        }

        // Dropzone & Drag and Drop Foto Tambahan
        const dropzone = document.getElementById('dropzone');
        if (dropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('border-indigo-600', 'bg-indigo-100/50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('border-indigo-600', 'bg-indigo-100/50');
                }, false);
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length > 0) {
                    const fileInput = document.getElementById('fotos');
                    fileInput.files = files;
                    handleFotosChange({ target: fileInput });
                }
            }, false);
        }

        let originalFiles = [];

        function handleFotosChange(event) {
            const dt = new DataTransfer();
            for (let i = 0; i < event.target.files.length; i++) {
                dt.items.add(event.target.files[i]);
            }
            originalFiles = Array.from(dt.files);
            reprocessImages();
        }

        function removeNewFile(index) {
            originalFiles.splice(index, 1);
            const dt = new DataTransfer();
            originalFiles.forEach(file => dt.items.add(file));
            document.getElementById('fotos').files = dt.files;
            reprocessImages();
        }

        // Stamp Watermark Geotag & Waktu
        function toggleStampInputs() {
            const inputs = document.getElementById('stamp_inputs');
            const check = document.getElementById('check_stamp');
            if (check.checked) {
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
            const gmapsLink = document.getElementById('link-gmaps');
            if (val) {
                const coords = val.match(/-?\d+\.\d+/g);
                if (coords && coords.length >= 2) {
                    const lat = parseFloat(coords[0]).toFixed(6);
                    const lng = parseFloat(coords[1]).toFixed(6);
                    input.value = `${lat}, ${lng}`;
                    if (gmapsLink) {
                        gmapsLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
                        gmapsLink.classList.remove('hidden');
                    }
                }
            } else {
                if (gmapsLink) gmapsLink.classList.add('hidden');
            }
            reprocessImages();
        }

        function getLocation() {
            const btnGps = document.getElementById('btn-gps');
            if (navigator.geolocation) {
                if (btnGps) btnGps.innerHTML = '<span>Mendeteksi...</span>';
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    const coordInput = document.getElementById('stamp_koordinat');
                    coordInput.value = `${lat}, ${lng}`;
                    formatKoordinat(coordInput);
                    if (btnGps) {
                        btnGps.innerHTML = `
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>GPS Terdeteksi</span>
                        `;
                    }
                }, function(error) {
                    alert("Gagal membaca sinyal GPS: " + error.message);
                    if (btnGps) {
                        btnGps.innerHTML = '<span>Deteksi GPS Saat Ini</span>';
                    }
                });
            } else {
                alert("Geolocation tidak didukung oleh browser Anda.");
            }
        }

        // Render Pratinjau Foto Baru dengan Stamp
        function reprocessImages() {
            const wrapper = document.getElementById('image-preview-wrapper');
            const container = document.getElementById('image-preview-container');
            const previewCount = document.getElementById('preview-count');

            if (originalFiles.length === 0) {
                if (wrapper) wrapper.classList.add('hidden');
                if (container) container.innerHTML = '';
                return;
            }

            if (wrapper) wrapper.classList.remove('hidden');
            if (previewCount) previewCount.innerText = `${originalFiles.length} Foto Baru`;
            container.innerHTML = '';

            const isStamped = document.getElementById('check_stamp').checked;
            const tujuan = document.getElementById('tujuan_perjalanan').value || 'Tujuan Kegiatan';

            let dtVal = document.getElementById('stamp_datetime').value;
            let hariTanggal = 'Waktu Belum Diatur';
            if (dtVal) {
                const dateObj = new Date(dtVal);
                const d = String(dateObj.getDate()).padStart(2, '0');
                const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                const y = dateObj.getFullYear();
                const h = String(dateObj.getHours()).padStart(2, '0');
                const min = String(dateObj.getMinutes()).padStart(2, '0');
                hariTanggal = `${d}/${m}/${y} ${h}:${min}`;
            }
            const koordinat = document.getElementById('stamp_koordinat').value || 'Koordinat Geotag';

            originalFiles.forEach((file, index) => {
                const previewSrc = URL.createObjectURL(file);
                const isWebp = file.type === 'image/webp' || file.name.toLowerCase().endsWith('.webp');

                const card = document.createElement('div');
                card.className = 'relative rounded-xl border border-indigo-200 bg-white overflow-hidden shadow-2xs group hover:shadow-md transition';

                card.innerHTML = `
                    <div class="aspect-square relative overflow-hidden bg-gray-100">
                        <img src="${previewSrc}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300">
                        
                        ${isStamped ? `
                            <div class="absolute bottom-1 left-1 right-1 text-white text-[8px] leading-tight font-bold border-l-2 border-yellow-400 pl-1 pointer-events-none drop-shadow-md truncate" style="text-shadow: 1px 1px 2px black, 0 0 1px black;">
                                ${tujuan}<br>${hariTanggal}<br>${koordinat}
                            </div>
                        ` : ''}

                        <button type="button" onclick="removeNewFile(${index})" class="absolute top-1.5 right-1.5 p-1 bg-white/90 hover:bg-rose-50 text-rose-600 rounded-lg shadow-sm border border-gray-200 transition" title="Batalkan unggahan foto ini">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="absolute top-1.5 left-1.5">
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold ${isWebp ? 'bg-purple-600 text-white' : 'bg-gray-800/80 text-white'}">
                                ${isWebp ? 'WEBP' : file.name.split('.').pop().toUpperCase()}
                            </span>
                        </div>
                    </div>
                    <div class="p-1.5 text-[10px] text-gray-500 border-t border-gray-100 flex items-center justify-between bg-white">
                        <span class="truncate max-w-[90px]" title="${file.name}">${file.name}</span>
                        <span class="text-[9px] text-gray-400 font-mono">${(file.size / 1024).toFixed(0)} KB</span>
                    </div>
                `;

                container.appendChild(card);
            });
        }

        // AI Studio Preferensi Panjang Narasi
        function selectNarasiRadio(val) {
            const select = document.getElementById('panjang_narasi');
            if (select) select.value = val;

            const cards = document.querySelectorAll('#narasi-card-options .narasi-card');
            cards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                const dot = card.querySelector('.narasi-dot');
                if (radio && radio.value === val) {
                    card.className = 'narasi-card relative flex flex-col p-3 rounded-xl border-2 border-indigo-600 bg-indigo-50/50 hover:border-indigo-600 cursor-pointer transition shadow-2xs';
                    if (dot) dot.classList.remove('hidden');
                } else {
                    card.className = 'narasi-card relative flex flex-col p-3 rounded-xl border border-indigo-200 bg-white hover:border-indigo-500 cursor-pointer transition shadow-2xs';
                    if (dot) dot.classList.add('hidden');
                }
            });
        }

        function toggle8JamOption() {
            // Visual feedback toggle
        }

        // Live Word Count & Char Count
        function updateWordCount() {
            const text = document.getElementById('hasil_perjalanan').value.trim();
            const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
            const chars = text.length;
            const counter = document.getElementById('word-count');
            if (counter) {
                counter.innerText = `${words} kata • ${chars} karakter`;
            }
        }

        // Copy Clipboard Helper
        async function copyHasilNarasi() {
            const textarea = document.getElementById('hasil_perjalanan');
            const btnText = document.getElementById('btn-copy-text');
            if (!textarea || !textarea.value) return;

            try {
                await navigator.clipboard.writeText(textarea.value);
                if (btnText) {
                    btnText.innerText = 'Tersalin! ✓';
                    setTimeout(() => {
                        btnText.innerText = 'Salin Teks';
                    }, 2000);
                }
            } catch (err) {
                textarea.select();
                document.execCommand('copy');
                if (btnText) {
                    btnText.innerText = 'Tersalin! ✓';
                    setTimeout(() => {
                        btnText.innerText = 'Salin Teks';
                    }, 2000);
                }
            }
        }

        // Generate AI Async Handler
        async function generateAI() {
            const btn = document.getElementById('btn-generate');
            const indicator = document.getElementById('loading-indicator');
            const loadingText = document.getElementById('loading-text');
            const hasilContainer = document.getElementById('hasil-container');
            const hasilTextarea = document.getElementById('hasil_perjalanan');
            const form = document.getElementById('laporan-form');

            // Validasi kelengkapan form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            indicator.classList.remove('hidden');

            const loadingPhases = [
                'Membaca data Surat Tugas & Pelaksana...',
                'Menganalisis parameter lapangan & kendala...',
                'Menyusun narasi resmi pertanggungjawaban dinas...',
                'Menyelaraskan struktur tata bahasa kedinasan...'
            ];
            let phaseIdx = 0;
            const interval = setInterval(() => {
                phaseIdx = (phaseIdx + 1) % loadingPhases.length;
                if (loadingText) loadingText.innerText = loadingPhases[phaseIdx];
            }, 2500);

            try {
                const formData = new FormData(form);
                // Hapus method spoofing _method: PUT agar endpoint POST /laporan/generate tidak 405
                formData.delete('_method');

                const response = await fetch("{{ route('laporan.generate') }}", {
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
                        alert('Validasi formulir belum lengkap. Pastikan seluruh kolom wajib telah terisi.');
                        return;
                    }
                    throw new Error(data.message || 'Terjadi kesalahan pada layanan AI.');
                }

                if (data.hasil && data.hasil.startsWith('Error:')) {
                    alert(data.hasil);
                    return;
                }

                hasilTextarea.value = data.hasil || 'Terjadi kesalahan tidak diketahui.';
                hasilContainer.style.display = 'block';
                updateWordCount();

                // Smooth scroll ke narasi
                hasilContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            } catch (error) {
                alert(error.message || 'Terjadi gangguan koneksi saat menghubungi server AI.');
            } finally {
                clearInterval(interval);
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                indicator.classList.add('hidden');
                if (loadingText) loadingText.innerText = 'Sedang menyusun narasi laporan dengan AI...';
            }
        }

        // Inisialisasi saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateWordCount();
            const coordInput = document.getElementById('stamp_koordinat');
            if (coordInput && coordInput.value) {
                formatKoordinat(coordInput);
            }
        });
    </script>
</x-app-layout>
