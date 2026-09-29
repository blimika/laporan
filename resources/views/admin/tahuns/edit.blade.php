<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tahun: ') }} {{ $tahun->tahun }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Perbarui Data Tahun</h3>
                    <a href="{{ route('admin.tahuns.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Kembali</a>
                </div>
                
                <div class="p-4 sm:p-6 bg-white">
                    <form action="{{ route('admin.tahuns.update', $tahun->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="tahun" :value="__('Tahun')" />
                                <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" value="{{ old('tahun', $tahun->tahun) }}" required />
                            </div>
                            <div class="flex items-center mt-6">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="aktif" value="1" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" {{ $tahun->aktif ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">Jadikan Tahun Aktif (Tahun lain otomatis nonaktif)</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.tahuns.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
