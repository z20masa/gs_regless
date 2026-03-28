<?php

namespace App\Http\Controllers; 

use App\Models\Regret;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * 管理画面の表示
     */
    public function index()
    {
        // 門番：管理者でなければ403を出す
        if (!auth()->check() || !auth()->user()->is_admin) { abort(403); }

        // 全件取得
        $allRegrets = Regret::with('user')->latest()->get();

        $categoryAverages = [];
        $genderAverages = [];

        return view('admin.index', compact('allRegrets', 'categoryAverages', 'genderAverages'));
    }

    /**
     * CSVエクスポート
     */
    public function exportCsv()
    {
        // 門番：ここでもチェック
        if (!auth()->check() || !auth()->user()->is_admin) { abort(403); }

        $regrets = Regret::with('user')->get();

        return new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($regrets) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // Excel用BOM

            // ヘッダーに「投稿内容」を追加し、手紙のカラム名を整理
            fputcsv($handle, [
                'ID', '投稿日', 'ニックネーム', '年代', '性別', 
                'カテゴリー', 'タイトル', '投稿内容', 'AI手紙(ai_letter)', 
                '認知的(cognitive)', '感情的(emotional)', '行動的(behavioral)', 
                '社会的(social)', '実存的(existential)', '時間的(temporal)'
            ]);

            foreach ($regrets as $regret) {
                fputcsv($handle, [
                    $regret->id,
                    $regret->created_at->format('Y-m-d'),
                    $regret->user->nickname ?? '不明',
                    $regret->user->age_group ?? '不明',
                    $regret->user->gender ?? '不明',
                    $regret->category,
                    $regret->title,
                    $regret->content,    // ★追加：投稿の本文
                    $regret->ai_letter,  // ★修正：if_letterからai_letterへ
                    $regret->cognitive,
                    $regret->emotional,
                    $regret->behavioral,
                    $regret->social,
                    $regret->existential,
                    $regret->temporal,
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="regrets_data_' . date('Ymd') . '.csv"',
        ]);
    }
}