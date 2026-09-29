<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Daftar Pegawai</h3>
                        @if(auth()->user()->role !== 'user')
                        <div class="flex space-x-2">
                            <a href="{{ route('pegawais.template') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Template CSV
                            </a>
                            <button onclick="document.getElementById('import-modal').classList.remove('hidden')" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded text-sm">
                                Import CSV
                            </button>
                            <a href="{{ route('pegawais.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                + Tambah
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Search Form -->
                    <div class="mb-4 bg-gray-50 p-4 rounded-lg border">
                        <form action="{{ route('pegawais.index') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center space-x-2 w-full sm:w-auto">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP atau Nama..." class="w-full sm:w-64 border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm text-sm">
                                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded text-sm">
                                    Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('pegawais.index') }}" class="text-gray-500 hover:text-gray-700 text-sm py-2 px-3 border border-gray-300 rounded hover:bg-gray-100">
                                        Reset
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2 w-full sm:w-auto">
                                <label for="per_page" class="text-sm text-gray-700">Tampilkan:</label>
                                <select name="per_page" id="per_page" onchange="this.form.submit()" class="border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm text-sm py-2">
                                    <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Golongan / Pangkat</th>
                                    @if(auth()->user()->role !== 'user')
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pegawais as $pegawai)
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap font-medium text-sm">{{ $pegawai->nip }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $pegawai->nama }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700 max-w-xs">{{ $pegawai->jabatan ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700 max-w-xs">
                                            @if($pegawai->golongan || $pegawai->pangkat)
                                                {{ $pegawai->golongan }} @if($pegawai->golongan && $pegawai->pangkat) / @endif {{ $pegawai->pangkat }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                            <!-- Tombol View -->
                                            <button type="button" data-pegawai="{{ $pegawai->toJson() }}" onclick="showDetail(this)" class="text-blue-600 hover:text-blue-900" title="View Detail">
                                                <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            
                                            @if(auth()->user()->role !== 'user')
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('pegawais.edit', $pegawai->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit Data">
                                                <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            
                                            <!-- Tombol Delete -->
                                            <form action="{{ route('pegawais.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pegawai ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Data">
                                                    <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 whitespace-nowrap text-center text-gray-500">Belum ada data pegawai</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $pegawais->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div id="import-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('pegawais.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Import Data Pegawai (CSV)</h3>
                        <p class="text-sm text-gray-500 mb-4">Pastikan Anda mendownload <b>Template CSV</b> terlebih dahulu, mengisi datanya, dan mengunggahnya kembali.</p>
                        
                        <input type="file" name="file_csv" accept=".csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100" />
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Import Data
                        </button>
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal View Detail -->
    <div id="detail-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('detail-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 border-b pb-2">Detail Pegawai</h3>
                    
                    <div class="space-y-4 text-sm" id="detail-content">
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
            const data = JSON.parse(buttonElement.getAttribute('data-pegawai'));
            const content = document.getElementById('detail-content');
            
            const renderRow = (label, value) => {
                return `<div>
                            <p class="text-xs text-gray-500 uppercase font-semibold">${label}</p>
                            <p class="text-gray-900 font-medium text-base">${value || '-'}</p>
                        </div>`;
            };

            content.innerHTML = `
                ${renderRow('NIP', data.nip)}
                ${renderRow('Nama Lengkap', data.nama)}
                ${renderRow('Jabatan', data.jabatan)}
                ${renderRow('Golongan', data.golongan)}
                ${renderRow('Pangkat', data.pangkat)}
            `;

            document.getElementById('detail-modal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
