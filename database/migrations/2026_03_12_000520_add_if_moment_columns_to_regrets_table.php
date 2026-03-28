<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regrets', function (Blueprint $table) {
            // 5枚分のプロンプトとURLをループで追加
            // 既存の ai_letter カラムの後ろに追加します
            $currentAfter = 'ai_letter';
            
            for ($i = 1; $i <= 5; $i++) {
                $table->text("image_prompt_{$i}")->nullable()->after($currentAfter);
                $table->string("image_url_{$i}")->nullable()->after("image_prompt_{$i}");
                
                // 次のカラムをこのカラムの後ろに置くために更新
                $currentAfter = "image_url_{$i}";
            }
        });
    }

    public function down(): void
    {
        Schema::table('regrets', function (Blueprint $table) {
            for ($i = 1; $i <= 5; $i++) {
                $table->dropColumn(["image_prompt_{$i}", "image_url_{$i}"]);
            }
        });
    }
};