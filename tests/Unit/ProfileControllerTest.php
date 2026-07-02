<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール画像ありでもプロフィールを更新できる(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image_path' => 'profiles/old.jpg',
        ]);

        $image = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => '更新ユーザー',
            'postal_code' => '987-6543',
            'address' => '東京都渋谷区渋谷4-5-6',
            'building' => '渋谷ビル',
            'profile_image' => $image,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '更新ユーザー',
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'postal_code' => '987-6543',
            'address' => '東京都渋谷区渋谷4-5-6',
            'building' => '渋谷ビル',
        ]);

        $profile = $user->fresh()->profile;

        $this->assertNotEquals('profiles/old.jpg', $profile->profile_image_path);

        Storage::disk('public')->assertExists($profile->profile_image_path);
    }

    /** @test */
    public function プロフィール画像なしでプロフィールを更新できる(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image_path' => 'profiles/old.jpg',
        ]);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => '画像なし更新ユーザー',
            'postal_code' => '111-2222',
            'address' => '東京都港区1-2-3',
            'building' => '港ビル',
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'profile_image_path' => 'profiles/old.jpg',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '画像なし更新ユーザー',
        ]);
    }

    /** @test */
    public function マイページからプロフィールを更新した場合マイページにリダイレクトされる(): void
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => '更新ユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'redirect_to' => 'mypage',
        ]);

        $response->assertRedirect(route('mypage.index'));
    }
}
