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
        $tables = ['pegawais', 'anggarans', 'surat_tugas', 'laporan_perjalanans'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table_bp) {
                $table_bp->foreignId('satker_id')->nullable()->constrained('satkers')->onDelete('cascade');
                $table_bp->foreignId('tahun_id')->nullable()->constrained('tahuns')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['pegawais', 'anggarans', 'surat_tugas', 'laporan_perjalanans'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table_bp) {
                $table_bp->dropForeign(['satker_id']);
                $table_bp->dropForeign(['tahun_id']);
                $table_bp->dropColumn(['satker_id', 'tahun_id']);
            });
        }
    }
};
