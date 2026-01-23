<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Find or create category by name
     */
    public static function findOrCreate($name, $order = 0)
    {
        $slug = Str::slug($name);

        // Cek apakah kategori sudah ada
        $category = self::where('slug', $slug)->first();

        if ($category) {
            return $category;
        }

        // Buat kategori baru
        return self::create([
            'name' => $name,
            'slug' => $slug,
            'description' => "Kategori untuk {$name}",
            'image' => null,
            'is_active' => true,
            'order' => $order,
        ]);
    }

    /**
     * Get top-level categories (without parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
