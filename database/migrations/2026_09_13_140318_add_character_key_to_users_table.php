<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 「英語の森」で使うキャラクター（8匹から1匹選択）の選択結果を保存する。
     * 登録直後は未選択（null）で、キャラクター選択画面で確定させる。
     * 写真アップロード機能は廃止するため、同時に avatar カラムも削除する。
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('character_key', 30)->nullable()->after('avatar');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->longText('avatar')->nullable()->after('email_verified_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('character_key');
        });
    }
};
