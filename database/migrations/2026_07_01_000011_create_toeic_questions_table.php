<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * toeic_questions テーブル（新規）
 * TOEIC選択問題本体
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toeic_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('part')->comment('5 / 6 / 7');
            $table->text('question_text')->comment('問題文');
            $table->text('explanation')->nullable()->comment('解説文（正解後に表示）');
            $table->string('difficulty', 10)->nullable()
                  ->comment('easy / medium / hard');
            $table->unsignedInteger('xp')->default(50)->comment('正解時に付与するXP');
            $table->integer('sort_order')->default(0)->comment('出題順');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['part', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toeic_questions');
    }
};
