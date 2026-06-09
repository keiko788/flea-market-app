<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 名前が入力されていない場合、バリデーションメッセージが表示される(): void
    {
        $this->get(route('register'))->assertOk();

        $response = $this->followingRedirects()
            ->post(route('register.store'), [
                'name' => '',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSee('お名前を入力してください');
    }

    /** @test */
    public function メールアドレスが入力されていない場合、バリデーションメッセージが表示される(): void
    {
        $this->get(route('register'))->assertOk();

        $response = $this->followingRedirects()
            ->post(route('register.store'), [
                'name' => 'テスト太郎',
                'email' => '',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSee('メールアドレスを入力してください');
    }

    /** @test */
    public function パスワードが入力されていない場合、バリデーションメッセージが表示される(): void
    {
        $this->get(route('register'))->assertOk();

        $response = $this->followingRedirects()
            ->post(route('register.store'), [
                'name' => 'テスト太郎',
                'email' => 'test@example.com',
                'password' => '',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSee('パスワードを入力してください');

    }

    /** @test */
    public function パスワードが7文字以下の場合、バリデーションメッセージが表示される(): void
    {
        $this->get(route('register'))->assertOk();

        $response = $this->followingRedirects()
            ->post(route('register.store'), [
                'name' => 'テスト太郎',
                'email' => 'test@example.com',
                'password' => 'short',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSee('パスワードは8文字以上で入力してください');
    }

    /** @test */
    public function パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される(): void
    {
        $this->get(route('register'))->assertOk();

        $response = $this->followingRedirects()
            ->post(route('register.store'), [
                'name' => 'テスト太郎',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSee('パスワードと一致しません');

    }

    /** @test */
    // public function 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される(): void
    // {
    //     $this->get(route('register'))->assertOk();

    //     $response = $this->post(route('register.store'), [
    //         'name' => '',
    //         'email' => 'test@example.com',
    //         'password' => 'password123',
    //         'password_confirmation' => 'password123',
    //     ]);

    //     $response->assertSessionHasErrors([
    //         'name' => 'お名前を入力してください',
    //     ]);
    // }
}
