<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hanya_laporans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            $table->string('nip_pegawai');
            $table->string('nomor_st');
            $table->string('nomor_spd')->nullable();
            $table->date('tgl_laporan');
            $table->date('tgl_perjalanan');
            $table->string('lokasi_perjalanan');
            $table->string('tujuan_perjalanan');
            $table->string('pegawai_ditemui');
            $table->string('kategori');
            $table->text('kendala_ditemui')->nullable();
            $table->text('hasil_perjalanan')->nullable();
            $table->foreignId('satker_id')->nullable()->constrained('satkers')->cascadeOnDelete();
            $table->foreignId('tahun_id')->nullable()->constrained('tahuns')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hanya_laporans');
    }
};
