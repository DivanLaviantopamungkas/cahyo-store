<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentWebhook extends Model
{
    protected $table = 'payment_webhooks';

    public $timestamps = false;

    protected $fillable = [
        'provider',
        'reference',
        'event',
        'status',
        'payload',
        'signature_valid',
        'received_at',
        'processed_at',
        'process_status',
        'error',
    ];

    protected $casts = [
        'signature_valid' => 'boolean',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
