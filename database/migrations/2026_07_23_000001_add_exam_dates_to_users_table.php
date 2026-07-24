<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * users テーブルへ TOEIC / IELTS の試験日カラムを追加
 *
 * 学習カレンダー（english/progress）で試験日までの残り日数表示・
 * カレンダー上への試験日マークに使用する。
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('toeic_exam_date')->nullable()->after('total_study_time');
            $table->date('ielts_exam_date')->nullable()->after('toeic_exam_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['toeic_exam_date', 'ielts_exam_date']);
        });
    }
};
