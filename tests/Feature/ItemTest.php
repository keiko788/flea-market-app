<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 全商品を取得できる(): void
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get(route('items.index'));

        $response->assertOk();
        $response->assertViewIs('items.index');
        $response->assertViewHas(
            'items',
            fn ($viewItems) => $viewItems->count() === 3
        );

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /** @test */
    public function 購入済み商品は「_sold」と表示される(): void
    {
        $user = User::factory()->create();

        $soldItem = Item::factory()->create();
        $unsoldItem = Item::factory()->create();

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);

        $response = $this->get(route('items.index'));

        $html = $response->getContent();

        $this->assertStringContainsString(
            'data-testid="sold-'.$soldItem->id.'"',
            $html
        );
        $this->assertStringNotContainsString(
            'data-testid="sold-'.$unsoldItem->id.'"',
            $html
        );
    }

    /** @test */
    public function 自分が出品した商品は表示されない(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);
        Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
        ]);

        $response = $this->actingAs($user)->get(route('items.index'));

        $response->assertOk();
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }

    /** @test */
    public function マイリストではいいねした商品だけが表示される(): void
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create([
            'name' => 'いいねした商品',
        ]);
        $notLikedItem = Item::factory()->create([
            'name' => 'いいねしていない商品',
        ]);

        Like::factory()->create([
            'item_id' => $likedItem->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('items.index', ['tab' => 'mylist']));

        $response->assertOk();
        $response->assertDontSee('いいねしていない商品');
        $response->assertSee('いいねした商品');
    }

    /** @test */
    public function マイリストで購入済み商品は「_sold」と表示される(): void
    {
        $user = User::factory()->create();

        $likedUnsoldItem = Item::factory()->create();
        $likedSoldItem = Item::factory()->create();

        Like::factory()->create([
            'item_id' => $likedUnsoldItem->id,
            'user_id' => $user->id,
        ]);

        Like::factory()->create([
            'item_id' => $likedSoldItem->id,
            'user_id' => $user->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedSoldItem->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('items.index', ['tab' => 'mylist']));

        $html = $response->getContent();

        $this->assertStringContainsString(
            'data-testid="sold-'.$likedSoldItem->id.'"',
            $html
        );
        $this->assertStringNotContainsString(
            'data-testid="sold-'.$likedUnsoldItem->id.'"',
            $html
        );
    }

    /** @test */
    public function マイリストで未認証の場合は何も表示されない(): void
    {
        Item::factory()->count(3)->create();

        $response = $this->get(route('items.index', ['tab' => 'mylist']));

        $response->assertOk();
        $response->assertViewHas('items', function ($items) {
            return $items->isEmpty();
        });
    }

    /** @test */
    public function 「商品名」で部分一致検索ができる(): void
    {
        $hitItem = Item::factory()->create([
            'name' => '赤いバッグ',
        ]);

        $missItem = Item::factory()->create([
            'name' => '青い財布',
        ]);

        $response = $this->get(route('items.index', ['keyword' => '赤']));

        $response->assertOk();
        $response->assertSee($hitItem->name);
        $response->assertDontSee($missItem->name);
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている(): void
    {
        $user = User::factory()->create();

        $hitItem = Item::factory()->create([
            'name' => '赤いバッグ',
        ]);
        $missItem = Item::factory()->create([
            'name' => '青い財布',
        ]);

        Like::factory()->create([
            'item_id' => $hitItem->id,
            'user_id' => $user->id,
        ]);
        Like::factory()->create([
            'item_id' => $missItem->id,
            'user_id' => $user->id,
        ]);

        // ホームで検索
        $response = $this->actingAs($user)
            ->get(route('items.index', [
                'keyword' => '赤',
            ]));

        $response->assertOk();
        $response->assertSee($hitItem->name);
        $response->assertDontSee($missItem->name);

        // マイリストへ移動（keywordを保持）
        $response = $this->actingAs($user)
            ->get(route('items.index', [
                'tab' => 'mylist',
                'keyword' => '赤',
            ]));

        $response->assertOk();
        $response->assertSee($hitItem->name);
        $response->assertDontSee($missItem->name);

        $response->assertSee('value="赤"', false);
    }
}
