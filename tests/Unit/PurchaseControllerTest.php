<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 自分の商品は購入処理できない(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => '1',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区新宿1-2-3',
        ]);

        $response->assertRedirect(route('items.show', $item->id));
        $this->assertDatabaseCount('purchases', 0);
    }

    /** @test */
    public function 「_sold」の商品は購入できない(): void
    {
        $user = User::factory()->create();
        $buyer = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        Purchase::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get(route('purchase.show', $item->id));

        $response->assertRedirect(route('items.show', $item->id));
        $this->assertDatabaseCount('purchases', 1);
    }

    /** @test */
    public function 配送先住所変更画面を表示できる(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('purchase.edit.address', $item->id));

        $response->assertOk();
        $response->assertViewIs('purchases.address');
    }

    /** @test */
    public function カード支払いを選択して購入手続きを開始できる(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => '2',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区新宿1-2-3',
            'shipping_building' => '新宿ビル',
        ]);

        $response->assertSessionHas('purchase');
        $response->assertStatus(302);
    }
}
