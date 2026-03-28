@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen bg-emerald-50 flex flex-col justify-center items-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-emerald-800 mb-6 text-center">新規会員登録</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-emerald-700 mb-1">ニックネーム</label>
                <input type="text" name="nickname" value="{{ old('nickname') }}" required autofocus
                    placeholder="アプリ内で表示される名前です"
                    class="w-full px-4 py-3 rounded-lg border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                @error('nickname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-emerald-700 mb-1">年代</label>
                <select id="age_group" name="age_group" required
                    class="w-full px-4 py-3 rounded-lg border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all bg-white">
                    <option value="">選択してください</option>
                    @foreach(['10代', '20代', '30代', '40代', '50代', '60代以上'] as $age)
                        <option value="{{ $age }}" {{ old('age_group') == $age ? 'selected' : '' }}>{{ $age }}</option>
                    @endforeach
                </select>
                @error('age_group') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-emerald-700 mb-1">性別</label>
                <select id="gender" name="gender" required
                    class="w-full px-4 py-3 rounded-lg border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all bg-white">
                    <option value="">選択してください</option>
                    @foreach(['男性', '女性', 'その他', '回答しない'] as $gender)
                        <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>{{ $gender }}</option>
                    @endforeach
                </select>
                @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-emerald-700 mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 rounded-lg border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-emerald-700 mb-1">パスワード</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 rounded-lg border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-emerald-700 mb-1">パスワード（確認用）</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 rounded-lg border border-emerald-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
            </div>

            <div class="mb-6 px-1">
                <p class="text-[12px] text-emerald-700 leading-relaxed text-center">
                    登録ボタンを押すことで、
                    <a href="{{ route('terms') }}" class="underline font-bold hover:text-emerald-900" target="_blank">利用規約</a>
                    および
                    <a href="{{ route('privacy') }}" class="underline font-bold hover:text-emerald-900" target="_blank">プライバシーポリシー</a>
                    に同意したものとみなされます。
                </p>
            </div>

            
            <button type="submit" 
                class="w-full bg-emerald-600 text-white font-bold py-3 rounded-lg hover:bg-emerald-700 transition-colors shadow-lg mb-4">
                登録する
            </button>
        </form>

        <div class="text-center text-sm">
            <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">すでに登録済みの方はこちら</a>
        </div>
    </div>
</div>