<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('surat-tugas.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Surat Tugas
                    </a>
                    <span class="text-gray-300">•</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        ✏️ Edit Dokumen SPD
                    </span>
                    <span class="font-mono text-xs text-gray-500 font-semibold">
                        {{ $spd->nomor_spd }}
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-amber-600 to-orange-600 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    <span>{{ __('Edit Surat Perjalanan Dinas (SPD)') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1 max-w-3xl">
                    Perbarui data Surat Perjalanan Dinas dan rincian pengeluaran riil (DPR) untuk penugasan Nomor: <strong class="font-mono text-gray-800">{{ $suratTugas->nomor_surat }}</strong>.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition">
                    Batal & Kembali
                </a>

                <!-- Cetak SPD Dropdown (Template 1 & Template 2) -->
                <div class="relative inline-block text-left" x-data="{ openSpdPrint: false }" @click.outside="openSpdPrint = false">
                    <button type="button" @click="openSpdPrint = !openSpdPrint" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak SPD</span>
                        <svg class="w-3 h-3 text-emerald-200 transition-transform duration-150" :class="{ 'rotate-180': openSpdPrint }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="openSpdPrint" x-cloak class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-30 divide-y divide-gray-100">
                        <div class="px-3.5 py-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pilih Template Cetak SPD</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('export.spd', ['spd' => $spd, 'template' => 'template_1']) }}" target="_blank" class="flex items-start gap-3 px-3.5 py-2.5 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-900 transition group">
                                <span class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg group-hover:bg-emerald-200 transition mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <div>
                                    <div class="font-bold text-gray-900 group-hover:text-emerald-900">Template 1 (Standar)</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">Format lengkap 2 lembar LKPP/Kemenkeu dengan tabel 10 poin & lembar 2.</div>
                                </div>
                            </a>
                            <a href="{{ route('export.spd', ['spd' => $spd, 'template' => 'template_2']) }}" target="_blank" class="flex items-start gap-3 px-3.5 py-2.5 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-900 transition group">
                                <span class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg group-hover:bg-indigo-200 transition mt-0.5">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                <div>
                                    <div class="font-bold text-gray-900 group-hover:text-indigo-900">Template 2 (Lampiran ST)</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">Format lampiran ST 2 kolom x 2 baris ringkas, tanpa nomor SPD.</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @php
        $durasiHari = \Carbon\Carbon::parse($suratTugas->tgl_berangkat)->diffInDays(\Carbon\Carbon::parse($suratTugas->tgl_kembali)) + 1;

        $ppkMap = $ppkPegawais->keyBy('id')->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama,
                'nip' => $p->nip,
                'jabatan' => $p->jabatan ?? 'Pejabat Pembuat Komitmen',
                'golongan' => $p->golongan ?? '-',
                'pangkat' => $p->pangkat ?? '-',
            ];
        });
    @endphp

    <div class="py-8 bg-gray-50/60 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Error Notification Alert -->
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

            <!-- ======================================================== -->
            <!-- INFORMASI SURAT TUGAS REFERENSI (OVERVIEW CARD) -->
            <!-- ======================================================== -->
            <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white rounded-2xl shadow-md p-6 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-white/10">
                    <div class="flex items-center gap-2.5">
                        <span class="p-2 bg-white/10 rounded-xl text-white">
                            <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-bold text-blue-300 uppercase tracking-wider">Surat Tugas Terhubung</span>
                            <h3 class="text-base font-black font-mono leading-tight">{{ $suratTugas->nomor_surat }}</h3>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <span class="text-xs bg-white/10 text-blue-100 px-3 py-1 rounded-full font-medium">
                            Tanggal: {{ $suratTugas->tgl_surat ? $suratTugas->tgl_surat->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
                    <!-- Pelaksana -->
                    <div class="space-y-1">
                        <span class="text-blue-300 font-semibold text-[11px] uppercase tracking-wider">Pegawai Pelaksana:</span>
                        <div class="font-bold text-white text-sm">{{ $suratTugas->pegawai->nama }}</div>
                        <div class="text-blue-200 font-mono text-[11px]">NIP. {{ $suratTugas->pegawai->nip }}</div>
                        <div class="text-blue-200/80 text-[11px]">{{ $suratTugas->pegawai->jabatan ?? '-' }}</div>
                    </div>

                    <!-- Tujuan & Jadwal -->
                    <div class="space-y-1">
                        <span class="text-blue-300 font-semibold text-[11px] uppercase tracking-wider">Tujuan & Jadwal:</span>
                        <div class="font-bold text-white">{{ $suratTugas->tujuan }}</div>
                        <div class="text-blue-200 text-[11px]">
                            {{ $suratTugas->tgl_berangkat ? $suratTugas->tgl_berangkat->format('d/m/Y') : '-' }} s.d. {{ $suratTugas->tgl_kembali ? $suratTugas->tgl_kembali->format('d/m/Y') : '-' }}
                        </div>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-500/30 text-emerald-200 border border-emerald-400/30">
                            {{ $durasiHari }} Hari Kalender
                        </span>
                    </div>

                    <!-- Pembebanan Anggaran -->
                    <div class="space-y-1">
                        <span class="text-blue-300 font-semibold text-[11px] uppercase tracking-wider">Mata Anggaran (DIPA):</span>
                        <div class="font-mono font-bold text-emerald-300">{{ $suratTugas->anggaran->mak ?? '-' }}</div>
                        <div class="text-blue-200 text-[11px] line-clamp-2" title="{{ $suratTugas->uraian_pembebanan }}">
                            {{ $suratTugas->uraian_pembebanan }}
                        </div>
                    </div>
                </div>

                <!-- Maksud Tugas -->
                <div class="mt-4 pt-3 border-t border-white/10 text-xs">
                    <span class="text-blue-300 font-semibold text-[11px] uppercase tracking-wider">Maksud Penugasan:</span>
                    <p class="text-blue-100 mt-0.5 leading-relaxed italic">"{{ $suratTugas->tugas }}"</p>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- FORMULIR EDIT SPD & DPR -->
            <!-- ======================================================== -->
            <form method="POST" action="{{ route('spd.update', $spd->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- CARD 1: INFORMASI LEMBAR SPD -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Parameter Surat Perjalanan Dinas (SPD)</h3>
                            <p class="text-xs text-gray-500">Nomor naskah dinas SPD, penunjukan PPK pembebanan, serta moda transportasi perjalanan dinas.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nomor SPD -->
                        <div>
                            <label for="nomor_spd" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Nomor SPD <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 font-mono text-xs">SPD</span>
                                <input 
                                    id="nomor_spd" 
                                    type="text" 
                                    name="nomor_spd" 
                                    value="{{ old('nomor_spd', $spd->nomor_spd) }}" 
                                    required 
                                    class="block w-full pl-12 pr-4 py-2.5 bg-white border border-gray-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 rounded-xl text-sm font-mono font-semibold text-gray-900 shadow-xs transition"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('nomor_spd')" class="mt-1" />
                        </div>

                        <!-- Alat Angkutan / Kendaraan -->
                        <div>
                            <label for="kendaraan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Alat Angkutan / Moda Transportasi <span class="text-rose-500">*</span>
                            </label>
                            <select id="kendaraan" name="kendaraan" class="block w-full px-4 py-2.5 bg-white border border-gray-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 rounded-xl text-sm font-medium text-gray-800 shadow-xs transition" required>
                                <option value="Kendaraan Dinas" {{ old('kendaraan', $spd->kendaraan) == 'Kendaraan Dinas' ? 'selected' : '' }}>🚗 Kendaraan Dinas (Mobil / Sepeda Motor Dinas)</option>
                                <option value="Kendaraan Umum" {{ old('kendaraan', $spd->kendaraan) == 'Kendaraan Umum' ? 'selected' : '' }}>🚌 Kendaraan Umum (Bus, Travel, Angkutan)</option>
                                <option value="Kendaraan Pribadi" {{ old('kendaraan', $spd->kendaraan) == 'Kendaraan Pribadi' ? 'selected' : '' }}>🛵 Kendaraan Pribadi (Mobil / Sepeda Motor Pribadi)</option>
                                <option value="Pesawat Udara" {{ old('kendaraan', $spd->kendaraan) == 'Pesawat Udara' ? 'selected' : '' }}>✈️ Pesawat Udara (Angkutan Udara)</option>
                                <option value="Kapal Laut" {{ old('kendaraan', $spd->kendaraan) == 'Kapal Laut' ? 'selected' : '' }}>🚢 Kapal Laut (Angkutan Penyeberangan)</option>
                            </select>
                            <x-input-error :messages="$errors->get('kendaraan')" class="mt-1" />
                        </div>
                    </div>

                    <!-- PPK Pegawai -->
                    <div class="mt-5">
                        <label for="ppk_pegawai_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Pejabat Pembuat Komitmen (PPK) <span class="text-rose-500">*</span>
                        </label>
                        <select id="ppk_pegawai_id" name="ppk_pegawai_id" class="block w-full select2" required onchange="updatePpkPreview(this.value)">
                            <option value="">-- Pilih Pejabat Pembuat Komitmen (PPK) --</option>
                            @forelse($ppkPegawais as $pegawai)
                                <option value="{{ $pegawai->id }}" {{ old('ppk_pegawai_id', $spd->ppk_pegawai_id) == $pegawai->id ? 'selected' : '' }}>
                                    {{ $pegawai->nama }} — {{ $pegawai->nip }} {{ $pegawai->jabatan ? '('.$pegawai->jabatan.')' : '' }}
                                </option>
                            @empty
                                <option value="" disabled class="text-rose-600 font-medium">⚠️ Belum ada pegawai dengan tag PPK. Harap atur di menu Data Pegawai.</option>
                            @endforelse
                        </select>
                        <x-input-error :messages="$errors->get('ppk_pegawai_id')" class="mt-1" />

                        <!-- Dynamic Preview Box PPK -->
                        <div id="preview-ppk-box" class="mt-3 p-3.5 bg-gradient-to-r from-emerald-50/70 to-teal-50/50 rounded-xl border border-emerald-100 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-bold text-xs flex items-center justify-center shrink-0 uppercase shadow-2xs" id="ppk-avatar">
                                {{ mb_substr($spd->ppkPegawai->nama ?? 'P', 0, 1) }}
                            </div>
                            <div class="text-xs space-y-0.5">
                                <div class="font-bold text-gray-900" id="ppk-nama">{{ $spd->ppkPegawai->nama ?? '-' }}</div>
                                <div class="text-gray-500 font-mono text-[11px]">NIP. <span id="ppk-nip">{{ $spd->ppkPegawai->nip ?? '-' }}</span></div>
                                <div class="text-emerald-700 font-semibold text-[11px]">Pejabat Pembuat Komitmen (PPK Satker)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: DAFTAR PENGELUARAN RIIL (DPR) -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 font-bold text-xs flex items-center justify-center">2</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Daftar Pengeluaran Riil (DPR) & Pertanggungjawaban Biaya</h3>
                            <p class="text-xs text-gray-500">Biaya perjalanan dinas riil tanpa kuitansi. Dapat diperbarui kembali setiap saat setelah perjalanan selesai.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Tanggal Laporan DPR -->
                        <div>
                            <label for="tgl_dpr" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tanggal Laporan DPR (Opsional)
                            </label>
                            <input 
                                id="tgl_dpr" 
                                type="date" 
                                name="tgl_dpr" 
                                value="{{ old('tgl_dpr', $spd->tgl_dpr ? $spd->tgl_dpr->format('Y-m-d') : '') }}" 
                                class="block w-full px-4 py-2.5 bg-white border border-gray-300 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 rounded-xl text-sm font-medium text-gray-800 shadow-xs transition"
                            />
                            <x-input-error :messages="$errors->get('tgl_dpr')" class="mt-1" />
                        </div>

                        <!-- Nilai DPR -->
                        <div>
                            <label for="nilai_dpr" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Total Biaya Riil / Nilai DPR (Rp)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-500 font-bold text-xs">Rp</span>
                                <input 
                                    id="nilai_dpr" 
                                    type="number" 
                                    step="1" 
                                    name="nilai_dpr" 
                                    value="{{ old('nilai_dpr', $spd->nilai_dpr ? (int) $spd->nilai_dpr : '') }}" 
                                    placeholder="Contoh: 350000" 
                                    oninput="updateTerbilang(this.value)" 
                                    class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 rounded-xl text-sm font-bold text-gray-900 shadow-xs transition"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('nilai_dpr')" class="mt-1" />

                            <!-- Quick Nominal Chips -->
                            <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                <span class="text-[10px] text-gray-400 uppercase font-semibold">Pilih cepat:</span>
                                <button type="button" onclick="setNominal(150000)" class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition">150.000</button>
                                <button type="button" onclick="setNominal(250000)" class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition">250.000</button>
                                <button type="button" onclick="setNominal(350000)" class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition">350.000</button>
                                <button type="button" onclick="setNominal(500000)" class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition">500.000</button>
                            </div>
                        </div>
                    </div>

                    <!-- LIVE TERBILANG PREVIEW -->
                    <div id="terbilang-box" class="{{ $spd->nilai_dpr ? '' : 'hidden' }} mt-4 p-3.5 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-200 text-xs text-teal-900 flex items-start gap-2.5">
                        <span class="text-base">💵</span>
                        <div>
                            <div class="font-bold text-teal-950 flex items-center gap-2">
                                <span id="nominal-formatted">Rp {{ number_format($spd->nilai_dpr ?? 0, 0, ',', '.') }}</span>
                                <span class="text-teal-400 font-normal">•</span>
                                <span class="text-teal-700 font-normal">Format Terbilang:</span>
                            </div>
                            <div class="italic text-teal-800 font-medium mt-0.5" id="terbilang-text">-</div>
                        </div>
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
                        <span>Perubahan data SPD akan langsung diperbarui pada seluruh dokumen cetak PDF terkait.</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('surat-tugas.index') }}" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition shadow-xs">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-orange-600/30 transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan SPD
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
            border-color: #059669 !important;
            box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2) !important;
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
            background-color: #059669 !important;
            color: #ffffff !important;
        }
    </style>

    <script>
        const ppkData = @json($ppkMap);

        function terbilang(angka) {
            angka = Math.floor(Math.abs(Number(angka)));
            if (isNaN(angka) || angka === 0) return 'Nol Rupiah';

            const bilangan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            let hasil = '';

            if (angka < 12) {
                hasil = bilangan[angka];
            } else if (angka < 20) {
                hasil = terbilang(angka - 10).replace(' Rupiah', '') + ' Belas';
            } else if (angka < 100) {
                hasil = bilangan[Math.floor(angka / 10)] + ' Puluh ' + (angka % 10 !== 0 ? bilangan[angka % 10] : '');
            } else if (angka < 200) {
                hasil = 'Seratus ' + (angka - 100 !== 0 ? terbilang(angka - 100).replace(' Rupiah', '') : '');
            } else if (angka < 1000) {
                hasil = bilangan[Math.floor(angka / 100)] + ' Ratus ' + (angka % 100 !== 0 ? terbilang(angka % 100).replace(' Rupiah', '') : '');
            } else if (angka < 2000) {
                hasil = 'Seribu ' + (angka - 1000 !== 0 ? terbilang(angka - 1000).replace(' Rupiah', '') : '');
            } else if (angka < 1000000) {
                hasil = terbilang(Math.floor(angka / 1000)).replace(' Rupiah', '') + ' Ribu ' + (angka % 1000 !== 0 ? terbilang(angka % 1000).replace(' Rupiah', '') : '');
            } else if (angka < 1000000000) {
                hasil = terbilang(Math.floor(angka / 1000000)).replace(' Rupiah', '') + ' Juta ' + (angka % 1000000 !== 0 ? terbilang(angka % 1000000).replace(' Rupiah', '') : '');
            } else if (angka < 1000000000000) {
                hasil = terbilang(Math.floor(angka / 1000000000)).replace(' Rupiah', '') + ' Miliar ' + (angka % 1000000000 !== 0 ? terbilang(angka % 1000000000).replace(' Rupiah', '') : '');
            }
            return hasil.trim() + ' Rupiah';
        }

        function updateTerbilang(val) {
            const num = parseFloat(val);
            const box = $('#terbilang-box');
            if (!isNaN(num) && num > 0) {
                $('#nominal-formatted').text('Rp ' + num.toLocaleString('id-ID'));
                $('#terbilang-text').text('"' + terbilang(num) + '"');
                box.removeClass('hidden');
            } else {
                box.addClass('hidden');
            }
        }

        function setNominal(val) {
            $('#nilai_dpr').val(val);
            updateTerbilang(val);
        }

        function updatePpkPreview(id) {
            const p = ppkData[id];
            const box = $('#preview-ppk-box');
            if (p) {
                $('#ppk-nama').text(p.nama);
                $('#ppk-nip').text(p.nip);
                $('#ppk-avatar').text(p.nama.charAt(0).toUpperCase());
                box.removeClass('hidden');
            } else {
                box.addClass('hidden');
            }
        }

        $(document).ready(function() {
            $('#ppk_pegawai_id').select2({
                placeholder: "-- Pilih Pejabat Pembuat Komitmen (PPK) --",
                width: '100%'
            });

            $('#ppk_pegawai_id').on('change', function() {
                updatePpkPreview($(this).val());
            });

            if ($('#ppk_pegawai_id').val()) {
                updatePpkPreview($('#ppk_pegawai_id').val());
            }

            if ($('#nilai_dpr').val()) {
                updateTerbilang($('#nilai_dpr').val());
            }
        });
    </script>
</x-app-layout>
