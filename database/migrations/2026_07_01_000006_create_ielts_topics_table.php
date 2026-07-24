<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ielts_topics テーブル（新規）
 * IELTSの学習トピックマスター（Education / Technology / Environment）
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ielts_topics', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique()
                  ->comment('URLスラッグ（education / technology / environment）');
            $table->string('name', 100)->comment('表示名（Education 等）');
            $table->string('description', 255)->nullable()->comment('トピックの説明');
            $table->string('icon', 50)->nullable()->comment('アイコン（絵文字またはアイコンクラス）');
            $table->integer('sort_order')->default(0)->comment('表示順');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ielts_topics');
    }
};
