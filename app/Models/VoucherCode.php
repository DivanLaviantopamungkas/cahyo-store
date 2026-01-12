<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoucherCode extends Model
{
    use HasFactory;

    protected $table = 'voucher_codes';

    protected $fillable = [
        'product_id',
        'product_nominal_id',
        'code',
        'secret',
        'status',      // available/reserved/sold/expired
        'expired_at',
        'sold_to',
        'sold_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'sold_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function nominal(): BelongsTo
    {
        return $this->belongsTo(ProductNominal::class, 'product_nominal_id');
    }

    public function soldTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_to');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
