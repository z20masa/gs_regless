<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-8">
    {{-- データの取得 --}}
    @php
        // コントローラーから届いた $allLatestRegrets から、
        // メッセージがあるものだけをランダムに6件抽出する
        $randomMessages = $allLatestRegrets->whereNotNull('message_to_others')
            ->where('message_to_others', '!=', '')
            ->shuffle()
            ->take(6);
            
        // 下のテーブル用にはそのまま全部使う
        $displayRegrets = $allLatestRegrets;
    @endphp

    <div class="space-y-20">
        
        {{-- セクション1：Reglet Cards --}}
        <div>
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-black text-emerald-800 flex items-center gap-3">
                    <span class="text-3xl">🌿</span> Reglet Cards
                    <span class="text-xs font-bold text-emerald-600/70 ml-2 hidden sm:inline tracking-wider">あなたの後悔は、誰かの光になる</span>
                </h2>
                {{-- <button wire:click="$refresh" class="text-[10px] font-black text-emerald-500 hover:text-emerald-400 transition-colors uppercase tracking-[0.2em] border-b-2 border-emerald-100 pb-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Shuffle
                </button> --}}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($randomMessages as $msg)
                    @if($msg)
                        <div class="bg-green-50 p-8 rounded-3xl border border-green-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group hover:-translate-y-1 flex flex-col justify-between min-h-[160px]">
                            <div class="absolute -top-4 -left-2 text-8xl text-green-200/50 font-serif group-hover:text-green-200 transition-colors pointer-events-none leading-none">“</div>
                            
                            <div class="relative z-10 flex flex-col h-full justify-between">
                                    <p class="text-green-900 text-sm leading-relaxed font-medium mb-6 mt-2 flex-grow">
                                        {{-- $msg が存在し、message_to_others を持っているか徹底的にチェック --}}
                                        @if(isset($msg) && is_object($msg))
                                            {{ \Str::limit($msg->message_to_others ?? 'メッセージなし', 100) }}
                                        @else
                                            データが正しく読み込めませんでした
                                        @endif
                                    </p>    
                                <div class="flex items-center justify-between border-t border-green-200/60 pt-4 mt-auto">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-green-700">
                                            {{ $msg->category ?? 'カテゴリなし' }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-green-600/50 font-mono font-bold">#{{ $msg->id }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-span-full py-16 text-center bg-green-50 rounded-3xl border-2 border-dashed border-green-200">
                        <p class="text-green-600/70 text-sm font-medium">まだ共有されたメッセージがありません。</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- セクション2：みんなの「後悔」エピソード --}}
        <div class="bg-white shadow-xl shadow-emerald-100/40 rounded-3xl overflow-hidden border border-emerald-100">
            <div class="p-8 border-b border-emerald-100 bg-gradient-to-r from-emerald-50/50 to-green-50/50">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-black text-emerald-800 tracking-tight flex items-center gap-2">
                        <span>📖</span> みんなの「後悔」エピソード
                    </h3>
                    <div class="text-[10px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-100 px-3 py-1 rounded-full">Recent Feed</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-emerald-50">
                    <tbody class="bg-white divide-y divide-emerald-50">
                        @php
                            $displayRegrets = \App\Models\Regret::latest()->get();
                        @endphp
                        @foreach($displayRegrets as $regret)
                            @if($regret)
                                <tr class="hover:bg-emerald-50/50 transition-colors duration-200 cursor-pointer group" onclick="window.location='{{ route('regrets.show', $regret) }}'">
                                    <td class="px-8 py-5 w-[25%]">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-black text-sm border border-emerald-200 shadow-sm">
                                                {{ mb_substr($regret->nickname ?? '匿', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-emerald-900 text-sm">{{ $regret->nickname ?? '匿名' }}</div>
                                                <div class="text-[10px] text-emerald-500 font-medium">{{ $regret->age_group ?? '-' }} / {{ $regret->gender ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 w-[20%]">
                                        <span class="px-3 py-1 bg-white text-emerald-600 rounded-full text-[10px] font-black border border-emerald-200 shadow-sm uppercase tracking-tighter">
                                            {{ $regret->category ?? 'なし' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 font-bold text-emerald-800 text-sm group-hover:text-emerald-600 transition-colors">
                                        {{-- ★ここを確実に \Str::limit に修正 --}}
                                        {{ $regret->title ?? \Str::limit($regret->content, 20) }}
                                    </td>
                                    <td class="px-8 py-5 text-right w-[10%]">
                                        <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center ml-auto group-hover:bg-emerald-200 transition-colors">
                                            <svg class="w-4 h-4 text-emerald-400 group-hover:text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>