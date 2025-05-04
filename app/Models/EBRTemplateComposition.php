<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRTemplateComposition extends Model
{
    protected $fillable = [
        'spreadsheet',
        'label',
        'var_name',
        'rules',
        'type',
    ];

    protected $casts = [
        'rules' => 'array',
    ];

    protected $table = 'ebr_template_compositions';
}
