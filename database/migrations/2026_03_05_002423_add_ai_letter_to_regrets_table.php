<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    
 public function up(): void
{
    Schema::table('regrets', function (Blueprint $table) {
        // ここにカラム追加の命令を書きます
        $table->text('ai_letter')->nullable()->after('content'); 
    });
}


 public function down(): void
{
    Schema::table('regrets', function (Blueprint $table) {
        // ロールバック（元に戻す）時のために削除命令も書いておくと安全です
        $table->dropColumn('ai_letter');
    });
}
};
