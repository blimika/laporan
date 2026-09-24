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
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id();
            $table->string('mak')->unique();
            $table->string('program_kode')->nullable();
            $table->string('program_uraian')->nullable();
            $table->string('kegiatan_kode')->nullable();
            $table->string('kegiatan_uraian')->nullable();
            $table->string('output_kode')->nullable();
            $table->string('output_uraian')->nullable();
            $table->string('suboutput_kode')->nullable();
            $table->string('suboutput_uraian')->nullable();
            $table->string('komponen_kode')->nullable();
            $table->string('komponen_uraian')->nullable();
            $table->string('subkomponen_kode')->nullable();
            $table->string('subkomponen_uraian')->nullable();
            $table->string('akun_kode')->nullable();
            $table->string('akun_uraian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};
