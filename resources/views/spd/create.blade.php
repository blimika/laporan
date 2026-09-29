<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penerbitan Surat Perjalanan Dinas (SPD)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-gray-50 rounded border">
                        <h4 class="font-bold mb-2">Detail Surat Tugas</h4>
                        <p><strong>No. ST:</strong> {{ $suratTugas->nomor_surat }}</p>
                        <p><strong>Pelaksana:</strong> {{ $suratTugas->pegawai->nama }} ({{ $suratTugas->pegawai->nip }})</p>
                        <p><strong>Tujuan:</strong> {{ $suratTugas->tujuan }}</p>
                    </div>

                    <form method="POST" action="{{ route('spd.store', $suratTugas->id) }}">
                        @csrf

                        <!-- Nomor SPD -->
                        <div class="mb-4">
                            <x-input-label for="nomor_spd" :value="__('Nomor SPD')" />
                            <x-text-input id="nomor_spd" class="block mt-1 w-full" type="text" name="nomor_spd" :value="old('nomor_spd')" required autofocus />
                            <x-input-error :messages="$errors->get('nomor_spd')" class="mt-2" />
                        </div>

                        <!-- PPK Pegawai -->
                        <div class="mb-4">
                            <x-input-label for="ppk_pegawai_id" :value="__('Pejabat Pembuat Komitmen (PPK)')" />
                            <select id="ppk_pegawai_id" name="ppk_pegawai_id" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required>
                                <option value="">-- Pilih PPK --</option>
                                @foreach($pegawais as $pegawai)
                                    <option value="{{ $pegawai->id }}">{{ $pegawai->nama }} ({{ $pegawai->nip }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('ppk_pegawai_id')" class="mt-2" />
                        </div>

                        <!-- Kendaraan -->
                        <div class="mb-4">
                            <x-input-label for="kendaraan" :value="__('Alat Angkutan yang Digunakan')" />
                            <select id="kendaraan" name="kendaraan" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required>
                                <option value="Kendaraan Dinas">Kendaraan Dinas</option>
                                <option value="Kendaraan Umum">Kendaraan Umum</option>
                                <option value="Kendaraan Pribadi">Kendaraan Pribadi</option>
                            </select>
                            <x-input-error :messages="$errors->get('kendaraan')" class="mt-2" />
                        </div>
                        
                        <hr class="my-6">
                        <h4 class="font-bold mb-4">Data Daftar Pengeluaran Riil (DPR)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Tgl DPR -->
                            <div>
                                <x-input-label for="tgl_dpr" :value="__('Tanggal Laporan DPR')" />
                                <x-text-input id="tgl_dpr" class="block mt-1 w-full" type="date" name="tgl_dpr" :value="old('tgl_dpr')" />
                                <x-input-error :messages="$errors->get('tgl_dpr')" class="mt-2" />
                            </div>

                            <!-- Nilai DPR -->
                            <div>
                                <x-input-label for="nilai_dpr" :value="__('Total Biaya Riil (Rp)')" />
                                <x-text-input id="nilai_dpr" class="block mt-1 w-full" type="number" step="0.01" name="nilai_dpr" :value="old('nilai_dpr')" />
                                <x-input-error :messages="$errors->get('nilai_dpr')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('surat-tugas.index') }}">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Terbitkan SPD & DPR') }}
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
            $('#ppk_pegawai_id').select2({
                placeholder: "-- Pilih PPK --",
                width: '100%'
            });
        });
    </script>
</x-app-layout>
