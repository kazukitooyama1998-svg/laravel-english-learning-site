<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * user_word_favorites テーブル（新規）
 * ユーザーがお気に入り登録した単語
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_word_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('word_id')
                  ->constrained('vocabulary_words')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'word_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_word_favorites');
    }
};
