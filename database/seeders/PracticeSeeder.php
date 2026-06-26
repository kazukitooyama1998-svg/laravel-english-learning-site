<?php

namespace Database\Seeders;

use App\Models\Practice;
use Illuminate\Database\Seeder;

class PracticeSeeder extends Seeder
{
    public function run(): void
    {
        // ⚡️ Debug Test (開発・動作確認用)
        Practice::create([
            'category_id' => 1,
            'title' => '⚡️ Quick Test Practice',
            'level' => 'Beginner',
            'prompt' => 'This is a short dataset for rapid testing purposes.',
            'text' => "[Question 1]\nIs the typing app running perfectly?\n[Answer]\nYes, it works fine.",
            'xp' => '30'
        ]);

        // 📚 IELTS Speaking: Education (5問セット)
        Practice::create([
            'category_id' => 2,
            'title' => 'Part 3: Education',
            'level' => 'Intermediate',
            'prompt' => 'Answer 5 high-frequency questions about Education in IELTS Speaking Part 3.',
            'text' => "[Question 1]\nWhy do some students perform better than others?\n[Answer]\nI think the main reason is motivation. Students who are interested in a subject are usually willing to spend more time studying it. For example, some students enjoy learning languages and therefore practice regularly outside school.\n\n" .
                      "[Question 2]\nShould schools focus more on practical skills?\n[Answer]\nYes, to some extent. Practical skills such as communication and financial management can help students in their daily lives. However, academic subjects are also important because they provide fundamental knowledge.\n\n" .
                      "[Question 3]\nHow has education changed in recent years?\n[Answer]\nEducation has become more technology-based. Many students now use online learning platforms and digital resources. As a result, learning is more flexible than before.\n\n" .
                      "[Question 4]\nIs university education necessary for everyone?\n[Answer]\nNo, I do not think so. Some people can build successful careers through vocational training or practical experience. It depends on the individual's goals and interests.\n\n" .
                      "[Question 5]\nWhat qualities make a good teacher?\n[Answer]\nA good teacher should be knowledgeable and patient. They should also be able to explain difficult concepts clearly. This helps students stay motivated and understand lessons better.",
            'xp' => '100'
        ]);

        // 💻 IELTS Speaking: Technology (5問セット)
        Practice::create([
            'category_id' => 2,
            'title' => 'Part 3: Technology',
            'level' => 'Intermediate',
            'prompt' => 'Answer 5 high-frequency questions about Technology in IELTS Speaking Part 3.',
            'text' => "[Question 1]\nHow has technology changed people's lives?\n[Answer]\nTechnology has made daily life more convenient. People can communicate instantly and access information from anywhere. For example, smartphones allow us to manage many tasks efficiently.\n\n" .
                      "[Question 2]\nDo you think AI will replace many jobs?\n[Answer]\nYes, I believe AI will replace some routine jobs. However, it is unlikely to replace jobs that require creativity or emotional intelligence. Therefore, people may need to develop new skills.\n\n" .
                      "[Question 3]\nWhat are the disadvantages of social media?\n[Answer]\nOne disadvantage is that people may spend too much time online. Another issue is the spread of misinformation. These problems can negatively affect both individuals and society.\n\n" .
                      "[Question 4]\nShould children use smartphones?\n[Answer]\nI think children can use smartphones, but with limits. Smartphones can be useful for learning and communication. However, excessive use may affect their health and concentration.\n\n" .
                      "[Question 5]\nHas technology improved communication?\n[Answer]\nOverall, yes. It allows people to stay connected regardless of distance. However, some people rely too much on digital communication and spend less time interacting face-to-face.",
            'xp' => '100'
        ]);

        // 🌱 IELTS Speaking: Environment (5問セット)
        Practice::create([
            'category_id' => 2,
            'title' => 'Part 3: Environment',
            'level' => 'Intermediate',
            'prompt' => 'Answer 5 high-frequency questions about Environment in IELTS Speaking Part 3.',
            'text' => "[Question 1]\nWho should be responsible for protecting the environment?\n[Answer]\nI think both governments and individuals should share responsibility. Governments can create environmental policies, while individuals can reduce waste and save energy in their daily lives.\n\n" .
                      "[Question 2]\nWhy is pollution a serious problem?\n[Answer]\nPollution affects human health and damages ecosystems. For example, air pollution can cause respiratory diseases, while water pollution harms marine life.\n\n" .
                      "[Question 2]\nWhat can people do to reduce waste?\n[Answer]\nPeople can recycle more and avoid single-use products. They can also choose reusable bags and containers. Small actions can make a significant difference over time.\n\n" .
                      "[Question 4]\nIs climate change the biggest environmental issue today?\n[Answer]\nYes, I believe it is one of the most serious issues. Climate change affects weather patterns, agriculture, and biodiversity. Its impact can be seen around the world.\n\n" .
                      "[Question 5]\nShould governments invest more in renewable energy?\n[Answer]\nYes, because renewable energy can reduce dependence on fossil fuels. It is also more sustainable in the long term. Investing in clean energy can benefit future generations.",
            'xp' => '100'
        ]);
    } // <-- run メソッドの終わりはここです！
} // <-- クラス全体の終わりはここです！
