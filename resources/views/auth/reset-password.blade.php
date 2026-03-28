@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen bg-emerald-50 flex flex-col justify-center items-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-emerald-100">
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-emerald-800 font-serif tracking-widest">New Password</h2>
            <p class="text-xs text-emerald-600/70 mt-2">新しいパスワードを設定してください。</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-5">
                <label class="block text-sm font-medium text-emerald-700 mb-1.5 tracking-wider">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-emerald-100 bg-gray-50 text-gray-400 outline-none" readonly>
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-emerald-700 mb-1.5 tracking-wider">新しいパスワード</label>
                <input type="password" name="password" required autofocus
                    class="w-full px-4 py-3 rounded-xl border border-emerald-100 focus:ring-2 focus:ring-emerald-500 outline-none transition-all shadow-sm">
                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-8">
                <label class="block text-sm font-medium text-emerald-700 mb-1.5 tracking-wider">新しいパスワード（確認用）</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 rounded-xl border border-emerald-100 focus:ring-2 focus:ring-emerald-500 outline-none transition-all shadow-sm">
            </div>

            <button type="submit" 
                class="w-full bg-emerald-600 text-white font-bold py-3.5 rounded-xl hover:bg-emerald-700 active:transform active:scale-[0.98] transition-all shadow-lg tracking-widest text-sm">
                パスワードを更新する
            </button>
        </form>
    </div>
</div>