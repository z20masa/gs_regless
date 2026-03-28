@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen bg-emerald-50 flex flex-col justify-center items-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-emerald-100">
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-emerald-800 font-serif tracking-widest">Password Reset</h2>
            <p class="text-xs text-emerald-600/70 mt-2 leading-relaxed">
                パスワードをお忘れですか？<br>
                登録したメールアドレスを入力してください。<br>
                再設定用のリンクをお送りします。
            </p>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-8">
                <label class="block text-sm font-medium text-emerald-700 mb-2 tracking-wider">ご登録メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-xl border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all shadow-sm placeholder-emerald-200"
                    placeholder="example@mail.com">
                @error('email') 
                    <p class="text-red-400 text-xs mt-1.5 ml-1">{{ $message }}</p> 
                @enderror
            </div>

            <button type="submit" 
                class="w-full bg-emerald-600 text-white font-bold py-3.5 rounded-xl hover:bg-emerald-700 active:transform active:scale-[0.98] transition-all shadow-lg mb-6 tracking-widest text-sm">
                再設定メールを送信する
            </button>
        </form>

        <div class="text-center pt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-emerald-600 transition-colors">
                ← ログイン画面に戻る
            </a>
        </div>
    </div>
</div>