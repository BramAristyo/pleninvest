<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'purchase_date',
        'buy_price',
        'quantity',
        'ticker',
        'note',
        'current_price',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'buy_price' => 'decimal:2',
        'quantity' => 'decimal:4',
        'current_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the investment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
