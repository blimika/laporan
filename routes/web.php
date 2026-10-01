<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $stats = [
        'pegawai' => \App\Models\Pegawai::count(),
        'anggaran' => \App\Models\Anggaran::count(),
        'surat_tugas' => \App\Models\SuratTugas::count(),
        'laporan' => \App\Models\LaporanPerjalanan::count(),
    ];
    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pegawais/template', [\App\Http\Controllers\PegawaiController::class, 'downloadTemplate'])->name('pegawais.template');
    Route::post('/pegawais/import', [\App\Http\Controllers\PegawaiController::class, 'import'])->name('pegawais.import');
    Route::resource('pegawais', \App\Http\Controllers\PegawaiController::class);
    Route::get('/anggarans/template', [\App\Http\Controllers\AnggaranController::class, 'downloadTemplate'])->name('anggarans.template');
    Route::post('/anggarans/import', [\App\Http\Controllers\AnggaranController::class, 'import'])->name('anggarans.import');
    Route::resource('anggarans', \App\Http\Controllers\AnggaranController::class);
    Route::resource('surat-tugas', \App\Http\Controllers\SuratTugasController::class);
    
    Route::get('spd/create/{suratTugas}', [\App\Http\Controllers\SpdController::class, 'create'])->name('spd.create');
    Route::post('spd/store/{suratTugas}', [\App\Http\Controllers\SpdController::class, 'store'])->name('spd.store');
    Route::get('spd/edit/{spd}', [\App\Http\Controllers\SpdController::class, 'edit'])->name('spd.edit');
    Route::put('spd/update/{spd}', [\App\Http\Controllers\SpdController::class, 'update'])->name('spd.update');

    Route::get('export/surat-tugas/{suratTugas}', [\App\Http\Controllers\SuratTugasController::class, 'exportPdf'])->name('export.surat-tugas');
    Route::get('export/spd/{spd}', [\App\Http\Controllers\SpdController::class, 'exportPdf'])->name('export.spd');
    Route::get('export/dpr/{spd}', [\App\Http\Controllers\SpdController::class, 'exportDpr'])->name('export.dpr');
    Route::get('export/pernyataan/{spd}', [\App\Http\Controllers\SpdController::class, 'exportPernyataan'])->name('export.pernyataan');
    Route::post('laporan/generate', [\App\Http\Controllers\LaporanPerjalananController::class, 'generate'])->name('laporan.generate');
    Route::resource('laporan', \App\Http\Controllers\LaporanPerjalananController::class);
    Route::get('export/laporan/{laporan}', [\App\Http\Controllers\LaporanPerjalananController::class, 'exportPdf'])->name('export.laporan');

    Route::post('hanya-laporan/generate', [\App\Http\Controllers\HanyaLaporanController::class, 'generate'])->name('hanya-laporan.generate');
    Route::get('export/hanya-laporan/{hanyaLaporan}', [\App\Http\Controllers\HanyaLaporanController::class, 'exportPdf'])->name('export.hanya-laporan');
    Route::resource('hanya-laporan', \App\Http\Controllers\HanyaLaporanController::class);

    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::resource('satkers', \App\Http\Controllers\SatkerController::class);
        Route::resource('tahuns', \App\Http\Controllers\TahunController::class);
        Route::get('/ai-provider', [\App\Http\Controllers\AiProviderController::class, 'edit'])->name('ai_provider.edit');
        Route::put('/ai-provider', [\App\Http\Controllers\AiProviderController::class, 'update'])->name('ai_provider.update');
    });
});

require __DIR__.'/auth.php';
