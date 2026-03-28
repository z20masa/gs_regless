<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- デザイン(CSS)は Vite に任せる --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- ★ Alpine.js を CDN から直接読み込む（Vite の不機嫌に左右されないための特効薬） --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- グラフ機能 --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
</head>

<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen bg-gray-100">
        <nav class="relative z-50">
            @include('layouts.navigation')
        </nav>

        @if (isset($header))
            <header class="bg-white shadow relative z-40">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="w-full relative z-10">
           {{ $slot }}
        </main>
    </div>
</body>
</html>