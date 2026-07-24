<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * toeic_passages テーブル（新規）
 * Part 6 / 7 で複数の設問が共有する長文（メール・記事・広告など）を保持する
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toeic_passages', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('part')->comment('6 / 7');
            $table->string('passage_type', 10)->comment('single / double / triple（文書数）');
            $table->string('title')->comment('文書セットのタイトル（一覧表示用）');
            $table->json('documents')->comment('文書配列 [{heading, body}, ...]');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['part', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toeic_passages');
    }
};
