<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'pin',
    ];

    protected $hidden = [
        'pin',
        'remember_token',
    ];

    /**
     * Get the transactions for the user.
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the net worth entries for the user.
     */
    public function netWorths(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NetWorth::class);
    }

    /**
     * Get the investments for the user.
     */
    public function investments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function reimbursements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reimbursement::class);
    }

    public function insurances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Insurance::class);
    }

    public function benefits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Benefit::class);
    }
}
