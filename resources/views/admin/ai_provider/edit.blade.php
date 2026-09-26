<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setting Provider AI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @if(auth()->user()->role === 'super')
                <div class="mb-6">
                    <form method="GET" action="{{ route('admin.ai_provider.edit') }}">
                        <label for="satker_switcher" class="block text-sm font-medium text-gray-700">Pilih Satker (Mode Super Admin)</label>
                        <select id="satker_switcher" name="satker_id" onchange="this.form.submit()" class="mt-1 block w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm">
                            @foreach($allSatkers as $s)
                                <option value="{{ $s->id }}" {{ $satker->id == $s->id ? 'selected' : '' }}>
                                    {{ $s->kode }} - {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.ai_provider.update') }}">
                    @csrf
                    @method('PUT')
                    
                    @if(auth()->user()->role === 'super')
                        <input type="hidden" name="satker_id" value="{{ $satker->id }}">
                    @endif

                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900">Satuan Kerja Aktif: <strong>{{ $satker->kode }} - {{ $satker->nama }}</strong></h3>
                        <p class="mt-1 text-sm text-gray-600">Pengaturan API Key di bawah ini akan digunakan untuk semua pengguna di bawah Satker ini, sehingga mereka tidak perlu menginputkan API Key sendiri di profil.</p>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="ai_provider" :value="__('Provider AI yang Digunakan')" />
                        <select id="ai_provider" name="ai_provider" class="block mt-1 w-full border-gray-400 focus:border-black focus:ring-black rounded-md shadow-sm" required>
                            <option value="gemini" {{ $satker->ai_provider === 'gemini' ? 'selected' : '' }}>Google Gemini (Disarankan)</option>
                            <option value="deepseek" {{ $satker->ai_provider === 'deepseek' ? 'selected' : '' }}>Deepseek</option>
                        </select>
                        <x-input-error :messages="$errors->get('ai_provider')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="gemini_api_key" :value="__('Gemini API Key')" />
                        <x-text-input id="gemini_api_key" type="text" name="gemini_api_key" class="block mt-1 w-full" :value="$satker->gemini_api_key" />
                        <p class="text-xs text-gray-500 mt-1">Isi jika Anda memilih Google Gemini.</p>
                        <x-input-error :messages="$errors->get('gemini_api_key')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="deepseek_api_key" :value="__('Deepseek API Key')" />
                        <x-text-input id="deepseek_api_key" type="text" name="deepseek_api_key" class="block mt-1 w-full" :value="$satker->deepseek_api_key" />
                        <p class="text-xs text-gray-500 mt-1">Isi jika Anda memilih Deepseek.</p>
                        <x-input-error :messages="$errors->get('deepseek_api_key')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
