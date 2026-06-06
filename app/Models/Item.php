<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    public const CONDITION_GOOD = 1;
    public const CONDITION_NO_DAMAGE = 2;
    public const CONDITION_SOME_DAMAGE = 3;
    public const CONDITION_BAD = 4;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'image_path',
        'condition',
        'price',
        'brand_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // 商品の状態のラベル取得
    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            self::CONDITION_GOOD => '良好',
            self::CONDITION_NO_DAMAGE => '目立った傷や汚れなし',
            self::CONDITION_SOME_DAMAGE => 'やや傷や汚れあり',
            self::CONDITION_BAD => '状態が悪い',
            default => '不明',
        };
    }
}
