<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;

class EBRRiskElement extends Model
{
    const DEFAULT_REPORT_RULES = [
        "selects" => [
            [
                "type" => "sum",
                "value" => "ebr_operations.monto_operacion",
                "alias" => "amount_mxn"
            ],
            [
                "type" => "count_distinct",
                "value" => "ebr_clients.id",
                "alias" => "total_clients"
            ],
            [
                "type" => "count",
                "value" => "ebr_operations.id",
                "alias" => "total_operations"
            ]
        ],
        "wheres" => [],
        "orders" => []
    ];

    const DONT_SHOW_FILES_IN_EXCEL = ['id', 'ebr_id', 'created_at', 'updated_at'];

    protected $table = 'ebr_risk_elements';

    protected $fillable = [
        'id',
        'risk_element',
        'lateral_header',
        'sub_header',
        'description',
        'report_config',
        'active'
    ];

    protected function casts(): array
    {
        return [
            'report_config' => 'array',
            'active' => 'boolean'
        ];
    }

    public function calculate(int $ebr_id): Boolean|Collection
    {
        $ebr = EBR::find($ebr_id);
        if (!$ebr) {
            return false;
        }

        return $ebr->join('ebr_clients', function ($query) use ($ebr) {
            $query->on('ebr_clients.ebr_id', '=', 'ebrs.id')
                ->where('ebrs.id', '=', $ebr->id);
        })->join('ebr_operations', function ($query) {
            $query->on('ebr_operations.ebr_id', '=', 'ebrs.id')
                ->on('ebr_operations.ebr_client_id', '=', 'ebr_clients.id');
        })->selectRaw(
            'ebr_' . $this->entity_grouper . '.' . $this->variable_grouper . ' as risk_element,' .
            'SUM(ebr_operations.operation_amount) as amount_mxn,' .
            'COUNT(DISTINCT ebr_clients.id) as total_clients, ' .
            'COUNT(ebr_operations.id) as total_operations'
        )
        ->groupBy('ebr_' . $this->entity_grouper . '.' . $this->variable_grouper)
        ->get();
    }

    public function riskElementRelated(): HasMany
    {
        return $this->hasMany(EBRRiskElementRelated::class, 'ebr_risk_element_id');
    }

    public function riskIndicatorRelated(): HasMany
    {
        return $this->hasMany(EBRRiskElementIndicator::class, 'ebr_risk_element_id');

    }
}
