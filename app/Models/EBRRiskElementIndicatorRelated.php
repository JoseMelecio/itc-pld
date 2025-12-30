<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRRiskElementIndicatorRelated extends Model
{
    protected $table = 'ebr_risk_element_indicator_related';

    protected $fillable = [
        'ebr_id',
        'ebr_risk_element_id',
        'characteristic',
        'key',
        'name',
        'description',
        'risk_indicator',
        'order',
        'amount',
        'related_clients',
        'related_operations',
        'weight_range_impact',
        'frequency_range_impact',
        'characteristic_concentration',
    ];
}
