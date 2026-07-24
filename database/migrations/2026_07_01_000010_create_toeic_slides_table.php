<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * toeic_slides テーブル（新規）
 * TOEIC学習スライドのコンテンツ。Part番号でグループ化
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toeic_slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('part')->comment('5 / 6 / 7（将来的にはPart1〜4も対応可）');
            $table->unsignedInteger('step_number')->comment('スライド内の順番（1始まり）');
            $table->string('slide_type', 30)
                  ->comment('vocabulary / grammar / idiom / strategy / explanation');
            $table->string('title')->comment('スライドタイトル');
            $table->text('content')->comment('スライド本文（HTMLまたはMarkdown）');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['part', 'step_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toeic_slides');
    }
};
