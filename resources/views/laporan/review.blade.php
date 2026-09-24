<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review & Revisi Hasil AI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <p class="text-blue-700"><strong>AI telah menyusun narasi.</strong> Silakan periksa, edit jika perlu, lalu klik Simpan Laporan.</p>
                    </div>
                    
                    <form method="POST" action="{{ route('laporan.store') }}">
                        @csrf
                        
                        <!-- Hidden Inputs from Previous Step -->
                        @foreach($data as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <div class="mb-4">
                            <x-input-label for="hasil_perjalanan" :value="__('Narasi Laporan (Bisa Anda Edit)')" />
                            <textarea id="hasil_perjalanan" name="hasil_perjalanan" class="block mt-2 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" rows="12" required>{{ old('hasil_perjalanan', $hasil_ai) }}</textarea>
                            <x-input-error :messages="$errors->get('hasil_perjalanan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <!-- Back button will lose the AI text, normally we'd do a wizard, but this is simple -->
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md mr-4" href="{{ route('laporan.create') }}">
                                Batal (Ulangi dari Awal)
                            </a>
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Finalisasi & Simpan Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
