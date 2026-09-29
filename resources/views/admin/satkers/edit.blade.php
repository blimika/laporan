<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Satker: ') }} {{ $satker->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Perbarui Data Satker</h3>
                    <a href="{{ route('admin.satkers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Kembali</a>
                </div>
                
                <div class="p-4 sm:p-6 bg-white">
                    <form action="{{ route('admin.satkers.update', $satker->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="kode" :value="__('Kode Satker')" />
                                <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode" value="{{ old('kode', $satker->kode) }}" required />
                            </div>
                            <div>
                                <x-input-label for="nama" :value="__('Nama Satker')" />
                                <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" value="{{ old('nama', $satker->nama) }}" required />
                            </div>
                            <div>
                                <x-input-label for="ai_provider" :value="__('AI Provider Default')" />
                                <select id="ai_provider" name="ai_provider" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm">
                                    <option value="gemini" {{ old('ai_provider', $satker->ai_provider) === 'gemini' ? 'selected' : '' }}>Google Gemini</option>
                                    <option value="deepseek" {{ old('ai_provider', $satker->ai_provider) === 'deepseek' ? 'selected' : '' }}>DeepSeek</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="gemini_api_key" :value="__('Gemini API Key (Opsional)')" />
                                <x-text-input id="gemini_api_key" class="block mt-1 w-full" type="password" name="gemini_api_key" value="{{ old('gemini_api_key', $satker->gemini_api_key) }}" />
                            </div>
                            <div>
                                <x-input-label for="deepseek_api_key" :value="__('DeepSeek API Key (Opsional)')" />
                                <x-text-input id="deepseek_api_key" class="block mt-1 w-full" type="password" name="deepseek_api_key" value="{{ old('deepseek_api_key', $satker->deepseek_api_key) }}" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.satkers.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
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
