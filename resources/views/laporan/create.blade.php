<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Laporan Perjalanan (Generate AI)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4 text-gray-600">Lengkapi parameter di bawah ini untuk menggenerate draft Laporan menggunakan AI (Gemini).</p>
                    
                    @php
                        $stJson = $suratTugas->mapWithKeys(function ($item) {
                            return [$item->id => [
                                'tgl_kembali' => $item->tgl_kembali->format('Y-m-d'),
                                'tgl_berangkat' => $item->tgl_berangkat->format('Y-m-d'),
                                'tujuan' => $item->tujuan,
                                'tugas' => $item->tugas,
                            ]];
                        })->toJson();
                    @endphp

                    <form id="laporan-form" method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Surat Tugas -->
                        <div class="mb-4">
                            <x-input-label for="surat_tugas_id" :value="__('Pilih Surat Tugas (Belum ada Laporan)')" />
                            <select id="surat_tugas_id" name="surat_tugas_id" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required onchange="fillSuratTugas(this)">
                                <option value="">-- Surat Tugas --</option>
                                @foreach($suratTugas as $st)
                                    <option value="{{ $st->id }}">{{ $st->nomor_surat }} - {{ $st->pegawai->nama }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('surat_tugas_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="tgl_laporan" :value="__('Tanggal Laporan Dibuat')" />
                                <x-text-input id="tgl_laporan" class="block mt-1 w-full" type="date" name="tgl_laporan" :value="old('tgl_laporan', date('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="tgl_perjalanan" :value="__('Tanggal Perjalanan (Pelaksanaan)')" />
                                <x-text-input id="tgl_perjalanan" class="block mt-1 w-full" type="date" name="tgl_perjalanan" :value="old('tgl_perjalanan')" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="lokasi_perjalanan" :value="__('Lokasi (Instansi / Desa / Tempat)')" />
                                <x-text-input id="lokasi_perjalanan" class="block mt-1 w-full" type="text" name="lokasi_perjalanan" :value="old('lokasi_perjalanan')" required />
                            </div>
                            <div>
                                <x-input-label for="tujuan_perjalanan" :value="__('Tujuan Perjalanan (Singkat)')" />
                                <x-text-input id="tujuan_perjalanan" class="block mt-1 w-full" type="text" name="tujuan_perjalanan" :value="old('tujuan_perjalanan')" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="pegawai_ditemui" :value="__('Pihak / Pegawai yang Ditemui')" />
                                <x-text-input id="pegawai_ditemui" class="block mt-1 w-full" type="text" name="pegawai_ditemui" :value="old('pegawai_ditemui')" required />
                            </div>
                            <div>
                                <x-input-label for="kategori" :value="__('Kategori Perjalanan')" />
                                <select id="kategori" name="kategori" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required onchange="toggleCustomKategori()">
                                    <option value="Translok Koordinasi">Translok Koordinasi</option>
                                    <option value="Pengawasan / Supervisi Lapangan">Pengawasan / Supervisi Lapangan</option>
                                    <option value="Verifikasi Data">Verifikasi Data</option>
                                    <option value="Pendataan Lapangan">Pendataan Lapangan</option>
                                    <option value="Custom">Custom (Isi Sendiri)</option>
                                </select>
                                <input type="text" id="kategori_custom" name="kategori_custom" class="block mt-2 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm hidden" placeholder="Masukkan kategori perjalanan">
                            </div>
                        </div>

                        <!-- Kendala -->
                        <div class="mb-4">
                            <x-input-label for="kendala_ditemui" :value="__('Kendala / Masalah yang Ditemukan (Opsional)')" />
                            <textarea id="kendala_ditemui" name="kendala_ditemui" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" rows="2" placeholder="Contoh: Responden tidak ada di tempat, cuaca buruk, atau biarkan kosong jika lancar.">{{ old('kendala_ditemui') }}</textarea>
                            <x-input-error :messages="$errors->get('kendala_ditemui')" class="mt-2" />
                        </div>

                        <!-- Upload Dokumentasi -->
                        <div class="mb-4 p-4 border border-gray-200 rounded bg-gray-50">
                            <x-input-label for="fotos" :value="__('Upload Foto Dokumentasi (Bisa lebih dari 1 foto)')" />
                            <input id="fotos" name="fotos[]" type="file" multiple accept="image/*" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" onchange="previewImages(event)" />
                            <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG. Anda dapat memblok beberapa foto sekaligus saat memilih.</p>
                            
                            <!-- Container Preview -->
                            <div id="image-preview-container" class="mt-4 flex flex-wrap gap-4"></div>
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
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-md" id="hasil-container" style="{{ old('hasil_perjalanan') ? '' : 'display:none;' }}">
                            <x-input-label for="hasil_perjalanan" :value="__('Narasi Laporan (Silakan Review & Edit)')" />
                            <textarea id="hasil_perjalanan" name="hasil_perjalanan" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" rows="10">{{ old('hasil_perjalanan') }}</textarea>
                            <x-input-error :messages="$errors->get('hasil_perjalanan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md mr-4" href="{{ route('laporan.index') }}">
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
        const stData = {!! $stJson !!};
        function fillSuratTugas(select) {
            const id = select.value;
            if (id && stData[id]) {
                const data = stData[id];
                document.getElementById('tgl_laporan').value = data.tgl_kembali;
                document.getElementById('tgl_perjalanan').value = data.tgl_berangkat;
                document.getElementById('lokasi_perjalanan').value = data.tujuan;
                document.getElementById('tujuan_perjalanan').value = data.tugas;
            }
        }

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
            container.innerHTML = ''; // Reset container

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
    </script>
</x-app-layout>
