<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRRiskElementRelated extends Model
{
    protected $table = 'ebr_risk_element_related';

    protected $fillable = [
        'id',
        'ebr_id',
        'ebr_risk_element_id',
        'element',
        'amount_mxn',
        'total_clients',
        'total_operations',
        'weight_range_impact',
        'frequency_range_impact',
        'risk_inherent_concentration',
        'risk_level_features',
        'risk_level_integrated',
    ];
}
