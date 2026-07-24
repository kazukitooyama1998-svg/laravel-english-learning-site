<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ielts_materials に order_no を追加
 * Part × Topic × Score の組み合わせに複数バリエーション（例: Part2の5問）を
 * 登録できるようにするための識別用カラム。バリエーションが1つしかない
 * Part/Topic/Score では null のままにする。
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ielts_materials', function (Blueprint $table) {
            $table->unsignedTinyInteger('order_no')
                  ->nullable()
                  ->after('target_score')
                  ->comment('同一 part/topic/score 内でのバリエーション番号（複数問対応）');
        });
    }

    public function down(): void
    {
        Schema::table('ielts_materials', function (Blueprint $table) {
            $table->dropColumn('order_no');
        });
    }
};
