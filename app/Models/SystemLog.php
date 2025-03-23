<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'model_type',
        'model_id',
        'type',
        'content'
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
