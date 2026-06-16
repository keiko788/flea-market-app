<x-app-layout>
    <nav class="border-b-2 border-[#5F5F5F] pl-[210px]">
        <ul class="flex gap-[82px] mt-[47px] mb-2">
            <li>
                <a href="{{ route('items.index', ['keyword' => request('keyword')]) }}" class="text-2xl font-bold {{ request('tab') !== 'mylist' ? 'text-[#FF0000]' : 'text-[#5F5F5F]' }}">おすすめ</a>
            </li>
            <li>
                <a href="{{ route('items.index', array_merge(request()->query(), ['tab' => 'mylist'])) }}" class="text-2xl font-bold {{ request('tab') === 'mylist' ? 'text-[#FF0000]' : 'text-[#5F5F5F]' }}">マイリスト</a>
            </li>
        </ul>
    </nav>

    <!-- 商品一覧リスト -->
    <div class="px-[69px]">
        <div class="max-w-[1374px] mx-auto mt-[76px]">
            <ul class="flex flex-wrap gap-x-[calc((100%-1160px)/3)] gap-y-[77px] w-full">
                @foreach($items as $item)
                <li>
                    <a href="{{ route('items.show', $item) }}" class="block w-[290px] h-[281px] overflow-hidden rounded mb-2">
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像"
                            class="w-full h-full object-cover">
                    </a>
                    <h2 class="font-normal text-[25px]">{{ $item->name }}</h2>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>