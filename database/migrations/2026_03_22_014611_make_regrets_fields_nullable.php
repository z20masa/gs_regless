<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('regrets', function (Blueprint $table) {
            // 全てのAI関連・補助カラムを「空文字OK」に変更する
            $table->string('title')->nullable()->change();
            $table->integer('cognitive')->default(0)->nullable()->change();
            $table->integer('emotional')->default(0)->nullable()->change();
            $table->integer('temporal')->default(0)->nullable()->change();
            $table->integer('social')->default(0)->nullable()->change();
            $table->integer('behavioral')->default(0)->nullable()->change();
            $table->integer('existential')->default(0)->nullable()->change();
            $table->text('ai_letter')->nullable()->change();
            $table->string('video_url')->nullable()->change();
            
            for ($i = 1; $i <= 5; $i++) {
                $table->string("image_prompt_$i")->nullable()->change();
                $table->string("image_url_$i")->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regrets', function (Blueprint $table) {
            //
        });
    }
};
