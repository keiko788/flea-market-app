<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 商品一覧画面を表示
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');
        $keyword = $request->query('keyword');

        if ($tab === 'mylist') {
            $items = auth()->check()
                ? auth()->user()
                    ->likedItems()
                    ->with('purchase')
                    ->when($keyword, function ($query, $keyword) {
                        $query->where('items.name', 'like', "%{$keyword}%");
                    })
                    ->latest('items.created_at')
                    ->get()
                : collect();
        } else {
            $items = Item::query()
                ->with('purchase')
                ->when(auth()->check(), function ($query) {
                    $query->where('user_id', '!=', auth()->id());
                })
                ->when(
                    $keyword,
                    function ($query, $keyword) {
                        $query->where('items.name', 'like', "%{$keyword}%");
                    }
                )
                ->latest()
                ->get();
        }

        return view('items.index', compact('items', 'tab'));
    }

    // 商品詳細画面を表示
    public function show(Item $item_id)
    {
        $item = $item_id;

        $item->load([
            'categories',
            'comments.user.profile',
            'likes',
        ]);

        $isLiked = auth()->check()
            ? auth()->user()
                ->likedItems()
                ->where('items.id', $item->id)
                ->exists()
            : false;

        return view('items.show', compact('item', 'isLiked'));
    }

    // 商品出品画面を表示
    public function create()
    {
        $categories = Category::all();

        return view('items.create', compact('categories'));
    }

    // 出品商品を登録する
    public function store(ExhibitionRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $path = $request->file('image_path')
            ->store('items', 'public');

        $item = Item::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image_path' => $path,
            'condition' => $validated['condition'],
            'price' => $validated['price'],
            'brand_name' => $request->input('brand_name'),
        ]);

        $item->categories()->sync($validated['category_ids']);

        return redirect()->route('mypage.index');
    }
}
