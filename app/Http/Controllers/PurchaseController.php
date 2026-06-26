<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    // 商品購入画面を表示する
    public function show(Item $item_id)
    {
        $item = $item_id;
        $user = auth()->user();

        // 購入不可の商品は詳細画面へリダイレクト
        if ($item->user_id === $user->id || $item->purchase()->exists()) {
            return redirect()->route('items.show', $item->id);
        }

        $profile = $user->profile;

        return view('purchase.show', compact('item', 'user', 'profile'));
    }

    // 配送先住所変更画面を表示する
    public function editAddress(Item $item_id)
    {
        $item = $item_id;
        $profile = auth()->user()->profile;

        return view('purchase.address', compact('item', 'profile'));
    }

    // 配送先住所を更新
    public function updateAddress(AddressRequest $request, Item $item_id)
    {
        $item = $item_id;

        $validated = $request->validated();

        $validated['shipping_building'] = $request->input('shipping_building', '');

        session([
            'purchase_address' => $validated,
        ]);

        return redirect()->route('purchase.show', $item->id);
    }

    // 購入処理を実行し、購入情報を保存する
    public function store(PurchaseRequest $request, Item $item_id)
    {
        $item = $item_id;

        // 購入不可の商品は詳細画面へリダイレクト
        if ($item->user_id === auth()->id() || $item->purchase()->exists()) {
            return redirect()->route('items.show', $item->id);
        }

        $validated = $request->validated();

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $validated['payment_method'],
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'shipping_address' => $validated['shipping_address'],
            'shipping_building' => $request->input('shipping_building'),
        ]);

        session()->forget('purchase_address');

        return redirect()->route('items.index');
    }
}
