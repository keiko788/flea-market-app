<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    public const PAYMENT_CONVENIENCE_STORE = 1;
    public const PAYMENT_CARD = 2;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // 支払い方法のラベル取得
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            self::PAYMENT_CONVENIENCE_STORE => 'コンビニ払い',
            self::PAYMENT_CARD => 'カード支払い',
            default => '不明',
        };
    }
}
