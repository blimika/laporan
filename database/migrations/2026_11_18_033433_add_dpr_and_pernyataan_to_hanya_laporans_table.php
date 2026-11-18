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
            $table->boolean('ada_dpr')->default(false)->after('hasil_perjalanan');
            $table->decimal('nilai_dpr', 15, 2)->nullable()->after('ada_dpr');
            $table->foreignId('ppk_pegawai_id')->nullable()->after('nilai_dpr')->constrained('pegawais')->nullOnDelete();
            $table->boolean('ada_pernyataan')->default(false)->after('ppk_pegawai_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hanya_laporans', function (Blueprint $table) {
            $table->dropForeign(['ppk_pegawai_id']);
            $table->dropColumn(['ada_dpr', 'nilai_dpr', 'ppk_pegawai_id', 'ada_pernyataan']);
        });
    }
};
