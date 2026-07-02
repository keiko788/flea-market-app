<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itemリレーションを持つ(): void
    {
        $purchase = new Purchase;

        $relation = $purchase->item();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Item::class, $relation->getRelated());
    }

    /** @test */
    public function userリレーションを持つ(): void
    {
        $purchase = new Purchase;

        $relation = $purchase->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }
}
