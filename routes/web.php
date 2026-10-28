<?php

use App\Http\Controllers\AiProviderController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\HanyaLaporanController;
use App\Http\Controllers\LaporanPerjalananController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\SpdController;
use App\Http\Controllers\SuratTugasController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\UserController;
use App\Models\Anggaran;
use App\Models\HanyaLaporan;
use App\Models\LaporanPerjalanan;
use App\Models\Pegawai;
use App\Models\SuratTugas;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $laporanPerjalanan = LaporanPerjalanan::count();
    $hanyaLaporan = HanyaLaporan::count();

    $stats = [
        'pegawai' => Pegawai::count(),
        'anggaran' => Anggaran::count(),
        'surat_tugas' => SuratTugas::count(),
        'laporan' => $laporanPerjalanan + $hanyaLaporan,
        'laporan_perjalanan' => $laporanPerjalanan,
        'hanya_laporan' => $hanyaLaporan,
    ];

    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('test-gd', function () {
    return function_exists('imagecreatefromjpeg') ? 'GD YES' : 'GD NO';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pegawais/template', [PegawaiController::class, 'downloadTemplate'])->name('pegawais.template');
    Route::post('/pegawais/import', [PegawaiController::class, 'import'])->name('pegawais.import');
    Route::resource('pegawais', PegawaiController::class);
    Route::get('/anggarans/template', [AnggaranController::class, 'downloadTemplate'])->name('anggarans.template');
    Route::post('/anggarans/import', [AnggaranController::class, 'import'])->name('anggarans.import');
    Route::resource('anggarans', AnggaranController::class);
    Route::resource('surat-tugas', SuratTugasController::class);

    Route::get('spd/create/{suratTugas}', [SpdController::class, 'create'])->name('spd.create');
    Route::post('spd/store/{suratTugas}', [SpdController::class, 'store'])->name('spd.store');
    Route::get('spd/edit/{spd}', [SpdController::class, 'edit'])->name('spd.edit');
    Route::put('spd/update/{spd}', [SpdController::class, 'update'])->name('spd.update');

    Route::get('export/surat-tugas/{suratTugas}', [SuratTugasController::class, 'exportPdf'])->name('export.surat-tugas');
    Route::get('export/spd/{spd}', [SpdController::class, 'exportPdf'])->name('export.spd');
    Route::get('export/dpr/{spd}', [SpdController::class, 'exportDpr'])->name('export.dpr');
    Route::get('export/pernyataan/{spd}', [SpdController::class, 'exportPernyataan'])->name('export.pernyataan');
    Route::post('laporan/generate', [LaporanPerjalananController::class, 'generate'])->name('laporan.generate');
    Route::resource('laporan', LaporanPerjalananController::class);
    Route::get('export/laporan/{laporan}', [LaporanPerjalananController::class, 'exportPdf'])->name('export.laporan');
    Route::get('export/laporan/{laporan}/word', [LaporanPerjalananController::class, 'exportWord'])->name('export.laporan.word');

    Route::post('hanya-laporan/generate', [HanyaLaporanController::class, 'generate'])->name('hanya-laporan.generate');
    Route::get('export/hanya-laporan/{hanyaLaporan}', [HanyaLaporanController::class, 'exportPdf'])->name('export.hanya-laporan');
    Route::get('export/hanya-laporan/{hanyaLaporan}/word', [HanyaLaporanController::class, 'exportWord'])->name('export.hanya-laporan.word');
    Route::get('test-gd', function () {
        return function_exists('imagecreatefromjpeg') ? 'GD YES' : 'GD NO';
    });
    Route::resource('hanya-laporan', HanyaLaporanController::class);

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('satkers', SatkerController::class);
        Route::resource('tahuns', TahunController::class);
        Route::get('/ai-provider', [AiProviderController::class, 'edit'])->name('ai_provider.edit');
        Route::put('/ai-provider', [AiProviderController::class, 'update'])->name('ai_provider.update');
    });
});

require __DIR__.'/auth.php';
