<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practices', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // 例: IELTS Writing
            $table->string('title');    // 例: Task 2: Agree/Disagree Template
            $table->string('level');    // 例: Intermediate
            $table->text('prompt');     // 問題の要約・プロンプト
            $table->text('text');       // タイピングする本文
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practices');
    }
};
