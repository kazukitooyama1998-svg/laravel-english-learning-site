<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

#################### English Learning Site 要件定義書（MVP）####################
// 先にデザインをHTMLにて作成しました。>>GitHub:https://github.com/kazukitooyama1998-svg/english-learning-site
// Laravelの移行にあたって先にUser登録画面、ログイン画面、ホーム画面(ログイン前/index)、ホーム画面(ログイン後)、タイピング画面を作成し後から機能や画面を追加できるように設計しました。

1. プロジェクト概要
サービス名：（未定）English Learning Site
目的：
 ・IELTS Writingフォーマット習得（テンプレート暗記＋タイピング練習）
 ・IELTS Speaking の表現習得（common Topic20(環境、都市問題etc)（スクリプト入力練習）
 ・ビジネス英語メールの定型表現習得
 ・英単語スペル強化（タイピングベース）
 ・英語タイピング速度向上（WPM測定）
 ・PCベースの英語学習習慣化
解決したい課題：
 日本人学習者の「英語は理解できるが書けない・タイピングできない」問題を改善する
ターゲットユーザー：
 大学生以上の英語学習者
 ・IELTS / TOEIC受験者
 ・ビジネス英語学習者
 ・PCでの学習に慣れているユーザー

2. MVP（最低限の機能）
※2週間開発ではここが最重要
機能1：Typing Practice（メイン機能）
 ・IELTS Writing / Business Email / Vocabularyをタイピング入力
 ・正解テキストと比較して結果表示
機能2：学習記録管理
 ・ユーザーごとの練習履歴保存
 ・WPM（タイピング速度）記録
 ・正答率記録
 ・ミス単語の保存
機能3：学習コンテンツ表示機能
 ・Writingテンプレート表示
 ・ビジネスメールテンプレ表示
 ・単語リスト表示
機能4：復習機能（簡易）
 ・間違えた単語・文章の再表示

3. 画面一覧（UI設計）
トップページ（/）
 ・サービス説明
 ・ログイン / 新規登録
 ・学習カテゴリ紹介
IELTS Writing
Business Email
Vocabulary

ログインページ（/login）
 ・メールアドレス入力
 ・パスワード入力
 ・ログインボタン

ユーザーダッシュボード（/dashboard）
 ・学習進捗（平均WPM）
 ・最近の学習履歴
 ・カテゴリ選択
IELTS Writing
Business Email
Vocabulary

Typing練習ページ（/practice/{id}）
 ・課題表示（文章・単語）
 ・タイピング入力欄
 ・スタート / ストップ機能
 ・結果表示
WPM（タイピング速度）
正答率
ミス単語

学習履歴ページ（/history）
 ・過去の練習記録一覧
 ・WPM推移
 ・正答率推移

コンテンツ一覧ページ（/contents）
 ・IELTS Writingテンプレ一覧
 ・Business Emailテンプレ一覧
 ・Vocabulary一覧

コンテンツ詳細ページ（/contents/{id}）
 ・学習コンテンツ全文表示
 ・「練習開始」ボタン
4. ユーザーフロー
ユーザーがサイトにアクセス
ログインまたは新規登録
ダッシュボードへ移動
学習カテゴリを選択
Typing練習を実施
結果（WPM・正答率）を確認
学習履歴として保存
復習コンテンツで再学習
5. データ設計（簡易）
users
 ・id
 ・name
 ・email
 ・password
contents
 ・id
 ・title
 ・type（IELTS / Business / Vocabulary）
 ・body（学習内容）
practices
 ・id
 ・user_id
 ・content_id
 ・input_text
 ・wpm
 ・accuracy
 ・created_at
mistakes
 ・id
 ・practice_id
 ・word

6. ルーティング設計（Laravel）
GET / → トップページ
GET /login → ログイン
POST /login → ログイン処理
GET /dashboard → ダッシュボード
GET /practice/{id} → 練習ページ
POST /practice/result → 結果保存
GET /history → 学習履歴
GET /contents → コンテンツ一覧
GET /contents/{id} → コンテンツ詳細

7. 入力・出力仕様
入力：
 ・ユーザーのタイピング文章
 ・単語入力
出力：
 ・WPM（タイピング速度）
 ・正答率
 ・ミス単語一覧
 ・学習履歴

8. 非機能要件
パフォーマンス：
 ・ページ表示は2秒以内
対応環境：
 ・PCメイン（スマホは最低限対応）
セキュリティ：
 ・ログイン必須
 ・パスワードはハッシュ化

9. UI/UX方針
シンプルで学習に集中できるデザイン
余計な機能・広告なし
入力中心のインターフェース

10. 将来拡張（今回は実装しない）
AI添削機能
スピーキング音声入力
ランキング機能
モバイルアプリ化
IELTSスコア自動判定


########################進捗記録########################

29/5/2026
HTMLデザインをLaravelに移行しました。
DBへログイン用にUser tableと、Typingのテキスト保存用にPractices tableのみ作成。
Login、Register、ホーム画面、ダッシュボード、タイピング画面のみ作成しています。
>>それぞれのUserのタイピング記録をDBに記録する（今後のため）。
>>ダッシュボードの下部に進捗度を表示するようにします。
>>Navbarの右側にハンバーガーを設定して、UserのProfileの情報等を編集できる用にします。
>>Typingのコースを増やします。
>>Typingの文章の英語を解説する項目を設け、資料を表示します。
>>日本語表示と英語表示を選べる用にします。
>>Typingのスコアにランク付け





