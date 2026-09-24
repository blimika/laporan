<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Surat Tugas & SPD') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Daftar Surat Tugas</h3>
                        <a href="{{ route('surat-tugas.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                            Buat Surat Tugas Baru
                        </a>
                    </div>

                    <!-- Search Form -->
                    <div class="mb-4 bg-gray-50 p-4 rounded-lg border">
                        <form action="{{ route('surat-tugas.index') }}" method="GET" class="flex items-center space-x-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Surat atau Tugas..." class="w-full sm:w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded text-sm">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('surat-tugas.index') }}" class="text-gray-500 hover:text-gray-700 text-sm py-2 px-3 border border-gray-300 rounded hover:bg-gray-100">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelaksana</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status SPD</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Dokumen</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($suratTugasList as $st)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $st->nomor_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $st->pegawai->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $st->tujuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $st->tgl_berangkat->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($st->spd)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                  SPD Terbit
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                  Belum Ada SPD
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                            <!-- Tombol View -->
                                            <button type="button" data-surat="{{ $st->toJson() }}" data-pegawai="{{ $st->pegawai->toJson() }}" onclick="showDetail(this)" class="text-blue-600 hover:text-blue-900" title="View Detail">
                                                <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('surat-tugas.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Menghapus Surat Tugas juga akan menghapus SPD dan Laporan terkait. Yakin?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Surat Tugas">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Cetak ST -->
                                            <a href="{{ route('export.surat-tugas', $st->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Cetak Surat Tugas" target="_blank">
                                                <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                            
                                            @if($st->spd)
                                                <!-- Cetak SPD -->
                                                <a href="{{ route('export.spd', $st->spd->id) }}" class="text-green-600 hover:text-green-900" title="Cetak SPD" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                                    </svg>
                                                </a>
                                                <!-- Cetak DPR -->
                                                <a href="{{ route('export.dpr', $st->spd->id) }}" class="text-blue-600 hover:text-blue-900" title="Cetak DPR" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                </a>
                                                <!-- Cetak Pernyataan -->
                                                <a href="{{ route('export.pernyataan', $st->spd->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Cetak Surat Pernyataan Riil" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="{{ route('spd.create', $st->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Terbitkan SPD & DPR">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">Belum ada data Surat Tugas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $suratTugasList->links() }}
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
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 border-b pb-2">Detail Surat Tugas</h3>
                    
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

    <script>
        function showDetail(buttonElement) {
            const data = JSON.parse(buttonElement.getAttribute('data-surat'));
            const pegawai = JSON.parse(buttonElement.getAttribute('data-pegawai'));
            const content = document.getElementById('detail-content');
            
            const renderRow = (label, value) => {
                return `<div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">${label}</p>
                            <p class="text-gray-900 font-medium">${value || '-'}</p>
                        </div>`;
            };
            
            const tglB = new Date(data.tgl_berangkat).toLocaleDateString('id-ID');
            const tglK = new Date(data.tgl_kembali).toLocaleDateString('id-ID');

            content.innerHTML = `
                ${renderRow('Nomor Surat', data.nomor_surat)}
                ${renderRow('Pelaksana', pegawai.nama)}
                
                <div class="col-span-2 mt-2 pt-2 border-t border-gray-100"></div>
                ${renderRow('Maksud / Tujuan Tugas', data.tugas)}
                ${renderRow('Tempat Tujuan', data.tujuan)}
                
                ${renderRow('Tanggal Berangkat', tglB)}
                ${renderRow('Tanggal Kembali', tglK)}
            `;

            document.getElementById('detail-modal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
