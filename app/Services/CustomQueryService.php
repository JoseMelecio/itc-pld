<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CustomQueryService
{
    public function execCustomQuery(array $config)
    {
        // Base de la consulta
        $query = DB::table('ebr_clients');

        // JOIN fijo (ejemplo: ventas -> productos)
        $query->join('productos', 'ventas.producto_id', '=', 'productos.id');

        // SELECT
        if (!empty($config['select'])) {
            $query->selectRaw(implode(', ', $config['select']));
        } else {
            $query->select('*');
        }

        // CONDITIONS
        if (!empty($config['conditions'])) {
            foreach ($config['conditions'] as $cond) {
                $field = $cond['field'] ?? null;
                $operator = strtolower($cond['operator'] ?? '=');
                $value = $cond['value'] ?? null;

                if (!$field || $value === null) {
                    continue;
                }

                switch ($operator) {
                    case 'between':
                        if (is_array($value) && count($value) === 2) {
                            $query->whereBetween($field, $value);
                        }
                        break;
                    case 'in':
                        if (is_array($value)) {
                            $query->whereIn($field, $value);
                        }
                        break;
                    case '>':
                    case '<':
                    case '>=':
                    case '<=':
                    case '=':
                    case '!=':
                        $query->where($field, $operator, $value);
                        break;
                }
            }
        }

        // GROUP BY
        if (!empty($config['group_by'])) {
            $query->groupBy($config['group_by']);
        }

        // ORDER BY
        if (!empty($config['order_by'])) {
            foreach ($config['order_by'] as $order) {
                $query->orderBy($order['field'], $order['direction'] ?? 'asc');
            }
        }

        return $query->get();
    }
}
