<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ielts_slides テーブル（新規）
 * IELTS学習スライド。Part × Topic × 目標スコアの組み合わせで内容が異なる
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ielts_slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('part')->comment('IELTS Speaking のPart番号（1 / 2 / 3）');
            $table->foreignId('topic_id')
                  ->constrained('ielts_topics')
                  ->cascadeOnDelete();
            $table->string('target_score', 5)
                  ->comment('目標スコア（5.5 / 6.0 / 6.5 / 7.0）');
            $table->unsignedInteger('step_number')->comment('スライド内の順番（1始まり）');
            $table->string('slide_type', 30)
                  ->comment('vocabulary / grammar / expression / tip');
            $table->string('title')->comment('スライドタイトル');
            $table->text('content')->comment('スライド本文（HTMLまたはMarkdown）');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['part', 'topic_id', 'target_score', 'step_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ielts_slides');
    }
};
