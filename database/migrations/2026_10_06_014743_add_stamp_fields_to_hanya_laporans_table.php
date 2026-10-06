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
        Schema::table('hanya_laporans', function (Blueprint $table) {
            $table->boolean('is_stamped')->default(false);
            $table->string('stamp_koordinat')->nullable();
            $table->dateTime('stamp_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hanya_laporans', function (Blueprint $table) {
            $table->dropColumn(['is_stamped', 'stamp_koordinat', 'stamp_datetime']);
        });
    }
};
