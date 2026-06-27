<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Checkout\Session;
use Stripe\Stripe;

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

        // 購入情報を登録
        $purchase = Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $validated['payment_method'],
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'shipping_address' => $validated['shipping_address'],
            'shipping_building' => $request->input('shipping_building'),
        ]);

        session()->forget('purchase_address');

        // Stripe APIキーを設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // Stripe決済画面へ遷移するためのCheckoutセッションを作成
        $checkoutSession = Session::create([
            'payment_method_types' => [$purchase->stripe_payment_method],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('items.index'),
            'cancel_url' => route('purchase.show', $item->id),
        ]);

        return redirect($checkoutSession->url);
    }
}
