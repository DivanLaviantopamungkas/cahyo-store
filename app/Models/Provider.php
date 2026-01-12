<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Encryption\DecryptException;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';

    protected $fillable = [
        'name',
        'code',
        'type',
        'credentials',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    // JANGAN cast credentials di sini, gunakan accessor
    // protected $casts = [
    //     'credentials' => 'encrypted:array', // HAPUS INI
    //     'settings' => 'array',
    //     'is_active' => 'boolean',
    // ];

    // Accessor untuk credentials dengan decrypt
    public function getCredentialsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        try {
            // Coba decrypt
            $decrypted = decrypt($value);
            return is_array($decrypted) ? $decrypted : [];
        } catch (DecryptException $e) {
            // Jika gagal decrypt, coba parse sebagai JSON
            try {
                return json_decode($value, true) ?? [];
            } catch (\Exception $e) {
                return [];
            }
        }
    }

    // Mutator untuk encrypt credentials
    public function setCredentialsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['credentials'] = encrypt($value);
        } else {
            $this->attributes['credentials'] = null;
        }
    }

    public function products(): HasMany
    {
        return $this->providerProducts();
    }

    public function providerProducts(): HasMany
    {
        return $this->hasMany(ProviderProduct::class);
    }

    // Helper methods
    public function isDigiflazz(): bool
    {
        return $this->code === 'digiflazz';
    }

    public function isTokopay(): bool
    {
        return $this->code === 'tokopay';
    }

    // Get credential dengan fallback
    public function getCredential(string $key, $default = null)
    {
        $credentials = $this->credentials;
        return $credentials[$key] ?? $default;
    }

    // Get setting dengan fallback
    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }
}
