<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_perjalanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_tugas_id')->constrained('surat_tugas')->cascadeOnDelete();
            $table->date('tgl_laporan');
            $table->date('tgl_perjalanan');
            $table->string('lokasi_perjalanan');
            $table->string('tujuan_perjalanan');
            $table->string('pegawai_ditemui');
            $table->string('kategori');
            $table->text('kendala_ditemui')->nullable();
            $table->text('hasil_perjalanan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_perjalanans');
    }
};
