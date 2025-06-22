<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EBR extends Model
{
    use HasFactory;

    protected $table = 'ebrs';

    protected $fillable = [
        'id',
        'tenant_id',
        'user_id',
        'file_name_clients',
        'file_name_operations',
        'ebr_type_id'
    ];
}
