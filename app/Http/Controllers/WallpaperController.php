<?php

namespace App\Http\Controllers; // ← ここが App\Http\Controllers になっているか

use App\Models\Regret;
use App\Services\AiLetterService;
use Illuminate\Http\Request;

class WallpaperController extends Controller // ← クラス名が WallpaperController か
{
    protected $aiService;

    public function __construct(AiLetterService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function edit(Regret $regret)
    {
        if (!$regret->ai_letter) {
            return redirect()->route('regrets.show', $regret)
                             ->with('error', '先にお焚き上げをして手紙を受け取ってください。');
        }

        // 格言を生成
        $maxim = $this->aiService->generateMaxim($regret->content, $regret->ai_letter);

        return view('wallpaper.edit', compact('regret', 'maxim'));
    }
}