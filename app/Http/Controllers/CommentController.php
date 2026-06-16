<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    // コメントを追加
    public function store(CommentRequest $request, Item $item_id)
    {
        $item = $item_id;
        $validated = $request->validated();

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'body' => $validated['body'],
        ]);

        return redirect()->route('items.show', $item->id);
    }
}
