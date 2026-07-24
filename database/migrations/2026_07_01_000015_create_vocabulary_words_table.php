<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * vocabulary_words テーブル（新規）
 * 英単語マスターテーブル。TOEIC・IELTS 各レベルの単語を一元管理
 *
 * exam_type: 'TOEIC' / 'IELTS'
 * level    : TOEIC → '600'/'700'/'800'/'900' / IELTS → '5.5'/'6.0'/'6.5'/'7.0'
 *
 * URLパラメータ({level})とDBカラムの対応は config/english.php の vocabulary_levels で管理
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vocabulary_words', function (Blueprint $table) {
            $table->id();
            $table->string('word', 100)->comment('英単語');
            $table->string('part_of_speech', 20)
                  ->comment('品詞（noun / verb / adjective / adverb 等）');
            $table->text('meaning_ja')->comment('日本語の意味');
            $table->text('meaning_en')->nullable()->comment('英語の定義文');
            $table->text('example_sentence')->nullable()->comment('例文（英語）');
            $table->text('example_sentence_ja')->nullable()->comment('例文（日本語訳）');
            $table->string('exam_type', 10)->comment('TOEIC / IELTS');
            $table->string('level', 5)
                  ->comment('TOEIC: 600/700/800/900 / IELTS: 5.5/6.0/6.5/7.0');
            $table->integer('sort_order')->default(0)->comment('学習順（シーダーで設定）');
            $table->timestamps();

            // レベル別一覧取得用（最もよく使うクエリ）
            $table->index(['exam_type', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vocabulary_words');
    }
};
