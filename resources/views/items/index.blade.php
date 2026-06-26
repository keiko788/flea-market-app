<x-app-layout>
    <h1 class="sr-only">商品一覧</h1>

    <nav class="border-b-2 border-[#5F5F5F] pl-[210px]">
        <ul class="flex gap-[82px] mt-[47px] mb-2">
            <li>
                <a href="{{ route('items.index', ['keyword' => request('keyword')]) }}" class="text-2xl font-bold {{ request('tab') !== 'mylist' ? 'text-[#FF0000]' : 'text-[#5F5F5F]' }}">
                    おすすめ
                </a>
            </li>
            <li>
                <a href="{{ route('items.index', array_merge(request()->query(), ['tab' => 'mylist'])) }}"
                class="text-2xl font-bold {{ request('tab') === 'mylist' ? 'text-[#FF0000]' : 'text-[#5F5F5F]' }}">
                    マイリスト
                </a>
            </li>
        </ul>
    </nav>

    <!-- 商品一覧リスト -->
    <div class="px-[69px]">
        <div class="max-w-[1374px] mx-auto my-[76px]">
            <ul class="flex flex-wrap gap-x-[calc((100%-1160px)/3)] gap-y-[77px] w-full">
                @foreach($items as $item)
                <x-item-card :item="$item" />
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>