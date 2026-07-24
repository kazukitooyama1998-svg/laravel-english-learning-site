<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('toeic_questions', function (Blueprint $table) {
            $table->foreignId('passage_id')->nullable()->after('part')
                  ->constrained('toeic_passages')->nullOnDelete()
                  ->comment('Part6/7で共有する長文（Part5はnull）');
        });
    }

    public function down(): void
    {
        Schema::table('toeic_questions', function (Blueprint $table) {
            $table->dropForeign(['passage_id']);
            $table->dropColumn('passage_id');
        });
    }
};
