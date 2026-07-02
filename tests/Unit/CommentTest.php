<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itemリレーションを持つ(): void
    {
        $comment = new Comment;

        $relation = $comment->item();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Item::class, $relation->getRelated());
    }
}
