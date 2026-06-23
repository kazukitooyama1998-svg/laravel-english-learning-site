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
 ・(実装検討中)IELTS Writingフォーマット習得（テンプレート暗記＋タイピング練習）
 ・IELTS Speaking の表現習得（common Topic20(環境、都市問題etc)（スクリプト入力練習）
 ・(実装検討中)著名人のスピーチから英語を学ぶ
 ・(実装検討中)ビジネス英語メールの定型表現習得
 ・英単語スペル強化（タイピングベース）
 ・英語タイピング速度向上（WPM測定）
 ・PCベースの英語学習習慣化
 ・他英語学習者とのコミュニケーション、学習履歴（スコア）等を見て刺激し合う。
解決したい課題：
 日本人学習者の「英語は理解できるが書けない・タイピングできない」問題を改善する
ターゲットユーザー：
 大学生以上の英語学習者
 ・IELTS / TOEIC受験者
 ・ビジネス英語学習者
 ・PCでの学習に慣れているユーザー

2. 機能
1：Typing Practice（メイン機能）
 ・IELTS Writing Task2(Education,Technology,Environment) 
 ・英単語スペル強化(Vocabularyをタイピング入力)(Education,Technology,Environment) 
 ・IELTS Speaking Part3(各テーマ質問５個程度)(Education,Technology,Environment)
 ・文章量が長いので実装検討中、もしくは工夫が必要(著名人のスピーチ表示)
 ・実装検討中(正解テキストと比較して結果表示)
2：学習記録管理
 ・ユーザーごとの練習履歴保存
 ・WPM（タイピング速度/正答率記録）記録
 ・実装検討中(ミス単語の保存)
3：学習コンテンツ表示機能
 ・Speakingの解説のコツ、汎用性の高い言い回し、各Topic毎に使用できると良い専門用語等
 ・Writing Task2の書き方、汎用性の高い言い回し、各Topic毎に使用できると良い専門用語等
 ・実装検討中(ビジネスメールテンプレ表示)
 ・文章量が長いので実装検討中、もしくは工夫が必要(著名人のスピーチ表示)
 ・単語リスト表示(英単語スペル強化関連、意味、品詞、意味付き例文)
4：復習機能(実装検討中)
 ・間違えた単語・文章の再表示
5：ユーザーフォロー機能
 ・follow,unfolllow機能
 ・FollowしているUser同士の直接のメッセージやり取り
6：ランキング機能
・クリア数（各セクション毎に費やす時間が異なるので今後経験値などで区別して良いかも）
・follower数
・各課題毎のタイム

,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,下記一旦無視,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
3. 画面一覧（UI設計）
トップページ（/）
 ・ログイン / 新規登録

User登録ページ（/login）
 ・フルネーム入力
 ・メールアドレス入力
 ・パスワード入力
 ・パスワード再入力

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

,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,上記一旦無視,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,

5. データ設計
users
 ・id
 ・name
 ・avatar
 ・introduction
 ・email
 ・password
 ・role_id
practices
 ・id
 ・category（IELTS / Business / Vocabulary）
 ・title
 ・level
 ・prompt
 ・text
records
 ・id
 ・user_id
 ・practice_id
 ・input_text
 ・wpm
 ・accuracy
 ・created_at

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

対応環境：
 ・PCメイン（スマホは今回実装なし（そもそもスマホUser向けのアプリではない））
セキュリティ：
 ・ログイン必須
 ・パスワードはハッシュ化

9. UI/UX方針
シンプルで学習に集中できるデザイン
余計な機能・広告なし
入力中心のインターフェース

########################進捗記録########################

29/5/2026
HTMLデザインをLaravelに移行しました。
DBへログイン用にUser tableと、Typingのテキスト保存用にPractices tableのみ作成。
Login、Register、ホーム画面、ダッシュボード、タイピング画面のみ作成しています。
🔸今後に向けて🔸
>>それぞれのUserのタイピング記録をDBに記録する（今後のため）。
>>ダッシュボードの下部に進捗度を表示するようにします。
>>Navbarの右側にハンバーガーを設定して、UserのProfileの情報等を編集できる用にします。
>>Typingのコースを増やします。
>>Typingの文章の英語を解説する項目を設け、資料を表示します。
>>日本語表示と英語表示を選べる用にします。
>>Typingのスコアにランク付け

22/6/2026
26/6/2026までに成果物を作成する必要があるため開発定義を修正しました（作業進捗に合わせて適宜修正します）。
usersテーブルにavatar,introduction,role_id(admin,user)追加しました。
practicesテーブルにSoft Deletes追加しました。
recordsテーブル追加（練習履歴 ＆ ランキング用）ユーザーとお題を紐づけ、タイピング結果を保存するテーブルです（Userから見て One to Many）。
followsテーブル追加(フォロー機能 ＆ ランキング用)ユーザー同士を結びつける Many to Many（多対多） の中間テーブル（Pivot）
create_chat_roomsとmessages_tablesの追加(相互フォローしているユーザー同士が1対1でメッセージをやり取りするためのテーブル)
UserSeeder(admin,user)の追加
Routeの作成・修正をしました
User、Record、モデル

23/6/2026
ダッシュボードにハンバーガーメニューを追加しました(Ranking,Friends,Profile)
タイピング教材を一旦WritingからSpeakingだけで実装するよう計画を変更します（教材を用意しやすいため）。
PracticeSeederにスピーキング教材をアップロード(Education,Technology,Environment)。writing教材削除。
practice/show.blade.php編集しました


