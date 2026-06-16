<?php

namespace App\Http\Controllers;

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
                    ->when($keyword, function ($query, $keyword) {
                        $query->where('items.name', 'like', "%{$keyword}%");
                    })
                    ->latest('items.created_at')
                    ->get()
                : collect();
        } else {
            $items = Item::query()
                ->where('items.name', 'like', "%{$keyword}%")
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
}
