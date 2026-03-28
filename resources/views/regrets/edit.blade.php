<x-app-layout>
    <div class="py-12 bg-[#f8fafc] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- ヘッダー --}}
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <a href="{{ route('regrets.index') }}" class="text-sm text-green-600 hover:text-green-700 flex items-center gap-1 mb-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        一覧に戻る
                    </a>
                    <h1 class="text-2xl font-bold text-gray-800">エピソードを整える</h1>
                </div>
                <span class="text-xs text-gray-400 font-mono">ID: #{{ $regret->id }}</span>
            </div>

            {{-- エラー表示 --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm">
                    <p class="font-bold mb-1">入力内容を確認してください：</p>
                    <ul class="text-xs list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- メインフォーム --}}
            <form action="{{ route('regrets.update', $regret) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-10">
                    
                    {{-- 基本情報 --}}
                    <div class="space-y-6 mb-10">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">タイトル</label>
                            <input type="text" name="title" value="{{ old('title', $regret->title) }}" 
                                   class="w-full border-gray-200 rounded-2xl p-4 focus:ring-green-500 focus:border-green-500 shadow-sm transition-all" placeholder="タイトルを入力">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">ジャンル</label>
                            <select name="category" class="w-full border-gray-200 rounded-2xl p-4 bg-white focus:ring-green-500 focus:border-green-500 shadow-sm transition-all text-gray-700">
                                @foreach(['学業・学び', '進学', '就職', 'キャリア', '恋愛', '人間関係', '趣味', 'スポーツ', '健康'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $regret->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">エピソードの詳細</label>
                            <textarea name="content" rows="5" class="w-full border-gray-200 rounded-2xl p-4 focus:ring-green-500 focus:border-green-500 shadow-sm transition-all text-gray-700" placeholder="今の素直な気持ちを書き留めてください...">{{ old('content', $regret->content) }}</textarea>
                        </div>
                    </div>

                    {{-- 6因子スコアエリア --}}
                    <div class="bg-[#f0f4f0] p-8 rounded-[2rem] mb-10">
                        <h2 class="text-sm font-black text-green-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                            Analysis Factors (6因子)
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                            @foreach(['cognitive' => '認知的', 'emotional' => '感情的', 'temporal' => '時間的', 'social' => '社会的', 'existential' => '実存的'] as $key => $label)
                            <div>
                                <label class="block text-[11px] font-bold text-green-700 mb-1.5 ml-1">{{ $label }} (1-5)</label>
                                <input type="number" name="{{ $key }}" min="1" max="5" value="{{ old($key, $regret->$key) }}" 
                                       class="w-full border-transparent bg-white rounded-xl p-2.5 text-center font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all">
                            </div>
                            @endforeach
                            <div>
                                <label class="block text-[11px] font-bold text-green-700 mb-1.5 ml-1">後悔のタイプ</label>
                                <select name="behavioral" class="w-full border-transparent bg-white rounded-xl p-2.5 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-green-400 transition-all">
                                    <option value="1" {{ old('behavioral', $regret->behavioral) == 1 ? 'selected' : '' }}>やった後悔</option>
                                    <option value="0" {{ old('behavioral', $regret->behavioral) == 0 ? 'selected' : '' }}>やらなかった後悔</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- リサイクルメッセージ --}}
                    <div class="p-8 bg-gradient-to-br from-[#fff9f2] to-[#fff4e6] rounded-[2.5rem] border-2 border-dashed border-[#ffd8a8] relative shadow-inner mb-4">
                        <div class="absolute -top-4 left-8 bg-[#ff922b] text-white px-6 py-1 rounded-full text-[10px] font-black tracking-tighter">RECYCLE MESSAGE</div>
                        <label class="block mb-3 text-lg font-black text-[#862e13] italic flex items-center gap-2">
                            <span>🌟</span> どこかの誰かと自分へのメッセージ
                        </label>
                        <textarea name="message_to_others" rows="3" class="w-full border-transparent rounded-2xl focus:ring-4 focus:ring-[#ffe8cc] text-gray-700 p-4 shadow-sm" placeholder="エールやアドバイスを。">{{ old('message_to_others', $regret->message_to_others) }}</textarea>
                    </div>
                </div>

                {{-- 送信ボタン --}}
                <div class="flex flex-col gap-4">
                    <button type="submit" class="w-full bg-green-600 text-white font-black py-5 rounded-[2rem] hover:bg-green-700 shadow-lg hover:shadow-green-200 transition-all active:scale-[0.98] text-lg">
                        変更を保存して一覧に戻る
                    </button>
                    <a href="{{ route('regrets.index') }}" class="text-center text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                        今は変更せずに戻る
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>