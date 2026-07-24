<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. 管理者ユーザー
        User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@gmail.com',
            'password'          => Hash::make('asdfasdf'),
            'email_verified_at' => now(),
            'role_id'           => User::ADMIN_ROLE_ID,
            'introduction'      => 'I am the administrator of this site.',
            'total_xp'          => 0,
            'study_streak'      => 0,
            'total_study_time'  => 0,
        ]);

        // 2. メインテストユーザー（開発者）
        User::create([
            'name'              => 'Kazuki Toyama',
            'email'             => 'kazukitooyama1998@gmail.com',
            'password'          => Hash::make('kazuki1998'),
            'email_verified_at' => now(),
            'role_id'           => User::USER_ROLE_ID,
            'introduction'      => 'Targeting IELTS 7.0! Let\'s study together.',
            'total_xp'          => 1850,
            'study_streak'      => 7,
            'last_study_date'   => now()->toDateString(),
            'total_study_time'  => 20700, // 約5.75時間
        ]);

        // 3. テストユーザーB（ランキング・フォロー機能のテスト用）
        User::create([
            'name'              => 'Joy Toyama',
            'email'             => 'kazukitooyama19988@gmail.com',
            'password'          => Hash::make('kazuki1998'),
            'email_verified_at' => now(),
            'role_id'           => User::USER_ROLE_ID,
            'introduction'      => 'Hi, I am studying business English.',
            'total_xp'          => 3200,
            'study_streak'      => 14,
            'last_study_date'   => now()->toDateString(),
            'total_study_time'  => 43200,
        ]);

        // 4. ランキング用ダミーユーザー（上位ランカーとして表示）
        $rankingUsers = [
            ['name' => 'Yuki T.',  'email' => 'yuki@example.com',  'xp' => 12500, 'streak' => 21],
            ['name' => 'Hana S.',  'email' => 'hana@example.com',  'xp' => 9800,  'streak' => 14],
            ['name' => 'Ryo K.',   'email' => 'ryo@example.com',   'xp' => 6200,  'streak' => 5],
            ['name' => 'Taro N.',  'email' => 'taro@example.com',  'xp' => 4800,  'streak' => 3],
            ['name' => 'Mei A.',   'email' => 'mei@example.com',   'xp' => 3600,  'streak' => 7],
        ];

        foreach ($rankingUsers as $idx => $data) {
            User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'role_id'           => User::USER_ROLE_ID,
                'total_xp'          => $data['xp'],
                'study_streak'      => $data['streak'],
                'last_study_date'   => now()->subDays(rand(0, 1))->toDateString(),
                'total_study_time'  => $data['xp'] * 10,
            ]);
        }
    }
}
