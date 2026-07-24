<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * user_section_progress テーブル（新規）
 * ユーザーがどのセクションをどこまで進めたかを記録
 * TOEICスライドのスキップ機能・各ダッシュボードの進捗バーに使用
 *
 * section_type / section_key の対応:
 *   toeic_slides    / part_5 / part_6 / part_7
 *   toeic_questions / part_5 / part_6 / part_7
 *   ielts_slides    / 1_education_6.0（part_topic_score）
 *   ielts_typing    / 1_education_6.0
 *   typing_slides   / {material_id}（教材ID）
 *   typing_material / {material_id}
 *   vocabulary      / toeic-700 / ielts-55（URLパラメータ形式で統一）
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_section_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('section_type', 30)->comment('セクション種別');
            $table->string('section_key', 50)->comment('セクションの識別キー');
            $table->unsignedInteger('last_step')->default(0)
                  ->comment('最後に閲覧したステップ番号');
            $table->boolean('is_completed')->default(false)->comment('完了フラグ');
            $table->timestamp('completed_at')->nullable()->comment('完了日時');
            $table->timestamps();

            $table->unique(['user_id', 'section_type', 'section_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_section_progress');
    }
};
