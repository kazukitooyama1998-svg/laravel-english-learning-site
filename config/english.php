<?php

/**
 * 英語学習機能 設定ファイル
 *
 * Route の whereIn バリデーションや Controller 内の検証で使用する。
 * 将来新しいスコア・トピック・レベルが追加された場合はここだけを更新する。
 */

return [

    /*
    |--------------------------------------------------------------------------
    | TOEIC 設定
    |--------------------------------------------------------------------------
    */

    // URL パラメータ {part} として許可する値
    'toeic_parts' => ['1', '2', '3', '4', '5', '6', '7'],

    // TOEIC Part ごとのメタ情報（今後 DB 管理に移行可能）
    'toeic_part_meta' => [
        1 => ['name' => 'Part 1', 'desc' => 'Photographs (Listening)',             'available' => true],
        2 => ['name' => 'Part 2', 'desc' => 'Question-Response (Listening)',        'available' => true],
        3 => ['name' => 'Part 3', 'desc' => 'Conversations (Listening)',            'available' => true],
        4 => ['name' => 'Part 4', 'desc' => 'Talks (Listening)',                   'available' => true],
        5 => ['name' => 'Part 5', 'desc' => 'Incomplete Sentences (Grammar)',       'available' => true],
        6 => ['name' => 'Part 6', 'desc' => 'Text Completion (Reading)',            'available' => true],
        7 => ['name' => 'Part 7', 'desc' => 'Reading Comprehension',               'available' => true],
    ],

    /*
    |--------------------------------------------------------------------------
    | IELTS 設定
    |--------------------------------------------------------------------------
    */

    // URL パラメータ {part} として許可する値
    'ielts_parts' => ['1', '2', '3'],

    // URL パラメータ {topic} として許可する値
    'ielts_topics' => ['education', 'technology', 'environment'],

    // URL パラメータ {score} として許可する値
    'ielts_scores' => ['5.5', '6.0', '6.5', '7.0'],

    // IELTS スコアのメタ情報
    'ielts_score_meta' => [
        '5.5' => [
            'level' => 'Modest User',
            'desc'  => '部分的なコミュニケーションが可能なレベル。身近な話題なら意見を伝えられるが、複雑な文法や語彙でミスが目立ち、言葉に詰まって同じ表現を繰り返しがち。基本文型を正確に使うことと、頻出フレーズのストックを増やすことが得点アップの鍵。',
        ],
        '6.0' => [
            'level' => 'Competent User',
            'desc'  => '大学・実務レベルで概ね通用するスコア。身近な話題は流暢に話せるが、抽象的・専門的な内容になると語彙不足や言い淀みが出やすい。however・thereforeなどのLinking wordsを使った論理的な構成と、具体例を交えた説明力を磨くことが次のスコアへの鍵。',
        ],
        '6.5' => [
            'level' => 'Good User',
            'desc'  => '大学院・専門職レベルで通用する実践的な英語力。複雑な話題にも幅広い語彙と文法で対応できるが、細かい言い間違いや発音のブレが残る段階。イディオムや高度な言い換え表現を自然に使いこなし、一貫した論理展開を保つことが7.0への壁を越えるポイント。',
        ],
        '7.0' => [
            'level' => 'Good User+',
            'desc'  => '流暢かつ正確に、あらゆる話題を柔軟に語れる高スコア帯。語彙選択・文法・発音のいずれも自然でネイティブに近い説明力を持つ。試験本番では質問の意図を正確に汲み取り、簡潔かつ説得力のある構成で答える意識を持つとさらに安定する。',
        ],
    ],

    // IELTS トピックのメタ情報
    'ielts_topic_meta' => [
        'education'   => ['name' => 'Education',   'icon' => 'school', 'desc' => '学校・大学・教育制度・学習方法について話す'],
        'technology'  => ['name' => 'Technology',  'icon' => 'devices', 'desc' => 'SNS・AI・デジタル技術の影響について話す'],
        'environment' => ['name' => 'Environment', 'icon' => 'eco', 'desc' => '環境問題・気候変動・持続可能性について話す'],
    ],

    // IELTS Part のメタ情報
    'ielts_part_meta' => [
        1 => ['name' => 'Speaking Part 1', 'desc' => 'Introduction and interview. Answer questions about yourself and everyday topics.', 'icon' => 'person',  'badge' => null],
        2 => ['name' => 'Speaking Part 2', 'desc' => 'Long turn. Speak about a given topic for 1-2 minutes.',                         'icon' => 'mic',    'badge' => null],
        3 => ['name' => 'Speaking Part 3', 'desc' => 'Discussion. Answer more abstract questions related to Part 2 topic.',            'icon' => 'forum',  'badge' => null],
    ],

    /*
    |--------------------------------------------------------------------------
    | 英単語 設定
    |--------------------------------------------------------------------------
    | URL パラメータ {level} → DB クエリ用 (exam_type, level) の変換マップ。
    | user_section_progress.section_key には URL パラメータ値をそのまま使用する。
    |--------------------------------------------------------------------------
    */

    'vocabulary_levels' => [
        'toeic-600' => ['exam_type' => 'TOEIC', 'level' => '600'],
        'toeic-700' => ['exam_type' => 'TOEIC', 'level' => '700'],
        'toeic-800' => ['exam_type' => 'TOEIC', 'level' => '800'],
        'toeic-900' => ['exam_type' => 'TOEIC', 'level' => '900'],
        'ielts-55'  => ['exam_type' => 'IELTS', 'level' => '5.5'],
        'ielts-60'  => ['exam_type' => 'IELTS', 'level' => '6.0'],
        'ielts-65'  => ['exam_type' => 'IELTS', 'level' => '6.5'],
        'ielts-70'  => ['exam_type' => 'IELTS', 'level' => '7.0'],
    ],

    // URL パラメータ {level} として許可する値（vocabulary_levels のキー一覧）
    'vocabulary_level_slugs' => [
        'toeic-600', 'toeic-700', 'toeic-800', 'toeic-900',
        'ielts-55', 'ielts-60', 'ielts-65', 'ielts-70',
    ],

    /*
    |--------------------------------------------------------------------------
    | 試験概要 / ストラテジー 設定
    |--------------------------------------------------------------------------
    */

    // URL パラメータ {exam} として許可する値
    'exam_types' => ['ielts', 'toeic'],

    // ストラテジー: URL パラメータ {level} として許可する値 (exam ごと)
    'strategy_levels' => [
        'toeic' => ['600', '700', '800', '900'],
        'ielts' => ['55', '60', '65', '70'],
    ],

    /*
    |--------------------------------------------------------------------------
    | XP 設計
    |--------------------------------------------------------------------------
    */

    'xp' => [
        'ielts_slides_complete'       => 20,
        'ielts_typing_accuracy_high'  => 150, // 90% 以上
        'ielts_typing_accuracy_mid'   => 100, // 70〜89%
        'ielts_typing_accuracy_low'   => 50,  // 70% 未満
        'typing_slides_complete'      => 20,
        'flashcard_set_complete'      => 30,
        'spelling_10_complete'        => 30,
        'quiz_per_correct'            => 5,   // スペルクイズ・語彙クイズ 1問あたり
        'toeic_per_correct'           => 10,
        'toeic_bonus_high'            => 100, // 正答率 80% 以上
        'toeic_bonus_low'             => 50,  // 正答率 80% 未満
    ],

    /*
    |--------------------------------------------------------------------------
    | キャラクター設定（アカウント作成後に 1 匹を選んでもらう）
    |--------------------------------------------------------------------------
    |
    | 「英語の森」を歩くアバターと、プロフィールに表示するアイコン。
    | 8 匹すべて見た目・配色が異なるオリジナルデザイン（特定作品の
    | キャラクターの模写ではなく、「かわいい動物の住人」という
    | 共通コンセプトのみを参考にしている）。
    | body/belly/accent は 2D バッジ（SVG）と 3D モデルの両方で使う配色。
    |
    */

    'characters' => [
        'fox' => [
            'name'    => 'コテツ',
            'species' => 'きつね',
            'blurb'   => '元気いっぱいな森の案内役。',
            'body'    => '#e08a4f',
            'belly'   => '#fbe9d7',
            'accent'  => '#6b4226',
        ],
        'cat' => [
            'name'    => 'ミルク',
            'species' => 'ねこ',
            'blurb'   => 'のんびり屋の甘えんぼ。',
            'body'    => '#eae3d3',
            'belly'   => '#fffdf7',
            'accent'  => '#c98f6b',
        ],
        'bear' => [
            'name'    => 'クロベエ',
            'species' => 'くま',
            'blurb'   => '力持ちでやさしい先生役。',
            'body'    => '#6b4a35',
            'belly'   => '#e9d9bd',
            'accent'  => '#4a3223',
        ],
        'rabbit' => [
            'name'    => 'フワリ',
            'species' => 'うさぎ',
            'blurb'   => '耳がじまんのおしゃれさん。',
            'body'    => '#f6d8e4',
            'belly'   => '#fff6f8',
            'accent'  => '#e79fb8',
        ],
        'raccoon' => [
            'name'    => 'タンタン',
            'species' => 'たぬき',
            'blurb'   => 'いたずら好きな夜ふかし。',
            'body'    => '#9a8b76',
            'belly'   => '#efe6d6',
            'accent'  => '#4a3f33',
        ],
        'panda' => [
            'name'    => 'パンジロウ',
            'species' => 'パンダ',
            'blurb'   => 'マイペースな読書家。',
            'body'    => '#f5f5f0',
            'belly'   => '#ffffff',
            'accent'  => '#2e2e2e',
        ],
        'owl' => [
            'name'    => 'ホロオ',
            'species' => 'ふくろう',
            'blurb'   => '星空の下で単語をかぞえる。',
            'body'    => '#8a6b4e',
            'belly'   => '#e8d9bc',
            'accent'  => '#5c4632',
        ],
        'frog' => [
            'name'    => 'ケロスケ',
            'species' => 'かえる',
            'blurb'   => '池のほとりで発音の達人。',
            'body'    => '#7cae5c',
            'belly'   => '#eaf0d0',
            'accent'  => '#4f7c3e',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 英語の森（3D アイランド）設定
    |--------------------------------------------------------------------------
    |
    | 島に配置する施設（スポット）の定義。x / z はワールド座標（島の中心が原点、
    | 島の芝生の半径はおよそ 30）、rot は Y 軸回転（ラジアン）。
    | kind は 3D モデルの種類で resources/js/english/forest/spots.js と対応する。
    |
    */

    'forest_spots' => [
        [
            'key'   => 'vocabulary',
            'kind'  => 'bigtree',
            'name'  => '単語の木',
            'desc'  => '英単語を覚える',
            'route' => 'english.vocabulary.index',
            'color' => '#5c7250',
            'x' => -21.6, 'z' => -9.5, 'rot' => 0.35,
        ],
        [
            'key'   => 'toeic',
            'kind'  => 'tent',
            'name'  => 'TOEIC キャンプ',
            'desc'  => 'Part 別の対策',
            'route' => 'english.toeic.index',
            'color' => '#c98f3c',
            'x' => 12.2, 'z' => -23.0, 'rot' => -0.5,
        ],
        [
            'key'   => 'ielts',
            'kind'  => 'lighthouse',
            'name'  => 'IELTS 灯台',
            'desc'  => 'スピーキング練習',
            'route' => 'english.ielts.index',
            'color' => '#b4483a',
            'x' => 28.4, 'z' => 8.1, 'rot' => 0.0,
        ],
        [
            'key'   => 'typing',
            'kind'  => 'cabin',
            'name'  => 'タイピング小屋',
            'desc'  => '指を動かして覚える',
            'route' => 'english.typing.index',
            'color' => '#a1815f',
            'x' => -12.2, 'z' => 21.6, 'rot' => -2.6,
        ],
        [
            'key'   => 'quiz',
            'kind'  => 'stage',
            'name'  => 'クイズ広場',
            'desc'  => '力だめしのクイズ',
            'route' => 'english.quiz.index',
            'color' => '#75688f',
            'x' => 20.2, 'z' => 18.9, 'rot' => -0.8,
        ],
        [
            'key'   => 'ranking',
            'kind'  => 'tower',
            'name'  => 'ランキング展望台',
            'desc'  => 'みんなの順位を見る',
            'route' => 'english.ranking',
            'color' => '#3d5a80',
            'x' => -27.0, 'z' => 12.2, 'rot' => 0.9,
        ],
        [
            'key'   => 'home',
            'kind'  => 'house',
            'name'  => 'じぶんの家',
            'desc'  => '中に入って模様替え',
            'route' => 'english.forest.home',
            'color' => '#c98f3c',
            'x' => 0.0, 'z' => 24.0, 'rot' => 0.0,
        ],
        [
            'key'   => 'progress',
            'kind'  => 'board',
            'name'  => '学習の掲示板',
            'desc'  => '進捗をふりかえる',
            'route' => 'english.progress',
            'color' => '#6f8154',
            'x' => 1.4, 'z' => -12.2, 'rot' => 0.15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | じぶんの家（インテリア）設定
    |--------------------------------------------------------------------------
    |
    | 部屋に置ける家具のカタログ。kind は 3D モデルの種類で
    | resources/js/english/home/furniture.js と対応する。
    | size は床に占める大きさ（メートル）で、配置時の当たり判定に使う。
    |
    */

    // 部屋の広さ（メートル）。壁の内側の寸法。
    'room' => [
        'width' => 12.0,
        'depth' => 10.0,
        'height' => 3.4,
    ],

    // 床・壁の選択肢（模様替え用）
    'room_floors' => [
        'oak'    => ['name' => 'オーク材',   'color' => '#c89a63'],
        'walnut' => ['name' => 'ウォルナット', 'color' => '#8a5c3b'],
        'tatami' => ['name' => 'たたみ',     'color' => '#c3c98a'],
        'tile'   => ['name' => 'タイル',     'color' => '#dcd8cf'],
        'carpet' => ['name' => 'カーペット',  'color' => '#9fb08a'],
    ],

    'room_walls' => [
        'cream'  => ['name' => 'クリーム',   'color' => '#f2e8d5'],
        'mint'   => ['name' => 'ミント',     'color' => '#d5e8dd'],
        'sky'    => ['name' => 'スカイ',     'color' => '#d8e6f2'],
        'rose'   => ['name' => 'ローズ',     'color' => '#f2ddde'],
        'wood'   => ['name' => '板張り',     'color' => '#d8bc93'],
    ],

    'furniture' => [
        'bed'       => ['name' => 'ベッド',       'kind' => 'bed',       'w' => 2.0, 'd' => 3.0, 'color' => '#7fa8c9'],
        'desk'      => ['name' => 'つくえ',       'kind' => 'desk',      'w' => 2.2, 'd' => 1.1, 'color' => '#a1815f'],
        'chair'     => ['name' => 'いす',         'kind' => 'chair',     'w' => 0.9, 'd' => 0.9, 'color' => '#c98f3c'],
        'bookshelf' => ['name' => '本だな',       'kind' => 'bookshelf', 'w' => 2.0, 'd' => 0.6, 'color' => '#8a5f3c'],
        'table'     => ['name' => 'テーブル',     'kind' => 'table',     'w' => 1.6, 'd' => 1.6, 'color' => '#b5834f'],
        'sofa'      => ['name' => 'ソファ',       'kind' => 'sofa',      'w' => 2.6, 'd' => 1.2, 'color' => '#6f8154'],
        'rug'       => ['name' => 'ラグ',         'kind' => 'rug',       'w' => 3.0, 'd' => 2.2, 'color' => '#d7a488'],
        'plant'     => ['name' => 'かんようしょくぶつ', 'kind' => 'plant', 'w' => 0.9, 'd' => 0.9, 'color' => '#5c7250'],
        'lamp'      => ['name' => 'フロアランプ', 'kind' => 'lamp',      'w' => 0.7, 'd' => 0.7, 'color' => '#f2d27a'],
        'clock'     => ['name' => 'かけ時計',     'kind' => 'clock',     'w' => 0.8, 'd' => 0.3, 'color' => '#75688f'],
        'globe'     => ['name' => 'ちきゅうぎ',   'kind' => 'globe',     'w' => 0.8, 'd' => 0.8, 'color' => '#4f8481'],
        'chest'     => ['name' => 'たんす',       'kind' => 'chest',     'w' => 1.6, 'd' => 0.8, 'color' => '#9c7d5b'],
    ],

    // 初めて家に入ったときの初期レイアウト（x/z は部屋の中心が原点、rot はラジアン）
    'default_room' => [
        'floor' => 'oak',
        'wall'  => 'cream',
        'items' => [
            ['key' => 'bed',       'x' => -3.8, 'z' => -2.6, 'rot' => 0],
            ['key' => 'chest',     'x' => -1.2, 'z' => -4.2, 'rot' => 0],
            ['key' => 'desk',      'x' =>  3.6, 'z' => -3.8, 'rot' => 0],
            ['key' => 'chair',     'x' =>  3.6, 'z' => -2.4, 'rot' => 3.14],
            ['key' => 'bookshelf', 'x' =>  5.0, 'z' =>  0.6, 'rot' => 1.5708],
            ['key' => 'rug',       'x' =>  0.6, 'z' =>  2.2, 'rot' => 0],
            ['key' => 'sofa',      'x' =>  0.6, 'z' =>  3.6, 'rot' => 3.14],
            ['key' => 'table',     'x' =>  0.6, 'z' =>  1.8, 'rot' => 0],
            ['key' => 'plant',     'x' => -4.6, 'z' =>  3.6, 'rot' => 0],
            ['key' => 'lamp',      'x' =>  4.6, 'z' =>  3.4, 'rot' => 0],
        ],
    ],

];
