<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hanya_laporan_dokumentasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hanya_laporan_id')->constrained('hanya_laporans')->cascadeOnDelete();
            $table->string('file_path');
            $table->text('keterangan_foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hanya_laporan_dokumentasis');
    }
};
