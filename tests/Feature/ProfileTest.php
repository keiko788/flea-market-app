<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール画面にてプロフィール画像、ユーザー名を取得できる(): void
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image_path' => 'profiles/test.jpg',
        ]);

        $response = $this->actingAs($user)->get(route('mypage.index'));

        $response->assertOk();
        $response->assertViewIs('mypage.index');
        $response->assertViewHas('profile');
        $response->assertSee('テストユーザー');
        $response->assertSee('storage/profiles/test.jpg');
    }

    /** @test */
    public function プロフィール画面にて出品した商品一覧が取得できる(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        Item::factory()->count(3)->sequence(
            ['user_id' => $user->id, 'name' => '出品商品1'],
            ['user_id' => $user->id, 'name' => '出品商品2'],
            ['user_id' => $user->id, 'name' => '出品商品3'],
        )->create();

        Item::factory()->count(2)->sequence(
            ['name' => '他人の商品1'],
            ['name' => '他人の商品2'],
        )->create();

        $response = $this->actingAs($user)->get(route('mypage.index', ['page' => 'sell']));

        $response->assertOk();
        $response->assertViewHas('listedItems', function ($items) {
            return $items->count() === 3;
        });
        $response->assertSee('出品商品1');
        $response->assertDontSee('他人の商品1');
    }

    /** @test */
    public function プロフィール画面にて購入した商品一覧が取得できる(): void
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);
        Profile::factory()->create([
            'user_id' => $seller->id,
        ]);

        $purchasedItems = Item::factory()->count(3)->sequence(
            ['user_id' => $seller->id, 'name' => '購入商品1'],
            ['user_id' => $seller->id, 'name' => '購入商品2'],
            ['user_id' => $seller->id, 'name' => '購入商品3'],
        )->create();

        $purchasedItems->each(function ($item) use ($user) {
            Purchase::factory()->create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        });

        Item::factory()->count(2)->sequence(
            ['name' => '他の商品1'],
            ['name' => '他の商品2'],
        )->create();

        $response = $this->actingAs($user)->get(route('mypage.index', ['page' => 'buy']));

        $response->assertOk();
        $response->assertViewHas('purchasedItems', function ($items) {
            return $items->count() === 3;
        });
        $response->assertSee('購入商品1');
        $response->assertDontSee('他の商品1');
    }

    /** @test */
    public function プロフィール画面からプロフィール編集画面に遷移した際、変更項目が初期値として設定されている(): void
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image_path' => 'profiles/test.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区新宿1-2-3',
            'building' => '新宿ビル',
        ]);

        $response = $this->actingAs($user)->get(route('mypage.index'));

        $response->assertOk();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee($user->name);
        $response->assertSee('storage/profiles/test.jpg');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区新宿1-2-3');
        $response->assertSee('新宿ビル');
    }
}
