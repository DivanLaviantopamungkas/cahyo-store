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

        // ✅ VOUCHER FIELDS (baru ditambah)
        'voucher_code',
        'qr_code_url',
        'expired_at',
        'completed_at',

        'fulfillment_source',  // manual/digiflazz/voucher_stock
        'provider_trx_id',
        'provider_status',
        'provider_rc',
        'provider_message',
        'sn',
        'raw_response',

        'delivered_at',
        'delivery_attempts',
    ];

    // ✅ FIX 1: CAST DATES & DECIMALS
    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'delivered_at' => 'datetime',
        'expired_at' => 'datetime',     // ✅ FIX WhatsAppService format()
        'completed_at' => 'datetime',    // ✅ Carbon instance otomatis
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ✅ FIX 2: Transaction (bukan Trancsaction!)
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
        return $this->belongsTo(VoucherCode::class, 'voucher_code_id');
    }
}
