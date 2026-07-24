<?php

/**
 * IELTS Speaking スライド コンテンツデータ
 *
 * 本試験の出題形式に合わせて Part ごとに内容を分けている。
 *   Part 1 = Introduction & Interview（身近な質問への短い受け答え）
 *   Part 2 = Long Turn / Cue Card（1〜2分間のスピーチ）
 *   Part 3 = Discussion（抽象的・分析的な議論）
 *
 * 文法パターン(grammar) / 自然な表現(expression) / 学習Tips(tip) は
 * Part × Band で内容が決まり、3トピック共通で再利用する
 * （実際の試験でも、話し方の型やTipsはトピックに依存しないため）。
 *
 * 語彙(vocabulary) のみ Part × Topic × Band で個別に用意し、
 * トピックごとに使う単語が変わるようにしている。
 *
 * @return array [$part][$topicSlug] => [$score => ['vocabulary'=>[...], 'grammar'=>[...], 'expression'=>[...], 'tip'=>[...]]]
 */
return (function () {
    // ── HTML生成ヘルパー ──────────────────────────────────────

    $vocabHtml = function (array $words): string {
        $html = '<ul class="space-y-3">';
        foreach ($words as $w) {
            $html .= '<li><strong>' . $w[0] . '</strong> [' . $w[1] . '] — ' . $w[2] . '<br><em>' . $w[3] . '</em></li>';
        }
        return $html . '</ul>';
    };

    $expressionHtml = function (string $introJa, array $phrases): string {
        $html = '<p class="mb-3">' . $introJa . '</p><ul class="space-y-2">';
        foreach ($phrases as $phrase) {
            $html .= '<li><strong>' . $phrase . '</strong></li>';
        }
        return $html . '</ul>';
    };

    $grammarHtml = function (string $introJa, array $patterns): string {
        $html = '<p class="mb-4">' . $introJa . '</p><div class="space-y-3">';
        foreach ($patterns as $label => $example) {
            $html .= '<div class="bg-surface-container p-4 rounded-xl"><p class="font-semibold mb-1">' . $label . '</p><p class="text-on-surface-variant">' . $example . '</p></div>';
        }
        return $html . '</div>';
    };

    $tipHtml = function (array $tips): string {
        $html = '<div class="space-y-3">';
        foreach ($tips as $title => $body) {
            $html .= '<div class="flex items-start gap-3"><span class="text-2xl">💡</span><div><p class="font-semibold">' . $title . '</p><p class="text-on-surface-variant">' . $body . '</p></div></div>';
        }
        return $html . '</div>';
    };

    $scores = ['5.5', '6.0', '6.5', '7.0'];
    $topicNames = ['education' => 'Education', 'technology' => 'Technology', 'environment' => 'Environment'];

    // ── Part × Band 共通コンテンツ（grammar / expression / tip）───

    $grammar = [
        1 => [
            '5.5' => ['title' => 'Part 1: 短い質問への答え方（基本パターン）', 'intro' => '短い質問には「答え + because + 理由」のシンプルな形で答えましょう。', 'patterns' => [
                '基本パターン' => 'Yes, I do. I enjoy it because it\'s relaxing.',
                '否定パターン' => 'No, not really. I don\'t have much free time.',
                '頻度を表す' => 'I usually / sometimes / rarely do this.',
            ]],
            '6.0' => ['title' => 'Part 1: 理由と具体例を加える', 'intro' => '理由に加えて、具体例を1つ加えるとより自然な答えになります。', 'patterns' => [
                '理由+具体例' => 'I enjoy it because it helps me relax. For example, I often read before bed.',
                '頻度+期間' => 'I\'ve been doing this for about two years now.',
            ]],
            '6.5' => ['title' => 'Part 1: 幅広い文法・言い換えを使う', 'intro' => '関係代名詞やヘッジ表現を使い、少し複雑な文にも挑戦しましょう。', 'patterns' => [
                '関係代名詞を使う' => 'It\'s something which I\'ve always found interesting.',
                'ヘッジ表現' => 'I\'d say I\'m fairly interested in it, though not hugely.',
            ]],
            '7.0' => ['title' => 'Part 1: 自然でネイティブらしい応答', 'intro' => '強調構文や高度な接続表現で、自然で説得力のある答えを作りましょう。', 'patterns' => [
                '強調構文' => 'What I really enjoy about it is the sense of achievement.',
                '自然な言い換え' => 'Admittedly, it\'s not something I do every day, but when I do, I really make the most of it.',
            ]],
        ],
        2 => [
            '5.5' => ['title' => 'Part 2: 出来事を順序立てて話す（基本パターン）', 'intro' => '「話題の紹介 → 出来事 → 気持ち」のシンプルな順番で話しましょう。', 'patterns' => [
                '導入' => 'I\'d like to talk about ...',
                '出来事' => 'It happened when I ...',
                '気持ち' => 'I felt really happy / proud.',
            ]],
            '6.0' => ['title' => 'Part 2: 時系列表現で内容を広げる', 'intro' => 'First, Then, After that, In the end などの順序を表す表現を使いましょう。', 'patterns' => [
                '時系列' => 'First, ... Then, ... After that, ... In the end, ...',
                '理由を加える' => 'This was memorable because ...',
            ]],
            '6.5' => ['title' => 'Part 2: 描写と振り返りを加える', 'intro' => '描写や振り返りの表現を加え、内容に深みを持たせましょう。', 'patterns' => [
                '描写' => 'What I remember most vividly is ...',
                '振り返り' => 'Looking back, I realise that ...',
            ]],
            '7.0' => ['title' => 'Part 2: 物語性のある高度な語り方', 'intro' => '倒置や強調構文を使い、物語性のある高度な語りにしましょう。', 'patterns' => [
                '強調構文' => 'What really stood out was the way ...',
                '過去完了' => 'I had never expected that ... until that day.',
            ]],
        ],
        3 => [
            '5.5' => ['title' => 'Part 3: 意見を述べる基本パターン', 'intro' => '「意見 + because + 理由」のシンプルな形で十分です。', 'patterns' => [
                '意見+理由' => 'I think ... because ...',
            ]],
            '6.0' => ['title' => 'Part 3: 理由と具体例で意見を広げる', 'intro' => '意見+理由+具体例の3ステップで答えを広げましょう。', 'patterns' => [
                '意見+理由+具体例' => 'I think ..., because .... For example, ...',
            ]],
            '6.5' => ['title' => 'Part 3: 両論を示しバランスを取る', 'intro' => '両方の視点を示し、ヘッジ表現でバランスを取りましょう。', 'patterns' => [
                '両論併記' => 'On the one hand, ... On the other hand, ...',
                'ヘッジ' => 'It could be argued that ..., although ...',
            ]],
            '7.0' => ['title' => 'Part 3: 仮定法・将来予測で議論を深める', 'intro' => '仮定法や将来予測の表現で、抽象的な議論を展開しましょう。', 'patterns' => [
                '将来予測' => 'It\'s likely that in the future, ...',
                '仮定法' => 'If governments were to invest more in this area, we would probably see real change.',
            ]],
        ],
    ];

    $expression = [
        1 => [
            '5.5' => ['title' => 'Part 1: 基本の受け答え表現', 'intro' => 'Part 1では、シンプルに好き嫌いや習慣を伝える基本表現を使いましょう。', 'phrases' => [
                'I like / enjoy ...', 'I don\'t really ...', 'It depends on ...', 'Yes, I do. / No, I don\'t.',
            ]],
            '6.0' => ['title' => 'Part 1: 一歩進んだ受け答え表現', 'intro' => '理由や具体例を加えて、単なるYes/No以上の答えを目指しましょう。', 'phrases' => [
                'What I really like about ... is ...', 'One thing I enjoy is ...', 'I usually ...', 'To be honest, ...',
            ]],
            '6.5' => ['title' => 'Part 1: 自然な口語表現', 'intro' => '少しくだけた自然な言い回しを使うことで、より自然な受け答えになります。', 'phrases' => [
                'I have to admit that ...', 'I\'m quite keen on ...', 'It\'s fair to say that ...', 'More often than not, ...',
            ]],
            '7.0' => ['title' => 'Part 1: ネイティブらしい高度な表現', 'intro' => 'ネイティブらしい自然な言い回しで、即興的で説得力のある受け答えを目指しましょう。', 'phrases' => [
                'I\'d go so far as to say ...', 'There\'s nothing quite like ...', 'If I\'m being completely honest, ...', 'It\'s not something I\'d given much thought to until recently, but ...',
            ]],
        ],
        2 => [
            '5.5' => ['title' => 'Part 2: 基本のCue Card表現', 'intro' => 'Cue Cardのトピックを紹介し、出来事を時系列で話す基本表現です。', 'phrases' => [
                'I\'d like to talk about ...', 'It happened when ...', 'I felt very happy / sad because ...', 'This is something I remember well.',
            ]],
            '6.0' => ['title' => 'Part 2: 内容を膨らませる表現', 'intro' => '出来事の説明に理由や当時の気持ちを加えて、内容を膨らませましょう。', 'phrases' => [
                'This was a memorable experience because ...', 'One thing I remember clearly is ...', 'At the time, I felt ...', 'Looking back now, ...',
            ]],
            '6.5' => ['title' => 'Part 2: 描写を深める表現', 'intro' => '描写を深め、なぜ印象に残ったのかを掘り下げる表現です。', 'phrases' => [
                'It left a lasting impression on me because ...', 'I\'ll never forget how ...', 'What made it particularly memorable was ...', 'In hindsight, ...',
            ]],
            '7.0' => ['title' => 'Part 2: 聞き手を引き込む高度な表現', 'intro' => '物語性のある高度な表現で、聞き手を引き込む語りを目指しましょう。', 'phrases' => [
                'There\'s one occasion that really stands out in my memory ...', 'It goes without saying that ...', 'Looking back, what strikes me most is ...', 'This experience has stayed with me ever since.',
            ]],
        ],
        3 => [
            '5.5' => ['title' => 'Part 3: 基本の意見表現', 'intro' => '意見と簡単な理由をセットで伝える基本表現です。', 'phrases' => [
                'I think ...', 'In my opinion, ...', 'For example, ...', 'I agree / disagree because ...',
            ]],
            '6.0' => ['title' => 'Part 3: 論理をつなげる表現', 'intro' => '意見に理由と追加情報を加え、論理的なつながりを作りましょう。', 'phrases' => [
                'I believe that ...', 'This is mainly because ...', 'Furthermore, ...', 'On the other hand, ...',
            ]],
            '6.5' => ['title' => 'Part 3: バランスの取れた表現', 'intro' => '断定を避けたヘッジ表現を使い、バランスの取れた意見を伝えましょう。', 'phrases' => [
                'It\'s often said that ...', 'To a certain extent, I agree, but ...', 'That being said, ...', 'It could be argued that ...',
            ]],
            '7.0' => ['title' => 'Part 3: 抽象的・分析的な表現', 'intro' => '抽象的・分析的な議論に適した、高度な表現を使いこなしましょう。', 'phrases' => [
                'By and large, ...', 'This raises the question of whether ...', 'Whilst it\'s true that ..., I would argue that ...', 'It\'s a trend that is likely to continue.',
            ]],
        ],
    ];

    $tip = [
        1 => [
            '5.5' => ['title' => 'Speaking Tips: Part 1（Band 5.5）', 'tips' => [
                '簡潔に答える' => 'Part 1の答えは20〜30秒程度で十分です。長く話しすぎる必要はありません。',
                '理由を1つ添える' => 'Yes / Noだけで終わらせず、簡単な理由を1つ加えましょう。',
            ]],
            '6.0' => ['title' => 'Speaking Tips: Part 1（Band 6.0）', 'tips' => [
                '具体例を加える' => '理由に加えて簡単な具体例を1つ入れると、より自然な答えになります。',
                '自然な接続語を使う' => 'because や so などの接続語を使って、文をつなげましょう。',
            ]],
            '6.5' => ['title' => 'Speaking Tips: Part 1（Band 6.5）', 'tips' => [
                '語彙を言い換える' => '質問で使われた単語をそのまま繰り返さず、類義語で言い換えましょう。',
                '自然な言い回しを使う' => 'イディオムやよく使われる表現を自然に取り入れましょう。',
            ]],
            '7.0' => ['title' => 'Speaking Tips: Part 1（Band 7.0）', 'tips' => [
                '自然に、即興的に話す' => '暗記したような答えではなく、自然でスポンテニアスな受け答えを心がけましょう。',
                '正確で幅広い語彙' => '文脈に合った、より精密な語彙選びを意識しましょう。',
            ]],
        ],
        2 => [
            '5.5' => ['title' => 'Speaking Tips: Part 2（Band 5.5）', 'tips' => [
                '準備時間を活用する' => '1分間の準備時間で、Cue Cardの各項目についてキーワードをメモしましょう。',
                '完璧を目指さない' => '全てを完璧に話す必要はありません。まずは話し続けることを意識しましょう。',
            ]],
            '6.0' => ['title' => 'Speaking Tips: Part 2（Band 6.0）', 'tips' => [
                '全項目に触れる' => 'Cue Cardの4つの項目すべてに触れるようにしましょう。',
                '1〜2分間話し続ける' => '長い沈黙を避け、1〜2分間できるだけ話し続けましょう。',
            ]],
            '6.5' => ['title' => 'Speaking Tips: Part 2（Band 6.5）', 'tips' => [
                '構成を意識する' => '導入・本論・まとめの構成を意識して話しましょう。',
                '言い換えで乗り切る' => '単語が分からない時は、パラフレーズ（言い換え）で乗り切りましょう。',
            ]],
            '7.0' => ['title' => 'Speaking Tips: Part 2（Band 7.0）', 'tips' => [
                '詳細と考察を加える' => 'Cue Cardの項目だけでなく、自分の考察や感想を加えて内容に深みを持たせましょう。',
                '自然なペースと抑揚' => '一定のペースを保ちながら、自然な抑揚（イントネーション）で話しましょう。',
            ]],
        ],
        3 => [
            '5.5' => ['title' => 'Speaking Tips: Part 3（Band 5.5）', 'tips' => [
                '意見を明確にする' => 'まず自分の意見をはっきり伝え、その後に簡単な理由を1つ加えましょう。',
                '怖がらず話す' => '間違いを恐れず、思ったことを言葉にしてみましょう。',
            ]],
            '6.0' => ['title' => 'Speaking Tips: Part 3（Band 6.0）', 'tips' => [
                '具体例と接続語を使う' => 'however、thereforeなどの接続語を使い、具体例で意見を補強しましょう。',
            ]],
            '6.5' => ['title' => 'Speaking Tips: Part 3（Band 6.5）', 'tips' => [
                '両論を示す' => '自分の意見を述べる前に、反対意見にも触れてバランスを取りましょう。',
                'ヘッジ表現を使う' => '断定的すぎる表現を避け、to some extent などのヘッジ表現を使いましょう。',
            ]],
            '7.0' => ['title' => 'Speaking Tips: Part 3（Band 7.0）', 'tips' => [
                '将来を見据えて話す' => '現在の問題だけでなく、将来への影響について自分の考えを述べましょう。',
                '多角的な視点を比較する' => '異なる立場を比較し、抽象的で精密な語彙を使って論じましょう。',
            ]],
        ],
    ];

    // ── Part × Topic × Band 語彙（トピックごとに変化）───────────

    $vocab = [
        1 => [ // Part 1: 身近な語彙
            'education' => [
                '5.5' => [['subject', '名', '科目', 'My favourite subject at school is art.'], ['homework', '名', '宿題', 'I usually finish my homework before dinner.'], ['classmate', '名', 'クラスメート', 'I still keep in touch with some of my classmates.'], ['timetable', '名', '時間割', 'Our timetable changes every semester.'], ['exam', '名', '試験', 'I have an important exam next week.']],
                '6.0' => [['curriculum', '名', '教育課程・カリキュラム', 'Schools need to update their curriculum regularly.'], ['compulsory', '形', '義務的な', 'Education is compulsory until the age of 16.'], ['scholarship', '名', '奨学金', 'She received a scholarship to study abroad.'], ['extracurricular', '形', '課外の', 'I take part in several extracurricular activities.'], ['motivated', '形', 'やる気のある', 'A good teacher keeps students motivated.']],
                '6.5' => [['rigorous', '形', '厳格な・徹底的な', 'The course has a fairly rigorous grading system.'], ['well-rounded', '形', 'バランスの取れた', 'I want to become a well-rounded student, not just academically.'], ['self-discipline', '名', '自己規律', 'Studying online really requires a lot of self-discipline.'], ['academic pressure', '名句', '学業のプレッシャー', 'Many students in my country face intense academic pressure.'], ['excel', '動', '秀でる', 'She has always excelled in subjects that involve creativity.']],
                '7.0' => [['meritocratic', '形', '実力主義の', 'Our education system is fairly meritocratic in theory, though not always in practice.'], ['formative assessment', '名句', '形成的評価', 'Formative assessment helps teachers adjust their lessons in real time.'], ['intellectual curiosity', '名句', '知的好奇心', 'Good teachers know how to spark intellectual curiosity in their students.'], ['holistic development', '名句', '全人的な発達', 'The best schools focus on the holistic development of each child.'], ['underpin', '動', '根底を支える', 'Critical thinking underpins almost every subject we study.']],
            ],
            'technology' => [
                '5.5' => [['phone', '名', '電話・スマートフォン', 'I use my phone for almost everything these days.'], ['internet', '名', 'インターネット', 'I use the internet every day for study and fun.'], ['app', '名', 'アプリ', 'I have an app for taking notes.'], ['computer', '名', 'コンピューター', 'I do my homework on the computer.'], ['use', '動', '使う', 'I use technology a lot in my daily life.']],
                '6.0' => [['device', '名', '機器・デバイス', 'I own several electronic devices.'], ['social media', '名句', 'SNS', 'I check social media a few times a day.'], ['online', '形/副', 'オンラインの・オンラインで', 'I do a lot of shopping online.'], ['convenient', '形', '便利な', 'Online banking is very convenient.'], ['useful', '形', '役に立つ', 'This app is really useful for learning languages.']],
                '6.5' => [['indispensable', '形', '不可欠な', 'My smartphone has become indispensable to my daily routine.'], ['multitask', '動', '同時並行で作業する', 'I often multitask between emails and messages.'], ['connectivity', '名', '接続性', 'Good internet connectivity is essential for remote work.'], ['streamline', '動', '効率化する', 'This app helps streamline my daily schedule.'], ['reliable', '形', '信頼できる', 'I need a reliable device for online classes.']],
                '7.0' => [['ubiquitous', '形', 'どこにでもある', 'Smartphones have become truly ubiquitous in modern society.'], ['seamless integration', '名句', 'シームレスな統合', 'The seamless integration between devices makes life much easier.'], ['digital dependency', '名句', 'デジタル依存', 'There is growing concern about digital dependency among young people.'], ['cognitive offloading', '名句', '認知的な負荷の外部化', 'Relying on apps for memory is a form of cognitive offloading.'], ['intuitive', '形', '直感的な', 'The best apps have an intuitive, easy-to-learn interface.']],
            ],
            'environment' => [
                '5.5' => [['recycle', '動', 'リサイクルする', 'I try to recycle plastic bottles every day.'], ['rubbish', '名', 'ゴミ', 'We should put rubbish in the correct bin.'], ['clean', '形', 'きれいな', 'I want to keep the air clean in my city.'], ['nature', '名', '自然', 'I enjoy spending time in nature.'], ['air', '名', '空気', 'The air quality here is not always good.']],
                '6.0' => [['pollution', '名', '汚染', 'Air pollution is a serious problem in big cities.'], ['environment', '名', '環境', 'We all need to protect the environment.'], ['protect', '動', '守る', 'Governments should protect natural forests.'], ['waste', '名', '廃棄物・無駄', 'We produce too much plastic waste.'], ['energy', '名', 'エネルギー', 'Saving energy at home is easy to do.']],
                '6.5' => [['sustainable', '形', '持続可能な', 'We need more sustainable ways of living.'], ['eco-friendly', '形', '環境に優しい', 'I try to buy eco-friendly products whenever possible.'], ['carbon footprint', '名句', 'カーボンフットプリント', 'Flying often increases your personal carbon footprint.'], ['conservation', '名', '保全', 'Wildlife conservation is important for biodiversity.'], ['renewable', '形', '再生可能な', 'Renewable energy sources are becoming more affordable.']],
                '7.0' => [['environmentally conscious', '形句', '環境意識の高い', 'More consumers are becoming environmentally conscious these days.'], ['ecological footprint', '名句', '生態学的フットプリント', 'Reducing our ecological footprint requires systemic change.'], ['sustainability-driven', '形句', '持続可能性を重視した', 'Many companies now follow a sustainability-driven business model.'], ['green initiative', '名句', '環境保全の取り組み', 'The city launched a new green initiative last year.'], ['eco-conscious', '形', '環境意識のある', 'Younger generations tend to be more eco-conscious than before.']],
            ],
        ],
        2 => [ // Part 2: 描写・物語的な語彙
            'education' => [
                '5.5' => [['influence', '動/名', '影響（する）', 'My teacher had a big influence on me.'], ['favourite', '形', 'お気に入りの', 'Maths was always my favourite subject.'], ['helpful', '形', '助けになる', 'She was always very helpful when I had questions.'], ['patient', '形', '忍耐強い', 'He was very patient with students who struggled.'], ['memorable', '形', '記憶に残る', 'It was one of the most memorable years of school.']],
                '6.0' => [['inspire', '動', '鼓舞する', 'My teacher really inspired me to keep studying.'], ['encourage', '動', '励ます', 'She always encouraged us to ask questions.'], ['confident', '形', '自信のある', 'I became much more confident in speaking English.'], ['curious', '形', '好奇心のある', 'He made me feel curious about the subject.'], ['supportive', '形', '協力的な', 'The teacher was always supportive, even when I made mistakes.']],
                '6.5' => [['profound impact', '名句', '大きな影響', 'That experience had a profound impact on my studies.'], ['insightful', '形', '洞察力のある', 'She gave really insightful comments on my work.'], ['thought-provoking', '形', '考えさせられる', 'The lesson was genuinely thought-provoking.'], ['nurturing', '形', '育成的な', 'It was a nurturing environment for young learners.'], ['resilience', '名', '粘り強さ・回復力', 'That teacher taught me the value of resilience.']],
                '7.0' => [['formative influence', '名句', '人格形成に影響を与えるもの', 'She was a formative influence during my teenage years.'], ['intellectually stimulating', '形句', '知的刺激を与える', 'His lessons were always intellectually stimulating.'], ['transformative experience', '名句', '変革的な経験', 'Studying under her was a genuinely transformative experience.'], ['unwavering support', '名句', '揺るぎない支え', 'I always felt his unwavering support, even during difficult times.'], ['instil', '動', '（価値観などを）植え付ける', 'He instilled in us a lifelong love of learning.']],
            ],
            'technology' => [
                '5.5' => [['helpful', '形', '役に立つ', 'This app is really helpful for organising my day.'], ['easy', '形', '簡単な', 'It was easy to learn how to use it.'], ['quick', '形', '素早い', 'It gives me a quick answer to any question.'], ['useful', '形', '役に立つ', 'It\'s one of the most useful apps on my phone.'], ['problem', '名', '問題', 'It helped me solve a problem quickly.']],
                '6.0' => [['convenient', '形', '便利な', 'Having everything on one device is very convenient.'], ['reliable', '形', '信頼できる', 'It has always been a reliable tool for me.'], ['efficient', '形', '効率的な', 'It makes my daily tasks much more efficient.'], ['innovative', '形', '革新的な', 'It was quite an innovative idea at the time.'], ['solve', '動', '解決する', 'This device helped me solve a real problem.']],
                '6.5' => [['game-changer', '名句', '状況を大きく変えるもの', 'This app has genuinely been a game-changer for my studies.'], ['revolutionise', '動', '一変させる', 'It has completely revolutionised the way I work.'], ['indispensable tool', '名句', '欠かせない道具', 'It has become an indispensable tool in my daily life.'], ['troubleshoot', '動', '問題を解決する', 'I used an online guide to troubleshoot the issue myself.'], ['versatile', '形', '多用途の', 'It\'s a remarkably versatile piece of technology.']],
                '7.0' => [['transformative', '形', '変革的な', 'The impact of this technology has been genuinely transformative.'], ['cutting-edge', '形', '最先端の', 'It uses some fairly cutting-edge technology.'], ['unparalleled convenience', '名句', '類を見ない利便性', 'It offers a level of convenience that is simply unparalleled.'], ['technological breakthrough', '名句', '技術的躍進', 'It felt like witnessing a genuine technological breakthrough.'], ['invaluable', '形', '非常に貴重な', 'It has proven invaluable during stressful periods of my life.']],
            ],
            'environment' => [
                '5.5' => [['forest', '名', '森', 'We walked through a beautiful forest.'], ['park', '名', '公園', 'There is a lovely park near my home.'], ['clean', '形', 'きれいな', 'The air felt very clean there.'], ['quiet', '形', '静かな', 'It was so quiet compared to the city.'], ['beautiful', '形', '美しい', 'The scenery there was really beautiful.']],
                '6.0' => [['peaceful', '形', '穏やかな', 'It was one of the most peaceful places I\'ve visited.'], ['fresh air', '名句', '新鮮な空気', 'I really enjoyed breathing the fresh air there.'], ['wildlife', '名', '野生動物', 'We saw quite a lot of wildlife on the trail.'], ['scenery', '名', '景色', 'The scenery around the lake was stunning.'], ['relaxing', '形', 'リラックスできる', 'It was such a relaxing place to spend the day.']],
                '6.5' => [['breathtaking', '形', '息をのむような', 'The view from the top was absolutely breathtaking.'], ['unspoiled', '形', '手つかずの', 'It felt like a truly unspoiled natural environment.'], ['tranquil', '形', '静穏な', 'There was a tranquil atmosphere by the waterfall.'], ['awe-inspiring', '形', '畏敬の念を起こさせる', 'The size of the mountains was awe-inspiring.'], ['picturesque', '形', '絵のように美しい', 'It was a picturesque little village surrounded by hills.']],
                '7.0' => [['pristine wilderness', '名句', '手つかずの自然', 'It felt like walking through pristine wilderness untouched by humans.'], ['awe-inspiring vista', '名句', '畏敬の念を抱かせる眺め', 'We were greeted by an awe-inspiring vista at the summit.'], ['restorative', '形', '回復させるような', 'Spending time there had a genuinely restorative effect on me.'], ['profoundly moving', '形句', '深く心を動かされる', 'The experience was profoundly moving in a way I hadn\'t expected.'], ['idyllic', '形', '牧歌的な・理想的な', 'It was an idyllic setting, far removed from city life.']],
            ],
        ],
        3 => [ // Part 3: 抽象的・分析的な語彙
            'education' => [
                '5.5' => [['change', '動/名', '変わる、変化', 'I think education needs to change.'], ['help', '動', '助ける', 'Technology can help students learn.'], ['learn', '動', '学ぶ', 'Children learn better with practical examples.'], ['important', '形', '重要な', 'It is important to update the curriculum.'], ['future', '名', '将来', 'We must prepare students for the future.']],
                '6.0' => [['improve', '動', '改善する', 'We need to improve the current system.'], ['method', '名', '方法', 'Teaching methods should be more practical.'], ['skill', '名', 'スキル', 'Students should learn practical skills, not just theory.'], ['employer', '名', '雇用主', 'Employers want graduates with real-world experience.'], ['update', '動', '更新する', 'The curriculum should be updated regularly.']],
                '6.5' => [['reform', '名/動', '改革（する）', 'The education system needs significant reform.'], ['standardised testing', '名句', '標準化されたテスト', 'Standardised testing doesn\'t measure creativity very well.'], ['critical thinking', '名句', '批判的思考', 'Schools should focus more on critical thinking.'], ['adaptability', '名', '適応力', 'Employers increasingly value adaptability over memorised knowledge.'], ['digital literacy', '名句', 'デジタルリテラシー', 'Digital literacy is now an essential skill.']],
                '7.0' => [['meritocracy', '名', '実力主義社会', 'In theory, education should support a fair meritocracy.'], ['socioeconomic disparity', '名句', '社会経済的格差', 'Socioeconomic disparity still affects access to quality education.'], ['pedagogical approach', '名句', '教育的アプローチ', 'Teachers are adopting a more student-centred pedagogical approach.'], ['lifelong learning', '名句', '生涯学習', 'The modern workplace demands a genuine commitment to lifelong learning.'], ['curriculum overhaul', '名句', 'カリキュラムの抜本的見直し', 'Many experts are calling for a complete curriculum overhaul.']],
            ],
            'technology' => [
                '5.5' => [['internet', '名', 'インターネット', 'Most people use the internet every day.'], ['technology', '名', '技術', 'Technology has changed how we live.'], ['people', '名', '人々', 'Many people rely on their phones too much.'], ['use', '動', '使う', 'We use technology for work and study.'], ['help', '動', '助ける', 'Technology can help us in many ways.']],
                '6.0' => [['automation', '名', '自動化', 'Automation is changing many industries.'], ['artificial intelligence', '名句', '人工知能', 'Artificial intelligence is used in many apps now.'], ['privacy', '名', 'プライバシー', 'People are more worried about privacy than before.'], ['impact', '名', '影響', 'Technology has a big impact on society.'], ['industry', '名', '産業', 'Many industries are becoming more digital.']],
                '6.5' => [['data privacy', '名句', 'データプライバシー', 'Data privacy has become a major public concern.'], ['algorithmic bias', '名句', 'アルゴリズムの偏り', 'Algorithmic bias can lead to unfair outcomes.'], ['digital divide', '名句', 'デジタル格差', 'The digital divide affects people in rural areas the most.'], ['cybersecurity', '名', 'サイバーセキュリティ', 'Cybersecurity has become essential for every business.'], ['regulation', '名', '規制', 'Governments are introducing stricter regulation of AI.']],
                '7.0' => [['technological determinism', '名句', '技術決定論', 'Some critics warn against a purely technological determinism.'], ['surveillance capitalism', '名句', '監視資本主義', 'Surveillance capitalism relies on collecting vast amounts of personal data.'], ['digital literacy gap', '名句', 'デジタルリテラシー格差', 'The digital literacy gap between generations remains significant.'], ['ethical implications', '名句', '倫理的な含意', 'The ethical implications of AI need serious public debate.'], ['disruptive innovation', '名句', '破壊的イノベーション', 'This is a clear example of disruptive innovation reshaping an industry.']],
            ],
            'environment' => [
                '5.5' => [['climate', '名', '気候', 'The climate in my country is changing.'], ['environment', '名', '環境', 'We should protect the environment.'], ['protect', '動', '守る', 'Governments must protect natural resources.'], ['government', '名', '政府', 'The government should do more to help.'], ['future', '名', '将来', 'We need to think about the future.']],
                '6.0' => [['climate change', '名句', '気候変動', 'Climate change is affecting weather patterns worldwide.'], ['renewable energy', '名句', '再生可能エネルギー', 'Renewable energy is becoming cheaper every year.'], ['greenhouse gas', '名句', '温室効果ガス', 'Cars produce a large amount of greenhouse gas.'], ['regulation', '名', '規制', 'Stricter regulation could reduce factory emissions.'], ['responsibility', '名', '責任', 'Everyone shares responsibility for protecting the planet.']],
                '6.5' => [['carbon emissions', '名句', '二酸化炭素排出量', 'Carbon emissions from transport remain a major issue.'], ['biodiversity loss', '名句', '生物多様性の喪失', 'Biodiversity loss is accelerating due to habitat destruction.'], ['sustainable development', '名句', '持続可能な開発', 'Sustainable development balances economic growth with environmental care.'], ['policy', '名', '政策', 'Effective environmental policy requires long-term planning.'], ['initiative', '名', '取り組み', 'Several green initiatives have been launched recently.']],
                '7.0' => [['decarbonisation', '名', '脱炭素化', 'Decarbonisation of heavy industry remains a huge challenge.'], ['ecological collapse', '名句', '生態系の崩壊', 'Scientists warn of potential ecological collapse in vulnerable regions.'], ['intergenerational responsibility', '名句', '世代間の責任', 'Climate action is often framed as a matter of intergenerational responsibility.'], ['systemic change', '名句', '構造的な変化', 'Individual action alone cannot replace genuine systemic change.'], ['mitigation', '名', '緩和策', 'Mitigation strategies must be paired with adaptation policies.']],
            ],
        ],
    ];

    // ── 組み立て ────────────────────────────────────────────

    $result = [];

    foreach ([1, 2, 3] as $part) {
        foreach (array_keys($topicNames) as $topicSlug) {
            $topicName = $topicNames[$topicSlug];

            foreach ($scores as $score) {
                $g = $grammar[$part][$score];
                $e = $expression[$part][$score];
                $t = $tip[$part][$score];

                $result[$part][$topicSlug][$score] = [
                    'vocabulary' => [
                        'title'   => "Key Vocabulary: {$topicName} (Part {$part}, Band {$score})",
                        'content' => $vocabHtml($vocab[$part][$topicSlug][$score]),
                    ],
                    'grammar' => [
                        'title'   => $g['title'],
                        'content' => $grammarHtml($g['intro'], $g['patterns']),
                    ],
                    'expression' => [
                        'title'   => $e['title'],
                        'content' => $expressionHtml($e['intro'], $e['phrases']),
                    ],
                    'tip' => [
                        'title'   => $t['title'],
                        'content' => $tipHtml($t['tips']),
                    ],
                ];
            }
        }
    }

    return $result;
})();
