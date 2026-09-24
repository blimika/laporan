<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan AI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Daftar Laporan Perjalanan</h3>
                        <a href="{{ route('laporan.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded shadow">
                            + Input Laporan AI Baru
                        </a>
                    </div>

                    <!-- Search Form -->
                    <div class="mb-4 bg-gray-50 p-4 rounded-lg border">
                        <form action="{{ route('laporan.index') }}" method="GET" class="flex items-center space-x-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Surat atau Kategori..." class="w-full sm:w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded text-sm">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('laporan.index') }}" class="text-gray-500 hover:text-gray-700 text-sm py-2 px-3 border border-gray-300 rounded hover:bg-gray-100">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border mt-4">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. ST</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelaksana</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($laporans as $laporan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->suratTugas->nomor_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->suratTugas->pegawai->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->lokasi_perjalanan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $laporan->tgl_laporan->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($laporan->dokumentasi->count() > 0)
                                                <div class="flex -space-x-2 overflow-hidden cursor-pointer" onclick="showFoto({{ $laporan->dokumentasi->toJson() }})" title="Lihat Foto">
                                                    @foreach($laporan->dokumentasi->take(3) as $dok)
                                                        <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white object-cover hover:opacity-75" src="{{ asset($dok->file_path) }}" alt="Foto">
                                                    @endforeach
                                                    @if($laporan->dokumentasi->count() > 3)
                                                        <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-gray-200 flex items-center justify-center text-xs text-gray-500 font-bold">
                                                            +{{ $laporan->dokumentasi->count() - 3 }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                            <!-- Tombol View -->
                                            <button type="button" data-laporan="{{ $laporan->toJson() }}" data-st="{{ $laporan->suratTugas->toJson() }}" data-pegawai="{{ $laporan->suratTugas->pegawai->toJson() }}" data-dokumentasi="{{ $laporan->dokumentasi->toJson() }}" onclick="showDetail(this)" class="text-blue-600 hover:text-blue-900" title="View Detail">
                                                <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>

                                            <!-- Tombol Edit -->
                                            <a href="{{ route('laporan.edit', $laporan->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit Laporan">
                                                <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Laporan ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Laporan">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Cetak PDF -->
                                            <a href="{{ route('export.laporan', $laporan->id) }}" class="text-purple-600 hover:text-purple-900" title="Cetak PDF Laporan" target="_blank">
                                                <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">Belum ada Laporan.</td>
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
        function showFoto(dokumentasi) {
            const container = document.getElementById('foto-content');
            container.innerHTML = '';
            
            if(dokumentasi && dokumentasi.length > 0) {
                const appUrl = "{{ config('app.url') }}";
                const baseUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;
                
                dokumentasi.forEach(dok => {
                    container.innerHTML += `
                        <div class="bg-white p-2 rounded max-w-full">
                            <img src="${baseUrl}/${dok.file_path}" class="max-h-[80vh] object-contain rounded">
                        </div>
                    `;
                });
                document.getElementById('foto-modal').classList.remove('hidden');
            }
        }

        function showDetail(buttonElement) {
            const data = JSON.parse(buttonElement.getAttribute('data-laporan'));
            const st = JSON.parse(buttonElement.getAttribute('data-st'));
            const pegawai = JSON.parse(buttonElement.getAttribute('data-pegawai'));
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
                dokumentasi.forEach(dok => {
                    // Pastikan url tidak double slash
                    const baseUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;
                    fotosHtml += `<div class="w-24 h-24 overflow-hidden rounded border border-gray-300">
                                    <a href="${baseUrl}/${dok.file_path}" target="_blank">
                                        <img src="${baseUrl}/${dok.file_path}" class="object-cover w-full h-full hover:opacity-75 transition-opacity">
                                    </a>
                                  </div>`;
                });
                fotosHtml += `</div></div>`;
            }

            content.innerHTML = `
                ${renderRow('Nomor Surat Tugas', st.nomor_surat)}
                ${renderRow('Pelaksana', pegawai.nama)}
                
                <div class="col-span-2 mt-2 pt-2 border-t border-gray-100"></div>
                ${renderRow('Tanggal Laporan', tglL)}
                ${renderRow('Tanggal Perjalanan', tglP)}
                
                ${renderRow('Lokasi Perjalanan', data.lokasi_perjalanan)}
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
