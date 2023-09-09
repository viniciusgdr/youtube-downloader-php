<!DOCTYPE html>
<html lang={{ str_replace('_', '-', app()->getLocale()) }}>
<head>
    <meta charset="UTF-8">
    <title>
        {{ $title ?? 'Page Title' }}
    </title>
    @vite('resources/css/app.css')
</head>

<body>
<nav class="bg-slate-900 text-white p-4">
    <div class="flex justify-between items-center">
        <div class="flex items-center flex-row">
            <a href="{{ route('home') }}" class="text-white text-2xl font-bold">Youtube Downloader</a>
        </div>
        @auth()
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-white text-lg font-bold">Dashboard</a>
            </div>
        @else
            <div class="flex items-center">
                <a href="{{ route('login') }}" class="text-white text-lg font-bold">Login</a>
                <a href="{{ route('register') }}" class="text-white text-lg font-bold ml-4">Register</a>
            </div>
        @endauth
    </div>
</nav>
{{ $slot ?? '' }}
{{ $scripts ?? '' }}
@yield('content')
</body>
</html>
