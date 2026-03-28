<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Regret;

class GenerateDailyInsights extends Command
{
    protected $signature = 'insights:generate';
    protected $description = '1日1回、後悔エピソードを解析してインサイトを生成します';

    public function handle()
    {
        $this->info('AI解析を開始します...');
        
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            $this->error('APIキーが見つかりません。.envを確認してください。');
            return;
        }

        $regrets = Regret::all()->map(fn($r) => "- {$r->title}: {$r->content}")->implode("\n");

        if (empty($regrets)) {
            $this->warn('解析対象のエピソードがありません。');
            return;
        }

        $types = [
            1 => ['title' => '📊 全体的な傾向と分析', 'prompt' => "以下の「後悔エピソード」群を分析し、共通する傾向や心理的背景を150文字程度で要約してください。\n\n{$regrets}"],
            2 => ['title' => '✨ 今日最も心に響く教訓', 'prompt' => "以下のエピソード群の中から、特に現代人が学ぶべき普遍的な教訓を1つ抽出し、150文字程度で解説してください。\n\n{$regrets}"],
            3 => ['title' => '💡 明日を前向きに変えるヒント', 'prompt' => "これらの後悔を「リサイクル」して、明日から実行できる具体的なポジティブな行動指針を150文字程度で提案してください。\n\n{$regrets}"]
        ];

        foreach ($types as $type => $data) {
                    // リストで確認できた最新モデル「gemini-2.5-flash」を直接指定します
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $data['prompt']]
                                ]
                            ]
                        ]
                    ]);

                    if ($response->failed()) {
                        // エラー内容を詳しく表示
                        $this->error("APIエラー({$data['title']}): " . $response->body());
                        $aiContent = '解析に失敗しました。';
                    } else {
                        // 成功！
                        $aiContent = $response->json('candidates.0.content.parts.0.text') ?? '解析に失敗しました。';
                        dump("--- {$data['title']} の解析結果 ---");
                        dump($aiContent);
                    }


            DB::table('daily_insights')->updateOrInsert(
                ['target_date' => now()->toDateString(), 'type' => $type],
                [
                    'title' => $data['title'],
                    'content' => $aiContent,
                    'created_at' => now(), 
                    'updated_at' => now(),
                ]
            );
        }

        $this->info('インサイトの生成が完了しました！');
    }
}