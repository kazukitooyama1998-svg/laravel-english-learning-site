<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * typing_categories テーブル
 * タイピング教材のカテゴリ（旧: categories を拡張・改名）
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typing_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('表示名（例: Business English）');
            $table->string('slug', 100)->unique()->comment('URLスラッグ（例: business-english）');
            $table->string('description', 255)->nullable()->comment('カテゴリの説明');
            $table->integer('sort_order')->default(0)->comment('表示順');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typing_categories');
    }
};
