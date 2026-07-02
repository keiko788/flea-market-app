                <li>
                    <a href="{{ route('items.show', $item) }}"
                        class="block w-[290px] h-[281px] overflow-hidden rounded mb-2">
                        <div class="relative overflow-hidden w-full h-full">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像"
                                class="w-full h-full object-cover">
                            @if ($item->purchase)
                            <div class="absolute top-[-16px] left-[-70px] rotate-[-45deg] bg-red-500 text-white font-bold text-2xl w-[200px] text-center pb-4 pt-12"
                            data-testid="sold-{{ $item->id }}">
                                Sold
                            </div>
                            @endif
                        </div>
                    </a>
                    <h2 class="font-normal text-[25px]">{{ $item->name }}</h2>
                </li>