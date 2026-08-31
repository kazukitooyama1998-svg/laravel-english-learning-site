<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('toeic_questions', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('question_text')
                  ->comment('Part1（写真描写問題）で表示する画像URL');
        });
    }

    public function down(): void
    {
        Schema::table('toeic_questions', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }
};
