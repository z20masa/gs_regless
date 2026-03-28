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
        Schema::create('regrets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user_idもここで定義
            $table->string('title');
            $table->text('content');
            $table->string('nickname')->nullable(); // 追加
            $table->string('age_group')->nullable(); // 追加
            $table->string('gender')->nullable();    // 追加
            $table->string('category')->nullable();  // 追加
            // 6因子のスコア
            $table->integer('cognitive');
            $table->integer('emotional');
            $table->integer('temporal');
            $table->integer('social');
            $table->integer('behavioral');
            $table->integer('existential');
            $table->text('message_to_others')->nullable(); // ← これがあるか確認！
            $table->boolean('is_burned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regrets');
    }
};
