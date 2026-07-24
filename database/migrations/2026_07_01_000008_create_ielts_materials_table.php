<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ielts_materials テーブル（新規）
 * IELTSタイピング練習の教材。Part × Topic × 目標スコアの組み合わせで1レコード
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ielts_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('part')->comment('1 / 2 / 3');
            $table->foreignId('topic_id')
                  ->constrained('ielts_topics')
                  ->cascadeOnDelete();
            $table->string('target_score', 5)
                  ->comment('5.5 / 6.0 / 6.5 / 7.0');
            $table->string('title')->comment('教材タイトル（例: Education × IELTS 6.0）');
            $table->text('prompt')->comment('練習前説明');
            $table->text('text')->comment('タイピング本文（[Question N]/[Answer] 形式）');
            $table->unsignedInteger('xp')->default(100)->comment('完了XP');
            $table->softDeletes();
            $table->timestamps();

            // ユニーク制約は課さない（複数バリエーション対応）
            $table->index(['part', 'topic_id', 'target_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ielts_materials');
    }
};
