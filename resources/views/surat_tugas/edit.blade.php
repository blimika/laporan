<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('surat-tugas.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Surat Tugas
                    </a>
                    <span class="text-gray-300">•</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        ✏️ Mode Edit Dokumen
                    </span>
                    <span class="font-mono text-xs text-gray-500 font-semibold">
                        {{ $suratTugas->nomor_surat }}
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-amber-600 to-orange-600 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    <span>{{ __('Edit Surat Tugas') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1 max-w-3xl">
                    Perbarui rincian Surat Tugas kedinasan. Perubahan nomor surat, penandatangan, atau tanggal penugasan akan otomatis disesuaikan pada lembar cetak PDF dan dokumen terkait.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition">
                    Batal & Kembali
                </a>
                <a href="{{ route('export.surat-tugas', $suratTugas) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-semibold shadow-xs transition">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Preview PDF
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $satker = \App\Models\Satker::find(session('satker_id'));
        $tahun = \App\Models\Tahun::find(session('tahun_id'));
        $satkerName = $satker ? $satker->nama : 'BPS Instansi';
        $satkerKode = $satker ? $satker->kode : '5202';
        $tahunName = $tahun ? $tahun->tahun : date('Y');

        $lamaHariInitial = \Carbon\Carbon::parse($suratTugas->tgl_berangkat)->diffInDays(\Carbon\Carbon::parse($suratTugas->tgl_kembali)) + 1;

        $pegawaiMap = $pegawais->keyBy('id')->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama,
                'nip' => $p->nip,
                'jabatan' => $p->jabatan ?? '-',
                'golongan' => $p->golongan ?? '-',
                'pangkat' => $p->pangkat ?? '-',
                'tags' => $p->tags ?? [],
            ];
        });

        $kepalaMap = $kepalaPegawais->keyBy('id')->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama,
                'nip' => $p->nip,
                'jabatan' => $p->jabatan ?? '-',
                'golongan' => $p->golongan ?? '-',
                'pangkat' => $p->pangkat ?? '-',
                'tags' => $p->tags ?? [],
            ];
        });

        $anggaranMap = $anggarans->keyBy('id')->map(function ($a) {
            return [
                'id' => $a->id,
                'mak' => $a->mak,
                'program' => ($a->program_kode ? $a->program_kode . ' - ' : '') . ($a->program_uraian ?? '-'),
                'kegiatan' => ($a->kegiatan_kode ? $a->kegiatan_kode . ' - ' : '') . ($a->kegiatan_uraian ?? '-'),
                'output' => ($a->output_kode ? $a->output_kode . ' - ' : '') . ($a->output_uraian ?? '-'),
                'komponen' => ($a->komponen_kode ? $a->komponen_kode . ' - ' : '') . ($a->komponen_uraian ?? '-'),
                'subkomponen' => ($a->subkomponen_kode ? $a->subkomponen_kode . ' - ' : '') . ($a->subkomponen_uraian ?? '-'),
                'akun' => ($a->akun_kode ? $a->akun_kode . ' - ' : '') . ($a->akun_uraian ?? '-'),
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
                            <p class="text-xs text-rose-700 mt-0.5">Terdapat beberapa data yang wajib dilengkapi atau formatnya belum sesuai:</p>
                            <ul class="list-disc list-inside text-xs mt-2 space-y-1 text-rose-800">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form id="surat-tugas-form" method="POST" action="{{ route('surat-tugas.update', $suratTugas) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- ======================================================== -->
                <!-- LANGKAH 1: IDENTITAS & PENANDATANGAN DOKUMEN -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Identitas Surat & Penandatangan Dokumen</h3>
                            <p class="text-xs text-gray-500">Nomor naskah dinas, tanggal penetapan, pegawai pelaksana, serta Kepala Satker penandatangan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nomor Surat -->
                        <div>
                            <label for="nomor_surat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Nomor Surat Tugas <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 font-mono text-xs">№</span>
                                <input 
                                    id="nomor_surat" 
                                    type="text" 
                                    name="nomor_surat" 
                                    value="{{ old('nomor_surat', $suratTugas->nomor_surat) }}" 
                                    placeholder="Contoh: B-001/{{ $satkerKode }}/KP.120/{{ $tahunName }}" 
                                    required 
                                    class="block w-full pl-9 pr-4 py-2.5 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-sm font-mono font-semibold text-gray-900 shadow-xs transition"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('nomor_surat')" class="mt-1" />
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label for="tgl_surat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tanggal Penetapan ST <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                id="tgl_surat" 
                                type="date" 
                                name="tgl_surat" 
                                value="{{ old('tgl_surat', $suratTugas->tgl_surat ? $suratTugas->tgl_surat->format('Y-m-d') : date('Y-m-d')) }}" 
                                required 
                                class="block w-full px-4 py-2.5 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-sm font-medium text-gray-800 shadow-xs transition"
                            />
                            <x-input-error :messages="$errors->get('tgl_surat')" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <!-- Pegawai Pelaksana -->
                        <div>
                            <label for="pegawai_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Pegawai Pelaksana (Yang Ditugaskan) <span class="text-rose-500">*</span>
                            </label>
                            <select id="pegawai_id" name="pegawai_id" class="block w-full select2" required onchange="updatePelaksanaPreview(this.value)">
                                <option value="">-- Ketik Nama atau NIP Pegawai --</option>
                                @foreach($pegawais as $pegawai)
                                    <option value="{{ $pegawai->id }}" {{ old('pegawai_id', $suratTugas->pegawai_id) == $pegawai->id ? 'selected' : '' }}>
                                        {{ $pegawai->nama }} — {{ $pegawai->nip }} {{ $pegawai->jabatan ? '('.$pegawai->jabatan.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pegawai_id')" class="mt-1" />

                            <!-- Dynamic Preview Box Pelaksana -->
                            <div id="preview-pelaksana-box" class="mt-3 p-3.5 bg-gradient-to-r from-blue-50/70 to-indigo-50/50 rounded-xl border border-blue-100 flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold text-xs flex items-center justify-center shrink-0 uppercase shadow-2xs" id="pelaksana-avatar">
                                    {{ mb_substr($suratTugas->pegawai->nama ?? 'P', 0, 1) }}
                                </div>
                                <div class="text-xs space-y-0.5">
                                    <div class="font-bold text-gray-900" id="pelaksana-nama">{{ $suratTugas->pegawai->nama ?? '-' }}</div>
                                    <div class="text-gray-500 font-mono text-[11px]">NIP. <span id="pelaksana-nip">{{ $suratTugas->pegawai->nip ?? '-' }}</span></div>
                                    <div class="text-gray-600 text-[11px] flex items-center gap-2">
                                        <span id="pelaksana-jabatan">{{ $suratTugas->pegawai->jabatan ?? 'Staf / Fungsional' }}</span>
                                        <span class="text-gray-300">•</span>
                                        <span class="font-medium text-blue-700" id="pelaksana-golongan">{{ $suratTugas->pegawai->golongan ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kepala Pegawai (Penandatangan) -->
                        <div>
                            <label for="kepala_pegawai_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Penandatangan ST (Kepala Satker / Plt / Plh) <span class="text-rose-500">*</span>
                            </label>
                            <select id="kepala_pegawai_id" name="kepala_pegawai_id" class="block w-full select2" required onchange="updateKepalaPreview(this.value)">
                                <option value="">-- Pilih Pejabat Penandatangan --</option>
                                @forelse($kepalaPegawais as $pegawai)
                                    <option value="{{ $pegawai->id }}" {{ old('kepala_pegawai_id', $suratTugas->kepala_pegawai_id) == $pegawai->id ? 'selected' : '' }}>
                                        {{ $pegawai->nama }} ({{ $pegawai->nip }})
                                        @if(!empty($pegawai->tags))
                                            [{{ implode(', ', array_map(fn($t) => strtoupper($t), $pegawai->tags)) }}]
                                        @endif
                                    </option>
                                @empty
                                    <option value="" disabled class="text-rose-600 font-medium">⚠️ Belum ada pegawai bertag Kepala/Plt/Plh (Atur di Data Pegawai)</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('kepala_pegawai_id')" class="mt-1" />

                            <!-- Dynamic Preview Box Kepala -->
                            <div id="preview-kepala-box" class="mt-3 p-3.5 bg-gradient-to-r from-purple-50/70 to-indigo-50/50 rounded-xl border border-purple-100 flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0 uppercase shadow-2xs" id="kepala-avatar">
                                    {{ mb_substr($suratTugas->kepalaPegawai->nama ?? 'K', 0, 1) }}
                                </div>
                                <div class="text-xs space-y-0.5">
                                    <div class="font-bold text-gray-900" id="kepala-nama">{{ $suratTugas->kepalaPegawai->nama ?? '-' }}</div>
                                    <div class="text-gray-500 font-mono text-[11px]">NIP. <span id="kepala-nip">{{ $suratTugas->kepalaPegawai->nip ?? '-' }}</span></div>
                                    <div class="text-purple-700 font-semibold text-[11px]" id="kepala-role-tag">Pejabat Penandatangan ST</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- LANGKAH 2: RINCIAN PENUGASAN & JADWAL PELAKSANAAN -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">2</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Rincian Penugasan & Jadwal Pelaksanaan</h3>
                            <p class="text-xs text-gray-500">Uraikan maksud penugasan dinas, lokasi tempat tujuan, serta durasi tanggal perjalanan.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <!-- Tugas / Maksud Penugasan -->
                        <div>
                            <label for="tugas" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Maksud / Uraian Tugas Dinas <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                id="tugas" 
                                name="tugas" 
                                rows="3" 
                                required 
                                placeholder="Contoh: Melakukan koordinasi pelaksanaan kegiatan sensus pertanian dan verifikasi sampel rumah tangga ke lapangan..." 
                                class="block w-full px-4 py-3 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-sm text-gray-900 shadow-xs transition placeholder:text-gray-400"
                            >{{ old('tugas', $suratTugas->tugas) }}</textarea>
                            <x-input-error :messages="$errors->get('tugas')" class="mt-1" />
                        </div>

                        <!-- Tempat Tujuan -->
                        <div>
                            <label for="tujuan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Tempat / Lokasi Tujuan Penugasan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                                <input 
                                    id="tujuan" 
                                    type="text" 
                                    name="tujuan" 
                                    value="{{ old('tujuan', $suratTugas->tujuan) }}" 
                                    placeholder="Contoh: Kantor Desa Kuta, Kec. Pujut, Kab. Lombok Tengah" 
                                    required 
                                    class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-sm text-gray-900 shadow-xs transition"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('tujuan')" class="mt-1" />
                        </div>

                        <!-- Jadwal: Berangkat, Lama Hari, Kembali -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-2">
                            <!-- Tanggal Berangkat -->
                            <div>
                                <label for="tgl_berangkat" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Tanggal Berangkat <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    id="tgl_berangkat" 
                                    type="date" 
                                    name="tgl_berangkat" 
                                    value="{{ old('tgl_berangkat', $suratTugas->tgl_berangkat ? $suratTugas->tgl_berangkat->format('Y-m-d') : date('Y-m-d')) }}" 
                                    required 
                                    class="block w-full px-4 py-2.5 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-sm font-medium text-gray-800 shadow-xs transition"
                                />
                                <x-input-error :messages="$errors->get('tgl_berangkat')" class="mt-1" />
                            </div>

                            <!-- Lama Hari -->
                            <div>
                                <label for="lama_hari" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Lama Perjalanan (Hari) <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    id="lama_hari" 
                                    type="number" 
                                    min="1" 
                                    name="lama_hari" 
                                    value="{{ old('lama_hari', $lamaHariInitial) }}" 
                                    required 
                                    class="block w-full px-4 py-2.5 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-sm font-bold text-center text-gray-900 shadow-xs transition"
                                />
                                <!-- Quick chips -->
                                <div class="flex items-center gap-1 mt-1.5">
                                    <button type="button" onclick="setLamaHari(1)" class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition">1 Hari</button>
                                    <button type="button" onclick="setLamaHari(2)" class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition">2 Hari</button>
                                    <button type="button" onclick="setLamaHari(3)" class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition">3 Hari</button>
                                    <button type="button" onclick="setLamaHari(5)" class="px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition">5 Hari</button>
                                </div>
                            </div>

                            <!-- Tanggal Kembali -->
                            <div>
                                <label for="tgl_kembali" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Tanggal Kembali (Otomatis) <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    id="tgl_kembali" 
                                    type="date" 
                                    name="tgl_kembali" 
                                    value="{{ old('tgl_kembali', $suratTugas->tgl_kembali ? $suratTugas->tgl_kembali->format('Y-m-d') : date('Y-m-d')) }}" 
                                    readonly 
                                    required 
                                    class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-600 rounded-xl text-sm font-medium shadow-xs cursor-not-allowed"
                                />
                                <x-input-error :messages="$errors->get('tgl_kembali')" class="mt-1" />
                            </div>
                        </div>

                        <!-- LIVE SCHEDULE PREVIEW BANNER -->
                        <div id="schedule-banner" class="p-4 rounded-xl bg-gradient-to-r from-emerald-50 via-teal-50 to-blue-50 border border-emerald-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-800">Ringkasan Jadwal Perjalanan Dinas</div>
                                    <div class="text-xs text-gray-700 font-medium">
                                        <span id="preview-berangkat" class="font-bold text-gray-900">-</span>
                                        <span class="text-gray-400 mx-1.5">➔</span>
                                        <span id="preview-kembali" class="font-bold text-gray-900">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right self-end sm:self-auto">
                                <span id="preview-durasi" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    {{ $lamaHariInitial }} Hari Kalender
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- LANGKAH 3: PEMBEBANAN ANGGARAN & DIPA -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center">3</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Pembebanan Anggaran (MAK & DIPA)</h3>
                            <p class="text-xs text-gray-500">Alokasikan mata anggaran kegiatan untuk pembiayaan perjalanan dinas dan pelaporan pertanggungjawaban.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <!-- Dropdown MAK -->
                        <div>
                            <label for="pembebanan_anggaran_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Mata Anggaran Kegiatan (MAK) <span class="text-rose-500">*</span>
                            </label>
                            <select id="pembebanan_anggaran_id" name="pembebanan_anggaran_id" class="block w-full select2" required onchange="updateAnggaranPreview(this.value)">
                                <option value="">-- Ketik Kode MAK atau Uraian Kegiatan --</option>
                                @foreach($anggarans as $anggaran)
                                    <option value="{{ $anggaran->id }}" {{ old('pembebanan_anggaran_id', $suratTugas->pembebanan_anggaran_id) == $anggaran->id ? 'selected' : '' }}>
                                        {{ $anggaran->mak }} — {{ $anggaran->kegiatan_uraian }} ({{ $anggaran->akun_kode }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pembebanan_anggaran_id')" class="mt-1" />

                            <!-- Dynamic Preview Box Anggaran -->
                            <div id="preview-anggaran-box" class="mt-3 p-4 bg-gradient-to-r from-emerald-50/70 to-teal-50/50 rounded-xl border border-emerald-200/80 space-y-2">
                                <div class="flex items-center justify-between pb-2 border-b border-emerald-100">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-emerald-900">Struktur Anggaran DIPA:</span>
                                        <span class="font-mono text-xs font-black text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-300" id="anggaran-mak">{{ $suratTugas->anggaran->mak ?? '-' }}</span>
                                    </div>
                                    <span class="text-[11px] text-emerald-700 font-semibold" id="anggaran-akun">{{ $suratTugas->anggaran->akun_uraian ?? '-' }}</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-600">
                                    <div><strong class="text-gray-700">Kegiatan:</strong> <span id="anggaran-kegiatan">{{ $suratTugas->anggaran->kegiatan_uraian ?? '-' }}</span></div>
                                    <div><strong class="text-gray-700">Output:</strong> <span id="anggaran-output">{{ $suratTugas->anggaran->output_uraian ?? '-' }}</span></div>
                                    <div><strong class="text-gray-700">Komponen:</strong> <span id="anggaran-komponen">{{ $suratTugas->anggaran->komponen_uraian ?? '-' }}</span></div>
                                    <div><strong class="text-gray-700">Program:</strong> <span id="anggaran-program">{{ $suratTugas->anggaran->program_uraian ?? '-' }}</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Uraian Pembebanan -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="uraian_pembebanan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Uraian Pembebanan Anggaran <span class="text-rose-500">*</span>
                                </label>
                                <button type="button" onclick="fillDefaultUraian()" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Format Standar Satker
                                </button>
                            </div>
                            <input 
                                id="uraian_pembebanan" 
                                type="text" 
                                name="uraian_pembebanan" 
                                value="{{ old('uraian_pembebanan', $suratTugas->uraian_pembebanan) }}" 
                                placeholder="Contoh: Beban DIPA {{ $satkerName }} Tahun {{ $tahunName }}" 
                                required 
                                class="block w-full px-4 py-2.5 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-sm text-gray-900 shadow-xs transition"
                            />
                            <x-input-error :messages="$errors->get('uraian_pembebanan')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- LANGKAH 4: FORMAT SURAT PERJALANAN DINAS (SPD) & BIAYA RIIL -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7" x-data="{ spdTemplate: '{{ old('spd_template', $suratTugas->spd_template ?? 'template_2') }}' }">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">4</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Format Surat Perjalanan Dinas (SPD)</h3>
                            <p class="text-xs text-gray-500">Pilih format SPD untuk penugasan ini. Template 2 dipilih secara default dan otomatis menerbitkan SPD.</p>
                        </div>
                    </div>

                    <!-- Pilihan Template Radio Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <!-- Template 2 (Default) -->
                        <label 
                            class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition"
                            :class="spdTemplate === 'template_2' ? 'border-indigo-600 bg-indigo-50/40 shadow-xs ring-1 ring-indigo-500/20' : 'border-gray-200 hover:border-gray-300 bg-white'"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="radio" 
                                        name="spd_template" 
                                        value="template_2" 
                                        x-model="spdTemplate" 
                                        class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                    >
                                    <span class="text-xs font-extrabold text-gray-900 flex items-center gap-1.5">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-200">T2</span>
                                        Template 2 (Lampiran ST)
                                    </span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                    Otomatis Terbit & Default
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed pl-6">
                                Format 1 lembar ringkas (2 kolom x 2 baris) tanpa nomor SPD. SPD langsung otomatis diperbarui / diterbitkan bersama surat tugas.
                            </p>
                        </label>

                        <!-- Template 1 -->
                        <label 
                            class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition"
                            :class="spdTemplate === 'template_1' ? 'border-emerald-600 bg-emerald-50/40 shadow-xs ring-1 ring-emerald-500/20' : 'border-gray-200 hover:border-gray-300 bg-white'"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="radio" 
                                        name="spd_template" 
                                        value="template_1" 
                                        x-model="spdTemplate" 
                                        class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500"
                                    >
                                    <span class="text-xs font-extrabold text-gray-900 flex items-center gap-1.5">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">T1</span>
                                        Template 1 (Standar)
                                    </span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Standar LKPP
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed pl-6">
                                Format resmi 2 lembar standar LKPP/Kemenkeu. Parameter nomor SPD dan rincian lengkap dikelola via menu SPD.
                            </p>
                        </label>
                    </div>

                    <!-- Input Total Biaya Riil / Nilai DPR (Otomatis muncul jika Template 2) -->
                    <div x-show="spdTemplate === 'template_2'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="pt-4 border-t border-gray-100">
                        <div class="bg-gradient-to-br from-indigo-50/70 via-white to-blue-50/50 p-4 sm:p-5 rounded-xl border border-indigo-200/80 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="p-1.5 bg-indigo-600 text-white rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Rincian Otomatis SPD & Biaya Riil (DPR)</h4>
                                        <p class="text-[11px] text-gray-500">SPD otomatis aktif. Tanggal DPR otomatis disamakan dengan tanggal kembali surat tugas.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Input Nilai DPR -->
                                <div>
                                    <label for="nilai_dpr" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                        Total Biaya Riil / Nilai DPR (Rp)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 font-bold text-xs">
                                            Rp
                                        </div>
                                        <input 
                                            id="nilai_dpr" 
                                            type="text" 
                                            name="nilai_dpr" 
                                            value="{{ old('nilai_dpr', $suratTugas->spd && $suratTugas->spd->nilai_dpr !== null ? number_format($suratTugas->spd->nilai_dpr, 0, ',', '.') : '150.000') }}" 
                                            placeholder="0" 
                                            class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-sm font-semibold text-gray-900 shadow-xs transition"
                                            oninput="formatRupiahInput(this)"
                                        />
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1">Biaya transport lokal / pengeluaran riil untuk rincian DPR.</p>
                                    <x-input-error :messages="$errors->get('nilai_dpr')" class="mt-1" />
                                </div>

                                <!-- Pejabat Pembuat Komitmen (PPK) -->
                                <div>
                                    <label for="ppk_pegawai_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                        Pejabat Pembuat Komitmen (PPK)
                                    </label>
                                    <select id="ppk_pegawai_id" name="ppk_pegawai_id" class="block w-full px-3 py-2.5 bg-white border border-gray-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 rounded-xl text-xs text-gray-900 shadow-xs transition">
                                        @foreach($ppkPegawais as $ppk)
                                            <option value="{{ $ppk->id }}" {{ old('ppk_pegawai_id', $suratTugas->spd->ppk_pegawai_id ?? '') == $ppk->id ? 'selected' : '' }}>
                                                {{ $ppk->nama }} (NIP. {{ $ppk->nip }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-[11px] text-gray-400 mt-1">Pejabat pengesah Daftar Pengeluaran Riil (DPR).</p>
                                    <x-input-error :messages="$errors->get('ppk_pegawai_id')" class="mt-1" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- PENGATURAN KEPEMILIKAN USER (ADMIN & SUPERADMIN ONLY) -->
                <!-- ======================================================== -->
                @if(isset($users) && $users->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                        <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-gray-100">
                            <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 font-bold text-xs flex items-center justify-center">4</span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Kepemilikan Data Dokumen (Otorisasi Penginput)</h3>
                                <p class="text-xs text-gray-500">Khusus Administrator: Anda dapat mentransfer kepemilikan Surat Tugas ke akun pengguna lain.</p>
                            </div>
                        </div>

                        <div>
                            <label for="user_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Akun Pembuat / Pemilik Surat Tugas
                            </label>
                            <select id="user_id" name="user_id" class="block w-full select2">
                                <option value="">-- Kosongkan (Tanpa Pemilik Khusus / Satker Umum) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $suratTugas->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} (@ {{ $user->username }}) — Role: {{ ucfirst($user->role) }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1">Surat Tugas ini akan muncul pada menu filter data user tersebut.</p>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                        </div>
                    </div>
                @endif

                <!-- ======================================================== -->
                <!-- ACTION BUTTONS FOOTER -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Perubahan yang disimpan akan otomatis memperbarui data SPD dan cetak PDF terkait.</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('surat-tugas.index') }}" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition shadow-xs">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-orange-600/30 transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
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
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2) !important;
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
            background-color: #4f46e5 !important;
            color: #ffffff !important;
        }
    </style>

    <script>
        const pegawaiData = @json($pegawaiMap);
        const kepalaData = @json($kepalaMap);
        const anggaranData = @json($anggaranMap);
        const defaultUraianText = @json('Beban DIPA ' . $satkerName . ' Tahun ' . $tahunName);

        function formatIndonesianDate(dateString) {
            if (!dateString) return '-';
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const parts = dateString.split('-');
            if (parts.length !== 3) return dateString;
            const d = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
            if (isNaN(d.getTime())) return dateString;
            return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
        }

        function updateTanggalKembali() {
            let berangkat = $('#tgl_berangkat').val();
            let lama = parseInt($('#lama_hari').val());

            if (berangkat && !isNaN(lama) && lama >= 1) {
                const parts = berangkat.split('-');
                let date = new Date(parts[0], parseInt(parts[1]) - 1, parts[2]);
                date.setDate(date.getDate() + (lama - 1));
                
                let y = date.getFullYear();
                let m = String(date.getMonth() + 1).padStart(2, '0');
                let d = String(date.getDate()).padStart(2, '0');
                const kembaliVal = `${y}-${m}-${d}`;
                $('#tgl_kembali').val(kembaliVal);

                $('#schedule-banner').removeClass('hidden');
                $('#preview-berangkat').text(formatIndonesianDate(berangkat));
                $('#preview-kembali').text(formatIndonesianDate(kembaliVal));
                $('#preview-durasi').text(`${lama} Hari Kalender`);
            } else {
                $('#schedule-banner').addClass('hidden');
            }
        }

        function setLamaHari(days) {
            $('#lama_hari').val(days);
            updateTanggalKembali();
        }

        function fillDefaultUraian() {
            $('#uraian_pembebanan').val(defaultUraianText);
        }

        function updatePelaksanaPreview(id) {
            const p = pegawaiData[id];
            const box = $('#preview-pelaksana-box');
            if (p) {
                $('#pelaksana-nama').text(p.nama);
                $('#pelaksana-nip').text(p.nip);
                $('#pelaksana-jabatan').text(p.jabatan || 'Staf / Fungsional');
                $('#pelaksana-golongan').text(p.golongan || '-');
                $('#pelaksana-avatar').text(p.nama.charAt(0).toUpperCase());
                box.removeClass('hidden');
            } else {
                box.addClass('hidden');
            }
        }

        function updateKepalaPreview(id) {
            const k = kepalaData[id];
            const box = $('#preview-kepala-box');
            if (k) {
                $('#kepala-nama').text(k.nama);
                $('#kepala-nip').text(k.nip);
                let tagLabel = 'Kepala Satker';
                if (k.tags && Array.isArray(k.tags)) {
                    if (k.tags.includes('plt')) tagLabel = 'Plt. Kepala Satker';
                    else if (k.tags.includes('plh')) tagLabel = 'Plh. Kepala Satker';
                }
                $('#kepala-role-tag').text(`Peran: ${tagLabel}`);
                $('#kepala-avatar').text(k.nama.charAt(0).toUpperCase());
                box.removeClass('hidden');
            } else {
                box.addClass('hidden');
            }
        }

        function updateAnggaranPreview(id) {
            const a = anggaranData[id];
            const box = $('#preview-anggaran-box');
            if (a) {
                $('#anggaran-mak').text(a.mak);
                $('#anggaran-akun').text(a.akun);
                $('#anggaran-kegiatan').text(a.kegiatan);
                $('#anggaran-output').text(a.output);
                $('#anggaran-komponen').text(a.komponen);
                $('#anggaran-program').text(a.program);
                box.removeClass('hidden');
            } else {
                box.addClass('hidden');
            }
        }

        function formatRupiahInput(input) {
            let val = input.value.replace(/[^0-9]/g, '');
            if (val) {
                input.value = new Intl.NumberFormat('id-ID').format(val);
            } else {
                input.value = '';
            }
        }

        $(document).ready(function() {
            $('#pegawai_id').select2({
                placeholder: "-- Ketik Nama atau NIP Pegawai --",
                width: '100%'
            });
            $('#kepala_pegawai_id').select2({
                placeholder: "-- Pilih Pejabat Penandatangan --",
                width: '100%'
            });
            $('#pembebanan_anggaran_id').select2({
                placeholder: "-- Ketik Kode MAK atau Uraian Kegiatan --",
                width: '100%'
            });
            if ($('#user_id').length) {
                $('#user_id').select2({
                    placeholder: "-- Kosongkan (Tanpa Pemilik Khusus) --",
                    width: '100%'
                });
            }

            $('#pegawai_id').on('change', function() {
                updatePelaksanaPreview($(this).val());
            });
            $('#kepala_pegawai_id').on('change', function() {
                updateKepalaPreview($(this).val());
            });
            $('#pembebanan_anggaran_id').on('change', function() {
                updateAnggaranPreview($(this).val());
            });

            $('#tgl_berangkat, #lama_hari').on('change input', updateTanggalKembali);

            // Initial render
            updateTanggalKembali();
            if ($('#pegawai_id').val()) updatePelaksanaPreview($('#pegawai_id').val());
            if ($('#kepala_pegawai_id').val()) updateKepalaPreview($('#kepala_pegawai_id').val());
            if ($('#pembebanan_anggaran_id').val()) updateAnggaranPreview($('#pembebanan_anggaran_id').val());
        });
    </script>
</x-app-layout>
