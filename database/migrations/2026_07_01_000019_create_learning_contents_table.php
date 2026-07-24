<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * learning_contents テーブル（新規）
 * 試験概要・学習ストラテジーの静的コンテンツ。管理者がCRUD可能
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_contents', function (Blueprint $table) {
            $table->id();
            $table->string('content_type', 20)
                  ->comment('overview（試験概要）/ strategy（学習戦略）');
            $table->string('exam_type', 10)->comment('IELTS / TOEIC');
            $table->string('target_level', 5)->nullable()
                  ->comment('600/700/5.5 等（overviewはNULL）');
            $table->string('title')->comment('記事タイトル');
            $table->longText('body')->comment('本文（HTML）');
            $table->integer('sort_order')->default(0)->comment('一覧での表示順');
            $table->boolean('is_published')->default(true)->comment('公開フラグ');
            $table->timestamps();

            $table->index(['content_type', 'exam_type', 'target_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_contents');
    }
};
