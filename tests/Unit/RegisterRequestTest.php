<?php

namespace Tests\Unit;

use App\Http\Requests\RegisterRequest;
use Tests\TestCase;

class RegisterRequestTest extends TestCase
{
    /** @test */
    public function authorizeはtrueを返す(): void
    {
        $request = new RegisterRequest;

        $this->assertTrue($request->authorize());
    }
}
