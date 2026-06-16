<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECHフリマ')</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans">
    <header class="bg-black h-[82px] w-full px-[25px] py-[22px]">

        <a href="{{ route('items.index') }}">
            <img src="{{ asset('images/logo.png') }}" alt="COACHTECH" class="w-[360px]">
        </a>
        
    </header>
    <main>
        {{ $slot }}
    </main>

</body>

</html>