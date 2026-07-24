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
        ToeicSlide::whereIn('part', [5, 6, 7])->delete();

        $this->createPart5Slides();
        $this->createPart6Slides();
        $this->createPart7Slides();
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
