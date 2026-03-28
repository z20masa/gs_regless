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
        Schema::table('regrets', function (Blueprint $table) {
            // video_url カラムを追加（空の状態も許容する nullable）
            $table->text('video_url')->nullable()->after('ai_letter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regrets', function (Blueprint $table) {
            // ロールバック（元に戻す）時にカラムを削除する
            $table->dropColumn('video_url');
        });
    }
};
