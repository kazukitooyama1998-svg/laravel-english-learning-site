<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 💡 1. 要件：Admin（管理者）ユーザーの登録
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('asdfasdf'), // 本番では変更してください
            'email_verified_at' => now(), // 💡 要件：メール認証済み状態にする
            'role_id' => 1, // 💡 1:admin
            'introduction' => 'I am the administrator of this site.',
        ]);

        // 💡 2. 一般ユーザー（テスト用ユーザーA）の登録
        User::create([
            'name' => 'Kazuki Toyama',
            'email' => 'kazukitooyama1998@gmail.com',
            'password' => Hash::make('kazuki1998'),
            'email_verified_at' => now(), // 💡 要件：メール認証済み
            'role_id' => 2, // 💡 2:user
            'introduction' => 'Targeting IELTS 7.0! Let\'s practice typing together.',
        ]);

        // 💡 3. 一般ユーザー（テスト用ユーザーB：フォロー機能テスト用）
        User::create([
            'name' => 'Joy Toyama',
            'email' => 'kazukitooyama19988@gmail.com',
            'password' => Hash::make('kazuki1998'),
            'email_verified_at' => now(),
            'role_id' => 2,
            'introduction' => 'Hi, I am studying business English.',
        ]);
    }
}
