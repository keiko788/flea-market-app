<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        // ログインフォームのビューを指定
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // ユーザー登録フォームのビューを指定
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // 会員登録後、メール認証誘導画面に遷移
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse
            {
                public function toResponse($request)
                {
                    return redirect()->route('verification.notice');
                }
            };
        });

        // メール認証完了後、プロフィール編集画面へ遷移
        $this->app->singleton(VerifyEmailResponse::class, function () {
            return new class implements VerifyEmailResponse
            {
                public function toResponse($request)
                {
                    return redirect()->route('profile.edit');
                }
            };
        });
    }
}
