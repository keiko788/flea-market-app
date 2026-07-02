<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class ItemTest extends TestCase
{
    /** @test */
    public function userリレーションを持つ(): void
    {
        $item = new Item;

        $relation = $item->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }
}
