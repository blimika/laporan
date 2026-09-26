<?php
$controllers = [
    'User' => ['fields' => ['name', 'username', 'email', 'role'], 'label' => 'User'],
    'Satker' => ['fields' => ['kode', 'nama'], 'label' => 'Satker'],
    'Tahun' => ['fields' => ['tahun', 'aktif'], 'label' => 'Tahun'],
];

foreach ($controllers as $model => $meta) {
    $lower = strtolower($model);
    $plural = $lower . 's';
    
    // Controller
    $controllerStr = "<?php
namespace App\Http\Controllers;

use App\Models\\$model;
use Illuminate\Http\Request;

class {$model}Controller extends Controller
{
    public function index() {
        \$data = $model::latest()->get();
        return view('admin.{$plural}.index', compact('data'));
    }
    
    public function store(Request \$request) {
        $model::create(\$request->all());
        return back()->with('success', 'Berhasil ditambahkan');
    }
    
    public function update(Request \$request, \$id) {
        \$record = $model::findOrFail(\$id);
        if (\$request->has('password') && \$request->password != '') {
            \$data = \$request->except('password');
            \$data['password'] = bcrypt(\$request->password);
            \$record->update(\$data);
        } else {
            \$record->update(\$request->except('password'));
        }
        return back()->with('success', 'Berhasil diupdate');
    }
    
    public function destroy(\$id) {
        $model::findOrFail(\$id)->delete();
        return back()->with('success', 'Berhasil dihapus');
    }
}
";
    file_put_contents("app/Http/Controllers/{$model}Controller.php", $controllerStr);

    // View
    @mkdir("resources/views/admin");
    @mkdir("resources/views/admin/{$plural}");
    
    $ths = implode('', array_map(fn($f) => "<th class='px-4 py-2 border'>".ucfirst($f)."</th>", $meta['fields']));
    $tds = implode('', array_map(fn($f) => "<td class='px-4 py-2 border'>{{ \$row->{$f} }}</td>", $meta['fields']));
    $inputs = implode('', array_map(fn($f) => "<input type='text' name='{$f}' placeholder='{$f}' class='border p-2 rounded' required>", $meta['fields']));
    if($model == 'User') {
        $inputs .= "<input type='password' name='password' placeholder='Password (Kosongkan jika edit)' class='border p-2 rounded'>";
    }

    $viewStr = "<x-app-layout>
    <x-slot name='header'>
        <h2 class='font-semibold text-xl text-gray-800 leading-tight'>Manajemen {$meta['label']}</h2>
    </x-slot>
    <div class='py-12'>
        <div class='max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6'>
            @if(session('success'))
                <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded'>{{ session('success') }}</div>
            @endif
            <div class='p-4 sm:p-8 bg-white shadow sm:rounded-lg'>
                <form action='{{ route(\"admin.{$plural}.store\") }}' method='POST' class='flex space-x-2 mb-4'>
                    @csrf
                    $inputs
                    <button type='submit' class='bg-blue-600 text-white px-4 py-2 rounded'>Tambah</button>
                </form>
                
                <table class='min-w-full bg-white border'>
                    <thead><tr>$ths<th class='px-4 py-2 border'>Aksi</th></tr></thead>
                    <tbody>
                        @foreach(\$data as \$row)
                        <tr>
                            $tds
                            <td class='px-4 py-2 border flex space-x-2'>
                                <form action='{{ route(\"admin.{$plural}.destroy\", \$row->id) }}' method='POST' onsubmit='return confirm(\"Yakin hapus?\")'>
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
</x-app-layout>";
    file_put_contents("resources/views/admin/{$plural}/index.blade.php", $viewStr);
}
echo "CRUD Generated!";
