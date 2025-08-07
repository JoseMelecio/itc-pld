<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRRiskElementIndicator extends Model
{
    protected $table = 'ebr_risk_element_indicators';

    protected $fillable = [
        'ebr_risk_element_id',
        'characteristic',
        'key',
        'name',
        'description',
        'risk_indicator',
        'order'
    ];
}
