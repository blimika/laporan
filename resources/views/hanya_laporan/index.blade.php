<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Hanya Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <form method="GET" action="{{ route('hanya-laporan.index') }}" class="flex items-center space-x-2">
                            <x-text-input id="search" name="search" type="text" class="block w-64" placeholder="Cari Pegawai, Tujuan, No ST..." value="{{ request('search') }}" />
                            <select name="per_page" class="border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" onchange="this.form.submit()">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 baris</option>
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 baris</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris</option>
                            </select>
                            <x-primary-button>Cari</x-primary-button>
                            @if(request('search'))
                                <a href="{{ route('hanya-laporan.index') }}" class="text-sm text-gray-500 hover:text-gray-700 ml-2">Reset</a>
                            @endif
                        </form>
                        <a href="{{ route('hanya-laporan.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span>Buat Laporan</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'tgl_perjalanan', 'direction' => ($sort == 'tgl_perjalanan' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center space-x-1 hover:text-gray-700">
                                            <span>Waktu</span>
                                            @if($sort == 'tgl_perjalanan')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nomor_st', 'direction' => ($sort == 'nomor_st' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center space-x-1 hover:text-gray-700">
                                            <span>Nomor ST</span>
                                            @if($sort == 'nomor_st')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_pegawai', 'direction' => ($sort == 'nama_pegawai' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center space-x-1 hover:text-gray-700">
                                            <span>Nama Pegawai</span>
                                            @if($sort == 'nama_pegawai')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'tujuan_perjalanan', 'direction' => ($sort == 'tujuan_perjalanan' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center space-x-1 hover:text-gray-700">
                                            <span>Tujuan Perjalanan</span>
                                            @if($sort == 'tujuan_perjalanan')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($laporans as $lap)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $lap->tgl_perjalanan->format('d/m/Y') }}<br>
                                            <span class="text-xs text-gray-500">Lap: {{ $lap->tgl_laporan->format('d/m/Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $lap->nomor_st }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="font-medium text-gray-900">{{ $lap->nama_pegawai }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div class="font-medium text-gray-900">
                                                {{ $lap->lokasi_perjalanan }}
                                                @if($lap->stamp_koordinat)
                                                    <a href="https://maps.google.com/?q={{ $lap->stamp_koordinat }}" target="_blank" class="ml-1 text-red-500 hover:text-red-700 inline-block" title="Buka di Google Maps">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="text-xs">{{ $lap->tujuan_perjalanan }}</div>
                                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $lap->kategori }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                            {{ $lap->user ? $lap->user->name : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex justify-center space-x-2">
                                                <!-- View Laporan -->
                                                <button type="button" onclick="showDetail(this)" data-laporan="{{ $lap->toJson() }}" data-dokumentasi="{{ $lap->dokumentasi->toJson() }}" class="text-purple-600 hover:text-purple-900" title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                
                                                <!-- Cetak Laporan -->
                                                <a href="{{ route('export.hanya-laporan', $lap->id) }}" class="text-green-600 hover:text-green-900" title="Cetak Laporan PDF" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                </a>

                                                <!-- Download Word -->
                                                <a href="{{ route('export.hanya-laporan.word', $lap->id) }}" class="text-blue-600 hover:text-blue-900" title="Download Word" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                                
                                                <a href="{{ route('hanya-laporan.edit', $lap->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit Laporan">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                
                                                <!-- Hapus Laporan -->
                                                <form action="{{ route('hanya-laporan.destroy', $lap->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Laporan">
                                                        <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada data laporan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $laporans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal View Detail -->
    <div id="detail-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('detail-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[70vh] overflow-y-auto">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 border-b pb-2">Detail Laporan Perjalanan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm" id="detail-content">
                        <!-- Konten akan diisi via Javascript -->
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="document.getElementById('detail-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Foto Viewer -->
    <div id="foto-modal" class="fixed z-20 inset-0 overflow-y-auto hidden" aria-labelledby="modal-foto-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-black bg-opacity-90 transition-opacity" aria-hidden="true" onclick="document.getElementById('foto-modal').classList.add('hidden')"></div>
            <div class="inline-block align-middle rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full relative z-30 p-4">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="document.getElementById('foto-modal').classList.add('hidden')" class="text-white hover:text-gray-300 focus:outline-none">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="foto-content" class="flex flex-wrap justify-center gap-4 mt-8 max-h-[85vh] overflow-y-auto">
                    <!-- Photos will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showFoto(dokumentasi, laporanData = null) {
            const container = document.getElementById('foto-content');
            container.innerHTML = '';
            
            if(dokumentasi && dokumentasi.length > 0) {
                const appUrl = "{{ config('app.url') }}";
                const baseUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;
                
                let stampHtml = '';
                if (laporanData && laporanData.is_stamped) {
                    let dtVal = '-';
                    if (laporanData.stamp_datetime) {
                        const dateObj = new Date(laporanData.stamp_datetime);
                        const d = String(dateObj.getDate()).padStart(2, '0');
                        const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                        const y = dateObj.getFullYear();
                        const h = String(dateObj.getHours()).padStart(2, '0');
                        const min = String(dateObj.getMinutes()).padStart(2, '0');
                        dtVal = `${d}/${m}/${y} ${h}:${min}`;
                    }
                    const koordinat = laporanData.stamp_koordinat || '-';
                    const tujuan = laporanData.tujuan_perjalanan || '-';
                    
                    stampHtml = `<div class="absolute bottom-4 left-4 text-white text-xs sm:text-sm leading-tight font-bold border-l-4 border-yellow-400 pl-2 pointer-events-none drop-shadow-md" style="text-shadow: 1px 1px 3px black, 0 0 2px black;">
                        ${tujuan}<br>${dtVal}<br>${koordinat}
                    </div>`;
                }
                
                dokumentasi.forEach(dok => {
                    container.innerHTML += `
                        <div class="bg-white p-2 rounded max-w-full relative inline-block">
                            <img src="${baseUrl}/${dok.file_path}" class="max-h-[80vh] object-contain rounded">
                            ${stampHtml}
                        </div>
                    `;
                });
                document.getElementById('foto-modal').classList.remove('hidden');
            }
        }

        function showDetail(buttonElement) {
            const data = JSON.parse(buttonElement.getAttribute('data-laporan'));
            const dokumentasi = JSON.parse(buttonElement.getAttribute('data-dokumentasi'));
            const content = document.getElementById('detail-content');
            
            const renderRow = (label, value) => {
                return `<div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">${label}</p>
                            <p class="text-gray-900 font-medium">${value || '-'}</p>
                        </div>`;
            };
            
            const tglL = new Date(data.tgl_laporan).toLocaleDateString('id-ID');
            const tglP = new Date(data.tgl_perjalanan).toLocaleDateString('id-ID');

            let fotosHtml = '';
            const appUrl = "{{ config('app.url') }}";
            if (dokumentasi && dokumentasi.length > 0) {
                fotosHtml = `<div class="col-span-2 mt-2 pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Foto Dokumentasi</p>
                    <div class="flex flex-wrap gap-2">`;
                
                let stampHtml = '';
                if (data.is_stamped) {
                    let dtVal = '-';
                    if (data.stamp_datetime) {
                        const dateObj = new Date(data.stamp_datetime);
                        const d = String(dateObj.getDate()).padStart(2, '0');
                        const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                        const y = dateObj.getFullYear();
                        const h = String(dateObj.getHours()).padStart(2, '0');
                        const min = String(dateObj.getMinutes()).padStart(2, '0');
                        dtVal = `${d}/${m}/${y} ${h}:${min}`;
                    }
                    const koordinat = data.stamp_koordinat || '-';
                    const tujuan = data.tujuan_perjalanan || '-';
                    
                    stampHtml = `<div class="absolute bottom-1 left-1 text-white text-[8px] leading-tight font-bold border-l-2 border-yellow-400 pl-1 pointer-events-none drop-shadow-md" style="text-shadow: 1px 1px 2px black, 0 0 1px black;">
                        ${tujuan}<br>${dtVal}<br>${koordinat}
                    </div>`;
                }
                
                dokumentasi.forEach(dok => {
                    const baseUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;
                    fotosHtml += `<div class="w-24 h-24 overflow-hidden rounded border border-gray-300 relative group">
                                    <a href="${baseUrl}/${dok.file_path}" target="_blank" class="block w-full h-full">
                                        <img src="${baseUrl}/${dok.file_path}" class="object-cover w-full h-full hover:opacity-75 transition-opacity">
                                        ${stampHtml}
                                    </a>
                                  </div>`;
                });
                fotosHtml += `</div></div>`;
            }

            let mapHtml = '';
            if (data.stamp_koordinat) {
                mapHtml = `<a href="https://maps.google.com/?q=${data.stamp_koordinat}" target="_blank" class="ml-1 text-red-500 hover:text-red-700 inline-block" title="Buka di Google Maps">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                           </a>`;
            }

            content.innerHTML = `
                ${renderRow('Nomor Surat Tugas', data.nomor_st)}
                ${renderRow('Nomor SPD', data.nomor_spd)}
                ${renderRow('Pelaksana', data.nama_pegawai)}
                ${renderRow('NIP', data.nip_pegawai)}
                
                <div class="col-span-2 mt-2 pt-2 border-t border-gray-100"></div>
                ${renderRow('Tanggal Laporan', tglL)}
                ${renderRow('Tanggal Perjalanan', tglP)}
                
                ${renderRow('Lokasi Perjalanan', data.lokasi_perjalanan + mapHtml)}
                ${renderRow('Tujuan Perjalanan', data.tujuan_perjalanan)}
                
                ${renderRow('Pegawai Ditemui', data.pegawai_ditemui)}
                ${renderRow('Kategori', data.kategori)}
                
                <div class="col-span-2 mt-2">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Kendala / Masalah yang Ditemukan</p>
                    <p class="text-gray-900 font-medium">${data.kendala_ditemui || '-'}</p>
                </div>
                
                <div class="col-span-2 mt-2 pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Hasil Perjalanan / Narasi (AI)</p>
                    <div class="text-gray-900 font-medium bg-gray-50 p-3 rounded border text-xs whitespace-pre-wrap">${data.hasil_perjalanan}</div>
                </div>

                ${fotosHtml}
            `;

            document.getElementById('detail-modal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
