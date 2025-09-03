<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRRiskZone extends Model
{
    protected $table = 'ebr_risk_zones';

    protected $fillable = [
        'risk_zone',
        'incidence_of_crime',
        'percentage_1',
        'percentage_2',
        'zone',
        'color'
    ];
}
