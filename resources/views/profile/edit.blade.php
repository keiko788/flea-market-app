<x-app-layout>
    <div class="max-w-[680px] mx-auto mt-[59px]">
        <h1 class="text-4xl font-bold text-center mb-[47px]">プロフィール設定</h1>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')

            <!-- プロフィール画像選択 -->
            <div class="flex items-center gap-10">
                <!-- プレビュー -->
                <div class="w-[150px] h-[150px] rounded-full bg-gray-300 overflow-hidden">
                    @if ($profile?->profile_image_path)
                    <img
                        src="{{ asset('storage/' . $profile->profile_image_path) }}"
                        alt="プロフィール写真"
                        class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- ボタン -->
                <label
                    for="profile_image"
                    class="cursor-pointer border-2 border-[#FF5555] text-[#FF5555] rounded-[10px] text-xl font-bold px-4 py-2">
                    画像を選択する</label>
                <input
                    type="file"
                    id="profile_image"
                    name="profile_image"
                    accept=".jpeg,.png"
                    class="hidden">

            </div>

            <!-- ユーザー名 -->
            <div class="mt-[76px]">
                <label for="name" class="block text-2xl font-bold mb-2">ユーザー名</label>
                <input type="text" id="email" class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4"
                    type="name" name="name" value="{{ old('name', $user->name ?? '') }}" autofocus />
                @if ($errors->get('name'))
                <ul class="text-sm text-red-600 space-y-1 mt-2">
                    @foreach ((array) $errors->get('name') as $message)
                    <li>{{ $message }}</li>
                    @endforeach
                </ul>
                @endif
            </div>

            <!-- 郵便番号 -->
            <div class="mt-[62px]">
                <label for="postal_code" class="block text-2xl font-bold mb-2">郵便番号</label>
                <input type="text" id="postal_code" class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4"
                    type="postal_code" name="postal_code" value="{{ old('postal_code', $profile?->postal_code) }}" />
                @if ($errors->get('postal_code'))
                <ul class="text-sm text-red-600 space-y-1 mt-2">
                    @foreach ((array) $errors->get('postal_code') as $message)
                    <li>{{ $message }}</li>
                    @endforeach
                </ul>
                @endif
            </div>

            <!-- 住所 -->
            <div class="mt-[43px]">
                <label for="address" class="block text-2xl font-bold mb-2">住所</label>
                <input type="text" id="address" class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4"
                    type="address" name="address" value="{{ old('address', $profile?->address) }}" />
                @if ($errors->get('address'))
                <ul class="text-sm text-red-600 space-y-1 mt-2">
                    @foreach ((array) $errors->get('address') as $message)
                    <li>{{ $message }}</li>
                    @endforeach
                </ul>
                @endif
            </div>

            <!-- 建物名 -->
            <div class="mt-[43px]">
                <label for="building" class="block text-2xl font-bold mb-2">建物名</label>
                <input type="text" id="address" class="block w-full border border-[#5F5F5F] h-14
                    rounded px-4"
                    type="building" name="building" value="{{ old('building', $profile?->building) }}" />
                @if ($errors->get('building'))
                <ul class="text-sm text-red-600 space-y-1 mt-2">
                    @foreach ((array) $errors->get('building') as $message)
                    <li>{{ $message }}</li>
                    @endforeach
                </ul>
                @endif
            </div>

            <button type="submit" class="w-full text-white font-bold text-[26px] bg-[#FF5555] rounded-[5px] flex items-center justify-center  mt-[67px] mb-[127px] h-[65.25px]">更新する</button>


        </form>
    </div>
</x-app-layout>