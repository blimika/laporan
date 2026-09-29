<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Satuan Kerja (Satker)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Form Tambah Satker -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Satker Baru</h3>
                    <p class="mt-1 text-sm text-gray-500">Tambahkan daftar Satuan Kerja yang dapat dipilih oleh pengguna.</p>
                </div>
                <div class="p-4 sm:p-6 bg-white">
                    <form action="{{ route('admin.satkers.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="kode" :value="__('Kode Satker')" />
                                <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode" required />
                            </div>
                            <div>
                                <x-input-label for="nama" :value="__('Nama Satker')" />
                                <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" required />
                            </div>
                            <div>
                                <x-input-label for="ai_provider" :value="__('AI Provider Default')" />
                                <select id="ai_provider" name="ai_provider" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm">
                                    <option value="gemini">Google Gemini</option>
                                    <option value="deepseek">DeepSeek</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="gemini_api_key" :value="__('Gemini API Key')" />
                                <x-text-input id="gemini_api_key" class="block mt-1 w-full" type="password" name="gemini_api_key" />
                            </div>
                            <div>
                                <x-input-label for="deepseek_api_key" :value="__('DeepSeek API Key')" />
                                <x-text-input id="deepseek_api_key" class="block mt-1 w-full" type="password" name="deepseek_api_key" />
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition duration-150">
                                + Tambah Satker
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Daftar Satker -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Satker</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Satker</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AI Provider</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($data as $row)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row->kode }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">{{ $row->ai_provider ?? 'gemini' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex justify-center items-center space-x-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.satkers.edit', $row->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit Satker">
                                        <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.satkers.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Satker ini?');" class="inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus Satker">
                                            <svg xmlns="http://www.w3.org/2003/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Belum ada data Satker.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
