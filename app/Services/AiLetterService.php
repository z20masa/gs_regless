<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class AiLetterService
{
    /**
     * 後悔エピソードから「IFレター」を生成する
     */
    public function generateLetter($episode, $genre, $scores, $userProfile)
    {
        try {
            // スコアの存在チェック（バリデーション漏れ対策）
            if (empty($episode)) {
                throw new \Exception('Episode content is empty.');
            }

            $isAction = ($scores['behavioral'] ?? 0) >= 4; // 仮：4以上を実行済みとする

            // 実行
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->generateContent($this->buildLetterPrompt($episode, $genre, $scores, $isAction));
            
            return $result->text();

        } catch (\Gemini\Laravel\Exceptions\EnvelopeException $e) {
            // Gemini特有のブロック（不適切コンテンツ判定など）
            Log::warning('Gemini Content Blocked: ' . $e->getMessage());
            return "あなたの想いを大切に受け止めようとしたのですが、言葉にするのが少し難しいようです。表現を少し変えて、もう一度お焚き上げをしてみませんか？";

        } catch (\Exception $e) {
            // その他の通信エラーやシステムエラー
            Log::error('Gemini Letter Generation Failed: ' . $e->getMessage());
            return "ただいまAIが「後悔」を「光」に変える魔法を練り直しています。お焚き上げの火が落ち着くのを待って、数分後にもう一度お試しください。";
        }
    }
    /**
     * プロンプトを組み立てる
     */
private function buildLetterPrompt($episode, $genre, $scores, $isAction, $age_group, $gender, $message_to_others)
{
    // 因子スコアを読みやすく整形
    $scoreDetails = json_encode($scores, JSON_UNESCAPED_UNICODE);

    return "
    # Role
    あなたは「後悔再生プロジェクト（re_reg）」から生まれたAIであり、ユーザーの古くからの友人です。
    使命：後悔を未来への価値へリサイクルし、「後悔を先に活かす」手助けをすること。
    あなたの分析と再定義によって、ユーザーの心が軽くなるようなメッセージを生成してください。

    # User Input
    - ジャンル: {$genre}
    - 内容: {$episode}
    - 後悔のタイプ: " . ($isAction ? 'やったこと（作為）' : 'やらなかったこと（不作為）') . "
    - 因子スコア: {$scoreDetails}
    - ユーザー属性: {$age_group} / {$gender}
    - 過去の自分への一言: {$message_to_others}

    # Instructions
    1. **共感と受容**: まず当時の感情を肯定してください。「やらなかったこと」は、当時の自分を守るための精一杯の選択だったことを認めてください。
    2. **属性別アプローチ**: 
       - {$age_group} という年代に合わせ、文脈（再挑戦、再設計、あるいは受容と統合）を調整してください。
    3. **因子の分析**: スコアから「IF思考の強さ」や「未処理の痛み」を読み取り、言葉を選んでください。
    4. **リサイクル**: この後悔があったからこそ得られた視点や、これからの人生にどう活かせるかを押し付けがましくないトーンで提示してください。

    # Output Format
    - 形式: 200文字程度の「親愛なる友人への手紙」
    - 冒頭: 「親愛なるあなたへ」などの親しみやすい挨拶から開始。
    - 結び: 「後悔を希望に変えようとしている、あなたの友人より」といった、温かい言葉で締めてください。
    - 注意: 説教臭くならず、あくまで対等な友人としてのトーンを維持すること。
    ";
}
    /**
     * ダッシュボード用の汎用分析メソッド
     */
    public function callGemini($prompt)
    {
        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->generateContent($prompt);
            
            return $result->text();
        } catch (\Exception $e) {
            Log::error('Gemini API Call Failed: ' . $e->getMessage());
            throw $e;
        }
    } // <-- callGemini はここで終わり

    /**
     * エピソードとIFレターから、壁紙用の短い「格言」を生成する
     */
    public function generateMaxim($episode, $ifLetter)
    {
        try {
            $prompt = "
                # Role
                あなたは、ユーザーが投稿したその人の「後悔エピソード」を元に、それぞれの投稿者に対して、最適な
                心に響く想いをのせた「投稿者自身の後悔からうまれる格言」をつくる日本でも有数のコピーライターです。
                
                # Input
                - ユーザーの後悔: {$episode}
                - IF LETTERが生成した手紙: {$ifLetter}
                
                # Instructions
                1. 上記の内容を凝縮し、スマホの壁紙として毎日眺めたくなる「短い格言（30文字以内）」を1つだけ生成してください。
                2. 押し付けがましくなく、ふとした時に心を軽くするような、優しく知的なトーンにしてください。
                3. 後悔をその人の未来に活かす、という想いを言葉にのせてください。
                4. 余計な解説や「」などの記号は不要です。テキストのみを出力してください。

            ";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->generateContent($prompt);
            
            return $result->text();
        } catch (\Exception $e) {
            Log::error('Gemini Maxim Generation Failed: ' . $e->getMessage());
            return "過去の自分も、今の自分も、どちらも大切に。"; // フォールバック用
        }
    }

} // <-- クラス全体はここで終わり