<x-app-layout>
    <div class="py-10 bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- ヘッダーエリア --}}
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">My Page</h1>
                    <p class="text-sm text-gray-500 mt-1">過去の自分と向き合い、手放すための場所</p>
                </div>
                <a href="{{ route('regrets.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-all hover:shadow-md active:scale-95 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    新しく記録する
                </a>
            </div>

            {{-- メッセージ表示 --}}
            @if(session('success'))
                <div class="bg-white border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded shadow-sm flex items-center gap-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- グリッド一覧 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch w-full">
                @forelse($regrets as $regret)
                
                    {{-- カード本体：背景色を @if で直接制御 --}}
                    <div class="group relative rounded-3xl p-7 shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col justify-between border-t-8 h-full w-full 
                        {{ $regret->is_burned ? 'bg-[#f2f7f1] border-[#e8f0e7] border-t-[#a8c6a0]' : 'bg-white border-white border-t-green-500' }}">
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-5">
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-green-50 text-green-700 text-[11px] font-bold rounded-full border border-green-100">
                                        {{ $regret->category ?? '未分類' }}
                                    </span>
                                    @if($regret->is_burned)
                                        <span class="flex items-center gap-1 px-3 py-1 bg-white text-green-600 text-[11px] font-bold rounded-full border border-green-200 shadow-sm">
                                            <span>✨</span> 浄化済み
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-gray-400 font-mono pt-1">
                                    {{ $regret->created_at->format('Y.m.d') }}
                                </span>
                            </div>

                            <h2 class="text-xl font-bold mb-4 leading-tight {{ $regret->is_burned ? 'text-gray-600' : 'text-gray-800' }}">
                                {{-- ここを以下の通り「after:content-['']」を含む形に書き換え --}}
                                <a href="{{ route('regrets.show', $regret) }}" class="hover:text-green-600 transition-colors after:content-[''] after:absolute after:inset-0 after:z-10">
                                    {{ $regret->title }}
                                </a>
                            </h2>
                            
                            <p class="text-sm {{ $regret->is_burned ? 'text-gray-400' : 'text-gray-500' }} leading-[1.7] line-clamp-3 mb-8">
                                {{ $regret->content }}
                            </p>
                        </div>

                        {{-- ボタンエリア：ここを差し替え --}}
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 relative z-50 mt-auto">
                            {{-- 編集ボタン --}}
                            <a href="{{ route('regrets.edit', $regret) }}" class="text-xs font-bold text-gray-500 hover:text-green-600 transition-colors bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                                編集
                            </a>

                            {{-- 削除ボタン --}}
                            <form action="{{ route('regrets.destroy', $regret) }}" method="POST" onsubmit="return confirm('この記録を完全に消去しますか？');" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 transition-colors bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                                    削除
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center bg-white rounded-3xl border-2 border-dashed border-green-100">
                        <div class="text-4xl mb-4">🍃</div>
                        <p class="text-gray-400">記録がまだありません。</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- お焚き上げ演出オーバーレイ --}}
    <div id="burn-overlay" style="display: none; opacity: 0;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black transition-opacity duration-1000 pointer-events-none">
        <div id="fire-particles" class="absolute inset-0"></div>
        <div class="relative flex flex-col items-center justify-center">
            <div class="fire-container"><div class="fire-flame"></div><div class="fire-flame"></div><div class="fire-flame"></div><div class="fire-flame"></div></div>
            <p class="mt-12 text-[#ff4d00] text-6xl font-black italic">浄化中</p>
        </div>
    </div>

    <style>
        .fire-container { position: relative; width: 150px; height: 250px; filter: blur(2px) contrast(20); }
        .fire-flame { position: absolute; bottom: 0; left: 50%; width: 100px; height: 100px; transform: translateX(-50%); background: white; border-radius: 45% 45% 20% 20%; box-shadow: 0 0 20px 10px #ff4d00, 0 -10px 30px 20px #ff9100; animation: real-fire 1.5s infinite alternate ease-in-out; mix-blend-mode: screen; }
        @keyframes real-fire { 0% { transform: translateX(-50%) scale(1); } 100% { transform: translateX(-48%) scale(1.1, 1.3); } }
        .particle { position: absolute; background: #fff; border-radius: 50%; animation: particle-rise var(--duration) linear forwards; }
        @keyframes particle-rise { 0% { transform: translateY(100vh); opacity: 1; } 100% { transform: translateY(-10vh); opacity: 0; } }
    </style>

    <script>
        function confirmBurn(id) {
            if (!confirm('浄化しますか？')) return;
            const overlay = document.getElementById('burn-overlay');
            overlay.style.display = 'flex';
            requestAnimationFrame(() => overlay.style.opacity = '1');
            setTimeout(() => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/regrets/${id}/burn`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                document.body.appendChild(form);
                form.submit();
            }, 4000);
        }
    </script>
</x-app-layout>