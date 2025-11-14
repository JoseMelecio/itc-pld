<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRRiskElementRelatedAverage extends Model
{
    protected $table = 'ebr_risk_element_related_averages';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'ebr_id',
        'ebr_risk_element_id',
        'average_risk_inherent_concentration',
        'average_risk_level_features',
        'average_risk_level_integrated',
        'weight_impact_range_header',
        'frequency_range_header',
        'risk_inherent_concentration_header',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = uniqid('', true);
            }
        });
    }
}
