<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Hanya Laporan (Generate AI)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4 text-gray-600">Fitur ini memungkinkan Anda membuat laporan lepas tanpa mengikat ke database Surat Tugas & SPD. Lengkapi parameter di bawah ini untuk menggenerate laporan menggunakan AI.</p>

                    <form id="laporan-form" method="POST" action="{{ route('hanya-laporan.update', $hanyaLaporan->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="nama_pegawai" :value="__('Nama Pegawai')" />
                                <select id="nama_pegawai" name="nama_pegawai" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm select2" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    <!-- Jika nama_pegawai lama tidak ada di tabel pegawais -->
                                    @php $namaFound = false; @endphp
                                    @foreach($pegawais as $p)
                                        <option value="{{ $p->nama }}" data-nip="{{ $p->nip }}" {{ old('nama_pegawai', $hanyaLaporan->nama_pegawai) == $p->nama ? 'selected' : '' }}>
                                            {{ $p->nama }}
                                        </option>
                                        @if(old('nama_pegawai', $hanyaLaporan->nama_pegawai) == $p->nama)
                                            @php $namaFound = true; @endphp
                                        @endif
                                    @endforeach
                                    @if(!$namaFound && $hanyaLaporan->nama_pegawai)
                                        <option value="{{ $hanyaLaporan->nama_pegawai }}" data-nip="{{ $hanyaLaporan->nip_pegawai }}" selected>
                                            {{ $hanyaLaporan->nama_pegawai }} (Data lama)
                                        </option>
                                    @endif
                                </select>
                                <x-input-error :messages="$errors->get('nama_pegawai')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nip_pegawai" :value="__('NIP Pegawai')" />
                                <x-text-input id="nip_pegawai" class="block mt-1 w-full bg-gray-100" type="text" name="nip_pegawai" :value="old('nip_pegawai', $hanyaLaporan->nip_pegawai)" required readonly />
                                <x-input-error :messages="$errors->get('nip_pegawai')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="nomor_st" :value="__('Nomor Surat Tugas')" />
                                <x-text-input id="nomor_st" class="block mt-1 w-full" type="text" name="nomor_st" :value="old('nomor_st', $hanyaLaporan->nomor_st)" required />
                                <x-input-error :messages="$errors->get('nomor_st')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nomor_spd" :value="__('Nomor SPD (Opsional)')" />
                                <x-text-input id="nomor_spd" class="block mt-1 w-full" type="text" name="nomor_spd" :value="old('nomor_spd', $hanyaLaporan->nomor_spd)" />
                                <x-input-error :messages="$errors->get('nomor_spd')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="tgl_laporan" :value="__('Tanggal Laporan Dibuat')" />
                                <x-text-input id="tgl_laporan" class="block mt-1 w-full" type="date" name="tgl_laporan" :value="old('tgl_laporan', $hanyaLaporan->tgl_laporan->format('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="tgl_perjalanan" :value="__('Tanggal Perjalanan (Pelaksanaan)')" />
                                <x-text-input id="tgl_perjalanan" class="block mt-1 w-full" type="date" name="tgl_perjalanan" :value="old('tgl_perjalanan', $hanyaLaporan->tgl_perjalanan->format('Y-m-d'))" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="lokasi_perjalanan" :value="__('Lokasi (Instansi / Desa / Tempat)')" />
                                <x-text-input id="lokasi_perjalanan" class="block mt-1 w-full" type="text" name="lokasi_perjalanan" :value="old('lokasi_perjalanan', $hanyaLaporan->lokasi_perjalanan)" required />
                            </div>
                            <div>
                                <x-input-label for="tujuan_perjalanan" :value="__('Tujuan Perjalanan (Singkat)')" />
                                <x-text-input id="tujuan_perjalanan" class="block mt-1 w-full" type="text" name="tujuan_perjalanan" :value="old('tujuan_perjalanan', $hanyaLaporan->tujuan_perjalanan)" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="pegawai_ditemui" :value="__('Pihak / Pegawai yang Ditemui')" />
                                <x-text-input id="pegawai_ditemui" class="block mt-1 w-full" type="text" name="pegawai_ditemui" :value="old('pegawai_ditemui', $hanyaLaporan->pegawai_ditemui)" required />
                            </div>
                            <div>
                                <x-input-label for="kategori" :value="__('Kategori Perjalanan')" />
                                @php
                                    $standardKategori = ['Translok Koordinasi', 'Pengawasan / Supervisi Lapangan', 'Verifikasi Data', 'Pendataan Lapangan'];
                                    $isCustom = !in_array(old('kategori', $hanyaLaporan->kategori), $standardKategori) && $hanyaLaporan->kategori;
                                    $selectedValue = $isCustom ? 'Custom' : old('kategori', $hanyaLaporan->kategori);
                                @endphp
                                <select id="kategori" name="kategori" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required onchange="toggleCustomKategori()">
                                    <option value="Translok Koordinasi" {{ $selectedValue == 'Translok Koordinasi' ? 'selected' : '' }}>Translok Koordinasi</option>
                                    <option value="Pengawasan / Supervisi Lapangan" {{ $selectedValue == 'Pengawasan / Supervisi Lapangan' ? 'selected' : '' }}>Pengawasan / Supervisi Lapangan</option>
                                    <option value="Verifikasi Data" {{ $selectedValue == 'Verifikasi Data' ? 'selected' : '' }}>Verifikasi Data</option>
                                    <option value="Pendataan Lapangan" {{ $selectedValue == 'Pendataan Lapangan' ? 'selected' : '' }}>Pendataan Lapangan</option>
                                    <option value="Custom" {{ $selectedValue == 'Custom' ? 'selected' : '' }}>Custom (Isi Sendiri)</option>
                                </select>
                                <input type="text" id="kategori_custom" name="kategori_custom" class="block mt-2 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm {{ $isCustom ? '' : 'hidden' }}" placeholder="Masukkan kategori perjalanan" value="{{ $isCustom ? old('kategori_custom', $hanyaLaporan->kategori) : '' }}" {{ $isCustom ? 'required' : '' }}>
                            </div>
                        </div>

                        <!-- Kendala -->
                        <div class="mb-4">
                            <x-input-label for="kendala_ditemui" :value="__('Kendala / Masalah yang Ditemukan (Opsional)')" />
                            <textarea id="kendala_ditemui" name="kendala_ditemui" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" rows="2" placeholder="Contoh: Responden tidak ada di tempat, cuaca buruk, atau biarkan kosong jika lancar.">{{ old('kendala_ditemui', $hanyaLaporan->kendala_ditemui) }}</textarea>
                            <x-input-error :messages="$errors->get('kendala_ditemui')" class="mt-2" />
                        </div>

                        <!-- Upload Dokumentasi -->
                        <div class="mb-4 p-4 border border-gray-200 rounded bg-gray-50">
                            <x-input-label for="fotos" :value="__('Upload Tambahan Foto Dokumentasi')" />
                            <input id="fotos" name="fotos[]" type="file" multiple accept="image/*" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" onchange="previewImages(event)" />
                            <p class="mt-1 text-xs text-gray-500">Abaikan jika tidak ingin menambah foto. Anda dapat memblok beberapa foto sekaligus saat memilih.</p>
                            
                            <div id="image-preview-container" class="mt-4 flex flex-wrap gap-4"></div>

                            @if($hanyaLaporan->dokumentasi && $hanyaLaporan->dokumentasi->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm font-semibold mb-2">Foto Dokumentasi Tersimpan (Centang untuk Hapus):</p>
                                <div class="flex flex-wrap gap-4">
                                    @foreach($hanyaLaporan->dokumentasi as $dok)
                                        <div class="relative w-32 h-32 border rounded overflow-hidden">
                                            <img src="{{ asset($dok->file_path) }}" class="object-cover w-full h-full">
                                            <div class="absolute top-0 right-0 bg-white bg-opacity-75 p-1 rounded-bl">
                                                <input type="checkbox" name="delete_fotos[]" value="{{ $dok->id }}" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mb-4 flex items-center">
                            <input type="checkbox" id="is_8_jam" name="is_8_jam" value="1" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <label for="is_8_jam" class="ml-2 block text-sm text-gray-900">
                                Perjadin 8 Jam (Format Tabel Waktu & Kegiatan 08.00 - 17.00)
                            </label>
                        </div>
                        
                        <div class="mb-4">
                            <button type="button" id="btn-generate" onclick="generateAI()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <span>Generate dengan AI ✨</span>
                            </button>
                            <span id="loading-indicator" class="ml-3 text-sm text-gray-500 hidden">Sedang menyusun narasi... mohon tunggu.</span>
                        </div>

                        <!-- Hasil AI -->
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-md" id="hasil-container" style="{{ old('hasil_perjalanan', $hanyaLaporan->hasil_perjalanan) ? '' : 'display:none;' }}">
                            <x-input-label for="hasil_perjalanan" :value="__('Narasi Laporan (Silakan Review & Edit)')" />
                            <textarea id="hasil_perjalanan" name="hasil_perjalanan" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" rows="10">{{ old('hasil_perjalanan', $hanyaLaporan->hasil_perjalanan) }}</textarea>
                            <x-input-error :messages="$errors->get('hasil_perjalanan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md mr-4" href="{{ route('hanya-laporan.index') }}">
                                Batal
                            </a>
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Finalisasi & Simpan Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function generateAI() {
            const btn = document.getElementById('btn-generate');
            const indicator = document.getElementById('loading-indicator');
            const hasilContainer = document.getElementById('hasil-container');
            const hasilTextarea = document.getElementById('hasil_perjalanan');
            
            // Validate form
            const form = document.getElementById('laporan-form');
            if(!form.checkValidity()) {
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
                        alert('Validasi form gagal. Mohon periksa kembali isian Anda.');
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
                
            } catch (error) {
                alert(error.message || 'Terjadi kesalahan saat menghubungi server AI.');
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-50');
                indicator.classList.add('hidden');
            }
        }

        function previewImages(event) {
            const container = document.getElementById('image-preview-container');
            container.innerHTML = '';

            const files = event.target.files;
            if (files) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const imgDiv = document.createElement('div');
                            imgDiv.className = 'w-24 h-24 overflow-hidden rounded border border-gray-300';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'object-cover w-full h-full';
                            
                            imgDiv.appendChild(img);
                            container.appendChild(imgDiv);
                        }
                        reader.readAsDataURL(file);
                    }
                }
            }
        }
        
        // Custom Kategori Logic
        function toggleCustomKategori() {
            const select = document.getElementById('kategori');
            const customInput = document.getElementById('kategori_custom');
            if (select.value === 'Custom') {
                customInput.classList.remove('hidden');
                customInput.setAttribute('required', 'required');
            } else {
                customInput.classList.add('hidden');
                customInput.removeAttribute('required');
            }
        }

        // Initialize Select2 and NIP autofill
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined') {
                $('#nama_pegawai').select2({
                    placeholder: "-- Pilih Pegawai --",
                    width: '100%'
                });

                $('#nama_pegawai').on('change', function() {
                    const selectedOption = $(this).find('option:selected');
                    const nip = selectedOption.data('nip');
                    if (nip) {
                        $('#nip_pegawai').val(nip);
                    } else {
                        $('#nip_pegawai').val('');
                    }
                });
            }
        });
    </script>
    
    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</x-app-layout>

