<?php

namespace Database\Seeders;

use App\Models\English\ToeicSlide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToeicSlideSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        ToeicSlide::whereIn('part', [1, 2, 3, 4, 5, 6, 7])->delete();

        $this->createPart1Slides();
        $this->createPart2Slides();
        $this->createPart3Slides();
        $this->createPart4Slides();
        $this->createPart5Slides();
        $this->createPart6Slides();
        $this->createPart7Slides();
    }

    private function createPart1Slides(): void
    {
        $slides = [
            [
                'step_number' => 1,
                'slide_type'  => 'explanation',
                'title'       => 'Part 1 とは？',
                'content'     => '<p class="mb-4">Part 1は<strong>Photographs（写真描写問題）</strong>です。</p>
<ul class="space-y-2">
<li>問題数: <strong>6問</strong>（本番）</li>
<li>形式: 1枚の写真について読み上げられる4つの英文（A〜D）を聞き、写真を最も正確に描写しているものを選ぶ</li>
<li>選択肢は<strong>音声のみ</strong>で読み上げられ、印刷はされない</li>
<li>目標解答時間: <strong>1問あたり約10秒</strong></li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>再生ボタンで英文を聞きながら、写真の「人物の動作」「物の位置」に注目しよう。迷ったら候補を2つに絞って消去法で選ぶ。</p>
</div>',
            ],
        ];

        foreach ($slides as $slide) {
            ToeicSlide::create(array_merge($slide, ['part' => 1, 'sort_order' => $slide['step_number']]));
        }
    }

    private function createPart2Slides(): void
    {
        $slides = [
            [
                'step_number' => 1,
                'slide_type'  => 'explanation',
                'title'       => 'Part 2 とは？',
                'content'     => '<p class="mb-4">Part 2は<strong>Question-Response（応答問題）</strong>です。</p>
<ul class="space-y-2">
<li>問題数: <strong>25問</strong>（本番）</li>
<li>形式: 1つの質問または発言と、それに対する3つの応答（A〜C）が読み上げられる。最もふさわしい応答を選ぶ</li>
<li>質問文・応答とも<strong>音声のみ</strong>で、印刷はされない</li>
<li>目標解答時間: <strong>1問あたり約5秒</strong></li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>最初の疑問詞（Who / What / When / Where / Why / How）を聞き逃さないことが最重要。疑問詞さえ聞き取れれば、話がそれた選択肢を消去できる。</p>
</div>',
            ],
            [
                'step_number' => 2,
                'slide_type'  => 'strategy',
                'title'       => '設問タイプ①：WH疑問文',
                'content'     => '<p class="mb-4">Who / What / When / Where / Why / How で始まる疑問文は、Yes/Noでは答えられません。疑問詞に対応した情報（人・時・場所・理由・方法など）を答えている選択肢を選びます。</p>
<ul class="space-y-2">
<li><strong>Who</strong> → 人名・役職</li>
<li><strong>When / What time</strong> → 時刻・日付</li>
<li><strong>Where</strong> → 場所</li>
<li><strong>Why</strong> → 理由（because など）</li>
<li><strong>How long / How much / How often</strong> → 期間・金額・頻度</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 ひっかけに注意</p>
<p>WH疑問文に "Yes" や "No" で始まる応答は、ほぼ確実に不正解。</p>
</div>',
            ],
            [
                'step_number' => 3,
                'slide_type'  => 'strategy',
                'title'       => '設問タイプ②：Yes/No疑問文・否定疑問文・付加疑問文',
                'content'     => '<p class="mb-4">Do / Does / Did / Is / Are / Have で始まる疑問文には、Yes/Noまたはそれに相当する内容で応答します。</p>
<ul class="space-y-2">
<li><strong>否定疑問文</strong>（Isn\'t / Don\'t で始まる）も、内容が事実ならYes、事実でなければNoで答える（日本語の「はい/いいえ」の感覚と逆になる場合があるので注意）</li>
<li><strong>付加疑問文</strong>（..., aren\'t you? / ..., isn\'t it? など）も同様にYes/Noベースで答える</li>
<li>実際にはYes/Noを省略し、具体的な内容だけで答える応答も多い（本番でも頻出）</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>Yes/Noの有無だけで判断せず、後に続く内容が質問と論理的につながっているかを確認する。</p>
</div>',
            ],
            [
                'step_number' => 4,
                'slide_type'  => 'strategy',
                'title'       => '設問タイプ③：平叙文・依頼・提案・選択疑問文',
                'content'     => '<p class="mb-4">TOEIC Part 2には疑問文ではなく、依頼・提案・報告（平叙文）の形式で出題されるパターンも多くあります。</p>
<ul class="space-y-2">
<li><strong>依頼表現</strong>（Could you...? / Would you mind...?）→ 引き受ける/断る応答</li>
<li><strong>提案表現</strong>（Why don\'t we...? / Shall we...?）→ 賛成/代案の応答</li>
<li><strong>選択疑問文</strong>（A or B?）→ どちらかを選ぶ、または両方とも否定する応答</li>
<li><strong>平叙文</strong>（問題の報告・状況説明）→ 対応策や反応を示す応答</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>平叙文は「質問」ではないため、内容を最後まで聞き、状況に対する自然な反応・対応を選ぶ意識が必要。</p>
</div>',
            ],
            [
                'step_number' => 5,
                'slide_type'  => 'strategy',
                'title'       => 'ひっかけパターンとPart 2解答戦略',
                'content'     => '<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">頻出のひっかけパターン</p>
<ul class="space-y-1">
<li><strong>音の類似</strong>：質問中の単語と似た発音の単語を含む選択肢（例：market ↔ marketing）</li>
<li><strong>連想語</strong>：質問と関連しそうだが、実際には答えになっていない単語を含む選択肢</li>
<li><strong>時制のズレ</strong>：質問は未来のことなのに過去形で答えるなど、時制が噛み合わない選択肢</li>
</ul>
</div>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 時間配分の目安</p>
<p>Part2全25問は<strong>約4分</strong>で解き切るのが理想。1問に迷っても選択肢は一度しか読まれないため、直感で選んですぐ次の問題に集中を切り替えることが高得点の鍵。</p>
</div>',
            ],
        ];

        foreach ($slides as $slide) {
            ToeicSlide::create(array_merge($slide, ['part' => 2, 'sort_order' => $slide['step_number']]));
        }
    }

    private function createPart3Slides(): void
    {
        $slides = [
            [
                'step_number' => 1,
                'slide_type'  => 'explanation',
                'title'       => 'Part 3 とは？',
                'content'     => '<p class="mb-4">Part 3は<strong>Conversations（会話問題）</strong>です。</p>
<ul class="space-y-2">
<li>問題数: <strong>39問</strong>（本番・13会話 × 3問）</li>
<li>形式: 2〜3人の会話を聞き、その内容について3つの設問（各4択）に答える</li>
<li>会話自体は<strong>音声のみ</strong>で、印刷はされない。ただし設問文と選択肢は問題冊子に<strong>印刷されている</strong>（Part1・2との大きな違い）</li>
<li>目標解答時間: <strong>1会話（3問）あたり約45秒</strong></li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>音声が流れる前に3つの設問と選択肢に目を通しておく（先読み）ことで、聞き取るべきポイントを絞り込める。</p>
</div>',
            ],
            [
                'step_number' => 2,
                'slide_type'  => 'strategy',
                'title'       => '設問の「先読み」テクニック',
                'content'     => '<p class="mb-4">Part3で最も重要なテクニックが<strong>先読み（プレビュー）</strong>です。会話の音声が流れる前に、3つの設問文だけを素早く読んでおきます。</p>
<div class="bg-surface-container p-4 rounded-xl mb-4">
<p class="font-semibold mb-2">先読みで確認すること</p>
<ul class="space-y-1">
<li>疑問詞（What / Where / When / Why / How）— 何を聞き取るべきか</li>
<li>主語（man / woman / speakers）— 誰の発言に注目するか</li>
<li>設問の順序 — 会話の流れに沿って出題されることが多い（設問1は前半、設問3は後半のヒントが多い）</li>
</ul>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>選択肢の文章まで全部読む時間はないので、設問文（What / Where など）だけを先に確認するだけでも効果は大きい。</p>
</div>',
            ],
            [
                'step_number' => 3,
                'slide_type'  => 'strategy',
                'title'       => '頻出設問タイプ①：話題・目的・場所',
                'content'     => '<p class="mb-4">会話全体に関わる設問は、多くの場合<strong>会話の冒頭</strong>にヒントがあります。</p>
<ul class="space-y-2">
<li><strong>What are the speakers discussing?</strong>（何について話しているか）</li>
<li><strong>Where does the conversation most likely take place?</strong>（会話の場所）</li>
<li><strong>Why is the man/woman calling?</strong>（電話の目的）</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>最初の1〜2文に集中する。話題や場所を示す単語（office / restaurant / flight など）が直接的なヒントになることが多い。</p>
</div>',
            ],
            [
                'step_number' => 4,
                'slide_type'  => 'strategy',
                'title'       => '頻出設問タイプ②：詳細・次の行動・依頼',
                'content'     => '<p class="mb-4">会話の中盤〜後半に関する設問は、<strong>具体的な発言</strong>を正確に聞き取る必要があります。</p>
<ul class="space-y-2">
<li><strong>What does the woman ask the man to do?</strong>（依頼内容）</li>
<li><strong>What will the man most likely do next?</strong>（次の行動）</li>
<li><strong>What problem does the speaker mention?</strong>（問題点）</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>選択肢は会話中の単語をそのまま使わず、<strong>言い換え（パラフレーズ）</strong>されていることが多い。例：「remote into your machine」→「access his computer remotely」。</p>
</div>',
            ],
            [
                'step_number' => 5,
                'slide_type'  => 'strategy',
                'title'       => 'Part 3 解答戦略',
                'content'     => '<div class="space-y-3">
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">1</span>
<div><p class="font-semibold">音声が流れる前に3問を先読み</p><p class="text-on-surface-variant">疑問詞と主語だけでも確認しておく</p></div>
</div>
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">2</span>
<div><p class="font-semibold">設問の順序 ≒ 会話の流れ</p><p class="text-on-surface-variant">設問1は前半、設問3は後半にヒントがあることが多い</p></div>
</div>
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">3</span>
<div><p class="font-semibold">分からなくても止まらない</p><p class="text-on-surface-variant">1問聞き逃しても、次の設問のヒントに集中を切り替える</p></div>
</div>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 時間配分の目安</p>
<p>Part3全13会話（39問）は<strong>約18分</strong>で進行する。1会話あたり約80秒（音声約30秒＋解答約50秒）が目安。</p>
</div>',
            ],
        ];

        foreach ($slides as $slide) {
            ToeicSlide::create(array_merge($slide, ['part' => 3, 'sort_order' => $slide['step_number']]));
        }
    }

    private function createPart4Slides(): void
    {
        $slides = [
            [
                'step_number' => 1,
                'slide_type'  => 'explanation',
                'title'       => 'Part 4 とは？',
                'content'     => '<p class="mb-4">Part 4は<strong>Talks（説明文問題）</strong>です。</p>
<ul class="space-y-2">
<li>問題数: <strong>30問</strong>（本番・10トーク × 3問）</li>
<li>形式: 1人の話者による短いトーク（留守番電話・アナウンス・広告など）を聞き、その内容について3つの設問（各4択）に答える</li>
<li>トーク自体は<strong>音声のみ</strong>で、印刷はされない。ただし設問文と選択肢は問題冊子に<strong>印刷されている</strong>（Part3と同じ形式）</li>
<li>目標解答時間: <strong>1トーク（3問）あたり約45秒</strong></li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 Part3との違い</p>
<p>Part3は2人以上の<strong>会話</strong>、Part4は1人の話者による<strong>まとまった説明</strong>（アナウンス・留守電・広告など）。話者交代がない分、話の流れ自体は追いやすいが、情報量が多く早口になりやすい。</p>
</div>',
            ],
            [
                'step_number' => 2,
                'slide_type'  => 'strategy',
                'title'       => '頻出トークのジャンル',
                'content'     => '<p class="mb-4">Part4で頻出するトークのジャンルを知っておくと、話の展開を予測しやすくなります。</p>
<ul class="space-y-2">
<li><strong>留守番電話メッセージ（Telephone message）</strong>— 用件・依頼・折り返し先が中心</li>
<li><strong>お知らせ・館内アナウンス（Announcement）</strong>— 場所・時間・注意事項が中心</li>
<li><strong>広告（Advertisement）</strong>— 商品/サービスの特徴・キャンペーン内容</li>
<li><strong>ニュース・天気予報（Broadcast）</strong>— 今日／明日の情報、対応策の提案</li>
<li><strong>会議冒頭の説明・紹介（Excerpt from a meeting）</strong>— 議題・登壇者紹介・進行予定</li>
<li><strong>自動音声案内（Recorded message）</strong>— 番号選択の案内、営業時間</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>ジャンルが分かれば「次に何を言いそうか」を予測しながら聞けるようになる。冒頭の一文でジャンルを見極める練習をしよう。</p>
</div>',
            ],
            [
                'step_number' => 3,
                'slide_type'  => 'strategy',
                'title'       => '設問の「先読み」はPart3と同様に重要',
                'content'     => '<p class="mb-4">Part4もPart3と同じく、音声が流れる前に3つの設問文を先読みしておくことが最重要テクニックです。</p>
<ul class="space-y-2">
<li><strong>What is the purpose of the talk?</strong>（トークの目的）— 冒頭にヒントがあることが多い</li>
<li><strong>What does the speaker mention about...?</strong>（詳細）— 該当箇所を聞き逃さないよう集中する</li>
<li><strong>What will the listener probably do next?</strong>（今後の行動）— トークの終盤にヒントが多い</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>設問の順序はトークの流れ（冒頭→中盤→終盤）にほぼ対応している。設問3は「今後の行動」や「次のステップ」を問う問題が多い。</p>
</div>',
            ],
            [
                'step_number' => 4,
                'slide_type'  => 'strategy',
                'title'       => '数字・固有名詞の聞き取り',
                'content'     => '<p class="mb-4">Part4では時刻・金額・割引率・電話で押す番号など、<strong>具体的な数字</strong>を問う設問が頻出します。</p>
<div class="bg-surface-container p-4 rounded-xl mb-4">
<p class="font-semibold mb-2">数字が絡む頻出パターン</p>
<ul class="space-y-1">
<li>営業時間・締め切り時刻（"before five P.M." など）</li>
<li>割引率・価格（"up to fifty percent" など）</li>
<li>自動音声案内での選択番号（"press one / press two" など）</li>
<li>所要期間（"approximately three weeks" など）</li>
</ul>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>数字はメモを取る余裕がなくても、設問の選択肢が数字の場合は特に集中して聞く。似た数字（fifteen / fifty など）の聞き間違いに注意。</p>
</div>',
            ],
            [
                'step_number' => 5,
                'slide_type'  => 'strategy',
                'title'       => 'Part 4 解答戦略',
                'content'     => '<div class="space-y-3">
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">1</span>
<div><p class="font-semibold">音声が流れる前に3問を先読み</p><p class="text-on-surface-variant">疑問詞と話題のジャンルを予測しておく</p></div>
</div>
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">2</span>
<div><p class="font-semibold">冒頭の1文でジャンルと目的をつかむ</p><p class="text-on-surface-variant">「誰が」「何のために」話しているかを最初に押さえる</p></div>
</div>
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">3</span>
<div><p class="font-semibold">終盤は「次の行動」に注目</p><p class="text-on-surface-variant">依頼・案内・お願いの表現はトークの最後に来ることが多い</p></div>
</div>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 時間配分の目安</p>
<p>Part4全10トーク（30問）は<strong>約16分</strong>で進行する。1トークあたり約90秒（音声約30秒＋解答約60秒）が目安。</p>
</div>',
            ],
        ];

        foreach ($slides as $slide) {
            ToeicSlide::create(array_merge($slide, ['part' => 4, 'sort_order' => $slide['step_number']]));
        }
    }

    private function createPart5Slides(): void
    {
        $slides = [
            [
                'step_number' => 1,
                'slide_type'  => 'explanation',
                'title'       => 'Part 5 とは？',
                'content'     => '<p class="mb-4">Part 5は<strong>Incomplete Sentences（穴埋め問題）</strong>です。</p>
<ul class="space-y-2">
<li>問題数: <strong>30問</strong></li>
<li>形式: 文の空欄に入る最適な語句を4択から選ぶ</li>
<li>出題内容: 文法・語彙・語形変化</li>
<li>目標解答時間: <strong>1問あたり20〜30秒</strong></li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>まず選択肢の品詞を確認する。動詞・名詞・形容詞・副詞の区別が9割の問題を解くカギ。</p>
</div>',
            ],
            [
                'step_number' => 2,
                'slide_type'  => 'grammar',
                'title'       => '頻出文法①：品詞の識別',
                'content'     => '<p class="mb-4">選択肢が同じ語根の異なる品詞の場合、空欄の役割を見極めます。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">例題</p>
<p>The project was completed with remarkable _____ .</p>
<p class="mt-2 text-on-surface-variant">(A) efficient　(B) efficiently　(C) efficiency　(D) efficiencies</p>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">解説</p>
<p>前置詞 "with" の後なので<strong>名詞</strong>が入る → <strong>(C) efficiency</strong></p>
<p class="mt-2">with + 名詞：remarkable efficiency（目覚ましい効率性）</p>
</div>
</div>',
            ],
            [
                'step_number' => 3,
                'slide_type'  => 'grammar',
                'title'       => '頻出文法②：動詞の形',
                'content'     => '<p class="mb-4">主語と動詞の一致、時制、態（能動/受動）を確認します。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">例題</p>
<p>The report _____ by the committee last week.</p>
<p class="mt-2 text-on-surface-variant">(A) reviewed　(B) was reviewed　(C) has reviewed　(D) reviews</p>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">解説</p>
<p>主語 "The report" はレポートを受ける側 → <strong>受動態</strong></p>
<p>"last week" = 過去 → <strong>(B) was reviewed</strong></p>
</div>
</div>',
            ],
            [
                'step_number' => 4,
                'slide_type'  => 'grammar',
                'title'       => '頻出文法③：前置詞 vs 接続詞',
                'content'     => '<p class="mb-4">前置詞（後ろに名詞・動名詞）と接続詞（後ろに主語＋動詞）の混同はPart5最頻出パターンの一つです。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">例題</p>
<p>_____ the heavy rain, the outdoor event was held as scheduled.</p>
<p class="mt-2 text-on-surface-variant">(A) Although　(B) Despite　(C) Because　(D) Even though</p>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">解説</p>
<p>空欄の後は "the heavy rain" という<strong>名詞句</strong>のみ → 前置詞が必要 → <strong>(B) Despite</strong></p>
<p class="mt-2">(A)(D)は接続詞（後ろにSV）。(C) Becauseも接続詞（前置詞として使う場合は because of）。</p>
</div>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 対になる語のペアで覚える</p>
<ul class="space-y-1 mt-2">
<li><strong>because of / due to</strong>（前置詞）↔ <strong>because</strong>（接続詞）</li>
<li><strong>despite / in spite of</strong>（前置詞）↔ <strong>although / though / even though</strong>（接続詞）</li>
<li><strong>during</strong>（前置詞）↔ <strong>while</strong>（接続詞）</li>
</ul>
</div>',
            ],
            [
                'step_number' => 5,
                'slide_type'  => 'grammar',
                'title'       => '頻出文法④：群前置詞・前置詞句',
                'content'     => '<p class="mb-4">2〜3語で1つの前置詞として働く「群前置詞」はPart5・6両方の頻出テーマです。</p>
<ul class="space-y-2">
<li><strong>in charge of</strong> — 〜を担当して</li>
<li><strong>in terms of</strong> — 〜の点では</li>
<li><strong>in addition to</strong> — 〜に加えて</li>
<li><strong>in response to</strong> — 〜に応じて</li>
<li><strong>with respect to</strong> — 〜に関して</li>
<li><strong>on account of</strong> — 〜が原因で</li>
<li><strong>at the expense of</strong> — 〜を犠牲にして</li>
<li><strong>in compliance with</strong> — 〜に従って（法令・規則）</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>選択肢が前置詞ばかりの問題は、後ろの名詞との「セット表現」で覚えているかが決め手。単語単体ではなくフレーズごと暗記する。</p>
</div>',
            ],
            [
                'step_number' => 6,
                'slide_type'  => 'grammar',
                'title'       => '頻出文法⑤：関係代名詞・関係副詞',
                'content'     => '<p class="mb-4">先行詞が「人」か「モノ」か、関係詞節の中で主語・目的語・所有格のどれが欠けているかを確認します。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">例題</p>
<p>Employees _____ performance exceeds expectations will receive a bonus.</p>
<p class="mt-2 text-on-surface-variant">(A) who　(B) whom　(C) whose　(D) which</p>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">解説</p>
<p>空欄の後ろ "performance exceeds..." は完全な文 → 名詞(performance)の前で所有格が必要 → <strong>(C) whose</strong></p>
</div>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 早見表</p>
<ul class="space-y-1 mt-2">
<li><strong>who</strong>：先行詞=人、節内で主語が欠ける</li>
<li><strong>whom</strong>：先行詞=人、節内で目的語が欠ける</li>
<li><strong>whose</strong>：先行詞=人/モノ、節内で名詞の所有格として使う（後ろは完全文）</li>
<li><strong>which</strong>：先行詞=モノ</li>
<li><strong>where / when</strong>：関係副詞。後ろは<strong>完全な文</strong>（主語・目的語が欠けない）</li>
</ul>
</div>',
            ],
            [
                'step_number' => 7,
                'slide_type'  => 'grammar',
                'title'       => '頻出文法⑥：比較級・最上級',
                'content'     => '<p class="mb-4">比較の形（原級・比較級・最上級）とそれに伴う語法をセットで確認します。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">例題</p>
<p>This year\'s sales figures are much _____ than last year\'s.</p>
<p class="mt-2 text-on-surface-variant">(A) high　(B) higher　(C) highest　(D) highly</p>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">解説</p>
<p>"than" があるので比較級 → <strong>(B) higher</strong>。"much" は比較級を強調する副詞。</p>
</div>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 セットで覚える語法</p>
<ul class="space-y-1 mt-2">
<li><strong>as + 原級 + as</strong>（〜と同じくらい）</li>
<li><strong>比較級 + than</strong></li>
<li><strong>the + 最上級 + of/in</strong></li>
<li>比較級の強調：<strong>much / far / even / a lot</strong>（× very は使えない）</li>
<li><strong>the + 比較級 〜, the + 比較級 …</strong>（〜すればするほど…）</li>
</ul>
</div>',
            ],
            [
                'step_number' => 8,
                'slide_type'  => 'vocabulary',
                'title'       => '頻出語彙①：ビジネス動詞',
                'content'     => '<ul class="space-y-3">
<li><strong>implement</strong> — 〜を実施する・実行する</li>
<li><strong>facilitate</strong> — 〜を促進する・容易にする</li>
<li><strong>negotiate</strong> — 〜を交渉する</li>
<li><strong>allocate</strong> — 〜を割り当てる・配分する</li>
<li><strong>coordinate</strong> — 〜を調整する・まとめる</li>
<li><strong>evaluate</strong> — 〜を評価する・査定する</li>
</ul>',
            ],
            [
                'step_number' => 9,
                'slide_type'  => 'vocabulary',
                'title'       => '頻出語彙②：紛らわしい語のペア',
                'content'     => '<p class="mb-4">スペルや意味が似ていて混同しやすい語のペアは、語彙問題の定番です。</p>
<ul class="space-y-3">
<li><strong>affect</strong>（動詞：〜に影響する） / <strong>effect</strong>（名詞：効果・影響）</li>
<li><strong>raise</strong>（他動詞：〜を上げる、目的語が必要） / <strong>rise</strong>（自動詞：上がる）</li>
<li><strong>economic</strong>（経済の） / <strong>economical</strong>（経済的な・節約になる）</li>
<li><strong>considerate</strong>（思いやりがある） / <strong>considerable</strong>（かなりの量の）</li>
<li><strong>respective</strong>（それぞれの） / <strong>respectful</strong>（敬意を払う）</li>
<li><strong>confident</strong>（自信がある） / <strong>confidential</strong>（機密の）</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>語尾のパターン（-ent / -ial、-ic / -ical）を意識して意味の違いを整理しておくと、初見の単語でも推測しやすくなる。</p>
</div>',
            ],
            [
                'step_number' => 10,
                'slide_type'  => 'vocabulary',
                'title'       => '頻出語彙③：ビジネス頻出名詞・形容詞',
                'content'     => '<p class="mb-4">Part5〜7全体で繰り返し登場するビジネス頻出語です。</p>
<ul class="space-y-2">
<li><strong>merger</strong> — 合併</li>
<li><strong>subsidiary</strong> — 子会社</li>
<li><strong>invoice</strong> — 請求書</li>
<li><strong>reimbursement</strong> — 払い戻し・経費精算</li>
<li><strong>candidate</strong> — 候補者</li>
<li><strong>enclosed</strong> — 同封の</li>
<li><strong>reputable</strong> — 評判の良い</li>
<li><strong>versatile</strong> — 多才な・汎用性のある</li>
<li><strong>mandatory</strong> — 義務的な・必須の</li>
<li><strong>preliminary</strong> — 予備の・準備の</li>
</ul>',
            ],
            [
                'step_number' => 11,
                'slide_type'  => 'grammar',
                'title'       => '頻出文法⑦：数量表現・呼応表現',
                'content'     => '<p class="mb-4">数量を表す語は、後ろに来る名詞が可算/不可算・単数/複数かで使い分けます。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">例題</p>
<p>_____ of the applicants have relevant work experience.</p>
<p class="mt-2 text-on-surface-variant">(A) Each　(B) Every　(C) Most　(D) Much</p>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">解説</p>
<p>"of the applicants"（複数名詞）を受けられるのは <strong>(C) Most</strong>。Each/Everyは単数名詞、Muchは不可算名詞に使う。</p>
</div>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 早見表</p>
<ul class="space-y-1 mt-2">
<li><strong>each / every / another</strong> + 単数名詞</li>
<li><strong>both / few / many / several / others</strong> + 複数名詞</li>
<li><strong>much / little</strong> + 不可算名詞</li>
<li><strong>most / all / some / a lot of</strong> + 可算/不可算どちらも可</li>
<li><strong>either / neither</strong> + of + 複数名詞（+ 単数扱いの動詞）</li>
</ul>
</div>',
            ],
            [
                'step_number' => 12,
                'slide_type'  => 'strategy',
                'title'       => 'Part 5 解答戦略',
                'content'     => '<div class="space-y-3">
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">1</span>
<div>
<p class="font-semibold">選択肢を先に見る（5秒）</p>
<p class="text-on-surface-variant">同語根なら品詞問題。バラバラなら語彙・前置詞問題と判断</p>
</div>
</div>
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">2</span>
<div>
<p class="font-semibold">空欄の前後を確認（10秒）</p>
<p class="text-on-surface-variant">空欄の前後の品詞・時制・能動受動を確認</p>
</div>
</div>
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">3</span>
<div>
<p class="font-semibold">迷ったら次へ（30秒ルール）</p>
<p class="text-on-surface-variant">1問30秒以上かけない。後で見直す</p>
</div>
</div>
</div>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 時間配分の目安</p>
<p>Part5全30問は<strong>10分以内</strong>で解き切るのが理想。Part6・7に多くの時間を残すため、Part5でじっくり考えすぎないことが本試験全体のスコアを左右する。</p>
</div>',
            ],
        ];

        foreach ($slides as $slide) {
            ToeicSlide::create(array_merge($slide, ['part' => 5, 'sort_order' => $slide['step_number']]));
        }
    }

    private function createPart6Slides(): void
    {
        $slides = [
            [
                'step_number' => 1,
                'slide_type'  => 'explanation',
                'title'       => 'Part 6 とは？',
                'content'     => '<p class="mb-4">Part 6は<strong>Text Completion（長文穴埋め問題）</strong>です。</p>
<ul class="space-y-2">
<li>問題数: <strong>16問（4文書 × 4問）</strong></li>
<li>形式: メール・手紙・記事など長文の空欄を埋める</li>
<li>出題内容: 文法・語彙・文脈理解・文挿入</li>
<li>目標解答時間: <strong>1文書あたり2〜3分</strong></li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 Part 5との違い</p>
<p>Part 6では文脈を読み解く問題（接続副詞・文挿入）が含まれる。文全体の流れを把握することが重要。</p>
</div>',
            ],
            [
                'step_number' => 2,
                'slide_type'  => 'idiom',
                'title'       => '頻出イディオム①',
                'content'     => '<ul class="space-y-3">
<li><strong>in light of</strong> — 〜を考慮して・〜に鑑みて</li>
<li><strong>with regard to</strong> — 〜に関して</li>
<li><strong>as a result of</strong> — 〜の結果として</li>
<li><strong>prior to</strong> — 〜の前に</li>
<li><strong>in accordance with</strong> — 〜に従って</li>
<li><strong>on behalf of</strong> — 〜を代表して・〜に代わって</li>
</ul>',
            ],
            [
                'step_number' => 3,
                'slide_type'  => 'idiom',
                'title'       => '頻出イディオム②・接続副詞',
                'content'     => '<p class="mb-4">文と文をつなぐ「接続副詞」は文脈問題の最重要ポイントです。前後の意味の関係（逆接・追加・結果・言い換え）を意識しましょう。</p>
<ul class="space-y-3">
<li><strong>however</strong> — しかしながら（逆接）</li>
<li><strong>therefore / thus / consequently</strong> — したがって（結果）</li>
<li><strong>in addition / furthermore / moreover</strong> — さらに（追加）</li>
<li><strong>otherwise</strong> — さもなければ（条件の帰結）</li>
<li><strong>meanwhile / in the meantime</strong> — その一方で・その間（同時進行）</li>
<li><strong>for example / for instance</strong> — 例えば（具体例）</li>
<li><strong>in fact</strong> — 実際は（強調・補足）</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>空欄の前後の文をざっくり訳し、「つながり方」を確認してから選択肢を当てはめる。単語の意味だけで即決しない。</p>
</div>',
            ],
            [
                'step_number' => 4,
                'slide_type'  => 'strategy',
                'title'       => '文挿入問題（Sentence Insertion）攻略法',
                'content'     => '<p class="mb-4">Part6には「この文を挿入するのに最も適切な位置は？」という<strong>文挿入問題</strong>が各文書に1問含まれます（全4問）。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">解き方の手順</p>
<ol class="list-decimal list-inside space-y-1">
<li>挿入する文に含まれる<strong>代名詞・指示語</strong>（this, it, these, such）を確認する</li>
<li>その代名詞が指す内容が、どの空欄の直前にあるかを探す</li>
<li>挿入文が<strong>時系列・因果関係</strong>のどこに来るかを確認する（after that, as a result 等の合図語に注目）</li>
<li>他の3問（文法・語彙）を先に解いてから、最後に取り組むと効率的</li>
</ol>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>文挿入問題は文書全体の理解が必要なため最も時間がかかる。他の3問を先に解いて文書内容を把握してから最後に解くと正答率が上がる。</p>
</div>
</div>',
            ],
            [
                'step_number' => 5,
                'slide_type'  => 'strategy',
                'title'       => 'ビジネス文書のフォーマット',
                'content'     => '<p class="mb-4">Part6でよく出る文書の型を知っておくと、話の流れを予測しやすくなります。</p>
<ul class="space-y-3">
<li><strong>Eメール</strong> — 件名(Subject)・宛先・本文・署名の順。冒頭で目的、末尾で依頼/次のアクションが多い</li>
<li><strong>社内メモ (Memo)</strong> — To/From/Date/Subjectのヘッダー。社内向けの通知・指示</li>
<li><strong>お知らせ (Notice / Announcement)</strong> — 制度変更・イベント・工事案内など</li>
<li><strong>広告 (Advertisement)</strong> — 商品・サービスの宣伝。割引条件や連絡先が末尾に来ることが多い</li>
<li><strong>記事 (Article)</strong> — ニュース性のある内容。5W1Hを意識して読む</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>文書の型がわかれば、空欄に入るべき情報の「立ち位置」（導入・詳細・結論）が予測でき、文法問題も文脈問題も解きやすくなる。</p>
</div>',
            ],
            [
                'step_number' => 6,
                'slide_type'  => 'strategy',
                'title'       => 'Part 6 解答戦略',
                'content'     => '<div class="space-y-3">
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">1</span>
<div><p class="font-semibold">文書の種類と文脈を把握</p><p class="text-on-surface-variant">メール？報告書？広告？文書の目的を最初に掴む</p></div>
</div>
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">2</span>
<div><p class="font-semibold">空欄前後の接続関係に注意</p><p class="text-on-surface-variant">However / Therefore などの接続詞は文脈問題のヒント</p></div>
</div>
<div class="flex items-start gap-3">
<span class="text-xl font-bold text-primary">3</span>
<div><p class="font-semibold">文挿入問題は最後に解く</p><p class="text-on-surface-variant">前後の段落との論理的なつながりを確認し、他の3問を解いた後に取り組む</p></div>
</div>
</div>',
            ],
        ];

        foreach ($slides as $slide) {
            ToeicSlide::create(array_merge($slide, ['part' => 6, 'sort_order' => $slide['step_number']]));
        }
    }

    private function createPart7Slides(): void
    {
        $slides = [
            [
                'step_number' => 1,
                'slide_type'  => 'explanation',
                'title'       => 'Part 7 とは？',
                'content'     => '<p class="mb-4">Part 7は<strong>Reading Comprehension（読解問題）</strong>です。</p>
<ul class="space-y-2">
<li>問題数: <strong>54問</strong></li>
<li>形式: 単一文書・複数文書の読解</li>
<li>出題内容: 主題・詳細・推測・語句の意味</li>
<li>目標解答時間: <strong>全体で55分</strong></li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 攻略の鍵</p>
<p>全文を読まず、設問を先に読んで必要な情報をスキャニングする技術が重要。</p>
</div>',
            ],
            [
                'step_number' => 2,
                'slide_type'  => 'strategy',
                'title'       => 'スキャニング・スキミング技法',
                'content'     => '<div class="space-y-4">
<div class="p-4 bg-surface-container rounded-xl">
<p class="font-semibold mb-2">スキャニング（特定情報の検索）</p>
<p>日付・数字・固有名詞など特定の情報を素早く見つける技法。設問に数字・名前が含まれる場合に使用。</p>
</div>
<div class="p-4 bg-surface-container rounded-xl">
<p class="font-semibold mb-2">スキミング（全体把握）</p>
<p>文書全体を素早く読んで主題と要点を把握する技法。"What is the purpose of..." という問題に使用。</p>
</div>
</div>',
            ],
            [
                'step_number' => 3,
                'slide_type'  => 'strategy',
                'title'       => '設問タイプ別攻略法①：主題・目的問題',
                'content'     => '<p class="mb-4">「What is the purpose of the memo?」「What is the article mainly about?」等、文書全体の要旨を問う問題です。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">解き方</p>
<ul class="space-y-1">
<li>文書の<strong>冒頭1〜2文</strong>と<strong>件名・タイトル</strong>に答えがあることが多い</li>
<li>Eメールなら "I am writing to..."、"This is to inform you that..." に注目</li>
<li>本文の一部の詳細ではなく、文書全体を要約した選択肢を選ぶ</li>
</ul>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>設問は必ず先に読み、冒頭を丁寧に読めば大半は正解できる。設問文にある "purpose / mainly about / written for" は主題問題の合図。</p>
</div>
</div>',
            ],
            [
                'step_number' => 4,
                'slide_type'  => 'strategy',
                'title'       => '設問タイプ別攻略法②：詳細・NOT/TRUE問題',
                'content'     => '<p class="mb-4">「According to the e-mail, what will happen on Monday?」のような詳細問題、「What is NOT mentioned...」のようなNOT問題です。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">解き方</p>
<ul class="space-y-1">
<li>詳細問題は設問中の<strong>固有名詞・数字・曜日</strong>をキーワードにスキャニングする</li>
<li>NOT/TRUE問題は選択肢を1つずつ本文と照合し、消去法で解く（最も時間がかかる問題タイプ）</li>
<li>選択肢の言い換え（パラフレーズ）に注意。本文と同じ単語が使われているとは限らない</li>
</ul>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>NOT問題は最後に解く。先に他の設問を解くことで本文の内容を把握でき、消去法のスピードが上がる。</p>
</div>
</div>',
            ],
            [
                'step_number' => 5,
                'slide_type'  => 'strategy',
                'title'       => '設問タイプ別攻略法③：推測問題・語彙問題',
                'content'     => '<p class="mb-4">「What can be inferred about...?」（推測）、「The word "X" in paragraph 2, line 3, is closest in meaning to」（同義語）タイプです。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">解き方</p>
<ul class="space-y-1">
<li>推測問題は本文に<strong>直接書かれていない</strong>が、状況から論理的に導ける内容が正解</li>
<li>語彙問題は指定された行(line)の<strong>文脈</strong>から意味を判断する。単語の一般的な意味ではなく、その文での使われ方を優先する</li>
<li>言い換え表現（本文の語句を別の語で表した選択肢）が正解になりやすい</li>
</ul>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">💡 攻略ポイント</p>
<p>推測問題は「本文に書いていないから不正解」と思いがちだが、行間を読んで論理的に導ける選択肢が正解になる点に注意。</p>
</div>
</div>',
            ],
            [
                'step_number' => 6,
                'slide_type'  => 'strategy',
                'title'       => 'シングル/ダブル/トリプルパッセージ攻略',
                'content'     => '<p class="mb-4">Part7後半は複数の文書を読んで解答する<strong>マルチプルパッセージ</strong>問題です。</p>
<ul class="space-y-2">
<li><strong>シングルパッセージ</strong>（29問）— 1つの文書につき2〜4問</li>
<li><strong>ダブルパッセージ</strong>（2セット・10問）— 2つの文書（例：メール＋請求書）</li>
<li><strong>トリプルパッセージ</strong>（3セット・15問）— 3つの文書（例：広告＋メール＋スケジュール表）</li>
</ul>
<div class="mt-4 p-4 bg-primary/10 rounded-xl">
<p class="font-semibold text-primary">💡 クロスリファレンス問題</p>
<p>複数文書の情報を組み合わせないと解けない問題（例：文書1の割引条件 × 文書2の注文金額）が各セット1問程度出題される。文書ごとに読むのではなく、設問のキーワードを軸に必要な箇所だけを行き来して探すのがコツ。</p>
</div>',
            ],
            [
                'step_number' => 7,
                'slide_type'  => 'strategy',
                'title'       => '頻出文書タイプと時間配分戦略',
                'content'     => '<p class="mb-4">Part7全体（54問・約55分）を時間内に解き切るための配分です。</p>
<div class="space-y-3">
<div class="bg-surface-container p-4 rounded-xl">
<p class="font-semibold mb-2">頻出文書タイプ</p>
<p>Eメール・広告・記事・お知らせ・チャット形式（テキストメッセージ）・スケジュール表・請求書など。特にチャット形式は「意図問題」（What does the writer mean when he writes "..."?）が頻出。</p>
</div>
<div class="bg-primary/10 p-4 rounded-xl">
<p class="font-semibold text-primary">💡 時間配分の目安</p>
<ul class="space-y-1 mt-2">
<li>シングルパッセージ：1問あたり<strong>約1分</strong></li>
<li>ダブル/トリプルパッセージ：1セットあたり<strong>約4〜5分</strong></li>
<li>残り時間が少ない場合は、NOT問題や推測問題より先に主題・詳細問題を優先して確保する</li>
</ul>
</div>
</div>',
            ],
        ];

        foreach ($slides as $slide) {
            ToeicSlide::create(array_merge($slide, ['part' => 7, 'sort_order' => $slide['step_number']]));
        }
    }
}
