<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function userリレーションを持つ(): void
    {
        $like = new Like;

        $relation = $like->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    /** @test */
    public function itemリレーションを持つ(): void
    {
        $like = new Like;

        $relation = $like->item();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Item::class, $relation->getRelated());
    }
}
