<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ielts_records テーブル（新規）
 * IELTSタイピング練習の結果記録
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ielts_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('material_id')
                  ->constrained('ielts_materials')
                  ->cascadeOnDelete();
            $table->unsignedInteger('wpm')->comment('タイピング速度');
            $table->decimal('accuracy', 5, 2)->comment('正答率（0.00〜100.00）');
            $table->decimal('clear_time', 8, 2)->comment('クリアタイム（秒）');
            $table->unsignedInteger('xp_gained')->default(0)->comment('獲得XP');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ielts_records');
    }
};
