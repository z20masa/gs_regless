@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen bg-emerald-50 flex flex-col justify-center items-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-emerald-100">
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-emerald-800 font-serif tracking-widest">Regless</h2>
            <p class="text-xs text-emerald-600/70 mt-1">おかえりなさい。あなたの「もしも」を整理しましょう。</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-emerald-700 mb-1.5 tracking-wider">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-xl border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all shadow-sm">
            </div>

            <div class="mb-2">
                <label class="block text-sm font-medium text-emerald-700 mb-1.5 tracking-wider">パスワード</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 rounded-xl border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all shadow-sm">
            </div>

            <div class="flex justify-end mb-8">
                <a href="{{ url('forgot-password') }}" class="text-xs text-emerald-600 hover:text-emerald-800 transition-colors duration-300">
                    パスワードをお忘れですか？
                </a>
            </div>
            
            <button type="submit" 
                class="w-full bg-emerald-600 text-white font-bold py-3.5 rounded-xl hover:bg-emerald-700 transition-all shadow-lg mb-6 tracking-[0.2em] text-sm">
                ログインする
            </button>
        </form>

        <div class="flex flex-col space-y-4 text-center text-sm border-t border-emerald-50 pt-8 mt-2">
            <div class="text-slate-500">
                アカウントをお持ちでないですか？ 
                <a href="{{ route('register') }}" class="text-emerald-600 hover:underline font-bold ml-1">新規登録</a>
            </div>
            <a href="/" class="text-gray-400 hover:text-gray-600 transition-colors">
                ← トップページへ戻る
            </a>
        </div>
    </div>
</div>