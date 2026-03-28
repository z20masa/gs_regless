<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regless | あなたの「もしも」を、未来への手紙に変える</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@400;700&family=Noto+Sans+JP:wght@300;500&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Noto Sans JP', sans-serif; margin: 0; padding: 0; overflow-x: hidden; }
        .font-serif { font-family: 'Shippori Mincho', serif; }
        
        .bg-hero {
            background-image: url('https://images.unsplash.com/photo-1518176007466-99e525a40b49?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
        }

        .reveal { opacity: 0; transform: translateY(30px); transition: all 1.2s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .particle { position: absolute; background: rgba(255, 255, 255, 0.4); border-radius: 50%; pointer-events: none; animation: rise 8s infinite ease-in; }
        @keyframes rise {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: translateY(-10vh) scale(1.2); opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-50 text-slate-700">

    <section class="relative min-h-[75vh] flex items-center justify-center overflow-hidden pt-12 bg-hero">
        <div class="absolute inset-0 bg-emerald-950/20 backdrop-blur-[1px]"></div>
        
        <div id="particles-container" class="z-10"></div>
        <div class="text-center z-20 px-4">
            <h1 class="font-serif text-3xl md:text-5xl mb-6 leading-relaxed animate__animated animate__fadeIn text-white drop-shadow-lg text-center">
                あの日の後悔、リサイクルします
            </h1>
            <p class="text-base md:text-lg text-emerald-50 mb-8 max-w-2xl mx-auto leading-loose animate__animated animate__fadeIn animate__delay-1s tracking-wide drop-shadow-md text-center">
                誰もが抱える「後悔」を、あなたと、誰かの明日のために<br>
                「後悔」を新たな価値に転換するプラットフォーム。
            </p>
            <div class="animate__animated animate__fadeIn animate__delay-2s text-center">
                <a href="{{ route('login') }}" 
                class="inline-block bg-white text-emerald-800 px-8 py-3 rounded-full font-medium tracking-widest shadow-lg hover:shadow-xl transition-all">
                    はじめる（ログイン/登録）
                </a>
            </div>
        </div>
    </section>

    <section class="py-16 px-6 bg-white rounded-t-3xl -mt-8 relative z-30 shadow-sm border-b border-emerald-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10 reveal">
                <h2 class="font-serif text-2xl md:text-3xl mb-3 text-emerald-950 tracking-widest">後悔の断片から探す</h2>
                <p class="text-slate-600 text-sm">誰かの物語が、あなたの光になるかもしれません。</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6">
                @php
                $display_categories = [
                    ['name' => '恋愛', 'img' => 'https://images.unsplash.com/photo-1471922694854-ff1b63b20054?auto=format&fit=crop&q=80&w=600'],
                    ['name' => '進学', 'img' => 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&q=80&w=600'],
                    ['name' => '就職・転職', 'img' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&q=80&w=600'],
                    ['name' => '学び', 'img' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&q=80&w=600'],
                    ['name' => '仕事', 'img' => 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=600'],
                    ['name' => 'お金', 'img' => 'https://images.unsplash.com/photo-1553729459-efe14ef6055d?auto=format&fit=crop&q=80&w=600'],
                    ['name' => '健康', 'img' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&q=80&w=600'],
                    ['name' => '趣味', 'img' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&q=80&w=600'],
                    ['name' => '出会い・別れ', 'img' => 'https://images.unsplash.com/photo-1495539406979-bf61750d38ad?auto=format&fit=crop&q=80&w=600'],
                ];
                @endphp

                @foreach($display_categories as $cat)
                <div class="reveal relative block aspect-video overflow-hidden rounded-xl shadow-md border border-white bg-emerald-100 group">
                    <img src="{{ $cat['img'] }}" alt="{{ $cat['name'] }}" class="absolute inset-0 h-full w-full object-cover opacity-80 transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-emerald-950/20 group-hover:bg-emerald-950/40 transition-colors"></div>
                    
                    <div class="absolute inset-0 flex items-center justify-center p-3">
                        <h3 class="font-serif text-white text-lg md:text-xl tracking-[0.2em] drop-shadow-lg text-center">
                            {{ $cat['name'] }}
                        </h3>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 text-center reveal">
                <a href="{{ route('regrets.index') }}" class="text-emerald-700 hover:text-emerald-500 font-medium tracking-widest transition-colors text-sm border-b border-emerald-200 pb-1 uppercase">
                    VIEW ALL EPISODES →
                </a>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 max-w-5xl mx-auto bg-gray-50">
        <div class="text-center mb-16 reveal">
            <h2 class="font-serif text-2xl md:text-3xl text-emerald-900 mb-4 tracking-widest uppercase">再生のプロセス</h2>
            <div class="w-16 h-0.5 bg-emerald-200 mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <div class="reveal">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-700 font-bold text-lg shadow-inner">1</div>
                <h3 class="font-serif text-xl mb-3 text-emerald-900">みつめる</h3>
                <p class="text-slate-500 text-sm leading-relaxed px-4">心に沈殿した「あのとき」を言葉に</p>
            </div>
            <div class="reveal">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-700 font-bold text-lg shadow-inner">2</div>
                <h3 class="font-serif text-xl mb-3 text-emerald-900">浄化する</h3>
                <p class="text-slate-500 text-sm leading-relaxed px-4">お焚き上げにより執着を光へ昇華</p>
            </div>
            <div class="reveal">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-700 font-bold text-lg shadow-inner">3</div>
                <h3 class="font-serif text-xl mb-3 text-emerald-900">転換する</h3>
                <p class="text-slate-500 text-sm leading-relaxed px-4">過去から未来への道標を</p>
            </div>
        </div>
        
        <div class="mt-20 text-center reveal">
            <p class="text-slate-400 font-serif italic text-sm">すべての後悔は、新しい物語のプロローグ</p>
        </div>
    </section>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        const container = document.getElementById('particles-container');
        for (let i = 0; i < 15; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.width = p.style.height = Math.random() * 5 + 2 + 'px';
            p.style.animationDelay = Math.random() * 5 + 's';
            container.appendChild(p);
        }
    </script>

    <footer class="bg-gray-900 py-10 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="{{ route('terms') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition underline decoration-gray-700 underline-offset-4">
                    利用規約
                </a>
                <a href="{{ route('privacy') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition underline decoration-gray-700 underline-offset-4">
                    プライバシーポリシー
                </a>
            </div>
            <p class="text-xs text-gray-600">
                &copy; 2026 Regless - 後悔を、未来につなぐ。
            </p>
        </div>
    </footer>

</body>
</html>