<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Perbarui Data User</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Kembali</a>
                </div>
                
                <div class="p-4 sm:p-6 bg-white">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $user->name) }}" required />
                            </div>
                            <div>
                                <x-input-label for="username" :value="__('Username')" />
                                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" value="{{ old('username', $user->username) }}" required />
                            </div>
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                            </div>
                            <div>
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    @if(auth()->user()->role === 'super')
                                    <option value="super" {{ old('role', $user->role) === 'super' ? 'selected' : '' }}>Super Admin</option>
                                    @endif
                                </select>
                            </div>
                            
                            @if(auth()->user()->role === 'super')
                            <div>
                                <x-input-label for="satker_id" :value="__('Satker (Satuan Kerja)')" />
                                <select id="satker_id" name="satker_id" class="block mt-1 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih Satker --</option>
                                    @foreach(\App\Models\Satker::all() as $s)
                                    <option value="{{ $s->id }}" {{ old('satker_id', $user->satker_id) == $s->id ? 'selected' : '' }}>{{ $s->kode }} - {{ $s->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div>
                                <x-input-label for="password" :value="__('Password (Opsional)')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
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
