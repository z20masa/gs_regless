<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * 登録画面を表示する
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * 登録処理を実行する
     */
public function store(Request $request): RedirectResponse
    {
        // 入力のチェック
        $request->validate([
            'nickname' => ['required', 'string', 'max:255'], // nicknameを必須に
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'age_group' => ['required', 'string'], // 追加
            'gender' => ['required', 'string'],    // 追加
        ]);

        // データベースにユーザーを保存
        $user = User::create([
            'name' => $request->nickname,    // nameカラムにはnicknameを代入して整合性を保つ
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'age_group' => $request->age_group, // 追加
            'gender' => $request->gender,       // 追加
        ]);

        // 登録イベントの発行
        event(new Registered($user));

        // ログインさせる
        Auth::login($user);

        // ダッシュボードへ移動
        return redirect(route('dashboard', absolute: false));
    }
}