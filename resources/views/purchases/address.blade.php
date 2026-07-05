<x-app-layout>
    <div class="max-w-[680px] mx-auto mt-12 mb-[135px]">
        <h1 class="text-4xl font-bold text-center mb-[103px]">住所の変更</h1>

        <form action="{{ route('purchase.update.address', $item->id) }}" method="POST">
            @csrf
            @method('PATCH')

            @php
            $purchaseAddress = session('purchase_address.' . $item->id);
            @endphp

            <!-- 郵便番号 -->
            <div>
                <label for="shipping_postal_code" class="block text-2xl font-bold mb-2">郵便番号</label>
                <input type="text" id="shipping_postal_code" class="block w-full border border-[#5F5F5F] h-[45px] rounded px-4"
                    name="shipping_postal_code"
                    value="{{ old('shipping_postal_code',
                    $purchaseAddress['shipping_postal_code'] ?? $profile->postal_code) }}" />
                @if ($errors->get('shipping_postal_code'))
                <ul class="text-sm text-red-600 space-y-1 mt-2">
                    @foreach ((array) $errors->get('shipping_postal_code') as $message)
                    <li>{{ $message }}</li>
                    @endforeach
                </ul>
                @endif
            </div>

            <!-- 住所 -->
            <div class="mt-[68px]">
                <label for="shipping_address" class="block text-2xl font-bold mb-2">住所</label>
                <input type="text" id="shipping_address" class="block w-full border border-[#5F5F5F] h-[45px] rounded px-4"
                    name="shipping_address"
                    value="{{ old('shipping_address',
                    $purchaseAddress['shipping_address'] ?? $profile->address) }}" />
                @error('shipping_address')
                <p class="text-sm text-red-600 mt-2">
                    {{ $message }}
                </p>
                @enderror

            </div>

            <!-- 建物名 -->
            <div class="mt-[90px]">
                <label for="shipping_building" class="block text-2xl font-bold mb-2">建物名</label>
                <input type="text" id="shipping_building" class="block w-full border border-[#5F5F5F] h-[45px] rounded px-4"
                    name="shipping_building" value="{{ old('shipping_building',
                    $purchaseAddress ? $purchaseAddress['shipping_building'] : $profile->building) }}" />
            </div>

            <button type="submit" class="w-full text-white font-bold text-[26px] bg-[#FF5555] rounded-[5px] flex items-center justify-center  mt-[111px] h-[60px] cursor-pointer">
                更新する
            </button>
        </form>

    </div>
</x-app-layout>