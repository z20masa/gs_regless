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
        Schema::create('daily_insights', function (Blueprint $table) {
            $table->id();
            $table->date('target_date')->index();
            $table->integer('type');              // これが必要！
            $table->string('title');             // これが必要！
            $table->text('content');
            $table->timestamps();
            $table->unique(['target_date', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_insights');
    }
};
