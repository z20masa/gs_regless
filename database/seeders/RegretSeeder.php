<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Regret;
use App\Models\User;

class RegretSeeder extends Seeder
{
    public function run(): void
    {
        // ログイン中のユーザー、または最初のユーザーを取得
        $user = User::first();

        if (!$user) {
            $this->command->error('ユーザーが見つかりません。先にユーザー登録をするか、UserSeederを実行してください。');
            return;
        }

        $samples = [
            [
                'title' => '高校時代の部活を途中で辞めたこと',
                'content' => '辛くて逃げるように辞めてしまった。最後まで続けていたら、今の自分はもっと粘り強かったのではないかと思ってしまう。',
                'category' => 'スポーツ',
                'is_burned' => false,
            ],
            [
                'title' => '第一志望の大学に落ちた時の挫折',
                'content' => 'あの時もっと勉強していれば。滑り止めの大学に行くことになった自分をずっと許せなかった。',
                'category' => '進学',
                'is_burned' => true,
                'ai_letter' => "親愛なるあなたへ。\nあの時の悔しさは、今のあなたが「他人の痛みに寄り添える優しさ」を持つための大切なステップでした。結果は望むものではなかったかもしれませんが、その過程で得た学びは消えません。\nいろんな後悔を経験した全ての人たちより",
            ],
            [
                'title' => '転職のチャンスを逃した',
                'content' => '数年前、ヘッドハンティングの話があったのに怖くて断ってしまった。今の安定も価値があるが、挑戦しなかった自分が情けない。',
                'category' => '就職・転職',
                'is_burned' => false,
            ],
        ];

        foreach ($samples as $sample) {
            Regret::create(array_merge($sample, [
                'user_id' => $user->id,
                'social' => rand(1, 5),
                'existential' => rand(1, 5),
                'cognitive' => rand(1, 5),
                'emotional' => rand(1, 5),
                'behavioral' => rand(1, 5),
                'temporal' => rand(1, 5),
            ]));
        }

        $this->command->info('サンプルデータを3件登録しました。');
    }
}