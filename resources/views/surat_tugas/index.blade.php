<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Surat Tugas & SPD') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <form method="GET" action="{{ route('surat-tugas.index') }}" class="flex items-center space-x-2">
                            <x-text-input id="search" name="search" type="text" class="block w-64" placeholder="Cari Pegawai, Tujuan, No Surat/SPD..." value="{{ request('search') }}" />
                            <select name="per_page" class="border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" onchange="this.form.submit()">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 baris</option>
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 baris</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris</option>
                            </select>
                            <x-primary-button>Cari</x-primary-button>
                            @if(request('search'))
                                <a href="{{ route('surat-tugas.index') }}" class="text-sm text-gray-500 hover:text-gray-700 ml-2">Reset</a>
                            @endif
                        </form>
                        <a href="{{ route('surat-tugas.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span>Buat Surat Tugas Baru</span>
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'tgl_berangkat', 'direction' => ($sort == 'tgl_berangkat' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center space-x-1 hover:text-gray-700">
                                            <span>Waktu</span>
                                            @if($sort == 'tgl_berangkat')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nomor_surat', 'direction' => ($sort == 'nomor_surat' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center space-x-1 hover:text-gray-700">
                                            <span>Nomor ST & SPD</span>
                                            @if($sort == 'nomor_surat')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pegawai
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'tujuan', 'direction' => ($sort == 'tujuan' && $direction == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center space-x-1 hover:text-gray-700">
                                            <span>Tujuan Perjalanan</span>
                                            @if($sort == 'tujuan')
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($suratTugasList as $st)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $st->tgl_berangkat->format('d/m/Y') }}
                                            @if($st->tgl_kembali && $st->tgl_kembali != $st->tgl_berangkat)
                                                <span class="text-xs text-gray-400">s/d</span> {{ $st->tgl_kembali->format('d/m/Y') }}
                                            @endif
                                            <br>
                                            <span class="text-xs text-gray-500">Surat: {{ $st->tgl_surat ? $st->tgl_surat->format('d/m/Y') : '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium text-gray-900">{{ $st->nomor_surat }}</div>
                                            <div class="text-xs font-mono mt-0.5">
                                                <span class="text-gray-400 font-sans">SPD:</span>
                                                @if($st->spd)
                                                    <span class="text-gray-600">{{ $st->spd->nomor_spd }}</span>
                                                @else
                                                    <span class="text-amber-600 font-sans font-semibold">Belum Terbit</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="font-medium text-gray-900">{{ $st->pegawai->nama }}</div>
                                            @if($st->pegawai->nip)
                                                <div class="text-xs text-gray-500 font-mono">{{ $st->pegawai->nip }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div class="font-medium text-gray-900">{{ $st->tujuan }}</div>
                                            <div class="text-xs">{{ $st->tugas }}</div>
                                            <div class="mt-1 flex flex-wrap items-center gap-1">
                                                @if($st->spd)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        SPD Terbit
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Belum Ada SPD
                                                    </span>
                                                @endif
                                                @if($st->anggaran)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 font-mono">
                                                        {{ $st->anggaran->mak }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 font-mono">
                                                {{ $st->user ? ($st->user->username ?? $st->user->name) : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex justify-center space-x-2">
                                                <!-- View Detail -->
                                                <button type="button" data-surat="{{ $st->toJson() }}" data-spd="{{ $st->spd ? $st->spd->toJson() : 'null' }}" data-pegawai="{{ $st->pegawai->toJson() }}" onclick="showDetail(this)" class="text-purple-600 hover:text-purple-900" title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                <!-- Cetak PDF Lengkap (Gabungan 1 Tombol) -->
                                                <a href="{{ route('export.surat-tugas', $st->id) }}" class="text-green-600 hover:text-green-900" title="{{ $st->spd ? 'Cetak / Preview PDF Lengkap (Surat Tugas, SPD, DPR, Pernyataan)' : 'Cetak / Preview PDF Surat Tugas' }}" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                </a>

                                                @if($st->spd)
                                                    <!-- Edit SPD & DPR -->
                                                    <a href="{{ route('spd.edit', $st->spd->id) }}" class="text-amber-600 hover:text-amber-900" title="Edit SPD & DPR">
                                                        <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </a>
                                                @else
                                                    <!-- Terbitkan SPD & DPR -->
                                                    <a href="{{ route('spd.create', $st->id) }}" class="text-amber-600 hover:text-amber-900" title="Terbitkan SPD & DPR">
                                                        <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                <!-- Edit Surat Tugas -->
                                                <a href="{{ route('surat-tugas.edit', $st->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit Surat Tugas">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>

                                                <!-- Hapus Surat Tugas -->
                                                <form action="{{ route('surat-tugas.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Menghapus Surat Tugas juga akan menghapus SPD dan Laporan terkait. Yakin?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Surat Tugas">
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
                                            Belum ada data Surat Tugas.
                                        </td>
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
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[70vh] overflow-y-auto">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 border-b pb-2">Detail Surat Tugas & SPD</h3>
                    
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
            const spd = buttonElement.getAttribute('data-spd') !== 'null' ? JSON.parse(buttonElement.getAttribute('data-spd')) : null;
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
            const tglS = data.tgl_surat ? new Date(data.tgl_surat).toLocaleDateString('id-ID') : '-';
            const mak = data.anggaran ? data.anggaran.mak : '-';

            let spdInfo = spd ? spd.nomor_spd : '<span class="text-amber-600">Belum Terbit</span>';
            let dprInfo = spd && spd.nilai_dpr ? 'Rp ' + parseInt(spd.nilai_dpr).toLocaleString('id-ID') : '-';

            content.innerHTML = `
                ${renderRow('Nomor Surat Tugas', data.nomor_surat)}
                ${renderRow('Nomor SPD', spdInfo)}
                ${renderRow('Tanggal Surat', tglS)}
                ${renderRow('Nomor MAK', mak)}
                ${renderRow('Pelaksana', pegawai.nama)}
                ${renderRow('NIP Pelaksana', pegawai.nip)}
                
                <div class="col-span-2 mt-2 pt-2 border-t border-gray-100"></div>
                ${renderRow('Tempat Tujuan', data.tujuan)}
                ${renderRow('Pengeluaran Riil (DPR)', dprInfo)}
                
                ${renderRow('Tanggal Berangkat', tglB)}
                ${renderRow('Tanggal Kembali', tglK)}
                
                <div class="col-span-2 mt-2">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Maksud / Tujuan Tugas</p>
                    <p class="text-gray-900 font-medium">${data.tugas || '-'}</p>
                </div>
            `;

            document.getElementById('detail-modal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
