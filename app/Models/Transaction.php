<?php

namespace App\Models;

use App\Models\Enums\TransactionAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'action' => TransactionAction::class,
    ];

    public function scopeDeposits(Builder $query): Builder
    {
        return $query->where('action', TransactionAction::Deposit);
    }

    public function scopeWithdraws(Builder $query): Builder
    {
        return $query->where('action', TransactionAction::Withdraw);
    }
}
