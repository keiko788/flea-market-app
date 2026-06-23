<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class MypageController extends Controller
{
    // プロフィール画面を表示する
    public function index(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        $page = $request->query('page', 'sell');

        $listedItems = $user->items()
            ->with('purchase')
            ->latest()
            ->get();

        $purchasedItems = Item::with('purchase')
            ->whereHas('purchase', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('mypage.index', compact('user', 'profile', 'listedItems', 'purchasedItems', 'page'));
    }
}
