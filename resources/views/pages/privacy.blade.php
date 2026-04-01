<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>プライバシーポリシー - Regless</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-300 antialiased">
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 bg-gray-800 p-10 rounded-2xl shadow-xl border border-gray-700">
            <h1 class="text-3xl font-bold mb-8 text-green-400 border-b border-gray-700 pb-4">プライバシーポリシー</h1>
            
            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-white">1. 個人情報の収集</h2>
                <p class="leading-relaxed">本サービスでは、アカウント登録時にニックネーム、メールアドレス、年代、性別を収集します。これらはサービスの提供および分析のために適切に管理されます。</p>
            </section>

            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-white">2. データの利用目的</h2>
                <p class="leading-relaxed">収集したデータは、AIによる回答生成、および「後悔」の傾向分析に利用します。個人を特定できる形で第三者に公開することはありません。</p>
            </section>

            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-white">3. 免責事項</h2>
                <p class="leading-relaxed">ユーザー自身の投稿により個人が特定された場合、本サービスは一切の責任を負いません。投稿内容には十分ご注意ください。</p>
            </section>

            <section class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-white">4. データのセキュリティ</h2>
                <p class="leading-relaxed">本サービスは、ユーザーのデータを保護するために適切なセキュリティ対策を講じています。保存されたデータは、バックアップやシステム改善の目的で厳重に管理されます。</p>
            </section>

            <div class="mt-10 text-center">
                <a href="{{ url('/') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition font-bold shadow-lg">
                    トップページに戻る
                </a>
            </div>
        </div>
    </div>
</body>
</html>