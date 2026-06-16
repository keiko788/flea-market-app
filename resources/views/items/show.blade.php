<x-app-layout>
    <div class="px-[111px]">
        <div class="max-w-[1290px] mx-auto">
            <div class="flex justify-between mt-[95px] mb-[178px] w-full">
                <div class="w-[46%] h-[46%] overflow-hidden rounded">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像" class="w-full h-full object-cover">
                </div>

                <div class="pr-[10px]">
                    <h2 class="text-[45px] font-bold mb-2">{{ $item->name }}</h2>
                    <span class="block text-xl mb-8">{{ $item->brand_name ?? '' }}</span>
                    <p class="text-3xl mb-7">¥<span class="text-[45px]">{{ number_format($item->price) }}</span>（税込）</p>
                    <div class="flex gap-[62px] mb-[22px] pl-10">

                        <div class="flex flex-col items-center">

                            @if ($isLiked)
                            <form action="{{ route('likes.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">
                                    <img src="{{ asset('images/likes-active.svg') }}" alt="いいねアイコン" class="w-[45px] h-10">
                                </button>
                            </form>
                            @else
                            <form action="{{ route('likes.store', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <img src="{{ asset('images/likes.svg') }}" alt="いいねアイコン" class="w-[45px] h-10">
                                </button>
                            </form>
                            @endif
                            <span class="block text-lg font-bold">{{ $item->likes()->count() }}</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <img src="{{ asset('images/comments.svg') }}" alt="コメントアイコン" class="w-10 h-10 mb-[7px]">
                            <span class="block text-lg font-bold">{{ $item->comments()->count() }}</span>
                        </div>

                    </div>

                    <form action="/purchase/{item_id}" method="GET">
                        <button type="submit" class="text-white text-[30px] font-bold flex justify-center items-center bg-[#FF5555] w-[570px] h-14 rounded mb-9">購入手続きへ</button>
                    </form>

                    <h3 class="text-4xl font-bold mb-[48px]">商品説明</h3>
                    <p class="text-2xl mb-[64px]">
                        {{ $item->description }}
                    </p>

                    <h3 class="text-4xl font-bold mb-8">商品の情報</h3>
                    <div class="mb-[60px] w-[570px]">

                        <div class="grid grid-cols-[183px_1fr] items-start mb-9">

                            <div class="text-2xl font-bold">カテゴリー</div>
                            <ul class="flex flex-wrap gap-x-[22.4px] gap-y-5">
                                @foreach ($item->categories as $category)
                                <li class="w-[102px] h-[30px] flex justify-center items-center text-xl rounded-[15px] bg-[#D9D9D9]">{{ $category->name }}</li>
                                @endforeach
                            </ul>

                        </div>

                        <div class="grid grid-cols-[183px_1fr] items-center">
                            <div class="text-2xl font-bold">商品の状態</div>
                            <div class="text-xl pl-8">{{ $item->condition_label }}</div>
                        </div>

                    </div>

                    <h3 class="text-4xl font-bold text-[#5F5F5F] mb-8">コメント({{ $item->comments->count() }})</h3>

                    @foreach ($item->comments as $comment)
                    <div class="flex gap-[18px] items-center mb-5">
                        <div class="w-[70px] h-[70px] rounded-full bg-[#D9D9D9] overflow-hidden">
                            @php
                            $imagePath = $comment->user->profile?->profile_image_path;
                            @endphp
                            @if(!empty($imagePath))
                            <img src="{{ asset('storage/' . $imagePath) }}" alt="プロフィール画像"
                                class="w-full h-full object-cover">
                            @endif
                        </div>
                        <span class="text-3xl font-bold">{{ $comment->user->name }}</span>
                    </div>
                    <div class="bg-[#D9D9D9] px-[15px] py-[15px] rounded-[5px] w-[570px] mb-11">
                        <p class="font-light text-xl">{{ $comment->body }}</p>
                    </div>
                    @endforeach

                    <form action="{{ route('comments.store', $item->id ) }}" method="POST">
                        @csrf
                        <label for="body" class="font-bold text-[28px]">商品へのコメント</label>
                        <textarea
                            name="body"
                            id="body"
                            class="block w-[570px] h-[246px] border-2 border-[#5F5F5F] rounded-[5px] text-2xl px-4 py-4 resize-y mt-2"></textarea>
                        @if ($errors->get('body'))
                        <ul class="text-xl text-red-600 space-y-1 mt-2 mb-12">
                            @foreach ((array) $errors->get('body') as $message)
                            <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                        @endif


                        <button type="submit" class="w-[570px] h-14 rounded bg-[#FF5555] flex justify-center items-center text-white text-2xl font-bold mt-12">コメントを送信する</button>
                    </form>

                </div>


            </div>
        </div>

    </div>
</x-app-layout>