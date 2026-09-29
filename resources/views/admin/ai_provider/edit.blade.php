<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setting Provider AI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if(auth()->user()->role === 'super')
            <div class="bg-white shadow sm:rounded-lg overflow-hidden mb-6">
                <div class="px-4 py-5 sm:p-6 bg-gray-50 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.ai_provider.edit') }}" class="flex items-center space-x-4">
                        <label for="satker_switcher" class="block text-sm font-medium text-gray-700 whitespace-nowrap">Pilih Satker (Super Admin Mode):</label>
                        <select id="satker_switcher" name="satker_id" onchange="this.form.submit()" class="block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm">
                            @foreach($allSatkers as $s)
                                <option value="{{ $s->id }}" {{ $satker->id == $s->id ? 'selected' : '' }}>
                                    {{ $s->kode }} - {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Pengaturan AI untuk Satker: <strong>{{ $satker->kode }} - {{ $satker->nama }}</strong></h3>
                    <p class="mt-1 text-sm text-gray-500">Konfigurasi sumber API Key dan preferensi model kecerdasan buatan untuk generate Laporan.</p>
                </div>
                
                <div class="p-4 sm:p-6 bg-white">
                    <form method="POST" action="{{ route('admin.ai_provider.update') }}">
                        @csrf
                        @method('PUT')
                        
                        @if(auth()->user()->role === 'super')
                            <input type="hidden" name="satker_id" value="{{ $satker->id }}">
                        @endif

                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <h4 class="text-md font-semibold text-gray-800 mb-4">Mode API Key</h4>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_centralized_api" name="is_centralized_api" type="checkbox" value="1" {{ $satker->is_centralized_api ? 'checked' : '' }} class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_centralized_api" class="font-medium text-gray-700">Gunakan API Key Terpusat (Global Satker)</label>
                                    <p class="text-gray-500">Jika dicentang, semua user dalam satker ini akan menggunakan API Key yang disetting pada halaman ini. Kolom API Key di halaman Profil masing-masing user akan disembunyikan.<br>Jika tidak dicentang, sistem akan memakai API Key dari masing-masing user (diisi pada halaman Profil User).</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="ai_provider" :value="__('Provider AI yang Digunakan')" />
                                <select id="ai_provider" name="ai_provider" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required>
                                    <option value="gemini" {{ $satker->ai_provider === 'gemini' ? 'selected' : '' }}>Google Gemini (Disarankan)</option>
                                    <option value="deepseek" {{ $satker->ai_provider === 'deepseek' ? 'selected' : '' }}>Deepseek</option>
                                </select>
                                <x-input-error :messages="$errors->get('ai_provider')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="gemini_api_key" :value="__('Gemini API Key')" />
                                <x-text-input id="gemini_api_key" type="password" name="gemini_api_key" class="block mt-1 w-full" :value="$satker->gemini_api_key" />
                                <p class="text-xs text-gray-500 mt-1">Hanya dibutuhkan jika Provider menggunakan Google Gemini.</p>
                                <x-input-error :messages="$errors->get('gemini_api_key')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="deepseek_api_key" :value="__('Deepseek API Key')" />
                                <x-text-input id="deepseek_api_key" type="password" name="deepseek_api_key" class="block mt-1 w-full" :value="$satker->deepseek_api_key" />
                                <p class="text-xs text-gray-500 mt-1">Hanya dibutuhkan jika Provider menggunakan Deepseek.</p>
                                <x-input-error :messages="$errors->get('deepseek_api_key')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md shadow-sm transition duration-150">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
