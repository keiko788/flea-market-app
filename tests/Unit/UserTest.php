<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function likesリレーションを持つ(): void
    {
        $user = new User;

        $relation = $user->likes();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Like::class, $relation->getRelated());
    }

    /** @test */
    public function commentsリレーションを持つ(): void
    {
        $user = new User;

        $relation = $user->comments();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Comment::class, $relation->getRelated());
    }

    /** @test */
    public function purchasesリレーションを持つ(): void
    {
        $user = new User;

        $relation = $user->purchases();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Purchase::class, $relation->getRelated());
    }
}
