<?php

namespace Database\Seeders;

use App\Models\English\ToeicPassage;
use App\Models\English\ToeicQuestion;
use App\Models\English\ToeicQuestionOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToeicQuestionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // forceDelete で実データを削除し、FK cascade で選択肢も削除する
        ToeicQuestion::withTrashed()->whereIn('part', [1, 2, 3, 4, 5, 6, 7])->forceDelete();
        ToeicPassage::whereIn('part', [3, 4, 6, 7])->delete();

        $this->createPart1Questions();
        $this->createPart2Questions();
        $this->createPart3Questions();
        $this->createPart4Questions();
        $this->createPart5Questions();
        $this->createPart6Questions();
        $this->createPart7Questions();
    }

    /**
     * 1問（選択肢付き）を作成する共通ヘルパー
     */
    private function createQuestion(array $data, int $part, int $sortOrder, ?int $passageId = null): void
    {
        $question = ToeicQuestion::create([
            'part'          => $part,
            'passage_id'    => $passageId,
            'question_text' => $data['question_text'],
            'image_url'     => $data['image_url'] ?? null,
            'explanation'   => $data['explanation'],
            'difficulty'    => $data['difficulty'],
            'xp'            => $data['xp'],
            'sort_order'    => $sortOrder,
        ]);

        foreach ($data['options'] as $option) {
            ToeicQuestionOption::create([
                'question_id' => $question->id,
                'label'       => $option['label'],
                'option_text' => $option['text'],
                'is_correct'  => $option['is_correct'],
            ]);
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Part 1：写真描写問題（音声読み上げ機能の動作確認用に5問のみ）
    // ──────────────────────────────────────────────────────────────

    private function createPart1Questions(): void
    {
        $questions = [
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/office-meeting.jpg',
                'explanation'   => '写真では女性が同僚たちにプレゼンテーションを行っている様子が写っている。他の選択肢は写真に写っていない動作（握手・退勤・投影機オフ）を描写している。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'The man is shaking hands with a colleague.',    'is_correct' => false],
                    ['label' => 'B', 'text' => 'A woman is presenting to a group of coworkers.', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'The employees are leaving the building.',        'is_correct' => false],
                    ['label' => 'D', 'text' => 'The projector screen is turned off.',             'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/kitchen-chef.jpg',
                'explanation'   => '写真ではシェフがまな板の上で野菜を刻んでいる様子が写っている。皿洗いや配膳、冷蔵庫のドアなどは写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'The chef is chopping vegetables on a cutting board.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'The dishes are being washed in the sink.',             'is_correct' => false],
                    ['label' => 'C', 'text' => 'A waiter is serving food to customers.',               'is_correct' => false],
                    ['label' => 'D', 'text' => 'The refrigerator door is wide open.',                  'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/park-dog.jpg',
                'explanation'   => '写真では男性が公園で犬を散歩させている様子が写っている。ジョギングやブランコ、無人のベンチは写真の内容と一致しない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'A woman is jogging along the beach.',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'Children are playing on the swings.',   'is_correct' => false],
                    ['label' => 'C', 'text' => 'A man is walking his dog in the park.', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'The bench is unoccupied.',              'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/street-parking.jpg',
                'explanation'   => '写真では通りの片側に車が連なって駐車されている様子が写っている。信号機の修理や横断中の歩行者、空の道路は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'Cars are lined up along the side of the street.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'A traffic light is being repaired.',               'is_correct' => false],
                    ['label' => 'C', 'text' => 'Pedestrians are crossing at the intersection.',    'is_correct' => false],
                    ['label' => 'D', 'text' => 'The road is completely empty.',                    'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/library-reading.jpg',
                'explanation'   => '写真では人々が静かに座って読書をしている様子が写っている。本の返却や受付の整理、廊下での会話は写真の内容と一致しない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'Books are being returned to the shelves.',       'is_correct' => false],
                    ['label' => 'B', 'text' => 'A librarian is organizing the front desk.',      'is_correct' => false],
                    ['label' => 'C', 'text' => 'People are sitting quietly and reading books.',  'is_correct' => true],
                    ['label' => 'D', 'text' => 'Students are talking loudly in the hallway.',    'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/waiter-serving.jpg',
                'explanation'   => '写真ではウェイターが屋外のレストランで客に料理の皿を手渡している様子が写っている。会計や食器洗い、片付けの様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'The customers are paying at the cash register.',            'is_correct' => false],
                    ['label' => 'B', 'text' => 'A waiter is handing plates of food to the customers.',      'is_correct' => true],
                    ['label' => 'C', 'text' => 'The table is being cleared of dishes.',                     'is_correct' => false],
                    ['label' => 'D', 'text' => 'The waiter is washing dishes in the kitchen.',               'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/train-platform.jpg',
                'explanation'   => '写真では乗客が荷物を持って駅のホームを歩いている様子が写っている。切符売り場や電車への乗車中の様子、空のホームは写真に写っていない。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Passengers are walking along the train station platform.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'People are buying tickets at the counter.',                 'is_correct' => false],
                    ['label' => 'C', 'text' => 'A train is arriving at an empty platform.',                 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Passengers are boarding the train through the doors.',      'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/construction-workers.jpg',
                'explanation'   => '写真では建設作業員2人がハードハット（安全ヘルメット）を着用して話している様子が写っている。作業機械の操作や休憩中の様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'The workers are operating heavy machinery.',                'is_correct' => false],
                    ['label' => 'B', 'text' => 'The construction workers are wearing hard hats.',           'is_correct' => true],
                    ['label' => 'C', 'text' => 'The workers are taking a break under a tree.',               'is_correct' => false],
                    ['label' => 'D', 'text' => 'A worker is climbing down a ladder.',                        'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/laptop-typing.jpg',
                'explanation'   => '写真では机の上でノートパソコンを操作している様子が写っている。電話をかけている様子や書類への署名、印刷している様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'A person is typing on a laptop at the desk.',               'is_correct' => true],
                    ['label' => 'B', 'text' => 'A person is talking on the phone.',                         'is_correct' => false],
                    ['label' => 'C', 'text' => 'A document is being signed with a pen.',                     'is_correct' => false],
                    ['label' => 'D', 'text' => 'Papers are being printed from a printer.',                   'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/cyclist-road.jpg',
                'explanation'   => '写真では男性が屋外の草原を自転車で走っている様子が写っている。自転車を修理している様子や駐輪している様子、歩いて自転車を押している様子は写真に写っていない。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'The man is repairing his bicycle.',                        'is_correct' => false],
                    ['label' => 'B', 'text' => 'The bicycle is parked against a fence.',                    'is_correct' => false],
                    ['label' => 'C', 'text' => 'A cyclist is riding his bicycle through a field.',          'is_correct' => true],
                    ['label' => 'D', 'text' => 'The man is walking while pushing his bicycle.',             'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/grocery-shopping.jpg',
                'explanation'   => '写真では女性が野菜や果物が入った買い物カートを押している様子が写っている。レジでの会計や商品の陳列作業、カートを片付けている様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'The woman is paying for her groceries at the checkout.',    'is_correct' => false],
                    ['label' => 'B', 'text' => 'Shelves are being stocked with products.',                   'is_correct' => false],
                    ['label' => 'C', 'text' => 'A shopping cart is being put away.',                         'is_correct' => false],
                    ['label' => 'D', 'text' => 'The woman is pushing a cart full of groceries.',            'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/watering-garden.jpg',
                'explanation'   => '写真では男性がじょうろで庭の植物に水をやっている様子が写っている。草刈りや土を掘っている様子、フェンスを直している様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'The man is watering the plants in his garden.',             'is_correct' => true],
                    ['label' => 'B', 'text' => 'The man is mowing the grass.',                              'is_correct' => false],
                    ['label' => 'C', 'text' => 'The man is digging a hole in the soil.',                    'is_correct' => false],
                    ['label' => 'D', 'text' => 'The man is repairing the fence.',                            'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/airport-luggage.jpg',
                'explanation'   => '写真では女性がスーツケースを引きながら空港のターミナルを歩いている様子が写っている。チェックインカウンターでの手続きやセキュリティ検査、搭乗中の様子は写真に写っていない。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'The woman is checking in at the counter.',                  'is_correct' => false],
                    ['label' => 'B', 'text' => 'The woman is walking through the airport with her luggage.','is_correct' => true],
                    ['label' => 'C', 'text' => 'Passengers are going through security screening.',          'is_correct' => false],
                    ['label' => 'D', 'text' => 'The woman is boarding the airplane.',                        'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/mechanic-garage.jpg',
                'explanation'   => '写真では整備士が車のボンネットを開けてエンジンを点検している様子が写っている。タイヤ交換や洗車、車を運転している様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'The mechanic is changing a tire.',                          'is_correct' => false],
                    ['label' => 'B', 'text' => 'The car is being washed.',                                  'is_correct' => false],
                    ['label' => 'C', 'text' => 'The mechanic is repairing the engine of a car.',            'is_correct' => true],
                    ['label' => 'D', 'text' => 'The mechanic is driving the car out of the garage.',        'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/pool-swimming.jpg',
                'explanation'   => '写真では複数の人がプールで泳いでいる様子が写っている。プールサイドで日光浴をしている様子やプールの清掃、飛び込んでいる様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'People are sunbathing beside the pool.',                    'is_correct' => false],
                    ['label' => 'B', 'text' => 'A group of people are swimming in the pool.',               'is_correct' => true],
                    ['label' => 'C', 'text' => 'The pool is being cleaned.',                                'is_correct' => false],
                    ['label' => 'D', 'text' => 'A man is diving into the pool.',                             'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/painting-wall.jpg',
                'explanation'   => '写真ではローラーで壁を緑色に塗っている様子が写っている。壁紙を貼っている様子や梯子に登っている様子、床を掃除している様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'Wallpaper is being applied to the wall.',                    'is_correct' => false],
                    ['label' => 'B', 'text' => 'A person is climbing up a ladder.',                          'is_correct' => false],
                    ['label' => 'C', 'text' => 'The floor is being swept.',                                  'is_correct' => false],
                    ['label' => 'D', 'text' => 'A person is painting the wall with a roller.',              'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/farmers-harvesting.jpg',
                'explanation'   => '写真では農作業員たちが畑でトウモロコシを収穫している様子が写っている。種をまいている様子やトラクターを運転している様子、休憩している様子は写真に写っていない。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'The farmers are harvesting crops in the field.',            'is_correct' => true],
                    ['label' => 'B', 'text' => 'Seeds are being planted in rows.',                          'is_correct' => false],
                    ['label' => 'C', 'text' => 'A tractor is being driven across the field.',                'is_correct' => false],
                    ['label' => 'D', 'text' => 'The farmers are resting under a tree.',                     'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/carpenter-workshop.jpg',
                'explanation'   => '写真では大工が作業場で木材を機械にかけて加工している様子が写っている。木材を運んでいる様子やペンキを塗っている様子、掃除をしている様子は写真に写っていない。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'The carpenter is carrying wood outside.',                    'is_correct' => false],
                    ['label' => 'B', 'text' => 'The carpenter is working with wood in his workshop.',       'is_correct' => true],
                    ['label' => 'C', 'text' => 'The carpenter is painting a piece of furniture.',            'is_correct' => false],
                    ['label' => 'D', 'text' => 'The carpenter is sweeping the workshop floor.',             'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/bus-stop.jpg',
                'explanation'   => '写真では複数の人がバス停のベンチに座ってバスを待っている様子が写っている。バスに乗り込んでいる様子や横断歩道を渡っている様子、自転車に乗っている様子は写真に写っていない。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'People are boarding the bus.',                              'is_correct' => false],
                    ['label' => 'B', 'text' => 'People are crossing the crosswalk.',                        'is_correct' => false],
                    ['label' => 'C', 'text' => 'People are sitting and waiting at the bus stop.',           'is_correct' => true],
                    ['label' => 'D', 'text' => 'A man is riding a bicycle past the bus stop.',              'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Look at the picture and listen to the four statements. Select the statement that best describes what you see.',
                'image_url'     => '/images/english/toeic/part1/fishing-lake.jpg',
                'explanation'   => '写真では男性が桟橋に座って湖で釣りをしている様子が写っている。魚を捕まえて掲げている様子や船を漕いでいる様子、釣り道具を片付けている様子は写真に写っていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'The man is holding up a fish he caught.',                    'is_correct' => false],
                    ['label' => 'B', 'text' => 'The man is fishing from the pier by the lake.',             'is_correct' => true],
                    ['label' => 'C', 'text' => 'The man is rowing a boat across the lake.',                  'is_correct' => false],
                    ['label' => 'D', 'text' => 'The man is packing up his fishing equipment.',               'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $idx => $data) {
            $this->createQuestion($data, 1, $idx + 1);
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Part 2：質問応答問題（20問プールからランダム5問出題）
    //  質問文（question_text）・選択肢（option_text）とも画面には表示せず、
    //  音声読み上げのみで出題する（本番同様の形式）。
    // ──────────────────────────────────────────────────────────────

    private function createPart2Questions(): void
    {
        $questions = [
            [
                'question_text' => 'When does the marketing meeting start?',
                'explanation'   => '時刻を尋ねるWhen疑問文には具体的な時刻で答える(A)が正解。(B)は"market"の音の類似による誤答、(C)は場所を答えており質問の意図とずれる。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'It starts at two o\'clock.',        'is_correct' => true],
                    ['label' => 'B', 'text' => 'I marketed the product last year.', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'The meeting room is on the third floor.', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Could you send me the quarterly report by tomorrow?',
                'explanation'   => '依頼表現"Could you...?"には引き受ける返答が自然。(A)が快諾する適切な応答。(B)は"report"に反応しただけで質問に答えていない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'Sure, I\'ll email it this afternoon.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'The report was very informative.',     'is_correct' => false],
                    ['label' => 'C', 'text' => 'I sent the invitation last week.',     'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Who is going to lead the new project?',
                'explanation'   => '人物を尋ねるWho疑問文には人名や役職で答える。(B)が適切。(A)は日時、(C)は"led/lead"の音の類似による誤答。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'It\'s scheduled for next Monday.', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Sarah will be in charge of it.',   'is_correct' => true],
                    ['label' => 'C', 'text' => 'I led a great discussion.',        'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Isn\'t the new office building supposed to open next month?',
                'explanation'   => '否定疑問文でも基本はYes/Noで答える(内容が真ならYes)。(A)は開業予定を確認しつつ補足情報を加えた自然な応答。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Yes, but it might be delayed a bit.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'The building has ten floors.',        'is_correct' => false],
                    ['label' => 'C', 'text' => 'I opened the window earlier.',        'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Where should I put these boxes of supplies?',
                'explanation'   => '場所を尋ねるWhereには具体的な場所で答える(A)が正解。(B)は"supplies"の音に反応しただけの誤答。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'You can leave them by the storage room.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'I supplied the data yesterday.',          'is_correct' => false],
                    ['label' => 'C', 'text' => 'They arrived this morning.',              'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'How long will the training session take?',
                'explanation'   => 'How longは所要時間を問う。時間の長さで答える(A)が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'It usually lasts about two hours.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'I trained for the marathon.',       'is_correct' => false],
                    ['label' => 'C', 'text' => 'The trainer arrived early.',        'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Would you like to join us for lunch, or stay in the office?',
                'explanation'   => '選択疑問文(or)には二択のどちらかを選んで答える。(A)は「残る」ことを選んだ自然な応答。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'I\'d rather stay and finish this report.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'Lunch was delicious.',                     'is_correct' => false],
                    ['label' => 'C', 'text' => 'The office is on the fifth floor.',        'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The printer on the second floor isn\'t working again.',
                'explanation'   => '問題提起の平叙文には対処法や反応で答える。(A)が自然な返答。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'I\'ll call maintenance right away.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'I printed twenty copies.',           'is_correct' => false],
                    ['label' => 'C', 'text' => 'The second floor has a nice view.',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Have you finished reviewing the contract yet?',
                'explanation'   => '現在完了のYes/No疑問文。まだ終わっていないことを伝える(A)が自然。(C)は"review"の意味を勘違いした誤答。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'Not yet, I should be done by noon.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'The contract is on the desk.',       'is_correct' => false],
                    ['label' => 'C', 'text' => 'I reviewed the movie last night.',   'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Why was the shipment delayed?',
                'explanation'   => 'Whyには理由で答える。"because of"で理由を示している(A)が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Because of a problem with customs clearance.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'It was shipped from Japan.',                   'is_correct' => false],
                    ['label' => 'C', 'text' => 'The delay lasted five minutes.',               'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Do you know where the nearest ATM is?',
                'explanation'   => '埋め込み疑問文(Do you know where...)。場所を答える(A)が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'There\'s one right around the corner.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'I know him very well.',                 'is_correct' => false],
                    ['label' => 'C', 'text' => 'The bank closes at five.',              'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'This coffee machine hasn\'t been cleaned in weeks.',
                'explanation'   => '問題を指摘する平叙文には対応を示す応答が自然。(A)が適切。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'I\'ll take care of it this afternoon.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'I like my coffee black.',               'is_correct' => false],
                    ['label' => 'C', 'text' => 'The machine was expensive.',            'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What time does the flight to Chicago depart?',
                'explanation'   => '出発時刻を尋ねるWhat timeには時刻で答える(A)が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'It leaves at nine thirty tomorrow morning.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'Chicago is a big city.',                     'is_correct' => false],
                    ['label' => 'C', 'text' => 'I departed from the meeting early.',         'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'You\'re coming to the workshop on Friday, aren\'t you?',
                'explanation'   => '付加疑問文。参加予定を確認しYesと答えている(A)が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Yes, I\'ve already registered.',   'is_correct' => true],
                    ['label' => 'B', 'text' => 'The workshop was very useful.',    'is_correct' => false],
                    ['label' => 'C', 'text' => 'Friday is my favorite day.',       'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'How much does it cost to renew the software license?',
                'explanation'   => 'How muchは金額を尋ねる。金額で答える(A)が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'It\'s about three hundred dollars a year.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'The software is easy to use.',              'is_correct' => false],
                    ['label' => 'C', 'text' => 'I renewed my passport last month.',         'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Could you turn down the air conditioner? It\'s freezing in here.',
                'explanation'   => '依頼表現には応じる返答が自然。(A)が適切。(C)は"turn"の音に反応した誤答。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'Sure, I\'ll adjust it now.',           'is_correct' => true],
                    ['label' => 'B', 'text' => 'The weather is quite cold outside.',   'is_correct' => false],
                    ['label' => 'C', 'text' => 'I turned in my assignment.',           'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Isn\'t there a staff meeting scheduled for this afternoon?',
                'explanation'   => '否定疑問文にYesで答え、具体的な時間を補足している(A)が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Yes, it starts right after lunch.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'The staff did a great job.',        'is_correct' => false],
                    ['label' => 'C', 'text' => 'I scheduled a dentist appointment.','is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Who left these documents on my desk?',
                'explanation'   => 'Whoには人物で答える(A)が正解。(B)は物、(C)は"documents/documented"の音の類似による誤答。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'I think it was someone from accounting.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'The desk needs to be replaced.',           'is_correct' => false],
                    ['label' => 'C', 'text' => 'I documented everything carefully.',       'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The client wants to reschedule tomorrow\'s meeting.',
                'explanation'   => '会議の再調整を伝える平叙文には対応方針を示す応答が自然。(C)は紛らわしいが質問の意図(再調整への対応)に直接応えていない。',
                'difficulty'    => 'hard',
                'xp'            => 50,
                'options'       => [
                    ['label' => 'A', 'text' => 'Let\'s find another time that works for everyone.', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'The client seemed satisfied.',                      'is_correct' => false],
                    ['label' => 'C', 'text' => 'I scheduled it for next Tuesday originally.',       'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Would you mind reviewing my presentation before the meeting?',
                'explanation'   => '"Would you mind ...?"には快諾する場合"Not at all"などで答える。(A)が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Not at all, just send it over.',   'is_correct' => true],
                    ['label' => 'B', 'text' => 'The presentation went really well.','is_correct' => false],
                    ['label' => 'C', 'text' => 'I don\'t mind the weather today.', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $idx => $data) {
            $this->createQuestion($data, 2, $idx + 1);
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Part 3：会話問題（8会話 × 3問 = 24問プールからランダム2会話出題）
    //  会話内容（passage.documents）は画面には表示せず、音声読み上げのみで出題する。
    //  質問文・選択肢（4択）は本番同様、画面に印刷（表示）する。
    // ──────────────────────────────────────────────────────────────

    private function createPart3Questions(): void
    {
        $conversationSets = [
            // ── 会話1：会議室の予約変更 ──
            [
                'title'     => '会議室の予約変更',
                'documents' => [
                    ['heading' => 'Man',   'body' => 'Hi Rachel, I need to move our budget meeting from two P.M. to four P.M. today. Is the large conference room still available then?'],
                    ['heading' => 'Woman', 'body' => "Let me check... Yes, it's free after three thirty. I'll update the calendar invite right away."],
                    ['heading' => 'Man',   'body' => 'Great, thanks. Could you also let the finance team know about the change?'],
                    ['heading' => 'Woman', 'body' => "Sure, I'll send them an e-mail as soon as I update the room booking."],
                ],
                'questions' => [
                    [
                        'question_text' => 'What does the man want to change?',
                        'explanation'   => '男性は"I need to move our budget meeting from two P.M. to four P.M."と述べており、会議の時間変更を求めている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'The time of a meeting',        'is_correct' => true],
                            ['label' => 'B', 'text' => 'The location of his office',   'is_correct' => false],
                            ['label' => 'C', 'text' => 'The topic of a presentation',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'The date of a conference',     'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What will the woman do next?',
                        'explanation'   => '女性は"I\'ll update the calendar invite right away."と述べており、予約(カレンダー)を更新する。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Cancel the meeting',               'is_correct' => false],
                            ['label' => 'B', 'text' => 'Book a different room',            'is_correct' => false],
                            ['label' => 'C', 'text' => 'Update the room reservation',      'is_correct' => true],
                            ['label' => 'D', 'text' => 'Attend the finance meeting',       'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the man ask the woman to do?',
                        'explanation'   => '男性は"Could you also let the finance team know about the change?"と依頼している。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Print the budget report',        'is_correct' => false],
                            ['label' => 'B', 'text' => 'Notify the finance team',        'is_correct' => true],
                            ['label' => 'C', 'text' => 'Reserve a larger room',          'is_correct' => false],
                            ['label' => 'D', 'text' => 'Reschedule another meeting',     'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 会話2：レストランの予約 ──
            [
                'title'     => 'レストランの予約',
                'documents' => [
                    ['heading' => 'Woman', 'body' => "Hi, I'd like to make a reservation for six people this Friday evening."],
                    ['heading' => 'Man',   'body' => 'Of course. What time would you like to come in?'],
                    ['heading' => 'Woman', 'body' => "Around seven thirty, if possible. Also, one of my guests has a nut allergy — can the kitchen accommodate that?"],
                    ['heading' => 'Man',   'body' => "Absolutely, I'll note that on the reservation and let our chef know in advance."],
                ],
                'questions' => [
                    [
                        'question_text' => 'Why is the woman calling?',
                        'explanation'   => '女性は"I\'d like to make a reservation for six people"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'To make a reservation',    'is_correct' => true],
                            ['label' => 'B', 'text' => 'To cancel an order',       'is_correct' => false],
                            ['label' => 'C', 'text' => 'To apply for a job',       'is_correct' => false],
                            ['label' => 'D', 'text' => 'To complain about service','is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the woman mention about one of her guests?',
                        'explanation'   => '女性は"one of my guests has a nut allergy"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'The guest will arrive late',        'is_correct' => false],
                            ['label' => 'B', 'text' => 'The guest has a food allergy',      'is_correct' => true],
                            ['label' => 'C', 'text' => 'The guest cannot attend',           'is_correct' => false],
                            ['label' => 'D', 'text' => 'The guest requested a window seat', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What will the man most likely do next?',
                        'explanation'   => '男性は"let our chef know in advance"と述べており、厨房スタッフに知らせる。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'Cancel the reservation',       'is_correct' => false],
                            ['label' => 'B', 'text' => 'Inform the kitchen staff',     'is_correct' => true],
                            ['label' => 'C', 'text' => 'Offer a discount',             'is_correct' => false],
                            ['label' => 'D', 'text' => 'Ask for a credit card number', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 会話3：ITサポート ──
            [
                'title'     => 'ITサポート',
                'documents' => [
                    ['heading' => 'Man',   'body' => "My laptop keeps freezing whenever I open the sales database. It's been happening since yesterday."],
                    ['heading' => 'Woman', 'body' => 'That\'s strange. Have you tried restarting the computer?'],
                    ['heading' => 'Man',   'body' => 'Yeah, I restarted it twice already, but the problem keeps coming back.'],
                    ['heading' => 'Woman', 'body' => 'Okay, let me remote into your machine and take a look. It might be a software update issue.'],
                ],
                'questions' => [
                    [
                        'question_text' => 'What problem does the man have?',
                        'explanation'   => '男性は"My laptop keeps freezing"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => "His password isn't working", 'is_correct' => false],
                            ['label' => 'B', 'text' => 'His computer keeps freezing', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'He lost an important file',   'is_correct' => false],
                            ['label' => 'D', 'text' => 'His printer is broken',       'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What has the man already tried?',
                        'explanation'   => '男性は"I restarted it twice already"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Restarting his computer',    'is_correct' => true],
                            ['label' => 'B', 'text' => 'Reinstalling the database',  'is_correct' => false],
                            ['label' => 'C', 'text' => 'Contacting the manufacturer','is_correct' => false],
                            ['label' => 'D', 'text' => 'Buying a new laptop',        'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What will the woman do to help?',
                        'explanation'   => '女性は"let me remote into your machine"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Replace the laptop',                'is_correct' => false],
                            ['label' => 'B', 'text' => 'Access his computer remotely',      'is_correct' => true],
                            ['label' => 'C', 'text' => 'Schedule a repair appointment',     'is_correct' => false],
                            ['label' => 'D', 'text' => 'Send him a new password',           'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 会話4：出張の手配 ──
            [
                'title'     => '出張の手配',
                'documents' => [
                    ['heading' => 'Woman', 'body' => 'I need to book a flight to the Chicago branch for the sales conference next week.'],
                    ['heading' => 'Man',   'body' => 'Sure, do you have a preferred departure time?'],
                    ['heading' => 'Woman', 'body' => 'Ideally something in the morning, so I can attend the afternoon session on the same day.'],
                    ['heading' => 'Man',   'body' => "I'll check flights before ten A.M. and send you a few options by the end of the day."],
                ],
                'questions' => [
                    [
                        'question_text' => 'What is the woman planning to do?',
                        'explanation'   => '女性は"book a flight...for the sales conference"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'Attend a conference',           'is_correct' => true],
                            ['label' => 'B', 'text' => 'Interview a job candidate',     'is_correct' => false],
                            ['label' => 'C', 'text' => "Visit a client's office",       'is_correct' => false],
                            ['label' => 'D', 'text' => 'Return from vacation',          'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the woman prefer?',
                        'explanation'   => '女性は"something in the morning"を希望している。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'A direct flight',       'is_correct' => false],
                            ['label' => 'B', 'text' => 'A morning departure',   'is_correct' => true],
                            ['label' => 'C', 'text' => 'A window seat',         'is_correct' => false],
                            ['label' => 'D', 'text' => 'A refundable ticket',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What will the man do by the end of the day?',
                        'explanation'   => '男性は"send you a few options by the end of the day"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Confirm a hotel booking',   'is_correct' => false],
                            ['label' => 'B', 'text' => 'Provide flight options',    'is_correct' => true],
                            ['label' => 'C', 'text' => 'Submit an expense report',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'Cancel the conference',     'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 会話5：アパートの内見 ──
            [
                'title'     => 'アパートの内見',
                'documents' => [
                    ['heading' => 'Man',   'body' => "Hi, I'm calling about the two-bedroom apartment listed on your website. Is it still available?"],
                    ['heading' => 'Woman', 'body' => 'Yes, it is. Would you like to schedule a viewing?'],
                    ['heading' => 'Man',   'body' => 'That would be great. Is this Saturday morning possible?'],
                    ['heading' => 'Woman', 'body' => "Let me check the schedule... Yes, ten o'clock works. I'll send you the address by text message."],
                ],
                'questions' => [
                    [
                        'question_text' => 'Why is the man calling?',
                        'explanation'   => '男性は"calling about the two-bedroom apartment"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'To renew a lease',                 'is_correct' => false],
                            ['label' => 'B', 'text' => 'To ask about an apartment',        'is_correct' => true],
                            ['label' => 'C', 'text' => 'To report a maintenance issue',    'is_correct' => false],
                            ['label' => 'D', 'text' => 'To pay a deposit',                 'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the man request?',
                        'explanation'   => '男性は"Is this Saturday morning possible?"と内見の予約を依頼している。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'A lower rent',            'is_correct' => false],
                            ['label' => 'B', 'text' => 'A viewing appointment',   'is_correct' => true],
                            ['label' => 'C', 'text' => 'A parking space',         'is_correct' => false],
                            ['label' => 'D', 'text' => 'A refund',                'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What will the woman send to the man?',
                        'explanation'   => '女性は"I\'ll send you the address by text message"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A rental contract',              'is_correct' => false],
                            ['label' => 'B', 'text' => "The apartment's address",        'is_correct' => true],
                            ['label' => 'C', 'text' => 'A list of nearby restaurants',    'is_correct' => false],
                            ['label' => 'D', 'text' => 'A payment receipt',               'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 会話6：製品の返品 ──
            [
                'title'     => '製品の返品',
                'documents' => [
                    ['heading' => 'Woman', 'body' => 'Hi, I bought this blender last week, but it stopped working after just two uses.'],
                    ['heading' => 'Man',   'body' => 'I\'m sorry to hear that. Do you have your receipt with you?'],
                    ['heading' => 'Woman', 'body' => "Yes, right here. I'd like a refund if that's possible."],
                    ['heading' => 'Man',   'body' => "Since it's within our thirty-day return policy, I can process a full refund for you right now."],
                ],
                'questions' => [
                    [
                        'question_text' => "What is the woman's problem?",
                        'explanation'   => '女性は"it stopped working after just two uses"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'She lost her receipt',        'is_correct' => false],
                            ['label' => 'B', 'text' => 'A product stopped working',   'is_correct' => true],
                            ['label' => 'C', 'text' => 'She was overcharged',         'is_correct' => false],
                            ['label' => 'D', 'text' => 'An item was out of stock',    'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the woman want?',
                        'explanation'   => '女性は"I\'d like a refund if that\'s possible"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A replacement product', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'A refund',              'is_correct' => true],
                            ['label' => 'C', 'text' => 'A store credit',        'is_correct' => false],
                            ['label' => 'D', 'text' => 'A repair service',      'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the man say about the return policy?',
                        'explanation'   => '男性は"it\'s within our thirty-day return policy"と述べ、返金対象であることを伝えている。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'It has already expired',                  'is_correct' => false],
                            ['label' => 'B', 'text' => 'The purchase qualifies for a refund',     'is_correct' => true],
                            ['label' => 'C', 'text' => 'It only applies to online orders',        'is_correct' => false],
                            ['label' => 'D', 'text' => "A manager's approval is required",        'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 会話7：健康診断の予約 ──
            [
                'title'     => '健康診断の予約',
                'documents' => [
                    ['heading' => 'Man',   'body' => "I'd like to schedule my annual health checkup sometime next week."],
                    ['heading' => 'Woman', 'body' => 'Sure. We have openings on Tuesday morning or Thursday afternoon.'],
                    ['heading' => 'Man',   'body' => 'Thursday afternoon works better for me. Also, should I fast before the appointment?'],
                    ['heading' => 'Woman', 'body' => "Yes, please don't eat or drink anything except water for twelve hours beforehand."],
                ],
                'questions' => [
                    [
                        'question_text' => 'What is the man trying to do?',
                        'explanation'   => '男性は"schedule my annual health checkup"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'Cancel an appointment',   'is_correct' => false],
                            ['label' => 'B', 'text' => 'Schedule a checkup',      'is_correct' => true],
                            ['label' => 'C', 'text' => 'Request a prescription',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'Change his doctor',       'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => "When will the man's appointment be?",
                        'explanation'   => '男性は"Thursday afternoon works better for me"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Tuesday morning',    'is_correct' => false],
                            ['label' => 'B', 'text' => 'Thursday afternoon', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Friday evening',     'is_correct' => false],
                            ['label' => 'D', 'text' => 'Monday morning',     'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the woman advise the man to do?',
                        'explanation'   => '女性は"don\'t eat or drink anything...for twelve hours beforehand"と述べ、断食を指示している。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Bring a form of identification', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Fast before the appointment',    'is_correct' => true],
                            ['label' => 'C', 'text' => 'Arrive an hour early',           'is_correct' => false],
                            ['label' => 'D', 'text' => 'Bring a family member',          'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 会話8：倉庫在庫の確認 ──
            [
                'title'     => '倉庫在庫の確認',
                'documents' => [
                    ['heading' => 'Woman', 'body' => 'Do we still have any of the wireless keyboards in stock? A customer just placed a large order.'],
                    ['heading' => 'Man',   'body' => 'Let me check the inventory system... It looks like we only have twelve units left in the warehouse.'],
                    ['heading' => 'Woman', 'body' => "That won't be enough. The customer wants fifty units by Friday."],
                    ['heading' => 'Man',   'body' => "I'll contact the supplier this afternoon and see how quickly we can get more shipped in."],
                ],
                'questions' => [
                    [
                        'question_text' => 'What are the speakers discussing?',
                        'explanation'   => '在庫(wireless keyboards)の数について話しており、在庫状況が話題。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'Product inventory',      'is_correct' => true],
                            ['label' => 'B', 'text' => 'A hiring decision',      'is_correct' => false],
                            ['label' => 'C', 'text' => 'A marketing campaign',   'is_correct' => false],
                            ['label' => 'D', 'text' => 'An office relocation',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What problem does the woman mention?',
                        'explanation'   => '女性は"That won\'t be enough"と述べ、在庫不足を指摘している。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'The price has increased',    'is_correct' => false],
                            ['label' => 'B', 'text' => "There isn't enough stock",   'is_correct' => true],
                            ['label' => 'C', 'text' => 'The order was canceled',     'is_correct' => false],
                            ['label' => 'D', 'text' => 'The warehouse is closed',    'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What will the man do this afternoon?',
                        'explanation'   => '男性は"I\'ll contact the supplier this afternoon"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Visit the warehouse',    'is_correct' => false],
                            ['label' => 'B', 'text' => 'Contact the supplier',   'is_correct' => true],
                            ['label' => 'C', 'text' => 'Update the price list',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'Train new staff',        'is_correct' => false],
                        ],
                    ],
                ],
            ],
        ];

        $sortOrder = 1;
        foreach ($conversationSets as $setIdx => $set) {
            $passage = ToeicPassage::create([
                'part'         => 3,
                'passage_type' => 'convo',
                'title'        => $set['title'],
                'documents'    => $set['documents'],
                'sort_order'   => $setIdx + 1,
            ]);

            foreach ($set['questions'] as $data) {
                $this->createQuestion($data, 3, $sortOrder, $passage->id);
                $sortOrder++;
            }
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Part 4：トーク問題（8トーク × 3問 = 24問プールからランダム2トーク出題）
    //  トーク内容（passage.documents）は画面には表示せず、音声読み上げのみで出題する。
    //  質問文・選択肢（4択）は本番同様、画面に印刷（表示）する。
    // ──────────────────────────────────────────────────────────────

    private function createPart4Questions(): void
    {
        $talkSets = [
            // ── トーク1：留守番電話メッセージ（配送遅延） ──
            [
                'title'     => '留守番電話メッセージ（配送遅延）',
                'documents' => [
                    ['heading' => 'Speaker', 'body' => "Hi, this is Daniel from Greenfield Logistics. I'm calling about your delivery scheduled for this afternoon."],
                    ['heading' => 'Speaker', 'body' => "Unfortunately, due to a mechanical issue with one of our trucks, the delivery will be delayed until tomorrow morning. I apologize for any inconvenience."],
                    ['heading' => 'Speaker', 'body' => "If this new time doesn't work for you, please call our office before five P.M. today to arrange a different schedule. Thank you for your patience."],
                ],
                'questions' => [
                    [
                        'question_text' => 'Who most likely is the speaker?',
                        'explanation'   => '話者は"this is Daniel from Greenfield Logistics"と名乗っており、配送業者の担当者である。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A delivery company employee', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'A restaurant manager',        'is_correct' => false],
                            ['label' => 'C', 'text' => 'A repair technician',         'is_correct' => false],
                            ['label' => 'D', 'text' => 'A store cashier',             'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'Why has the delivery been delayed?',
                        'explanation'   => '"due to a mechanical issue with one of our trucks"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'The weather is bad',              'is_correct' => false],
                            ['label' => 'B', 'text' => 'The address was incorrect',       'is_correct' => false],
                            ['label' => 'C', 'text' => 'A truck has a mechanical problem','is_correct' => true],
                            ['label' => 'D', 'text' => 'The warehouse is closed',         'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What should the listener do if the new time is inconvenient?',
                        'explanation'   => '"please call our office before five P.M. today"と案内している。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Visit the office in person', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'Call before five P.M.',      'is_correct' => true],
                            ['label' => 'C', 'text' => 'Send an e-mail',             'is_correct' => false],
                            ['label' => 'D', 'text' => 'Wait until next week',       'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── トーク2：店内アナウンス（閉店・セール） ──
            [
                'title'     => '店内アナウンス（閉店・セール）',
                'documents' => [
                    ['heading' => 'Speaker', 'body' => "Attention, shoppers. We'd like to remind you that Greenway Department Store will be closing in fifteen minutes. Please bring your final purchases to the checkout counters located on the first and second floors."],
                    ['heading' => 'Speaker', 'body' => "Also, don't forget that our annual summer clearance sale continues through this weekend, with discounts of up to fifty percent on selected clothing items."],
                    ['heading' => 'Speaker', 'body' => 'Thank you for shopping with us today, and we look forward to seeing you again soon.'],
                ],
                'questions' => [
                    [
                        'question_text' => 'Where is this announcement being made?',
                        'explanation'   => '"Greenway Department Store"と述べており、店内でのアナウンスである。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'At an airport',        'is_correct' => false],
                            ['label' => 'B', 'text' => 'At a department store','is_correct' => true],
                            ['label' => 'C', 'text' => 'At a restaurant',      'is_correct' => false],
                            ['label' => 'D', 'text' => 'At a train station',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What are listeners reminded about?',
                        'explanation'   => '"will be closing in fifteen minutes"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'The store is closing soon', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'A parking lot is full',     'is_correct' => false],
                            ['label' => 'C', 'text' => 'A new store is opening',    'is_correct' => false],
                            ['label' => 'D', 'text' => 'Prices have increased',     'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the speaker mention about the summer sale?',
                        'explanation'   => '"discounts of up to fifty percent on selected clothing items"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'It has already ended',                    'is_correct' => false],
                            ['label' => 'B', 'text' => 'It offers discounts up to fifty percent',  'is_correct' => true],
                            ['label' => 'C', 'text' => 'It applies only to shoes',                 'is_correct' => false],
                            ['label' => 'D', 'text' => 'It starts next month',                     'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── トーク3：天気予報 ──
            [
                'title'     => '天気予報',
                'documents' => [
                    ['heading' => 'Speaker', 'body' => 'Good morning, and welcome to your local weather update. Today will be mostly sunny with a high of seventy-eight degrees, perfect for outdoor activities.'],
                    ['heading' => 'Speaker', 'body' => "However, we're expecting a change tomorrow, as a cold front moves in from the north, bringing rain and much cooler temperatures throughout the afternoon."],
                    ['heading' => 'Speaker', 'body' => 'If you have outdoor plans tomorrow, you may want to reschedule them for later in the week when conditions are expected to improve.'],
                ],
                'questions' => [
                    [
                        'question_text' => 'What is the weather like today?',
                        'explanation'   => '"mostly sunny with a high of seventy-eight degrees"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'Rainy and cold',   'is_correct' => false],
                            ['label' => 'B', 'text' => 'Mostly sunny',     'is_correct' => true],
                            ['label' => 'C', 'text' => 'Snowy',            'is_correct' => false],
                            ['label' => 'D', 'text' => 'Extremely windy',  'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What will happen tomorrow?',
                        'explanation'   => '"a cold front moves in...bringing rain and much cooler temperatures"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'The temperature will rise further', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'A cold front will bring rain',      'is_correct' => true],
                            ['label' => 'C', 'text' => 'The weather will stay the same',    'is_correct' => false],
                            ['label' => 'D', 'text' => 'There will be a heat wave',         'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the speaker suggest listeners do?',
                        'explanation'   => '"you may want to reschedule them for later in the week"と提案している。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Cancel all outdoor plans permanently',            'is_correct' => false],
                            ['label' => 'B', 'text' => 'Reschedule outdoor plans for later in the week',  'is_correct' => true],
                            ['label' => 'C', 'text' => 'Bring an umbrella today',                          'is_correct' => false],
                            ['label' => 'D', 'text' => 'Avoid going outside today',                        'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── トーク4：ツアーガイドの案内 ──
            [
                'title'     => 'ツアーガイドの案内',
                'documents' => [
                    ['heading' => 'Speaker', 'body' => "Welcome aboard, everyone. My name is Sofia, and I'll be your guide for today's city tour."],
                    ['heading' => 'Speaker', 'body' => "Our first stop will be the historic art museum, where we'll spend about an hour exploring the main exhibits. After that, we'll head to the riverside park for a short break before lunch."],
                    ['heading' => 'Speaker', 'body' => "Please remember to keep your ticket with you at all times, as you'll need it to enter each site. If you have any questions during the tour, feel free to ask me at any time."],
                ],
                'questions' => [
                    [
                        'question_text' => "What is the speaker's role?",
                        'explanation'   => '"I\'ll be your guide for today\'s city tour"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A museum curator',    'is_correct' => false],
                            ['label' => 'B', 'text' => 'A tour guide',        'is_correct' => true],
                            ['label' => 'C', 'text' => 'A restaurant owner',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'A bus driver',        'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'Where will the group go first?',
                        'explanation'   => '"Our first stop will be the historic art museum"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'A riverside park',     'is_correct' => false],
                            ['label' => 'B', 'text' => 'A restaurant',         'is_correct' => false],
                            ['label' => 'C', 'text' => 'An art museum',        'is_correct' => true],
                            ['label' => 'D', 'text' => 'A shopping center',    'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What are listeners asked to keep with them?',
                        'explanation'   => '"keep your ticket with you at all times"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A water bottle',  'is_correct' => false],
                            ['label' => 'B', 'text' => 'A map',           'is_correct' => false],
                            ['label' => 'C', 'text' => 'Their ticket',    'is_correct' => true],
                            ['label' => 'D', 'text' => 'A camera',        'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── トーク5：会議冒頭の紹介 ──
            [
                'title'     => '会議冒頭の紹介',
                'documents' => [
                    ['heading' => 'Speaker', 'body' => "Good afternoon, everyone, and thank you for joining today's quarterly meeting."],
                    ['heading' => 'Speaker', 'body' => "Before we begin, I'd like to introduce our new marketing director, Mr. Lee, who joined the company last month. He'll be presenting the updated marketing strategy later in the meeting."],
                    ['heading' => 'Speaker', 'body' => "First, though, let's review last quarter's sales figures, which I'll display on the screen in just a moment."],
                ],
                'questions' => [
                    [
                        'question_text' => 'What is the purpose of this talk?',
                        'explanation'   => '"thank you for joining today\'s quarterly meeting"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'To open a quarterly meeting', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'To announce a product recall','is_correct' => false],
                            ['label' => 'C', 'text' => 'To welcome new customers',    'is_correct' => false],
                            ['label' => 'D', 'text' => 'To close the fiscal year',    'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'Who is Mr. Lee?',
                        'explanation'   => '"our new marketing director, Mr. Lee"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => "The company's accountant",  'is_correct' => false],
                            ['label' => 'B', 'text' => 'The new marketing director','is_correct' => true],
                            ['label' => 'C', 'text' => 'A guest speaker',            'is_correct' => false],
                            ['label' => 'D', 'text' => 'A former employee',          'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What will happen first in the meeting?',
                        'explanation'   => "直後に\"let's review last quarter's sales figures\"と述べている。",
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => "Mr. Lee's presentation",                     'is_correct' => false],
                            ['label' => 'B', 'text' => "A review of last quarter's sales figures",   'is_correct' => true],
                            ['label' => 'C', 'text' => 'A lunch break',                               'is_correct' => false],
                            ['label' => 'D', 'text' => 'A vote on the budget',                        'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── トーク6：自動音声案内（銀行） ──
            [
                'title'     => '自動音声案内（銀行）',
                'documents' => [
                    ['heading' => 'Speaker', 'body' => 'Thank you for calling Horizon Bank customer service. Our office hours are Monday through Friday, nine A.M. to six P.M.'],
                    ['heading' => 'Speaker', 'body' => "If you'd like to check your account balance, press one. To report a lost or stolen card, press two."],
                    ['heading' => 'Speaker', 'body' => 'To speak with a representative, please stay on the line, and the next available agent will assist you. We appreciate your patience during this busy time.'],
                ],
                'questions' => [
                    [
                        'question_text' => 'What kind of business is this recording for?',
                        'explanation'   => '"Thank you for calling Horizon Bank"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A hotel',              'is_correct' => false],
                            ['label' => 'B', 'text' => 'A bank',               'is_correct' => true],
                            ['label' => 'C', 'text' => 'An airline',           'is_correct' => false],
                            ['label' => 'D', 'text' => 'An insurance company', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What should a caller do to report a lost card?',
                        'explanation'   => '"To report a lost or stolen card, press two"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Press one',        'is_correct' => false],
                            ['label' => 'B', 'text' => 'Press two',        'is_correct' => true],
                            ['label' => 'C', 'text' => 'Stay on the line', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'Visit a branch',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What does the speaker say about wait times?',
                        'explanation'   => '"the next available agent will assist you...appreciate your patience"と述べ、待機が必要と示唆している。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'There is no wait',                     'is_correct' => false],
                            ['label' => 'B', 'text' => 'Calls are not being accepted',          'is_correct' => false],
                            ['label' => 'C', 'text' => 'Callers may need to wait for an agent', 'is_correct' => true],
                            ['label' => 'D', 'text' => 'The office is closed',                  'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── トーク7：ラジオニュース（交通情報） ──
            [
                'title'     => 'ラジオニュース（交通情報）',
                'documents' => [
                    ['heading' => 'Speaker', 'body' => 'In local news, construction on Main Street will begin next Monday and is expected to continue for approximately three weeks.'],
                    ['heading' => 'Speaker', 'body' => 'During this time, one lane will be closed in each direction, so drivers should expect delays during rush hour. City officials recommend using Second Avenue as an alternate route.'],
                    ['heading' => 'Speaker', 'body' => "We'll continue to provide updates on the project's progress throughout the coming weeks."],
                ],
                'questions' => [
                    [
                        'question_text' => 'What is the news report mainly about?',
                        'explanation'   => '"construction on Main Street will begin next Monday"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A new construction project', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'A weather warning',          'is_correct' => false],
                            ['label' => 'C', 'text' => 'A local election',           'is_correct' => false],
                            ['label' => 'D', 'text' => 'A sports event',             'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'How long is the construction expected to last?',
                        'explanation'   => '"expected to continue for approximately three weeks"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'One week',                     'is_correct' => false],
                            ['label' => 'B', 'text' => 'Approximately three weeks',    'is_correct' => true],
                            ['label' => 'C', 'text' => 'Six months',                    'is_correct' => false],
                            ['label' => 'D', 'text' => 'One day',                       'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What do officials recommend?',
                        'explanation'   => '"recommend using Second Avenue as an alternate route"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'Avoiding downtown entirely',   'is_correct' => false],
                            ['label' => 'B', 'text' => 'Using public transportation',  'is_correct' => false],
                            ['label' => 'C', 'text' => 'Taking an alternate route',    'is_correct' => true],
                            ['label' => 'D', 'text' => 'Driving only at night',        'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── トーク8：製品紹介（広告） ──
            [
                'title'     => '製品紹介（広告）',
                'documents' => [
                    ['heading' => 'Speaker', 'body' => 'Introducing the all-new UltraBrew coffee maker, designed for busy mornings. With its one-touch brewing system, you can have a fresh cup of coffee ready in under two minutes.'],
                    ['heading' => 'Speaker', 'body' => 'The UltraBrew also features a built-in timer, so you can set it up the night before and wake up to the smell of freshly brewed coffee.'],
                    ['heading' => 'Speaker', 'body' => 'Visit our website today to order yours and receive free shipping on all orders this month.'],
                ],
                'questions' => [
                    [
                        'question_text' => 'What is being advertised?',
                        'explanation'   => '"the all-new UltraBrew coffee maker"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A coffee maker',  'is_correct' => true],
                            ['label' => 'B', 'text' => 'A blender',       'is_correct' => false],
                            ['label' => 'C', 'text' => 'A refrigerator',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'A toaster',       'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What feature allows users to prepare coffee in advance?',
                        'explanation'   => '"features a built-in timer, so you can set it up the night before"と述べている。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'A built-in timer',      'is_correct' => true],
                            ['label' => 'B', 'text' => 'A voice control system','is_correct' => false],
                            ['label' => 'C', 'text' => 'A mobile app',          'is_correct' => false],
                            ['label' => 'D', 'text' => 'A larger water tank',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => 'What is offered this month?',
                        'explanation'   => '"receive free shipping on all orders this month"と述べている。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'A discount on accessories', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'A free coffee maker',       'is_correct' => false],
                            ['label' => 'C', 'text' => 'Free shipping',             'is_correct' => true],
                            ['label' => 'D', 'text' => 'An extended warranty',      'is_correct' => false],
                        ],
                    ],
                ],
            ],
        ];

        $sortOrder = 1;
        foreach ($talkSets as $setIdx => $set) {
            $passage = ToeicPassage::create([
                'part'         => 4,
                'passage_type' => 'talk',
                'title'        => $set['title'],
                'documents'    => $set['documents'],
                'sort_order'   => $setIdx + 1,
            ]);

            foreach ($set['questions'] as $data) {
                $this->createQuestion($data, 4, $sortOrder, $passage->id);
                $sortOrder++;
            }
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Part 5：短文穴埋め（50問プールからランダム10問出題）
    // ──────────────────────────────────────────────────────────────

    private function createPart5Questions(): void
    {
        $questions = [
            [
                'question_text' => '_____ is important to note that the project deadline has been moved to next Friday.',
                'explanation'   => '形式主語の"It"が正解。"It is important that..."の構文。That は接続詞として使えない。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'That',  'is_correct' => false],
                    ['label' => 'B', 'text' => 'It',    'is_correct' => true],
                    ['label' => 'C', 'text' => 'There', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'What',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The board of directors _____ to meet next week to discuss the budget proposal.',
                'explanation'   => '"board of directors" は集合名詞として単数扱い。三人称単数の plans が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'plans',   'is_correct' => true],
                    ['label' => 'B', 'text' => 'plan',    'is_correct' => false],
                    ['label' => 'C', 'text' => 'planning','is_correct' => false],
                    ['label' => 'D', 'text' => 'planned', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Please submit your expense reports _____ the end of the month.',
                'explanation'   => '"by" は期限を表す前置詞。"by the end of the month" で「月末までに」。until は継続を表す。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'until',  'is_correct' => false],
                    ['label' => 'B', 'text' => 'by',     'is_correct' => true],
                    ['label' => 'C', 'text' => 'during', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'since',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The new policy will _____ effect starting January 1st.',
                'explanation'   => '"take effect" は「発効する・効力を持つ」の意味のイディオム。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'take',  'is_correct' => true],
                    ['label' => 'B', 'text' => 'make',  'is_correct' => false],
                    ['label' => 'C', 'text' => 'do',    'is_correct' => false],
                    ['label' => 'D', 'text' => 'bring', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'All employees are _____ to attend the mandatory safety training session.',
                'explanation'   => '"are required to" は受動態+不定詞で「〜することが求められている」の意味。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'required',    'is_correct' => true],
                    ['label' => 'B', 'text' => 'requiring',   'is_correct' => false],
                    ['label' => 'C', 'text' => 'require',     'is_correct' => false],
                    ['label' => 'D', 'text' => 'requirement', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The renovation project was completed _____ of schedule, much to the client\'s delight.',
                'explanation'   => '"ahead of schedule" は「予定より早く」の意味のイディオム。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'in front', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'before',   'is_correct' => false],
                    ['label' => 'C', 'text' => 'ahead',    'is_correct' => true],
                    ['label' => 'D', 'text' => 'prior',    'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Ms. Chen is _____ for overseeing the implementation of the new software system.',
                'explanation'   => '"responsible for" は「〜に対して責任がある」の意味。be responsible for + 名詞/動名詞。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'accountable', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'responsible', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'capable',     'is_correct' => false],
                    ['label' => 'D', 'text' => 'eligible',    'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Due to the inclement weather, the outdoor ceremony was _____ until further notice.',
                'explanation'   => '"postponed" は「延期された」の意味。"until further notice" は「追って連絡があるまで」。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'cancelled',  'is_correct' => false],
                    ['label' => 'B', 'text' => 'postponed',  'is_correct' => true],
                    ['label' => 'C', 'text' => 'rescheduled','is_correct' => false],
                    ['label' => 'D', 'text' => 'delayed',    'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The marketing team _____ their quarterly report to the management by noon tomorrow.',
                'explanation'   => '未来の予定で "by noon tomorrow"（明日正午までに）があるので、未来形の "will submit" が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'submitted',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'will submit', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'submitting',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'has submitted','is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The new office building is conveniently _____ near the central train station.',
                'explanation'   => '"located" は「位置している」の意味。"conveniently located" は「便利な場所にある」の定型句。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'placed',  'is_correct' => false],
                    ['label' => 'B', 'text' => 'situated','is_correct' => false],
                    ['label' => 'C', 'text' => 'located', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'founded', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'By the time the new manager arrives, the team _____ the quarterly report.',
                'explanation'   => '未来のある時点までに完了している内容には未来完了形 "will have completed" を使う。',
                'difficulty'    => 'hard',
                'xp'            => 50,
                'options'       => [
                    ['label' => 'A', 'text' => 'will have completed', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'completes',           'is_correct' => false],
                    ['label' => 'C', 'text' => 'completed',           'is_correct' => false],
                    ['label' => 'D', 'text' => 'completing',          'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The conference room _____ for the board meeting every Monday.',
                'explanation'   => '主語 "The conference room" は予約「される」側なので受動態 "is reserved" が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'reserves',  'is_correct' => false],
                    ['label' => 'B', 'text' => 'is reserved','is_correct' => true],
                    ['label' => 'C', 'text' => 'reserving', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'reserve',   'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The company is considering _____ its operations to Southeast Asia.',
                'explanation'   => '"consider" は動名詞を目的語にとる動詞。"consider doing" の形。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'expand',    'is_correct' => false],
                    ['label' => 'B', 'text' => 'to expand', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'expanding', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'expansion', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Employees _____ in the pilot program will receive additional training.',
                'explanation'   => '「参加している従業員」という能動的な意味を表す現在分詞 "participating" が正解。',
                'difficulty'    => 'hard',
                'xp'            => 50,
                'options'       => [
                    ['label' => 'A', 'text' => 'participate',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'participating', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'participated',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'to participate','is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The client _____ complaint was resolved yesterday expressed satisfaction with our service.',
                'explanation'   => '空欄の後ろに名詞 "complaint" が続くので所有格の関係代名詞 "whose" が正解。',
                'difficulty'    => 'hard',
                'xp'            => 50,
                'options'       => [
                    ['label' => 'A', 'text' => 'who',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'whom',  'is_correct' => false],
                    ['label' => 'C', 'text' => 'whose', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'which', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Our new smartphone model is _____ than any other product in the market.',
                'explanation'   => '"than" があるので比較級 "lighter" が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'light',      'is_correct' => false],
                    ['label' => 'B', 'text' => 'lighter',    'is_correct' => true],
                    ['label' => 'C', 'text' => 'lightest',   'is_correct' => false],
                    ['label' => 'D', 'text' => 'more light', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => '_____ the marketing team nor the sales department was informed of the schedule change.',
                'explanation'   => '"neither A nor B"（AもBも〜ない）の相関接続詞。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Both',     'is_correct' => false],
                    ['label' => 'B', 'text' => 'Either',   'is_correct' => false],
                    ['label' => 'C', 'text' => 'Neither',  'is_correct' => true],
                    ['label' => 'D', 'text' => 'Not only', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => '_____ of the applicants who applied for the position had previous management experience.',
                'explanation'   => '"few" は可算名詞の複数形を受ける数量表現。"of the applicants" と複数が続くため。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Each', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Few',  'is_correct' => true],
                    ['label' => 'C', 'text' => 'Much', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Little','is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The seminar has been rescheduled _____ of a venue conflict.',
                'explanation'   => '文中に "of" が既にあるため、空欄には "because" のみが入る。"because of" を選ぶと "because of of a venue conflict" となり "of" が重複してしまう。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'because',    'is_correct' => true],
                    ['label' => 'B', 'text' => 'because of', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'due',        'is_correct' => false],
                    ['label' => 'D', 'text' => 'since',       'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The manager praised the team for their _____ to the project\'s success.',
                'explanation'   => '前置詞 "to" の後ろで、かつ "their" の後ろなので名詞 "contribution" が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'contribute',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'contributor',  'is_correct' => false],
                    ['label' => 'C', 'text' => 'contribution', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'contributing', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The negotiations proceeded _____ despite initial disagreements.',
                'explanation'   => '動詞 "proceeded" を修飾する副詞 "smoothly" が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'smooth',    'is_correct' => false],
                    ['label' => 'B', 'text' => 'smoothly',  'is_correct' => true],
                    ['label' => 'C', 'text' => 'smoother',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'smoothness','is_correct' => false],
                ],
            ],
            [
                'question_text' => 'It is essential that every employee _____ the updated compliance guidelines.',
                'explanation'   => '"It is essential that..." は仮定法現在（原形動詞）をとる構文。',
                'difficulty'    => 'hard',
                'xp'            => 50,
                'options'       => [
                    ['label' => 'A', 'text' => 'reviews',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'review',    'is_correct' => true],
                    ['label' => 'C', 'text' => 'reviewing', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'reviewed',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The supervisor had the technicians _____ the equipment before the inspection.',
                'explanation'   => '使役動詞 "have" + 目的語 + 原形動詞（have someone do）の形。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'inspect',    'is_correct' => true],
                    ['label' => 'B', 'text' => 'inspected',  'is_correct' => false],
                    ['label' => 'C', 'text' => 'to inspect', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'inspecting', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'If the shipment _____ on time, we would not have missed the deadline.',
                'explanation'   => '主節が "would not have missed"（仮定法過去完了）なので、if節も過去完了 "had arrived" が正解。',
                'difficulty'    => 'hard',
                'xp'            => 50,
                'options'       => [
                    ['label' => 'A', 'text' => 'arrives',     'is_correct' => false],
                    ['label' => 'B', 'text' => 'arrived',     'is_correct' => false],
                    ['label' => 'C', 'text' => 'had arrived', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'will arrive', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The proposal was found to be _____ acceptable by the committee.',
                'explanation'   => '"be動詞 + 形容詞" を修飾する副詞 "completely" が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'complete',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'completely', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'completion', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'completes',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The merger is expected to be finalized _____ the end of the fiscal year.',
                'explanation'   => '期限を表す前置詞 "by"（〜までに）が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'until', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'by',    'is_correct' => true],
                    ['label' => 'C', 'text' => 'since', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'for',   'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The board decided to _____ the budget for the upcoming quarter.',
                'explanation'   => '不定詞 "to" の後ろは動詞の原形。"revise"（〜を見直す）が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'revision',  'is_correct' => false],
                    ['label' => 'B', 'text' => 'revise',    'is_correct' => true],
                    ['label' => 'C', 'text' => 'revised',   'is_correct' => false],
                    ['label' => 'D', 'text' => 'revisable', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'All confidential documents _____ shredded before disposal.',
                'explanation'   => '"must be + 過去分詞" の受動態。文書は「シュレッダーにかけられる」側。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'must',      'is_correct' => false],
                    ['label' => 'B', 'text' => 'must be',   'is_correct' => true],
                    ['label' => 'C', 'text' => 'must have', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'must being','is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Mr. Tanaka, _____ was recently promoted to regional director, will oversee the new branch.',
                'explanation'   => '非制限用法の関係代名詞。先行詞が「人」なので "who" が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'who',   'is_correct' => true],
                    ['label' => 'B', 'text' => 'whom',  'is_correct' => false],
                    ['label' => 'C', 'text' => 'which', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'whose', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The department is responsible _____ ensuring workplace safety.',
                'explanation'   => '"be responsible for" は「〜に責任がある」の定型句。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'of',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'for',  'is_correct' => true],
                    ['label' => 'C', 'text' => 'with', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'to',   'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The training session proved to be highly _____ for new employees.',
                'explanation'   => 'be動詞の後ろで名詞 "employees" を修飾しないため、形容詞 "beneficial" が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'benefit',    'is_correct' => false],
                    ['label' => 'B', 'text' => 'beneficial', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'benefited',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'benefiting', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => '_____ the meeting begins, please turn off your mobile phones.',
                'explanation'   => '「会議が始まる前に」という時間関係を表す接続詞 "Before" が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'During', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Before', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'While',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'For',    'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'This year\'s production costs are _____ than we had projected.',
                'explanation'   => '"than" があるので比較級 "lower" が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'low',      'is_correct' => false],
                    ['label' => 'B', 'text' => 'lower',    'is_correct' => true],
                    ['label' => 'C', 'text' => 'lowest',   'is_correct' => false],
                    ['label' => 'D', 'text' => 'more low', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The report shows _____ the new strategy has increased customer satisfaction.',
                'explanation'   => '名詞節を導く接続詞 "that" が正解。"shows that S+V"。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'what',  'is_correct' => false],
                    ['label' => 'B', 'text' => 'that',  'is_correct' => true],
                    ['label' => 'C', 'text' => 'which', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'whom',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Ms. Rivera is _____ charge of the new product launch.',
                'explanation'   => '"in charge of" は「〜を担当して」の意味の群前置詞。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'at', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'on', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'in', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'for','is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The company\'s _____ to reduce waste has earned it several environmental awards.',
                'explanation'   => '主語の一部となる名詞 "commitment"（取り組み・約束）が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'commit',     'is_correct' => false],
                    ['label' => 'B', 'text' => 'commitment', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'committed',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'committing', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The building _____ renovated to comply with the new safety regulations.',
                'explanation'   => '現在完了受動態 "has been renovated"（改装された）が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'has',      'is_correct' => false],
                    ['label' => 'B', 'text' => 'has been', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'have',     'is_correct' => false],
                    ['label' => 'D', 'text' => 'having',   'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The team _____ completed the project ahead of schedule.',
                'explanation'   => '動詞 "completed" を修飾する副詞 "successfully" が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'success',    'is_correct' => false],
                    ['label' => 'B', 'text' => 'successful', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'successfully','is_correct' => true],
                    ['label' => 'D', 'text' => 'succeed',    'is_correct' => false],
                ],
            ],
            [
                'question_text' => '_____ response to customer feedback, the company redesigned its packaging.',
                'explanation'   => '"in response to" は「〜に応じて」の意味の群前置詞。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'At',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'In',   'is_correct' => true],
                    ['label' => 'C', 'text' => 'On',   'is_correct' => false],
                    ['label' => 'D', 'text' => 'With', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Each of the branch managers _____ required to submit a monthly report.',
                'explanation'   => '"Each of + 複数名詞" は単数扱い。三人称単数の "is" が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'is',   'is_correct' => true],
                    ['label' => 'B', 'text' => 'are',  'is_correct' => false],
                    ['label' => 'C', 'text' => 'were', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'have', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Employees are encouraged _____ their concerns directly to human resources.',
                'explanation'   => '"be encouraged to do"（〜するよう奨励される）の不定詞構文。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'report',    'is_correct' => false],
                    ['label' => 'B', 'text' => 'reporting', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'to report', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'reported',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => '_____ to the merger, both companies operated independently.',
                'explanation'   => '"prior to"（〜の前に）は前置詞句として使う定型表現。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Prior',       'is_correct' => true],
                    ['label' => 'B', 'text' => 'Previous',    'is_correct' => false],
                    ['label' => 'C', 'text' => 'Before that', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Ahead',       'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The factory _____ the accident occurred has since improved its safety measures.',
                'explanation'   => '先行詞が場所（The factory）で、後ろが完全な文なので関係副詞 "where" が正解。',
                'difficulty'    => 'hard',
                'xp'            => 50,
                'options'       => [
                    ['label' => 'A', 'text' => 'which', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'where', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'that',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'when',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'It _____ that the new policy will take effect next month.',
                'explanation'   => '受動態の報告構文 "It is announced that..."（〜と発表されている）。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'announces',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'is announced','is_correct' => true],
                    ['label' => 'C', 'text' => 'announcing',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'announce',    'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The _____ of the merger was announced during the shareholders\' meeting.',
                'explanation'   => '主語となる名詞 "approval"（承認）が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'approve',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'approval',  'is_correct' => true],
                    ['label' => 'C', 'text' => 'approving', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'approved',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => '_____ regard to the budget proposal, further discussion is needed.',
                'explanation'   => '"with regard to"（〜に関して）の定型句。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'With', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'At',   'is_correct' => false],
                    ['label' => 'C', 'text' => 'For',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'By',   'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The more feedback we collect, the _____ we can improve our service.',
                'explanation'   => '"the + 比較級 〜, the + 比較級 …"（〜すればするほど…）の構文。',
                'difficulty'    => 'hard',
                'xp'            => 50,
                'options'       => [
                    ['label' => 'A', 'text' => 'good',   'is_correct' => false],
                    ['label' => 'B', 'text' => 'better', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'best',   'is_correct' => false],
                    ['label' => 'D', 'text' => 'well',   'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The updated employee handbook _____ distributed next week.',
                'explanation'   => '未来受動態 "will be distributed"（配布される予定）が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'will',      'is_correct' => false],
                    ['label' => 'B', 'text' => 'will be',   'is_correct' => true],
                    ['label' => 'C', 'text' => 'is',        'is_correct' => false],
                    ['label' => 'D', 'text' => 'has',       'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The consultant offered several _____ solutions to reduce operational costs.',
                'explanation'   => '名詞 "solutions" を修飾する形容詞 "innovative"（革新的な）が正解。',
                'difficulty'    => 'easy',
                'xp'            => 30,
                'options'       => [
                    ['label' => 'A', 'text' => 'innovate',      'is_correct' => false],
                    ['label' => 'B', 'text' => 'innovation',    'is_correct' => false],
                    ['label' => 'C', 'text' => 'innovative',    'is_correct' => true],
                    ['label' => 'D', 'text' => 'innovatively',  'is_correct' => false],
                ],
            ],
            [
                'question_text' => '_____ the budget cuts, the department managed to complete all its projects.',
                'explanation'   => '後ろに名詞句 "the budget cuts" が続くため前置詞 "Despite"（〜にもかかわらず）が正解。',
                'difficulty'    => 'medium',
                'xp'            => 40,
                'options'       => [
                    ['label' => 'A', 'text' => 'Although', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Despite',  'is_correct' => true],
                    ['label' => 'C', 'text' => 'Because',  'is_correct' => false],
                    ['label' => 'D', 'text' => 'Unless',   'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $idx => $data) {
            $this->createQuestion($data, 5, $idx + 1);
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Part 6：長文穴埋め（4文書 × 4問 = 16問）
    // ──────────────────────────────────────────────────────────────

    private function createPart6Questions(): void
    {
        $documentSets = [
            // ── 文書1：出荷遅延のお詫びメール ──
            [
                'title'     => '出荷遅延のお詫びメール',
                'documents' => [[
                    'heading' => 'E-mail',
                    'body'    => "Dear Mr. Alvarez,\n\nWe are writing to inform you that your recent order (Order #4521) has been delayed due to an unexpected shortage of raw materials at our supplier's facility. We (1)_____ for any inconvenience this may cause.\n\nOur production team is currently working to resolve the issue, and we expect the shipment to be ready for dispatch within the next five business days. (2)_____, we will provide you with a tracking number as soon as the package leaves our warehouse.\n\nIn light of this delay, we would like to offer you a 10% discount on your next purchase (3)_____ our appreciation for your patience.\n\n(4)_____\n\nIf you have any questions, please do not hesitate to contact our customer service department.\n\nSincerely,\nOrder Fulfillment Team",
                ]],
                'questions' => [
                    [
                        'question_text' => '空欄(1)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '主語 "We" に対応する現在形の動詞 "apologize" が正解。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'apologize',  'is_correct' => true],
                            ['label' => 'B', 'text' => 'apologized', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'apologizing','is_correct' => false],
                            ['label' => 'D', 'text' => 'apology',    'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(2)に入る最も適切な接続副詞を選びなさい。',
                        'explanation'   => '前文の情報に追加情報を加える "In addition"（さらに）が文脈に合う。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'However',    'is_correct' => false],
                            ['label' => 'B', 'text' => 'Otherwise',  'is_correct' => false],
                            ['label' => 'C', 'text' => 'In addition','is_correct' => true],
                            ['label' => 'D', 'text' => 'Instead',    'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(3)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '「感謝の意を示すために」という目的を表す不定詞 "to show" が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'show',    'is_correct' => false],
                            ['label' => 'B', 'text' => 'showing', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'to show', 'is_correct' => true],
                            ['label' => 'D', 'text' => 'shown',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(4)に入れるのに最も適切な文を選びなさい。',
                        'explanation'   => '直前の割引提案の流れを受けて、顧客サービスへの取り組みを示す文が自然につながる。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'We hope this gesture demonstrates our commitment to quality service.', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'The new product line will be available starting next month.', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Please remember to renew your annual subscription before it expires.', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'Our office will be closed for the national holiday next Friday.', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 文書2：備品申請手続きの社内メモ ──
            [
                'title'     => '備品申請手続きの社内メモ',
                'documents' => [[
                    'heading' => 'Memo — To: All Staff / From: Facilities Management',
                    'body'    => "Effective next Monday, all requests for new office equipment (1)_____ be submitted through the updated online portal instead of paper forms. This change is intended to streamline the approval process and reduce processing time.\n\nEmployees should log in using their company credentials and select the appropriate category for their request. (2)_____ your request is submitted, you will receive a confirmation e-mail within 24 hours.\n\nPlease note that requests for equipment exceeding \$500 will require additional approval (3)_____ your department manager.\n\n(4)_____\n\nWe appreciate your cooperation in adopting this new system.",
                ]],
                'questions' => [
                    [
                        'question_text' => '空欄(1)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '来週から義務付けられる強い規則を表す "must be submitted" が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'must',   'is_correct' => true],
                            ['label' => 'B', 'text' => 'can',    'is_correct' => false],
                            ['label' => 'C', 'text' => 'may',    'is_correct' => false],
                            ['label' => 'D', 'text' => 'should', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(2)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '「送信され次第」という時間関係を表す "Once" が正解。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'Once',    'is_correct' => true],
                            ['label' => 'B', 'text' => 'Despite', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Unless',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'Although','is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(3)に入る最も適切な前置詞を選びなさい。',
                        'explanation'   => '"approval from + 人"（〜からの承認）という組み合わせ。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'of',   'is_correct' => false],
                            ['label' => 'B', 'text' => 'from', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'with', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'at',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(4)に入れるのに最も適切な文を選びなさい。',
                        'explanation'   => '手続きに関する問い合わせ先を案内する文が、結びの感謝の一文の前に自然に入る。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'If you have any questions about the new procedure, please contact the Facilities Management team.', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'The company picnic has been rescheduled to next month.', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'All invoices must be paid within thirty days of receipt.', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'The parking garage will be repaved starting next week.', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 文書3：エレベーター点検のお知らせ ──
            [
                'title'     => 'エレベーター点検のお知らせ',
                'documents' => [[
                    'heading' => 'Notice to All Tenants — Scheduled Elevator Maintenance',
                    'body'    => "This notice is to inform all tenants that the elevators in the East Wing will undergo scheduled maintenance from July 10 to July 12. During this period, elevator service (1)_____ be temporarily unavailable between 9 A.M. and 5 P.M. each day.\n\nTenants are advised to use the West Wing elevators or the stairwell (2)_____ this time. We apologize for any inconvenience this may cause and appreciate your understanding.\n\nThe maintenance work is being carried out (3)_____ ensure the long-term safety and reliability of the elevator system.\n\n(4)_____\n\nFor urgent access needs, please contact the building management office.",
                ]],
                'questions' => [
                    [
                        'question_text' => '空欄(1)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '未来の予定を表す "will be unavailable"（利用できなくなる予定）が正解。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'will',  'is_correct' => true],
                            ['label' => 'B', 'text' => 'is',    'is_correct' => false],
                            ['label' => 'C', 'text' => 'was',   'is_correct' => false],
                            ['label' => 'D', 'text' => 'has',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(2)に入る最も適切な前置詞を選びなさい。',
                        'explanation'   => '"during + 名詞句"（〜の間）の形。"this time" は名詞句。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'during', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'while',  'is_correct' => false],
                            ['label' => 'C', 'text' => 'for',    'is_correct' => false],
                            ['label' => 'D', 'text' => 'since',  'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(3)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '目的を表す不定詞 "to ensure"（確実にするために）が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'for',  'is_correct' => false],
                            ['label' => 'B', 'text' => 'to',   'is_correct' => true],
                            ['label' => 'C', 'text' => 'so',   'is_correct' => false],
                            ['label' => 'D', 'text' => 'that', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(4)に入れるのに最も適切な文を選びなさい。',
                        'explanation'   => '点検スケジュールの続報として、通常営業に戻る見込みを伝える文が自然につながる。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'We expect all services to return to normal by July 13.', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'The building\'s rooftop garden will open this summer.', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Rent payments are due on the first of each month.', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'New tenants must complete a registration form.', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 文書4：企業拡大に関する記事 ──
            [
                'title'     => '企業拡大に関する記事',
                'documents' => [[
                    'heading' => 'Article — Local Tech Firm Announces Expansion',
                    'body'    => "Greenfield Technologies, a software development firm based in Portland, announced last week that it (1)_____ open a new office in Austin, Texas, by the end of the year. The expansion is expected to create approximately 150 new jobs in the region.\n\nAccording to CEO Laura Bennett, the company chose Austin because of its (2)_____ pool of skilled engineers and its growing reputation as a technology hub.\n\n(3)_____, the company plans to invest in local partnerships with universities to support future talent development.\n\n(4)_____\n\nThe new office is scheduled to begin operations in January.",
                ]],
                'questions' => [
                    [
                        'question_text' => '空欄(1)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '今後の計画を表す未来形 "will" が正解。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'will', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'has',  'is_correct' => false],
                            ['label' => 'C', 'text' => 'had',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'is',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(2)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '名詞 "pool" を修飾する形容詞 "abundant"（豊富な）が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'abundant',   'is_correct' => true],
                            ['label' => 'B', 'text' => 'abundance',  'is_correct' => false],
                            ['label' => 'C', 'text' => 'abundantly', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'abound',     'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(3)に入る最も適切な接続副詞を選びなさい。',
                        'explanation'   => '前文の内容に情報を追加する "In addition"（さらに）が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'However',     'is_correct' => false],
                            ['label' => 'B', 'text' => 'In addition', 'is_correct' => true],
                            ['label' => 'C', 'text' => 'Otherwise',   'is_correct' => false],
                            ['label' => 'D', 'text' => 'Nevertheless','is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(4)に入れるのに最も適切な文を選びなさい。',
                        'explanation'   => '記事全体の前向きな論調に沿い、事業拡大の意義を分析する文が自然につながる。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'Analysts believe this move will strengthen the company\'s presence in the southern United States.', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'The firm\'s stock price fell sharply last quarter.', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Employees will be required to relocate immediately.', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'The company was founded over fifty years ago.', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 文書5：価格改定のお知らせレター ──
            [
                'title'     => '価格改定のお知らせレター',
                'documents' => [[
                    'heading' => 'Letter to Valued Customers',
                    'body'    => "Dear Valued Customer,\n\nWe would like to inform you that, due to rising material costs, our product prices (1)_____ by approximately 5% starting next month.\n\nWe understand this change may affect your purchasing decisions. (2)_____, we remain committed to providing the highest quality products and services.\n\nThis adjustment allows us (3)_____ maintain the quality standards our customers have come to expect.\n\n(4)_____\n\nThank you for your continued support and understanding.\n\nSincerely,\nCustomer Relations Department",
                ]],
                'questions' => [
                    [
                        'question_text' => '空欄(1)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '来月から始まる未来の予定を表す "will increase" が正解。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'will increase',   'is_correct' => true],
                            ['label' => 'B', 'text' => 'increased',       'is_correct' => false],
                            ['label' => 'C', 'text' => 'have increased',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'increasing',      'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(2)に入る最も適切な接続副詞を選びなさい。',
                        'explanation'   => '値上げという悪い知らせの後に逆接で安心材料を伝える "However"（しかしながら）が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'However',     'is_correct' => true],
                            ['label' => 'B', 'text' => 'Therefore',   'is_correct' => false],
                            ['label' => 'C', 'text' => 'In addition', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'Otherwise',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(3)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '"allow + 人 + to do"（〜が…することを可能にする）の形で不定詞 "to maintain" が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'maintain',    'is_correct' => false],
                            ['label' => 'B', 'text' => 'maintaining', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'to maintain', 'is_correct' => true],
                            ['label' => 'D', 'text' => 'maintained',  'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(4)に入れるのに最も適切な文を選びなさい。',
                        'explanation'   => '価格改定の説明の後、問い合わせ窓口を案内する文が結びの感謝の一文の前に自然につながる。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'If you have any questions regarding this change, please contact our customer service team.', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'The new product line will be available starting next month.', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Please remember to renew your annual subscription before it expires.', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'Our office will be closed for the national holiday next Friday.', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 文書6：新規オープンセール広告 ──
            [
                'title'     => '新規オープンセール広告',
                'documents' => [[
                    'heading' => 'Advertisement — Grand Opening Sale',
                    'body'    => "Come celebrate the grand opening of TechWorld Electronics this Saturday! We (1)_____ offering discounts of up to 40% on all merchandise throughout the weekend.\n\nCustomers who arrive early will receive a free gift bag. (2)_____, the first 50 customers will be entered into a raffle for a chance to win a new laptop.\n\nDon't miss this opportunity (3)_____ upgrade your devices at unbeatable prices.\n\n(4)_____\n\nVisit us at 120 Main Street or shop online at techworld.com.",
                ]],
                'questions' => [
                    [
                        'question_text' => '空欄(1)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '今週末にかけて継続する未来の予定を表す未来進行形 "will be" が正解。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'will be',     'is_correct' => true],
                            ['label' => 'B', 'text' => 'have been',   'is_correct' => false],
                            ['label' => 'C', 'text' => 'was',         'is_correct' => false],
                            ['label' => 'D', 'text' => 'being',       'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(2)に入る最も適切な接続副詞を選びなさい。',
                        'explanation'   => '前文の特典情報にさらに情報を加える "In addition"（さらに）が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'In addition',  'is_correct' => true],
                            ['label' => 'B', 'text' => 'However',      'is_correct' => false],
                            ['label' => 'C', 'text' => 'Otherwise',    'is_correct' => false],
                            ['label' => 'D', 'text' => 'Nevertheless', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(3)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '"opportunity to do"（〜する機会）の形で不定詞 "to upgrade" が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'upgrade',    'is_correct' => false],
                            ['label' => 'B', 'text' => 'upgrading',  'is_correct' => false],
                            ['label' => 'C', 'text' => 'to upgrade', 'is_correct' => true],
                            ['label' => 'D', 'text' => 'upgraded',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(4)に入れるのに最も適切な文を選びなさい。',
                        'explanation'   => '広告全体の呼びかけの流れを受けて、SNSでの継続的な情報発信を案内する文が自然につながる。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'Follow us on social media for updates on future promotions and events.', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'All returns must be made within thirty days of purchase.', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'The company was founded over twenty years ago.', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'Employees are required to wear uniforms during business hours.', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 文書7：駐車場工事に関するお知らせ ──
            [
                'title'     => '駐車場工事に関するお知らせ',
                'documents' => [[
                    'heading' => 'Notice to All Employees — Parking Lot Resurfacing',
                    'body'    => "This is to notify all employees that the north parking lot (1)_____ be closed for resurfacing from August 5 to August 9.\n\nDuring this period, employees are asked to park in the visitor lot on Elm Street. (2)_____, additional shuttle service will be provided every 15 minutes between 7 A.M. and 6 P.M.\n\nThe resurfacing project is being undertaken (3)_____ improve safety and extend the lifespan of the parking surface.\n\n(4)_____\n\nWe appreciate your patience during this necessary maintenance work.",
                ]],
                'questions' => [
                    [
                        'question_text' => '空欄(1)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '来月の予定を表す未来の助動詞 "will"（〜される予定）が正解。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'will', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'is',   'is_correct' => false],
                            ['label' => 'C', 'text' => 'was',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'has',  'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(2)に入る最も適切な接続副詞を選びなさい。',
                        'explanation'   => '駐車場案内に加えてシャトル情報を追加する "In addition"（さらに）が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'In addition', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'However',     'is_correct' => false],
                            ['label' => 'C', 'text' => 'Otherwise',   'is_correct' => false],
                            ['label' => 'D', 'text' => 'Despite',     'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(3)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '目的を表す不定詞 "to improve"（改善するために）が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'for',  'is_correct' => false],
                            ['label' => 'B', 'text' => 'to',   'is_correct' => true],
                            ['label' => 'C', 'text' => 'so',   'is_correct' => false],
                            ['label' => 'D', 'text' => 'that', 'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(4)に入れるのに最も適切な文を選びなさい。',
                        'explanation'   => '工事案内の後、問い合わせ先を案内する文が結びの感謝の一文の前に自然につながる。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'Please contact the Facilities Office if you have any questions about parking arrangements.', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'The company picnic has been rescheduled to next month.', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'All invoices must be paid within thirty days of receipt.', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'The rooftop garden will open to tenants this summer.', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
            // ── 文書8：新製品発表に関する記事 ──
            [
                'title'     => '新製品発表に関する記事',
                'documents' => [[
                    'heading' => 'Article — Local Manufacturer Launches New Product',
                    'body'    => "Bright Wave Appliances, a home appliance manufacturer headquartered in Denver, (1)_____ launch its newest line of energy-efficient refrigerators next month.\n\nThe new models are designed to reduce electricity consumption by up to 30 percent compared to previous versions. (2)_____, the company has redesigned the interior layout to increase storage capacity.\n\nIndustry analysts believe the launch comes at a time (3)_____ consumers are increasingly prioritizing sustainability.\n\n(4)_____\n\nThe refrigerators will be available at major retailers nationwide starting next month.",
                ]],
                'questions' => [
                    [
                        'question_text' => '空欄(1)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '来月の予定を表す未来形の助動詞 "will" が正解。',
                        'difficulty'    => 'easy', 'xp' => 30,
                        'options' => [
                            ['label' => 'A', 'text' => 'will', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'has',  'is_correct' => false],
                            ['label' => 'C', 'text' => 'had',  'is_correct' => false],
                            ['label' => 'D', 'text' => 'is',   'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(2)に入る最も適切な接続副詞を選びなさい。',
                        'explanation'   => '省エネ性能に加えて収納力の改善という情報を追加する "In addition"（さらに）が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'In addition', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'However',     'is_correct' => false],
                            ['label' => 'C', 'text' => 'Otherwise',   'is_correct' => false],
                            ['label' => 'D', 'text' => 'Despite',     'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(3)に入る最も適切な語句を選びなさい。',
                        'explanation'   => '"a time when 〜"（〜という時期に）という時を表す関係副詞 "when" が正解。',
                        'difficulty'    => 'medium', 'xp' => 40,
                        'options' => [
                            ['label' => 'A', 'text' => 'which', 'is_correct' => false],
                            ['label' => 'B', 'text' => 'when',  'is_correct' => true],
                            ['label' => 'C', 'text' => 'where', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'that',  'is_correct' => false],
                        ],
                    ],
                    [
                        'question_text' => '空欄(4)に入れるのに最も適切な文を選びなさい。',
                        'explanation'   => '記事全体の前向きな論調に沿い、今後の需要見通しを述べる文が自然につながる。',
                        'difficulty'    => 'hard', 'xp' => 50,
                        'options' => [
                            ['label' => 'A', 'text' => 'The company expects strong demand given the growing consumer interest in eco-friendly products.', 'is_correct' => true],
                            ['label' => 'B', 'text' => 'The firm\'s stock price fell sharply last quarter.', 'is_correct' => false],
                            ['label' => 'C', 'text' => 'Employees will be required to relocate immediately.', 'is_correct' => false],
                            ['label' => 'D', 'text' => 'The company was founded over fifty years ago.', 'is_correct' => false],
                        ],
                    ],
                ],
            ],
        ];

        $sortOrder = 1;
        foreach ($documentSets as $setIdx => $set) {
            $passage = ToeicPassage::create([
                'part'         => 6,
                'passage_type' => 'single',
                'title'        => $set['title'],
                'documents'    => $set['documents'],
                'sort_order'   => $setIdx + 1,
            ]);

            foreach ($set['questions'] as $data) {
                $this->createQuestion($data, 6, $sortOrder, $passage->id);
                $sortOrder++;
            }
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  Part 7：読解（シングル7セット + ダブル2セット + トリプル1セット = 39問）
    // ──────────────────────────────────────────────────────────────

    private function createPart7Questions(): void
    {
        $sortOrder = 1;

        // ── シングルパッセージ1：オフィス家具セールの広告 ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'single', 'sort_order' => 1,
            'title' => 'オフィス家具セールの広告',
            'documents' => [[
                'heading' => 'Advertisement',
                'body' => "ErgoWorks Office Solutions — Summer Clearance Sale\n\nSave up to 40% on select ergonomic chairs, adjustable desks, and storage solutions now through August 15. All clearance items are floor models in excellent condition and come with a one-year warranty.\n\nVisit our showroom at 220 Industrial Parkway or shop online at www.ergoworks.example.com. Orders over \$300 qualify for free delivery within a 50-mile radius. Customers who spend more than \$500 will also receive a complimentary desk organizer set.\n\nNote: Clearance items are sold on a first-come, first-served basis and cannot be returned or exchanged. For questions about specific products, please call our sales team at (503) 555-0148.",
            ]],
        ]);
        $questions = [
            [
                'question_text' => 'What is the purpose of the advertisement?',
                'explanation'   => '広告全体がオフィス家具のセールを告知する内容。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'To announce a store relocation', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'To promote a sale on office furniture', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'To recruit new sales staff', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'To introduce a new product line', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'According to the advertisement, what is required to receive free delivery?',
                'explanation'   => '"Orders over $300 qualify for free delivery" と明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Spending more than $500', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Purchasing a warranty', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'Spending more than $300', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'Shopping only online', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What is true about the clearance items?',
                'explanation'   => '"come with a one-year warranty" と本文に明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'They can be exchanged within 30 days', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'They come with a one-year warranty', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'They are only available online', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'They require a special membership', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The word "complimentary" in paragraph 2 is closest in meaning to',
                'explanation'   => '"complimentary" は「無料の」という意味。',
                'difficulty' => 'hard', 'xp' => 50,
                'options' => [
                    ['label' => 'A', 'text' => 'expensive', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'optional', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'free', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'additional', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── シングルパッセージ2：面接日程確認メール ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'single', 'sort_order' => 2,
            'title' => '面接日程確認メール',
            'documents' => [[
                'heading' => 'E-mail',
                'body' => "To: Rachel Kim\nFrom: David Chen, HR Coordinator\nSubject: Interview Confirmation\n\nDear Ms. Kim,\n\nThank you for applying for the Marketing Analyst position at Bridgeway Consulting. We were impressed with your resume and would like to invite you for an interview on Thursday, June 18, at 10:00 A.M. at our downtown office, located at 55 Harbor Street, Suite 400.\n\nThe interview will last approximately 45 minutes and will include a brief written exercise. Please bring a copy of your portfolio if available. If this time does not work for you, please reply to this e-mail by June 14 so that we can arrange an alternative schedule.\n\nWe look forward to meeting you.\n\nBest regards,\nDavid Chen",
            ]],
        ]);
        $questions = [
            [
                'question_text' => 'Why did Mr. Chen send this e-mail?',
                'explanation'   => '面接の日程を伝えるためのメール。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'To offer Ms. Kim a job', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'To schedule an interview', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'To request additional documents', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'To cancel an appointment', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What should Ms. Kim do if the proposed time is inconvenient?',
                'explanation'   => '"please reply to this e-mail by June 14" と明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Call the office immediately', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Arrive early on the day of the interview', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'Reply to the e-mail by June 14', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'Visit the office in person', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What can be inferred about the interview?',
                'explanation'   => '"will include a brief written exercise" から筆記課題が含まれることが分かる。',
                'difficulty' => 'hard', 'xp' => 50,
                'options' => [
                    ['label' => 'A', 'text' => 'It will be conducted online', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'It will include a written component', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'It requires a formal dress code', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'It will be rescheduled', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── シングルパッセージ3：図書館改装のお知らせ ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'single', 'sort_order' => 3,
            'title' => '図書館改装のお知らせ',
            'documents' => [[
                'heading' => 'Notice',
                'body' => "Notice: Temporary Closure of the Reading Room\n\nThe main reading room of the Lakeside Public Library will be closed for renovation from September 5 to September 20. During this period, the reference desk and computer lab will remain open on the second floor.\n\nPatrons may still borrow and return books using the self-checkout kiosks near the main entrance. Study rooms on the third floor will also remain available for reservation.\n\nWe apologize for any inconvenience and thank you for your patience as we work to improve our facilities. For more information, please visit the library's front desk or call (415) 555-0199.",
            ]],
        ]);
        $questions = [
            [
                'question_text' => 'What is the main purpose of the notice?',
                'explanation'   => '一時閉鎖を案内する内容。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'To announce new library hours', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'To inform patrons of a temporary closure', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'To advertise a new book collection', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'To request donations for renovation', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'According to the notice, where can patrons return books during the renovation?',
                'explanation'   => '"using the self-checkout kiosks near the main entrance" と明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'At the reference desk', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'In the study rooms', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'At the self-checkout kiosks', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'At the third-floor entrance', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Which area will remain accessible during the renovation period?',
                'explanation'   => '"Study rooms on the third floor will also remain available" とある。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'The main reading room', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'The third-floor study rooms', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'The renovation office', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'The book donation center', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'The word "facilities" in the last paragraph is closest in meaning to',
                'explanation'   => '"facilities" はここでは「設備・施設」の意味。',
                'difficulty' => 'hard', 'xp' => 50,
                'options' => [
                    ['label' => 'A', 'text' => 'staff members', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'amenities', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'financial resources', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'rules', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── シングルパッセージ4：チャット形式（テキストメッセージ） ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'single', 'sort_order' => 4,
            'title' => '社内チャット：営業資料の依頼',
            'documents' => [[
                'heading' => 'Text Message Chain',
                'body' => "Sofia Martinez [2:14 P.M.]: Hi Tom, are you still able to send me the sales figures for the Chicago region before 3 P.M.?\nTom Reilly [2:16 P.M.]: I just finished compiling them. I'll email the spreadsheet in a few minutes.\nSofia Martinez [2:17 P.M.]: Great, thanks! I need them for the client presentation this afternoon.\nTom Reilly [2:19 P.M.]: No problem. By the way, did you want the figures broken down by month or just the quarterly total?\nSofia Martinez [2:20 P.M.]: Quarterly total should be fine for now.\nTom Reilly [2:21 P.M.]: Got it. Sending it over now.",
            ]],
        ]);
        $questions = [
            [
                'question_text' => 'At 2:19 P.M., what does Mr. Reilly mean when he writes, "No problem"?',
                'explanation'   => '依頼された資料を送ることに同意している意図問題。',
                'difficulty' => 'hard', 'xp' => 50,
                'options' => [
                    ['label' => 'A', 'text' => 'He disagrees with Ms. Martinez\'s request', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'He is willing to send the figures as requested', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'He cannot finish the task on time', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'He needs more information before proceeding', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What does Ms. Martinez need the sales figures for?',
                'explanation'   => '"I need them for the client presentation this afternoon" と明記されている。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'A budget meeting', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'A client presentation', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'An employee review', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'A training session', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── ダブルパッセージ：請求書の数量相違（メール + 請求書） ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'double', 'sort_order' => 5,
            'title' => '請求書の数量相違（メール＋請求書）',
            'documents' => [
                [
                    'heading' => 'E-mail',
                    'body' => "To: accounts@brightpath.example.com\nFrom: Marcus Lee, Purchasing Manager, Novak Manufacturing\nSubject: Invoice #7734 — Discrepancy\n\nDear Accounts Team,\n\nI am writing regarding Invoice #7734, which we received on June 2. The invoice lists a quantity of 150 units of item SKU-2210, but our warehouse records confirm that only 120 units were delivered on May 28.\n\nCould you please review this discrepancy and issue a corrected invoice reflecting the actual quantity received? We would like to resolve this before processing payment.\n\nThank you for your prompt attention to this matter.\n\nRegards,\nMarcus Lee",
                ],
                [
                    'heading' => 'Invoice',
                    'body' => "BRIGHTPATH SUPPLY CO. — INVOICE\nInvoice #: 7734\nDate: June 2\nBill To: Novak Manufacturing\n\nItem: SKU-2210 — Industrial Fasteners\nQuantity: 150 units\nUnit Price: \$4.50\nSubtotal: \$675.00\nShipping: \$25.00\nTotal Due: \$700.00\n\nPayment Terms: Net 30 days\nDelivery Date: May 28",
                ],
            ],
        ]);
        $questions = [
            [
                'question_text' => 'Why did Mr. Lee write the e-mail?',
                'explanation'   => '請求書の数量相違を報告するためのメール。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'To request a discount', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'To report a quantity discrepancy on an invoice', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'To cancel an order', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'To confirm a delivery date', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'How many units did Novak Manufacturing\'s warehouse actually receive?',
                'explanation'   => 'メールに "only 120 units were delivered" とある。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => '100', 'is_correct' => false],
                    ['label' => 'B', 'text' => '120', 'is_correct' => true],
                    ['label' => 'C', 'text' => '150', 'is_correct' => false],
                    ['label' => 'D', 'text' => '175', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What is the unit price listed on the invoice?',
                'explanation'   => '請求書に "Unit Price: $4.50" とある。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => '$4.50', 'is_correct' => true],
                    ['label' => 'B', 'text' => '$25.00', 'is_correct' => false],
                    ['label' => 'C', 'text' => '$675.00', 'is_correct' => false],
                    ['label' => 'D', 'text' => '$700.00', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Based on the actual quantity received, what should the corrected subtotal be (before shipping)?',
                'explanation'   => '実際の数量120units × 単価$4.50 = $540.00。2つの文書を照合するクロスリファレンス問題。',
                'difficulty' => 'hard', 'xp' => 60,
                'options' => [
                    ['label' => 'A', 'text' => '$540.00', 'is_correct' => true],
                    ['label' => 'B', 'text' => '$675.00', 'is_correct' => false],
                    ['label' => 'C', 'text' => '$700.00', 'is_correct' => false],
                    ['label' => 'D', 'text' => '$450.00', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What will most likely happen next?',
                'explanation'   => 'メールで修正請求書の発行を依頼しているため、次のアクションとして最も自然なもの。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Novak Manufacturing will pay the invoice in full immediately', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Brightpath Supply will issue a corrected invoice', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'The order will be cancelled entirely', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Novak Manufacturing will return all units', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── トリプルパッセージ：研修ワークショップ（広告＋確認メール＋日程変更通知） ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'triple', 'sort_order' => 6,
            'title' => '研修ワークショップ（広告＋確認メール＋日程変更通知）',
            'documents' => [
                [
                    'heading' => 'Advertisement',
                    'body' => "Career Growth Workshop Series — Register Now!\n\nJoin Pinecrest Professional Institute for a three-part workshop series designed to help professionals advance their careers. Sessions include: 'Effective Negotiation Skills' (July 8), 'Leadership in the Digital Age' (July 15), and 'Time Management for Managers' (July 22). Each session runs from 6:00 P.M. to 8:30 P.M. at the Pinecrest Conference Center.\n\nEarly registration (before July 1) costs \$75 per session or \$200 for all three. Registration after July 1 costs \$90 per session. Space is limited to 40 participants per session.",
                ],
                [
                    'heading' => 'E-mail Confirmation',
                    'body' => "To: Priya Nair\nFrom: registration@pinecrest.example.com\nSubject: Workshop Registration Confirmation\n\nDear Ms. Nair,\n\nThank you for registering for the Career Growth Workshop Series. You have registered for all three sessions at the early registration rate. Your total payment of \$200 has been received.\n\nPlease arrive 15 minutes early to each session to check in and collect your materials. If you are unable to attend a session, please notify us at least 24 hours in advance so we may offer your spot to another participant.\n\nWe look forward to seeing you there.",
                ],
                [
                    'heading' => 'Schedule Update Notice',
                    'body' => "Schedule Update — Career Growth Workshop Series\n\nPlease note that the July 15 session, 'Leadership in the Digital Age,' has been moved from the Pinecrest Conference Center to the Willow Room at the Grand Hotel due to a scheduling conflict. All other sessions remain at their original location. Registered participants will receive directional signage at the main entrance of the Grand Hotel.",
                ],
            ],
        ]);
        $questions = [
            [
                'question_text' => 'What is indicated about Ms. Nair\'s registration?',
                'explanation'   => '早期登録料金の合計$200を支払っていることから、7月1日より前に登録したと分かる。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'She registered for only one session', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'She paid the standard rate', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'She registered before July 1', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'She requested a refund', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'How much would a single session cost after July 1?',
                'explanation'   => '広告に "Registration after July 1 costs $90 per session" とある。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => '$75', 'is_correct' => false],
                    ['label' => 'B', 'text' => '$90', 'is_correct' => true],
                    ['label' => 'C', 'text' => '$200', 'is_correct' => false],
                    ['label' => 'D', 'text' => '$40', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What should participants do if they cannot attend a session?',
                'explanation'   => '確認メールに "please notify us at least 24 hours in advance" とある。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Arrive 15 minutes late', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Notify the organizers at least 24 hours in advance', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'Send a written apology letter', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Forfeit their registration fee immediately', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Which session will take place at a different location than originally planned?',
                'explanation'   => '日程変更通知に "the July 15 session... has been moved" とある。3つの文書を照合するクロスリファレンス問題。',
                'difficulty' => 'hard', 'xp' => 60,
                'options' => [
                    ['label' => 'A', 'text' => 'Effective Negotiation Skills', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Leadership in the Digital Age', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'Time Management for Managers', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'All three sessions', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Where will Ms. Nair most likely attend the session on July 15?',
                'explanation'   => '確認メールで全セッション登録済みと分かり、日程変更通知でJuly15はWillow Roomに変更されたと分かる。3文書の統合が必要な問題。',
                'difficulty' => 'hard', 'xp' => 60,
                'options' => [
                    ['label' => 'A', 'text' => 'Pinecrest Conference Center', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Her own office', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'The Willow Room at the Grand Hotel', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'An online meeting', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── シングルパッセージ5：求人広告（マーケティング担当者募集） ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'single', 'sort_order' => 7,
            'title' => '求人広告（マーケティング担当者募集）',
            'documents' => [[
                'heading' => 'Advertisement',
                'body' => "Northfield Marketing Group is seeking a Marketing Coordinator to join our growing team in Seattle. The ideal candidate will have at least two years of experience in digital marketing and strong writing skills.\n\nResponsibilities include managing social media accounts, drafting promotional content, and analyzing campaign performance. This is a full-time position with a competitive salary and benefits package, including health insurance and paid vacation.\n\nInterested applicants should submit a resume and cover letter to careers@northfieldmarketing.example.com by July 25. Only candidates selected for an interview will be contacted.",
            ]],
        ]);
        $questions = [
            [
                'question_text' => 'What is the purpose of the advertisement?',
                'explanation'   => '求人広告全体がマーケティング担当者の募集内容。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'To announce a company merger', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'To recruit a new employee', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'To promote a marketing campaign', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'To advertise a training course', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'According to the advertisement, what is required for the position?',
                'explanation'   => '"at least two years of experience in digital marketing" と明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'A graduate degree', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'At least two years of digital marketing experience', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'Fluency in a foreign language', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Prior management experience', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What should interested applicants do?',
                'explanation'   => '"submit a resume and cover letter to careers@..." と明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Visit the company office in person', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Call the HR department', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'Submit a resume and cover letter by e-mail', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'Attend a job fair', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What can be inferred about the hiring process?',
                'explanation'   => '"Only candidates selected for an interview will be contacted" から、全員には連絡がないと分かる。',
                'difficulty' => 'hard', 'xp' => 50,
                'options' => [
                    ['label' => 'A', 'text' => 'All applicants will be interviewed', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Only shortlisted applicants will be contacted', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'The position is part-time', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'The deadline has already passed', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── シングルパッセージ6：社内メモ（リモートワーク制度の変更） ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'single', 'sort_order' => 8,
            'title' => '社内メモ（リモートワーク制度の変更）',
            'documents' => [[
                'heading' => 'Memo — To: All Staff / From: Human Resources',
                'body' => "Effective August 1, employees will be permitted to work remotely up to two days per week, subject to manager approval. This policy replaces the previous requirement of full-time in-office attendance.\n\nEmployees wishing to participate should submit a remote work request form to their supervisor at least one week in advance. Requests will be reviewed based on team workload and project deadlines.\n\nPlease note that certain roles, such as those requiring on-site equipment access, may not be eligible for this arrangement. For questions, contact the HR department.",
            ]],
        ]);
        $questions = [
            [
                'question_text' => 'What is the main purpose of the memo?',
                'explanation'   => 'リモートワーク制度の導入を知らせる内容。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'To announce a new remote work policy', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'To cancel all remote work arrangements', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'To introduce a new HR manager', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'To request feedback on office design', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What must employees do before working remotely?',
                'explanation'   => '"submit a remote work request form... at least one week in advance" と明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Purchase their own equipment', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Submit a request form in advance', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'Complete a training course', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Resign from their current position', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'According to the memo, who might not be eligible for remote work?',
                'explanation'   => '"roles requiring on-site equipment access, may not be eligible" と明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Employees who requested it late', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Employees in roles requiring on-site equipment', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'New employees only', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Employees without a manager', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── ダブルパッセージ2：出張日程確認（メール＋旅程表） ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'double', 'sort_order' => 9,
            'title' => '出張日程確認（メール＋旅程表）',
            'documents' => [
                [
                    'heading' => 'E-mail',
                    'body' => "To: Daniel Osei\nFrom: Travel Department\nSubject: Business Trip Itinerary — Chicago Conference\n\nDear Mr. Osei,\n\nAttached is your finalized itinerary for the upcoming trip to Chicago for the National Sales Conference. Please review the details carefully and confirm your flight preferences by June 20.\n\nNote that your hotel reservation includes breakfast, and a shuttle service to the conference venue will be available each morning at 7:30 A.M.\n\nIf you need to make any changes, please contact our office as soon as possible.\n\nBest regards,\nTravel Department",
                ],
                [
                    'heading' => 'Itinerary',
                    'body' => "BUSINESS TRIP ITINERARY — Daniel Osei\nDestination: Chicago, IL\nConference: National Sales Conference\n\nDeparture: June 24, 8:15 A.M., Flight 220\nReturn: June 27, 6:40 P.M., Flight 335\nHotel: Lakeshore Grand Hotel (June 24–27)\nConference Venue: Chicago Convention Center\nShuttle Departure: 7:30 A.M. daily from hotel lobby",
                ],
            ],
        ]);
        $questions = [
            [
                'question_text' => 'Why was the e-mail sent to Mr. Osei?',
                'explanation'   => '出張の確定旅程を伝えるメール。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'To cancel his business trip', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'To provide his finalized travel itinerary', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'To request a hotel refund', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'To offer him a promotion', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What is Mr. Osei asked to do by June 20?',
                'explanation'   => '"confirm your flight preferences by June 20" と明記されている。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'Book his own flight', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Confirm his flight preferences', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'Submit an expense report', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Cancel his hotel reservation', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'According to the itinerary, what time does the shuttle depart each morning?',
                'explanation'   => '旅程表に "Shuttle Departure: 7:30 A.M. daily" とある。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => '6:40 A.M.', 'is_correct' => false],
                    ['label' => 'B', 'text' => '7:30 A.M.', 'is_correct' => true],
                    ['label' => 'C', 'text' => '8:15 A.M.', 'is_correct' => false],
                    ['label' => 'D', 'text' => '9:00 A.M.', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'How many nights will Mr. Osei stay at the hotel?',
                'explanation'   => '旅程表よりJune24〜27の宿泊のため3泊。メールと旅程表を照合するクロスリファレンス問題。',
                'difficulty' => 'hard', 'xp' => 60,
                'options' => [
                    ['label' => 'A', 'text' => 'Two', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Three', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'Four', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Five', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => "What amenity is included with Mr. Osei's hotel reservation?",
                'explanation'   => 'メールに "your hotel reservation includes breakfast" とある。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Airport pickup', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Breakfast', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'A rental car', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Conference registration', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }

        // ── シングルパッセージ7：レストランのオンラインレビュー ──
        $passage = ToeicPassage::create([
            'part' => 7, 'passage_type' => 'single', 'sort_order' => 10,
            'title' => 'レストランのオンラインレビュー',
            'documents' => [[
                'heading' => 'Online Review — Harbor View Bistro',
                'body' => "I visited Harbor View Bistro last weekend for my anniversary dinner, and the experience exceeded my expectations. The seafood pasta was outstanding, and the staff were attentive without being intrusive.\n\nThe only drawback was the noise level; the dining room was quite loud due to its popularity, making conversation difficult at times. I would recommend requesting a table on the outdoor patio if you prefer a quieter atmosphere.\n\nOverall, I would rate this restaurant 4 out of 5 stars and plan to return for special occasions in the future.",
            ]],
        ]);
        $questions = [
            [
                'question_text' => 'What is the purpose of the review?',
                'explanation'   => 'レビュー全体が食事体験の感想を述べる内容。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => 'To complain about poor service', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'To share an opinion about a dining experience', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'To advertise a new restaurant', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'To request a refund', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'According to the review, what did the writer dislike about the restaurant?',
                'explanation'   => '"The only drawback was the noise level" と明記されている。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'The food quality', 'is_correct' => false],
                    ['label' => 'B', 'text' => "The staff's attitude", 'is_correct' => false],
                    ['label' => 'C', 'text' => 'The noise level', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'The price', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What does the writer suggest for a quieter experience?',
                'explanation'   => '"I would recommend requesting a table on the outdoor patio" とある。',
                'difficulty' => 'medium', 'xp' => 40,
                'options' => [
                    ['label' => 'A', 'text' => 'Visiting during lunch hours', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Requesting a table on the outdoor patio', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'Making a reservation in advance', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Avoiding weekends', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'What rating did the writer give the restaurant?',
                'explanation'   => '"I would rate this restaurant 4 out of 5 stars" と明記されている。',
                'difficulty' => 'easy', 'xp' => 30,
                'options' => [
                    ['label' => 'A', 'text' => '3 out of 5', 'is_correct' => false],
                    ['label' => 'B', 'text' => '4 out of 5', 'is_correct' => true],
                    ['label' => 'C', 'text' => '5 out of 5', 'is_correct' => false],
                    ['label' => 'D', 'text' => '2 out of 5', 'is_correct' => false],
                ],
            ],
        ];
        foreach ($questions as $data) { $this->createQuestion($data, 7, $sortOrder, $passage->id); $sortOrder++; }
    }
}
