<?php

namespace Tests\Unit;

use App\Http\Requests\ProfileRequest;
use Tests\TestCase;

class ProfileRequestTest extends TestCase
{
    /** @test */
    public function authorizeはtrueを返す(): void
    {
        $request = new ProfileRequest;

        $this->assertTrue($request->authorize());
    }

    /** @test */
    public function rulesが正しい(): void
    {
        $request = new ProfileRequest;

        $this->assertEquals([
            'profile_image_path' => 'image|mimes:jpeg,png',
            'name' => 'required|max:20',
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required',
        ], $request->rules());
    }
}
