<x-app-layout>
    <div class="max-w-[1120px] mx-auto mt-[78px] pl-[103px] flex justify-between items-center">
        <div class="w-[150px] h-[150px] rounded-full bg-[#D9D9D9] overflow-hidden">
            @if ($profile?->profile_image_path)
            <img
                src="{{ asset('storage/' . $profile->profile_image_path) }}"
                alt="プロフィール写真"
                class="w-full h-full object-cover">
            @endif
        </div>
        <h2 class="text-4xl font-bold">{{ $user->name }}</h2>
        <a href="{{ route('profile.edit', ['redirect_to' => 'mypage']) }}" class="flex justify-center items-center w-[310px] h-[59px] border-[#FF5555] rounded-[10px] border-2 text-[26px] text-[#FF5555] font-bold">プロフィールを編集</a>
    </div>
    <nav class="border-b-2 border-[#5F5F5F] pl-[210px]">
        <ul class="flex gap-[82px] mt-[47px] mb-2">
            <li>
                <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="text-2xl font-bold {{ request('page', 'sell') === 'sell' ? 'text-[#FF0000]' : 'text-[#5F5F5F]' }}">出品した商品</a>
            </li>
            <li>
                <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="text-2xl font-bold {{ request('page', 'sell') === 'buy' ? 'text-[#FF0000]' : 'text-[#5F5F5F]' }}">購入した商品</a>
            </li>
        </ul>
    </nav>

    <div class="px-[69px]">
        <div class="max-w-[1374px] mx-auto my-[76px]">
            <ul class="flex flex-wrap gap-x-[calc((100%-1160px)/3)] gap-y-[77px] w-full">

                @if ($page === 'sell')
                @foreach($listedItems as $item)
                <li>
                    <a href="{{ route('items.show', $item) }}" class="block w-[290px] h-[281px] overflow-hidden rounded mb-2">
                        <div class="relative overflow-hidden w-full h-full">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像"
                                class="w-full h-full object-cover">
                            @if ($item->purchase)
                            <div class="absolute top-[-16px] left-[-70px] rotate-[-45deg] bg-red-500 text-white font-bold text-2xl w-[200px] text-center pb-4 pt-12">
                                SOLD
                            </div>
                            @endif
                        </div>
                    </a>
                    <h3 class="font-normal text-[25px]">{{ $item->name }}</h3>
                    @endforeach
                </li>

                @elseif ($page === 'buy')
                @foreach($purchasedItems as $item)
                <li>
                    <a href="{{ route('items.show', $item) }}" class="block w-[290px] h-[281px] overflow-hidden rounded mb-2">
                        <div class="relative overflow-hidden w-full h-full">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像"
                                class="w-full h-full object-cover">
                            @if ($item->purchase)
                            <div class="absolute top-[-16px] left-[-70px] rotate-[-45deg] bg-red-500 text-white font-bold text-2xl w-[200px] text-center pb-4 pt-12">
                                SOLD
                            </div>
                            @endif
                        </div>
                    </a>
                    <h3 class="font-normal text-[25px]">{{ $item->name }}</h3>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>



</x-app-layout>