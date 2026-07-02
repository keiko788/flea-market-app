<x-app-layout>
    <div class="px-[80px]">
        <div class="max-w-[1352px] mx-auto">
            <h1 class="sr-only">商品購入画面</h1>

            <div class="flex justify-between mt-[93px] mb-[174px] w-full">

                <div class="w-[59%]">
                    <div class="border-b-[1px] border-black flex w-full pb-[52.74px]">
                        <img src="{{ asset('storage/' . $item->image_path ) }}" alt="商品画像"
                            class="w-[177px] h-[177px] object-cover">
                        <div class="ml-[55px]">
                            <h2 class="text-3xl font-bold mb-5">{{ $item->name }}</h2>
                            <div class="text-[32px]">
                                <span class="text-[27px] inline-block mr-2">¥</span>
                                {{ number_format($item->price) }}
                            </div>
                        </div>
                    </div>

                    <!-- 支払い方法 -->
                    <form action="{{ route('purchase.show', $item->id) }}" method="GET">
                        @csrf
                        <div class="border-b-[1px] border-black w-full px-[35px] pt-[36px] pb-[63px]">
                            <label for="payment_method" class="text-xl font-bold mb-[30px]">支払い方法</label>
                            <div class="relative inline-block">
                                <select name="payment_method"
                                    id="payment_method"
                                    onchange="this.form.submit()"
                                    class="pl-2 ml-[67px] w-[265px] h-[31px] border-[#5F5F5F] border-[1px] rounded appearance-none text-[#5F5F5F] focus:outline-none focus:ring-0 focus:border-[#5F5F5F]">
                                    <option value="" disabled {{ old('payment_method') ? '' : 'selected' }} hidden>選択してください</option>
                                    <option value="1" {{ old('payment_method') == 1 ? 'selected' : '' }}>コンビニ払い</option>
                                    <option value="2" {{ old('payment_method') == 2 ? 'selected' : '' }}>カード支払い</option>
                                </select>
                                <img src="{{ asset('images/arrow-down.svg') }}"
                                    alt=""
                                    class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-3">
                            </div>

                            @error('payment_method')
                            <p class="text-red-600 ml-[67px]">
                                {{ $message }}
                            </p>
                            @enderror

                        </div>
                    </form>

                    <!-- 配送先 -->
                    <div class="border-b-[1px] border-black w-full px-[35px] pt-[36px] pb-[63px]">
                        <div class="flex justify-between mb-[29px]">
                            <div class="text-xl font-bold">配送先</div>
                            <a href="{{ route('purchase.edit.address', $item->id) }}"
                                class="text-xl text-[#0073CC]">
                                変更する
                            </a>
                        </div>

                        @php
                        $purchaseAddress = session('purchase_address');
                        $shippingPostalCode = $purchaseAddress['shipping_postal_code'] ?? $profile->postal_code;
                        $shippingAddress = $purchaseAddress['shipping_address'] ?? $profile->address;
                        $shippingBuilding = $purchaseAddress ? $purchaseAddress['shipping_building'] : $profile->building;
                        @endphp

                        <p class="text-xl font-bold ml-[67px] leading-8">
                            〒 {{ $shippingPostalCode }}
                            <br>
                            {{ $shippingAddress }}{{ $shippingBuilding }}
                        </p>
                        @if ($errors->has('shipping_postal_code') || $errors->has('shipping_address'))
                        <div class="text-red-600 space-y-1 ml-[67px]">
                            @if ($errors->has('shipping_postal_code'))
                            <p>{{ $errors->first('shipping_postal_code') }}</p>
                            @endif

                            @if ($errors->has('shipping_address'))
                            <p>{{ $errors->first('shipping_address') }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 小計画面 -->
                <div class="w-[32.5%] pr-[5px]">
                    <table class="border-[1px] border-black w-full h-[230px] mb-[66px]">
                        <tbody>
                            <tr>
                                <th class="text-xl">商品代金</th>
                                <td class="text-2xl w-[220px] text-center">¥ <span class="text-[28px]">{{ number_format($item->price) }}</span></td>
                            </tr>

                            <tr class="border-t-[1px] border-black" data-testid="payment-method-summary">
                                <th class="text-xl">支払い方法</th>
                                <td class="text-2xl w-[220px] text-center">
                                    {{
                                        match (request('payment_method')) {
                                            '1' => 'コンビニ払い',
                                            '2' => 'カード支払い',
                                            default => 'コンビニ払い',
                                        }
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <form action="{{ route('purchase.store', $item->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                        <input type="hidden" name="shipping_postal_code" value="{{ $shippingPostalCode }}">
                        <input type="hidden" name="shipping_address" value="{{ $shippingAddress }}">
                        <input type="hidden" name="shipping_building" value="{{ $shippingBuilding }}">
                        <button type="submit" class="w-full h-[60px] bg-[#FF5555] text-white text-[26px] font-bold rounded-[5px]">購入する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 支払い方法を小計画面に反映 -->
    <!-- <script>
        const paymentSelect = document.getElementById('payment_method');
        const paymentText = document.getElementById('payment_method_text');
        const selectedPaymentMethod = document.getElementById('selected_payment_method');

        paymentSelect.addEventListener('change', function() {
            paymentText.textContent = this.options[this.selectedIndex].text;
            selectedPaymentMethod.value = this.value;
        });
    </script> -->
</x-app-layout>