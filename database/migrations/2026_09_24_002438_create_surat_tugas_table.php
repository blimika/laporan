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
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->string('tujuan');
            $table->text('tugas');
            $table->date('tgl_surat');
            $table->date('tgl_berangkat');
            $table->date('tgl_kembali');
            $table->foreignId('pembebanan_anggaran_id')->constrained('anggarans')->cascadeOnDelete();
            $table->foreignId('kepala_pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};
