<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログアウトができる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('items.index'));
        $this->assertGuest();
    }

    /** @test */
    public function 未ログインユーザーはログアウトできない(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
    }
}
