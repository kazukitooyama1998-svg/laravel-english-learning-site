<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * quiz_results テーブル（新規）
 * クイズ（スペルクイズ・語彙クイズ）のセッション結果
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('quiz_type', 20)->comment('spelling / vocabulary');
            $table->string('exam_type', 10)->nullable()
                  ->comment('TOEIC / IELTS（NULLは混合）');
            $table->string('level', 5)->nullable()
                  ->comment('対象レベル（例: 700）、NULLは全レベル混合');
            $table->unsignedInteger('total_questions')->comment('出題数');
            $table->unsignedInteger('correct_count')->comment('正解数');
            $table->unsignedInteger('xp_gained')->default(0)->comment('獲得XP');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
