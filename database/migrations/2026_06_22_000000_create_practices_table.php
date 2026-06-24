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
            // カテゴリーテーブルが先に作成されている前提です
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); 
            $table->string('title');
            $table->string('level');
            $table->text('prompt');
            $table->text('text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practices');
    }
};
