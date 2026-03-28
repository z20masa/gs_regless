<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegretController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WallpaperController;

// ==========================================
// 1. 公開ページ（ログイン不要・誰でも見れる）
// ==========================================
Route::view('/', 'welcome');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');


// ==========================================
// 2. 認証が必要なグループ（ログイン必須）
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // ダッシュボード
    Route::get('/dashboard', [RegretController::class, 'dashboard'])->name('dashboard');
    
    // プロフィール
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile-view', [ProfileController::class, 'edit'])->name('profile');

    // 後悔関連
    // --- 1. 固定のパスを上に ---
    Route::get('/regrets', [RegretController::class, 'index'])->name('regrets.index');
    Route::get('/regrets/create', [RegretController::class, 'create'])->name('regrets.create');
    Route::post('/regrets', [RegretController::class, 'store'])->name('regrets.store');

    // --- 2. 特定の操作（editやburn）を先に ---
    Route::get('/regrets/{regret}/edit', [RegretController::class, 'edit'])->name('regrets.edit');
    Route::post('/regrets/{regret}/burn', [RegretController::class, 'burn'])->name('regrets.burn');

    // --- 3. 最も汎用的な {regret} だけのルートを最後に ---
    Route::get('/regrets/{regret}', [RegretController::class, 'show'])->name('regrets.show');

    // --- 4. 更新・削除 ---
    Route::put('/regrets/{regret}', [RegretController::class, 'update'])->name('regrets.update');
    Route::delete('/regrets/{regret}', [RegretController::class, 'destroy'])->name('regrets.destroy');
  
    // AI・演出・壁紙
    Route::post('/regrets/{regret}/burn', [RegretController::class, 'burn'])->name('regrets.burn');
    Route::post('/regrets/{regret}/moment', [RegretController::class, 'generate_moment'])->name('regrets.generate_moment');
    Route::get('/regrets/{regret}/wallpaper', [WallpaperController::class, 'edit'])->name('wallpaper.edit');
    Route::post('/regrets/{regret}/wallpaper/generate', [WallpaperController::class, 'generate'])->name('wallpaper.generate');

    // --- 管理者専用ルート ---
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/export', [AdminController::class, 'exportCsv'])->name('admin.export');

}); // ← 認証グループの閉じカッコ

// ==========================================
// 3. 認証システム（Login/Register等）
// ==========================================
require __DIR__.'/auth.php';