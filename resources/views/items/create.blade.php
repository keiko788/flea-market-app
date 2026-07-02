<x-app-layout>
    <div class="max-w-[680px] mx-auto mt-[51px] mb-[152px]">
        <h1 class="text-4xl font-bold text-center mb-[43px]">商品の出品</h1>

        <form method="POST" action="{{ route('items.store') }}"
            enctype="multipart/form-data" novalidate>
            @csrf

            <!-- 商品画像 -->
            <div class="text-2xl font-bold mb-2">商品の画像</div>
            <div class="w-full h-[150px] border-[1px] border-dashed rounded
        border-[#5F5F5F] flex items-center justify-center">
                <label
                    for="image_path"
                    class=" cursor-pointer w-[165px] h-[43.46px] border-2 border-[#FF5555] rounded-[10px] text-[#FF5655] flex justify-center items-center">
                    画像を選択する
                </label>
                <input
                    type="file"
                    id="image_path"
                    name="image_path"
                    accept=".jpeg,.png"
                    class="hidden">
            </div>
            @if ($errors->get('image_path'))
            <ul class="text-red-600">
                @foreach ((array) $errors->get('image_path') as $message)
                <li>{{ $message }}</li>
                @endforeach
            </ul>
            @endif


            <!-- 商品の詳細 -->
            <section class="mt-[70px]">
                <h2 class="text-3xl font-bold text-[#5F5F5F] pb-3 mb-9 border-b-[1px] border-[#5F5F5F]">
                    商品の詳細
                </h2>

                <div class="mb-[65px]">
                    <div class="text-2xl font-bold">カテゴリー</div>
                    <div class=" w-full flex flex-wrap gap-x-[19px] gap-y-[35px] mt-[31px]">
                        @foreach($categories as $category)
                        <label class="cursor-pointer">
                            <input type="checkbox"
                                name="category_ids[]"
                                value="{{ $category->id }}"
                                {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                class="peer sr-only" />
                            <span class=" text-[15px] text-[#FF5655] font-medium border-2 border-[#FF5655]
                        rounded-[200px] py-1 px-[17px]
                        peer-checked:text-white peer-checked:bg-[#FF5655] transition-colors">
                                {{ $category->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('category_ids')
                    <p class="text-red-600 mt-2">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <label for="condition" class="text-2xl font-bold">
                    商品の状態
                </label>
                <div class="relative mt-[19px]">
                    <select name="condition"
                        id="condition"
                        class="w-full h-[45px] border-[#5F5F5F] border cursor-pointer
                        rounded px-[15px] font-bold text-[#5F5F5F] appearance-none
                        focus:outline-none
                        focus:border-[#5F5F5F]">
                        <option value="" disabled hidden {{ old('condition') == '' ? 'selected' : '' }}>選択してください</option>
                        <option value="1" {{ old('condition') == '1' ? 'selected' : '' }}>良好</option>
                        <option value="2" {{ old('condition') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="3" {{ old('condition') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="4" {{ old('condition') == '4' ? 'selected' : '' }}>状態が悪い</option>
                    </select>
                    <img src="{{ asset('images/arrow-down.svg') }}"
                        alt=""
                        class="pointer-events-none absolute top-1/2 -translate-y-1/2  right-[14px] w-[18px]">
                </div>
                @error('condition')
                <p class="text-red-600 mt-2">
                    {{ $message }}
                </p>
                @enderror
            </section>

            <!-- 商品名と説明 -->
            <section class="mt-[69px]">
                <h2 class="text-3xl font-bold text-[#5F5F5F] pb-3 mb-9 border-b-[1px] border-[#5F5F5F]">
                    商品名と説明
                </h2>
                <div class="mb-9">
                    <label for="name" class="text-2xl font-bold ">商品名</label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name') }}"
                        class="w-full h-[45px] border border-[#5F5F5F] rounded px-[15px] mt-1">
                    @error('name')
                    <p class="text-red-600 mt-2">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="mb-[39px]">
                    <label for="brand_name" class="text-2xl font-bold">ブランド名</label>
                    <input type="text" id="brand_name" name="brand_name"
                        value="{{ old('brand_name') }}"
                        class="w-full h-[45px] border border-[#5F5F5F] rounded px-[15px] mt-1">
                </div>

                <div class="mb-9">
                    <label for="description" class="text-2xl font-bold">商品の説明</label>
                    <textarea
                        name="description"
                        id="description"
                        class="w-full h-[125px] border border-[#5F5F5F] rounded px-[15px] py-2 mt-1 resize-none">{{ old('description') }}</textarea>
                    @if ($errors->get('description'))
                    <ul class="text-red-600 space-y-1 mt-1">
                        @foreach ((array) $errors->get('description') as $message)
                        <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <div class="mb-[118px]">
                    <label for="price" class="text-2xl font-bold">販売価格</label>
                    <div class="relative mt-1">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-2xl font-bold">
                            ¥
                        </span>
                        <input type="text"
                            id="price"
                            name="price"
                            value="{{ old('price') }}"
                            inputmode="numeric"
                            min="0"
                            class="w-full h-[45px] border border-[#5F5F5F] rounded pl-10 pr-[15px]">
                    </div>
                    @if ($errors->get('price'))
                    <ul class="text-red-600 space-y-1 mt-2">
                        @foreach ((array) $errors->get('price') as $message)
                        <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <button type="submit"
                    class="text-[26px] text-white font-bold w-full h-[60px] rounded-[5px]
                    bg-[#FF5555] flex justify-center items-center cursor-pointer">
                    出品する
                </button>

            </section>

        </form>
    </div>
</x-app-layout>