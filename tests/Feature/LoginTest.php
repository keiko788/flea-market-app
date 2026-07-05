<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function メールアドレスが入力されていない場合、バリデーションメッセージが表示される(): void
    {
        $this->get(route('login'))->assertOk();

        $response = $this->followingRedirects()
            ->post('/login', [
                'email' => '',
                'password' => 'password123',
            ]);

        $response->assertSee('メールアドレスを入力してください');
    }

    /** @test */
    public function パスワードが入力されていない場合、バリデーションメッセージが表示される(): void
    {
        $this->get(route('login'))->assertOk();

        $response = $this->followingRedirects()
            ->post('/login', [
                'email' => 'test@example.com',
                'password' => '',
            ]);

        $response->assertSee('パスワードを入力してください');
    }

    /** @test */
    public function 入力情報が間違っている場合、バリデーションメッセージが表示される(): void
    {
        User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'taro@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->get(route('login'))->assertOk();

        $response = $this->followingRedirects()
            ->post('/login', [
                'email' => 'jiro@example.com',
                'password' => 'password',
            ]);

        $response->assertSee('ログイン情報が登録されていません');
    }

    /** @test */
    public function 正しい情報が入力された場合、ログイン処理が実行される(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->get(route('login'))->assertOk();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
