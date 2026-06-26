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
    <header class="bg-black h-[82px] w-full px-[25px]">
        <div class="max-w-[1512px] w-full mx-auto pl-10 pr-[26px] flex items-center justify-between h-[82px]">

            <a href="{{ route('items.index') }}">
                <img src="{{ asset('images/logo.png') }}" alt="COACHTECH" class="w-[360px]">
            </a>

            <!-- 検索フォーム -->
            <div class="w-4/12">
                <form action="{{ route('items.index') }}" method="GET">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？" class="w-full h-[50px] rounded-[5px] text-2xl placeholder:text-black px-10">
                </form>

            </div>

            <!-- ナビゲーション -->
            <nav>
                <ul class="flex gap-[27px] h-[50px] items-center">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-white text-2xl">ログアウト
                            </button>
                        </form>
                    </li>

                    <li>
                        <a href="/mypage" class="text-white text-2xl">マイページ</a>
                    </li>

                    <li class="flex bg-white h-full w-[100px] rounded items-center justify-center">
                        <a href="{{ route('items.create') }}" class="text-2xl cursor-pointer">出品</a>
                    </li>

                </ul>

            </nav>

        </div>
    </header>
    <main>
        {{ $slot }}
    </main>

</body>

</html>