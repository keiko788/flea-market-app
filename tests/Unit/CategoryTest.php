<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /** @test */
    public function itemsリレーションを持つ(): void
    {
        $category = new Category;

        $relation = $category->items();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertInstanceOf(Item::class, $relation->getRelated());
    }
}
