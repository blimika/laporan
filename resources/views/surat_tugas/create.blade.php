<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Surat Tugas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('surat-tugas.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nomor Surat -->
                            <div>
                                <x-input-label for="nomor_surat" :value="__('Nomor Surat Tugas')" />
                                <x-text-input id="nomor_surat" class="block mt-1 w-full" type="text" name="nomor_surat" :value="old('nomor_surat')" required />
                                <x-input-error :messages="$errors->get('nomor_surat')" class="mt-2" />
                            </div>

                            <!-- Tanggal Surat -->
                            <div>
                                <x-input-label for="tgl_surat" :value="__('Tanggal Surat')" />
                                <x-text-input id="tgl_surat" class="block mt-1 w-full" type="date" name="tgl_surat" :value="old('tgl_surat')" required />
                                <x-input-error :messages="$errors->get('tgl_surat')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Pegawai Pelaksana -->
                            <div>
                                <x-input-label for="pegawai_id" :value="__('Pegawai Pelaksana (Yang Ditugaskan)')" />
                                <select id="pegawai_id" name="pegawai_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama }} ({{ $pegawai->nip }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('pegawai_id')" class="mt-2" />
                            </div>

                            <!-- Kepala Pegawai -->
                            <div>
                                <x-input-label for="kepala_pegawai_id" :value="__('Penandatangan ST (Kepala Satker)')" />
                                <select id="kepala_pegawai_id" name="kepala_pegawai_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Kepala Satker --</option>
                                    @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama }} ({{ $pegawai->nip }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('kepala_pegawai_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <!-- Tugas -->
                            <x-input-label for="tugas" :value="__('Maksud / Tujuan Tugas')" />
                            <textarea id="tugas" name="tugas" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required>{{ old('tugas') }}</textarea>
                            <x-input-error :messages="$errors->get('tugas')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <!-- Tujuan -->
                            <x-input-label for="tujuan" :value="__('Tempat Tujuan')" />
                            <x-text-input id="tujuan" class="block mt-1 w-full" type="text" name="tujuan" :value="old('tujuan')" required />
                            <x-input-error :messages="$errors->get('tujuan')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Tanggal Berangkat -->
                            <div>
                                <x-input-label for="tgl_berangkat" :value="__('Tanggal Berangkat')" />
                                <x-text-input id="tgl_berangkat" class="block mt-1 w-full" type="date" name="tgl_berangkat" :value="old('tgl_berangkat')" required />
                                <x-input-error :messages="$errors->get('tgl_berangkat')" class="mt-2" />
                            </div>

                            <!-- Tanggal Kembali -->
                            <div>
                                <x-input-label for="tgl_kembali" :value="__('Tanggal Kembali')" />
                                <x-text-input id="tgl_kembali" class="block mt-1 w-full" type="date" name="tgl_kembali" :value="old('tgl_kembali')" required />
                                <x-input-error :messages="$errors->get('tgl_kembali')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <!-- Pembebanan Anggaran -->
                            <x-input-label for="pembebanan_anggaran_id" :value="__('Pembebanan Anggaran (MAK)')" />
                            <select id="pembebanan_anggaran_id" name="pembebanan_anggaran_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Anggaran --</option>
                                @foreach($anggarans as $anggaran)
                                    <option value="{{ $anggaran->id }}">{{ $anggaran->mak }} - {{ $anggaran->kegiatan_uraian }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pembebanan_anggaran_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <!-- Uraian Pembebanan -->
                            <x-input-label for="uraian_pembebanan" :value="__('Uraian Pembebanan')" />
                            <x-text-input id="uraian_pembebanan" class="block mt-1 w-full" type="text" name="uraian_pembebanan" :value="old('uraian_pembebanan')" placeholder="Contoh: Beban DIPA BPS Provinsi Bali Tahun 2026" required />
                            <x-input-error :messages="$errors->get('uraian_pembebanan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('surat-tugas.index') }}">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Simpan Surat Tugas') }}
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
        });
    </script>
</x-app-layout>
