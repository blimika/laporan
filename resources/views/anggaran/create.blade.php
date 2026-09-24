<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Anggaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('anggarans.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Tahun -->
                            <div>
                                <x-input-label for="tahun" :value="__('Tahun Anggaran')" />
                                <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" :value="old('tahun', date('Y'))" required autofocus />
                                <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                            </div>
                            
                            <!-- MAK -->
                            <div>
                                <x-input-label for="mak" :value="__('MAK (Mata Anggaran Kegiatan)')" />
                                <x-text-input id="mak" class="block mt-1 w-full" type="text" name="mak" :value="old('mak')" required placeholder="Contoh: 054.01.WA.xxxx" />
                                <x-input-error :messages="$errors->get('mak')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Program -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="program_kode" :value="__('Kode Program (Opsional)')" />
                                <x-text-input id="program_kode" class="block mt-1 w-full" type="text" name="program_kode" :value="old('program_kode')" />
                            </div>
                            <div>
                                <x-input-label for="program_uraian" :value="__('Uraian Program (Opsional)')" />
                                <x-text-input id="program_uraian" class="block mt-1 w-full" type="text" name="program_uraian" :value="old('program_uraian')" />
                            </div>
                        </div>

                        <!-- Kegiatan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="kegiatan_kode" :value="__('Kode Kegiatan (Opsional)')" />
                                <x-text-input id="kegiatan_kode" class="block mt-1 w-full" type="text" name="kegiatan_kode" :value="old('kegiatan_kode')" />
                            </div>
                            <div>
                                <x-input-label for="kegiatan_uraian" :value="__('Uraian Kegiatan (Opsional)')" />
                                <x-text-input id="kegiatan_uraian" class="block mt-1 w-full" type="text" name="kegiatan_uraian" :value="old('kegiatan_uraian')" />
                            </div>
                        </div>

                        <!-- Output -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="output_kode" :value="__('Kode Output (Opsional)')" />
                                <x-text-input id="output_kode" class="block mt-1 w-full" type="text" name="output_kode" :value="old('output_kode')" />
                            </div>
                            <div>
                                <x-input-label for="output_uraian" :value="__('Uraian Output (Opsional)')" />
                                <x-text-input id="output_uraian" class="block mt-1 w-full" type="text" name="output_uraian" :value="old('output_uraian')" />
                            </div>
                        </div>

                        <!-- Suboutput -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="suboutput_kode" :value="__('Kode Suboutput (Opsional)')" />
                                <x-text-input id="suboutput_kode" class="block mt-1 w-full" type="text" name="suboutput_kode" :value="old('suboutput_kode')" />
                            </div>
                            <div>
                                <x-input-label for="suboutput_uraian" :value="__('Uraian Suboutput (Opsional)')" />
                                <x-text-input id="suboutput_uraian" class="block mt-1 w-full" type="text" name="suboutput_uraian" :value="old('suboutput_uraian')" />
                            </div>
                        </div>

                        <!-- Komponen -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="komponen_kode" :value="__('Kode Komponen (Opsional)')" />
                                <x-text-input id="komponen_kode" class="block mt-1 w-full" type="text" name="komponen_kode" :value="old('komponen_kode')" />
                            </div>
                            <div>
                                <x-input-label for="komponen_uraian" :value="__('Uraian Komponen (Opsional)')" />
                                <x-text-input id="komponen_uraian" class="block mt-1 w-full" type="text" name="komponen_uraian" :value="old('komponen_uraian')" />
                            </div>
                        </div>

                        <!-- Subkomponen -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="subkomponen_kode" :value="__('Kode Subkomponen (Opsional)')" />
                                <x-text-input id="subkomponen_kode" class="block mt-1 w-full" type="text" name="subkomponen_kode" :value="old('subkomponen_kode')" />
                            </div>
                            <div>
                                <x-input-label for="subkomponen_uraian" :value="__('Uraian Subkomponen (Opsional)')" />
                                <x-text-input id="subkomponen_uraian" class="block mt-1 w-full" type="text" name="subkomponen_uraian" :value="old('subkomponen_uraian')" />
                            </div>
                        </div>

                        <!-- Akun -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="akun_kode" :value="__('Kode Akun (Opsional)')" />
                                <x-text-input id="akun_kode" class="block mt-1 w-full" type="text" name="akun_kode" :value="old('akun_kode')" />
                            </div>
                            <div>
                                <x-input-label for="akun_uraian" :value="__('Uraian Akun (Opsional)')" />
                                <x-text-input id="akun_uraian" class="block mt-1 w-full" type="text" name="akun_uraian" :value="old('akun_uraian')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('anggarans.index') }}">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Simpan Anggaran') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
