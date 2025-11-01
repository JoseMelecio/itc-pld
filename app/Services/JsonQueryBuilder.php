<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JsonQueryBuilder
{
    protected $data;
    protected $ebr_id;

    public function __construct(array $data, $ebr_id)
    {
        $this->data = $data;
        $this->ebr_id = $ebr_id;
    }

    public function build()
    {
        $ebrId = $this->ebr_id;
        // Base query con el join fijo
        $query = DB::table('ebr_clients')
            ->join('ebr_operations', function($q) use ($ebrId) {
                $q->on('ebr_clients.ebr_id', '=', 'ebr_operations.ebr_id')
                    ->on('ebr_clients.id_usuario_cliente', '=', 'ebr_operations.id_usuario_cliente_que_realizo_operacion')
                    ->where('ebr_operations.ebr_id', '=', $ebrId);
        });

        // SELECTs dinámicos
        if (!empty($this->data['selects'])) {
            $selects = [];
            foreach ($this->data['selects'] as $select) {
                $selects[] = $this->buildSelect($select);
            }
            $query->selectRaw(implode(', ', $selects));
        }

        // WHEREs dinámicos
        if (!empty($this->data['wheres'])) {
            foreach ($this->data['wheres'] as $where) {
                $query->where($where['column'], $where['operator'], $where['value']);
            }
        }

        // GROUP BY dinámico
        if (!empty($this->data['group_by'])) {
            $query->groupBy($this->data['group_by']);
        }

        // ORDER BY dinámico
        if (!empty($this->data['orders'])) {
            foreach ($this->data['orders'] as $order) {
                $query->orderBy($order['column'], $order['order']);
            }
        }

        return $query;
    }

    protected function buildSelect(array $select)
    {
        $type = strtolower($select['type']);
        $value = $select['value'];
        $alias = $select['alias'] ?? $value;

        switch ($type) {
            case 'sum':
                return "SUM($value) AS {$alias}";
            case 'count':
                return "COUNT($value) AS {$alias}";
            case 'count_distinct':
                return "COUNT(DISTINCT $value) AS {$alias}";
            case 'avg':
            case 'average':
                return "AVG($value) AS {$alias}";
            default:
                // En caso de que solo quiera seleccionar una columna sin función
                return "$value AS {$alias}";
        }
    }
}
