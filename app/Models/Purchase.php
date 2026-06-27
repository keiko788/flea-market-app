<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'shipping_postal_code',
        'shipping_address',
        'shipping_building',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getStripePaymentMethodAttribute(): string
    {
        return match ($this->payment_method) {
            '1' => 'konbini',
            '2' => 'card',
            default => 'card',
        };
    }
}
