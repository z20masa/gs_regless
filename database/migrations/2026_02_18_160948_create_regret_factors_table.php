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
        Schema::create('regret_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regret_id')->constrained()->onDelete('cascade'); // 後悔データに紐付け
            $table->integer('cognitive'); // 認知的
            $table->integer('emotional'); // 感情的
            $table->integer('temporal'); // 時間的
            $table->integer('social'); // 社会的
            $table->boolean('behavioral'); // 行動（二者択一）
            $table->integer('existential'); // 実存的
            $table->text('self_message')->nullable(); // 昔の自分へのメッセージ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regret_factors');
    }
};
