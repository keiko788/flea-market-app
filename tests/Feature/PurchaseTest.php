<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 「購入する」ボタンを押下すると_stripeへ遷移する(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('purchase.show', $item->id));

        $response->assertOk();

        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => '1',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区新宿1-2-3',
            'shipping_building' => '新宿ビル',
        ]);

        $response->assertSessionHas('purchase');

        $response->assertStatus(302);

        $location = $response->headers->get('Location');
        $this->assertStringContainsString('checkout.stripe.com', $location);
    }

    /** @test */
    public function 決済完了後_購入が完了する(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        session([
            'purchase' => [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => '1',
                'shipping_postal_code' => '123-4567',
                'shipping_address' => '東京都新宿区新宿1-2-3',
                'shipping_building' => '新宿ビル',
            ],
        ]);

        $response = $this->actingAs($user)->get(route('purchase.success', $item->id));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_address' => '東京都新宿区新宿1-2-3',
        ]);

        $response->assertRedirect(route('items.index'));
    }

    /** @test */
    public function 購入した商品は商品一覧画面にて「sold」と表示される(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('purchase.show', $item->id));

        $response->assertOk();

        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => '1',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区新宿1-2-3',
            'shipping_building' => '新宿ビル',
        ]);

        $response->assertSessionHas('purchase');
        $response->assertStatus(302);

        // Stripe決済完了後の戻りを再現
        $response = $this->actingAs($user)->get(route('purchase.success', $item->id));

        $response->assertRedirect(route('items.index'));

        $response = $this->get(route('items.index'));

        $response->assertOk();

        // Sold表示確認
        $response->assertSee('data-testid="sold-'.$item->id.'"', false);
    }

    /** @test */
    public function 「プロフィール_購入した商品一覧」に購入した商品が追加される(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create([
            'name' => '購入した商品',
        ]);

        $response = $this->actingAs($user)->get(route('purchase.show', $item->id));

        $response->assertOk();

        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => '1',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区新宿1-2-3',
            'shipping_building' => '新宿ビル',
        ]);

        $response->assertSessionHas('purchase');
        $response->assertStatus(302);

        // Stripe決済完了後の戻りを再現
        $response = $this->actingAs($user)->get(route('purchase.success', $item->id));

        $response->assertRedirect(route('items.index'));

        $response = $this->actingAs($user)->get(route('mypage.index', ['page' => 'buy']));

        $response->assertOk();
        $response->assertSee('購入した商品');
    }

    /** @test */
    public function 小計画面で支払い方法変更が反映される(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('purchase.show', $item->id));

        $response->assertOk();

        $response = $this->actingAs($user)->get(route('purchase.show', [
            'item_id' => $item->id,
            'payment_method' => '2',
        ]));

        $response->assertOk();
        $response->assertSee('data-testid="payment-method-summary"', false);
        $response->assertSee('カード支払い');
    }

    /** @test */
    public function 送付先住所変更画面にて登録した住所が商品購入画面に反映されている(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区新宿1-2-3',
            'building' => '新宿ビル',
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->patch(
            route('purchase.update.address', $item->id),
            [
                'shipping_postal_code' => '987-6543',
                'shipping_address' => '東京都渋谷区渋谷4-5-6',
                'shipping_building' => '渋谷ビル',
            ]
        );

        $response->assertRedirect(route('purchase.show', $item->id));
        $response = $this->actingAs($user)->get(route('purchase.show', $item->id));

        $response->assertOk();
        $response->assertSee('987-6543');
        $response->assertSee('東京都渋谷区渋谷4-5-6');
        $response->assertSee('渋谷ビル');
        $response->assertDontSee('東京都新宿区新宿1-2-3');
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区新宿1-2-3',
            'building' => '新宿ビル',
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->patch(
            route('purchase.update.address', $item->id),
            [
                'shipping_postal_code' => '987-6543',
                'shipping_address' => '東京都渋谷区渋谷4-5-6',
                'shipping_building' => '渋谷ビル',
            ]
        );

        $response->assertRedirect(route('purchase.show', $item->id));
        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => '1',
            'shipping_postal_code' => '987-6543',
            'shipping_address' => '東京都渋谷区渋谷4-5-6',
            'shipping_building' => '渋谷ビル',
        ]);

        $response->assertSessionHas('purchase');
        $response->assertStatus(302);

        // Stripe決済完了後の戻りを再現
        $response = $this->actingAs($user)->get(route('purchase.success', $item->id));

        $response->assertRedirect(route('items.index'));
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '987-6543',
            'shipping_address' => '東京都渋谷区渋谷4-5-6',
            'shipping_building' => '渋谷ビル',
        ]);
    }
}
