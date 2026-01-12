<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    use HasFactory;

    protected $table = 'transaction_items';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'product_nominal_id',
        'voucher_code_id',
        'quantity',
        'price',
        'total',
        'status',              // pending/processing/completed/cancelled

        'fulfillment_source',  // (patch) manual/digiflazz
        'provider_trx_id',      // (patch)
        'provider_status',      // (patch)
        'provider_rc',          // (patch)
        'provider_message',     // (patch)
        'sn',                   // (patch)
        'raw_response',         // (patch)

        'delivered_at',
        'delivery_attempts',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'delivered_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Trancsaction::class, 'transaction_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function nominal(): BelongsTo
    {
        return $this->belongsTo(ProductNominal::class, 'product_nominal_id');
    }

    public function voucherCode(): BelongsTo
    {
        return $this->belongsTo(VoucherCode::class);
    }
}
