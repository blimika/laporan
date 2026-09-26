<x-app-layout>
    <x-slot name='header'>
        <h2 class='font-semibold text-xl text-gray-800 leading-tight'>Manajemen Tahun</h2>
    </x-slot>
    <div class='py-12'>
        <div class='max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6'>
            @if(session('success'))
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded'>{{ session('success') }}</div>
            @endif
            <div class='p-4 sm:p-8 bg-white shadow sm:rounded-lg'>
                <form action='{{ route("admin.tahuns.store") }}' method='POST' class='flex space-x-2 mb-4'>
                    @csrf
                    <input type='text' name='tahun' placeholder='tahun' class='border p-2 rounded' required><input type='text' name='aktif' placeholder='aktif' class='border p-2 rounded' required>
                    <button type='submit' class='bg-blue-600 text-white px-4 py-2 rounded'>Tambah</button>
                </form>
                
                <table class='min-w-full bg-white border'>
                    <thead><tr><th class='px-4 py-2 border'>Tahun</th><th class='px-4 py-2 border'>Aktif</th><th class='px-4 py-2 border'>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td class='px-4 py-2 border'>{{ $row->tahun }}</td><td class='px-4 py-2 border'>{{ $row->aktif }}</td>
                            <td class='px-4 py-2 border flex space-x-2'>
                                <form action='{{ route("admin.tahuns.destroy", $row->id) }}' method='POST' onsubmit='return confirm("Yakin hapus?")'>
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
