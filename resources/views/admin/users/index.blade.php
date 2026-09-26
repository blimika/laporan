<x-app-layout>
    <x-slot name='header'>
        <h2 class='font-semibold text-xl text-gray-800 leading-tight'>Manajemen User</h2>
    </x-slot>
    <div class='py-12'>
        <div class='max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6'>
            @if(session('success'))
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded'>{{ session('success') }}</div>
            @endif
            <div class='p-4 sm:p-8 bg-white shadow sm:rounded-lg'>
                <form action='{{ route("admin.users.store") }}' method='POST' class='flex flex-wrap gap-2 mb-4'>
                    @csrf
                    <input type='text' name='name' placeholder='Nama' class='border p-2 rounded' required>
                    <input type='text' name='username' placeholder='Username' class='border p-2 rounded' required>
                    <input type='text' name='email' placeholder='Email' class='border p-2 rounded' required>
                    <select name='role' class='border p-2 rounded' required>
                        <option value='user'>User</option>
                        <option value='admin'>Admin</option>
                        @if(auth()->user()->role === 'super')
                        <option value='super'>Super Admin</option>
                        @endif
                    </select>
                    @if(auth()->user()->role === 'super')
                    <select name='satker_id' class='border p-2 rounded'>
                        <option value=''>-- Pilih Satker --</option>
                        @foreach(\App\Models\Satker::all() as $s)
                        <option value='{{ $s->id }}'>{{ $s->kode }} - {{ $s->nama }}</option>
                        @endforeach
                    </select>
                    @endif
                    <input type='password' name='password' placeholder='Password (Kosongkan jika edit)' class='border p-2 rounded'>
                    <button type='submit' class='bg-blue-600 text-white px-4 py-2 rounded'>Tambah</button>
                </form>
                
                <table class='min-w-full bg-white border'>
                    <thead><tr><th class='px-4 py-2 border'>Name</th><th class='px-4 py-2 border'>Username</th><th class='px-4 py-2 border'>Email</th><th class='px-4 py-2 border'>Role</th><th class='px-4 py-2 border'>Satker</th><th class='px-4 py-2 border'>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td class='px-4 py-2 border'>{{ $row->name }}</td><td class='px-4 py-2 border'>{{ $row->username }}</td><td class='px-4 py-2 border'>{{ $row->email }}</td><td class='px-4 py-2 border'>{{ $row->role }}</td>
                            <td class='px-4 py-2 border'>{{ $row->satker ? $row->satker->nama : '-' }}</td>
                            <td class='px-4 py-2 border flex space-x-2'>
                                <form action='{{ route("admin.users.destroy", $row->id) }}' method='POST' onsubmit='return confirm("Yakin hapus?")'>
                                    @csrf @method('DELETE')
                                    <button class='text-red-600'>Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
