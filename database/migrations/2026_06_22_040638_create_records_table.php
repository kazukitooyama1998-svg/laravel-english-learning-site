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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            // 💡 One to Many の外部キー設定
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('practice_id')->constrained()->onDelete('cascade');
            
            $table->integer('wpm')->comment('タイピング速度');
            $table->integer('accuracy')->comment('正答率 0-100');
            $table->decimal('clear_time', 8, 2)->comment('クリア時間（秒）');
            $table->timestamps(); // created_at がそのまま「練習日時」になります
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
