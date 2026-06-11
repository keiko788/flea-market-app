<x-auth-layout>
    <div class="max-w-[680px] mx-auto mt-[96px]">
        <h1 class="text-4xl font-bold text-center mb-[54px]">会員登録</h1>
        <div>
            <form method="POST" action="{{ route('register.store') }}" novalidate>
                @csrf

                <!-- ユーザー名 -->
                <div>
                    <label for="name" class="block text-2xl font-bold mb-2">ユーザー名</label>
                    <input id="name" class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4"
                        type="name" name="name" value="{{ old('name') }}" autofocus />
                    @if ($errors->get('name'))
                    <ul class="text-sm text-red-600 space-y-1 mt-2">
                        @foreach ((array) $errors->get('name') as $message)
                        <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <!-- メールアドレス -->
                <div class="mt-[38px]">
                    <label for="email" class="block text-2xl font-bold mb-2">メールアドレス</label>
                    <input id="email" class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4"
                        type="email" name="email" value="{{ old('email') }}"/>
                    @if ($errors->get('email'))
                    <ul class="text-sm text-red-600 space-y-1 mt-2">
                        @foreach ((array) $errors->get('email') as $message)
                        <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <!-- パスワード -->
                <div class="mt-[38px]">
                    <label for="password" class="block text-2xl font-bold mb-2">パスワード</label>
                    <input id="password" class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4" type="password" name="password" />
                    @if ($errors->get('password'))
                    <ul class="text-sm text-red-600 space-y-1 mt-2">
                        @foreach ((array) $errors->get('password') as $message)
                        <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <!-- 確認用パスワード -->
                <div class="mt-[38px]">
                    <label for="password" class="block text-2xl font-bold mb-2">確認用パスワード</label>
                    <input id="password_confirmation" class="block w-full border border-[#5F5F5F] h-14
                    rounded mb-[116px] px-4 mb-20" type="password" name="password_confirmation" />
                </div>

                <button type="submit" class="w-full text-white font-bold text-[26px] bg-[#FF5555] rounded-[5px] flex items-center justify-center h-[65.25px] mb-[18px]">登録する</button>

            </form>
            <a href="{{ route('login') }}" class="block text-center text-[#0073cc] mb-[43px]">ログインはこちら</a>
        </div>
    </div>
</x-auth-layout>