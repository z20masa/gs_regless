@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Alpine.js（スライダーの数値表示に必要） --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="min-h-screen bg-emerald-50 py-12 px-4">
    <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-3xl overflow-hidden border border-emerald-100">
        
        {{-- ヘッダーエリア --}}
        <div class="bg-emerald-600 p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">後悔のエピソードを記録する</h1>
                <p class="text-emerald-100 text-sm mt-1 italic">ここに置いていくことで、新しい一歩が始まります。</p>
            </div>
            <div class="text-right text-sm font-medium bg-emerald-700/50 px-4 py-2 rounded-full">
                {{ Auth::user()->nickname ?? Auth::user()->name }} さんとして投稿
            </div>
        </div>

        @if ($errors->any())
            <div class="m-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded-r shadow-sm">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('regrets.store') }}" method="POST" class="p-8 space-y-10" x-data="{ cognitive:3, emotional:3, temporal:3, social:3, existential:3 }">
            @csrf

            {{-- 1. 基本情報 --}}
            <div class="space-y-6">
                <div class="flex items-center space-x-2 border-l-4 border-emerald-500 pl-3">
                    <h2 class="font-bold text-emerald-900">1. 後悔の内容</h2>
                </div>
                <div>
                    <label class="block font-bold text-emerald-800 mb-2 text-sm">タイトル</label>
                    <input type="text" 
                        name="title" 
                        value="{{ old('title') }}" 
                        class="w-full border-emerald-100 rounded-xl p-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-emerald-50/30 @error('title') border-red-400 ring-1 ring-red-400 @enderror" 
                        placeholder="例：あの時の進路選択" 
                        required>
                    
                    {{-- ★ エラーメッセージの表示場所 --}}
                    @error('title')
                        <p class="text-xs text-red-500 mt-1 font-bold ml-1">
                            <span class="inline-block mr-1">⚠️</span>{{ $message }}
                        </p>
                    @enderror
                </div>
                <input type="hidden" name="nickname" value="{{ Auth::user()->nickname ?? Auth::user()->name }}">

                <div>
                    <label class="block font-bold text-emerald-800 mb-2 text-sm">ジャンル</label>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 mt-2">
                        @foreach(['恋愛', '進学', '就職・転職', '学業・学び', 'スポーツ', 'お金', '健康', '趣味', '出会い・別れ', 'その他'] as $cat)
                            <label class="flex items-center justify-center p-2 border border-emerald-100 rounded-lg hover:bg-emerald-50 cursor-pointer text-xs transition-all has-[:checked]:bg-emerald-100 has-[:checked]:border-emerald-500">
                                <input type="radio" name="category" value="{{ $cat }}" class="hidden peer" required> 
                                <span class="text-emerald-700">{{ $cat }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-emerald-800 mb-2 text-sm">エピソードの詳細</label>
                    <textarea name="content" class="w-full border-emerald-100 rounded-xl p-4 h-40 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-emerald-50/30 resize-none" placeholder="あなたの後悔について自由にお書きください" required></textarea>
                </div>
            </div>

            {{-- 2. 6因子の分析エリア --}}
            <div class="p-8 bg-emerald-50/50 rounded-[2rem] border border-emerald-100 shadow-inner">
                <h3 class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-8 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                    2. 心の状態を分析（1: 全くない 〜 5: 非常に強い）
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                    @foreach(['cognitive'=>'どれくらい頭の中で考え続けてしまいますか？
(認知的)', 'emotional'=>'今もそのことで、心が痛みますか？
（感情的）', 'temporal'=>'どれくらい長く心に残っていますか？
（時間的）', 'social'=>'その後悔は他人や周囲が関係していますか？
（社会的）', 'existential'=>'その後悔は今の自分の考え方や生き方に影響していますか？（実存的）'] as $key => $label)
                        <div>
                            <div class="flex justify-between mb-3">
                                <label class="text-sm font-bold text-emerald-800">{{ $label }}</label>
                                <span class="text-emerald-600 font-mono font-bold text-sm bg-white px-2 rounded border border-emerald-100 shadow-sm" x-text="{{ $key }}">3</span>
                            </div>
                            <input type="range" name="{{ $key }}" min="1" max="5" step="1" x-model="{{ $key }}"
                                class="w-full h-2 bg-emerald-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                        </div>
                    @endforeach

                    <div>
                        <label class="text-sm font-bold text-emerald-800 mb-3 block">その後悔は？</label>
                        <select name="behavioral" class="w-full rounded-xl border-emerald-100 bg-white text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="1">「行動したから」のものですか？ (Action)</option>
                            <option value="0">「行動しなかった（できなかった）」ものですか？ (Inaction)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- 3. どこかの誰かへのメッセージ --}}
            <div class="p-8 bg-gradient-to-br from-teal-50 to-emerald-50 rounded-[2.5rem] border-2 border-dashed border-emerald-200 relative shadow-sm mt-10">
                <div class="absolute -top-4 left-8 bg-emerald-500 text-white px-6 py-1 rounded-full text-xs font-black shadow-md uppercase tracking-wider">
                    Recycle Message
                </div>
                
                <label class="block mb-3 text-lg font-black text-emerald-900">
                    🌟 どこかの誰かへのメッセージ
                </label>
                
                <p class="text-xs text-emerald-600 mb-4 leading-relaxed font-bold">
                    あなたの後悔は、誰かの「これから」を助ける光になります。<br>
                    今、同じようなことで悩んでいる誰かに、一言メッセージを。
                </p>

                <textarea name="message_to_others" 
                        class="w-full rounded-2xl border-none focus:ring-4 focus:ring-emerald-200 min-h-[120px] text-gray-700 placeholder-emerald-200 shadow-inner bg-white/80" 
                        placeholder="例：無理に笑わなくて大丈夫。いつか必ず、これで良かったと思える日が来るから。"></textarea>
            </div>

            {{-- 登録ボタン --}}
            <div class="pt-6 text-center">
                <button type="submit" class="w-full md:w-2/3 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-5 rounded-3xl text-xl shadow-xl shadow-emerald-200 transition-all transform hover:scale-[1.02] active:scale-95">
                    この後悔を登録する
                </button>
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold underline transition-all">みんなの後悔一覧にもどる</a>
                </div>
            </div>
        </form>
    </div>
</div>