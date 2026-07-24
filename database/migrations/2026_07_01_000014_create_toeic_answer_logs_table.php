<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * toeic_answer_logs テーブル（新規）
 * TOEIC問題1問1問の回答記録。復習・間違い問題一覧の表示に使用
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toeic_answer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_id')
                  ->constrained('toeic_results')
                  ->cascadeOnDelete()
                  ->comment('親セッション');
            $table->foreignId('question_id')
                  ->constrained('toeic_questions')
                  ->cascadeOnDelete();
            $table->foreignId('selected_option_id')
                  ->nullable()
                  ->constrained('toeic_question_options')
                  ->nullOnDelete()
                  ->comment('選択した選択肢（未回答はNULL）');
            $table->boolean('is_correct')->default(false)->comment('正解かどうか');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toeic_answer_logs');
    }
};
