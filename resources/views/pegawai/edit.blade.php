<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Data Pegawai') }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui biodata, status keaktifan, dan peran penandatangan untuk <strong>{{ $pegawai->nama }}</strong>.</p>
            </div>
            <a href="{{ route('pegawais.index') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <form method="POST" action="{{ route('pegawais.update', $pegawai->id) }}" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Section: Informasi Utama -->
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-blue-600 mr-2"></span>
                            Identitas Pegawai
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- NIP -->
                            <div>
                                <x-input-label for="nip" :value="__('NIP (Nomor Induk Pegawai)')" class="font-semibold" />
                                <x-text-input id="nip" class="block mt-1 w-full text-sm font-mono" type="text" name="nip" :value="old('nip', $pegawai->nip)" required autofocus />
                                <x-input-error :messages="$errors->get('nip')" class="mt-1" />
                            </div>

                            <!-- Nama -->
                            <div>
                                <x-input-label for="nama" :value="__('Nama Lengkap')" class="font-semibold" />
                                <x-text-input id="nama" class="block mt-1 w-full text-sm" type="text" name="nama" :value="old('nama', $pegawai->nama)" required />
                                <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Jabatan -->
                        <div class="mt-4">
                            <x-input-label for="jabatan" :value="__('Jabatan')" class="font-semibold" />
                            <x-text-input id="jabatan" class="block mt-1 w-full text-sm" type="text" name="jabatan" :value="old('jabatan', $pegawai->jabatan)" placeholder="Contoh: Statistisi Ahli Muda / Kepala Subbagian Tata Usaha" />
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-1" />
                        </div>

                        <!-- Golongan & Pangkat -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="golongan" :value="__('Golongan')" class="font-semibold" />
                                <x-text-input id="golongan" class="block mt-1 w-full text-sm" type="text" name="golongan" :value="old('golongan', $pegawai->golongan)" placeholder="Contoh: III/c atau IV/a" />
                                <x-input-error :messages="$errors->get('golongan')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="pangkat" :value="__('Pangkat')" class="font-semibold" />
                                <x-text-input id="pangkat" class="block mt-1 w-full text-sm" type="text" name="pangkat" :value="old('pangkat', $pegawai->pangkat)" placeholder="Contoh: Penata / Pembina" />
                                <x-input-error :messages="$errors->get('pangkat')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Section: Status Keaktifan Pegawai -->
                    <div class="pt-2">
                        <div class="border-b border-gray-200 pb-2 mb-4">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-600 mr-2"></span>
                                Status Keaktifan Pegawai
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Jika pegawai telah mutasi atau pensiun, cukup ubah status tag-nya di bawah. Data pegawai tetap tersimpan agar <strong>tidak merusak data laporan atau Surat Tugas masa lalu</strong> yang pernah dibuat.
                            </p>
                        </div>

                        @php
                            $currentStatusKey = 'aktif';
                            if ($pegawai->hasTag('pensiun')) {
                                $currentStatusKey = 'pensiun';
                            } elseif ($pegawai->hasTag('mutasi')) {
                                $currentStatusKey = 'mutasi';
                            } elseif ($pegawai->hasTag('tidak_aktif') || ! $pegawai->is_active) {
                                $currentStatusKey = 'tidak_aktif';
                            }
                            $selectedStatus = old('status_keaktifan', $currentStatusKey);
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Aktif -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-emerald-50/50 {{ $selectedStatus === 'aktif' ? 'border-emerald-400 bg-emerald-50/40 ring-1 ring-emerald-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="radio" name="status_keaktifan" value="aktif" class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500" {{ $selectedStatus === 'aktif' ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span>
                                        Aktif
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Aktif bekerja di satker</p>
                                </div>
                            </label>

                            <!-- Tidak Aktif -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-gray-50 {{ $selectedStatus === 'tidak_aktif' ? 'border-gray-400 bg-gray-100/60 ring-1 ring-gray-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="radio" name="status_keaktifan" value="tidak_aktif" class="h-4 w-4 border-gray-300 text-gray-600 focus:ring-gray-500" {{ $selectedStatus === 'tidak_aktif' ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1"></span>
                                        Tidak Aktif
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Non-aktif secara umum</p>
                                </div>
                            </label>

                            <!-- Mutasi -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-amber-50/50 {{ $selectedStatus === 'mutasi' ? 'border-amber-400 bg-amber-50/40 ring-1 ring-amber-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="radio" name="status_keaktifan" value="mutasi" class="h-4 w-4 border-gray-300 text-amber-600 focus:ring-amber-500" {{ $selectedStatus === 'mutasi' ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1"></span>
                                        Mutasi
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Pindah satker / instansi</p>
                                </div>
                            </label>

                            <!-- Pensiun -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-slate-50 {{ $selectedStatus === 'pensiun' ? 'border-slate-400 bg-slate-100/60 ring-1 ring-slate-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="radio" name="status_keaktifan" value="pensiun" class="h-4 w-4 border-gray-300 text-slate-600 focus:ring-slate-500" {{ $selectedStatus === 'pensiun' ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-1"></span>
                                        Pensiun
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Purna tugas / pensiun</p>
                                </div>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('status_keaktifan')" class="mt-2" />
                    </div>

                    <!-- Section: Tag Peran & Penandatangan -->
                    <div class="pt-2">
                        <div class="border-b border-gray-200 pb-2 mb-4">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-purple-600 mr-2"></span>
                                Tag Peran Penandatangan (Opsional)
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Pilih peran penandatangan jika pegawai ini berwenang menandatangani dokumen.
                                <strong>Kepala, Plt, Plh</strong> akan muncul pada pilihan TTD Kepala Satker (ST).
                                <strong>PPK</strong> akan muncul pada pilihan TTD PPK (SPD & DPR).
                            </p>
                        </div>

                        @php
                            $selectedTags = old('tags', $pegawai->tags ?? []);
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Kepala -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-purple-50/50 {{ in_array('kepala', $selectedTags) ? 'border-purple-400 bg-purple-50/40 ring-1 ring-purple-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="tags[]" value="kepala" class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ in_array('kepala', $selectedTags) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                        Kepala
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Kepala Satker (Penandatangan ST)</p>
                                </div>
                            </label>

                            <!-- Plt -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-amber-50/50 {{ in_array('plt', $selectedTags) ? 'border-amber-400 bg-amber-50/40 ring-1 ring-amber-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="tags[]" value="plt" class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500" {{ in_array('plt', $selectedTags) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        Plt.
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Plt. Kepala (Penandatangan ST)</p>
                                </div>
                            </label>

                            <!-- Plh -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-orange-50/50 {{ in_array('plh', $selectedTags) ? 'border-orange-400 bg-orange-50/40 ring-1 ring-orange-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="tags[]" value="plh" class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500" {{ in_array('plh', $selectedTags) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                        Plh.
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Plh. Kepala (Penandatangan ST)</p>
                                </div>
                            </label>

                            <!-- PPK -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-blue-50/50 {{ in_array('ppk', $selectedTags) ? 'border-blue-400 bg-blue-50/40 ring-1 ring-blue-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="tags[]" value="ppk" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ in_array('ppk', $selectedTags) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                        PPK
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Pejabat Pembuat Komitmen (SPD & DPR)</p>
                                </div>
                            </label>

                            <!-- Bendahara -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-emerald-50/50 {{ in_array('bendahara', $selectedTags) ? 'border-emerald-400 bg-emerald-50/40 ring-1 ring-emerald-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="tags[]" value="bendahara" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" {{ in_array('bendahara', $selectedTags) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Bendahara
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Bendahara Pengeluaran</p>
                                </div>
                            </label>

                            <!-- Kasubbag -->
                            <label class="relative flex items-start p-3 rounded-lg border transition cursor-pointer hover:bg-rose-50/50 {{ in_array('kasubbag', $selectedTags) ? 'border-rose-400 bg-rose-50/40 ring-1 ring-rose-300' : 'border-gray-200 bg-white' }}">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="tags[]" value="kasubbag" class="h-4 w-4 rounded border-gray-300 text-rose-600 focus:ring-rose-500" {{ in_array('kasubbag', $selectedTags) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                        Kasubbag
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Kepala Subbagian Umum / TU</p>
                                </div>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('pegawais.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm transition">
                            <svg xmlns="http://www.w3.org/2003/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Simpan Perubahan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
