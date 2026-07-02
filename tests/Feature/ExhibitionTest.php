<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品出品画面にて必要な情報が保存できている(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $category = Category::factory()->create();

        $response = $this->actingAs($user)->get(route('items.create'));

        $response->assertOk();

        $image = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($user)->post(route('items.store'), [
            'name' => '赤いバッグ',
            'description' => 'テスト用の赤いトートバッグです。',
            'image_path' => $image,
            'condition' => 1,
            'price' => 4000,
            'brand_name' => 'Test Brand',
            'category_ids' => [$category->id],
        ]);

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => '赤いバッグ',
            'description' => 'テスト用の赤いトートバッグです。',
            'condition' => 1,
            'price' => 4000,
            'brand_name' => 'Test Brand',
        ]);

        $createdItem = Item::where('name', '赤いバッグ')->first();

        $this->assertDatabaseHas('category_item', [
            'category_id' => $category->id,
            'item_id' => $createdItem->id,
        ]);

        $response->assertRedirect(route('mypage.index'));
    }
}
