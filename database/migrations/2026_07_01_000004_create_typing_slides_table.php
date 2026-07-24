<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * typing_slides テーブル（新規）
 * タイピング教材ごとのスライドコンテンツ（S17: タイピング学習スライドで使用）
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typing_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')
                  ->constrained('typing_materials')
                  ->cascadeOnDelete();
            $table->unsignedInteger('step_number')->comment('スライド内の順番（1始まり）');
            $table->string('slide_type', 30)
                  ->comment('vocabulary / grammar / point');
            $table->string('title')->comment('スライドタイトル');
            $table->text('content')->comment('スライド本文（HTMLまたはMarkdown）');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['material_id', 'step_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typing_slides');
    }
};
