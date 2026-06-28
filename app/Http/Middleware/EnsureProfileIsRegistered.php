<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsRegistered
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // プロフィール未登録のユーザーをプロフィール編集画面に遷移
        $user = auth()->user();
        if (! $user->profile) {
            return redirect()->route('profile.edit');
        }

        return $next($request);
    }
}
