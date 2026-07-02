<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品詳細画面にて商品基本情報が表示される(): void
    {
        $item = Item::factory()->create([
            'name' => '赤いバッグ',
            'description' => 'テスト用の赤いトートバッグです。',
            'image_path' => 'items/test.jpg',
            'condition' => 1,
            'price' => 4000,
            'brand_name' => 'Test Brand',
        ]);

        $response = $this->get(route('items.show', $item->id));

        $response->assertOk();
        $response->assertSee('赤いバッグ');
        $response->assertSee('テスト用の赤いトートバッグです。');
        $response->assertSee('storage/items/test.jpg');
        $response->assertSee('良好');
        $response->assertSee('4,000');
        $response->assertSee('Test Brand');
    }

    /** @test */
    public function 商品詳細画面でいいね数とコメント数が表示される(): void
    {
        $item = Item::factory()->create();
        Like::factory()->create([
            'item_id' => $item->id,
        ]);
        Comment::factory()->create([
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('items.show', $item->id));

        $html = $response->getContent();

        $this->assertStringContainsString(
            'data-testid="likes-count-'.$item->id.'">1',
            $html
        );

        $this->assertStringNotContainsString(
            'data-testid="comments-count'.$item->id.'">1',
            $html
        );
    }

    /** @test */
    public function 商品詳細画面でコメント情報が表示される(): void
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image_path' => 'profiles/test.jpg',
        ]);
        $item = Item::factory()->create();

        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'テストコメント',
        ]);

        $response = $this->get(route('items.show', $item->id));

        $response->assertOk();
        $response->assertSee('コメント(1)');
        $response->assertSee($user->name);
        $response->assertSee('storage/profiles/test.jpg');
        $response->assertSee('テストコメント');
    }

    /** @test */
    public function 複数選択されたカテゴリが表示されている(): void
    {
        $item = Item::factory()->create();

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $item->categories()->attach([
            $category1->id,
            $category2->id,
        ]);

        $response = $this->get(route('items.show', $item->id));

        $response->assertOk();
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
    }

    /** @test */
    public function いいねアイコンを押下することによって、いいねした商品として登録することができる(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertOk();
        $response->assertSee('data-testid="likes-count-'.$item->id.'">0', false);

        $response = $this->actingAs($user)->post(route('likes.store', $item->id));

        $this->assertDatabaseHas('likes', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('items.show', $item->id));

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertSee('data-testid="likes-count-'.$item->id.'">1', false);
    }

    /** @test */
    public function いいね追加済みのアイコンは色が変化する(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertOk();
        $response->assertSee('images/likes.svg');

        $response = $this->actingAs($user)->post(route('likes.store', $item->id));

        $this->assertDatabaseHas('likes', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('items.show', $item->id));

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertSee('images/likes-active.svg');
    }

    /** @test */
    public function 再度いいねアイコンを押下することによって、いいねを解除することができる(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();
        Like::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertOk();
        $response->assertSee('data-testid="likes-count-'.$item->id.'">1', false);

        $response = $this->actingAs($user)->delete(route('likes.destroy', $item->id));

        $this->assertDatabaseMissing('likes', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('items.show', $item->id));

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertSee('data-testid="likes-count-'.$item->id.'">0', false);
    }

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertOk();
        $response->assertSee('コメント(0)');

        $response = $this->actingAs($user)->post(route('comments.store', $item->id), [
            'body' => 'テストコメントです',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'テストコメントです',
        ]);

        $response->assertRedirect(route('items.show', $item->id));

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertOk();
        $response->assertSee('コメント(1)');
        $response->assertSee('テストコメントです');
        $response->assertSee($user->name);
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない(): void
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comments.store', $item->id), [
            'body' => 'テストコメントです',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'body' => 'テストコメントです',
        ]);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function コメントが入力されていない場合、バリデーションメッセージが表示される(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('comments.store', $item->id), [
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['body']);
    }

    /** @test */
    public function コメントが255字以上の場合、バリデーションメッセージが表示される(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('comments.store', $item->id), [
            'body' => str_repeat('あ', 256),
        ]);

        $response->assertSessionHasErrors(['body']);
    }
}
