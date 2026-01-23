<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderProduct extends Model
{
    use HasFactory;

    protected $table = 'provider_products';

    protected $fillable = [
        'provider_id',
        'provider_sku',     // buyer_sku_code (Digiflazz)
        'name',
        'category',
        'brand',
        'description',
        'provider_price',
        'is_available',
        'details',
        'last_sync_at',
    ];

    protected $casts = [
        'provider_price' => 'decimal:2',
        'is_available' => 'boolean',
        'details' => 'array',
        'last_sync_at' => 'datetime',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    // Kalau Anda mau: 1 provider SKU bisa di-import jadi banyak product_nominals (mis. beda markup)
    public function productNominals(): HasMany
    {
        return $this->hasMany(ProductNominal::class, 'provider_sku', 'provider_sku');
        // note: ini hanya aman kalau provider_id juga sama.
        // Lebih aman relasi lewat provider_product_id (foreign key) kalau Anda buat kolom itu.
    }
}
