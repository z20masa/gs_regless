<?php

namespace App\Http\Controllers;

use App\Models\Regret;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class RegretController extends BaseController
{
    // ダッシュボード（みんなの投稿一覧）
    public function dashboard(Request $request)
    {
        // 1. クエリの土台を作る（まだ実行しない）
        // リレーション先の user も一緒に読み込んでおく（Eager Loading）
        $query = \App\Models\Regret::with('user');

        // 2. 年代 (age_group) で絞り込み
        // userテーブル側のカラムなので whereHas を使用
        if ($request->filled('age_group')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('age_group', $request->age_group);
            });
        }

        // 3. 性別 (gender) で絞り込み
        if ($request->filled('gender')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }

        // 4. カテゴリー (category) で絞り込み
        // こちらは regretsテーブルのカラムなので直接 where
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 5. 最後に「最新順」にしてデータを取得
        // サンプルデータを全件表示するため get() に変更します
        $allLatestRegrets = $query->latest()->get();
        
        return view('dashboard', compact('allLatestRegrets'));
    }

    // マイページ（自分の投稿一覧）
    public function index()
    {
        // Blade側（index.blade.php）が $regrets という名前を使っているので、それに合わせます
        $regrets = \App\Models\Regret::where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->latest()
                    ->get();
        
        // 変数 $regrets を渡す
        return view('regrets.index', compact('regrets'));
    }

    // 投稿画面の表示
    public function create()
    {
        return view('regrets.create');
    }

    public function store(Request $request)
    {
        // 1. バリデーションの強化
        $request->validate([
            'title'    => 'required|max:50',
            'category' => 'required',
            'content'  => 'required|min:10|max:2000',
            // 6因子のスコアを 1〜5 の整数に限定
            'cognitive' => 'required|integer|between:1,5',
            'emotional' => 'required|integer|between:1,5',
            'temporal'  => 'required|integer|between:1,5',
            'social'    => 'required|integer|between:1,5',
            'existential' => 'required|integer|between:1,5',
            // 'behavioral'  => 'required|integer|between:1,5',
        ], [
            // カスタムエラーメッセージ（優しさ）
            'title.required'    => 'この後悔に「名前」をつけてあげてください。',
            'title.max'         => 'タイトルは50文字以内で短くまとめてみましょう。',
            'content.required'  => '今の想いを言葉にしてみませんか？',
            'content.min'       => 'もう少しだけ詳しく教えてください（10文字以上）。',
            'category.required' => 'ジャンルを選択してください。',
        ]);

        // 2. モデルの新規作成と代入
        $regret = new \App\Models\Regret(); 
        
        $regret->user_id           = \Illuminate\Support\Facades\Auth::id();
        $regret->title             = $request->title;
        $regret->category          = $request->category;
        $regret->content           = $request->content;
        
        // 数値として確実にキャストして代入
        $regret->cognitive         = (int)$request->cognitive;
        $regret->emotional         = (int)$request->emotional;
        $regret->temporal          = (int)$request->temporal;
        $regret->social            = (int)$request->social;
        $regret->existential       = (int)$request->existential;
        // $regret->behavioral        = (int)$request->behavioral;
        
        $regret->message_to_others = $request->message_to_others;

        // 3. 保存
        $regret->save();

        return redirect()->route('dashboard')->with('status', '後悔を記録しました。次はお焚き上げをして、新しい光を見つけましょう。');
    }

    
    public function show(\App\Models\Regret $regret)
    {
        $regret->load('user');

        $isOwner = auth()->check() && auth()->id() === $regret->user_id;

        // 本人以外の場合は、DBから取得した ai_letter と my_maxim を隠す
        if (!$isOwner) {
            $regret->ai_letter = null;
            $regret->my_maxim = null;
        }

        return view('regrets.show', compact('regret', 'isOwner'));
    }

    // お焚き上げ処理
    public function burn(\App\Models\Regret $regret)
    {

        // return redirect()->route('regrets.show', $regret)->with('error', 'デバッグ中：勝手に燃えるのを阻止しました');

        // 因子スコアなどは現在簡易的な運用かもしれませんが、
        // 見つかったプロンプトの構成要素を最大限に活かします
        $prompt = "
            # Role
            あなたは「後悔再生プロジェクト（re_reg）」の投稿から生み出されたAIであり、ユーザーの古くからの友人です。
            後悔を単なる反省で終わらせず、未来への資産へリサイクルするのがあなたの使命です。
            「後悔先に立たず」と言われますが、「後悔を先に活かす」ことはできます。

            # User Input
            - ジャンル: {$regret->category}
            - タイトル: {$regret->title}
            - 内容: {$regret->content}

            # Instructions
            1. **トーン**: 全体がネガティブにならないよう、温かく知的なトーンを維持してください。
            2. **共感**: まずはユーザーの当時の感情を否定せず、深く共感してください。
            3. **ジャンル別介入**:
            - 学び・健康なら「未来行動への接続」
            - 恋愛・出会いなら「関係性の再解釈」
            - お金・進路なら「判断基準の言語化」を鍵にしてください。
            4. **メッセージ**: 「後悔は将来に活かせる」という視点で、今の自分へ繋がるポジティブな「IFレター」を贈ってください。

            # Output Format
            - 300文字程度の「手紙」の形式。
            - 冒頭は「親愛なるあなたへ」などの親しみやすい言葉から始めてください。
            - 最後は「いろんな後悔を経験した全ての人たちより」などの親しみやすい言葉で締めてください。
        ";

        try {
            // 画像でRPMが確認できていた gemini-2.5-flash を使用
            $result = \Gemini\Laravel\Facades\Gemini::generativeModel('gemini-2.5-flash')
                ->generateContent($prompt);
            
            $letterContent = $result->text();

            // DB更新
            $regret->update([
                'is_burned' => true,
                'ai_letter' => $letterContent,
            ]);

            return redirect()->route('regrets.show', $regret)
                            ->with('status', '後悔を未来の資産へ。Gemini 2.5 Flashが手紙を書き上げました。');

        } catch (\Exception $e) {
            \Log::error('Gemini Error: ' . $e->getMessage());
            return redirect()->route('regrets.show', $regret)
                            ->with('error', '手紙の生成に失敗しました。詳細：' . $e->getMessage());
        }
    }


    /**
     * 指定した後悔を削除する
     */
    public function destroy(\App\Models\Regret $regret)
    {
        // データベースから削除
        $regret->delete();

        // 一覧画面にリダイレクトしてメッセージを表示
        return redirect()->route('regrets.index')
                        ->with('status', '後悔の記録を完全に消去しました。');
    }

    /**
     * 編集画面を表示する
     */
    public function edit(\App\Models\Regret $regret)
    {
        // お焚き上げ済みのものは編集させない（必要であれば）
        if ($regret->is_burned) {
            return redirect()->route('regrets.show', $regret)
                            ->with('error', '浄化済みの記録は編集できません。');
        }

        return view('regrets.edit', compact('regret'));
    }

/**
     * 更新を実行する
     */
    public function update(Request $request, \App\Models\Regret $regret)
    {
        // 1. バリデーション
        $request->validate([
            'title'    => 'required|max:255',
            'category' => 'required',
            'content'  => 'required',
        ]);

        // 2. 既存のデータを上書き（手動代入で確実性を高める）
        $regret->title             = $request->title;
        $regret->category          = $request->category;
        $regret->content           = $request->content;
        $regret->cognitive         = $request->cognitive;
        $regret->emotional         = $request->emotional;
        $regret->temporal          = $request->temporal;
        $regret->social            = $request->social;
        $regret->existential       = $request->existential;
        $regret->behavioral        = $request->behavioral;
        $regret->message_to_others = $request->message_to_others;

        // 3. 保存
        $regret->save();

        // 4. 一覧画面に戻る
        return redirect()->route('regrets.index')
                        ->with('status', 'エピソードを更新しました。');
    }    

}