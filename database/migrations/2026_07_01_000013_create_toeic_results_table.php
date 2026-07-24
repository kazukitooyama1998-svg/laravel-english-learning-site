<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * toeic_results テーブル（新規）
 * TOEIC練習セッション（1Partを通して回答した1回分）の結果サマリー
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toeic_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->unsignedTinyInteger('part')->comment('5 / 6 / 7');
            $table->unsignedInteger('total_questions')->comment('セッション内の総問題数');
            $table->unsignedInteger('correct_count')->comment('正解数');
            $table->unsignedInteger('xp_gained')->default(0)->comment('このセッションで獲得したXP');
            $table->timestamp('completed_at')->comment('セッション完了日時');
            $table->timestamps();

            $table->index(['user_id', 'part', 'completed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toeic_results');
    }
};
