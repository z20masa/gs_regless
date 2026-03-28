<x-app-layout>
    {{-- ヘッダータイトル（親レイアウトのスロットを使用する場合） --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-emerald-800 leading-tight flex items-center gap-3">
                <span class="text-3xl">🌍</span> みんなの後悔一覧
            </h2>
            <a href="{{ route('regrets.index') }}" class="px-6 py-2 bg-emerald-600 text-white rounded-full font-bold text-xs shadow-md hover:bg-emerald-700 transition">
                My Page
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-emerald-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. 絞り込みフォーム --}}
            <div class="mb-10 mx-4 sm:mx-0 bg-white p-8 rounded-[2.5rem] border border-emerald-100 shadow-sm">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-end gap-6">
                    <div class="flex flex-col min-w-[150px]">
                        <span class="text-[10px] font-black text-emerald-600 mb-2 ml-1 uppercase tracking-widest">Age</span>
                        <select name="age_group" class="rounded-2xl border-emerald-100 bg-emerald-50/30 text-sm focus:ring-emerald-500 py-3">
                            <option value="">全ての年代</option>
                            @foreach(['10代', '20代', '30代', '40代', '50代', '60代以上'] as $age)
                                <option value="{{ $age }}" {{ request('age_group') == $age ? 'selected' : '' }}>{{ $age }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col min-w-[150px]">
                        <span class="text-[10px] font-black text-emerald-600 mb-2 ml-1 uppercase tracking-widest">Gender</span>
                        <select name="gender" class="rounded-2xl border-emerald-100 bg-emerald-50/30 text-sm focus:ring-emerald-500 py-3">
                            <option value="">全ての性別</option>
                            @foreach(['男性', '女性', '回答しない'] as $gender)
                                <option value="{{ $gender }}" {{ request('gender') == $gender ? 'selected' : '' }}>{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col min-w-[200px]">
                        <span class="text-[10px] font-black text-emerald-600 mb-2 ml-1 uppercase tracking-widest">Category</span>
                        <select name="category" class="rounded-2xl border-emerald-100 bg-emerald-50/30 text-sm focus:ring-emerald-500 py-3">
                            <option value="">全てのカテゴリー</option>
                            @foreach(['恋愛', '進学', '就職・転職', '学業・学び', 'スポーツ', 'お金', '健康', '趣味', '出会い・別れ'] as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-emerald-600 text-white px-10 py-3.5 rounded-2xl font-black hover:bg-emerald-700 transition shadow-md text-sm">
                        🔍 絞り込む
                    </button>
                </form>
            </div>

            {{-- 2. 投稿一覧カード --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-4 sm:px-0">
                @forelse($allLatestRegrets as $regret)
                    <div class="bg-white rounded-[2.5rem] border border-emerald-50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">
                        <div class="p-8 pb-0">
                            <div class="flex justify-between items-start mb-6">
                                <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase tracking-widest">
                                    {{ $regret->category }}
                                </span>
                                <div class="flex gap-2">
                                    <span class="text-[10px] font-bold px-2 py-1 bg-gray-50 text-gray-500 rounded-md border border-gray-100">{{ $regret->user->age_group ?? '??' }}</span>
                                    <span class="text-[10px] font-bold px-2 py-1 bg-gray-50 text-gray-500 rounded-md border border-gray-100">{{ $regret->user->gender ?? '??' }}</span>
                                </div>
                            </div>
                            <h3 class="text-xl font-black text-gray-800 mb-4 leading-tight">
                                {{ $regret->title }}
                            </h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-3 italic">
                                {{ $regret->content }}
                            </p>
                        </div>

                        <div class="mt-auto px-8 py-6 bg-emerald-50/30 border-t border-emerald-50 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-emerald-600 border border-emerald-200 text-sm font-bold shadow-sm">
                                    {{ mb_substr($regret->user->nickname ?? $regret->user->name ?? '匿', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-gray-800">{{ $regret->user->nickname ?? $regret->user->name ?? '匿名' }}</span>
                                    <span class="text-[9px] text-gray-400 font-bold tracking-tighter">{{ $regret->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <a href="{{ route('regrets.show', $regret) }}" class="inline-flex items-center px-5 py-2.5 bg-white text-emerald-600 border-2 border-emerald-600 rounded-xl text-xs font-black hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                詳細をみる →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-gray-400 font-bold italic">まだ投稿がありません。</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>