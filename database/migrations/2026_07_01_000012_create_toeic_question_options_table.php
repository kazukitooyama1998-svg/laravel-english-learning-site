<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * toeic_question_options テーブル（新規）
 * TOEIC選択肢（1問につき4件）
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toeic_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                  ->constrained('toeic_questions')
                  ->cascadeOnDelete();
            $table->char('label', 1)->comment('A / B / C / D');
            $table->text('option_text')->comment('選択肢テキスト');
            $table->boolean('is_correct')->default(false)
                  ->comment('正解フラグ（1問につき1件のみtrue）');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toeic_question_options');
    }
};
