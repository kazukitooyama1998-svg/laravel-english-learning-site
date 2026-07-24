<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * typing_materials テーブル
 * タイピング練習の教材本体（旧: practices を拡張・改名）
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typing_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained('typing_categories')
                  ->cascadeOnDelete();
            $table->string('title')->comment('教材タイトル');
            $table->string('level', 50)->comment('難易度ラベル（例: Intermediate）');
            $table->text('prompt')->comment('練習前に表示する説明文');
            $table->text('text')->comment('タイピング本文（[Question N]/[Answer] 形式）');
            $table->unsignedInteger('xp')->default(100)->comment('完了時に付与するXP');
            $table->integer('sort_order')->default(0)->comment('表示順');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typing_materials');
    }
};
