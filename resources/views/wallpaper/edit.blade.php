<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('MY格言　〜あなただけの格言を壁紙に〜') }}
        </h2>
    </x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Shippori+Mincho:wght@400;700&family=Kaisei+Tokumin:wght@400;700&display=swap" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">1. メッセージを整える</h3>
                            <p class="text-sm text-gray-600 mb-4">あなたの後悔を元にAIが生成した、あなただけの格言です。プレビューを確認し改行などを入れてリズムを整えてみてください。</p>
                            <textarea id="quote-input" rows="4" 
                                class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm text-lg"
                                placeholder="ここに格言を入力...">{{ $maxim }}</textarea>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">2. フォントを選ぶ</h3>
                            <select id="font-family" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="'Shippori Mincho', serif">落ち着いた明朝体</option>
                                <option value="'Noto Sans JP', sans-serif">誠実なゴシック体</option>
                                <option value="'Kaisei Tokumin', serif">温かい手書き風（解星 特ミン）</option>
                            </select>
                        </div>

                        <div class="pt-6">
                            <button id="download-btn" class="w-full inline-flex justify-center items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="get_app" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                壁紙にダウンロード (PNG)
                            </button>
                            <p class="text-xs text-gray-400 mt-4 text-center">※画像はブラウザ内で生成され、サーバーには保存されません。</p>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('regrets.show', $regret) }}" class="text-sm text-gray-500 hover:underline">詳細に戻る</a>
                        </div>
                    </div>

                    <div class="flex flex-col items-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">プレビュー</h3>
                        
                        <div id="wallpaper-target" 
                             class="relative bg-white shadow-2xl overflow-hidden border border-gray-200"
                             style="width: 320px; height: 568px; font-family: 'Shippori Mincho', serif;">
                            
                            <div class="absolute inset-0 bg-gradient-to-b from-emerald-50 to-white opacity-40"></div>
                            
                            <div id="preview-text" class="absolute inset-0 flex items-center justify-center p-10 text-center leading-relaxed text-gray-800 text-2xl whitespace-pre-wrap">
                                {{ $maxim }}
                            </div>

                            <div class="absolute bottom-6 w-full text-center text-gray-300 text-xs tracking-widest uppercase">
                                Re_Reg Project
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('quote-input');
            const fontSelect = document.getElementById('font-family');
            const previewText = document.getElementById('preview-text');
            const wallpaperTarget = document.getElementById('wallpaper-target');
            const downloadBtn = document.getElementById('download-btn');

            // リアルタイム反映：テキスト
            input.addEventListener('input', () => {
                previewText.textContent = input.value;
            });

            // リアルタイム反映：フォント
            fontSelect.addEventListener('change', () => {
                wallpaperTarget.style.fontFamily = fontSelect.value;
            });

            // ダウンロード処理
            downloadBtn.addEventListener('click', () => {
                // ダウンロードボタンを一時的に非表示にするなどの処理は不要（targetの外なので）
                html2canvas(wallpaperTarget, {
                    scale: 3, // 高画質化（スマホのRetinaディスプレイ対応）
                    backgroundColor: "#ffffff"
                }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'my-maxim-wallpaper.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });
        });
    </script>
</x-app-layout>