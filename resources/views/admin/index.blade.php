<x-app-layout>
    <div class="py-10 bg-gray-900 min-h-screen text-white">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-3xl font-bold mb-8 text-blue-400">管理者ダッシュボード</h1>

            {{-- 統計・CSVエクスポートエリア --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div class="p-4 bg-gray-800 border border-yellow-500 rounded-lg flex-1 min-w-[300px]">
                    <p class="text-xl">
                        システム全体の投稿数: <span class="text-blue-400 font-bold">{{ $allRegrets->count() }}</span> 件
                    </p>
                    <p class="text-sm text-gray-400">
                        (デバッグ: 最新IDは {{ $allRegrets->first()?->id ?? 'なし' }} です)
                    </p>
                </div>
                
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.export') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        全データをCSVで保存
                    </a>
                </div>
            </div>

            {{-- 性別・カテゴリー統計チャート --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                @foreach($genderAverages as $gender => $data)
                    <div class="bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-700">
                        <h3 class="text-center font-bold mb-4">{{ $gender }}の傾向</h3>
                        <div class="h-64">
                            <canvas id="gender-{{ $loop->index }}"></canvas>
                        </div>
                    </div>
                @endforeach
            </div>

            <h2 class="text-xl font-bold mb-6 border-l-4 border-blue-500 pl-4">ジャンル別・全体統計</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @foreach($categoryAverages as $cat => $data)
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                        <h4 class="text-center text-sm font-bold mb-2">{{ $cat }}</h4>
                        <div class="h-48">
                            <canvas id="cat-{{ $loop->index }}"></canvas>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 全投稿ログテーブル --}}
            <h2 class="text-xl font-bold mb-6 border-l-4 border-green-500 pl-4">全投稿ログ</h2>
            <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-700 text-gray-300">
                            <tr>
                                <th class="p-4 whitespace-nowrap">日時</th>
                                <th class="p-4 whitespace-nowrap">ユーザー (ニックネーム/性別/年代)</th>
                                <th class="p-4 whitespace-nowrap text-center">ジャンル</th>
                                <th class="p-4 whitespace-nowrap">タイトル</th>
                                {{-- ★ 追加：内容カラム --}}
                                <th class="p-4">内容</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($allRegrets as $regret)
                                <tr class="hover:bg-gray-750 transition">
                                    <td class="p-4 text-gray-400 whitespace-nowrap text-xs">
                                        {{ $regret->created_at->format('m/d H:i') }}
                                    </td>
                                    
                                    <td class="p-4">
                                        <a href="{{ route('regrets.show', $regret) }}" class="group block">
                                            <span class="font-bold group-hover:text-blue-400 transition">
                                                {{ $regret->user->nickname ?? '匿名' }}
                                            </span> 
                                            <span class="text-[10px] text-gray-500 block">
                                                ({{ $regret->user->gender ?? '-' }}/{{ $regret->user->age_group ?? '-' }})
                                            </span>
                                        </a>
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        <span class="px-2 py-1 bg-gray-900 rounded-md text-blue-300 text-xs border border-gray-600">
                                            {{ $regret->category }}
                                        </span>
                                    </td>

                                    <td class="p-4">
                                        <a href="{{ route('regrets.show', $regret) }}" class="text-blue-400 hover:text-blue-300 hover:underline transition font-medium">
                                            {{ $regret->title }}
                                        </a>
                                    </td>

                                    {{-- ★ 追加：内容セル（省略表示付き） --}}
                                    <td class="p-4 text-gray-400 min-w-[200px] leading-relaxed">
                                        <span title="{{ $regret->content }}">
                                            {{ \Illuminate\Support\Str::limit($regret->content, 40, '...') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js スクリプト --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = ['社会', '実存', '認知', '感情', '行動', '時間'];
            const cfg = { 
                scales: { r: { min: 0, max: 5, ticks: { display: false }, pointLabels: { color: '#9ca3af' } } },
                plugins: { legend: { display: false } }
            };

            @foreach($genderAverages as $gender => $data)
                new Chart(document.getElementById('gender-{{ $loop->index }}'), {
                    type: 'radar',
                    data: { labels, datasets: [{ data: @json($data), backgroundColor: 'rgba(59, 130, 246, 0.2)', borderColor: '#3b82f6' }] },
                    options: cfg
                });
            @endforeach

            @foreach($categoryAverages as $cat => $data)
                new Chart(document.getElementById('cat-{{ $loop->index }}'), {
                    type: 'radar',
                    data: { labels, datasets: [{ data: @json($data), backgroundColor: 'rgba(16, 185, 129, 0.2)', borderColor: '#10b981' }] },
                    options: cfg
                });
            @endforeach
        });
    </script>
</x-app-layout>