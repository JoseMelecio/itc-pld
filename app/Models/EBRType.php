<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function riskElements(): HasMany
    {
        return $this->hasMany(EBRRiskElement::class, 'ebr_type_id');
    }
}
