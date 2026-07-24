<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * user_word_progress テーブル（新規）
 * 単語ごとの学習進捗と正誤履歴。フラッシュカード・スペル練習で更新
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_word_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('word_id')
                  ->constrained('vocabulary_words')
                  ->cascadeOnDelete();
            $table->unsignedTinyInteger('status')->default(0)
                  ->comment('0:未学習 / 1:学習中 / 2:覚えた');
            $table->unsignedInteger('correct_count')->default(0)->comment('正解回数');
            $table->unsignedInteger('incorrect_count')->default(0)->comment('不正解回数');
            $table->timestamp('last_reviewed_at')->nullable()->comment('最後に学習した日時');
            $table->timestamps();

            $table->unique(['user_id', 'word_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_word_progress');
    }
};
