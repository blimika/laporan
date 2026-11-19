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
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        ✏️ Mode Edit Laporan Mandiri
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                        ✨ Gemini AI Studio
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        📸 Support Foto WebP & Geotag
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-purple-600 to-indigo-600 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    <span>{{ __('Edit Hanya Laporan (Generate AI)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1 max-w-3xl">
                    Perbarui parameter laporan perjalanan dinas lepas, kelola foto dokumentasi kegiatan, atau generate ulang narasi resmi berbasis AI secara mandiri.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a href="{{ route('hanya-laporan.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition">
                    Daftar Laporan
                </a>
                <a href="{{ route('export.hanya-laporan', $hanyaLaporan) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-purple-50 border border-purple-200 text-purple-700 hover:bg-purple-100 rounded-xl text-xs font-semibold shadow-xs transition" title="Unduh dan tinjau format PDF hasil laporan">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Preview PDF
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $ppkPegawais = $ppkPegawais ?? collect();
        $currentNama = old('nama_pegawai', $hanyaLaporan->nama_pegawai ?? '');
        $currentNip = old('nip_pegawai', $hanyaLaporan->nip_pegawai ?? '');
        $namaInDb = !empty($currentNama) && $pegawais->contains('nama', $currentNama);
        $isCustomPegawai = !empty($currentNama) && !$namaInDb;

        $stampDatetimeVal = '';
        if (!empty($hanyaLaporan->stamp_datetime)) {
            try {
                $stampDatetimeVal = \Carbon\Carbon::parse($hanyaLaporan->stamp_datetime)->format('Y-m-d\TH:i');
            } catch (\Exception $e) {
                $stampDatetimeVal = $hanyaLaporan->stamp_datetime;
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

            <!-- CARD CONTEXT: METADATA LAPORAN SAAT INI -->
            <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-slate-900 text-white rounded-2xl shadow-md p-5 sm:p-6 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-white/10">
                    <div class="flex items-center gap-2.5">
                        <span class="p-2 bg-white/10 rounded-xl text-white">
                            <svg class="w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-bold text-purple-300 uppercase tracking-wider">Identitas Dokumen Laporan</span>
                            <h3 class="text-base font-black font-mono leading-tight">ST: {{ $hanyaLaporan->nomor_st }}</h3>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                        <span class="text-xs bg-white/10 text-purple-100 px-3 py-1 rounded-full font-medium">
                            Dibuat: {{ $hanyaLaporan->created_at ? $hanyaLaporan->created_at->format('d M Y H:i') : '-' }}
                        </span>
                        <span class="text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 px-3 py-1 rounded-full font-medium">
                            {{ $hanyaLaporan->dokumentasi->count() }} Foto Terunggah
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
                    <div class="space-y-0.5">
                        <span class="text-purple-300 font-semibold text-[11px] uppercase tracking-wider">Pegawai Bertugas:</span>
                        <div class="font-bold text-white text-sm">{{ $hanyaLaporan->nama_pegawai }}</div>
                        <div class="text-purple-200 font-mono text-[11px]">
                            {{ $hanyaLaporan->nip_pegawai ? 'NIP. ' . $hanyaLaporan->nip_pegawai : '(Non-NIP / Petugas Mitra)' }}
                        </div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-purple-300 font-semibold text-[11px] uppercase tracking-wider">Tujuan & Lokasi:</span>
                        <div class="font-bold text-white">{{ $hanyaLaporan->tujuan_perjalanan }}</div>
                        <div class="text-purple-200 text-[11px]">{{ $hanyaLaporan->lokasi_perjalanan }}</div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-purple-300 font-semibold text-[11px] uppercase tracking-wider">Kategori & Jadwal:</span>
                        <div class="font-bold text-purple-200">{{ $hanyaLaporan->kategori }}</div>
                        <div class="text-purple-200/80 text-[11px]">
                            Pelaksanaan: {{ $hanyaLaporan->tgl_perjalanan ? $hanyaLaporan->tgl_perjalanan->format('d M Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM UTAMA EDIT LAPORAN -->
            <form id="laporan-form" method="POST" action="{{ route('hanya-laporan.update', $hanyaLaporan->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- CARD KHUSUS ADMIN: PINDAHKAN KEPEMILIKAN LAPORAN -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                    <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 shadow-xs">
                        <div class="flex items-center gap-2.5 mb-3">
                            <span class="p-1.5 bg-amber-500 text-white rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider">Otorisasi Kepemilikan Laporan (Khusus Administrator)</h4>
                                <p class="text-xs text-amber-700">Pindahkan kepemilikan dokumen ini ke akun pengguna/operator lain jika diperlukan.</p>
                            </div>
                        </div>

                        <div>
                            <select id="user_id" name="user_id" class="block w-full border-amber-300 focus:border-amber-600 focus:ring-amber-600 rounded-xl shadow-xs text-xs select2">
                                <option value="">-- Tetap Gunakan Pemilik Saat Ini ({{ $hanyaLaporan->user->name ?? 'Default' }}) --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('user_id', $hanyaLaporan->user_id) == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} (@ {{ $u->username }} - {{ $u->email }}) — {{ ucfirst($u->role) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                        </div>
                    </div>
                @endif

                <!-- ======================================================== -->
                <!-- SECTION 1: IDENTITAS PELAKSANA & SURAT TUGAS -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Identitas Pelaksana & Administrasi Surat</h3>
                            <p class="text-xs text-gray-500">Tentukan pelaksana tugas serta nomor surat penugasan dinas.</p>
                        </div>
                    </div>

                    <!-- Row 1: Nama Pegawai & NIP -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Nama Pegawai -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="nama_pegawai_select" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Nama Pegawai Pelaksana <span class="text-rose-500">*</span>
                                </label>
                                <label class="inline-flex items-center text-xs text-purple-700 hover:text-purple-900 font-semibold cursor-pointer select-none">
                                    <input type="checkbox" id="check_custom_pegawai" class="rounded border-gray-300 text-purple-600 shadow-xs mr-1.5 focus:ring-purple-500" onchange="toggleCustomPegawai()" {{ $isCustomPegawai ? 'checked' : '' }}>
                                    Nama Custom (Non-Database)
                                </label>
                            </div>

                            <!-- Opsi 1: Select Database -->
                            <div id="wrapper_select_pegawai" class="{{ $isCustomPegawai ? 'hidden' : '' }}">
                                <select id="nama_pegawai_select" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-sm select2">
                                    <option value="">-- Ketik atau Pilih Nama Pegawai di Database --</option>
                                    @foreach($pegawais as $p)
                                        <option value="{{ $p->nama }}" data-nip="{{ $p->nip }}" {{ (!$isCustomPegawai && $currentNama == $p->nama) ? 'selected' : '' }}>
                                            {{ $p->nama }} — {{ $p->nip }} ({{ $p->jabatan ?? '-' }})
                                        </option>
                                    @endforeach
                                    @if($isCustomPegawai)
                                        <option value="{{ $currentNama }}" data-nip="{{ $currentNip }}" selected>
                                            {{ $currentNama }} (Custom)
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <!-- Opsi 2: Input Teks Custom -->
                            <div id="wrapper_custom_pegawai" class="{{ $isCustomPegawai ? '' : 'hidden' }}">
                                <div class="relative">
                                    <input type="text" id="nama_pegawai_custom" class="border-purple-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm pl-9" placeholder="Ketik nama lengkap beserta gelar..." value="{{ $currentNama }}">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-purple-500 text-xs">✏️</span>
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1">Gunakan opsi ini jika pelaksana adalah mitra, konsultan, atau pegawai luar satker.</p>
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
                                <span class="text-[11px] text-gray-400">Kosongkan jika bukan ASN / Mitra</span>
                            </div>
                            <input id="nip_pegawai" type="text" name="nip_pegawai" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm font-mono" placeholder="Contoh: 198801012010121001 (opsional)" value="{{ $currentNip }}" />
                            <p class="text-[11px] text-gray-400 mt-1">Otomatis terisi jika memilih nama pegawai dari database.</p>
                            <x-input-error :messages="$errors->get('nip_pegawai')" class="mt-1.5" />
                        </div>
                    </div>

                    <!-- Row 2: Nomor ST & Nomor SPD -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Nomor Surat Tugas -->
                        <div>
                            <label for="nomor_st" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Nomor Surat Tugas (ST) <span class="text-rose-500">*</span>
                            </label>
                            <input id="nomor_st" type="text" name="nomor_st" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm font-mono font-semibold text-gray-900" placeholder="Contoh: 012/ST/5202/2026" value="{{ old('nomor_st', $hanyaLaporan->nomor_st) }}" required />
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
                            <input id="nomor_spd" type="text" name="nomor_spd" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm font-mono" placeholder="Contoh: 012/SPD/5202/2026" value="{{ old('nomor_spd', $hanyaLaporan->nomor_spd) }}" />
                            <x-input-error :messages="$errors->get('nomor_spd')" class="mt-1.5" />
                        </div>
                    </div>

                    <!-- Row 3: Tanggal Laporan & Tanggal Perjalanan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Tanggal Laporan Dibuat -->
                        <div>
                            <label for="tgl_laporan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tanggal Laporan Dibuat <span class="text-rose-500">*</span>
                            </label>
                            <input id="tgl_laporan" type="date" name="tgl_laporan" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm font-medium text-gray-800" value="{{ old('tgl_laporan', $hanyaLaporan->tgl_laporan ? $hanyaLaporan->tgl_laporan->format('Y-m-d') : date('Y-m-d')) }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Tanggal penetapan tanda tangan laporan.</p>
                            <x-input-error :messages="$errors->get('tgl_laporan')" class="mt-1.5" />
                        </div>

                        <!-- Tanggal Perjalanan Pelaksanaan -->
                        <div>
                            <label for="tgl_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tanggal Pelaksanaan Perjalanan <span class="text-rose-500">*</span>
                            </label>
                            <input id="tgl_perjalanan" type="date" name="tgl_perjalanan" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm font-medium text-gray-800" value="{{ old('tgl_perjalanan', $hanyaLaporan->tgl_perjalanan ? $hanyaLaporan->tgl_perjalanan->format('Y-m-d') : date('Y-m-d')) }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Tanggal riil kegiatan bertugas di lapangan.</p>
                            <x-input-error :messages="$errors->get('tgl_perjalanan')" class="mt-1.5" />
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 2: DETAIL KEGIATAN & LAPANGAN -->
                <!-- ======================================================== -->
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
                            <input id="lokasi_perjalanan" type="text" name="lokasi_perjalanan" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm text-gray-900" placeholder="Contoh: Kantor Desa Sukamaju, Kec. Jonggat" value="{{ old('lokasi_perjalanan', $hanyaLaporan->lokasi_perjalanan) }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Nama desa, kecamatan, instansi, atau tempat yang dikunjungi.</p>
                            <x-input-error :messages="$errors->get('lokasi_perjalanan')" class="mt-1.5" />
                        </div>

                        <!-- Tujuan Perjalanan Singkat -->
                        <div>
                            <label for="tujuan_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tujuan Perjalanan (Singkat & Jelas) <span class="text-rose-500">*</span>
                            </label>
                            <input id="tujuan_perjalanan" type="text" name="tujuan_perjalanan" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm text-gray-900" placeholder="Contoh: Koordinasi teknis pelaksanaan survei ekonomi 2026" value="{{ old('tujuan_perjalanan', $hanyaLaporan->tujuan_perjalanan) }}" required oninput="reprocessImages()" />
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
                            <input id="pegawai_ditemui" type="text" name="pegawai_ditemui" class="border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs block w-full text-sm text-gray-900" placeholder="Contoh: Kepala Desa Bpk. H. Ahmad dan Sekretaris Desa" value="{{ old('pegawai_ditemui', $hanyaLaporan->pegawai_ditemui) }}" required />
                            <p class="text-[11px] text-gray-400 mt-1">Nama pejabat, staf, responden, atau tokoh yang diwawancarai/ditemui.</p>
                            <x-input-error :messages="$errors->get('pegawai_ditemui')" class="mt-1.5" />
                        </div>

                        <!-- Kategori Perjalanan -->
                        <div>
                            @php
                                $standardKategori = ['Translok Koordinasi', 'Pengawasan / Supervisi Lapangan', 'Verifikasi Data', 'Pendataan Lapangan'];
                                $rawKategori = old('kategori', $hanyaLaporan->kategori);
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
                        <textarea id="kendala_ditemui" name="kendala_ditemui" rows="2" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-sm placeholder:text-gray-400" placeholder="Contoh: Terjadi hujan deras saat menuju lokasi, responden tidak berada di tempat sehingga dijadwalkan ulang... (Biarkan kosong jika perjalanan lancar)">{{ old('kendala_ditemui', $hanyaLaporan->kendala_ditemui) }}</textarea>
                        <p class="text-[11px] text-gray-400 mt-1">💡 Tips: Jika ada kendala, AI akan otomatis membuatkan bagian evaluasi dan solusi tindak lanjut pada narasi laporan.</p>
                        <x-input-error :messages="$errors->get('kendala_ditemui')" class="mt-1.5" />
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 3: LAMPIRAN DOKUMEN TAMBAHAN (DPR & PERNYATAAN RIIL) -->
                <!-- ======================================================== -->
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
                                    <input type="checkbox" id="check_dpr" name="check_dpr" value="1" class="w-4 h-4 rounded border-gray-300 text-purple-600 shadow-xs focus:ring-purple-500 cursor-pointer" onchange="toggleDprInputs()" {{ old('check_dpr', $hanyaLaporan->ada_dpr) ? 'checked' : '' }}>
                                </div>
                                <div class="flex-1">
                                    <label for="check_dpr" class="text-xs font-bold text-gray-900 cursor-pointer uppercase tracking-wider block">
                                        Lampirkan Daftar Pengeluaran Riil (DPR)
                                    </label>
                                    <p class="text-xs text-gray-500 mt-0.5">Lampiran pertanggungjawaban biaya riil (uang harian/transport) sesuai standar biaya masukan.</p>
                                </div>
                            </div>

                            <div id="dpr_inputs" class="mt-4 pt-4 border-t border-gray-200 space-y-3 {{ old('check_dpr', $hanyaLaporan->ada_dpr) ? '' : 'hidden' }}">
                                <div>
                                    <label for="nilai_dpr" class="block text-xs font-semibold text-gray-700 mb-1">
                                        Total Nilai Pengeluaran Riil (Rp) <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative rounded-xl shadow-xs">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-xs font-bold font-mono">Rp</span>
                                        </div>
                                        <input id="nilai_dpr" name="nilai_dpr" type="number" class="block w-full pl-10 border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl text-sm font-mono font-bold text-gray-900" placeholder="Contoh: 150000" value="{{ old('nilai_dpr', $hanyaLaporan->nilai_dpr ? (int) $hanyaLaporan->nilai_dpr : '') }}" />
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
                                            <option value="{{ $p->id }}" {{ old('ppk_pegawai_id', $hanyaLaporan->ppk_pegawai_id) == $p->id ? 'selected' : '' }}>
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
                                    <input type="checkbox" id="check_pernyataan" name="check_pernyataan" value="1" class="w-4 h-4 rounded border-gray-300 text-purple-600 shadow-xs focus:ring-purple-500 cursor-pointer" {{ old('check_pernyataan', $hanyaLaporan->ada_pernyataan) ? 'checked' : '' }}>
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

                <!-- ======================================================== -->
                <!-- SECTION 4: FOTO DOKUMENTASI KEGIATAN (EXISTING & BARU) -->
                <!-- ======================================================== -->
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
                                <p class="text-xs text-gray-500">Kelola foto yang sudah tersimpan atau unggah foto tambahan dalam format WebP, JPG, dan PNG.</p>
                            </div>
                        </div>

                        <!-- Format Support Badges -->
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">JPG</span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">PNG</span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-300 shadow-2xs">✨ WEBP</span>
                        </div>
                    </div>

                    <!-- GALERI FOTO DOKUMENTASI SAAT INI -->
                    @if($hanyaLaporan->dokumentasi && $hanyaLaporan->dokumentasi->count() > 0)
                        <div class="mb-6 p-4 rounded-xl border border-gray-200 bg-gray-50/70">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Foto Dokumentasi Saat Ini ({{ $hanyaLaporan->dokumentasi->count() }})</span>
                                    <span class="text-[11px] text-gray-400">• Centang/Klik tombol merah untuk menandai foto yang ingin dihapus</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
                                @foreach($hanyaLaporan->dokumentasi as $dok)
                                    <div id="photo_card_{{ $dok->id }}" class="relative rounded-xl border border-gray-200 bg-white overflow-hidden shadow-2xs transition group hover:shadow-md">
                                        <div class="aspect-square relative overflow-hidden bg-gray-100">
                                            <a href="{{ asset($dok->file_path) }}" target="_blank" class="block w-full h-full" title="Klik untuk melihat foto ukuran penuh">
                                                <img src="{{ asset($dok->file_path) }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300" alt="Dokumentasi">
                                                
                                                @if($hanyaLaporan->is_stamped)
                                                    @php
                                                        $dtVal = $hanyaLaporan->stamp_datetime ? \Carbon\Carbon::parse($hanyaLaporan->stamp_datetime)->format('d/m/Y H:i') : '-';
                                                    @endphp
                                                    <div class="absolute bottom-1 left-1 right-1 text-white text-[8px] leading-tight font-bold border-l-2 border-yellow-400 pl-1 pointer-events-none drop-shadow-md truncate" style="text-shadow: 1px 1px 2px black, 0 0 1px black;">
                                                        {{ $hanyaLaporan->tujuan_perjalanan }}<br>{{ $dtVal }}<br>{{ $hanyaLaporan->stamp_koordinat }}
                                                    </div>
                                                @endif
                                            </a>

                                            <!-- Tombol & Checkbox Hapus -->
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

                    <!-- Dropzone Unggah Foto Tambahan -->
                    <div id="dropzone" class="relative border-2 border-dashed border-purple-300 hover:border-purple-500 bg-purple-50/30 hover:bg-purple-50/60 rounded-2xl p-6 text-center transition cursor-pointer group" onclick="document.getElementById('fotos').click()">
                        <input id="fotos" name="fotos[]" type="file" multiple accept="image/jpeg,image/png,image/jpg,image/webp,image/*" class="sr-only" onchange="handleFotosChange(event)" />
                        
                        <div class="max-w-md mx-auto space-y-2 pointer-events-none">
                            <div class="w-12 h-12 mx-auto rounded-full bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="text-xs text-gray-700">
                                <span class="font-bold text-purple-700 hover:underline">Klik untuk Menambah Foto Baru</span> atau seret & lepas berkas ke sini
                            </div>
                            <p class="text-[11px] text-gray-500 leading-relaxed">
                                Mendukung <strong>WEBP (Format foto kamera HP Android/iPhone)</strong>, <strong>JPG/JPEG</strong>, dan <strong>PNG</strong>. Maksimal 5MB per file foto.
                            </p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('fotos.*')" class="mt-1.5" />

                    <!-- STAMP WATERMARK GPS & TANGGAL -->
                    <div class="mt-5 p-5 border border-gray-200 rounded-2xl bg-gray-50/70">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="check_stamp" name="check_stamp" class="w-4 h-4 rounded border-gray-300 text-purple-600 shadow-xs focus:ring-purple-500 cursor-pointer" onchange="toggleStampInputs()" {{ $hanyaLaporan->is_stamped ? 'checked' : '' }}>
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

                        <div id="stamp_inputs" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 {{ $hanyaLaporan->is_stamped ? '' : 'hidden' }}">
                            <!-- Koordinat Manual / GPS -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="stamp_koordinat" class="block text-xs font-semibold text-gray-700">
                                        Koordinat Lokasi Geotag
                                    </label>
                                    <button type="button" onclick="getLocation()" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Ambil Lokasi Saat Ini (GPS)
                                    </button>
                                </div>
                                <input id="stamp_koordinat" name="stamp_koordinat" type="text" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-xs font-mono" placeholder="Contoh: -8.650000, 116.320000" value="{{ old('stamp_koordinat', $hanyaLaporan->stamp_koordinat) }}" onchange="formatKoordinat(this)" oninput="reprocessImages()" />
                                <p class="text-[11px] text-gray-400 mt-1">Format: Latitude, Longitude (contoh: -8.650000, 116.320000).</p>
                                <x-input-error :messages="$errors->get('stamp_koordinat')" class="mt-1" />
                            </div>

                            <!-- Tanggal & Jam -->
                            <div>
                                <label for="stamp_datetime" class="block text-xs font-semibold text-gray-700 mb-1">
                                    Waktu / Jam Pengambilan Foto
                                </label>
                                <input id="stamp_datetime" name="stamp_datetime" type="datetime-local" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-xs" value="{{ old('stamp_datetime', $stampDatetimeVal) }}" onchange="reprocessImages()" />
                                <p class="text-[11px] text-gray-400 mt-1">Waktu yang akan dicetak di sudut foto dokumentasi.</p>
                                <x-input-error :messages="$errors->get('stamp_datetime')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Container Preview Foto Tambahan Baru -->
                    <div id="image-preview-container" class="mt-4 flex flex-wrap gap-3"></div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 5: AI STUDIO & HASIL NARASI LAPORAN -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center">5</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                AI Studio & Hasil Narasi Laporan
                                <span class="text-[10px] bg-purple-100 text-purple-800 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider border border-purple-200">
                                    Gemini 2.5 Pro
                                </span>
                            </h3>
                            <p class="text-xs text-gray-500">Sesuaikan parameter struktur narasi atau biarkan AI menyusun ulang draft laporan kedinasan yang komprehensif.</p>
                        </div>
                    </div>

                    <!-- Parameter Pengaturan AI Studio -->
                    <div class="p-5 rounded-2xl bg-gradient-to-br from-purple-50/80 via-indigo-50/40 to-white border border-purple-200/80 mb-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-center">
                            <!-- Pilihan Panjang Paragraf -->
                            <div id="narasi-card-options">
                                <label for="panjang_narasi" class="block text-xs font-bold text-purple-950 uppercase tracking-wider mb-1.5">
                                    Panjang Narasi Laporan AI
                                </label>
                                <select id="panjang_narasi" name="panjang_narasi" class="block w-full border-purple-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-xs font-medium bg-white">
                                    <option value="minimal_2">Standar Ringkas (Minimal 2 Paragraf)</option>
                                    <option value="3_paragraf">Lengkap (Tepat 3 Paragraf Terstruktur)</option>
                                    <option value="lebih">Mendalam & Komprehensif (4+ Paragraf)</option>
                                </select>
                                <p class="text-[11px] text-purple-700/80 mt-1">Mengontrol kedalaman dan banyaknya paragraf narasi hasil perjalanan.</p>
                            </div>

                            <!-- Opsi Perjadin 8 Jam -->
                            <div class="p-3.5 bg-white rounded-xl border border-purple-200/80 shadow-2xs">
                                <div class="flex items-start gap-2.5">
                                    <input type="checkbox" id="is_8_jam" name="is_8_jam" value="1" class="w-4 h-4 rounded border-purple-300 text-purple-600 shadow-xs focus:ring-purple-500 mt-0.5 cursor-pointer" onchange="toggle8JamOption()">
                                    <div>
                                        <label for="is_8_jam" class="text-xs font-bold text-gray-900 cursor-pointer block">
                                            Format Khusus Perjadin 8 Jam (Tabel Jadwal Kegiatan)
                                        </label>
                                        <p class="text-[11px] text-gray-500 mt-0.5 leading-relaxed">
                                            AI akan menyusun tabel jadwal kegiatan terperinci dari pukul 08.00 s.d. 17.00 WITA.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Trigger Generate AI -->
                        <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                            <button type="button" id="btn-generate" onclick="generateAI()" class="w-full sm:w-auto bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs py-2.5 px-6 rounded-xl shadow-md hover:shadow-purple-600/30 inline-flex items-center justify-center gap-2 transition active:scale-95 cursor-pointer">
                                <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>Generate Ulang dengan AI ✨</span>
                            </button>

                            <div id="loading-indicator" class="hidden flex items-center gap-2 text-xs font-semibold text-purple-700 bg-purple-100/70 px-3.5 py-2 rounded-xl">
                                <svg class="animate-spin h-4 w-4 text-purple-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Sedang menyusun narasi laporan... Mohon tunggu.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Area Hasil Narasi Laporan -->
                    <div id="hasil-container" class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="hasil_perjalanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Teks Narasi Hasil Laporan <span class="text-rose-500">*</span>
                            </label>
                            
                            <!-- Badges & Tools -->
                            <div class="flex items-center gap-2">
                                <span id="word-count-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                    0 Kata
                                </span>
                                <span id="char-count-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-500 border border-gray-200">
                                    0 Karakter
                                </span>
                                <button type="button" id="btn-copy-narasi" onclick="copyHasilNarasi()" class="text-xs font-semibold text-purple-700 hover:text-purple-900 flex items-center gap-1 px-2 py-0.5 rounded hover:bg-purple-50 transition" title="Salin seluruh teks narasi ke clipboard">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    Salin Narasi
                                </button>
                            </div>
                        </div>

                        <textarea id="hasil_perjalanan" name="hasil_perjalanan" rows="12" class="block w-full border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs text-sm leading-relaxed font-sans" placeholder="Hasil narasi perjalanan dinas akan otomatis terisi di sini setelah klik tombol 'Generate dengan AI'. Anda bebas menyunting atau menambahkan detail sebelum menyimpan." required oninput="updateWordCount()">{{ old('hasil_perjalanan', $hanyaLaporan->hasil_perjalanan) }}</textarea>
                        
                        <p class="text-[11px] text-gray-400">
                            💡 Narasi ini akan dicetak pada lembar resmi laporan perjalanan dinas. Anda dapat menyunting kalimat secara manual sebelum menekan tombol simpan.
                        </p>
                        <x-input-error :messages="$errors->get('hasil_perjalanan')" class="mt-1.5" />
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- ACTION BUTTONS FOOTER -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Perubahan yang disimpan akan otomatis memperbarui dokumen cetak PDF dan lampiran foto.</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('hanya-laporan.index') }}" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition shadow-xs">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-emerald-600/30 transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Finalisasi & Simpan Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Select2 CDN & Custom Styles -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container .select2-selection--single {
            height: 44px !important;
            border-radius: 0.75rem !important;
            border: 1px solid #d1d5db !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 0.5rem !important;
            padding-right: 2rem !important;
            background-color: #ffffff !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827 !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #9333ea !important; /* purple-600 */
            box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%) !important;
            right: 10px !important;
        }
        .select2-dropdown {
            border-radius: 0.75rem !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
        }
        .select2-results__option {
            font-size: 0.875rem !important;
            padding: 8px 12px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #9333ea !important;
            color: #ffffff !important;
        }
    </style>

    <script>
        // ==========================================
        // SYNC & PEGAWAI SELECTION
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
        // KATEGORI & DPR TOGGLES
        // ==========================================
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

        // ==========================================
        // MANAJEMEN FOTO & STAMP GEOTAG
        // ==========================================
        function toggleDeletePhoto(id) {
            const checkbox = document.getElementById('del_photo_' + id);
            if (!checkbox) return;
            checkbox.checked = !checkbox.checked;
            handleDeleteCheck(id);
        }

        function handleDeleteCheck(id) {
            const checkbox = document.getElementById('del_photo_' + id);
            const card = document.getElementById('photo_card_' + id);
            const badge = document.getElementById('photo_badge_' + id);
            if (!checkbox || !card) return;

            if (checkbox.checked) {
                card.classList.add('opacity-50', 'border-rose-400', 'ring-2', 'ring-rose-400');
                if (badge) badge.classList.remove('hidden');
            } else {
                card.classList.remove('opacity-50', 'border-rose-400', 'ring-2', 'ring-rose-400');
                if (badge) badge.classList.add('hidden');
            }
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

        function removeSelectedFile(index) {
            originalFiles.splice(index, 1);
            reprocessImages();
        }

        function toggleStampInputs() {
            const inputs = document.getElementById('stamp_inputs');
            if (document.getElementById('check_stamp').checked) {
                inputs.classList.remove('hidden');
                if (!document.getElementById('stamp_datetime').value) {
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    document.getElementById('stamp_datetime').value = now.toISOString().slice(0, 16);
                }
            } else {
                inputs.classList.add('hidden');
            }
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
                }
            }
            reprocessImages();
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('stamp_koordinat').value = position.coords.latitude.toFixed(6) + ", " + position.coords.longitude.toFixed(6);
                    reprocessImages();
                }, function(error) {
                    alert("Gagal mendapatkan lokasi GPS: " + error.message);
                });
            } else {
                alert("Geolocation tidak didukung oleh peramban ini.");
            }
        }

        function formatBytes(bytes, decimals = 1) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function reprocessImages() {
            const container = document.getElementById('image-preview-container');
            container.innerHTML = '';
            if (originalFiles.length === 0) return;

            const isStamped = document.getElementById('check_stamp').checked;
            const dt = new DataTransfer();

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
                const card = document.createElement('div');
                card.className = 'w-36 rounded-xl border border-purple-200 bg-white overflow-hidden shadow-2xs relative group flex flex-col';

                const imgContainer = document.createElement('div');
                imgContainer.className = 'w-full h-32 relative bg-gray-100 overflow-hidden';

                const link = document.createElement('a');
                link.href = previewSrc;
                link.target = '_blank';
                link.className = 'block w-full h-full';
                link.title = 'Klik untuk memperbesar gambar';

                const img = document.createElement('img');
                img.src = previewSrc;
                img.className = 'object-cover w-full h-full group-hover:scale-105 transition';
                link.appendChild(img);

                if (isStamped) {
                    const stampDiv = document.createElement('div');
                    stampDiv.className = 'absolute bottom-1 left-1 right-1 text-white text-[8px] leading-tight font-bold border-l-2 border-yellow-400 pl-1 pointer-events-none drop-shadow-md truncate';
                    stampDiv.style.textShadow = '1px 1px 2px black, 0 0 1px black';
                    stampDiv.innerHTML = `${escapeHtml(tujuan)}<br>${hariTanggal}<br>${escapeHtml(koordinat)}`;
                    link.appendChild(stampDiv);
                }

                imgContainer.appendChild(link);

                // Tombol Hapus preview foto baru
                const delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.className = 'absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center hover:bg-rose-700 shadow-sm transition active:scale-95';
                delBtn.title = 'Hapus foto ini';
                delBtn.innerHTML = '&times;';
                delBtn.onclick = ((idx) => () => removeSelectedFile(idx))(i);
                imgContainer.appendChild(delBtn);

                // Footer kartu
                const infoDiv = document.createElement('div');
                infoDiv.className = 'p-1.5 text-[10px] text-gray-500 bg-white border-t border-gray-100 flex items-center justify-between';
                infoDiv.innerHTML = `<span class="truncate max-w-[75px]" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</span><span class="font-mono text-[9px] text-purple-700 font-bold">${formatBytes(file.size)}</span>`;

                card.appendChild(imgContainer);
                card.appendChild(infoDiv);
                container.appendChild(card);
            }

            document.getElementById('fotos').files = dt.files;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ==========================================
        // AI GENERATION STUDIO
        // ==========================================
        async function generateAI() {
            const btn = document.getElementById('btn-generate');
            const indicator = document.getElementById('loading-indicator');
            const hasilTextarea = document.getElementById('hasil_perjalanan');

            syncNamaPegawai();
            if (!document.getElementById('nama_pegawai').value) {
                alert('Silakan pilih atau isi Nama Pegawai terlebih dahulu.');
                return;
            }

            const form = document.getElementById('laporan-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            btn.disabled = true;
            btn.classList.add('opacity-50');
            indicator.classList.remove('hidden');

            try {
                const formData = new FormData(form);
                formData.delete('_method'); // Mencegah Laravel method spoofing ke PUT saat generate AI

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
                        alert('Validasi formulir gagal. Mohon lengkapi parameter yang diperlukan.');
                        return;
                    }
                    throw new Error(data.message || 'Terjadi kesalahan dari server AI.');
                }

                if (data.hasil && data.hasil.startsWith('Error:')) {
                    alert(data.hasil);
                    return;
                }

                hasilTextarea.value = data.hasil || 'Terjadi kesalahan tidak diketahui.';
                updateWordCount();

                // Smooth scroll to result
                document.getElementById('hasil-container').scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            } catch (error) {
                alert(error.message || 'Terjadi kendala saat menghubungi server AI.');
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-50');
                indicator.classList.add('hidden');
            }
        }

        function updateWordCount() {
            const text = document.getElementById('hasil_perjalanan').value.trim();
            const words = text ? text.split(/\s+/).length : 0;
            const chars = text.length;
            document.getElementById('word-count-badge').textContent = `${words} Kata`;
            document.getElementById('char-count-badge').textContent = `${chars} Karakter`;
        }

        function copyHasilNarasi() {
            const text = document.getElementById('hasil_perjalanan').value;
            if (!text) {
                alert('Belum ada teks narasi laporan untuk disalin.');
                return;
            }
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    const btn = document.getElementById('btn-copy-narasi');
                    const origHtml = btn.innerHTML;
                    btn.innerHTML = `<svg class="w-3.5 h-3.5 text-emerald-600 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Tersalin!`;
                    setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
                });
            } else {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                alert('Teks narasi berhasil disalin ke clipboard.');
            }
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
                    placeholder: "-- Ketik atau Pilih Nama Pegawai di Database --",
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

                if ($('#user_id').length) {
                    $('#user_id').select2({
                        placeholder: "-- Pilih Pemilik Laporan --",
                        width: '100%'
                    });
                }
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
