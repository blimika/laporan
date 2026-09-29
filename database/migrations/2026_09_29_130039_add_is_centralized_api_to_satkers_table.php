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
        Schema::table('satkers', function (Blueprint $table) {
            $table->boolean('is_centralized_api')->default(true)->after('deepseek_api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('satkers', function (Blueprint $table) {
            $table->dropColumn('is_centralized_api');
        });
    }
};
