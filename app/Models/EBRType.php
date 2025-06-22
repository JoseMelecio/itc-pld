<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRType extends Model
{
    protected $table = 'ebr_types';

    protected $fillable = [
        'type',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];
}
