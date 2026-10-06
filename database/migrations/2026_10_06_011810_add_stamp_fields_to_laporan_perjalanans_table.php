<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_perjalanans', function (Blueprint $table) {
            $table->boolean('is_stamped')->default(false);
            $table->string('stamp_koordinat')->nullable();
            $table->string('stamp_datetime')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_perjalanans', function (Blueprint $table) {
            $table->dropColumn(['is_stamped', 'stamp_koordinat', 'stamp_datetime']);
        });
    }
};
