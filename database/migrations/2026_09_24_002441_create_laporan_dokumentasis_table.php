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
        Schema::create('laporan_dokumentasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan_perjalanans')->cascadeOnDelete();
            $table->string('file_path');
            $table->text('keterangan_foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_dokumentasis');
    }
};
