<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRRiskElementIndicator extends Model
{
    protected $table = 'ebr_risk_element_indicators';

    protected $fillable = [
        'characteristic',
        'key',
        'name',
        'description',
        'report_config',
        'risk_indicator',
        'order'
    ];

    protected function casts(): array
    {
        return [
            'report_config' => 'array'
        ];
    }
}
