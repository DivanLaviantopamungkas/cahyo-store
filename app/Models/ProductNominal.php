<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductNominal extends Model
{
    use HasFactory;

    protected $table = 'product_nominals';

    protected $fillable = [
        'product_id',
        'name',
        'provider_sku',    // (patch) SKU DigiFlazz
        'price',
        'discount_price',
        'cost_price',      // (patch) harga modal/provider
        'margin',          // (patch)
        'stock',
        'available_stock',
        'stock_mode',      // (patch) manual / provider
        'is_active',
        'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'margin' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function voucherCodes(): HasMany
    {
        return $this->hasMany(VoucherCode::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function productNominals()
    {
        return $this->hasMany(ProductNominal::class, 'product_id');
    }
}
