<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * study_logs テーブル（新規）
 * 全学習活動の統合ログ。学習履歴表示・連続学習日数算出・ランキング集計に使用
 *
 * activity_type の値:
 *   typing     : 単独タイピング練習（activity_id → typing_records.id）
 *   ielts      : IELTSタイピング練習（activity_id → ielts_records.id）
 *   toeic      : TOEIC問題セッション（activity_id → toeic_results.id）
 *   vocabulary : フラッシュカード・スペル練習（activity_id は NULL）
 *   quiz       : クイズ（activity_id → quiz_results.id）
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('activity_type', 30)->comment('typing / ielts / toeic / vocabulary / quiz');
            $table->unsignedBigInteger('activity_id')->nullable()
                  ->comment('関連レコードのID（activity_typeによって参照先が異なる）');
            $table->unsignedInteger('xp_gained')->default(0)->comment('この活動で獲得したXP');
            $table->unsignedInteger('duration_seconds')->default(0)
                  ->comment('活動に費やした時間（秒）');
            $table->date('studied_date')->comment('学習した日付（streak算出用）');
            $table->timestamps();

            $table->index(['user_id', 'studied_date']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_logs');
    }
};
