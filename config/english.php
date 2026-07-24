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
    'toeic_parts' => ['5', '6', '7'],

    // TOEIC Part ごとのメタ情報（今後 DB 管理に移行可能）
    'toeic_part_meta' => [
        1 => ['name' => 'Part 1', 'desc' => 'Photographs (Listening)',             'available' => false],
        2 => ['name' => 'Part 2', 'desc' => 'Question-Response (Listening)',        'available' => false],
        3 => ['name' => 'Part 3', 'desc' => 'Conversations (Listening)',            'available' => false],
        4 => ['name' => 'Part 4', 'desc' => 'Talks (Listening)',                   'available' => false],
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
        'education'   => ['name' => 'Education',   'emoji' => '🎓', 'desc' => '学校・大学・教育制度・学習方法について話す'],
        'technology'  => ['name' => 'Technology',  'emoji' => '💻', 'desc' => 'SNS・AI・デジタル技術の影響について話す'],
        'environment' => ['name' => 'Environment', 'emoji' => '🌿', 'desc' => '環境問題・気候変動・持続可能性について話す'],
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

];
