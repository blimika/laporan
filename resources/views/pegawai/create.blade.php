<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('pegawais.store') }}">
                        @csrf

                        <!-- NIP -->
                        <div class="mb-4">
                            <x-input-label for="nip" :value="__('NIP')" />
                            <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip')" required autofocus />
                            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                        </div>

                        <!-- Nama -->
                        <div class="mb-4">
                            <x-input-label for="nama" :value="__('Nama Pegawai')" />
                            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required />
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <!-- Jabatan -->
                        <div class="mb-4">
                            <x-input-label for="jabatan" :value="__('Jabatan')" />
                            <x-text-input id="jabatan" class="block mt-1 w-full" type="text" name="jabatan" :value="old('jabatan')" />
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                        </div>

                        <!-- Golongan -->
                        <div class="mb-4">
                            <x-input-label for="golongan" :value="__('Golongan')" />
                            <x-text-input id="golongan" class="block mt-1 w-full" type="text" name="golongan" :value="old('golongan')" />
                            <x-input-error :messages="$errors->get('golongan')" class="mt-2" />
                        </div>

                        <!-- Pangkat -->
                        <div class="mb-4">
                            <x-input-label for="pangkat" :value="__('Pangkat')" />
                            <x-text-input id="pangkat" class="block mt-1 w-full" type="text" name="pangkat" :value="old('pangkat')" />
                            <x-input-error :messages="$errors->get('pangkat')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('pegawais.index') }}">
                                {{ __('Batal') }}
                            </a>

                            <x-primary-button>
                                {{ __('Simpan Pegawai') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
