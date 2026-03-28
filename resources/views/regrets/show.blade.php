<x-app-layout>
    {{-- 成功・エラーメッセージ --}}
    <div class="max-w-7xl mx-auto px-4 mt-4">
        @if(session('status'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm">{{ session('status') }}</div>
        @endif
    </div>

    <div class="min-h-screen bg-[#f8faf9] py-12" 
         x-data="{ 
            isBurning: false, 
            isPurified: {{ $regret->is_burned ? 'true' : 'false' }}, 
            isOwner: {{ $isOwner ? 'true' : 'false' }},
            startBurn() {
                if(this.isBurning) return;
                if(!confirm('この後悔をお焚き上げし、前を向くための手紙を受け取りますか？')) return;
                this.isBurning = true;
                setTimeout(() => {
                    document.getElementById('burn-form').submit();
                }, 3500);
            }
         }">
        
        {{-- ★ 本人実行時のみ：オーバーレイ --}}
        @if($isOwner && !$regret->is_burned)
        <div x-show="isBurning" 
             style="display: none;"
             x-transition:enter="transition opacity duration-1000"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black">
            <video autoplay muted playsinline class="absolute inset-0 w-full h-full object-cover opacity-80">
                <source src="{{ asset('movies/burn_effect.mp4') }}" type="video/mp4">
            </video>
            <div class="relative z-10 text-center">
                <div class="mb-6 text-6xl animate-pulse">🔥</div>
                <h2 class="text-3xl md:text-5xl font-black text-white tracking-[0.6em] drop-shadow-2xl">お焚き上げ中</h2>
                <p class="text-orange-200 mt-6 font-serif italic text-lg opacity-80 animate-pulse">あなたの後悔は、今、新しい光に変わります</p>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black opacity-60"></div>
        </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- 戻るボタン --}}
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-bold text-emerald-700 hover:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    一覧へ戻る
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                {{-- 左側：メインエリア --}}
                <div class="w-full lg:w-2/3 space-y-8">
                    
                    {{-- 本文カード --}}
                    <div class="bg-white p-8 sm:p-12 rounded-[2.5rem] border border-emerald-100 shadow-sm relative overflow-hidden transition-all duration-[2000ms]"
                         :class="isBurning ? 'opacity-20 scale-95 blur-xl' : (isPurified && isOwner ? 'border-emerald-500 shadow-emerald-100 shadow-xl' : '')">
                        
                        <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-6">
                            <span class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">RECORDED {{ $regret->created_at->format('Y.m.d') }}</span>
                        </div>

                        <h1 class="text-3xl font-black mb-8 text-gray-800 leading-tight">{{ $regret->title }}</h1>
                        <div class="text-gray-600 text-lg leading-relaxed whitespace-pre-wrap mb-10">{{ $regret->content }}</div>
                        
                        <div class="flex items-center gap-3 pt-6 border-t border-gray-50">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold border border-emerald-100 shadow-sm">
                                {{ mb_substr($regret->user->nickname ?? '匿', 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-sm">{{ $regret->user->nickname ?? '匿名ユーザー' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ★【本人限定】お焚き上げ済みの場合のみ表示される手紙エリア --}}
                    {{-- if_letter を ai_letter に修正 --}}
                    @if($isOwner && $regret->is_burned && $regret->ai_letter)
                    <div x-data="{ 
                            fullText: {{ json_encode($regret->ai_letter) }}, 
                            displayText: '', 
                            index: 0,
                            startTyping() {
                                let timer = setInterval(() => {
                                    if (this.index < this.fullText.length) {
                                        this.displayText += this.fullText[this.index];
                                        this.index++;
                                    } else { clearInterval(timer); }
                                }, 30);
                            }
                         }"
                         x-init="setTimeout(() => startTyping(), 800)"
                         class="p-8 sm:p-12 bg-gradient-to-br from-emerald-900 to-teal-950 rounded-[2.5rem] shadow-2xl relative overflow-hidden border-4 border-emerald-400/30">
                        
                        <div class="absolute -right-4 -bottom-4 text-9xl opacity-10 pointer-events-none">✉️</div>
                        <h3 class="text-xs font-black text-emerald-400 uppercase tracking-[0.3em] mb-8 flex items-center">
                            <span class="w-12 h-[1px] bg-emerald-500/50 mr-4"></span>IF Letter for You
                        </h3>
                        
                        <div class="text-emerald-50 text-xl leading-relaxed italic whitespace-pre-wrap font-serif min-h-[100px]" x-text="displayText"></div>
                        
                        {{-- MY格言 --}}
                        <div class="mt-12 p-8 bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 text-center">
                            <p class="text-[9px] font-black uppercase tracking-[0.4em] mb-4 text-emerald-400">Your New Maxim</p>
                            <p class="text-2xl sm:text-3xl font-serif italic text-white leading-snug">" {{ $regret->my_maxim }} "</p>
                        </div>

                        {{-- スマホ壁紙ボタン --}}
                        <div class="mt-10 flex justify-center">
                            <a href="{{ route('wallpaper.edit', $regret) }}" 
                               class="px-8 py-4 bg-emerald-500 text-white rounded-full font-black shadow-lg hover:bg-emerald-400 hover:scale-105 transition-all flex items-center gap-3 text-sm">
                                <span>📱</span> MY格言・スマホ壁紙を生成する
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 右側：サイドバーエリア --}}
                <div class="w-full lg:w-1/3 space-y-6 lg:sticky lg:top-8">
                    <div class="bg-white p-8 rounded-[2.5rem] border border-emerald-100 shadow-xl">
                        <div class="text-center mb-8 border-b border-gray-50 pb-8">
                            <div class="text-6xl mb-4">
                                @php
                                    $icons = ['恋愛'=>'💖','進学'=>'🎓','就職・転職'=>'💼','学業・学び'=>'📚','スポーツ'=>'⚽','お金'=>'💰','健康'=>'🌿','趣味'=>'🎨','出会い・別れ'=>'🤝'];
                                    echo $icons[$regret->category] ?? '🍃';
                                @endphp
                            </div>
                            <p class="text-2xl font-black text-emerald-800 tracking-wider">{{ $regret->category }}</p>
                        </div>
                        
                        <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">Analysis Score</h3>
                        <div class="space-y-5">
                            @foreach([
                                'どれくらい頭の中で考え続けてしまいますか？(認知的要旨)' => $regret->cognitive,
                                '今もそのことで、心が痛みますか？（感情的要素）' => $regret->emotional,
                                'どれくらい長く心に残っていますか？（時間的要素）' => $regret->temporal,
                                'その後悔は他人や周囲が関係していますか？（社会的要素' => $regret->social, 
                                'その後悔は今の自分の考え方や生き方に影響していますか？（実存的要素）' => $regret->existential
                            ] as $l => $v)
                                <div>
                                    <div class="flex justify-between text-[10px] mb-1.5 font-bold">
                                        <span class="text-gray-400 uppercase">{{ $l }}</span>
                                        <span class="text-emerald-700">{{ $v }}/5</span>
                                    </div>
                                    <div class="w-full bg-emerald-50 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-emerald-400 h-full transition-all duration-1000" style="width: {{ ($v/5)*100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 本人限定のアクションボタン --}}
                    @if($isOwner)
                        <div class="space-y-4">
                            @if(!$regret->is_burned)
                                <form action="{{ route('regrets.burn', $regret) }}" method="POST" id="burn-form">
                                    @csrf
                                    <button type="button" @click="startBurn()" 
                                        :disabled="isBurning"
                                        class="w-full bg-gradient-to-r from-orange-500 to-amber-500 py-5 rounded-3xl text-lg font-black text-white shadow-xl hover:shadow-orange-200 hover:scale-[1.02] transition-all flex items-center justify-center gap-3 disabled:opacity-50">
                                        <span x-show="!isBurning">🔥 お焚き上げする</span>
                                        <span x-show="isBurning" style="display: none;">お焚き上げ中</span>
                                    </button>
                                </form>
                            @else
                                <div class="w-full py-5 bg-emerald-50 text-emerald-600 rounded-3xl text-center font-black border-2 border-emerald-100 flex items-center justify-center gap-2">
                                    <span>✨</span> 浄化済み
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>