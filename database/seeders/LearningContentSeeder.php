<?php

namespace Database\Seeders;

use App\Models\English\LearningContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LearningContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        LearningContent::query()->delete();

        $this->createOverviewContents();
        $this->createStrategyContents();
    }

    private function createOverviewContents(): void
    {
        $contents = [
            // TOEIC 試験概要
            [
                'content_type' => 'overview',
                'exam_type'    => 'TOEIC',
                'target_level' => null,
                'title'        => 'TOEICとは？試験の概要と構成',
                'body'         => '<h2>TOEIC (Test of English for International Communication) とは</h2>
<p>TOEICは、英語を母国語としない人を対象にした英語コミュニケーション能力を評価する国際的な試験です。日本では就職・昇進の基準として広く採用されています。</p>

<h3>試験の構成（TOEIC L&R）</h3>
<table class="w-full border-collapse my-4">
<thead><tr class="bg-surface-container">
<th class="border border-outline p-3 text-left">セクション</th>
<th class="border border-outline p-3 text-left">問題数</th>
<th class="border border-outline p-3 text-left">時間</th>
</tr></thead>
<tbody>
<tr><td class="border border-outline p-3">Listening (Part 1-4)</td><td class="border border-outline p-3">100問</td><td class="border border-outline p-3">45分</td></tr>
<tr><td class="border border-outline p-3">Reading (Part 5-7)</td><td class="border border-outline p-3">100問</td><td class="border border-outline p-3">75分</td></tr>
</tbody>
</table>

<h3>スコアの目安</h3>
<ul>
<li>600点以上: 日常的なビジネスコミュニケーションが可能</li>
<li>700点以上: 海外取引先との基本的なやり取りが可能</li>
<li>800点以上: ほぼすべてのビジネス場面で対応可能</li>
<li>900点以上: ネイティブとほぼ同等の英語力</li>
</ul>

<h3>Kredo Plus で学べる内容</h3>
<ul>
<li><strong>Part 5</strong>: 文法問題（穴埋め）</li>
<li><strong>Part 6</strong>: 長文穴埋め問題</li>
<li><strong>Part 7</strong>: 読解問題</li>
</ul>',
                'sort_order'   => 1,
                'is_published' => true,
            ],
            // IELTS 試験概要
            [
                'content_type' => 'overview',
                'exam_type'    => 'IELTS',
                'target_level' => null,
                'title'        => 'IELTSとは？試験の概要と構成',
                'body'         => '<h2>IELTS (International English Language Testing System) とは</h2>
<p>IELTSは英国、オーストラリア、カナダへの留学・移住に必要な英語力を証明する国際的な試験です。スコアは1.0〜9.0のバンドスコアで表示されます。</p>

<h3>試験の構成</h3>
<table class="w-full border-collapse my-4">
<thead><tr class="bg-surface-container">
<th class="border border-outline p-3 text-left">セクション</th>
<th class="border border-outline p-3 text-left">時間</th>
<th class="border border-outline p-3 text-left">内容</th>
</tr></thead>
<tbody>
<tr><td class="border border-outline p-3">Listening</td><td class="border border-outline p-3">30分</td><td class="border border-outline p-3">4つのセクション、40問</td></tr>
<tr><td class="border border-outline p-3">Reading</td><td class="border border-outline p-3">60分</td><td class="border border-outline p-3">3つの長文、40問</td></tr>
<tr><td class="border border-outline p-3">Writing</td><td class="border border-outline p-3">60分</td><td class="border border-outline p-3">Task 1 + Task 2</td></tr>
<tr><td class="border border-outline p-3">Speaking</td><td class="border border-outline p-3">11-14分</td><td class="border border-outline p-3">3パート面接形式</td></tr>
</tbody>
</table>

<h3>バンドスコアの目安</h3>
<ul>
<li>5.5: 英語圏の大学への条件付き入学許可</li>
<li>6.0: 多くの大学学部の入学要件</li>
<li>6.5: 大学院の入学要件（多くの場合）</li>
<li>7.0: 一流大学・難関大学院の要件</li>
</ul>

<h3>Kredo Plus で学べる内容</h3>
<ul>
<li><strong>Speaking Part 1</strong>: 個人的な話題について話す（4〜5分）</li>
<li><strong>Speaking Part 3</strong>: 社会的トピックについて議論する（4〜5分）</li>
</ul>',
                'sort_order'   => 2,
                'is_published' => true,
            ],
        ];

        foreach ($contents as $data) {
            LearningContent::create($data);
        }
    }

    private function createStrategyContents(): void
    {
        $strategies = [
            // TOEIC 戦略
            ['exam' => 'TOEIC', 'level' => '600', 'sort' => 1, 'title' => 'TOEIC 600点突破ストラテジー',
             'body' => '<h2>600点突破の学習戦略</h2>
<h3>1. 優先的に取り組むこと</h3>
<ul>
<li>基本文法の完全習得（特に品詞・時制・態）</li>
<li>TOEIC頻出語彙100語のマスター</li>
<li>Part 5の正答率を80%以上にする</li>
</ul>
<h3>2. 学習スケジュール（目安）</h3>
<ul>
<li>平日: 単語20分 + Part5練習20分</li>
<li>週末: 模擬問題45分 + 復習30分</li>
<li>目標期間: 3〜4ヶ月</li>
</ul>
<h3>3. よくある間違い</h3>
<ul>
<li>文法書を読むだけで問題練習を怠る</li>
<li>間違えた問題の解説を読まない</li>
<li>リスニング（Part1-4）の練習を後回しにする</li>
</ul>'],
            ['exam' => 'TOEIC', 'level' => '700', 'sort' => 2, 'title' => 'TOEIC 700点突破ストラテジー',
             'body' => '<h2>700点突破の学習戦略</h2>
<h3>1. 優先的に取り組むこと</h3>
<ul>
<li>Part 5の正答率を90%以上に引き上げる</li>
<li>Part 6の文脈理解問題（接続詞・指示語）の精度を高める</li>
<li>Part 7の速読力の向上（1文書あたり2〜3分）</li>
</ul>
<h3>2. 学習スケジュール（目安）</h3>
<ul>
<li>平日: 単語15分 + Part5/6練習30分</li>
<li>週末: 模擬Reading全体 75分 + 復習45分</li>
<li>目標期間: 4〜5ヶ月</li>
</ul>'],
            ['exam' => 'TOEIC', 'level' => '800', 'sort' => 3, 'title' => 'TOEIC 800点突破ストラテジー',
             'body' => '<h2>800点突破の学習戦略</h2>
<h3>1. 優先的に取り組むこと</h3>
<ul>
<li>Part 7で時間切れにならない読解スピードの養成</li>
<li>複数文書問題（ダブル・トリプルパッセージ）の攻略</li>
<li>TOEIC上級語彙（800〜900点レベル）の習得</li>
</ul>
<h3>2. 学習スケジュール（目安）</h3>
<ul>
<li>毎日: Part7長文1文書（精読）+ 模擬問題</li>
<li>週末: 公式問題集 2セット分</li>
<li>目標期間: 5〜6ヶ月</li>
</ul>'],
            ['exam' => 'TOEIC', 'level' => '900', 'sort' => 4, 'title' => 'TOEIC 900点突破ストラテジー',
             'body' => '<h2>900点突破の学習戦略</h2>
<h3>1. 優先的に取り組むこと</h3>
<ul>
<li>ビジネス英字紙（FT、WSJ）の購読で実践力強化</li>
<li>ミスの傾向分析と弱点の徹底強化</li>
<li>時間配分の完全最適化</li>
</ul>
<h3>2. 注意点</h3>
<ul>
<li>高得点帯はわずかなミスが大きなスコア差を生む</li>
<li>ケアレスミスの完全排除が鍵</li>
</ul>'],
            // IELTS 戦略
            ['exam' => 'IELTS', 'level' => '5.5', 'sort' => 5, 'title' => 'IELTS 5.5突破ストラテジー',
             'body' => '<h2>IELTS 5.5 攻略戦略</h2>
<h3>Speakingでのポイント</h3>
<ul>
<li>基本的な文法で正確に話す（複雑な構文より正確さ優先）</li>
<li>質問に直接答える（話が脱線しない）</li>
<li>知っている語彙を正しく使う</li>
</ul>
<h3>学習の進め方</h3>
<ul>
<li>基本語彙100語の習得</li>
<li>シンプルな構文（SVO）を正確に使う練習</li>
<li>毎日5分間の英語スピーキング練習</li>
</ul>'],
            ['exam' => 'IELTS', 'level' => '6.0', 'sort' => 6, 'title' => 'IELTS 6.0突破ストラテジー',
             'body' => '<h2>IELTS 6.0 攻略戦略</h2>
<h3>Speakingでのポイント</h3>
<ul>
<li>意見を明確に述べ、理由と例を加える（PEE構造）</li>
<li>Linking words を積極的に使う（however, furthermore, etc.）</li>
<li>流暢さと正確さのバランスを保つ</li>
</ul>
<h3>学習の進め方</h3>
<ul>
<li>IELTS頻出トピック（教育・環境・テクノロジー）の語彙を強化</li>
<li>PEE構造（Point・Evidence・Explanation）で意見を述べる練習</li>
</ul>'],
            ['exam' => 'IELTS', 'level' => '6.5', 'sort' => 7, 'title' => 'IELTS 6.5突破ストラテジー',
             'body' => '<h2>IELTS 6.5 攻略戦略</h2>
<h3>Speakingでのポイント</h3>
<ul>
<li>複雑な文構造（仮定法・関係節）を適切に使う</li>
<li>高度な語彙を文脈に合わせて使用する</li>
<li>抽象的なトピックでも一貫した意見を述べる</li>
</ul>
<h3>学習の進め方</h3>
<ul>
<li>上級語彙（IELTS 6.5レベル）の習得</li>
<li>複雑な構文の練習（It is argued that / Given that...）</li>
<li>模擬面接（Part 2の2分間スピーチ）の練習</li>
</ul>'],
            ['exam' => 'IELTS', 'level' => '7.0', 'sort' => 8, 'title' => 'IELTS 7.0突破ストラテジー',
             'body' => '<h2>IELTS 7.0 攻略戦略</h2>
<h3>Speakingでのポイント</h3>
<ul>
<li>ネイティブに近い自然な流暢さを目指す</li>
<li>精度の高い語彙使用（コロケーション・慣用句）</li>
<li>話題を深く掘り下げ、批判的思考を示す</li>
</ul>
<h3>学習の進め方</h3>
<ul>
<li>英語でのニュース・ポッドキャスト（BBC, TED）を毎日聴く</li>
<li>IELTS 7.0レベルの語彙・表現の徹底習得</li>
<li>模擬試験を本番と同じ条件で行い、客観的な評価を受ける</li>
</ul>'],
        ];

        foreach ($strategies as $data) {
            LearningContent::create([
                'content_type' => 'strategy',
                'exam_type'    => $data['exam'],
                'target_level' => $data['level'],
                'title'        => $data['title'],
                'body'         => $data['body'],
                'sort_order'   => $data['sort'],
                'is_published' => true,
            ]);
        }
    }
}
