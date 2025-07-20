<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EBRMatrixQualitativeRisk extends Model
{
    protected $table = 'ebr_matrix_qualitative_risks';

    protected $fillable = [
        'risk_level',
        'percentage',
        'probability',
        'final_value',
        'basis',
        'color',
        'order',
    ];
}
