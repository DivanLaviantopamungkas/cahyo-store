<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trancsaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'invoice',
        'user_id',
        'amount',
        'total_paid',
        'payment_method',     // qris/bank_transfer/ewallet/balance
        'payment_provider',   // (patch) tokopay/midtrans
        'payment_reference',
        'reff_id',            // (patch) reff_id tokopay
        'payment_url',
        'payment_payload',    // (patch)
        'status',             // pending/paid/processing/completed/expired/cancelled
        'paid_at',
        'completed_at',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }
}
