<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'provider_id',
        'name',
        'slug',
        'description',
        'image',
        'type',
        'source',      // manual / digiflazz
        'provider_sku', // SKU dari provider jika source=digiflazz
        'is_active',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function nominals(): HasMany
    {
        return $this->hasMany(ProductNominal::class);
    }

    public function voucherCodes(): HasMany
    {
        return $this->hasMany(VoucherCode::class);
    }

    public function product_nominals()
    {
        return $this->hasMany(ProductNominal::class, 'product_id');
    }


    // Scope untuk filter
    public function scopeManual($query)
    {
        return $query->where('source', 'manual');
    }

    public function scopeDigiflazz($query)
    {
        return $query->where('source', 'digiflazz');
    }
}
