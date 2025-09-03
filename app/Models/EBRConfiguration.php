<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRConfiguration extends Model
{
    protected $table = 'ebr_configurations';

    protected $fillable = [
        'tenant_id',
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
}
