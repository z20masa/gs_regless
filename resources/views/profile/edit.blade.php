<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-emerald-800 leading-tight">
            プロフィール設定
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-emerald-100">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900">ユーザー情報</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        ニックネーム: {{ Auth::user()->nickname }}<br>
                        年代: {{ Auth::user()->age_group }}<br>
                        性別: {{ Auth::user()->gender }}<br>
                        メールアドレス: {{ Auth::user()->email }}
                    </p>
                    
                    <p class="mt-4 text-sm text-emerald-600 font-bold">
                        ※MVP期間中はここからの編集機能は制限しています。<br>
                        （データの整合性を保つため、登録時の情報を使用します）
                    </p>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-red-100">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-red-600">アカウントの削除</h3>
                    <form method="post" action="{{ route('profile.destroy') }}" class="mt-4">
                        @csrf
                        @method('delete')
                        <p class="text-sm text-gray-600 mb-4">アカウントを削除すると、これまでの後悔リサイクルの記録がすべて消去されます。</p>
                        <x-danger-button onclick="return confirm('本当に削除しますか？')">
                            アカウントを削除する
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>