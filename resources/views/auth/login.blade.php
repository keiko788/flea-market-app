<x-auth-layout>
    <div class="max-w-[680px] mx-auto mt-[110px]">
        <h1 class="text-4xl font-bold text-center mb-[52px]">ログイン</h1>
        <div>
            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <!-- メールアドレス -->
                <div>
                    <label for="email" class="block text-2xl font-bold mb-2">
                        メールアドレス
                    </label>
                    <input id="email"
                        class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4"
                        type="email" name="email"
                        value="{{ old('email') }}" autofocus />

                    @if ($errors->get('email'))
                    <ul class="text-sm text-red-600 space-y-1 mt-2">
                        @foreach ((array) $errors->get('email') as $message)
                        <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                    @endif

                </div>

                <!-- パスワード -->
                <div class="mt-9">
                    <label for="password" class="block text-2xl font-bold mb-2">
                        パスワード
                    </label>
                    <input id="password"
                        class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4"
                        type="password"
                        name="password" />

                    @error('password')
                    <p class="text-sm text-red-600 mt-2">
                        {{ $message }}
                    </p>
                    @enderror

                </div>

                <button type=" submit"
                    class="w-full text-white font-bold text-[26px] bg-[#FF5555] rounded-[5px] flex items-center justify-center h-[65.25px] mt-20 mb-[18px] cursor-pointer">
                    ログインする
                </button>

            </form>
            <a href="/register" class="block text-center text-[#0073cc] cursor-pointer">
                会員登録はこちら
            </a>
        </div>
    </div>
</x-auth-layout>