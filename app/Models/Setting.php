<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
        'options',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'order' => 'integer',
    ];
}
