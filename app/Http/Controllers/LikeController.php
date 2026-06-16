<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;

class LikeController extends Controller
{
    // いいねを追加
    public function store(Item $item_id)
    {
        $item = $item_id;
        Like::firstOrCreate([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('items.show', $item->id);
    }

    // いいねを削除
    public function destroy(Item $item_id)
    {
        $item = $item_id;
        Like::where('user_id', auth()->id())
            ->where('item_id', $item->id)
            ->delete();

        return redirect()->route('items.show', $item->id);
    }
}
