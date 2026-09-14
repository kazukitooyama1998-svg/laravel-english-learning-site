<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 「じぶんの家」の模様替え内容（家具の配置・床・壁）を保存する。
     * 家具は数〜数十個の小さな配列なので、専用テーブルを作らず JSON で持つ。
     * 形式: {"floor":"oak","wall":"cream","items":[{"key":"bed","x":0,"z":0,"rot":0}]}
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('room_layout')->nullable()->after('character_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('room_layout');
        });
    }
};
