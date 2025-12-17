<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EBRConfiguration extends Model
{
    protected $table = 'ebr_configurations';

    protected $fillable = [
        'user_id',
        'template_clients_config',
        'template_operations_config'
    ];

    protected function casts(): array
    {
        return [
            'template_clients_config' => 'array',
            'template_operations_config' => 'array'
        ];
    }

    public function riskElements(): BelongsToMany
    {
        return $this->belongsToMany(
            EBRRiskElement::class,
            'ebr_configuration_risk_element',
            'ebr_configuration_id',
            'risk_element_id'
        );
    }

    public function riskIndicators()
    {
        return $this->belongsToMany(
            EBRRiskElementIndicator::class,
            'ebr_configuration_risk_element_indicators',
            'ebr_configuration_id',
            'risk_indicator_id'
        );
    }
}
