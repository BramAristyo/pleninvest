<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetWorth extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'savings',
        'emergency_fund',
        'investments',
        'other_assets',
        'debt',
    ];

    protected $casts = [
        'savings' => 'decimal:2',
        'emergency_fund' => 'decimal:2',
        'investments' => 'decimal:2',
        'other_assets' => 'decimal:2',
        'debt' => 'decimal:2',
    ];

    /**
     * Get the user that owns the net worth entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
