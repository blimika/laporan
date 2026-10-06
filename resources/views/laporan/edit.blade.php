<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Laporan Perjalanan (Generate AI)') }}
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

                    <form id="laporan-form" method="POST" action="{{ route('laporan.update', $laporan->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Surat Tugas -->
                        <div class="mb-4">
                            <x-input-label for="surat_tugas_id" :value="__('Surat Tugas (Pelaksana)')" />
                            <select id="surat_tugas_id" name="surat_tugas_id" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required>
                                <option value="{{ $laporan->surat_tugas_id }}">{{ $suratTugas[0]->nomor_surat }} - {{ $suratTugas[0]->pegawai->nama }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('surat_tugas_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="tgl_laporan" :value="__('Tanggal Laporan Dibuat')" />
                                <x-text-input id="tgl_laporan" class="block mt-1 w-full" type="date" name="tgl_laporan" :value="old('tgl_laporan', $laporan->tgl_laporan->format('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="tgl_perjalanan" :value="__('Tanggal Perjalanan (Pelaksanaan)')" />
                                <x-text-input id="tgl_perjalanan" class="block mt-1 w-full" type="date" name="tgl_perjalanan" :value="old('tgl_perjalanan', $laporan->tgl_perjalanan->format('Y-m-d'))" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="lokasi_perjalanan" :value="__('Lokasi (Instansi / Desa / Tempat)')" />
                                <x-text-input id="lokasi_perjalanan" class="block mt-1 w-full" type="text" name="lokasi_perjalanan" :value="old('lokasi_perjalanan', $laporan->lokasi_perjalanan)" required />
                            </div>
                            <div>
                                <x-input-label for="tujuan_perjalanan" :value="__('Tujuan Perjalanan (Singkat)')" />
                                <x-text-input id="tujuan_perjalanan" class="block mt-1 w-full" type="text" name="tujuan_perjalanan" :value="old('tujuan_perjalanan', $laporan->tujuan_perjalanan)" required onchange="reprocessImages()" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="pegawai_ditemui" :value="__('Pihak / Pegawai yang Ditemui')" />
                                <x-text-input id="pegawai_ditemui" class="block mt-1 w-full" type="text" name="pegawai_ditemui" :value="old('pegawai_ditemui', $laporan->pegawai_ditemui)" required />
                            </div>
                            <div>
                                <x-input-label for="kategori" :value="__('Kategori Perjalanan')" />
                                @php
                                    $standardKategori = ['Translok Koordinasi', 'Pengawasan / Supervisi Lapangan', 'Verifikasi Data', 'Pendataan Lapangan'];
                                    $isCustom = !in_array(old('kategori', $laporan->kategori), $standardKategori) && $laporan->kategori;
                                    $selectedValue = $isCustom ? 'Custom' : old('kategori', $laporan->kategori);
                                @endphp
                                <select id="kategori" name="kategori" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required onchange="toggleCustomKategori()">
                                    <option value="Translok Koordinasi" {{ $selectedValue == 'Translok Koordinasi' ? 'selected' : '' }}>Translok Koordinasi</option>
                                    <option value="Pengawasan / Supervisi Lapangan" {{ $selectedValue == 'Pengawasan / Supervisi Lapangan' ? 'selected' : '' }}>Pengawasan / Supervisi Lapangan</option>
                                    <option value="Verifikasi Data" {{ $selectedValue == 'Verifikasi Data' ? 'selected' : '' }}>Verifikasi Data</option>
                                    <option value="Pendataan Lapangan" {{ $selectedValue == 'Pendataan Lapangan' ? 'selected' : '' }}>Pendataan Lapangan</option>
                                    <option value="Custom" {{ $selectedValue == 'Custom' ? 'selected' : '' }}>Custom (Isi Sendiri)</option>
                                </select>
                                <input type="text" id="kategori_custom" name="kategori_custom" class="block mt-2 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm {{ $isCustom ? '' : 'hidden' }}" placeholder="Masukkan kategori perjalanan" value="{{ $isCustom ? old('kategori_custom', $laporan->kategori) : '' }}" {{ $isCustom ? 'required' : '' }}>
                            </div>
                        </div>

                        <!-- Kendala -->
                        <div class="mb-4">
                            <x-input-label for="kendala_ditemui" :value="__('Kendala / Masalah yang Ditemukan (Opsional)')" />
                            <textarea id="kendala_ditemui" name="kendala_ditemui" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" rows="2">{{ old('kendala_ditemui', $laporan->kendala_ditemui) }}</textarea>
                            <x-input-error :messages="$errors->get('kendala_ditemui')" class="mt-2" />
                        </div>

                        <!-- Upload Dokumentasi -->
                        <div class="mb-4 p-4 border border-gray-200 rounded bg-gray-50">
                            @if($laporan->dokumentasi && $laporan->dokumentasi->count() > 0)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Foto Dokumentasi Saat Ini:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($laporan->dokumentasi as $dok)
                                            <div class="relative w-32 h-32 rounded border border-gray-300 group" id="photo-container-{{ $dok->id }}">
                                                <a href="{{ asset($dok->file_path) }}" target="_blank" class="block w-full h-full">
                                                    <img src="{{ asset($dok->file_path) }}" class="object-cover w-full h-full hover:opacity-75 transition-opacity rounded">
                                                    @if($laporan->is_stamped)
                                                        @php
                                                            $dtVal = $laporan->stamp_datetime ? \Carbon\Carbon::parse($laporan->stamp_datetime)->format('d/m/Y H:i') : '-';
                                                        @endphp
                                                        <div class="absolute bottom-1 left-1 text-white text-[8px] leading-tight font-bold border-l-2 border-yellow-400 pl-1 pointer-events-none drop-shadow-md" style="text-shadow: 1px 1px 2px black, 0 0 1px black;">
                                                            {{ $laporan->tujuan_perjalanan }}<br>{{ $dtVal }}<br>{{ $laporan->stamp_koordinat }}
                                                        </div>
                                                    @endif
                                                </a>
                                                <button type="button" onclick="removeExistingPhoto({{ $dok->id }})" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus Foto">
                                                    &#10005;
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <hr class="mb-4">
                            <x-input-label for="fotos" :value="__('Upload Tambahan Foto Dokumentasi (Bisa lebih dari 1 foto)')" />
                            <input id="fotos" name="fotos[]" type="file" multiple accept="image/*" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" onchange="handleFotosChange(event)" />
                            <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG. Anda dapat memblok beberapa foto sekaligus saat memilih.</p>
                            
                            <div class="mt-4 p-4 border border-gray-300 rounded bg-white">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="check_stamp" name="check_stamp" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" onchange="toggleStampInputs()" {{ $laporan->is_stamped ? 'checked' : '' }}>
                                    <label for="check_stamp" class="ml-2 block text-sm text-gray-900 font-bold">
                                        Tambahkan Stamp Foto Dokumentasi
                                    </label>
                                </div>
                                
                                <div id="stamp_inputs" class="{{ $laporan->is_stamped ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <x-input-label for="stamp_koordinat" :value="__('Koordinat Lokasi (Manual)')" />
                                        <x-text-input id="stamp_koordinat" name="stamp_koordinat" class="block mt-1 w-full" type="text" placeholder="Contoh: -6.200000, 106.816666" value="{{ old('stamp_koordinat', $laporan->stamp_koordinat) }}" onchange="reprocessImages()" />
                                        <button type="button" onclick="getLocation()" class="mt-1 text-xs text-blue-600 hover:underline">Ambil Lokasi Saat Ini (GPS)</button>
                                    </div>
                                    <div>
                                        <x-input-label for="stamp_datetime" :value="__('Tanggal & Jam (Manual/Otomatis)')" />
                                        <x-text-input id="stamp_datetime" name="stamp_datetime" class="block mt-1 w-full" type="datetime-local" value="{{ old('stamp_datetime', $laporan->stamp_datetime) }}" onchange="reprocessImages()" />
                                    </div>
                                </div>
                            </div>
                            
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
                                <span>Generate Ulang dengan AI ✨</span>
                            </button>
                            <span id="loading-indicator" class="ml-3 text-sm text-gray-500 hidden">Sedang menyusun narasi... mohon tunggu.</span>
                        </div>

                        <!-- Hasil AI -->
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-md" id="hasil-container" style="{{ old('hasil_perjalanan', $laporan->hasil_perjalanan) ? '' : 'display:none;' }}">
                            <x-input-label for="hasil_perjalanan" :value="__('Narasi Laporan (Silakan Review & Edit)')" />
                            <textarea id="hasil_perjalanan" name="hasil_perjalanan" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" rows="10">{{ old('hasil_perjalanan', $laporan->hasil_perjalanan) }}</textarea>
                            <x-input-error :messages="$errors->get('hasil_perjalanan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md mr-4" href="{{ route('laporan.index') }}">
                                Batal
                            </a>
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Perbarui Laporan') }}
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
                reprocessImages();
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
                formData.delete('_method'); // Mencegah Laravel method spoofing ke PUT saat generate AI
                
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

        let originalFiles = [];

        function handleFotosChange(event) {
            const dt = new DataTransfer();
            for (let i = 0; i < event.target.files.length; i++) {
                dt.items.add(event.target.files[i]);
            }
            originalFiles = Array.from(dt.files);
            
            reprocessImages();
        }

        function toggleStampInputs() {
            const inputs = document.getElementById('stamp_inputs');
            if (document.getElementById('check_stamp').checked) {
                inputs.classList.remove('hidden');
                if (!document.getElementById('stamp_datetime').value) {
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    document.getElementById('stamp_datetime').value = now.toISOString().slice(0,16);
                }
            } else {
                inputs.classList.add('hidden');
            }
            reprocessImages();
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('stamp_koordinat').value = position.coords.latitude.toFixed(5) + ", " + position.coords.longitude.toFixed(5);
                    reprocessImages();
                }, function(error) {
                    alert("Gagal mendapatkan lokasi: " + error.message);
                });
            } else {
                alert("Geolocation tidak didukung oleh browser ini.");
            }
        }

        function reprocessImages() {
            if (originalFiles.length === 0) return;
            
            const container = document.getElementById('image-preview-container');
            container.innerHTML = '';
            
            const isStamped = document.getElementById('check_stamp').checked;
            const dt = new DataTransfer();
            
            const newContainerHTML = document.createElement('div');
            newContainerHTML.className = 'flex flex-wrap gap-4 mt-2';
            
            // Get stamp text
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
                const imgDiv = document.createElement('div');
                imgDiv.className = 'w-48 h-48 overflow-hidden rounded border border-gray-300 relative group';
                
                const link = document.createElement('a');
                link.href = previewSrc;
                link.target = '_blank';
                link.className = 'block w-full h-full';
                link.title = 'Klik untuk memperbesar gambar';
                
                const img = document.createElement('img');
                img.src = previewSrc;
                img.className = 'object-cover w-full h-full';
                
                link.appendChild(img);
                
                if (isStamped) {
                    const stampDiv = document.createElement('div');
                    stampDiv.className = 'absolute bottom-2 left-2 text-white text-[10px] leading-tight font-bold border-l-2 border-yellow-400 pl-1 pointer-events-none drop-shadow-md';
                    stampDiv.style.textShadow = '1px 1px 2px black, 0 0 1px black';
                    stampDiv.innerHTML = `${tujuan}<br>${hariTanggal}<br>${koordinat}`;
                    link.appendChild(stampDiv);
                }
                
                imgDiv.appendChild(link);
                newContainerHTML.appendChild(imgDiv);
            }
            
            container.appendChild(newContainerHTML);
            document.getElementById('fotos').files = dt.files;
        }
        function removeExistingPhoto(id) {
            if(!confirm('Anda yakin ingin menghapus foto ini? (Akan terhapus saat laporan disimpan)')) return;
            
            // Add hidden input to form
            const form = document.getElementById('laporan-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_fotos[]';
            input.value = id;
            form.appendChild(input);
            
            // Remove visually
            const container = document.getElementById('photo-container-' + id);
            container.remove();
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
