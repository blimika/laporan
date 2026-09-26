<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Surat Tugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('surat-tugas.update', $suratTugas) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nomor Surat -->
                            <div>
                                <x-input-label for="nomor_surat" :value="__('Nomor Surat Tugas')" />
                                <x-text-input id="nomor_surat" class="block mt-1 w-full" type="text" name="nomor_surat" :value="old('nomor_surat', $suratTugas->nomor_surat)" required />
                                <x-input-error :messages="$errors->get('nomor_surat')" class="mt-2" />
                            </div>

                            <!-- Tanggal Surat -->
                            <div>
                                <x-input-label for="tgl_surat" :value="__('Tanggal Surat')" />
                                <x-text-input id="tgl_surat" class="block mt-1 w-full" type="date" name="tgl_surat" :value="old('tgl_surat', $suratTugas->tgl_surat->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('tgl_surat')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Pegawai Pelaksana -->
                            <div>
                                <x-input-label for="pegawai_id" :value="__('Pegawai Pelaksana (Yang Ditugaskan)')" />
                                <select id="pegawai_id" name="pegawai_id" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}" {{ old('pegawai_id', $suratTugas->pegawai_id) == $pegawai->id ? 'selected' : '' }}>{{ $pegawai->nama }} ({{ $pegawai->nip }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('pegawai_id')" class="mt-2" />
                            </div>

                            <!-- Kepala Pegawai -->
                            <div>
                                <x-input-label for="kepala_pegawai_id" :value="__('Penandatangan ST (Kepala Satker)')" />
                                <select id="kepala_pegawai_id" name="kepala_pegawai_id" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Kepala Satker --</option>
                                    @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}" {{ old('kepala_pegawai_id', $suratTugas->kepala_pegawai_id) == $pegawai->id ? 'selected' : '' }}>{{ $pegawai->nama }} ({{ $pegawai->nip }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('kepala_pegawai_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <!-- Tugas -->
                            <x-input-label for="tugas" :value="__('Maksud / Tujuan Tugas')" />
                            <textarea id="tugas" name="tugas" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" rows="3" required>{{ old('tugas', $suratTugas->tugas) }}</textarea>
                            <x-input-error :messages="$errors->get('tugas')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <!-- Tujuan -->
                            <x-input-label for="tujuan" :value="__('Tempat Tujuan')" />
                            <x-text-input id="tujuan" class="block mt-1 w-full" type="text" name="tujuan" :value="old('tujuan', $suratTugas->tujuan)" required />
                            <x-input-error :messages="$errors->get('tujuan')" class="mt-2" />
                        </div>

                        @php
                            $satker = \App\Models\Satker::find(session('satker_id'));
                            $tahun = \App\Models\Tahun::find(session('tahun_id'));
                            $satkerName = $satker ? $satker->nama : 'BPS Instansi';
                            $tahunName = $tahun ? $tahun->tahun : date('Y');
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <!-- Tanggal Berangkat -->
                            <div>
                                <x-input-label for="tgl_berangkat" :value="__('Tanggal Berangkat')" />
                                <x-text-input id="tgl_berangkat" class="block mt-1 w-full" type="date" name="tgl_berangkat" :value="old('tgl_berangkat', $suratTugas->tgl_berangkat->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('tgl_berangkat')" class="mt-2" />
                            </div>

                            <!-- Lama Hari -->
                            <div>
                                <x-input-label for="lama_hari" :value="__('Lama Perjalanan (Hari)')" />
                                <x-text-input id="lama_hari" class="block mt-1 w-full" type="number" min="1" name="lama_hari" value="{{ old('lama_hari', \Carbon\Carbon::parse($suratTugas->tgl_berangkat)->diffInDays(\Carbon\Carbon::parse($suratTugas->tgl_kembali)) + 1) }}" required />
                            </div>

                            <!-- Tanggal Kembali -->
                            <div>
                                <x-input-label for="tgl_kembali" :value="__('Tanggal Kembali')" />
                                <x-text-input id="tgl_kembali" class="block mt-1 w-full bg-gray-100" type="date" name="tgl_kembali" :value="old('tgl_kembali', $suratTugas->tgl_kembali->format('Y-m-d'))" readonly required />
                                <x-input-error :messages="$errors->get('tgl_kembali')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <!-- Pembebanan Anggaran -->
                            <x-input-label for="pembebanan_anggaran_id" :value="__('Pembebanan Anggaran (MAK)')" />
                            <select id="pembebanan_anggaran_id" name="pembebanan_anggaran_id" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required>
                                <option value="">-- Pilih Anggaran --</option>
                                @foreach($anggarans as $anggaran)
                                    <option value="{{ $anggaran->id }}" {{ old('pembebanan_anggaran_id', $suratTugas->pembebanan_anggaran_id) == $anggaran->id ? 'selected' : '' }}>{{ $anggaran->mak }} - {{ $anggaran->kegiatan_uraian }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pembebanan_anggaran_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <!-- Uraian Pembebanan -->
                            <x-input-label for="uraian_pembebanan" :value="__('Uraian Pembebanan')" />
                            <x-text-input id="uraian_pembebanan" class="block mt-1 w-full" type="text" name="uraian_pembebanan" :value="old('uraian_pembebanan', $suratTugas->uraian_pembebanan)" placeholder="Contoh: Beban DIPA {{ $satkerName }} Tahun {{ $tahunName }}" required />
                            <x-input-error :messages="$errors->get('uraian_pembebanan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('surat-tugas.index') }}">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Update Surat Tugas') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#pegawai_id').select2({
                placeholder: "-- Pilih Pegawai --",
                width: '100%'
            });
            $('#kepala_pegawai_id').select2({
                placeholder: "-- Pilih Kepala Satker --",
                width: '100%'
            });
            $('#pembebanan_anggaran_id').select2({
                placeholder: "-- Pilih Anggaran --",
                width: '100%'
            });

            // Date calculation logic
            function updateTanggalKembali() {
                let berangkat = $('#tgl_berangkat').val();
                let lama = parseInt($('#lama_hari').val());

                if (berangkat && !isNaN(lama) && lama >= 1) {
                    let date = new Date(berangkat);
                    // Add (lama - 1) days
                    date.setDate(date.getDate() + (lama - 1));
                    
                    // Format back to YYYY-MM-DD
                    let y = date.getFullYear();
                    let m = String(date.getMonth() + 1).padStart(2, '0');
                    let d = String(date.getDate()).padStart(2, '0');
                    $('#tgl_kembali').val(${y}--);
                }
            }

            $('#tgl_berangkat, #lama_hari').on('change input', updateTanggalKembali);
        });
    </script>
</x-app-layout>
