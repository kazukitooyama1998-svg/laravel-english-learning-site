<?php

namespace Database\Seeders;

use App\Models\Practice;
use Illuminate\Database\Seeder;

class PracticeSeeder extends Seeder
{
    public function run(): void
    {
        // 1件目のデータ登録
        Practice::create([
            'category' => 'IELTS Writing',
            'title' => 'Task 2: Agree/Disagree Template',
            'level' => 'Intermediate',
            'prompt' => 'Some people believe that studying abroad, such as in Cebu, offers more advantages than studying in one’s own country.',
            'text' => "In recent years, studying abroad has become increasingly popular among students who want to improve their language skills and broaden their perspectives. Cebu in the Philippines is widely recognized as an affordable destination for English learners. While some people believe that studying in one's own country is more practical and comfortable, I strongly agree that studying abroad offers greater benefits in terms of language development and personal growth. One major advantage of studying abroad is the opportunity to use English in real-life situations every day. In Cebu, students are surrounded by an English-speaking environment not only in classrooms but also in daily life such as restaurants and public transportation. As a result, they can improve their communication skills more quickly than students who rely only on textbooks. In addition, many schools offer one-on-one lessons, allowing students to focus on their weaknesses effectively. Another benefit is personal development through living in a foreign country. Students must manage their daily life independently, adapt to different cultures, and interact with people from various countries such as Korea, Taiwan, and Vietnam. These experiences help build confidence and global awareness, which are useful for future international careers. In conclusion, I believe that studying abroad in places such as Cebu provides more valuable opportunities than studying in one's own country."
        ]);

        // ─── 💡 2件目のデータ（ここをrunメソッドの内側に入れます） ───
        Practice::create([
            'category' => 'Debug Test',
            'title' => '⚡️ Short Test Practice (For Dev)',
            'level' => 'Beginner',
            'prompt' => 'This is a prompt for testing purposes only.',
            'text' => "This is a short test sentence. Typing app is running perfectly. End of the test."
        ]);
        
    } // <-- run メソッドの終わりはここです！
} // <-- クラス全体の終わりはここです！
