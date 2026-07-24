<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * typing_records テーブル
 * タイピング練習の結果記録（旧: records を拡張・改名）
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typing_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('material_id')
                  ->constrained('typing_materials')
                  ->cascadeOnDelete();
            $table->unsignedInteger('wpm')->comment('タイピング速度（Words Per Minute）');
            $table->decimal('accuracy', 5, 2)->comment('正答率（0.00〜100.00）');
            $table->decimal('clear_time', 8, 2)->comment('クリアタイム（秒）');
            $table->unsignedInteger('xp_gained')->default(0)->comment('この練習で獲得したXP');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typing_records');
    }
};
